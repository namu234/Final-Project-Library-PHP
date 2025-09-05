<?php
session_start(); // <- WAJIB

// Cek apakah user adalah admin
if (isset($_SESSION['user_admin'])) {
  header('Location: admin.php');
  exit;
}

require 'connection.php';

$showLoginPopup = false;
if (isset($_GET['login_required']) && $_GET['login_required'] == 1) {
  $showLoginPopup = true;
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
  <link rel="stylesheet" href="main.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


<body>
  <!-- Main Content -->
  <div class="main-content">
    <div class="welcome-section">
      <h2>Selamat Datang di E-Library</h2>
      <p>Sistem perpustakaan digital modern untuk mengakses buku dari mana saja</p>
      <button class="login-btn" id="openLogin">LOGIN</button>
    </div>

    <div class="stats-section">
      <div class="stat-card stat-1">
        <div class="stat-icon">
          <i class="fas fa-book"></i>
        </div>
        <div class="stat-info">
          <?php
          include 'connection.php';
          $query = mysqli_query($connection, "SELECT COUNT(*) AS total FROM buku");
          $row = mysqli_fetch_assoc($query);
          $total_buku = $row['total'];
          ?>
          <h3><?php echo $row['total']; ?></h3>
          <p>Total Buku</p>
        </div>
      </div>

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

      <div class="stat-card stat-3">
        <div class="stat-icon">
          <i class="fas fa-book-reader"></i>
        </div>
        <div class="stat-info">
          <?php
          include 'connection.php';
          $query = mysqli_query($connection, "SELECT COUNT(*) AS total FROM detail_peminjaman WHERE tgl_kembali IS NULL");
          $row = mysqli_fetch_assoc($query);
          $total_buku = $row['total'];
          ?>
          <h3><?php echo $row['total']; ?></h3>
          <p>Sedang Dipinjam</p>
        </div>
      </div>

      <div class="stat-card stat-4">
        <div class="stat-icon">
          <i class="fas fa-clock"></i>
        </div>
        <div class="stat-info">
          <?php
          include 'connection.php';

          // total buku
          $query_total = mysqli_query($connection, "SELECT COUNT(*) AS total FROM buku");
          $row_total = mysqli_fetch_assoc($query_total);
          $total_buku = $row_total['total'];

          // total buku tersedia
          $query_sedia = mysqli_query($connection, "SELECT COUNT(*) AS tersedia FROM buku WHERE id_sedia_buku = 1");
          $row_sedia = mysqli_fetch_assoc($query_sedia);
          $total_sedia = $row_sedia['tersedia'];

          //hitung ketersediaan
          $presentase = 0;
          if ($total_buku > 0) {
            $presentase = ($total_sedia / $total_buku) * 100;
          }
          ?>
          <h3><?php echo number_format($presentase); ?>%</h3>
          <p>Ketersediaan Buku</p>
        </div>
      </div>
    </div>

    <h2>Tersedia</h2>
    <div class="book-list">
      <?php while ($data = mysqli_fetch_assoc($result)): ?>
        <a href="sinopsis.php?id_buku=<?= $data['id_buku']; ?>" class="book-card">
          <img src="image/<?php echo $data['cover']; ?>" alt="<?php echo $data['judul']; ?>">
          <div class="book-info">
            <h3><?php echo $data['judul']; ?></h3>
            <p><strong>Author:</strong> <?php echo $data['author']; ?></p>
            <p><strong>Kategori:</strong> <?php echo $data['deskripsi']; ?></p>
            <p><strong>Tahun:</strong> <?php echo $data['tahun']; ?></p>
          </div>
        </a>
      <?php endwhile; ?>
    </div>


    <!-- Login Overlay -->
    <div class="login-overlay" id="loginOverlay">
      <div class="login-container">
        <span class="close-btn" id="closeLogin">&times;</span>

        <div class="login-header">
          <h2>Login Akun</h2>
          <p>Masukkan kredensial Anda untuk mengakses dashboard</p>
        </div>

        <form id="loginForm" action="login.php" method="post">
          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" id="login-username" placeholder="Masukkan username Anda" required>
          </div>

          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="login-password" placeholder="Masukkan password Anda" required>
          </div>

          <button type="submit" class="submit-btn">MASUK</button>
        </form>

        <div class="login-footer">
          <p>Belum punya akun? <a href="#" id="openRegisterFromLogin">Daftar sekarang</a></p>
        </div>
      </div>
    </div>

    <!-- Register Overlay -->
    <div class="register-overlay" id="registerOverlay">
      <div class="register-container">
        <span class="close-btn" id="closeRegister">&times;</span>

        <div class="register-header">
          <h2>Daftar Akun</h2>
          <p>Silhakan isi data berikut untuk membuat akun</p>
        </div>

        <form id="registerForm" action="register.php" method="post">
          <div class="form-group">
            <label for="fullname">Nama Lengkap</label>
            <input type="text" name="nama" placeholder="Masukkan nama lengkap Anda" required>
          </div>

          <div class="form-group">
            <label for="birthplace">Tempat Tanggal Lahir</label>
            <input type="text" name="TTL" placeholder="Contoh: Jakarta, 11 Januari 2000" required>
          </div>

          <div class="form-group">
            <label for="gender">Jenis Kelamin</label>
            <select name="jenis_kelamin" required>
              <option value="">Pilih jenis kelamin</option>
              <option value="L">Laki-laki</option>
              <option value="P">Perempuan</option>
            </select>
          </div>

          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" placeholder="Masukkan email Anda" required>
          </div>

          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" placeholder="Masukkan username Anda" required>
          </div>

          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" placeholder="Masukkan password Anda" required>
          </div>

          <button type="submit" class="submit-btn">Daftar</button>

        </form>

        <div class="register-footer">
          <p>Sudah punya akun? <a href="#" id="openLoginFromRegister">Login di sini</a></p>
        </div>
      </div>
    </div>

    <!-- Pop Up -->
    <?php if ($showLoginPopup): ?>
      <div class="popup-notice-overlay" id="popupLoginNotice">
        <div class="popup-notice-box">
          <h3>🔒 Login Diperlukan</h3>
          <p>Silakan login terlebih dahulu untuk membaca buku.</p>
          <button id="closePopupBtn">Tutup</button>
        </div>
      </div>
    <?php endif; ?>


    <script src="all.js"></script>
</body>

</html>