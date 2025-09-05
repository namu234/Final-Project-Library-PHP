<?php
session_start();
$username = isset($_SESSION['uname']) ? $_SESSION['uname'] : 'Pengunjung';
require 'connection.php';

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
  <title>E-Library - Ketersediaan Buku</title>
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


  <!-- Kategori -->
  <div class="main-content">
    <?php
    // Ambil semua kategori
    $query_kategori = mysqli_query($connection, "SELECT * FROM kategori");
    while ($kat = mysqli_fetch_assoc($query_kategori)) :
      $id_kategori = $kat['id_kategori'];
      $deskripsi_kategori = $kat['deskripsi'];

      // Ambil semua buku berdasarkan kategori
      $query_buku = mysqli_query(
        $connection,
        "SELECT buku.*, kategori.deskripsi, sedia.sedia_buku 
              FROM buku JOIN kategori ON buku.id_kategori = kategori.id_kategori
              JOIN sedia ON buku.id_sedia_buku = sedia.id_sedia_buku
              WHERE buku.id_kategori = '$id_kategori'"
      );

      if (mysqli_num_rows($query_buku) > 0):
    ?>
        <h2 style="margin-left: 20px;"><?php echo $deskripsi_kategori; ?></h2>
        <div class="book-list">

          <?php while ($data = mysqli_fetch_assoc($query_buku)) : ?>
            <a href="sinopsis.php?id_buku=<?= $data['id_buku']; ?>" class="book-card-link">

              <div class="book-card">
                <img src="image/<?php echo $data['cover']; ?>" alt="<?php echo $data['judul']; ?>">
                <div class="book-info">
                  <h3><?php echo $data['judul']; ?></h3>
                  <p><strong>Author:</strong> <?php echo $data['author']; ?></p>
                  <p><strong>Kategori:</strong> <?php echo $data['deskripsi']; ?></p>
                  <p><strong>Tahun:</strong> <?php echo $data['tahun']; ?></p>
                  <p><strong>Status:</strong>
                    <?= $data['sedia_buku'] == 'Tersedia'
                      ? '<span class="tersedia">Tersedia</span>'
                      : '<span class="tidak-tersedia">Tidak Tersedia</span>'; ?>
                  </p>
                  <?php if ($data['sedia_buku'] == 'Tersedia') : ?>
                    <a class="btn" href="pinjam_buku.php?id_buku=<?= $data['id_buku']; ?>">Baca Sekarang</a>
                  <?php else : ?>
                    <button class="btn" disabled>Baca Sekarang</button>
                  <?php endif; ?>
                </div>
              </div>
            </a>
          <?php endwhile; ?>
        </div> <!-- tutup book-list -->
      <?php endif; ?>
    <?php endwhile; ?>

  </div>

  <script src="all.js"></script>
</body>

</html>