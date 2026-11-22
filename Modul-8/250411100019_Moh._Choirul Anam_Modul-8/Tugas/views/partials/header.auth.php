<?php
// Halaman yang memakai header ini hanya untuk user yang sudah login.
// (Router sudah menjaga; ini lapis pengaman kedua.)
if (!is_logged_in()) {
  redirect("/login");
}

$authUser = $_SESSION['user'];
$isOwner  = (int) $authUser['level'] === LEVEL_OWNER;
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($title ?? 'Sistem Penjualan') ?> - Sistem Penjualan</title>
  <?= $extraHead ?? '' ?>
  <?php require __DIR__ . "/style.php" ?>
</head>

<body>
  <nav class="topnav no-print">
    <div class="topnav-in">
      <a class="brand" href="<?= e(url('/')) ?>">Sistem Penjualan</a>

      <!-- (1) Menu navigasi: bergantung pada level -->
      <ul class="menu">
        <li><a href="<?= e(url('/')) ?>">Home</a></li>

        <?php if ($isOwner): ?>
          <!-- Data Master hanya tampil untuk level 1 (owner) -->
          <li class="dd">
            <a href="#" tabindex="0">Data Master &#9662;</a>
            <ul class="dd-menu">
              <li><a href="<?= e(url('/barang')) ?>">Data Barang</a></li>
              <li><a href="<?= e(url('/supplier')) ?>">Data Supplier</a></li>
              <li><a href="<?= e(url('/pelanggan')) ?>">Data Pelanggan</a></li>
              <li><a href="<?= e(url('/users')) ?>">Data User</a></li>
            </ul>
          </li>
        <?php endif ?>

        <li><a href="<?= e(url('/transaksi')) ?>">Transaksi</a></li>
        <li><a href="<?= e(url('/reports')) ?>">Laporan</a></li>
      </ul>

      <!-- (2) Nama user sesuai login + Logout -->
      <ul class="menu right">
        <li class="dd">
          <a href="#" tabindex="0"><?= e($authUser['nama']) ?> &#9662;</a>
          <ul class="dd-menu end">
            <li><a href="<?= e(url('/logout')) ?>">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>

  <main class="wrap">
