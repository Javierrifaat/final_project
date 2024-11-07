<?php
include "service/database.php";

$register_message = "";

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "INSERT INTO users (username,password) VALUES ('$username', '$password')";

    if ($db->query($sql)) {
        $register_message = "Daftar akun berhasil, silahkah login";
    } else {
        $register_message = "daftar akun gagal, silahkan ulangi!";
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Document</title>
</head>

<body>
    <?php include "layout/header.html" ?>
    <h3>Daftar Akun</h3>
    <b><?= $register_message ?> </b>
    <form action="register.php" method="POST">
        <input type="text" placeholder="Username" name="username" /> <br><br>
        <input type="password" placeholder="password" name="password" /><br><br>
        <!-- <button type="submit" name="register">daftar sekarang</button> -->
        <button class="btn btn-primary" name="register " type="submit">Daftar</button>
    </form>

    <?php include "layout/footer.html" ?>
</body>

</html>