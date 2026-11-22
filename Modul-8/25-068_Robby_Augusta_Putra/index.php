<?php
$judul_halaman = 'Home - Sistem Penjualan';
$extra_head = <<<'HTML'
<style>
    .cards { display: flex; flex-wrap: wrap; gap: 15px; margin: 20px 0; }
    .card { background: #fff; border: 1px solid #d1d5db; border-left: 5px solid #2563eb;
            padding: 12px 20px; min-width: 190px; }
    .card small { color: #6b7280; }
    .card strong { display: block; font-size: 22px; margin-top: 4px; }
    .menu-cepat { display: flex; flex-wrap: wrap; gap: 10px; }
</style>
HTML;
include 'cek_login.php';
require_once __DIR__ . '/utils.php';

$total_barang    = (int) $db->query("SELECT COUNT(*) FROM barang")->fetchColumn();
$total_pelanggan = (int) $db->query("SELECT COUNT(*) FROM pelanggan")->fetchColumn();
$hariIni = $db->query("SELECT COUNT(*) AS jml, COALESCE(SUM(total), 0) AS omzet
                       FROM transaksi WHERE DATE(waktu_transaksi) = CURDATE()")->fetch();
include 'header.php';
?>

<div class="top">
    <h2>Selamat datang, <?= e($_SESSION['user_login']) ?></h2>
</div>
<p>Anda login sebagai <b><?= $_SESSION['level'] == 1 ? 'Owner' : 'Kasir' ?></b>. Silakan pilih menu di navigasi atas.</p>

<div class="cards">
    <div class="card"><small>Transaksi Hari Ini</small><strong><?= (int) $hariIni['jml'] ?></strong></div>
    <div class="card"><small>Pendapatan Hari Ini</small><strong><?= rupiah($hariIni['omzet']) ?></strong></div>
    <div class="card"><small>Total Barang</small><strong><?= $total_barang ?></strong></div>
    <div class="card"><small>Total Pelanggan</small><strong><?= $total_pelanggan ?></strong></div>
</div>

<?php include __DIR__ . '/footer.php'; ?>
