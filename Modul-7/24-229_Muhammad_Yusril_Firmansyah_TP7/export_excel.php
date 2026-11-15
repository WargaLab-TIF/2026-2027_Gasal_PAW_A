<?php
require_once 'config.php';
require_once 'functions.php';

$tanggalMulai = $_GET['tanggal_mulai'] ?? '';
$tanggalSelesai = $_GET['tanggal_selesai'] ?? '';

if (!validDate($tanggalMulai) || !validDate($tanggalSelesai) || $tanggalMulai > $tanggalSelesai) {
    http_response_code(400);
    exit('Rentang tanggal tidak valid.');
}

$sql = "SELECT DATE(waktu_transaksi) AS tanggal_transaksi,
               SUM(total) AS total_pendapatan
        FROM transaksi
        WHERE waktu_transaksi BETWEEN :mulai AND :selesai
        GROUP BY DATE(waktu_transaksi)
        ORDER BY DATE(waktu_transaksi) ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute(['mulai' => $tanggalMulai, 'selesai' => $tanggalSelesai]);
$data = $stmt->fetchAll();

header('Content-Type: application/vnd.ms-excel; charset=utf-8');
header('Content-Disposition: attachment; filename="laporan_penjualan_' . $tanggalMulai . '_sampai_' . $tanggalSelesai . '.xls"');
header('Cache-Control: max-age=0');

echo "\xEF\xBB\xBF";
?>
<table border="1">
    <tr><th colspan="3">LAPORAN PENJUALAN</th></tr>
    <tr><td colspan="3">Periode: <?= htmlspecialchars($tanggalMulai) ?> sampai <?= htmlspecialchars($tanggalSelesai) ?></td></tr>
    <tr><th>No</th><th>Total Pendapatan</th><th>Tanggal Transaksi</th></tr>
    <?php $grandTotal = 0; foreach ($data as $i => $row): $grandTotal += (int)$row['total_pendapatan']; ?>
    <tr>
        <td><?= $i + 1 ?></td>
        <td><?= (int)$row['total_pendapatan'] ?></td>
        <td><?= htmlspecialchars($row['tanggal_transaksi']) ?></td>
    </tr>
    <?php endforeach; ?>
    <tr><th colspan="1">Total Keseluruhan</th><th colspan="2"><?= $grandTotal ?></th></tr>
</table>
