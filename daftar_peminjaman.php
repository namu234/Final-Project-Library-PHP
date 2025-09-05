<?php
include('connection.php');

$query = mysqli_query(
  $connection,
  "SELECT p.id_pinjam, a.nama, b.judul, p.tgl_pinjam, p.tgl_kembali, p.tgl_deadline
  FROM detail_peminjaman p
  JOIN anggota a ON p.id_user = a.id_user
  JOIN buku b ON p.id_buku = b.id_buku
  ORDER BY p.id_pinjam DESC
"
);

session_start();

if (!isset($_SESSION['user_admin'])) {
  header('location:admin.php');
  exit();
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
    <h1>Data Riwayat Peminjaman Anggota</h1>
    <br>

    <!-- Search Bar -->
    <div class="search-bar">
      <form id="formCariRiwayat">
        <input type="text" name="keyword" id="keywordRiwayat" placeholder="Cari buku atau anggota...">
        <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
      </form>
    </div>

    <br>
    <div id="hasilPinjam">
      <?php if (mysqli_num_rows($query) > 0) { ?>
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Anggota</th>
              <th>Buku</th>
              <th>Tanggal Pinjam</th>
              <th>Deadline</th>
              <th>Tanggal Kembali</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = mysqli_fetch_assoc($query)) {
              $tgl_deadline = $row['tgl_deadline'];
              $tgl_kembali = $row['tgl_kembali'];
              $sekarang = date('Y-m-d');
            ?>
              <tr>
                <td><?= $row['id_pinjam']; ?></td>
                <td><?= $row['nama']; ?></td>
                <td><?= $row['judul']; ?></td>
                <td><?= $row['tgl_pinjam']; ?></td>
                <td><?= $tgl_deadline; ?></td>
                <td>
                  <?= $tgl_kembali ? $tgl_kembali : '<i>Belum dikembalikan</i>'; ?>
                </td>
                <td>
                  <?php if (!$tgl_kembali && $tgl_deadline < $sekarang): ?>
                    <span style="color:red; font-weight:bold;">Terlambat</span>
                  <?php elseif (!$tgl_kembali): ?>
                    <span>Dipinjam</span>
                  <?php else: ?>
                    <span>Dikembalikan</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      <?php } else { ?>
        <p>Data tidak tersedia</p>
      <?php } ?>
    </div>
  </div>


  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const form = document.getElementById("formCariRiwayat");
      const input = document.getElementById("keywordRiwayat");
      const hasil = document.getElementById("hasilPinjam");

      function muatRiwayat(keyword = "") {
        fetch(`dp_ajax.php?keyword=${encodeURIComponent(keyword)}`)
          .then(res => res.text())
          .then(html => hasil.innerHTML = html)
          .catch(err => hasil.innerHTML = "Gagal memuat.");
      }

      muatRiwayat();

      form.addEventListener("submit", function(e) {
        e.preventDefault();
        muatRiwayat(input.value);
      });

      input.addEventListener("input", function() {
        muatRiwayat(this.value);
      });
    });
  </script>
</body>

</html>