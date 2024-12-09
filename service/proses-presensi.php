<?php
// Koneksi ke database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'fpp'; // Sesuaikan dengan nama database Anda
$connection = new mysqli($host, $username, $password, $database);

if ($connection->connect_error) {
    die("Koneksi gagal: " . $connection->connect_error);
}

// Mendapatkan data dari request
$request_body = file_get_contents('php://input');
$data = json_decode($request_body, true);

if (!isset($data['qr_data'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Data QR Code tidak ditemukan.'
    ]);
    exit;
}

// Parsing data QR Code
$qrData = json_decode($data['qr_data'], true);

if (!$qrData) {
    echo json_encode([
        'success' => false,
        'message' => 'Format QR Code tidak valid.'
    ]);
    exit;
}

// Ambil data dari QR Code
$event_id = $qrData['event_id'];
$user_id = $qrData['user_id'];

// Cek apakah user sudah melakukan presensi
$query = "SELECT * FROM presensi WHERE event_id = ? AND user_id = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param('ii', $event_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode([
        'success' => false,
        'message' => 'User sudah melakukan presensi.'
    ]);
    exit;
}

// Simpan presensi ke database
$insertQuery = "INSERT INTO presensi (event_id, user_id, presensi_status, created_at) VALUES (?, ?, 'Hadir', NOW())";
$insertStmt = $connection->prepare($insertQuery);
$insertStmt->bind_param('ii', $event_id, $user_id);

if ($insertStmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Presensi berhasil disimpan.'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Gagal menyimpan presensi.'
    ]);
}

// Tutup koneksi
$stmt->close();
$insertStmt->close();
$connection->close();
?>
