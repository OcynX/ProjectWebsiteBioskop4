<?php
include '../koneksi.php';

// Ambil id jadwal dari parameter URL
if (!isset($_GET['id'])) {
    die("ID Jadwal tidak ditemukan.");
}
$jadwal_id = $_GET['id'];

// Ambil data jadwal berdasarkan id
$queryJadwal = "SELECT * FROM jadwal WHERE id = ?";
$stmtJadwal = $db->prepare($queryJadwal);
$stmtJadwal->bind_param("i", $jadwal_id);
$stmtJadwal->execute();
$resultJadwal = $stmtJadwal->get_result();
$jadwal = $resultJadwal->fetch_assoc();

if (!$jadwal) {
    die("Data jadwal tidak ditemukan.");
}

// Fetch data untuk dropdown
$movies = $db->query("SELECT id, title FROM movies");
$theatres = $db->query("SELECT id, name FROM theatres");
$studios = $db->query("SELECT id_studio, nama_studio FROM studio");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $movie_id = $_POST['movie_id'];
    $theatre_id = $_POST['theatre_id'];
    $studio_id = $_POST['studio_id'];
    $show_time = $_POST['show_time'];
    $show_time2 = $_POST['show_time2'];
    $show_time3 = $_POST['show_time3'];
    $show_time4 = $_POST['show_time4'];
    $price = $_POST['price'];

    // Update data jadwal
    $stmtUpdate = $db->prepare("UPDATE jadwal SET movie_id = ?, theatre_id = ?, studio_id = ?, show_time = ?, show_time2 = ?, show_time3 = ?, show_time4 = ?, price = ? WHERE id = ?");
    $stmtUpdate->bind_param("iiissssdi", $movie_id, $theatre_id, $studio_id, $show_time, $show_time2, $show_time3, $show_time4, $price, $jadwal_id);

    if ($stmtUpdate->execute()) {
        echo "<script>alert('Jadwal berhasil diperbarui'); window.location.href='jadwal.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui jadwal');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Jadwal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            background-color: black;
            display: flex;
            overflow: hidden;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
            color: white;
            overflow-y: auto;
            max-height: 100vh;
        }
        .form-container {
            background: #1c1c1c;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.5);
            width: 100%;
        }
        .form-container label {
            color: white;
        }
        .form-container .form-control {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
        }
        .form-container .form-select {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
        }
        .form-container .btn {
            background: white;
            color: black;
            border: none;
            width: 100%;
        }
        .form-container .btn:hover {
            background: gray;
            color: white;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3 class="p-3">CINEMA21</h3>
        <a href="halamanAdmin.php">Dashboard</a>
        <a href="management_ticket.php">Management Tiket</a>
        <a href="managementTheater.php">Management Bioskop</a>
        <a href="gudangdatafilm.php">Manajemen Film</a>
        <a href="LaporanPenjualan.php">Laporan Penjualan</a>
    </div>

    <div class="content">
        <h1>Edit Jadwal</h1>
        <div class="form-container">
            <form action="" method="POST">
                <!-- Dropdown Judul Film -->
                <div class="mb-3">
                    <label for="movie_id" class="form-label">Judul Film</label>
                    <select name="movie_id" id="movie_id" class="form-select" required>
                        <option value="" disabled>Pilih Judul Film</option>
                        <?php while ($row = $movies->fetch_assoc()): ?>
                            <option value="<?= $row['id'] ?>" <?= $row['id'] == $jadwal['movie_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($row['title']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Dropdown Theatre -->
                <div class="mb-3">
                    <label for="theatre_id" class="form-label">Theatre</label>
                    <select name="theatre_id" id="theatre_id" class="form-select" required>
                        <option value="" disabled>Pilih Theatre</option>
                        <?php while ($row = $theatres->fetch_assoc()): ?>
                            <option value="<?= $row['id'] ?>" <?= $row['id'] == $jadwal['theatre_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($row['name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Dropdown Studio -->
                <div class="mb-3">
                    <label for="studio_id" class="form-label">Studio</label>
                    <select name="studio_id" id="studio_id" class="form-select" required>
                        <option value="" disabled>Pilih Studio</option>
                        <?php while ($row = $studios->fetch_assoc()): ?>
                            <option value="<?= $row['id_studio'] ?>" <?= $row['id_studio'] == $jadwal['studio_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($row['nama_studio']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Input Show Time -->
                <div class="mb-3">
                    <label for="show_time" class="form-label">Jadwal Tayang 1</label>
                    <input type="datetime-local" class="form-control" id="show_time" name="show_time" value="<?= htmlspecialchars($jadwal['show_time']) ?>" required>
                </div>


                <?php for ($i = 2; $i <= 4; $i++): ?>
                    <div class="mb-3">
                        <label for="show_time<?= $i ?>" class="form-label">Jadwal Tayang <?= $i ?></label>
                        <input type="datetime-local" class="form-control" id="show_time<?= $i ?>" name="show_time<?= $i ?>" value="<?= htmlspecialchars($jadwal["show_time" . ($i === 1 ? "" : $i)]) ?>" required>
                    </div>
                <?php endfor; ?>

                <div class="mb-3">
                    <label for="price" class="form-label">Harga</label>
                    <input type="number" class="form-control" name="price" required value="<?= htmlspecialchars($jadwal['price']) ?>">
                </div>

                <!-- Tombol Submit -->
                <button type="submit" class="btn btn-primary">Perbarui Jadwal</button>
            </form>
        </div>
    </div>
</body>
</html>
