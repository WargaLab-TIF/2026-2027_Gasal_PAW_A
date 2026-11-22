<?php
require_once "includes/auth.php";
wajibLogin();
require_once "config/koneksi.php";
require_once "includes/header.php";
$ringkas = $conn->query("SELECT COUNT(*) jumlah_transaksi, COALESCE(SUM(total),0) total_penjualan FROM transaksi")->fetch_assoc();
$data = $conn->query("SELECT DATE(waktu_transaksi) tanggal, COUNT(*) jumlah_transaksi, SUM(total) total
                      FROM transaksi GROUP BY DATE(waktu_transaksi) ORDER BY tanggal DESC");
?>
<div class="card">
    <h1>Laporan Penjualan</h1>
    <div class="grid">
        <div class="card"><p class="muted">Jumlah Transaksi</p><h2><?= (int)$ringkas['jumlah_transaksi'] ?></h2></div>
        <div class="card"><p class="muted">Total Penjualan</p><h2>Rp <?= number_format((int)$ringkas['total_penjualan'],0,',','.') ?></h2></div>
    </div>
    <h2>Rekap Penjualan per Tanggal</h2>
    <table>
        <tr><th>Tanggal</th><th>Jumlah Transaksi</th><th>Total Penjualan</th></tr>
        <?php while($r=$data->fetch_assoc()): ?>
        <tr><td><?= htmlspecialchars($r['tanggal']) ?></td><td><?= (int)$r['jumlah_transaksi'] ?></td><td>Rp <?= number_format((int)$r['total'],0,',','.') ?></td></tr>
        <?php endwhile; ?>
    </table>
</div>
<?php require_once "includes/footer.php"; ?>
