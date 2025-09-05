<?php
session_start();
require 'connection.php';

if (!isset($_SESSION['user_admin'])) {
  header('location:admin.php');
  exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $id_buku    = $_POST['id_buku'];
  $judul      = mysqli_real_escape_string($connection, $_POST['Judul']);
  $author     = mysqli_real_escape_string($connection, $_POST['Author']);
  $sinopsis   = mysqli_real_escape_string($connection, $_POST['Sinopsis']);
  $publisher  = mysqli_real_escape_string($connection, $_POST['Publisher']);
  $tahun      = $_POST['Tahun'];
  $kota       = mysqli_real_escape_string($connection, $_POST['Kota']);
  $isbn       = mysqli_real_escape_string($connection, $_POST['ISBN']);
  $kategori   = $_POST['Kategori'];
  $sedia      = $_POST['Sedia'];

  // Proses Upload Cover
  $coverBaru = '';
  if (!empty($_FILES['Cover']['name'])) {
    $namaFile = $_FILES['Cover']['name'];
    $tmpFile = $_FILES['Cover']['tmp_name'];
    $ekstensi = pathinfo($namaFile, PATHINFO_EXTENSION);
    $coverBaru = uniqid() . '.' . $ekstensi;

    move_uploaded_file($tmpFile, 'image/' . $coverBaru);
  }

  // Query Update
  $query = "UPDATE buku SET 
              judul = '$judul',
              author = '$author',
              sinopsis = '$sinopsis',
              publisher = '$publisher',
              tahun = '$tahun',
              kota = '$kota',
              ISBN = '$isbn',
              id_kategori = '$kategori',
              id_sedia_buku = '$sedia'";

  if (!empty($coverBaru)) {
    $query .= ", cover = '$coverBaru'";
  }

  $query .= " WHERE id_buku = '$id_buku'";

  $result = mysqli_query($connection, $query);

  if ($result) {
    echo "<script>alert('Data buku berhasil diubah!'); window.location='daftar_buku.php';</script>";
  } else {
    echo "<script>alert('Gagal mengubah data buku.'); window.history.back();</script>";
  }
} else {
  echo "<script>alert('Akses tidak valid!'); window.location='admin.php';</script>";
}
