<?php
session_start();
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Link Google Fonts untuk Poppins dan Roboto -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        /* Menggunakan font Poppins untuk body */
        body {
            font-family: 'Poppins', sans-serif;
        }
        h1, h2, h3, p {
            font-family: 'Roboto', sans-serif;  /* Menggunakan font Roboto untuk heading dan paragraf */
        }
        .logo {
            max-width: 80px;
            height: auto;
            margin-right: 15px;
        }
        header {
            background-color: #007bff;
            color: white;
            padding: 15px 0;
        }
        header .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 15px;
        }
        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
        footer {
            background-color: #f8f9fa;
            text-align: center;
            padding: 15px 0;
            margin-top: 20px;
        }
    </style>
    <title>Admin Dashboard - Event Lomba</title>
</head>
<body>

<header>
    <div class="container">
        <div class="d-flex align-items-center">
            <img src="../layout/logo.jpg" alt="Logo RSC" class="logo">
            <div>
                <h1>Admin Dashboard</h1>
                <p class="mb-0">Selamat datang, Admin <?= $_SESSION["username"] ?></p>
            </div>
        </div>
        <nav>
            <ul class="d-flex">
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="#manage-participants">Kelola Peserta</a></li>
                <li><a href="#validate-payments">Validasi Pembayaran</a></li>
                <li><a href="../index.php">Logout</a></li>
            </ul>
        </nav>
    </div>
</header>

<div class="container mt-4">

<!-- Formulir Pencarian dan Penyaringan -->
<form method="GET" action="" class="mb-3">
    <div class="d-flex gap-3">
        <!-- Pencarian berdasarkan Nama atau NIM -->
        <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan Nama atau NIM" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">

        <!-- Filter Status Pembayaran -->
        <select name="status" class="form-control">
            <option value="">-- Pilih Status Pembayaran --</option>
            <option value="Lunas" <?= isset($_GET['status']) && $_GET['status'] == 'Lunas' ? 'selected' : '' ?>>Lunas</option>
            <option value="Belum Lunas" <?= isset($_GET['status']) && $_GET['status'] == 'Belum Lunas' ? 'selected' : '' ?>>Belum Lunas</option>
        </select>

        <!-- Filter Cabang Perlombaan -->
        <select name="cabang" class="form-control">
            <option value="">-- Pilih Cabang Perlombaan --</option>
            <option value="Badminton" <?= isset($_GET['cabang']) && $_GET['cabang'] == 'Badminton' ? 'selected' : '' ?>>Badminton</option>
            <option value="Catur" <?= isset($_GET['cabang']) && $_GET['cabang'] == 'Catur' ? 'selected' : '' ?>>Catur</option>
            <option value="Futsal" <?= isset($_GET['cabang']) && $_GET['cabang'] == 'Futsal' ? 'selected' : '' ?>>Futsal</option>
            <option value="Efootball" <?= isset($_GET['cabang']) && $_GET['cabang'] == 'Efootball' ? 'selected' : '' ?>>Efootball</option>
        </select>

        <!-- Tombol Submit -->
        <button type="submit" class="btn btn-primary">Cari</button>
    </div>
</form>

<div class="container mt-4">
    <section id="manage-participants">
        <h2 class="mb-3" style="font-size: 16px;">Profile</h2>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead class="table-primary text-center">
                    <tr>
                        <th>ID Peserta</th>
                        <th>Nama</th>
                        <th>NIM</th>
                        <th>Prodi</th>
                        <th>Fakultas</th>
                        <th>Email</th>
                        <th>No. Telepon</th>
                        <th>Status Pembayaran</th>
                        <th>Ceklis</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $conn = new mysqli('localhost', 'root', '', 'fp');
                    if ($conn->connect_error) {
                        die("Koneksi database gagal: " . $conn->connect_error);
                    }
                    $result = $conn->query("SELECT * FROM peserta");
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id_peserta']}</td>
                                <td>{$row['nama']}</td>
                                <td>{$row['nim']}</td>
                                <td>{$row['prodi']}</td>
                                <td>{$row['fakultas']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['no.telepon']}</td>
                                <td>{$row['status_pembayaran']}</td>
                                <td>
                                    <a href='edit_participant.php?id={$row['id_peserta']}' class='btn btn-warning btn-sm'>Edit</a>
                                    <a href='delete_participant.php?id={$row['id_peserta']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin ingin menghapus?\");'>Hapus</a>
                                </td>
                            </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>

<!-- Data Peserta Badminton -->
<section id="manage-participants">
    <h3 class="mb-3">Data Peserta Badminton</h3>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead class="table-primary text-center">
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Whatsapp</th>
                    <th>Nama</th>
                    <th>Nama Peserta 2</th>
                    <th>NIM</th>
                    <th>NIM Peserta 2</th>
                    <th>Prodi</th>
                    <th>Fakultas</th>
                    <th>Angkatan</th>
                    <th>Cereated_at</th>
                    <th>Biaya</th>
                    <th>Order_id</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Ambil data peserta badminton dari database
                $result = $conn->query("SELECT * FROM tlb");
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                           <td>{$row['id']}</td>
                           <td>{$row['email']}</td>
                           <td>{$row['whatsapp']}</td>
                           <td>{$row['nama']}</td>
                           <td>{$row['nama_peserta_2']}</td>
                           <td>{$row['nim']}</td>
                           <td>{$row['nim_peserta_2']}</td>
                           <td>{$row['prodi']}</td>
                           <td>{$row['fakultas']}</td>
                           <td>{$row['angkatan']}</td>
                           <td>{$row['created_at']}</td>
                           <td>{$row['biaya']}</td>
                           <td>{$row['order_id']}</td>
                           <td>
                               <a href='edit_participant.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                               <a href='delete_participant.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin ingin menghapus?\");'>Hapus</a>
                           </td>
                       </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Data Peserta Catur -->
<section id="manage-participants">
    <h3 class="mb-3">Data Peserta Catur</h3>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead class="table-primary text-center">
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Whatsapp</th>
                    <th>Nama</th>
                    <th>NIM</th>
                    <th>Prodi</th>
                    <th>Fakultas</th>
                    <th>Angkatan</th>
                    <th>Biaya</th>
                    <th>Order_id_Catur</th>
                    <th>Created_at</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Ambil data peserta catur dari database
                $result = $conn->query("SELECT * FROM tlc");
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                           <td>{$row['id']}</td>
                           <td>{$row['email']}</td>
                           <td>{$row['whatsapp']}</td>
                           <td>{$row['nama']}</td>
                           <td>{$row['nim']}</td>
                           <td>{$row['prodi']}</td>
                           <td>{$row['fakultas']}</td>
                           <td>{$row['angkatan']}</td>
                           <td>{$row['biaya']}</td>
                           <td>{$row['order_id_catur']}</td>
                           <td>{$row['created_at']}</td>
                           <td>
                               <a href='edit_participant.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                               <a href='delete_participant.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin ingin menghapus?\");'>Hapus</a>
                           </td>
                       </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Data Peserta Futsal -->
<section id="manage-participants">
    <h3 class="mb-3">Data Peserta Futsal</h3>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead class="table-primary text-center">
                <tr>
                    <th>ID Peserta</th>
                    <th>Nama</th>
                    <th>NIM</th>
                    <th>Prodi</th>
                    <th>Fakultas</th>
                    <th>Email</th>
                    <th>No. Telepon</th>
                    <th>Status Pembayaran</th>
                    <th>Ceklis</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Ambil data peserta futsal dari database
                $result = $conn->query("SELECT * FROM futsal");
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                           <td>{$row['id_peserta']}</td>
                           <td>{$row['nama']}</td>
                           <td>{$row['nim']}</td>
                           <td>{$row['prodi']}</td>
                           <td>{$row['fakultas']}</td>
                           <td>{$row['email']}</td>
                           <td>{$row['no_telpon']}</td>
                           <td>{$row['status_pembayaran']}</td>
                           <td>
                               <a href='edit_participant.php?id={$row['id_peserta']}' class='btn btn-warning btn-sm'>Edit</a>
                               <a href='delete_participant.php?id={$row['id_peserta']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin ingin menghapus?\");'>Hapus</a>
                           </td>
                       </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Data Peserta Efootball -->
<section id="manage-participants">
    <h3 class="mb-3">Data Peserta Efootball</h3>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead class="table-primary text-center">
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Whatsapp</th>
                    <th>Nama</th>
                    <th>NIM</th>
                    <th>Prodi</th>
                    <th>Fakultas</th>
                    <th>Angkatan</th>
                    <th>Biaya</th>
                    <th>Order_id_efootball</th>
                    <th>Cereated_at</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Ambil data peserta efootball dari database
                $result = $conn->query("SELECT * FROM tle");
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                       <td>{$row['id']}</td>
                           <td>{$row['email']}</td>
                           <td>{$row['whatsapp']}</td>
                           <td>{$row['nama']}</td>
                           <td>{$row['nim']}</td>
                           <td>{$row['prodi']}</td>
                           <td>{$row['fakultas']}</td>
                           <td>{$row['angkatan']}</td>
                           <td>{$row['biaya']}</td>
                           <td>{$row['order_id_efootball']}</td>
                           <td>{$row['created_at']}</td>
                           <td>
                               <a href='edit_participant.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                               <a href='delete_participant.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin ingin menghapus?\");'>Hapus</a>
                           </td>
                       </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</section>

<footer>
    <p>&copy; 2024 Event Lomba | All rights reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>