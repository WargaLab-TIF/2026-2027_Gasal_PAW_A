<?php

require_once __DIR__ . "/../../route.php";
require_once __DIR__ . "/../../utils.php";
require_once __DIR__ . "/reports.query.php";

$mulai = parseDate($_GET["mulai"] ?? "");
$selesai = parseDate($_GET["selesai"] ?? "");

if (!$mulai || !$selesai) {
  redirect("reports");
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

<head>
  <meta charset="utf-8">
</head>

<body>
  <h3>Rekap Laporan Penjualan</h3>
  <p>Periode: <?= sanitazion($mulai) ?> sampai <?= sanitazion($selesai) ?></p>
  <table border="1">
    <tr>
      <th>No</th>
      <th>Tanggal Transaksi</th>
      <th>Total Pendapatan</th>
    </tr>
    <?php foreach ($rows as $i => $row): ?>
      <tr>
        <td><?= $i + 1 ?></td>
        <td><?= sanitazion($row["tgl"]) ?></td>
        <td><?= (int) $row["total"] ?></td>
      </tr>
    <?php endforeach ?>
    <tr>
      <td colspan="2">Total pendapatan keseluruhan</td>
      <td><?= (int) $summary["pendapatan"] ?></td>
    </tr>
  </table>
  <p>Jumlah pelanggan unik: <?= (int) $summary["pelanggan"] ?> orang</p>
</body>

</html>
