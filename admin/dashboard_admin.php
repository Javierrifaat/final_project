<?php
session_start();

// Menyertakan file koneksi database
include '../service/database.php';

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="layout/styles.css">
    <title>Admin Dashboard - Event Lomba</title>
</head>

<body>
    <header>
        <div class="container">
            <img src="logo.jpg" alt="logo rsc" class="logo">
            <h1>Admin Dashboard</h1>
            <h3>Selamat datang, Admin <?= $_SESSION["username"] ?></h3>
            <nav>
                <ul>
                    <li><a href="admin_dashboard.php">Dashboard</a></li>
                    <li><a href="#manage-participants">Kelola Peserta</a></li>
                    <li><a href="#validate-payments">Validasi Pembayaran</a></li>
                    <li><a href="../index.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section id="manage-participants">
        <div class="container">
            <h2>Kelola Data Peserta</h2>
            <table border="1" cellpadding="10" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID Peserta</th>
                        <th>Nama</th>
                        <th>NIM</th>
                        <th>Prodi<d/th>
                        <th>Fakultas</th>
                        <th>Email</th>
                        <th>No. Telepon</th>
                        <th>Status Pembayaran</th>
                        <th>Ceklis</th>
                      
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Contoh koneksi database (ganti dengan koneksi Anda)
                    $conn = new mysqli('localhost', 'root', '', 'fp');
                    
                    if ($conn->connect_error) {
                        die("Koneksi database gagal: " . $conn->connect_error);
                    }

                    // Ambil data peserta dari database
                    $result = $conn->query("SELECT * FROM users");
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
                                <td>{$row['ceklis']}</td>
                                <td>
                                    <a href='edit_participant.php?id={$row['id_peserta']}'>Edit</a> |
                                    <a href='delete_participant.php?id={$row['id_peserta']}' onclick='return confirm(\"Yakin ingin menghapus?\");'>Hapus</a>
                                </td>
                            </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>

    <div class="container">
            <h3>Kelola Data Peserta Badminton</h3>
            <table border="1" cellpadding="10" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID Peserta</th>
                        <th>Nama</th>
                        <th>Nama Peserta 2</th>
                        <th>NIM</th>
                        <th>NIM Peserta 2</th>
                        <th>Prodi<d/th>
                        <th>Fakultas<d/th>
                        <th>Tahun Angkatan<d/th>
                        <th>Email<d/th>
                        <th>NO.Telp<d/th>
                        <th>Cabang Perlombaan<d/th>
                        <th>Status Pembayaran</th>
                        <th>Ceklis</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Contoh koneksi database (ganti dengan koneksi Anda)
                    $conn = new mysqli('localhost', 'root', '', 'fp');
                    
                    if ($conn->connect_error) {
                        die("Koneksi database gagal: " . $conn->connect_error);
                    }

                    // Ambil data peserta dari database
                    $result = $conn->query("SELECT * FROM badminton");
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                               <td>{$row['id_peserta']}</td>
                                <td>{$row['nama']}</td>
                                <td>{$row['nama_peserta_2']}</td>
                                <td>{$row['nim']}</td>
                                <td>{$row['nim_peserta_2']}</td>
                                <td>{$row['prodi']}</td>
                                <td>{$row['fakultas']}</td>
                                <td>{$row['tahun_angkatan']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['no.telepon']}</td>
                                <td>{$row['cabang_perlombaan']}</td>
                                <td>{$row['status_pembayaran']}</td>
                                <td>{$row['ceklis']}</td>
                                <td>
                                    <a href='edit_participant.php?id={$row['id_peserta']}'>Edit</a> |
                                    <a href='delete_participant.php?id={$row['id_peserta']}' onclick='return confirm(\"Yakin ingin menghapus?\");'>Hapus</a>
                                </td>
                            </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>

    <div class="container">
            <h4>Kelola Data Peserta Catur</h4>
            <table border="1" cellpadding="10" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID Peserta</th>
                        <th>Nama</th>
                        <th>NIM</th>
                        <th>Prodi<d/th>
                        <th>Fakultas<d/th>
                        <th>Tahun Angkatan<d/th>
                        <th>Email<d/th>
                        <th>NO.Telp<d/th>
                        <th>Cabang Perlombaan<d/th>
                        <th>Status Pembayaran</th>
                        <th>Ceklis</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Contoh koneksi database (ganti dengan koneksi Anda)
                    $conn = new mysqli('localhost', 'root', '', 'fp');
                    
                    if ($conn->connect_error) {
                        die("Koneksi database gagal: " . $conn->connect_error);
                    }

                    // Ambil data peserta dari database
                    $result = $conn->query("SELECT * FROM catur");
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                               <td>{$row['id_peserta']}</td>
                                <td>{$row['nama']}</td>
                                <td>{$row['nim']}</td>
                                <td>{$row['prodi']}</td>
                                <td>{$row['fakultas']}</td>
                                <td>{$row['tahun_angkatan']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['no.telepon']}</td>
                                <td>{$row['cabang_perlombaan']}</td>
                                <td>{$row['status_pembayaran']}</td>
                                <td>{$row['ceklis']}</td>
                                <td>
                                    <a href='edit_participant.php?id={$row['id_peserta']}'>Edit</a> |
                                    <a href='delete_participant.php?id={$row['id_peserta']}' onclick='return confirm(\"Yakin ingin menghapus?\");'>Hapus</a>
                                </td>
                            </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>

    <div class="container">
            <h5>Kelola Data Peserta Futsal</h5>
            <table border="1" cellpadding="10" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID Peserta</th>
                        <th>Nama</th>
                        <th>NIM</th>
                        <th>Prodi<d/th>
                        <th>Fakultas<d/th>
                        <th>Tahun Angkatan<d/th>
                        <th>Email<d/th>
                        <th>NO.Telp<d/th>
                        <th>Cabang Perlombaan<d/th>
                        <th>Status Pembayaran</th>
                        <th>Ceklis</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Contoh koneksi database (ganti dengan koneksi Anda)
                    $conn = new mysqli('localhost', 'root', '', 'fp');
                    
                    if ($conn->connect_error) {
                        die("Koneksi database gagal: " . $conn->connect_error);
                    }

                    // Ambil data peserta dari database
                    $result = $conn->query("SELECT * FROM futsal");
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                               <td>{$row['id_peserta']}</td>
                                <td>{$row['nama']}</td>
                                <td>{$row['nim']}</td>
                                <td>{$row['prodi']}</td>
                                <td>{$row['fakultas']}</td>
                                <td>{$row['tahun_angkatan']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['no.telepon']}</td>
                                <td>{$row['cabang_perlombaan']}</td>
                                <td>{$row['status_pembayaran']}</td>
                                <td>{$row['ceklis']}</td>
                                <td>
                                    <a href='edit_participant.php?id={$row['id_peserta']}'>Edit</a> |
                                    <a href='delete_participant.php?id={$row['id_peserta']}' onclick='return confirm(\"Yakin ingin menghapus?\");'>Hapus</a>
                                </td>
                            </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>

    <div class="container">
            <h6>Kelola Data Peserta e-Football</h6>
            <table border="1" cellpadding="10" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID Peserta</th>
                        <th>Nama</th>
                        <th>NIM</th>
                        <th>Prodi<d/th>
                        <th>Fakultas<d/th>
                        <th>Tahun Angkatan<d/th>
                        <th>Email<d/th>
                        <th>NO.Telp<d/th>
                        <th>Cabang Perlombaan<d/th>
                        <th>Status Pembayaran</th>
                        <th>Ceklis</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Contoh koneksi database (ganti dengan koneksi Anda)
                    $conn = new mysqli('localhost', 'root', '', 'fp');
                    
                    if ($conn->connect_error) {
                        die("Koneksi database gagal: " . $conn->connect_error);
                    }

                    // Ambil data peserta dari database
                    $result = $conn->query("SELECT * FROM efootball");
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                               <td>{$row['id_peserta']}</td>
                                <td>{$row['nama']}</td>
                                <td>{$row['nim']}</td>
                                <td>{$row['prodi']}</td>
                                <td>{$row['fakultas']}</td>
                                <td>{$row['tahun angkatan']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['no.telepon']}</td>
                                <td>{$row['cabang_perlombaan']}</td>
                                <td>{$row['status_pembayaran']}</td>
                                <td>{$row['ceklis']}</td>
                                <td>
                                    <a href='edit_participant.php?id={$row['id_peserta']}'>Edit</a> |
                                    <a href='delete_participant.php?id={$row['id_peserta']}' onclick='return confirm(\"Yakin ingin menghapus?\");'>Hapus</a>
                                </td>
                            </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>&copy; 2024 Rektor Sport Championship</p>
        </div>
    </footer>
</body>
</html>