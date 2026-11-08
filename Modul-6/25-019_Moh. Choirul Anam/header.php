<?php require_once 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title><?= h($judul ?? 'Penjualan') ?></title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<nav>
  <a href="barang.php">Data Barang</a>
  <a href="transaksi.php">Data Transaksi</a>
</nav>
<div class="wrap">
<?php if (!empty($_SESSION['flash'])):
    $f = $_SESSION['flash'];
    unset($_SESSION['flash']); ?>
  <div class="alert <?= h($f['tipe']) ?>"><?= h($f['pesan']) ?></div>
<?php endif; ?>
