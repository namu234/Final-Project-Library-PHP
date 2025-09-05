<?php
require 'connection.php';

$keyword = isset($_GET['keyword']) ? mysqli_real_escape_string($connection, $_GET['keyword']) : '';

$sql = "SELECT dp.*, a.nama, b.judul 
        FROM detail_peminjaman dp
        JOIN anggota a ON dp.id_user = a.id_user
        JOIN buku b ON dp.id_buku = b.id_buku
        WHERE a.nama LIKE '%$keyword%' OR b.judul LIKE '%$keyword%'
        ORDER BY dp.id_pinjam DESC";

$q = mysqli_query($connection, $sql);

if (mysqli_num_rows($q) > 0) {
  echo '<table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Anggota</th>
              <th>Buku</th>
              <th>Tanggal Pinjam</th>
              <th>Deadline</th>
              <th>Tanggal Kembali</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>';

  while ($row = mysqli_fetch_assoc($q)) {
    $tgl_deadline = $row['tgl_deadline'];
    $tgl_kembali = $row['tgl_kembali'];
    $sekarang = date('Y-m-d');

    echo '<tr>';
    echo '<td>' . $row['id_pinjam'] . '</td>';
    echo '<td>' . $row['nama'] . '</td>';
    echo '<td>' . $row['judul'] . '</td>';
    echo '<td>' . $row['tgl_pinjam'] . '</td>';
    echo '<td>' . $tgl_deadline . '</td>';
    echo '<td>' . ($tgl_kembali ? $tgl_kembali : '<i>Belum dikembalikan</i>') . '</td>';
    echo '<td>';
    if (!$tgl_kembali && $tgl_deadline < $sekarang) {
      echo "<span style='color:red; font-weight:bold;'>Terlambat</span>";
    } elseif (!$tgl_kembali) {
      echo "Dipinjam";
    } else {
      echo "Dikembalikan";
    }
    echo '</td>';
    echo '</tr>';
  }

  echo '</tbody></table>';
} else {
  echo 'Data tidak ditemukan.';
}
