<?php
include '../koneksi.php';

if (isset($_GET['id_theatres'])) {
    $id_theatres = $_GET['id_theatres'];
    $query = $db->query("SELECT id, show_time FROM jadwal WHERE theatre_id = '$id_theatres'");
    
    $result = [];
    while ($row = $query->fetch_assoc()) {
        $result[] = $row;
    }

    echo json_encode($result);
}
?>