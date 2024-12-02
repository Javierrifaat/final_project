<?php
session_start();
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: ../index.php");
    exit();
}

// Koneksi ke database
$conn = new mysqli('localhost', 'root', '', 'fpp');
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Menghitung total data pengguna
$total_users_query = "SELECT COUNT(*) AS total FROM users";
$total_users_result = $conn->query($total_users_query);
$total_users = $total_users_result->fetch_assoc()['total'];

// Pagination logic
$limit = 10; // Max rows per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Data pengguna untuk tabel
$users_query = "SELECT * FROM users LIMIT $start, $limit";
$users_result = $conn->query($users_query);

// Cek jika query berhasil
if (!$users_result) {
    die("Query gagal: " . $conn->error);
}

// Total pages calculation
$total_pages_users = ceil($total_users / $limit);

// Ambil daftar event/lomba
$events_query = "SELECT * FROM event";
$events_result = $conn->query($events_query);
?>

<!DOCTYPE html>
<html lang="en">

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        h1, h2, h3, p {
            font-family: 'Roboto', sans-serif;
        }

        .logo {
            max-width: 50px; /* Ukuran logo yang lebih kecil */
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

        table {
            font-size: 12px;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 5px;
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
</head>

<body>
    <header>
        <div class="container">
            <div class="d-flex align-items-center">
                <img src="../layout/logo.jpg" alt="Logo RSC" class="logo">
                <div>
                    <h1>Admin Dashboard</h1>
                    <p class="mb-0">Selamat datang <?= $_SESSION["username"] ?></p>
                </div>
            </div>
            <nav>
    <ul class="d-flex">
        <li class="nav-item">
            <a class="nav-link" href="edit-dashboard.php">Kelola Perlombaan</a>
        </li>
        <li><a class="logout-btn" href="#" onclick="document.getElementById('logout-form').submit();">Logout</a></li>
    </ul>
</nav>

<!-- Form Logout -->
<form id="logout-form" action="" method="POST" style="display: none;">
    <input type="hidden" name="logout" value="1">
</form>

<!-- ... kode sebelumnya tetap sama ... -->

<style>
    /* ... kode CSS sebelumnya tetap sama ... */

    /* Gaya untuk tombol logout */
    .logout-btn {
        color: white;
        background-color: red; /* Warna merah */
        padding: 10px 15px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
        transition: background-color 0.3s ease, transform 0.3s ease;
    }
    .logout-btn:hover {
        background-color: darkred; /* Warna lebih gelap saat hover */
        transform: scale(1.05); /* Efek zoom saat hover */
    }

    /* Gaya untuk kartu */
    .hover-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;
        cursor: pointer;
        border-radius: 10px; /* Rounded corners */
    }
    .hover-card:hover {
        transform: translateY(-10px); /* Sedikit lebih tinggi saat hover */
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2); /* Bayangan lebih dalam */
        background-color: #e7f3ff; /* Warna latar belakang lebih cerah saat hover */
    }

    /* ... kode CSS lainnya tetap sama ... */
</style>
        </div>
    </header>


    <div class="container mt-4">
    <!-- Tabel Total Data Peserta -->
    <div class="row justify-content-center mb-4">
        <div class="col-md-6">
            <div class="card text-white bg-info text-center">
                <div class="card-header">Total User</div>
                <div class="card-body">
                    <h5 class="card-title"><?= $total_users ?> Users </h5>
                </div>
            </div>
        </div>
    </div>

           <!-- Card Profile -->
    <div class="row justify-content-center mb-4">
        <div class="col-md-3 mb-4">
            <div class="card shadow border-0 rounded-3 hover-card">
                <div class="card-body text-center">
                    <!-- Ikon Profile -->
                    <i class="fas fa-users text-primary mb-3" style="font-size: 40px;"></i>
                    <h5 class="card-title text-dark">Data Peserta</h5>
                    <p class="card-text text-muted">Data dari semua Peserta</p>
                    <a href="tables/table_users.php" class="btn btn-primary w-100">Manage Peserta</a>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="container mt-4">
    <!-- Tabel Daftar Lomba -->
    <h2>Daftar Lomba</h2>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-primary">
                <tr>
                    <th>Event Name</th>
                    <th>Event Date</th>
                    <th>Location</th>
                    <th>Total Participants</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($event = $events_result->fetch_assoc()) : ?>
                    <?php 
                        // Hitung jumlah peserta terdaftar untuk lomba ini
                        $participants_query = "SELECT COUNT(*) AS total FROM registrations WHERE event_id = " . $event['event_id'];
                        $participants_result = $conn->query($participants_query);
                        $participants = $participants_result->fetch_assoc()['total'];
                    ?>
                    <tr>
                        <td><?= $event['event_name'] ?></td>
                        <td><?= date('d-m-Y', strtotime($event['event_date'])) ?></td> <!-- Format tanggal -->
                        <td><?= $event['location'] ?></td>
                        <td><?= $participants ?></td>
                        <td>
                            <a href="event-details.php?event_id=<?= $event['event_id'] ?>" class="btn btn-info btn-sm">Lihat Peserta</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

        <footer>
            <p>&copy; 2024 Your Website. All rights reserved.</p>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>