<?php http_response_code(404); ?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>404 - Halaman tidak ditemukan</title>
  <?php require __DIR__ . "/partials/style.php" ?>
</head>

<body>
  <main class="wrap narrow">
    <h1>404</h1>
    <p>Halaman tidak ditemukan.</p>
    <a class="btn" href="<?= e(url('/')) ?>">Kembali ke Home</a>
  </main>
</body>

</html>
