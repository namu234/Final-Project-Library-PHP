<?php
session_start();
$username = isset($_SESSION['uname']) ? $_SESSION['uname'] : 'Pengunjung';
require 'connection.php';

if (!isset($_SESSION['id_user'])) {
  header('Location: login.php');
  exit;
}

$id_user = $_SESSION['id_user'];

$query_foto = mysqli_query($connection, "SELECT foto FROM anggota WHERE id_user = '$id_user'");
$data_foto = mysqli_fetch_assoc($query_foto);
$foto = (!empty($data_foto['foto'])) ? $data_foto['foto'] : 'Default Profile.jpeg';

// Pinjaman Aktif
$query_pinjaman = mysqli_query(
  $connection,
  "SELECT p.id_pinjam, b.judul, p.tgl_pinjam, p.tgl_deadline
          FROM detail_peminjaman p 
          JOIN buku b ON p.id_buku = b.id_buku 
          WHERE p.id_user = '$id_user' 
          AND p.tgl_kembali IS NULL"
);

// Riwayat Pinjaman
$query_riwayat = mysqli_query(
  $connection,
  "SELECT p.id_pinjam, b.judul, p.tgl_pinjam, p.tgl_kembali
    FROM detail_peminjaman p 
    JOIN buku b ON p.id_buku = b.id_buku 
    WHERE p.id_user = '$id_user' AND p.tgl_kembali IS NOT NULL
    ORDER BY p.id_pinjam ASC"
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

  <!-- Pinjaman dan Riwayat-->
  <div class="main-content">
    <!-- Pinjaman -->
    <h1>Pinjaman Buku</h1>

    <div class="pinjam" id="hasilUserPinjam">
      <h2>Buku yang Saya Pinjam</h2>
      <br>
      <?php
      if (mysqli_num_rows($query_pinjaman) > 0) {
        echo "<table border='1' cellspacing='0' cellpadding='8'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>Judul</th>";
        echo "<th>Tanggal Pinjam</th>";
        echo "<th>Deadline</th>";
        echo "<th>Status</th>";
        echo "<th>Aksi</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        while ($pinjam = mysqli_fetch_assoc($query_pinjaman)) {
          echo "<tr>";
          echo "<td>" . $pinjam['id_pinjam'] . "</td>";
          echo "<td>" . $pinjam['judul'] . "</td>";
          echo "<td>" . $pinjam['tgl_pinjam'] . "</td>";
          echo "<td>" . $pinjam['tgl_deadline'] . "</td>";

          $tgl_deadline = $pinjam['tgl_deadline'];
          $sekarang = date('Y-m-d');

          if ($tgl_deadline < $sekarang) {
            echo "<td style='color: red; font-weight: bold;'>Terlambat</td>";
          } else {
            echo "<td>Belum dikembalikan</td>";
          }

          echo "<td><a class='delete' href='kembali_buku.php?id_pinjam=" . $pinjam['id_pinjam'] . "'>Kembalikan</a></td>";
          echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
      } else {
        echo "<p><i>Kamu belum meminjam buku apa pun saat ini.</i></p>";
      }
      ?>
      <br>
      <button><a href="pinjam_buku.php">Pinjam Buku</a></button>
    </div>

    <!-- Riwayat -->
    <div class="pinjam" id="hasilRiwayatUser" style="margin-top: 40px;">
      <h2>Riwayat Peminjaman Buku</h2>
      <br>
      <?php
      if (mysqli_num_rows($query_riwayat) > 0) {
        echo "<table border='1' cellspacing='0' cellpadding='8'>";
        echo "<thead>
              <tr>
                <th>ID</th>
                <th>Judul</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
              </tr>
            </thead><tbody>";

        while ($pinjam = mysqli_fetch_assoc($query_riwayat)) {
          echo "<tr>
                <td>{$pinjam['id_pinjam']}</td>
                <td>{$pinjam['judul']}</td>
                <td>{$pinjam['tgl_pinjam']}</td>
                <td>{$pinjam['tgl_kembali']}</td>
              </tr>";
        }

        echo "</tbody></table>";
      } else {
        echo "<p><i>Belum ada buku yang dikembalikan.</i></p>";
      }
      ?>
    </div>
    </section>

    <script src="all.js"></script>
</body>

</html>