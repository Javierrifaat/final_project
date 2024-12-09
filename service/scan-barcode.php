<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan QR Code</title>
    <script src="https://cdn.jsdelivr.net/npm/html5-qrcode/minified/html5-qrcode.min.js"></script>
</head>
<body>
    <h1>Scan QR Code untuk Presensi</h1>
    <div style="text-align: center;">
        <div id="qr-reader" style="width: 500px; margin: auto;"></div>
        <div id="result" style="margin-top: 20px; font-size: 18px;"></div>
    </div>

    <script>
        const qrReader = new Html5Qrcode("qr-reader");

        function onScanSuccess(decodedText) {
            // Menampilkan hasil scan untuk debugging
            // document.getElementById("result").innerText = "QR Code berhasil dibaca: " + decodedText;

            // Kirim data QR Code ke proses-presensi.php
            fetch("proses-presensi.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ qr_data: decodedText })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Presensi berhasil: " + data.message);
                } else {
                    alert("Presensi gagal: " + data.message);
                }
            })
            .catch(err => console.error("Error:", err));
        }

        function onScanFailure(error) {
            console.warn("Scan gagal:", error);
        }

        // Konfigurasi HTML5 QR Code
        qrReader.start(
            { facingMode: "environment" }, 
            { fps: 10, qrbox: 250 },
            onScanSuccess,
            onScanFailure
        );
    </script>
</body>
</html>
