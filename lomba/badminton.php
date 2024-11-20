<?php
session_start();

// Menyertakan file koneksi database dan Midtrans
include '../service/database.php';
require_once '../payment/midtrans-php-master/Midtrans.php'; // Pastikan library Midtrans sudah diunduh dan diinstal

// Konfigurasi Midtrans
\Midtrans\Config::$serverKey = 'SB-Mid-server-SdGSNrMDhqUgP4KJM_0hTR3O';
\Midtrans\Config::$isProduction = false; // Gunakan sandbox mode untuk testing
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;





if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari formulir
    $email = $_POST['email'];
    $whatsapp = $_POST['whatsapp'];
    $nama1 = $_POST['nama1'];
    $nama2 = $_POST['nama2'];
    $nim1 = $_POST['nim1'];
    $nim2 = $_POST['nim2'];
    $prodi = $_POST['prodi'];
    $fakultas = $_POST['fakultas'];
    $angkatan = $_POST['angkatan'];

    // Data pembayaran
    $biaya_pendaftaran = 100000; // Biaya pendaftaran untuk badminton
    $order_id = uniqid("badminton_"); // ID transaksi unik

    // Simpan data ke database terlebih dahulu
    $sql = "INSERT INTO tlb (email, whatsapp, nama, nama_peserta_2, nim, nim_peserta_2, prodi, fakultas, angkatan, biaya, order_id, created_at)
        VALUES ('$email', '$whatsapp', '$nama1', '$nama2', '$nim1', '$nim2', '$prodi', '$fakultas', '$angkatan', '$biaya_pendaftaran', '$order_id', NOW())";



    if (mysqli_query($db, $sql)) {
        // Jika data berhasil disimpan, buat token pembayaran Midtrans
        $transaction_details = [
            'order_id' => $order_id,
            'gross_amount' => $biaya_pendaftaran, // Total biaya
        ];

        $item_details = [
            [
                'id' => 'badminton_fee',
                'price' => $biaya_pendaftaran,
                'quantity' => 1,
                'name' => "Pendaftaran Badminton",
            ]
        ];

        $customer_details = [
            'first_name' => $nama1,
            'last_name' => $nama2,
            'email' => $email,
            'phone' => $whatsapp,
        ];

        $transaction = [
            'transaction_details' => $transaction_details,
            'item_details' => $item_details,
            'customer_details' => $customer_details,
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

// Tutup koneksi database
mysqli_close($db);
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
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <?= $_SESSION["username"] ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
                    </ul>
                </div>
                <form id="logout-form" action="../index.php" method="POST" style="display: none;">
                    <input type="hidden" name="logout" value="1">
                </form>
            </div>
        </div>
    </nav>

    <h1>Formulir Pendaftaran Badminton</h1>
    <form action="badminton.php" method="POST" enctype="multipart/form-data">
        <label for="email">Email Anda:</label>
        <input type="email" id="email" name="email" required> <br>

        <label for="whatsapp">No Whatsapp:</label>
        <input type="text" id="whatsapp" name="whatsapp" required><br>

        <label for="nama1">Nama Peserta 1:</label>
        <input type="text" id="nama1" name="nama1" required><br>

        <label for="nama2">Nama Peserta 2:</label>
        <input type="text" id="nama2" name="nama2" required><br>

        <label for="nim1">Nomor Induk Mahasiswa 1:</label>
        <input type="text" id="nim1" name="nim1" required><br>

        <label for="nim2">Nomor Induk Mahasiswa 2:</label>
        <input type="text" id="nim2" name="nim2" required><br>

        <label for="prodi">Prodi:</label>
        <select name="prodi" id="prodi">
            <option value="akuntansi">Akuntansi</option>
            <option value="manajemen">Manajemen</option>
            <option value="sistem_informasi">Sistem Informasi</option>
            <option value="teknik_informatika">Teknik Informatika</option>
            <option value="teknik_industri">Teknik Industri</option>
            <option value="teknik_mesin">Teknik Mesin</option>
            <option value="hukum">Hukum</option>
            <option value="farmasi">Farmasi</option>
            <option value="psikologi">Psikologi</option>
            <option value="ppkn">PPKn</option>
            <option value="pgsd">PGSD</option>
            <option value="pai">PAI</option>
        </select> <br>

        <label for="fakultas">Asal Fakultas:</label>
        <input type="text" id="fakultas" name="fakultas" required> <br>

        <label for="angkatan">Tahun Angkatan:</label>
        <select name="angkatan" id="angkatan">
            <option value="2021">2021</option>
            <option value="2022">2022</option>
            <option value="2023">2023</option>
            <option value="2024">2024</option>

        </select> <br>

        <button type="submit">Proses Pembayaran</button>
    </form>


</html>