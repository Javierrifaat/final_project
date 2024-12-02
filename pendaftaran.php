<?php
session_start();
require 'service/database.php'; // Koneksi database dengan variabel $db
require_once 'payment/midtrans-php-master/Midtrans.php'; // Pastikan path ini benar

// Konfigurasi Midtrans
\Midtrans\Config::$serverKey = 'SB-Mid-server-SdGSNrMDhqUgP4KJM_0hTR3O';
\Midtrans\Config::$isProduction = false; // Sandbox mode untuk testing
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['register'])) {
        $user_id = $_SESSION['is_login']; // Pastikan ada sesi user
        $event_id = $_POST['event_id']; // Event yang dipilih

        // Ambil data event
        $query_event = "SELECT * FROM event WHERE event_id = ?";
        $stmt = mysqli_prepare($db, $query_event);
        mysqli_stmt_bind_param($stmt, 'i', $event_id);
        mysqli_stmt_execute($stmt);
        $event_result = mysqli_stmt_get_result($stmt);
        $event = mysqli_fetch_assoc($event_result);
        mysqli_stmt_close($stmt);

        if (!$event) {
            die("Event tidak ditemukan.");
        }

        // Menyimpan data registrasi
        $query = "INSERT INTO registrations (user_id, event_id, order_id, status_pembayaran, created_at) 
                  VALUES (?, ?, ?, ?, NOW())";
        $stmt = mysqli_prepare($db, $query);
        $order_id = uniqid('order_'); // ID unik untuk pendaftaran
        $status_pembayaran = 'Belum Dibayar'; // Default status pembayaran
        mysqli_stmt_bind_param($stmt, 'iiss', $user_id, $event_id, $order_id, $status_pembayaran);
        mysqli_stmt_execute($stmt);
        $registration_id = mysqli_insert_id($db); // Dapatkan ID pendaftaran baru
        mysqli_stmt_close($stmt);

        // Menyimpan data formulir yang diisi oleh user ke tabel 'registration_data'
        if (isset($_POST['fields'])) {
            foreach ($_POST['fields'] as $field_name => $field_value) {
                $query_field = "INSERT INTO registration_data (registration_id, field_name, field_value) 
                                VALUES (?, ?, ?)";
                $stmt = mysqli_prepare($db, $query_field);
                mysqli_stmt_bind_param($stmt, 'iss', $registration_id, $field_name, $field_value);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
        }

        // Data pembayaran untuk Midtrans
        $biaya_pendaftaran = $event['registration_fee'];
        $transaction_details = [
            'order_id' => $order_id,
            'gross_amount' => $biaya_pendaftaran, // Total biaya
        ];

        $item_details = [
            [
                'id' => 'event_fee',
                'price' => $biaya_pendaftaran,
                'quantity' => 1,
                'name' => "Pendaftaran Event " . htmlspecialchars($event['event_name']),
            ]
        ];

        $customer_details = [
            'first_name' => $_POST['fields']['Nama'] ?? 'Tidak Diketahui', // Nama peserta
            'email' => $_POST['fields']['Email'] ?? 'email@domain.com', // Email peserta
            'phone' => $_POST['fields']['No Whatsapp'] ?? 'N/A', // Nomor HP peserta
        ];

        $transaction = [
            'transaction_details' => $transaction_details,
            'item_details' => $item_details,
            'customer_details' => $customer_details,
            'finish_redirect_url' => 'https://www.yourwebsite.com/success-page.php',
        ];

        try {
            // Buat Snap Token
            $snapToken = \Midtrans\Snap::getSnapToken($transaction);

            // Redirect ke halaman pembayaran Snap
            echo "<html><body>";
            echo "<h3>Mohon tunggu, sedang diarahkan ke halaman pembayaran...</h3>";
            echo "<script src='https://app.sandbox.midtrans.com/snap/snap.js' data-client-key='SB-Mid-client-uw81o6eb7cacAn_V'></script>";
            echo "<script>snap.pay('$snapToken');</script>";
            echo "</body></html>";
            exit;
        } catch (Exception $e) {
            echo "Gagal membuat transaksi. Error: " . $e->getMessage();
        }
    }
}

// Ambil data event dan field formulir
$event_id = $_GET['event_id'];
$query_event = "SELECT * FROM event WHERE event_id = ?";
$stmt = mysqli_prepare($db, $query_event);
mysqli_stmt_bind_param($stmt, 'i', $event_id);
mysqli_stmt_execute($stmt);
$event_result = mysqli_stmt_get_result($stmt);
$event = mysqli_fetch_assoc($event_result);
mysqli_stmt_close($stmt);

$query_fields = "SELECT * FROM event_form_fields WHERE event_id = ?";
$stmt = mysqli_prepare($db, $query_fields);
mysqli_stmt_bind_param($stmt, 'i', $event_id);
mysqli_stmt_execute($stmt);
$fields_result = mysqli_stmt_get_result($stmt);
$fields = mysqli_fetch_all($fields_result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Lomba</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1>Registrasi Lomba: <?= htmlspecialchars($event['event_name']) ?></h1>
        <form method="POST">
            <input type="hidden" name="event_id" value="<?= htmlspecialchars($event_id) ?>">

            <?php foreach ($fields as $field): ?>
                <div class="mb-3">
                    <label for="field_<?= htmlspecialchars($field['field_name']) ?>" class="form-label"><?= htmlspecialchars($field['field_name']) ?></label>
                    <?php if ($field['field_type'] === 'text'): ?>
                        <input type="text" name="fields[<?= htmlspecialchars($field['field_name']) ?>]" id="field_<?= htmlspecialchars($field['field_name']) ?>" class="form-control" required>
                    <?php elseif ($field['field_type'] === 'number'): ?>
                        <input type="number" name="fields[<?= htmlspecialchars($field['field_name']) ?>]" id="field_<?= htmlspecialchars($field['field_name']) ?>" class="form-control" required>
                    <?php elseif ($field['field_type'] === 'email'): ?>
                        <input type="email" name="fields[<?= htmlspecialchars($field['field_name']) ?>]" id="field_<?= htmlspecialchars($field['field_name']) ?>" class="form-control" required>
                    <?php elseif ($field['field_type'] === 'date'): ?>
                        <input type="date" name="fields[<?= htmlspecialchars($field['field_name']) ?>]" id="field_<?= htmlspecialchars($field['field_name']) ?>" class="form-control" required>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <button type="submit" name="register" class="btn btn-primary">Proses Pendaftaran</button>
        </form>
    </div>
</body>

</html>
