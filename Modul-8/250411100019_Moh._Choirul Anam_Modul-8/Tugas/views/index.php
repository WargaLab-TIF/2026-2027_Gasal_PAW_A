<?php

$title   = "Home";
$isOwner = (int) $_SESSION['user']['level'] === LEVEL_OWNER;

?>
<?php require_once __DIR__ . "/partials/header.auth.php" ?>

<h1>Selamat datang, <?= e($_SESSION['user']['nama']) ?>!</h1>
<?= render_flash() ?>
<p>Anda login sebagai <b><?= $isOwner ? "Owner" : "Kasir" ?></b>.</p>

<?php if ($isOwner): ?>
  <div class="card-box"><b><a href="<?= e(url('/barang')) ?>">Data Master</a></b><br>Kelola data barang, supplier, pelanggan, dan user.</div>
<?php endif ?>
<div class="card-box"><b><a href="<?= e(url('/transaksi')) ?>">Transaksi</a></b><br>Catat dan lihat transaksi penjualan.</div>
<div class="card-box"><b><a href="<?= e(url('/reports')) ?>">Laporan</a></b><br>Rekap penjualan per periode, cetak, dan unduh Excel.</div>

<?php require_once __DIR__ . "/partials/footer.auth.php" ?>
