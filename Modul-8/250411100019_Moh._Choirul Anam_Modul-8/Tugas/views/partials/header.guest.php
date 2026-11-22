<?php
// Halaman tamu (login). Jika sudah login, tidak boleh melihatnya -> ke Home.
if (is_logged_in()) {
  redirect("/");
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($title ?? 'Login') ?> - Sistem Penjualan</title>
  <?php require __DIR__ . "/style.php" ?>
</head>

<body>
  <main class="wrap narrow">
