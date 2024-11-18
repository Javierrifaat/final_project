<?php
include "service/database.php";

session_start();

$login_message = "";

if (isset($_POST['login'])) {
    $username_or_email = $_POST['username_or_email']; // Menggunakan satu input
    $password = $_POST['password'];
    $hash_password = hash('sha256', $password);

    // Ubah query untuk memeriksa username atau email
    $sql = "SELECT * FROM users WHERE (username='$username_or_email' OR email='$username_or_email') AND password='$hash_password'";

    $result = $db->query($sql);

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $_SESSION["username"] = $data["username"];
        $_SESSION["is_login"] = true;
        header("location: dashboard.php");
        exit(); // Pastikan untuk menghentikan eksekusi setelah redirect
    } else {
        $login_message = "Akun tidak ditemukan";
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
    <h3>Masuk akun</h3>
    <b><?= $login_message ?></b>
    <form action="login.php" method="POST">
        <input type="text" placeholder="username atau email" name="username_or_email" required /> <!-- Menggunakan satu input -->
        <input type="password" placeholder="password" name="password" required />
        <button type="submit" name="login">masuk sekarang</button>
    </form>
</body>

</html>