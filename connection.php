<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "library2";

date_default_timezone_set('Asia/Jakarta');

$connection = new mysqli($host, $user, $pass, $db);
if ($connection->connect_error) {
  echo "koneksi gagal karena" . $connection->connect_error;
}
