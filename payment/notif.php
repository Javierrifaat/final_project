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

    // Periksa jika transaksi berhasil
    if ($status_code == '200' && $transaction_status == 'settlement') {
        // Cari data di tabel registrations
        $sql = "SELECT * FROM registrations WHERE order_id = ?";
        $stmt = mysqli_prepare($db, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $order_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result && mysqli_num_rows($result) > 0) {
                // Data ditemukan, update status pembayaran
                $update_sql = "UPDATE registrations SET status_pembayaran = 'Berhasil' WHERE order_id = ?";
                $update_stmt = mysqli_prepare($db, $update_sql);

                if ($update_stmt) {
                    mysqli_stmt_bind_param($update_stmt, "s", $order_id);
                    if (mysqli_stmt_execute($update_stmt)) {
                        echo "Pembayaran berhasil diupdate.<br>";
                        // Redirect ke bukti pembayaran dengan menyertakan order_id
                        header("Location: ../service/bukti-pembayaran.php?order_id=" . urlencode($order_id)); 
                        exit();
                    } else {
                        echo "Gagal mengupdate status pembayaran.<br>";
                    }
                    mysqli_stmt_close($update_stmt);
                }
            } else {
                echo "Order ID tidak ditemukan di tabel registrations.";
            }
            mysqli_stmt_close($stmt);
        } else {
            error_log("Query Error: " . mysqli_error($db));
        }
    } else {
        echo "Status transaksi tidak valid untuk update.";
    }
} else {
    echo "Data yang diperlukan tidak tersedia.";
}
?>
