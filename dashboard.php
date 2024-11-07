<?php
session_start();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profil Perusahaan - Event Lomba</title>
    <link rel="stylesheet" href="layout/styles.css">
</head>
<body>
    <header>
        <div class="container">
            <img src="layout/logo.jpg" alt="logo rsc" class="logo">
            <h1>Rektor Sport championship</h1>
            <h3>Selamat datang <?= $_SESSION["username"] ?></h3>
            <nav>
                <ul>
                    <li><a href="home.html">Home</a></li>
                    <li><a href="#about">Tentang Kami</a></li>
                    <li><a href="#events">Lomba</a></li>
                    <li><a href="#contact">Kontak</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section id="about">
        <div class="container">
            <h2>Tentang Kami</h2>
            <p>
                Rektor Sport championship adalah event olahraga dan e-sport. Kami telah sukses mengadakan berbagai event skala besar 
                seperti turnamen futsal, badminton, Mobile Legend, catur, dan eFootball. Visi kami adalah menjadi platform terkemuka 
                dalam dunia kompetisi olahraga dan e-sport, dengan misi memberikan pengalaman kompetitif yang adil dan menyenangkan bagi semua peserta.
            </p> <hr>
        </div>
    </section>

    <section id="events">
        <div class="container">
            <h2>Lomba Kami</h2>
            <div class="event-grid">
                <div class="event-card">
                    <h3>Badminton</h3>
                    <p>Turnamen badminton tingkat regional dengan hadiah menarik untuk pemenang!</p>
                </div>
                <div class="event-card">
                    <h3>Futsal</h3>
                    <p>Liga futsal tahunan yang memperebutkan piala bergengsi di kota Anda.</p>
                </div>
                <div class="event-card">
                    <h3>Mobile Legend</h3>
                    <p>Kompetisi e-sport Mobile Legend dengan tim-tim profesional dari berbagai daerah.</p>
                </div>
                <div class="event-card">
                    <h3>Catur</h3>
                    <p>Turnamen catur tingkat nasional yang terbuka untuk semua kalangan.</p>
                </div>
                <div class="event-card">
                    <h3>eFootball</h3>
                    <p>Turnamen eFootball yang mendebarkan dengan hadiah besar dan peserta dari berbagai negara.</p>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <section id="contact">
        <div class="container">
            <p>Contact Us</p>
            <p>Email: @Rsc_ubp.gmail.com</p>
            <p>Nomer Telp: 0878129388</p>
            <p>Instagram: Rsc_ubp</p>
        </div>
        </section>
    </footer>
</body>
</html>
