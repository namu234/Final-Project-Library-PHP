  <?php
  session_start();
  require 'connection.php';

  $id_buku = isset($_GET['id_buku']) ? intval($_GET['id_buku']) : 0;


  $query = mysqli_query(
    $connection,
    "SELECT b.*, k.deskripsi AS kategori, s.sedia_buku 
                  FROM buku b LEFT JOIN kategori k 
                  ON b.id_kategori = k.id_kategori 
                  LEFT JOIN sedia s ON b.id_sedia_buku = s.id_sedia_buku 
                  WHERE b.id_buku = '$id_buku'"
  );

  $buku = mysqli_fetch_assoc($query);

  if (!$buku) {
    echo "<script>alert('Buku tidak ditemukan!'); window.location.href='main.php';</script>";
    exit;
  }
  ?>

  <!DOCTYPE html>
  <html lang="id">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sinopsis Buku</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
  </head>

  <body>

    <div class="overlay-wrapper">
      <div class="sinopsis-popup">
        <img src="image/<?= $buku['cover']; ?>" alt="<?= $buku['judul']; ?>" onerror="this.src='image/default.jpg'">

        <div class="book-info">
          <h2><?= $buku['judul']; ?></h2>
          <p><span class="label">Author:</span> <?= $buku['author']; ?></p>
          <p><span class="label">Publisher:</span> <?= $buku['publisher']; ?></p>
          <p><span class="label">Tahun:</span> <?= $buku['tahun']; ?></p>
          <p><span class="label">Kategori:</span> <?= $buku['kategori']; ?></p>
          <p><span class="label">Kota:</span> <?= $buku['kota']; ?></p>
          <p><span class="label">ISBN:</span> <?= $buku['ISBN']; ?></p>
          <p><span class="label">Stok:</span> <?= $buku['stok']; ?></p>
          <p><span class="label">Status:</span>
            <span class="status <?= strtolower(trim($buku['sedia_buku'])) === 'tersedia' ? 'tersedia' : 'tidak-tersedia' ?>">
              <?= $buku['sedia_buku']; ?>
            </span>
          </p>

          <div class="sinopsis">
            <p><span class="label">Sinopsis:</span><br><br><?= nl2br($buku['sinopsis']); ?></p>
          </div>

          <?php if (isset($_SESSION['id_user'])): ?>
            <a href="pinjam_buku.php?id_buku=<?= $buku['id_buku']; ?>" class="button">Baca Sekarang</a>
          <?php else: ?>
            <a href="main.php?login_required=1" class="button">Baca Sekarang</a>
          <?php endif; ?>

        </div>
      </div>
    </div>

  </body>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      background-color: #FFF8E8;
      height: 100vh;
      overflow: hidden;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 0;
    }

    body.overlay-bg {
      background-color: rgba(0, 0, 0, 0.7);
      height: 100vh;
      overflow: hidden;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .overlay-wrapper {
      width: 90%;
      max-width: 1000px;
      padding: 20px;
      z-index: 10;
    }

    .sinopsis-popup {
      background: #ffffff;
      padding: 40px;
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
      display: flex;
      gap: 40px;
      animation: fadeSlideIn 0.4s ease-out;
    }

    .sinopsis-popup img {
      width: 300px;
      height: auto;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .book-info {
      flex: 1;
    }

    .book-info h2 {
      margin-bottom: 15px;
      color: #1f2937;
    }

    .book-info p {
      margin-bottom: 10px;
      color: #4b5563;
    }

    .label {
      font-weight: 600;
      color: #111827;
    }

    .status {
      font-weight: bold;
    }

    .status.tersedia {
      color: green;
    }

    .status.tidak-tersedia {
      color: red;
    }

    .book-info .sinopsis {
      margin-top: 20px;
      line-height: 1.6;
    }

    .button {
      display: inline-block;
      margin-top: 25px;
      padding: 10px 20px;
      background-color: #8d493a;
      color: white;
      border: none;
      border-radius: 8px;
      text-decoration: none;
      transition: background-color 0.1s ease;
    }

    .button:hover {
      background-color: #674636;
    }

    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateY(50px) scale(0.97);
      }

      to {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }
  </style>

  </html>