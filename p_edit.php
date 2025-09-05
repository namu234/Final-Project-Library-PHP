<?php
require 'connection.php';
session_start();

// Jika klik tombol Batal, kembali ke profile
if (isset($_POST['batal'])) {
  header('Location: profile.php');
  exit();
}

// Jika klik tombol Konfirmasi
if (isset($_POST['konfirmasi'])) {
  $id         = $_POST['id_user'];
  $nama       = trim($_POST['nama']);
  $TTL        = trim($_POST['TTL']);
  $JK         = trim($_POST['JK']);
  $user       = trim($_POST['Username']);
  $email      = trim($_POST['Email']);
  $pass       = trim($_POST['Password']);
  $fotoBaru   = null;
  $folder     = 'image/';

  // Ambil password lama dari database
  $getOld = $connection->prepare("SELECT password FROM anggota WHERE id_user = ?");
  $getOld->bind_param("i", $id);
  $getOld->execute();
  $result = $getOld->get_result();
  $row = $result->fetch_assoc();
  $old_pass = $row['password'];

  // Jika password diisi baru → hash ulang, kalau tidak → tetap pakai lama
  if (!empty($pass)) {
    $pass = iconv('UTF-8', 'ASCII//IGNORE', $pass);
    $pass = preg_replace('/[^\x20-\x7E]/', '', $pass);
    $hashed_pass = md5($pass);
  } else {
    $hashed_pass = $old_pass;
  }

  // Cek apakah user upload foto baru
  if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
      echo "<script>alert('Format gambar tidak didukung!'); location.href='form_edit_profile.php';</script>";
      exit();
    }

    $namaFile = uniqid() . '_' . basename($_FILES['foto']['name']);
    $tmpName  = $_FILES['foto']['tmp_name'];
    $upload   = move_uploaded_file($tmpName, $folder . $namaFile);

    if ($upload) {
      $fotoBaru = $namaFile;
    }
  }

  // SQL Update
  if ($fotoBaru) {
    $sql = "UPDATE anggota SET nama=?, TTL=?, jenis_kelamin=?, username=?, email=?, password=?, foto=? WHERE id_user=?";
    $pre = $connection->prepare($sql);
    $pre->bind_param("sssssssi", $nama, $TTL, $JK, $user, $email, $hashed_pass, $fotoBaru, $id);
  } else {
    $sql = "UPDATE anggota SET nama=?, TTL=?, jenis_kelamin=?, username=?, email=?, password=? WHERE id_user=?";
    $pre = $connection->prepare($sql);
    $pre->bind_param("ssssssi", $nama, $TTL, $JK, $user, $email, $hashed_pass, $id);
  }

  if ($pre->execute()) {
    header('Location: profile.php');
    exit();
  } else {
    echo "<script>alert('Gagal menyimpan perubahan.'); location.href='form_edit_profile.php';</script>";
  }
}
