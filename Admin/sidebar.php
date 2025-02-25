<?php
if(session_status()=== PHP_SESSION_NONE){
    session_start();
}
?>
<div class="sidebar">
  <h3 class="p-3">CINEMA21</h3>
  <a href="halamanAdmin.php">Dashboard</a>
  <a href="management_ticket.php">Management Tiket</a>

  <!-- Dropdown untuk Management Bioskop -->
  <!-- <div class="dropdown">
    <a class="dropdown-toggle" href="managementTheater.php" id="managementTheaterDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
      Management Bioskop
    </a>
    <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="managementTheaterDropdown">
      <li><a class="dropdown-item" href="studio.php">Management Studio</a></li>
      <li><a class="dropdown-item" href="jadwal.php">Jadwal Tayang</a></li>
    </ul>
  </div> -->
  <a class="nav-link px-0 align-middle" data-bs-toggle="collapse" href="#collapseManagementTheater" role="button" aria-expanded="false" aria-controls="collapseManagementTheater">
    Management Bioskop
  </a>
  <div class="collapse" id="collapseManagementTheater">
    <ul class="nav flex-column ms-3">
      <li class="nav-item">
        <a class="nav-link" href="studio.php">Management Studio</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="jadwal.php">Jadwal Tayang</a>
      </li>
    </ul>
  </div>
  <a href="gudangdatafilm.php">Manajemen Film</a>
  <a href="LaporanPenjualan.php">Laporan Penjualan</a>
  <a href="../logoutAdmin.php">Logout</a>
</div>
