<?php
session_start();
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header('Location: ../index.php'); // Pastikan ini mengarah ke index.php di luar folder lomba
    exit(); // Pastikan untuk menghentikan eksekusi skrip setelah pengalihan
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

<form>
  <label for="email">Email:</label>
  <input type="email" id="email" name="email" required> <br>

  <label for="nama_team">Nama Tim:</label>
  <input type="text" id="nama_team" name="nama_team" required> <br>

  <label for="bukti">Bukti Kartu Tanda Mahasiswa (KTM)/SIPT Bukti Mahasiswa Aktif & FOTO 4x6 (Semua Anggota Team):</label> <br>
  <input type="file" id="bukti" name="bukti[]" multiple accept=".pdf,.jpg,.jpeg,.png" required>
  <p>Upload maksimal 10 file yang didukung: PDF atau image. Maks 10 MB per file.</p> <br>

  <p>Contoh Pengumpulan Bukti KTM & Foto Anggota Team (Dalam Satu File):</p>
  <img src="../layout/contoh_tim.jpg" alt="Contoh Bukti"><br>

  <button type="submit">Kirim</button>
</form>

</html>
