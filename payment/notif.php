<?php
include '../service/database.php';

// Mengambil data dari URL
$order_id = isset($_GET['order_id']) ? mysqli_real_escape_string($db, $_GET['order_id']) : null;
$status_code = isset($_GET['status_code']) ? mysqli_real_escape_string($db, $_GET['status_code']) : null;
$transaction_status = isset($_GET['transaction_status']) ? mysqli_real_escape_string($db, $_GET['transaction_status']) : null;

// Menampilkan data jika ada
if ($order_id && $status_code && $transaction_status) {
    echo "Order ID: $order_id<br>";
    echo "Status Code: $status_code<br>";
    echo "Transaction Status: $transaction_status<br>";

    // Query untuk update status_pembayaran
    $sql = "UPDATE tlb 
            SET status_pembayaran = 'success' 
            WHERE order_id = ?";
    $stmt = mysqli_prepare($db, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $order_id);
        if (mysqli_stmt_execute($stmt)) {
            echo "Pembayaran berhasil diupdate.<br>";

            // Redirect ke dashboard
            header("Location: /fp/dashboard.php");
            exit(); // Pastikan script berhenti setelah redirect
        } else {
            echo "Gagal mengupdate status pembayaran.<br>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Terjadi kesalahan dalam mempersiapkan query.";
    }
} else {
    echo "Data tidak lengkap atau URL tidak valid.";
}
