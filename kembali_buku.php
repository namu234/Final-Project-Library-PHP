<?php
session_start();
if (!isset($_SESSION['id_user'])) {
  header('location: login.php');
  exit;
}
include('connection.php');

$query_pinjam = mysqli_query(
  $connection,
  "SELECT dp.id_pinjam, b.judul 
    FROM detail_peminjaman dp 
    JOIN buku b ON dp.id_buku = b.id_buku 
    WHERE dp.id_user = '{$_SESSION['id_user']}' 
    AND dp.tgl_kembali IS NULL"
);
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

  <!-- Form Kembali -->
  <div class="main-content">
    <h1>Pengembalian Buku</h1>

    <?php if (mysqli_num_rows($query_pinjam) == 0) { ?>
      <p>Kamu **belum meminjam buku** atau **sudah mengembalikan semuanya**.</p>
      <br>
      <a href="pinjam_buku.php"><button>Lihat Pinjaman Saya</button></a>
    <?php } else { ?>
      <form action="kembali.php" method="POST">
        <div class="form-tambah-buku">
          <input name="id_user" type="hidden" value="<?= $_SESSION['id_user']; ?>">

          <label for="">Peminjam</label>
          <input type="text" value="<?= $_SESSION['uname']; ?>" disabled>

          <label for="id_pinjam">Pilih Buku yang dipinjam</label>
          <select name="id_pinjam" required>
            <option value="">--Pilih Buku--</option>
            <?php while ($pinjam = mysqli_fetch_assoc($query_pinjam)) { ?>
              <option value="<?= $pinjam['id_pinjam']; ?>"> <?= $pinjam['judul']; ?> </option>
            <?php } ?>
          </select>

          <input type="submit" name="kembalikan" value="Kembalikan Buku">
        </div>
      </form>
    <?php } ?>
  </div>

  <script src="all.js"></script>
</body>

</html>