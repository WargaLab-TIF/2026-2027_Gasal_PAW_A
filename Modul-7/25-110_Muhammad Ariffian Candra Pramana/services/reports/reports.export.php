<?php

require_once __DIR__ . "/../../koneksi.php";
require_once __DIR__ . "/../../utils.php";
require_once __DIR__ . "/reports.query.php";

$mulai = parseDate($_GET["mulai"] ?? "");
$selesai = parseDate($_GET["selesai"] ?? "");

if (!$mulai || !$selesai) {
  header("Location: ../../laporan.php");
  exit();
}

if ($mulai > $selesai) {
  [$mulai, $selesai] = [$selesai, $mulai];
}

[$rows, $summary] = getPenjualanReport($DBH, $mulai, $selesai);

header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=laporan_penjualan_{$mulai}sampai{$selesai}.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<html>
<head><meta charset="utf-8"></head>
<body>
  <h3>Rekap Laporan Penjualan</h3>
  <p>Periode: <?= $mulai ?> sampai <?= $selesai ?></p>
  <table border="1">
    <tr><th>No</th><th>Total Pendapatan</th><th>Tanggal Transaksi</th></tr>
    <?php foreach ($rows as $i => $row): ?>
      <tr>
        <td><?= $i + 1 ?></td>
        <td><?= $row["total"] ?></td>
        <td><?= $row["tgl"] ?></td>
      </tr>
    <?php endforeach ?>
    <tr><td colspan="2">Total pendapatan keseluruhan</td><td><?= $summary["pendapatan"] ?></td></tr>
  </table>
  <p>Jumlah pelanggan unik: <?= $summary["pelanggan"] ?> orang</p>
</body>
</html>