<?php
include "service/database.php";

$register_message = "";

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash password
    $hash_password = hash("sha256", $password);

    try {
        // Siapkan statement
        $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hash_password);

        // Eksekusi statement
        if ($stmt->execute()) {
            $register_message = "Daftar akun berhasil, silahkan login";
        } else {
            $register_message = "Daftar akun gagal, silahkan ulangi!";
        }

        // Tutup statement
        $stmt->close();
    } catch (mysqli_sql_exception $e) {
        $register_message = "Username sudah digunakan atau terjadi kesalahan: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php include "layout/header.html" ?>
    <h3>Daftar Akun</h3>
    <b><?= $register_message ?> </b>
    <form action="register.php" method="POST">
        <input type="text" placeholder="username" name="username" required /> <br>
        <input type="email" placeholder="email (gmail)" name="email" required /> <br>
        <input type="password" placeholder="password" name="password" required /> <br>
        <button type="submit" name="register">daftar sekarang</button>
    </form>
</body>