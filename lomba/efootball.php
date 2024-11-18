<?php
session_start();
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header('Location: ../index.php');
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
                    <form id="logout-form" action="dashboard.php" method="POST">
                        <input type="hidden" name="logout" value="1">
                        <button type="submit" class="btn btn-danger">Logout</button>
                    </form>
                </div>
            </div>
            <form id="logout-form" action="../index.php" method="POST" style="display: none;">
                <input type="hidden" name="logout" value="1">
            </form>
            </div>
        </div>
    </nav>

    <h1>Formulir Pendaftaran E Football</h1>

    <form>
        <label for="email">Email Anda:</label>
        <input type="email" id="email" name="email" required> <br>

        <label for="whatsapp">No Whatsapp:</label>
        <input type="text" id="whatsapp" name="whatsapp" required><br>

        <label for="nama">Nama Peserta:</label>
        <input type="text" id="nama" name="nama" required><br>

        <label for="nim">Nomor Induk Mahasiswa:</label>
        <input type="text" id="nim" name="nim" required><br>

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

        <label for="bukti_pembayaran">Bukti Pembayaran:</label>
        <input type="file" id="bukti_pembayaran" name="bukti_pembayaran" required> <br>

        <button type="submit">Kirim</button>
    </form>
</body>

</html>
</body>

</html>