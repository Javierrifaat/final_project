<?php
session_start();


require '../service/database.php';

// Validasi parameter registration_id
if (!isset($_GET['registration_id']) || !is_numeric($_GET['registration_id'])) {
    die("Parameter registration_id tidak valid.");
}

$registration_id = intval($_GET['registration_id']);

// Ambil data formulir pendaftaran berdasarkan registration_id
$form_data_query = "SELECT field_name, field_value FROM registration_data WHERE registration_id = ?";
$stmt = $db->prepare($form_data_query);
if (!$stmt) {
    die("Query gagal: " . $conn->error);
}
$stmt->bind_param('i', $registration_id);
$stmt->execute();
$form_data_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Detail Pendaftaran Peserta</title>
</head>

<body>
    <div class="container mt-4">
        <h2>Detail Pendaftaran Peserta</h2>

        <?php if ($form_data_result->num_rows > 0) : ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Field Name</th>
                        <th>Field Value</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($form_data = $form_data_result->fetch_assoc()) : ?>
                        <tr>
                            <td><?= htmlspecialchars($form_data['field_name']) ?></td>
                            <td><?= htmlspecialchars($form_data['field_value']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else : ?>
            <div class="alert alert-warning">Data pendaftaran tidak ditemukan untuk ID: <?= htmlspecialchars($registration_id) ?></div>
        <?php endif; ?>

        <a href="dashboard_admin.php" class="btn btn-secondary mt-3">Kembali</a>
    </div>
</body>

</html>

<?php
// Tutup koneksi dan statement
$stmt->close();
$db->close();
?>
