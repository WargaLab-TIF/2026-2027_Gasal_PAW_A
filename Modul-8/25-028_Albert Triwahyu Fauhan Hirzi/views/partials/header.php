<?php
require_once dirname(__DIR__, 2) . '/auth.php';
wajib_login();
$level = (int) $_SESSION['user']['level'];
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($judul ?? 'Sistem Penjualan') ?></title>
  <link rel="stylesheet" href="assets/css/auth.css">
  <link rel="stylesheet" href="assets/css/table-theme.css">
  <link rel="stylesheet" href="assets/css/app.css">
</head>
<body>
  <?php require __DIR__ . '/navbar.php'; ?>
  <main>
