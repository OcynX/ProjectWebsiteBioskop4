<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'koneksi.php';
$isLoggedIn = isset($_SESSION['user_id']); // Periksa apakah user login
?>

<style>

    .navbar-nav .nav-link {
        transition: all 0.3s ease-in-out;
    }

    .navbar-nav .nav-link:hover {
        transform: scale(1.0);
        color: red ; 
    }
</style>
<!-- Navbar Start -->
<nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark" style="box-shadow: 0px 4px 10px rgba(255, 0, 0, 0.6);" >
    <div class="container ">
        <a class="navbar-brand" href="HalamanUser.php">
            <i class='bx bx-movie-play'></i> Cinema12
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="theater.php">Theatre</a>
                </li>
                <?php if ($isLoggedIn): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="orderhistory.php">Riwayat Pesanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout </i></a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login </i></a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>


