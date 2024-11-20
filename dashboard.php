<?php
session_start();
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header('Location: index.php');
}
if (!isset($_SESSION["is_login"])) {
    echo "You must login";
    die;
} elseif ($_SESSION["is_login"] == false) {
    echo "You must login";
    die;
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
            <a class="navbar-brand text-light fs-3 fw-bold" href="">RSC</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link text-light px-3 py-2" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light px-3 py-2" href="#card-section">Event</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light px-3 py-2" href="#contact-section">Contact</a>
                    </li>
                </ul>
                <!-- Dropdown untuk Email dan Logout -->
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <?= $_SESSION["username"] ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
                    </ul>
                </div>
                <form id="logout-form" action="dashboard.php" method="POST" style="display: none;">
                    <input type="hidden" name="logout" value="1">
                </form>
            </div>
        </div>
    </nav>

    <!--section end-->


    <div id="home-section" class="container mt-5 py-5">
        <div class="text-center">
            <h1 class="display-4 fw-bold text-light">Selamat Datang <?= $_SESSION["username"] ?> di Rektor Sport Championship</h1>
            <p class="lead text-light mt-3" style="font-size: 1.25rem;">
                Kompetisi olahraga tahunan yang menginspirasi semangat sportivitas, persahabatan, dan prestasi di kalangan mahasiswa. Bergabunglah dan saksikan berbagai cabang olahraga di mana para atlet terbaik bersaing untuk meraih gelar juara!
            </p>
        </div>
    </div>




    <!--first layout start-->
    <div class="container-fluid text-center text-light mt-4" style="height: 100%;">
        <!-- Carousel -->
        <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="3" aria-label="Slide 4"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="layout/badminton.jpg" class="d-block w-100" alt="badminton foto" style="object-fit: cover; height: 600px;">
                    <div class="carousel-caption" style="text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);">
                        <h5 class="fs-1 fw-bold text-shadow">Badminton</h5>
                        <p class="lead">Setiap pukulan adalah peluang untuk meraih kemenangan.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="layout/caturrrr.JPG" class="d-block w-100" alt="catur foto" style="object-fit: cover; height: 600px;">
                    <div class="carousel-caption" style="text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);">
                        <h5 class="fs-1 fw-bold text-shadow">Catur</h5>
                        <p class="lead">Catur adalah seni yang mengekspresikan ilmu logika.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="layout/futsal.jpg" class="d-block w-100" alt="futsal foto" style="object-fit: cover; height: 600px;">
                    <div class="carousel-caption" style="text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);">
                        <h5 class="fs-1 fw-bold text-shadow">Futsal</h5>
                        <p class="lead">Berikan kemampuan terbaik dan usaha terkerasmu dalam setiap pertandingan.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="layout/efootball.JPG" class="d-block w-100" alt="e-football foto" style="object-fit: cover; height: 600px;">
                    <div class="carousel-caption" style="text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);">
                        <h5 class="fs-1 fw-bold text-shadow">E-football</h5>
                        <p class="lead">Bermain dengan semangat untuk meraih kemenangan.</p>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>

    <!--end carousel img-->

    <div id="card-section" class="container mt-5">
        <div class="row">
            <!-- Card Badminton -->
            <div class="col-sm-6 col-md-6 mb-2">
                <div class="card shadow border-0 rounded-3">
                    <img src="layout/badminton.jpg" class="card-img-top rounded-top" alt="Badminton" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title text-dark">Badminton</h5>
                        <p class="card-text text-muted">Setiap pukulan adalah peluang untuk meraih kemenangan.</p>
                        <p class="card-text text-dark"><strong>Rp100.000</strong></p>
                        <a href="lomba/badminton.php" class="btn btn-primary w-100">Daftar Sekarang</a>
                    </div>
                </div>
            </div>

            <!-- Card Catur -->
            <div class="col-md-6 mb-4">
                <div class="card shadow border-0 rounded-3">
                    <img src="layout/caturrrr.JPG" class="card-img-top rounded-top" alt="Catur" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title text-dark">Catur</h5>
                        <p class="card-text text-muted">Catur adalah seni yang mengekspresikan ilmu logika.</p>
                        <p class="card-text text-dark"><strong>Rp25.000</strong></p>
                        <a href="lomba/catur.php" class="btn btn-primary w-100">Daftar Sekarang</a>
                    </div>
                </div>
            </div>

            <!-- Card Futsal -->
            <div class="col-md-6 mb-4">
                <div class="card shadow border-0 rounded-3">
                    <img src="layout/futsal.jpg" class="card-img-top rounded-top" alt="Futsal" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title text-dark">Futsal</h5>
                        <p class="card-text text-muted">Berikan kemampuan terbaik dalam setiap pertandingan.</p>
                        <p class="card-text text-dark"><strong>Rp120.000</strong></p>
                        <a href="lomba/futsal.php" class="btn btn-primary w-100">Daftar Sekarang</a>
                    </div>
                </div>
            </div>

            <!-- Card E-Football -->
            <div class="col-md-6 mb-4">
                <div class="card shadow border-0 rounded-3">
                    <img src="layout/efootball.JPG" class="card-img-top rounded-top" alt="E-Football" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title text-dark">E-Football</h5>
                        <p class="card-text text-muted">Bermain dengan semangat untuk meraih kemenangan.</p>
                        <p class="card-text text-dark"><strong>Rp80.000</strong></p>
                        <a href="lomba/efootball.php" class="btn btn-primary w-100">Daftar Sekarang</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- contact-section -->
    <?php include "layout/footer.html" ?>
    <!--first layout end-->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>