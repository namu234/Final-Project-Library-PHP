<?php
session_start();

if (!isset($_SESSION['user_admin'])) {
  header('location:main.php');
  exit();
}

require 'connection.php';

if (!$connection) {
  die("Koneksi database gagal: " . mysqli_connect_error());
}

$sql = "SELECT * FROM buku b LEFT JOIN kategori k ON b.id_kategori = k.id_kategori";
$result = mysqli_query($connection, $sql);

if (!$result) {
  die("Query Error: " . mysqli_error($connection));
}

?>


<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>E-Library - Sistem Perpustakaan Digital</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <div class="sidebar-header">
      <img src="image/logo.png" alt="Logo e-library" class="logo">
    </div>

    <div class="menu-items">
      <a href="admin.php" class="menu-item">
        <i class="fas fa-home"></i>
        <span>Home</span>
      </a>
      <a href="data_anggota.php" class="menu-item">
        <i class="fa-solid fa-user-group"></i>
        <span>Data Anggota</span>
      </a>
      <a href="daftar_peminjaman.php" class="menu-item">
        <i class="fa-solid fa-list"></i>
        <span>Riwayat Peminjaman</span>
      </a>
      <a href="daftar_buku.php" class="menu-item">
        <i class="fas fa-bookmark"></i>
        <span>Daftar Buku</span>
      </a>
    </div>

    <a href="logout-confirm.php" class="menu-item logout">
      <i class="fa-solid fa-right-from-bracket"></i>
      <span>Log Out</span>
    </a>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="welcome-section">
      <h2>Halo, admin! Selamat datang di Read and Relax</h2>
      <p>Sistem perpustakaan digital modern untuk mengakses buku dari mana saja</p>
    </div>

    <div class="stats-section">
      <div class="stat-card stat-1">
        <div class="stat-icon">
          <i class="fas fa-book"></i>
        </div>
        <div class="stat-info">
          <?php
          include 'connection.php';
          $query = mysqli_query($connection, "SELECT COUNT(*) AS total FROM buku");
          $row = mysqli_fetch_assoc($query);
          $total_buku = $row['total'];
          ?>
          <h3><?php echo $row['total']; ?></h3>
          <p>Total Buku</p>
        </div>
      </div>

      <div class="stat-card stat-2">
        <div class="stat-icon">
          <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
          <?php
          include 'connection.php';
          $query = mysqli_query($connection, "SELECT COUNT(*) AS total FROM anggota");
          $row = mysqli_fetch_assoc($query);
          $total_buku = $row['total'];
          ?>
          <h3><?php echo $row['total']; ?></h3>
          <p>Anggota Aktif</p>
        </div>
      </div>

      <div class="stat-card stat-3">
        <div class="stat-icon">
          <i class="fas fa-book-reader"></i>
        </div>
        <div class="stat-info">
          <?php
          include 'connection.php';
          $query = mysqli_query($connection, "SELECT COUNT(*) AS total FROM detail_peminjaman WHERE tgl_kembali IS NULL");
          $row = mysqli_fetch_assoc($query);
          $total_buku = $row['total'];
          ?>
          <h3><?php echo $row['total']; ?></h3>
          <p>Sedang Dipinjam</p>
        </div>
      </div>

      <div class="stat-card stat-4">
        <div class="stat-icon">
          <i class="fas fa-clock"></i>
        </div>
        <div class="stat-info">
          <?php
          include 'connection.php';

          // total buku
          $query_total = mysqli_query($connection, "SELECT COUNT(*) AS total FROM buku");
          $row_total = mysqli_fetch_assoc($query_total);
          $total_buku = $row_total['total'];

          // total buku tersedia
          $query_sedia = mysqli_query($connection, "SELECT COUNT(*) AS tersedia FROM buku WHERE id_sedia_buku = 1");
          $row_sedia = mysqli_fetch_assoc($query_sedia);
          $total_sedia = $row_sedia['tersedia'];

          //hitung ketersediaan
          $presentase = 0;
          if ($total_buku > 0) {
            $presentase = ($total_sedia / $total_buku) * 100;
          }
          ?>
          <h3><?php echo number_format($presentase); ?>%</h3>
          <p>Ketersediaan Buku</p>
        </div>
      </div>
    </div>


    <script src="all.js"></script>
</body>

</html>