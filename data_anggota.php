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
    <h1>Data Anggota</h1>
    <div class="welcome">
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
    </div>
    <div id="hasil-anggota">
      <?php
      $q = mysqli_query($connection, "SELECT * FROM anggota");

      if (mysqli_num_rows($q) > 0) {
        echo '<table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th>ID</th>';
        echo '<th>Nama</th>';
        echo '<th>TTL</th>';
        echo '<th>Jenis Kelamin</th>';
        echo '<th>Username</th>';
        echo '<th>Email</th>';
        echo '<th>Aksi</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';


        while ($data = mysqli_fetch_object($q)) {
          echo '<tr>';
          echo '<td>' . $data->id_user . '</td>';
          echo '<td>' . $data->nama . '</td>';
          echo '<td>' . $data->TTL . '</td>';
          echo '<td>' . $data->jenis_kelamin . '</td>';
          echo '<td>' . $data->username . '</td>';
          echo '<td>' . $data->email . '</td>';
          echo '<td>';
          echo '<a class="delete" href="hapus.php?id=' . $data->id_user . '" onclick="return confirm(\'Yakin ingin menghapus data ini?\')">Hapus</a>';
          echo '</td>';
          echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
      } else {
        echo 'Data tidak tersedia';
      }
      ?>
    </div>
  </div>


  <script src="all.js"></script>
</body>

</html>