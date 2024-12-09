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

// Ambil data dari URL
$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : null;

// Validasi order_id
if (!$order_id) {
    die("Order ID tidak ditemukan.");
}

// Query untuk mendapatkan data bukti pembayaran dari tabel registrations
$query = "
    SELECT 
        users.email,
        registrations.status_pembayaran,
        event.event_name,
        registrations.order_id,
        event.event_date,
        event.event_time,
        registrations.user_id,
        registrations.event_id,
        registrations.team_id
    FROM 
        registrations
    JOIN 
        users ON registrations.user_id = users.id
    JOIN 
        event ON registrations.event_id = event.event_id
    WHERE 
        registrations.order_id = ? 
        AND registrations.status_pembayaran = 'Berhasil'
";

// Persiapkan dan jalankan query
$stmt = $connection->prepare($query);
if (!$stmt) {
    die("Query gagal: " . $connection->error);
}
$stmt->bind_param('s', $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Bukti pembayaran tidak ditemukan atau pembayaran belum lunas.");
}

// Ambil data dari hasil query
$data = $result->fetch_assoc();

// Ambil team_id, jika kosong, anggap sebagai peserta individu
$team_id = $data['team_id'] ?? null;

// Tambahkan library QR Code
require '../vendor/autoload.php';

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

// Konfigurasi QR Code
$options = new QROptions([
    'eccLevel' => QRCode::ECC_L,
    'outputType' => QRCode::OUTPUT_IMAGE_PNG,
    'imageBase64' => true,
]);

// Data QR Code
$qrData = json_encode([
    'event_id' => $data['event_id'],
    'team_id' => $team_id, // Tetap tambahkan meskipun null
    'user_id' => $data['user_id'],
    'event_name' => $data['event_name'],
    'email' => $data['email'],
    'event_date' => $data['event_date'],
    'event_time' => $data['event_time'],
]);

// Generate QR Code
$qrCode = (new QRCode($options))->render($qrData);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pembayaran</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, rgba(0, 128, 255, 1), rgba(0, 255, 255, 0.8));
            color: black;
        }
        .receipt {
            border: 1px solid #007bff;
            border-radius: 10px;
            padding: 30px;
            max-width: 450px;
            margin: 50px auto;
            background-color: #ffffff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .receipt h1 {
            font-size: 26px;
            margin-bottom: 20px;
            color: #007bff;
            text-align: center;
        }
        .receipt hr {
            border: 1px solid #007bff;
            margin: 20px 0;
        }
        .receipt p {
            margin: 10px 0;
            font-size: 16px;
            line-height: 1.5;
        }
        .barcode {
            margin: 20px 0;
            text-align: center;
        }
        .btn-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .btn-back, .btn-print {
            padding: 12px 25px;
            border-radius: 25px;
            font-size: 16px;
            text-decoration: none;
            text-align: center;
            transition: background-color 0.3s, transform 0.3s;
            border: none;
            outline: none;
            cursor: pointer;
        }
        .btn-back {
            background-color: green;
            color: white;
            max-width: 200px;
        }
        .btn-back:hover {
            background-color: darkgreen;
            transform: scale(1.05);
        }
        .btn-print {
            background-color: #007bff;
            color: white;
        }
        .btn-print:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
<div class="receipt" id="receipt">
    <h1>Bukti Pembayaran Lomba</h1>
    <hr>
    <p><strong>Email:</strong> <?= htmlspecialchars($data['email']) ?></p>
    <p><strong>Status Pembayaran:</strong> <?= ucfirst(htmlspecialchars($data['status_pembayaran'])) ?></p>
    <p><strong>Lomba:</strong> <?= htmlspecialchars($data['event_name']) ?></p>
    <p><strong>Order ID:</strong> <?= htmlspecialchars($data['order_id']) ?></p>
    <p><strong>Tanggal Lomba:</strong> <?= htmlspecialchars($data['event_date']) ?></p>
    <p><strong>Waktu:</strong> <?= htmlspecialchars($data['event_time']) ?></p>
    <div class="barcode">
        <img src="<?= $qrCode ?>" alt="QR Code" />
    </div>
    <hr>
    <p>Terima kasih telah mendaftar!</p>
</div>

<div class="btn-container">
    <button id="printBtn" class="btn-print">Cetak Bukti Pembayaran</button>
    <a href="../dashboard.php" class="btn-back">Kembali ke Dashboard</a>
</div>

<script>
    document.getElementById('printBtn').addEventListener('click', function () {
        const receiptContent = document.getElementById('receipt').outerHTML;
        const printWindow = window.open('', '_blank');
        printWindow.document.open();
        printWindow.document.write(
            `<html>
            <head>
                <title>Cetak Bukti Pembayaran</title>
            </head>
            <body>${receiptContent}</body>
            </html>`
        );
        printWindow.document.close();
        printWindow.print();
    });
</script>

</body>
</html>

<?php
// Menutup koneksi
$stmt->close();
$connection->close();
?>
