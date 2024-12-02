<?php
session_start();

// Logout logic
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit;
}

// Cek apakah pengguna sudah login
if (!isset($_SESSION['is_login'])) {
    header('Location: index.php');
    exit;
} elseif ($_SESSION['is_login'] == false) {
    header('Location: index.php');
    exit;
}

// Ambil user_id dari session
$user_id = $_SESSION['is_login'];

// Menghubungkan ke database
require 'service/database.php';

// Query untuk mendapatkan daftar lomba
$query = "SELECT event_id, event_name, event_description, registration_fee, image_url FROM event";
$result = mysqli_query($db, $query);

$event = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $event[] = $row;
    }
} else {
    echo "Error: " . mysqli_error($db);
}

// Query untuk mengambil profil pengguna
$query = "SELECT profile_picture FROM user_profiles WHERE user_id = ?";
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $profile_picture);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

// Menyimpan foto profil di session
if ($profile_picture) {
    $_SESSION['profile_picture'] = 'uploads/' . $profile_picture;
} else {
    $_SESSION['profile_picture'] = 'layout/profil-default.jpg'; // Gambar default jika tidak ada foto profil
}

mysqli_close($db);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="layout/logo.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="layout/style.css">
    <title>RSC - HOMEPAGE</title>
</head>

<body class="bg-primary">
    <nav class="navbar navbar-expand-lg navbar-light bg-transparent">
        <div class="container-fluid">
            <a class="navbar-brand text-light fs-3 fw-bold" href="">RSC</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link text-light px-3 py-2" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light px-3 py-2" href="#card-section">Event</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light px-3 py-2" href="#contact-section">Contact</a>
                    </li>
                </ul>
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle d-flex align-items-center" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?= $_SESSION['profile_picture'] ?>" alt="Profile Picture" class="img-thumbnail" style="width: 30px; height: 30px; margin-right: 10px;">
                        <span><?= $_SESSION['username'] ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" href="profile/profile.php">Profile</a></li>
                        <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
                    </ul>
                </div>

                <form id="logout-form" method="POST" style="display: none;">
                    <input type="hidden" name="logout" value="true">
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="text-center">
            <h1 class="display-4 fw-bold text-light">Selamat Datang, <?= $_SESSION['username'] ?>!</h1>
            <p class="lead text-light mt-3">Kompetisi olahraga tahunan yang penuh semangat sportivitas dan persahabatan.</p>
        </div>

        <section id="card-section" class="mt-5">
            <div class="row">
                <?php if (!empty($event)): ?>
                    <?php foreach ($event as $event): ?>
                        <div class="col-md-4">
                            <div class="card mt-4">
                            <?php if (!empty($event['image_url'])): ?>
    <img src="<?= htmlspecialchars('uploads/' . $event['image_url']) ?>" alt="Event Image" class="img-thumbnail" style="width: 100%; height: 300px; object-fit: cover;">
<?php endif; ?>                 
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($event['event_name']) ?></h5>
                                    <p class="card-text"><?= htmlspecialchars($event['event_description']) ?></p>
                                    <p class="card-text">Rp<?= number_format($event['registration_fee'], 0, ',', '.') ?></p>
                                    <a href="pendaftaran.php?event_id=<?= $event['event_id'] ?>" class="btn btn-primary">Daftar</a>

                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center text-light">Belum ada lomba yang tersedia.</p>
                <?php endif; ?>
            </div>
        </section>
    </div>

    <?php include "layout/footer.html"; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
