<?php
$host = "localhost";
$dbname = "db_bioskop";
$username = "root";
$password = "";

$db = mysqli_connect($host, $username, $password, $dbname);

if ($db->connect_error) {
    die("Koneksi gagal: " . $db->connect_error);
}

?>
