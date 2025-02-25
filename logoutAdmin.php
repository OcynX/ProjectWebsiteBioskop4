<?php
session_start(); // Mulai session
session_destroy(); // Menghancurkan session
header("Location: loginAdmin.php"); // Redirect ke halaman login setelah logout
exit();
?>
