<?php
session_start();
require 'connection.php';

if (!isset($_SESSION['user_admin'])) {
  header('location:admin.php');
  exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $judul = mysqli_real_escape_string($connection, $_POST['Judul']);
  $author = mysqli_real_escape_string($connection, $_POST['Author']);
  $sinopsis = mysqli_real_escape_string($connection, $_POST['Sinopsis']);
  $publisher = mysqli_real_escape_string($connection, $_POST['Publisher']);
  $tahun = $_POST['Tahun'];
  $kota = mysqli_real_escape_string($connection, $_POST['Kota']);
  $isbn = mysqli_real_escape_string($connection, $_POST['ISBN']);
  $kategori = $_POST['Kategori'];
  $sedia = $_POST['Sedia'];

  // Upload Cover
  $namaFile = $_FILES['Cover']['name'];
  $tmpFile = $_FILES['Cover']['tmp_name'];
  $ekstensi = pathinfo($namaFile, PATHINFO_EXTENSION);
  $coverBaru = uniqid() . '.' . $ekstensi;

  move_uploaded_file($tmpFile, 'image/' . $coverBaru);

  $query = "INSERT INTO buku (judul, author, sinopsis, publisher, tahun, kota, ISBN, id_kategori, id_sedia_buku, cover)
            VALUES ('$judul', '$author', '$sinopsis', '$publisher', '$tahun', '$kota', '$isbn', '$kategori', '$sedia', '$coverBaru')";

  if (mysqli_query($connection, $query)) {
    echo "<script>alert('Buku berhasil ditambahkan.'); window.location='daftar_buku.php';</script>";
  } else {
    echo "<script>alert('Gagal menambahkan buku: " . mysqli_error($connection) . "'); window.history.back();</script>";
  }
}
