<?php
session_start();
include '../koneksi.php';

// Ambil data theatres
$theatres = $db->query("SELECT id, name FROM theatres");

// Cek jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $theatres_id = $_POST['theatres_id'];
    $studio_id = $_POST['studio_id'];
    $jadwal_id = $_POST['jadwal_id'];
    $jumlah_kursi = $_POST['jumlah_kursi'];

    $stmt = $db->prepare("SELECT nama_studio FROM studio WHERE id_studio = ?");
    $stmt->bind_param("i", $studio_id);
    $stmt->execute();
    $studio_result = $stmt->get_result()->fetch_assoc();
    $studio_initial = strtoupper(substr($studio_result['nama_studio'], 0, 1));

    $status = 'available'; // Status default
    for ($i = 1; $i <= $jumlah_kursi; $i++) {
        $seat_number = $studio_initial . $i; // Format penamaan kursi
        $stmt = $db->prepare("INSERT INTO seats (theatres_id, studio_id, jadwal_id, seat_number, status) 
                              VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiss", $theatres_id, $studio_id, $jadwal_id, $seat_number, $status);
        $stmt->execute();
    }

    echo "<script>alert('Kursi berhasil ditambahkan!');</script>";
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kursi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="admin.css">
</head>
<body>


    <div class="container mt-5">
        
        <h1>Tambah Kursi</h1>
        <form method="POST">

            <!-- Pilih Theatre -->
            <div class="mb-3">
                <label for="theatres_id" class="form-label">Theatre</label>
                <select name="theatres_id" id="theatres_id" class="form-select" required>
                    <option value="" disabled selected>Pilih Theatre</option>
                    <?php while ($row = $theatres->fetch_assoc()): ?>
                        <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Pilih Studio -->
            <div class="mb-3">
                <label for="studio_id" class="form-label">Studio</label>
                <select name="studio_id" id="studio_id" class="form-select" required>
                    <option value="" disabled selected>Pilih Studio</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="jadwal_id" class="form-label">Jadwal</label>
                <select name="jadwal_id" id="jadwal_id" class="form-select" required>
                    <option value="" disabled selected>Pilih jadwal</option>
                </select>
            </div>

            <!-- Input Jumlah Kursi -->
            <div class="mb-3">
                <label for="jumlah_kursi" class="form-label">Jumlah Kursi</label>
                <input type="number" name="jumlah_kursi" id="jumlah_kursi" class="form-control" placeholder="Masukkan jumlah kursi" required>
            </div>

            <div class="pesan">
                <p style="font-size: 14px;"><i><b>*Perhatian: </b>Saat ini, sistem hanya mendukung pengaturan jumlah kursi dalam satu kali input. Sebagai contoh, jika Anda telah menambahkan 100 kursi, kursi tambahan tidak dapat langsung ditambahkan di kemudian hari.
                Jika Anda ingin mengubah jumlah kursi, harap hapus data kursi yang ada terlebih dahulu sebelum menambahkan jumlah baru. Kami sedang mengembangkan fitur untuk mendukung penambahan kursi secara bertahap. Terima kasih atas pengertian Anda.</i>&#128512</p>
            </div>

            <!-- Tombol Submit -->
            <button type="submit" class="btn btn-primary">Tambah Kursi</button>
        </form>

        
    </div>

    <script>
        $('#theatres_id').change(function() {
    const id_theatres = $(this).val(); // ID elemen benar adalah theatres_id

    // Kosongkan dropdown Studio
    $('#studio_id').html('<option value="" disabled selected>Pilih Studio</option>');

    if (id_theatres) { // Pastikan id_theatres memiliki nilai
        $.ajax({
            url: 'get_studio.php',
            method: 'GET',
            data: { id_theatres: id_theatres },
            dataType: 'json',
            success: function(data) {
                data.forEach(function(studio) {
                    $('#studio_id').append(
                        `<option value="${studio.id_studio}">${studio.nama_studio}</option>`
                    );
                });
            },
            error: function() {
                alert('Gagal mengambil data studio.');
            }
        });
    }

    $('#studio_id').change(function () {
        const id_theatres = $('#theatres_id').val(); // Ambil ID theatre yang dipilih
    
        // Kosongkan dropdown jadwal
        $('#jadwal_id').html('<option value="" disabled selected>Pilih jadwal</option>');
    
        if (id_theatres) { // Pastikan theatre dipilih
            $.ajax({
                url: 'get_jadwal.php',
                method: 'GET',
                data: { id_theatres: id_theatres },
                dataType: 'json',
                success: function (data) {
                    data.forEach(function (jadwal) {
                        $('#jadwal_id').append(
                            `<option value="${jadwal.id}">${jadwal.show_time}</option>`
                        );
                    });
                },
                error: function () {
                    alert('Gagal mengambil data jadwal.');
                }
            });
        }
    });
    
});

    </script>
</body>
</html>
