<?php
session_start();
require '../service/database.php';

// Pastikan user sudah login
if (!isset($_SESSION['is_login'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['is_login']; // ID user yang sedang login

// Query untuk mendapatkan data user
$query = "
    SELECT u.username, u.email, p.profile_picture, p.phone_number 
    FROM users u 
    LEFT JOIN user_profiles p ON u.id = p.user_id 
    WHERE u.id = ?
";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Path foto profil
$defaultProfilePicture = "../layout/profil-default.jpg";
$profilePicture = !empty($user['profile_picture']) ? $user['profile_picture'] : $defaultProfilePicture;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        .profile-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="card-body">
            <div class="text-center">
                <!-- Foto Profil, menambahkan query string untuk mencegah cache -->
                <img src="<?php echo $profilePicture . '?' . time(); ?>" alt="Foto Profil" class="profile-img">
            </div>
            <form action="update-profile.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="phone_number">Nomor WhatsApp:</label>
                    <input type="text" name="phone_number" id="phone_number" value="<?php echo htmlspecialchars($user['phone_number']); ?>" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="profile_picture">Ganti Foto Profil:</label>
                    <input type="file" name="profile_picture" id="profile_picture" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
