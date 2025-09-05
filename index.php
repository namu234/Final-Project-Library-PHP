<?php
session_start();
$username = isset($_SESSION['uname']) ? $_SESSION['uname'] : 'Pengunjung';
include 'connection.php';

if (!isset($_SESSION['id_user'])) {
  header('Location: main.php');
  exit;
}

$id_user = $_SESSION['id_user'];

$query_foto = mysqli_query($connection, "SELECT foto FROM anggota WHERE id_user = '$id_user'");
$data_foto = mysqli_fetch_assoc($query_foto);
$foto = (!empty($data_foto['foto'])) ? $data_foto['foto'] : 'Default Profile.jpeg';

$sql = "SELECT * FROM buku b 
        LEFT JOIN kategori k ON b.id_kategori = k.id_kategori 
        LEFT JOIN sedia s ON b.id_sedia_buku = s.id_sedia_buku";

$result = mysqli_query($connection, $sql);
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
      <a href="index.php" class="menu-item">
        <i class="fas fa-home"></i>
        <span>Home</span>
      </a>
      <a href="bb_online.php" class="menu-item">
        <i class="fas fa-book-open"></i>
        <span>Baca Buku Online</span>
      </a>
      <a href="pinjaman.php" class="menu-item">
        <i class="fas fa-search"></i>
        <span>Pinjaman Buku Saya</span>
      </a>
      <a href="katalog.php" class="menu-item">
        <i class="fas fa-bookmark"></i>
        <span>Katalog Buku</span>
      </a>
    </div>

    <a href="logout-confirm.php" class="menu-item logout">
      <i class="fa-solid fa-right-from-bracket"></i>
      <span>Log Out</span>
    </a>
  </div>

  <!-- Profile -->
  <div class="profile-wrapper">
    <a href="profile.php">
      <img src="image/<?= htmlentities($foto) ?>" alt="Foto Profil" class="profile-photo">
    </a>
  </div>


  <!-- Main Content -->
  <div class="main-content">
    <div class="welcome-section">
      <h2>Halo, <?= htmlspecialchars($username); ?>! Selamat datang di Read and Relax</h2>
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

    <h2>Baca Buku Kesukaanmu</h2>
    <div class="book-list">
      <?php while ($data = mysqli_fetch_assoc($result)): ?>
        <a href="sinopsis.php?id_buku=<?= $data['id_buku']; ?>" class="book-card">
          <img src="image/<?php echo $data['cover']; ?>" alt="<?php echo $data['judul']; ?>">
          <div class="book-info">
            <h3><?php echo $data['judul']; ?></h3>
            <p><strong>Author:</strong> <?php echo $data['author']; ?></p>
            <p><strong>Kategori:</strong> <?php echo $data['deskripsi']; ?></p>
            <p><strong>Tahun:</strong> <?php echo $data['tahun']; ?></p>
          </div>
        </a>
      <?php endwhile; ?>
    </div>

    <script src="all.js"></script>
</body>

</html>