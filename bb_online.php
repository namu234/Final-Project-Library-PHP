<?php
session_start();

if (!isset($_SESSION['uname'])) {
  header('location:main.php');
  exit();
}

require 'connection.php';

$id_user = $_SESSION['id_user'];
$query_foto = mysqli_query($connection, "SELECT foto FROM anggota WHERE id_user = '$id_user'");
$data_foto = mysqli_fetch_assoc($query_foto);
$foto = (!empty($data_foto['foto'])) ? $data_foto['foto'] : 'Default Profile.jpeg';


if (!$connection) {
  die("Koneksi database gagal: " . mysqli_connect_error());
}

$sql = "SELECT buku.*, sedia.sedia_buku
        FROM detail_peminjaman 
        JOIN buku ON detail_peminjaman.id_buku = buku.id_buku 
        JOIN sedia ON buku.id_sedia_buku = sedia.id_sedia_buku 
        WHERE detail_peminjaman.id_user = '$id_user' 
        AND detail_peminjaman.tgl_kembali IS NULL";

$query_dipinjam = mysqli_query($connection, $sql);
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>E-Library</title>
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

  <!-- Pinjam Buku -->
  <div class="main-content">
    <h1>Baca Bukumu Sekarang</h1>
    <div class="book-list">
      <?php
      if (mysqli_num_rows($query_dipinjam) > 0) {
        while ($data = mysqli_fetch_assoc($query_dipinjam)) {
      ?>
          <div class="book-card">
            <img src="image/<?php echo $data['cover']; ?>" alt="<?php echo $data['judul']; ?>">

            <div class="book-info">
              <h3><?php echo $data['judul']; ?></h3>
              <p><strong>Author:</strong> <?php echo $data['author']; ?></p>
              <p><strong>Status:</strong> <span class="tersedia"><?php echo $data['sedia_buku']; ?></span></p>
              <p><strong>Tahun:</strong> <?php echo $data['tahun']; ?></p>

              <div class="book-actions">
                <?php if (!empty($data['file_pdf'])) { ?>
                  <button onclick="console.log('pdf/<?php echo $data['file_pdf']; ?>'); tampilkanPDF('pdf/<?php echo $data['file_pdf']; ?>')">Baca Sekarang</button>
                <?php } else { ?>
                  <button disabled>Tidak Ada File</button>
                <?php } ?>
              </div>
            </div>
          </div>
      <?php
        }
      } else {
        echo "<p>Tidak ada buku yang sedang kamu pinjam.</p>";
      }
      ?>
    </div>

    <!-- Container untuk PDF -->
    <div id="pdfContainer" style="padding: 20px;"></div>
  </div>

  <script>
    function tampilkanPDF(filePath) {
      const container = document.getElementById("pdfContainer");
      container.innerHTML = `
      <h2>Pratinjau Buku</h2>
      <iframe 
        src="pdfjs/web/viewer.html?file=../../${filePath}#toolbar=0&navpanes=0&view=Fit"
        width="100%" height="700px"
        style="border: 1px solid #ccc; border-radius: 10px;">
      </iframe>
    `;

      container.scrollIntoView({
        behavior: "smooth"
      });
    }

    // Blok download, print, klik kanan
    document.addEventListener("contextmenu", e => e.preventDefault());
    document.addEventListener("keydown", function(e) {
      if ((e.ctrlKey && (e.key === 's' || e.key === 'p')) || e.key === 'PrintScreen') {
        e.preventDefault();
      }
    });
  </script>

</body>

</html>