<?php
session_start();
require '../service/database.php';

// Ambil event_id dari URL
$event_id = $_GET['event_id'];

// Ambil data lomba berdasarkan event_id
$event_query = "SELECT * FROM event WHERE event_id = ?";
$stmt = $db->prepare($event_query);
$stmt->bind_param('i', $event_id);
$stmt->execute();
$event_result = $stmt->get_result();
$event = $event_result->fetch_assoc();

// Ambil daftar peserta yang terdaftar untuk event ini
$participants_query = "SELECT r.id AS registration_id, u.username, u.email, r.status_pembayaran
                       FROM registrations r
                       JOIN users u ON r.user_id = u.id
                       WHERE r.event_id = ?";
$stmt = $db->prepare($participants_query);
$stmt->bind_param('i', $event_id);
$stmt->execute();
$participants_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Detail Peserta Lomba: <?= htmlspecialchars($event['event_name']) ?></title>
</head>

<body>
<div class="container mt-4">
    <h2>Peserta Lomba: <?= htmlspecialchars($event['event_name']) ?></h2>

    <!-- Daftar Peserta -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-primary">
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Status Pembayaran</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($participant = $participants_result->fetch_assoc()) : ?>
                    <tr>
                        <td><?= htmlspecialchars($participant['username']) ?></td>
                        <td><?= htmlspecialchars($participant['email']) ?></td>
                        <td><?= htmlspecialchars($participant['status_pembayaran']) ?></td>
                        <td>
                            <a href="participant-details.php?registration_id=<?= $participant['registration_id'] ?>" class="btn btn-info btn-sm">Detail</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
                    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
