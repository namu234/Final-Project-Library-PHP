<?php
session_start();
require 'connection.php';

$username = trim($_POST['username']);
$password = trim($_POST['password']);
$hashedPass = md5($password);

// Cek apakah admin
if ($username === 'admin' && $password === 'admin') {
  $_SESSION['user_admin'] = $username;
  header('Location: admin.php');
  exit;
}

// Jika bukan admin, cek anggota
$query = "SELECT id_user, username FROM anggota WHERE username = ? AND password = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("ss", $username, $hashedPass);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  $data = $result->fetch_assoc();
  $_SESSION['uname'] = $data['username'];
  $_SESSION['id_user'] = $data['id_user'];
  header('Location: index.php');
  exit;
} else {
  echo "<script>alert('Username dan Password salah!'); window.location.href='main.php';</script>";
  exit;
}
