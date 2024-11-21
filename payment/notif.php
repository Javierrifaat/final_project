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

    // Daftar tabel yang akan diperiksa
    $tables = ['tlb', 'tlc', 'tle'];
    $found = false;

    foreach ($tables as $table) {
        // Mencari data di setiap tabel
        $sql = "SELECT * FROM $table WHERE order_id = ?";
        $stmt = mysqli_prepare($db, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $order_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) > 0) {
                // Data ditemukan, update status pembayaran
                $update_sql = "UPDATE $table SET status_pembayaran = 'success' WHERE order_id = ?";
                $update_stmt = mysqli_prepare($db, $update_sql);

                if ($update_stmt) {
                    mysqli_stmt_bind_param($update_stmt, "s", $order_id);
                    if (mysqli_stmt_execute($update_stmt)) {
                        echo "Pembayaran berhasil diupdate di $table.<br>";
                        header("Location: /fp/dashboard.php");
                        exit();
                    } else {
                        echo "Gagal mengupdate status pembayaran di $table.<br>";
                    }
                    mysqli_stmt_close($update_stmt);
                }

                $found = true;
                break; // Keluar dari loop karena data telah ditemukan dan diupdate
            }
            mysqli_stmt_close($stmt);
        }
    }

    if (!$found) {
        // Jika data tidak ditemukan di semua tabel
        echo "Kau Gagal Mendapatkannya.<br>";
    }
} else {
    echo "Data tidak lengkap atau URL tidak valid.";
}
