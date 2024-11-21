<?php
session_start();

// Menyertakan file koneksi database dan Midtrans
include '../service/database.php';
require_once '../payment/midtrans-php-master/Midtrans.php';

// Konfigurasi Midtrans
\Midtrans\Config::$serverKey = 'SB-Mid-server-SdGSNrMDhqUgP4KJM_0hTR3O';
\Midtrans\Config::$isProduction = false; // Gunakan sandbox mode untuk testing
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari formulir
    $email = $_POST['email'];
    $whatsapp = $_POST['whatsapp'];
    $nama = $_POST['nama'];
    $nim = $_POST['nim'];
    $prodi = $_POST['prodi'];
    $fakultas = $_POST['fakultas'];
    $angkatan = $_POST['angkatan'];

    // Data pembayaran
    $biaya_pendaftaran = 50000; // Biaya pendaftaran untuk efootball
    $order_id = uniqid("efootball_"); // ID transaksi unik

    // Simpan data ke database terlebih dahulu dengan status_pembayaran 'pending'
    $sql = "INSERT INTO tle (email, whatsapp, nama, nim, prodi, fakultas, angkatan, biaya, order_id, status_pembayaran, created_at)
        VALUES ('$email', '$whatsapp', '$nama', '$nim', '$prodi', '$fakultas', '$angkatan', '$biaya_pendaftaran', '$order_id', 'pending', NOW())";

    if (mysqli_query($db, $sql)) {
        // Jika data berhasil disimpan, buat token pembayaran Midtrans
        $transaction_details = [
            'order_id' => $order_id,
            'gross_amount' => $biaya_pendaftaran, // Total biaya
        ];

        $item_details = [
            [
                'id' => 'efootball_fee',
                'price' => $biaya_pendaftaran,
                'quantity' => 1,
                'name' => "Pendaftaran efootball",
            ]
        ];

        $customer_details = [
            'first_name' => $nama,
            'email' => $email,
            'phone' => $whatsapp,
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

// Tutup koneksi database
mysqli_close($db);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../layout/logo.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="layout/style.css">
    <title>RSC - PENDAFTARAN EFOOTBALL</title>
    <style>
        body {
 background-color: #f8f9fa;
        }
        .container {
            max-width: 1000px;
            margin: auto;
            margin-top: 20px;
        }

        .form-container {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-weight: bold;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
    </style>
</head>

<body class="bg-primary">
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
                <div class="d-flex ms-auto">
                    <form id="logout-form" action="../index.php" method="POST">
                        <input type="hidden" name="logout" value="1">
                        <button type="submit" class="btn btn-danger">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="form-container">
                    <h1>Formulir Pendaftaran efootball</h1>
                    <form action="efootball.php" method="POST" enctype="multipart/form-data">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Anda:</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="col-md-6">
                                <label for="whatsapp" class="form-label">No Whatsapp:</label>
                                <input type="text" class="form-control" id="whatsapp" name="whatsapp" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nama" class="form-label">Nama Peserta :</label>
                                <input type="text" class="form-control" id="nama" name="nama" required>
                            </div>
                            <div class="col-md-6">
                                <label for="nim" class="form-label">Nomor Induk Mahasiswa :</label>
                                <input type="text" class="form-control" id="nim" name="nim" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="prodi" class="form-label">Prodi:</label>
                                <select class="form-select" name="prodi" id="prodi">
                                    <option value="Prodi">--Pilih Prodi--</option>
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
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="fakultas" class="form-label">Asal Fakultas:</label>
                                <input type="text" class="form-control" id="fakultas" name="fakultas" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="angkatan" class="form-label">Tahun Angkatan:</label>
                                <select class="form-select" name="angkatan" id="angkatan">
                                    <option value="Angkatan">--Pilih Angkatan--</option>
                                    <option value="2021">2021</option>
                                    <option value="2022">2022</option>
                                    <option value="2023">2023</option>
                                    <option value="2024">2024</option>
                                </select>
                            </div>
                        </div>    
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-container event-info">
                    <img src="../layout/efootball.JPG" alt="Catur" class="img-fluid">
                    <h5 class="mt-3">efootball</h5>
                    <p>
                        <i class="bi bi-calendar"></i> Sat, 30 Nov 2024 <br>
                        <i class="bi bi-clock"></i> 09:00 WIB <br>
                        <i class="bi bi-geo-alt"></i> Karawang, Indonesia
                    </p>
                    <hr>
                    <div class="event-rules mb-3">
                        <p><strong>Rules:</strong></p>
                        <ul>
                            <li>Perlombaan hanya untuk mahasiswa.</li>
                            <li>Setiap peserta wajib mendaftar secara online.</li>
                            <li>Pendaftaran ditutup pada 28 November 2024.</li>
                        </ul>
                    </div>
                    <hr>
                    <h6>Data Pembayaran</h6>
                    <p>
                        Jenis Perlombaan: <span class="float-end">efootball</span><br>
                        Biaya Pendaftaran: <span class="float-end">Rp.50.0000</span><br>
                        <hr>
                        <strong>Total: <span class="float-end">Rp.50.0000</span></strong>
                    </p>
                    <button class="btn btn-primary w-100">Proses ke Pembayaran</button>
                </div>
            </div>
        </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-x4dVbZX3EJfXb0wrbkv0OUx+Gy4IS4JgtMlEKtx79A" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-OB3BdNa1ahWOsHk9hr+hbVoJ8y+qElvc99c1nljIC9z8KnOerqqRvlp4LIG7WIEm" crossorigin="anonymous"></script>
</body>
</html>