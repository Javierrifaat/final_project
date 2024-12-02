<?php
session_start();
require '../service/database.php'; // Koneksi database dengan variabel $db

// Proses form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_event'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $rules = $_POST['rules'];
        $location = $_POST['location'];
        $event_date = $_POST['event_date'];
        $event_time = $_POST['event_time'];

        // Upload gambar
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image_name = basename($_FILES['image']['name']);
            $target_dir = "../uploads/";
            $target_file = $target_dir . $image_name;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image_url = $target_file;
            } else {
                $image_url = null; // Jika gagal upload, set null
            }
        } else {
            $image_url = null;
        }

        // Tambahkan event ke database
        $query = "INSERT INTO event (event_name, event_description, registration_fee, rules, location, event_date, event_time, image_url) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, 'ssdsssss', $name, $description, $price, $rules, $location, $event_date, $event_time, $image_url);
        mysqli_stmt_execute($stmt);
        $event_id = mysqli_insert_id($db); // Dapatkan ID event baru
        mysqli_stmt_close($stmt);

        // Tambahkan field default jika ada
        if (isset($_POST['fields'])) {
            $insert_field_query = "INSERT INTO event_form_fields (event_id, field_name, field_type) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($db, $insert_field_query);

            foreach ($_POST['fields'] as $field) {
                $field_name = $field['name'];
                $field_type = $field['type'];
                mysqli_stmt_bind_param($stmt, 'iss', $event_id, $field_name, $field_type);
                mysqli_stmt_execute($stmt);
            }

            mysqli_stmt_close($stmt);
        }

        header("Location: edit-dashboard.php");
        exit;
    } elseif (isset($_POST['delete_event'])) {
        $eventId = $_POST['event_id'];
        // Hapus fields terkait terlebih dahulu
        $query = "DELETE FROM event_form_fields WHERE event_id = ?";
        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, 'i', $eventId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Kemudian hapus event
        $query = "DELETE FROM event WHERE event_id = ?";
        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, 'i', $eventId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        header("Location: edit-dashboard.php");
        exit;
    }
}

// Ambil daftar event dari database
$query = "SELECT * FROM event";
$result = mysqli_query($db, $query);
$event = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #e9ecef;
            font-family: 'Arial', sans-serif;
        }

        /* Header dengan gradiasi elegan */
        .header-background {
            background: linear-gradient(120deg, #00d4ff, #007bff);
            padding: 30px;
            border-radius: 0.5rem;
            color: white;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Kontainer utama dengan background dan efek hover */
        .container {
            margin-top: 50px;
        }

        .event-container, .add-event-container, .field-container {
            background-color: #ffffff;
            border-radius: 0.75rem;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .event-container:hover, .add-event-container:hover {
            transform: scale(1.02);
            box-shadow: 0 0 25px rgba(0, 123, 255, 0.3);
        }

        h2 {
            color: #007bff;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        .btn-secondary {
            margin-right: 10px;
            background-color: #6c757d;
            border-color: #6c757d;
            transition: background-color 0.3s, transform 0.3s;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            transition: background-color 0.3s, transform 0.3s;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            transition: background-color 0.3s, transform 0.3s;
        }

        .btn-danger:hover {
            background-color: #c82333;
            transform: translateY(-2px);
        }

        .table-hover tbody tr:hover {
            background-color: #d1ecf1;
        }

        /* Input Focus */
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        /* Field tambahan */
        .field-container {
            border: 1px dashed #007bff;
            padding: 15px;
        }

        .field-container h3 {
            color: #28a745;
        }

    </style>
</head>
<body>
<div class="container">
    <div class="header-background">
        <h1><i class="fas fa-edit"></i> Edit Dashboard</h1>
    </div>
    <div class="text-end mb-3">
        <a href="dashboard_admin.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>
    </div>

    <!-- Daftar Event -->
    <div class="event-container">
        <h2>Daftar Event</h2>
        <table class="table table-striped table-hover">
            <thead class="table-light">
            <tr>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Harga</th>
                <th>Tempat</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Foto</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($event as $event): ?>
                <tr>
                    <td><?= htmlspecialchars($event['event_name']) ?></td>
                    <td><?= htmlspecialchars($event['event_description']) ?></td>
                    <td>Rp<?= number_format($event['registration_fee'], 2) ?></td>
                    <td><?= htmlspecialchars($event['location']) ?></td>
                    <td><?= htmlspecialchars($event['event_date']) ?></td>
                    <td><?= htmlspecialchars($event['event_time']) ?></td>
                    <td>
                        <?php if ($event['image_url']): ?>
                            <img src="<?= $event['image_url'] ?>" alt="Event Image" width="50" class="rounded">
                        <?php else: ?>
                            No Image
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="edit-event.php?event_id=<?= $event['event_id'] ?>" class="btn btn-primary"><i class="fas fa-edit"></i> Edit</a>
                        <form action="" method="post" style="display:inline;">
                            <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
                            <button type="submit" name="delete_event" class="btn btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Form tambah event baru -->
    <div class="add-event-container">
        <h2>Tambah Event Baru</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Nama Event</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Harga</label>
                <input type="number" class="form-control" id="price" name="price" required>
            </div>
            <div class="mb-3">
                <label for="rules" class="form-label">Aturan</label>
                <textarea class="form-control" id="rules" name="rules" rows="4" required></textarea>
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Tempat</label>
                <input type="text" class="form-control" id="location" name="location" required>
            </div>
            <div class="mb-3">
                <label for="event_date" class="form-label">Tanggal</label>
                <input type="date" class="form-control" id="event_date" name="event_date" required>
            </div>
            <div class="mb-3">
                <label for="event_time" class="form-label">Waktu</label>
                <input type="time" class="form-control" id="event_time" name="event_time" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Gambar Event</label>
                <input type="file" class="form-control" id="image" name="image">
            </div>

            <!-- Optional form fields -->
            <div class="field-container">
                <h3>Optional Fields</h3>
                <div id="additional-fields"></div>
                <button type="button" class="btn btn-primary" id="add-field">Tambah Field</button>
            </div>

            <div class="mt-3 text-end">
                <button type="submit" name="add_event" class="btn btn-success"><i class="fas fa-plus"></i> Tambah Event</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('add-field').addEventListener('click', function() {
        const additionalFields = document.getElementById('additional-fields');
        const fieldCount = additionalFields.children.length + 1;

        const newField = document.createElement('div');
        newField.className = 'mb-3';
        newField.innerHTML = `
            <label for="field_name_${fieldCount}" class="form-label">Field ${fieldCount}</label>
            <input type="text" class="form-control" id="field_name_${fieldCount}" name="fields[${fieldCount - 1}][name]" placeholder="Nama Field" required>
            <select class="form-select" name="fields[${fieldCount - 1}][type]" required>
                <option value="text">Text</option>
                <option value="email">Email</option>
                <option value="number">Number</option>
                <option value="date">Date</option>
                <option value="file">File</option>
            </select>
        `;
        additionalFields.appendChild(newField);
    });
</script>
</body>
</html>
