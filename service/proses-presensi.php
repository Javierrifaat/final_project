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

// Debugging: Tampilkan data yang diterima dari client
error_log("Data yang diterima: " . print_r($data, true));

if (!isset($data['qr_data'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Data QR Code tidak ditemukan.'
    ]);
    exit;
}

// Parsing data QR Code
$qrData = json_decode($data['qr_data'], true);

// Debugging: Tampilkan data QR setelah decoding
error_log("Data QR setelah decoding: " . print_r($qrData, true));

if (!$qrData || !isset($qrData['event_id']) || !isset($qrData['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Format QR Code tidak valid atau data tidak lengkap.'
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

// Simpan presensi ke database dengan mencatat waktu presensi dan status 'Hadir'
$insertQuery = "INSERT INTO presensi (user_id, event_id, presensi_status, presensi_time) VALUES (?, ?, 'Hadir', NOW())";
$insertStmt = $connection->prepare($insertQuery);
$insertStmt->bind_param('ii', $user_id, $event_id);

if ($insertStmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Presensi berhasil disimpan.'
    ]);
} else {
    // Log jika ada kesalahan saat query
    error_log("Gagal menyimpan presensi: " . $insertStmt->error);
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
