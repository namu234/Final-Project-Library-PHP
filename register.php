<?php
require 'connection.php';

// Ambil data dari form
$nama           = trim($_POST['nama']);
$ttl            = trim($_POST['TTL']);
$jenis_kelamin  = trim($_POST['jenis_kelamin']);
$email          = trim($_POST['email']);
$username       = trim($_POST['username']);
$password       = md5(trim($_POST['password'])); // enkripsi password (gunakan bcrypt di produksi ya)

// Validasi sederhana
if (
  empty($nama) || empty($ttl) || empty($jenis_kelamin) || empty($email) ||
  empty($username) || empty($password)
) {
  echo "<script>alert('Semua field wajib diisi!'); window.location.href='main.php';</script>";
  exit;
}

// Cek apakah username sudah digunakan
$check = $connection->prepare("SELECT * FROM anggota WHERE username = ?");
$check->bind_param("s", $username);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
  echo "<script>alert('Username sudah digunakan, silakan pilih username lain!'); window.location.href='main.php';</script>";
  exit;
}

// Simpan ke database
$stmt = $connection->prepare("INSERT INTO anggota (nama, TTL, jenis_kelamin, email, username, password) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $nama, $ttl, $jenis_kelamin, $email, $username, $password);

if ($stmt->execute()) {
  echo "<script>alert('Pendaftaran berhasil! Silakan login.'); window.location.href='main.php';</script>";
} else {
  echo "<script>alert('Terjadi kesalahan saat menyimpan data.'); window.location.href='main.php';</script>";
}
