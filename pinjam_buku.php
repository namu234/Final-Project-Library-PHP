<?php
session_start();
if (!isset($_SESSION['id_user'])) {
  header('location: login.php');
  exit;
}
include('connection.php');

$id_buku_dari_link = isset($_GET['id_buku']) ? $_GET['id_buku'] : '';
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>E-Library - Pinjaman Buku Saya</title>
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

  <!-- Form Pinjaman -->
  <div class="main-content">
    <h1>Pinjam Buku</h1>
    <form action="pinjam.php" method="POST">
      <div class="form-tambah-buku">
        <label>Peminjam :</label>
        <p><?= $_SESSION['uname']; ?> </p><br />

        <label for="id_buku">Buku yang dipinjam :</label>
        <select name="id_buku" id="id_buku" required>
          <option value="">--Pilih Buku--</option>
          <?php
          include 'connection.php';
          $query = mysqli_query($connection, "SELECT * FROM buku WHERE stok > 0");

          while ($buku = mysqli_fetch_assoc($query)) {
            $selected = ($buku['id_buku'] == $id_buku_dari_link) ? 'selected' : '';
            echo "<option value='" . $buku['id_buku'] . "'$selected>" . $buku['judul'] . " (stok: " . $buku['stok'] . ")</option>";
          }
          ?>
        </select>

        <input type="submit" name="pinjam" value="Pinjam Buku">
        <br>
        <button><a href="katalog.php">Kembali ke Katalog</a></button>
      </div>
    </form>
    <br>

  </div>

  <script src="all.js"></script>
</body>

</html>