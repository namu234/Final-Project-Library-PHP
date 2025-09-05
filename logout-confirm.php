<?php
session_start();
// Cek apakah user login (anggota atau admin)
if (!isset($_SESSION['id_user']) && !isset($_SESSION['user_admin'])) {
  header('location: main.php');
  exit;
}

// Ambil username dari session mana pun yang tersedia
$username = isset($_SESSION['uname']) ? $_SESSION['uname'] : $_SESSION['user_admin'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Konfirmasi Login</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <div class="login-overlay active">
    <div class="login-container">
      <span class="close-btn" onclick="window.location.href='index.php'">&times;</span>
      <div class="login-header">
        <h2>Logout</h2>
        <p>Halo <strong><?= htmlspecialchars($username); ?></strong>, apakah kamu yakin ingin keluar dari akun ini?</p>
      </div>

      <div class="logout-actions">
        <form action="logout.php" method="post">
          <button type="submit" class="submit-btn">Ya, Logout</button>
        </form>
        <?php
        $cancelPage = isset($_SESSION['user_admin']) ? 'admin.php' : 'index.php';
        ?>
        <button onclick="window.location.href='<?= $cancelPage ?>'" class="cancel-btn">Batal</button>
      </div>
    </div>
  </div>
</body>

</html>