<?php
session_start();

if (!isset($_SESSION['uname'])) {
  header('location:login.php'); // Kalau blm login, diarahkan ke form login
  exit();
}

require 'connection.php';

$id_user = $_SESSION['id_user'];
$query_foto = mysqli_query($connection, "SELECT foto FROM anggota WHERE id_user = '$id_user'");
$data_foto = mysqli_fetch_assoc($query_foto);
$foto = (!empty($data_foto['foto']) && $data_foto['foto'] != 'Default Profile.jpeg')
  ? $data_foto['foto']
  : 'Default Profile.jpeg';


$id = $_SESSION['id_user']; // Mengambil id berdasarkan sesi
$query = mysqli_query($connection, "SELECT * FROM anggota WHERE id_user='$id'");

if (!$query) {
  die("Query Error: " . mysqli_error($connection));
}

$data = mysqli_fetch_assoc($query);

$nama = $data['nama'];
$TTL = $data['TTL'];
$JK = $data['jenis_kelamin'];
$Username = $data['username'];
$Password = $data['password'];
$email = $data['email'];
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

  <!-- Profile -->
  <div class="main-content">
    <h1>Profile Saya</h1>

    <div class="form-tambah-buku">
      <input type="hidden" name="id_user" value="<?= htmlentities($data['id_user']) ?>">

      <label>Nama</label>
      <input name="nama" type="text" value="<?= htmlentities($data['nama']) ?>" disabled>

      <label>Tempat Tanggal Lahir</label>
      <input name="TTL" type="text" value="<?= htmlentities($data['TTL']) ?>" disabled>

      <label>Jenis Kelamin</label>
      <input name="JK" type="text" value="<?= htmlentities($data['jenis_kelamin']) ?>" disabled>

      <label>Username</label>
      <input name="Username" type="text" value="<?= htmlentities($data['username']) ?>" disabled>

      <label>Password</label>
      <input name="Email" type="text" value="<?= htmlentities($data['password']) ?>" disabled>

      <label>Email</label>
      <input name="Email" type="text" value="<?= htmlentities($data['email']) ?>" disabled>

      <label>Foto Profile</label>
      <img src="image/<?= htmlentities($foto) ?>"
        alt="Foto Profil"
        style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 2px solid #ccc;">


      <button><a href="edit_profile.php">Edit Profile</a></button>
    </div>

    <script src="all.js"></script>
</body>

</html>