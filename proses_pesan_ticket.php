<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    die("Anda harus login untuk melakukan transaksi.");
}

// Ambil data dari form
$user_id = $_POST['user_id'];
$jadwal_id = $_POST['jadwal_id'];
$theatre_id = $_POST['theatre_id'];
$studio_id = $_POST['studio_id'];
$movie_id = $_POST['movie_id'];
$seats_id = $_POST['seats_id'];
$seat = $_POST['seat'];
$nama = $_POST['nama'];
$email = $_POST['email'];
$total_ticket = $_POST['total_ticket'];
$total_harga = $_POST['total_harga'];  // Total harga keseluruhan (ini tidak akan digunakan langsung untuk tiap tiket)

// Validasi data
if (empty($studio_id) || empty($movie_id) || empty($total_ticket) || empty($nama) || empty($email)) {
    die("Error: Semua field harus diisi.");
}

$seats = explode(", ", $seat);
$ticketPrice = $total_harga / $total_ticket; // Harga tiket per kursi

// Proses penyimpanan data ke dalam tabel penjualan
$queryPenjualan = "INSERT INTO penjualan (movies_id, theatres_id, studio_id, jadwal_id, seats_id, total, users_id)
                   VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmtPenjualan = $db->prepare($queryPenjualan);
$stmtPenjualan->bind_param("iiiiiii", $movie_id, $theatre_id, $studio_id, $jadwal_id, $seats_id, $ticketPrice, $user_id);
$stmtPenjualan->execute();
$penjualan_id = $stmtPenjualan->insert_id;

// Array untuk menyimpan id_ticket yang baru saja di-generate
$ticket_ids = [];

// Proses penyimpanan data tiket yang dipilih
foreach ($seats as $selectedSeat) {
    // Insert tiket per kursi dengan harga tiket individual
    $queryTicket = "INSERT INTO tickets (movie_id, jadwal_id, customer_name, customer_email, price, user_id, seat_number) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmtTicket = $db->prepare($queryTicket);
    $stmtTicket->bind_param("iissiis", $movie_id, $jadwal_id, $nama, $email, $ticketPrice, $user_id, $selectedSeat);
    $stmtTicket->execute();
    
    // Ambil id_ticket yang baru saja di-generate
    $ticket_id = $stmtTicket->insert_id;
    $ticket_ids[] = $ticket_id;
}

// Update status kursi
foreach ($seats as $selectedSeat) {
    $queryUpdateSeat = "UPDATE seats SET status = 'booked' WHERE jadwal_id = ? AND seat_number = ?";
    $stmtUpdateSeat = $db->prepare($queryUpdateSeat);
    $stmtUpdateSeat->bind_param("is", $jadwal_id, $selectedSeat);
    $stmtUpdateSeat->execute();
}

// Update tabel penjualan dengan tiket_id
$ticket_ids_str = implode(", ", $ticket_ids); // Gabungkan semua id_ticket menjadi string
$queryUpdatePenjualan = "UPDATE penjualan SET tiket_id = ? WHERE id_penjualan = ?";
$stmtUpdatePenjualan = $db->prepare($queryUpdatePenjualan);
$stmtUpdatePenjualan->bind_param("si", $ticket_ids_str, $penjualan_id);
$stmtUpdatePenjualan->execute();

echo "<script>alert('Pesan Ticket Berhasil')</script>";

echo "<script>
        setTimeout(function() {
            window.location.href = 'tiket_berhasil.php?penjualan_id=$penjualan_id';
        }, 1000);
      </script>";
?>