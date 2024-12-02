<?php
session_start();
require '../service/database.php';

// Cek jika event_id ada di URL
if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];

    // Ambil data event yang akan diedit
    $query = "SELECT * FROM event WHERE event_id = ?";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, 'i', $event_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $event = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    // Jika event tidak ditemukan, redirect ke dashboard
    if (!$event) {
        header("Location: edit-dashboard.php");
        exit;
    }
}

// Proses form submission untuk edit event
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_event'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $rules = $_POST['rules'];
        $location = $_POST['location'];
        $event_date = $_POST['event_date'];
        $event_time = $_POST['event_time'];
        $image_url = $event['image_url'];

        // Cek jika ada gambar baru yang diupload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image_name = basename($_FILES['image']['name']);
            $target_dir = "../uploads/";
            $target_file = $target_dir . $image_name;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image_url = $target_file;
            }
        }

        // Update data event ke database
        $query = "UPDATE event SET event_name = ?, event_description = ?, registration_fee = ?, rules = ?, location = ?, event_date = ?, event_time = ?, image_url = ? WHERE event_id = ?";
        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, 'ssdsssss', $name, $description, $price, $rules, $location, $event_date, $event_time, $image_url, $event_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        header("Location: edit-dashboard.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Styling tambahan untuk gambar dan tombol */
        .event-image {
            width: 100%;
            height: auto;
            border-radius: 8px;
            transition: transform 0.3s ease-in-out;
        }
        .event-image:hover {
            transform: scale(1.05);
        }
        .card-custom {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-radius: 12px;
            background: linear-gradient(135deg, #007bff, #00c6ff);
        }
        .form-label {
            font-weight: bold;
        }
        .form-control {
            border-radius: 8px;
            padding: 10px;
        }
        .btn-custom {
            border-radius: 30px;
            padding: 12px 30px;
            font-weight: bold;
            text-transform: uppercase;
            background: linear-gradient(135deg, #00c6ff, #007bff);
            color: white;
        }
        .btn-custom:hover {
            background: linear-gradient(135deg, #007bff, #00c6ff);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .bg-gradient {
            background: linear-gradient(135deg, #007bff, #00c6ff);
        }
        .card-header {
            background: linear-gradient(135deg, #00c6ff, #007bff);
            color: white;
            padding: 15px;
            border-radius: 10px 10px 0 0;
        }
        .form-control:focus {
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
            border-color: #007bff;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-custom">
                    <div class="card-header text-center">
                        <h3>Edit Event</h3>
                    </div>
                    <div class="card-body p-5">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Event</label>
                                <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($event['event_name']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea name="description" id="description" class="form-control" required><?= htmlspecialchars($event['event_description']) ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="price" class="form-label">Harga Pendaftaran</label>
                                <input type="number" name="price" id="price" class="form-control" value="<?= $event['registration_fee'] ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="rules" class="form-label">Aturan</label>
                                <textarea name="rules" id="rules" class="form-control" required><?= htmlspecialchars($event['rules']) ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="location" class="form-label">Lokasi</label>
                                <input type="text" name="location" id="location" class="form-control" value="<?= htmlspecialchars($event['location']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="event_date" class="form-label">Tanggal</label>
                                <input type="date" name="event_date" id="event_date" class="form-control" value="<?= $event['event_date'] ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="event_time" class="form-label">Waktu</label>
                                <input type="time" name="event_time" id="event_time" class="form-control" value="<?= $event['event_time'] ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Foto Event</label>
                                <input type="file" name="image" id="image" class="form-control">
                                <?php if ($event['image_url']): ?>
                                    <div class="mt-2">
                                        <img src="<?= htmlspecialchars($event['image_url']) ?>" alt="Event Image" class="event-image">
                                    </div>
                                <?php endif; ?>
                            </div>

                            <h3 class="mt-4">Field Formulir</h3>
                            <?php
                            // Ambil fields yang ada dan tampilkan
                            $field_query = "SELECT * FROM event_form_fields WHERE event_id = ?";
                            $stmt = mysqli_prepare($db, $field_query);
                            mysqli_stmt_bind_param($stmt, 'i', $event_id);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);
                            $fields = mysqli_fetch_all($result, MYSQLI_ASSOC);
                            mysqli_stmt_close($stmt);

                            foreach ($fields as $index => $field) {
                            ?>
                                <div class="mb-3">
                                    <label for="field_name_<?= $index ?>" class="form-label">Nama Field</label>
                                    <input type="text" name="fields[<?= $index ?>][name]" id="field_name_<?= $index ?>" class="form-control" value="<?= htmlspecialchars($field['field_name']) ?>" required>

                                    <label for="field_type_<?= $index ?>" class="form-label mt-2">Tipe Field</label>
                                    <select name="fields[<?= $index ?>][type]" id="field_type_<?= $index ?>" class="form-control" required>
                                        <option value="text" <?= $field['field_type'] == 'text' ? 'selected' : '' ?>>Text</option>
                                        <option value="email" <?= $field['field_type'] == 'email' ? 'selected' : '' ?>>Email</option>
                                        <option value="tel" <?= $field['field_type'] == 'tel' ? 'selected' : '' ?>>Telephone</option>
                                        <option value="textarea" <?= $field['field_type'] == 'textarea' ? 'selected' : '' ?>>Textarea</option>
                                    </select>
                                </div>
                            <?php } ?>

                            <div class="mb-3">
                                <button type="submit" name="update_event" class="btn btn-custom">Update Event</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
