<?php
session_start();

if (!isset($_SESSION['user_admin'])) {
  header('location:admin.php');
  exit();
}

require 'connection.php';

$query_kategori = $connection->query("SELECT * FROM kategori");
$query_sedia = $connection->query("SELECT * FROM sedia");
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tambahkan Buku</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>

<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <div class="sidebar-header">
      <img src="image/logo.png" alt="Logo e-library" class="logo" />
    </div>
    <div class="menu-items">
      <a href="admin.php" class="menu-item"><i class="fas fa-home"></i><span>Home</span></a>
      <a href="data_anggota.php" class="menu-item"><i class="fa-solid fa-user-group"></i><span>Data Anggota</span></a>
      <a href="daftar_peminjaman.php" class="menu-item"><i class="fa-solid fa-list"></i><span>Riwayat Peminjaman</span></a>
      <a href="daftar_buku.php" class="menu-item"><i class="fas fa-bookmark"></i><span>Daftar Buku</span></a>
    </div>
    <a href="logout-confirm.php" class="menu-item logout"><i class="fa-solid fa-right-from-bracket"></i><span>Log Out</span></a>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <h1>Tambahkan Buku</h1>

    <form action="simpan_buku.php" method="POST" enctype="multipart/form-data" class="form-tambah-buku">
      <label>Judul</label>
      <input name="Judul" type="text" required>

      <label>Author</label>
      <input name="Author" type="text" required>

      <label>Sinopsis</label>
      <textarea name="Sinopsis" required></textarea>

      <label>Publisher</label>
      <input name="Publisher" type="text" required>

      <label>Tahun</label>
      <input name="Tahun" type="number" required>

      <label>Kota</label>
      <input name="Kota" type="text" required>

      <label>ISBN</label>
      <input name="ISBN" type="text" required>

      <label>Kategori</label>
      <select name="Kategori" required>
        <option value="">--Pilih Kategori--</option>
        <?php while ($kat = mysqli_fetch_assoc($query_kategori)): ?>
          <option value="<?= $kat['id_kategori']; ?>"><?= htmlentities($kat['deskripsi']); ?></option>
        <?php endwhile; ?>
      </select>

      <label>Ketersediaan</label>
      <select name="Sedia" required>
        <option value="">--Pilih--</option>
        <?php while ($sedia = mysqli_fetch_assoc($query_sedia)): ?>
          <option value="<?= $sedia['id_sedia_buku']; ?>"><?= htmlentities($sedia['sedia_buku']); ?></option>
        <?php endwhile; ?>
      </select>

      <label>Cover</label>
      <input name="Cover" type="file" accept="image/*" required><br><br>

      <input type="submit" value="Tambahkan Buku">
    </form>
  </div>

  <script src="all.js"></script>
</body>

</html>