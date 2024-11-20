<?php
include "../service/database.php";
session_start();

$login_message = "";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek apakah username dan password yang dimasukkan adalah "admin"
    if ($username === 'admin' && $password === 'admin') {
        // Jika benar, simpan data session
        $_SESSION["username"] = $username;
        $_SESSION["role"] = 'admin'; // Simpan role
        $_SESSION["is_login"] = true;

        // Redirect ke halaman admin
        header("location: dashboard_admin.php");
        exit();
    } else {
        $login_message = "Username atau Password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../layout/logo.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>RSC - LOGIN ADMIN</title>
    <style>
        body {
            background: linear-gradient(to right, #0066cc, #00bfff);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #fff;
            font-family: Arial, sans-serif;
        }
        .card {
            background-color: #f8f9fa;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            padding: 20px;
            max-width: 400px;
            width: 100%;
        }
        .card-title {
            color: #1a73e8;
            font-weight: bold;
            text-align: center;
            font-size: 24px;
        }
        .form-label {
            color: #1a73e8;
            font-size: 14px;
        }
        .form-control {
            border-radius: 10px;
            padding: 10px;
            border: 1px solid silver;
        }
        .form-control:focus {
            border-color: #155bb5;
            box-shadow: 0 0 5px rgba(26, 115, 232, 0.5);
        }
        .btn-primary {
            background: linear-gradient(to right, #0066cc, #00bfff);
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            width: 100%;
            transition: background-color 0.3s;
        }
        .btn-primary:hover {
            background: linear-gradient(to right, #0066cc, #00bfff);
        }
    </style>
</head>
<body>
    <!--card login start-->
    <div class="card">
        <div class="text-center mb-3">
            <img src="../layout/logo.jpg" alt="logo" style="width: 120px; height: auto; border-radius: 50%;">
        </div>
        <h5 class="card-title">Login Admin</h5>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username" class="form-control" placeholder="Masukkan Username Anda" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan Password Anda" required>
            </div>
            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="showPassword" onclick="togglePasswordVisibility()">
                <label class="form-check-label" for="showPassword">Show Password</label>
            </div>
            <button type="submit" name="login" class="btn btn-primary mt-3">Login</button>
            <p class="text-danger text-center mt-3"><?php echo $login_message; ?></p>
        </form>
    </div>
    <!--card login end-->

    <script>
        function togglePasswordVisibility() {
            const passwordField = document.getElementById("password");
            passwordField.type = passwordField.type === "password" ? "text" : "password";
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
