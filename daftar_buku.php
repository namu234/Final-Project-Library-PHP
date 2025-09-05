<?php
session_start();

if (!isset($_SESSION['user_admin'])) {
  header('location:admin.php');
  exit();
}

require 'connection.php';

if (!$connection) {
  die("Koneksi database gagal: " . mysqli_connect_error());
}

$sql = "SELECT * FROM buku b LEFT JOIN kategori k ON b.id_kategori = k.id_kategori
        LEFT JOIN sedia s ON b.id_sedia_buku = s.id_sedia_buku";
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
</head>

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
    <h1>Daftar Buku</h1>
    <br>
    <div class="stats-section">
      <div class="stat-card stat-1">
        <div class="stat-icon">
          <i class="fas fa-book"></i>
        </div>
        <div class="stat-info">
          <?php
          $query = mysqli_query($connection, "SELECT COUNT(*) AS total FROM buku");
          $row = mysqli_fetch_assoc($query);
          $total_buku = $row['total'];
          ?>
          <h3><?php echo $row['total']; ?></h3>
          <p>Total Buku</p>
        </div>
      </div>

      <a href="tambah_buku.php" class="btn-tambah-buku">
        <i class="fa fa-plus"></i> Tambah Buku
      </a>
    </div>


    <!-- Daftar Buku -->
    <div class="book-list">
      <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($data = mysqli_fetch_assoc($result)): ?>
          <div class="book-card">
            <img src="image/<?= $data['cover']; ?>" alt="<?= $data['judul']; ?>">
            <div class="book-info">
              <h3><?= $data['judul']; ?></h3>
              <p><strong>Author:</strong> <?= $data['author']; ?></p>
              <p><strong>Kategori:</strong> <?= $data['deskripsi']; ?></p>
              <p><strong>Tahun:</strong> <?= $data['tahun']; ?></p>

              <div class="book-actions">
                <a href="edit_detail_buku.php?id_buku=<?= $data['id_buku'] ?>" class="btn-edit">Edit Detail</a>

              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>Tidak ada buku dalam database.</p>
      <?php endif; ?>
    </div>



    <script src="all.js"></script>
</body>

</html>