<?php http_response_code(404); ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>404 - Halaman tidak ditemukan</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 24px;">
  <h1>404</h1>
  <p>Halaman tidak ditemukan.</p>
  <a href="<?= htmlspecialchars($_SERVER['SCRIPT_NAME']) ?>/reports">Ke halaman laporan</a>
</body>
</html>
