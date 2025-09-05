<?php
session_start();

if (!isset($_SESSION['user_admin'])) {
  header('location:admin.php');
  exit();
}

require 'connection.php';

$id = isset($_GET['id_buku']) ? $_GET['id_buku'] : null;

if (!$id) {
  die("ID buku tidak ditemukan di URL.");
}

$getdata = $connection->query("SELECT * FROM buku WHERE id_buku = '$id' ");
$baris = $getdata->fetch_object();

$query_kategori = $connection->query("SELECT * FROM kategori");
$query_sedia = $connection->query("SELECT * FROM sedia");
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Buku - E-Library</title>
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
    <h1>Edit Buku</h1>

    <form action="edit_buku.php" method="POST" enctype="multipart/form-data" class="form-tambah-buku">
      <input type="hidden" name="id_buku" value="<?= $baris->id_buku ?>">

      <label>Judul</label>
      <input name="Judul" type="text" value="<?= htmlentities($baris->judul) ?>">

      <label>Author</label>
      <input name="Author" type="text" value="<?= htmlentities($baris->author) ?>">

      <label>Sinopsis</label>
      <textarea name="Sinopsis"><?= htmlentities($baris->sinopsis) ?></textarea>

      <label>Publisher</label>
      <input name="Publisher" type="text" value="<?= htmlentities($baris->publisher) ?>">

      <label>Tahun</label>
      <input name="Tahun" type="number" value="<?= htmlentities($baris->tahun) ?>">

      <label>Kota</label>
      <input name="Kota" type="text" value="<?= htmlentities($baris->kota) ?>">

      <label>ISBN</label>
      <input name="ISBN" type="text" value="<?= htmlentities($baris->ISBN) ?>">

      <label>Kategori</label>
      <select name="Kategori">
        <option value="">--Pilih Kategori--</option>
        <?php while ($kat = mysqli_fetch_assoc($query_kategori)): ?>
          <option value="<?= $kat['id_kategori']; ?>" <?= ($kat['id_kategori'] == $baris->id_kategori) ? 'selected' : '' ?>>
            <?= htmlentities($kat['deskripsi']); ?>
          </option>
        <?php endwhile; ?>
      </select>

      <label>Ketersediaan</label>
      <select name="Sedia">
        <option value="">--Pilih--</option>
        <?php while ($sedia = mysqli_fetch_assoc($query_sedia)): ?>
          <option value="<?= $sedia['id_sedia_buku']; ?>" <?= ($sedia['id_sedia_buku'] == $baris->id_sedia_buku) ? 'selected' : '' ?>>
            <?= htmlentities($sedia['sedia_buku']); ?>
          </option>
        <?php endwhile; ?>
      </select>

      <label>Cover</label>
      <img src="image/<?= htmlentities($baris->cover) ?>" alt="Cover Buku" style="width: 100px;height: 150px;"><br>
      <input name="Cover" type="file"><br><br>

      <input type="submit" value="Edit Buku">
    </form>
  </div>

  <script src="all.js"></script>
</body>

</html>