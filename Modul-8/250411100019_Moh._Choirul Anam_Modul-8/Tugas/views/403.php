<?php http_response_code(403); ?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>403 - Akses ditolak</title>
  <?php require __DIR__ . "/partials/style.php" ?>
</head>

<body>
  <main class="wrap narrow">
    <h1>403</h1>
    <p>Anda tidak berhak mengakses halaman ini.</p>
    <a class="btn" href="<?= e(url('/')) ?>">Kembali ke Home</a>
  </main>
</body>

</html>
