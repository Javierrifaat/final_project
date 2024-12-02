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
        event.event_time
    FROM 
        registrations
    JOIN 
        users ON registrations.user_id = users.id
    JOIN 
        event ON registrations.event_id = event.event_id
    WHERE 
        registrations.order_id = ? 
        AND registrations.status_pembayaran = 'Berhasil'  -- Sesuaikan dengan status pembayaran yang valid
";

// Persiapkan dan jalankan query dengan menggunakan prepared statement untuk keamanan
$stmt = $connection->prepare($query);
if (!$stmt) {
    die("Query gagal: " . $connection->error);
}
$stmt->bind_param('s', $order_id); // 's' untuk parameter string (order_id)
$stmt->execute();
$result = $stmt->get_result();

// Debugging: Menampilkan hasil query untuk memastikan ada data yang ditemukan
if ($result->num_rows == 0) {
    // Cek apakah order_id ada di tabel registrations dengan status pembayaran selain 'Berhasil'
    $check_query = "SELECT * FROM registrations WHERE order_id = ?";
    $check_stmt = $connection->prepare($check_query);
    $check_stmt->bind_param('s', $order_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    if ($check_result->num_rows > 0) {
        echo "Order ID ditemukan tetapi status pembayaran belum berhasil.<br>";
    } else {
        echo "Order ID tidak ditemukan di tabel registrations.<br>";
    }
    die("Bukti pembayaran tidak ditemukan atau pembayaran belum lunas.");
}

// Ambil data dari hasil query
$data = $result->fetch_assoc();

// Panggil library barcode
require '../vendor/autoload.php';
use Picqer\Barcode\BarcodeGeneratorHTML;

$generator = new BarcodeGeneratorHTML();
$barcode = $generator->getBarcode($data['order_id'], $generator::TYPE_CODE_128);

// Menampilkan bukti pembayaran
?>
<!DOCTYPE html>
<html>
<head>
    <title>Bukti Pembayaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .receipt {
            border: 1px solid #000;
            padding: 20px;
            max-width: 400px;
            margin: auto;
            text-align: center;
        }
        .receipt h1 {
            font-size: 18px;
            margin-bottom: 10px;
        }
        .receipt .barcode {
            margin: 20px 0;
        }
        .btn-back {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }
        .btn-back:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <h1>Bukti Pembayaran Lomba</h1>
        <hr>
        <p><strong>Email:</strong> <?= htmlspecialchars($data['email']) ?></p>
        <p><strong>Status Pembayaran:</strong> <?= ucfirst(htmlspecialchars($data['status_pembayaran'])) ?></p>
        <p><strong>Lomba:</strong> <?= htmlspecialchars($data['event_name']) ?></p>
        <p><strong>Order ID:</strong> <?= htmlspecialchars($data['order_id']) ?></p>
        <p><strong>Tanggal Lomba:</strong> <?= htmlspecialchars($data['event_date']) ?></p>
        <p><strong>Waktu:</strong> <?= htmlspecialchars($data['event_time']) ?></p>
        <div class="barcode">
            <?= $barcode ?>
        </div>
        <hr>
        <p>Terima kasih telah mendaftar!</p>
        <a href="../dashboard.php" class="btn-back">Kembali ke Dashboard</a>
    </div>
</body>
</html>

<?php
// Menutup koneksi
$stmt->close();
$connection->close();
?>
