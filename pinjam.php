<?php
include 'connection.php';
session_start();

if (!isset($_SESSION['id_user'])) {
  header('Location: login.php'); // Kalau belum login
  exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $id_buku = $_POST['id_buku'];
  $id_user = $_SESSION['id_user'];
  $tgl_pinjam = date('Y-m-d H:i:s');
  $tgl_deadline = date('Y-m-d', strtotime('+3 days')); // Deadline 1 hari

  // Cek stok dahulu
  $query = mysqli_query($connection, "SELECT stok FROM buku WHERE id_buku='$id_buku'");
  $buku = mysqli_fetch_assoc($query);

  if ($buku && $buku['stok'] > 0) {
    // Simpan ke detail_peminjaman dengan deadline
    $insert = mysqli_query($connection, "INSERT INTO detail_peminjaman (id_user, id_buku, tgl_pinjam, tgl_kembali, tgl_deadline) 
      VALUES ($id_user, $id_buku, '$tgl_pinjam', NULL, '$tgl_deadline')");

    // Update stok (-1)
    mysqli_query($connection, "UPDATE buku SET stok = stok - 1 WHERE id_buku = $id_buku");

    header('Location: pinjaman.php');
    exit;
  } else {
    echo "<script>alert('Buku habis');window.location='pinjam_buku.php'</script>";
  }
} else {
  header('Location: pinjam_buku.php');
  exit;
}
