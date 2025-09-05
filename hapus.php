<?php
require "connection.php";

$id = $_GET['id'];
$sql = "DELETE FROM anggota WHERE id_user ='$id'";
$exec = $connection->query($sql);

if ($exec == true) {
  header('location:data_anggota.php');
} else {
  header('location:data_anggota.php');
}
