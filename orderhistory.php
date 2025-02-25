<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include "koneksi.php";

// Ambil user_id dari session
$user_id = $_SESSION['user_id'];

// Query untuk mendapatkan data riwayat pemesanan berdasarkan user_id
$query = "SELECT 
    tickets.customer_name AS nama_customer,
    users.email AS email_customer,
    movies.title AS judul_film,
    theatres.name AS nama_theater,
    studio.nama_studio AS nama_studio,
    DATE_FORMAT(jadwal.show_time, '%H:%i') AS jadwal_tayang,   
    tickets.seat_number AS nomor_kursi,
    DATE_FORMAT(penjualan.order_at, '%d-%m-%Y') AS waktu_order
FROM penjualan
LEFT JOIN users ON penjualan.users_id = users.id
LEFT JOIN movies ON penjualan.movies_id = movies.id
LEFT JOIN theatres ON penjualan.theatres_id = theatres.id
LEFT JOIN studio ON penjualan.studio_id = studio.id_studio
LEFT JOIN jadwal ON penjualan.jadwal_id = jadwal.id
LEFT JOIN tickets ON penjualan.tiket_id = tickets.idtiket
WHERE penjualan.users_id = ?";

$stmt = $db->prepare($query);
if (!$stmt) {
    die("Statement preparation failed: " . $db->error);
}

// Bind parameter user_id untuk prepared statement
$stmt->bind_param("i", $user_id);

// Eksekusi query
$stmt->execute();

// Ambil hasil query
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title >Riwayat Pesanan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="user.css">
</head>
<body style="background-color: black;">
<?php include"Layout/header.php";?>
    
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div style="
    background: linear-gradient(to bottom right, #d00000, #800000); 
    color: white; 
    border-radius: 20px; 
    padding: 30px; 
    margin-top: 20px; 
    margin-bottom: 10px; 
    margin: 40px; 
    box-shadow: inset -10px -10px 15px rgba(0, 0, 0, 0.8), 
                inset 10px 10px 15px rgba(255, 255, 255, 0.2);
    font-size: 13px;
    text-align: start;
    width: 600px;
    height: auto; /* Atur height otomatis jika konten melebihi */
    max-height: 200px; /* Tetapkan maksimum tinggi */
    overflow-y: auto; /* Tampilkan scrollbar jika diperlukan */
    word-wrap: break-word; /* Memastikan kata panjang terpotong */
    overflow-wrap: break-word;
    ">

                <p><strong>Nama:</strong> <?php echo htmlspecialchars($row['nama_customer']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($row['email_customer']); ?></p>
                <p><strong>Judul Film:</strong> <?php echo htmlspecialchars($row['judul_film']); ?></p>
                <p><strong>Nama Theater:</strong> <?php echo htmlspecialchars($row['nama_theater']); ?></p>
                <p><strong>Nama Studio:</strong> <?php echo htmlspecialchars($row['nama_studio']); ?></p>
                <p><strong>Waktu Tayang:</strong> <?php echo htmlspecialchars($row['jadwal_tayang']); ?></p>
                <p><strong>Nomor Kursi:</strong> <?php echo htmlspecialchars($row['nomor_kursi']); ?></p>
                <p style="display: flex; align-items:center; "><strong><i class='bx bxs-time-five' style="font-size: 20px; vertical-align: middle;">:</i></strong> <?php echo htmlspecialchars($row['waktu_order']); ?></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="text" style="display: flex; align-items: center; justify-content: center;  height: 500px;">
    <p class="text-center" style="color: white; font-size: 20px;">Tidak ada riwayat pemesanan.</p>
</div>

        
    <?php endif; ?>
</body>
</html>
