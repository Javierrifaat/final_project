<?php
session_start();
include '../service/database.php';
require_once '../payment/midtrans-php-master/Midtrans.php';

// Konfigurasi Midtrans
\Midtrans\Config::$serverKey = 'SB-Mid-server-SdGSNrMDhqUgP4KJM_0hTR3O';
\Midtrans\Config::$isProduction = false; // Gunakan sandbox mode untuk testing
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header('Location: ../index.php'); // Pastikan ini mengarah ke index.php di luar folder lomba
    exit(); // Pastikan untuk menghentikan eksekusi skrip setelah pengalihan
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $db->real_escape_string($_POST['email']);
    $nama_team = $db->real_escape_string($_POST['nama_team']);
    $created_at = date('Y-m-d H:i:s');
    $order_id = uniqid(); // ID unik untuk Midtrans

    // data pembayara
    $biaya_pendaftaran = 120000; // Biaya pendaftaran untuk futsal
    $order_id = uniqid("futsal_"); // ID transaksi unik

    // Proses upload file
    $uploaded_files = [];
    $target_dir = "../uploads/";
    foreach ($_FILES['bukti']['name'] as $key => $name) {
        $tmp_name = $_FILES['bukti']['tmp_name'][$key];
        $file_size = $_FILES['bukti']['size'][$key];
        $file_type = pathinfo($name, PATHINFO_EXTENSION);
        
        if ($file_size <= 10485760 && in_array($file_type, ['pdf', 'jpg', 'jpeg', 'png'])) {
            $new_name = uniqid() . "_" . basename($name);
            $target_file = $target_dir . $new_name;
            if (move_uploaded_file($tmp_name, $target_file)) {
                $uploaded_files[] = $target_file;
            }
        }
    }

    // Simpan path file ke database (gabungkan menjadi string jika lebih dari satu)
    $bukti_files = implode(',', $uploaded_files);

    // Masukkan data ke tabel
    $sql = "INSERT INTO tlf (email, nama_team, bukti_files, biaya, order_id, status_pembayaran, created_at)
            VALUES ('$email', '$nama_team', '$bukti_files', '$biaya_pendaftaran', '$order_id', 'pending', '$created_at')";

if (mysqli_query($db, $sql)) {
    // Jika data berhasil disimpan, buat token pembayaran Midtrans
    $transaction_details = [
        'order_id' => $order_id,
        'gross_amount' => $biaya_pendaftaran, // Total biaya
    ];

    $item_details = [
        [
            'id' => 'futsal_fee',
            'price' => $biaya_pendaftaran,
            'quantity' => 1,
            'name' => "Pendaftaran futsal",
        ]
    ];

    $customer_details = [
        'first_name' => $nama_team,
        'email' => $email,
        // 'phone' => $whatsapp,
    ];

    $transaction = [
        'transaction_details' => $transaction_details,
        'item_details' => $item_details,
        'customer_details' => $customer_details,
        'finish_redirect_url' => 'https://www.yourwebsite.com/../dashboard.php'
    ];

    try {
        // Buat Snap Token
        $snapToken = \Midtrans\Snap::getSnapToken($transaction);

        // Redirect ke halaman pembayaran Snap
        echo "<html><body>";
        echo "<h3>Mohon tunggu, sedang diarahkan ke halaman pembayaran...</h3>";
        echo "<script src='https://app.sandbox.midtrans.com/snap/snap.js' data-client-key='SB-Mid-client-uw81o6eb7cacAn_V'></script>";
        echo "<script>snap.pay('$snapToken');</script>";
        echo "</body></html>";
        exit;
    } catch (Exception $e) {
        echo "Gagal membuat transaksi. Error: " . $e->getMessage();
    }
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($db);
}
}        



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="layout/logo.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="layout/style.css">
    <title>RSC - HOMEPAGE</title>
</head>

<body class="bg-primary">
    <!-- Nav section start-->
    <nav class="navbar navbar-expand-lg navbar-light bg-transparent">
        <div class="container-fluid">
            <a class="navbar-brand text-light fs-3 fw-bold" href="../dashboard.php">RSC</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link text-light px-3 py-2" aria-current="page" href="../dashboard.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light px-3 py-2" href="../dashboard.php#card-section">Event</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light px-3 py-2" href="../dashboard.php#contact-section">Contact</a>
                    </li>
                </ul>
                <!-- Tombol Logout di pojok kanan atas -->
                <div class="d-flex ms-auto">
                    <form id="logout-form" action="../index.php" method="POST">
                        <input type="hidden" name="logout" value="1">
                        <button type="submit" class="btn btn-danger">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <h1>Formulir Pendaftaran futsal</h1>

<form action="futsal.php" method="POST" enctype="multipart/form-data">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required> <br>

    <label for="nama_team">Nama Tim:</label>
    <input type="text" id="nama_team" name="nama_team" required> <br>

    <label for="bukti">Bukti Kartu Tanda Mahasiswa (KTM)/SIPT Bukti Mahasiswa Aktif & FOTO 4x6 (Semua Anggota Team):</label> <br>
    <input type="file" id="bukti" name="bukti[]" multiple accept=".pdf,.jpg,.jpeg,.png" required>
    <p>Upload maksimal 10 file yang didukung: PDF atau image. Maks 10 MB per file.</p> <br>

    <button type="submit">Kirim</button>
</form>


</html>