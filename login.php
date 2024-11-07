<?php
    include "service/database.php";

    session_start();

    $login_message="";

    if(isset($_POST['login'])) {
       $username = $_POST['username'];
       $password = $_POST ['password'];

       $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'
       ";

       $result = $db->query($sql);

        if($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            $_SESSION["username"] = $data["username"];
            $_SESSION["is_login"] = true;
            header("location: dashboard.php");
       
        }else {
            $login_message = "Akun tidak ditemukan";
        }
    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    </head>
    <title>Document</title>
</head>
<body>
    <?php include "layout/header.html" ?>
    <h3>Masuk akun</h3><br>
    <b><?= $login_message ?></b>
    <form action="login.php" method="POST">
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Username :</label>
    <input type="text" class="form-control" id="username" aria-describedby="emailHelp">
    <!-- <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div> -->
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Password :</label>
    <input type="password" class="form-control" id="password">
  </div>
  <button type="submit" name="login" class="btn btn-primary">LOGIN</button>
</form>

    <?php include "layout/footer.html" ?>
</body>
</html>