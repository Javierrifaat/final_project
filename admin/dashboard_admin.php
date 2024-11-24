<?php
session_start();
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: ../index.php");
    exit();
}

// Koneksi ke database
$conn = new mysqli('localhost', 'root', '', 'fp');
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Menghitung total peserta dari masing-masing kategori
$total_profiles_query = "SELECT COUNT(*) AS total FROM peserta";
$total_badminton_query = "SELECT COUNT(*) AS total FROM tlb";
$total_chess_query = "SELECT COUNT(*) AS total FROM tlc";
$total_futsal_query = "SELECT COUNT(*) AS total FROM tlf";
$total_efootball_query = "SELECT COUNT(*) AS total FROM tle";

$total_profiles_result = $conn->query($total_profiles_query);
$total_badminton_result = $conn->query($total_badminton_query);
$total_chess_result = $conn->query($total_chess_query);
$total_futsal_result = $conn->query($total_futsal_query);
$total_efootball_result = $conn->query($total_efootball_query);

$total_profiles = $total_profiles_result->fetch_assoc()['total'];
$total_badminton = $total_badminton_result->fetch_assoc()['total'];
$total_chess = $total_chess_result->fetch_assoc()['total'];
$total_futsal = $total_futsal_result->fetch_assoc()['total'];
$total_efootball = $total_efootball_result->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        h1, h2, h3, p {
            font-family: 'Roboto', sans-serif;
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
        .card-title {
            font-size: 14px; /* Ukuran teks diperbesar agar tetap jelas */
        }
        .card {
            padding: 10px; /* Memberikan padding kecil agar lebih ringkas */
        }
    table {
        font-size: 12px; /* Ukuran font kecil */
        border-collapse: collapse;
    }
    table th, table td {
        padding: 5px; /* Padding kecil */
        text-align: center;
        vertical-align: middle;
    }
    table th {
        background-color: #f1f1f1;
    }
    .table-responsive {
        overflow-x: auto;
    }
    .table thead th {
        font-weight: bold;
        font-size: 13px;
    }
    .table tbody td {
        font-size: 12px;
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
                <li><a href="#manage-participants">Kelola Peserta</a></li>
                <li><a href="../index.php">Logout</a></li>
            </ul>
        </nav>
    </div>
</header>

<div class="container mt-4">

    <!-- Tabel Total Data Peserta -->
    <div class="row justify-content-center mb-4">
        <div class="col-md-6">
            <div class="card text-white bg-info text-center">
                <div class="card-header">Total Data Peserta</div>
                <div class="card-body">
                    <h5 class="card-title"><?= $total_profiles ?> Peserta</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Menampilkan total kategori lainnya -->
    <div class="row">
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Total Badminton</div>
                <div class="card-body">
                    <h5 class="card-title"><?= $total_badminton ?> Peserta</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Total Catur</div>
                <div class="card-body">
                    <h5 class="card-title"><?= $total_chess ?> Peserta</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger mb-3">
                <div class="card-header">Total Futsal</div>
                <div class="card-body">
                    <h5 class="card-title"><?= $total_futsal ?> Peserta</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Total Efootball</div>
                <div class="card-body">
                    <h5 class="card-title"><?= $total_efootball ?> Peserta</h5>
                </div>
            </div>
        </div>
    </div>

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
        <h2 class="mb-3" style="font-size: 28px;">Data Peserta</h2>
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
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM peserta");
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id_peserta']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['whatsapp']}</td>
                        <td>{$row['nama']}</td>
                        <td>{$row['nim']}</td>
                        <td>{$row['prodi']}</td>
                        <td>{$row['fakultas']}</td>
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
                <th>Nama 1</th>
                <th>Nama 2</th>
                <th>NIM 1</th>
                <th>NIM 2</th>
                <th>Prodi</th>
                <th>Fakultas</th>
                <th>Angkatan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM tlb");
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['whatsapp']}</td>
                    <td>{$row['nama1']}</td>
                    <td>{$row['nama2']}</td>
                    <td>{$row['nim1']}</td>
                    <td>{$row['nim2']}</td>
                    <td>{$row['prodi']}</td>
                    <td>{$row['fakultas']}</td>
                    <td>{$row['angkatan']}</td>
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
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM tlc"); // ganti tlc sesuai nama tabel data catur
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
                <th>ID</th>
                <th>Email</th>
                <th>Whatsapp</th>
                <th>Nama Tim</th>
                <th>Nama Ketua</th>
                <th>NIM Ketua</th>
                <th>Prodi</th>
                <th>Fakultas</th>
                <th>Angkatan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM tlf"); // ganti tlf sesuai nama tabel data futsal
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['whatsapp']}</td>
                    <td>{$row['nama_tim']}</td>
                    <td>{$row['nama_ketua']}</td>
                    <td>{$row['nim_ketua']}</td>
                    <td>{$row['prodi']}</td>
                    <td>{$row['fakultas']}</td>
                    <td>{$row['angkatan']}</td>
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
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM tle"); // ganti tle sesuai nama tabel data eFootball
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