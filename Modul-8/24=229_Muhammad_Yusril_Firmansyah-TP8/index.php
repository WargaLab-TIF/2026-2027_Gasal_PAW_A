<?php
require_once "includes/auth.php";
wajibLogin();
require_once "config/koneksi.php";
require_once "includes/header.php";
?>
<div class="card">
    <h1>Dashboard Sistem Penjualan</h1>
    <p>Selamat datang, <b><?= htmlspecialchars($_SESSION['user']['nama']) ?></b>.</p>
    <p class="muted">Anda login sebagai <?= (int)$_SESSION['user']['level'] === 1 ? 'Owner' : 'Kasir' ?>.</p>
</div>
<div class="grid">
    <?php if ((int)$_SESSION['user']['level'] === 1): ?>
    <div class="card"><h2>Data Master</h2><p>Kelola data barang, supplier, pelanggan, dan user.</p><a class="btn" href="master.php?jenis=barang">Buka Data Master</a></div>
    <?php endif; ?>
    <div class="card"><h2>Transaksi</h2><p>Lihat data transaksi penjualan.</p><a class="btn" href="transaksi.php">Buka Transaksi</a></div>
    <div class="card"><h2>Laporan</h2><p>Lihat ringkasan laporan penjualan.</p><a class="btn" href="laporan.php">Buka Laporan</a></div>
</div>
<?php require_once "includes/footer.php"; ?>
