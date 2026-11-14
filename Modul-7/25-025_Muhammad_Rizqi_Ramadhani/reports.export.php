<?php
require_once __DIR__ . "/koneksi.php";
require_once __DIR__ . "/reports.query.php";

$mulai = parseDate($_GET["mulai"] ?? "");
$selesai = parseDate($_GET["selesai"] ?? "");

if (!$mulai || !$selesai) {
    header("Location: reporting.php");
    exit();
}

if ($mulai > $selesai) {
    [$mulai, $selesai] = [$selesai, $mulai];
}

[$rows, $summary] = getLoanReport($conn, $mulai, $selesai);

header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=laporan_penjualan_{$mulai}_sampai_{$selesai}.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<html>

<head>
  <meta charset="utf-8">
</head>

<body>
  <h3>Rekap Laporan Penjualan</h3>
  <p>Periode: <?= $mulai ?> sampai <?= $selesai ?></p>
  
  <table border="1" cellpadding="5" cellspacing="0">
    <thead>
      <tr style="background-color: #f2f2f2;">
        <th>No</th>
        <th>Tanggal Transaksi</th>
        <th>Jumlah Transaksi</th>
        <th>Total Pendapatan Harian</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($rows as $i => $row): ?>
        <tr>
          <td><?= $i + 1 ?></td>
          <td><?= $row["tgl"] ?></td>
          <td><?= $row["total"] ?></td>
          <td><?= $row["pendapatan_harian"] ?></td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>

  <br>

  <table border="1" cellpadding="5" cellspacing="0">
    <thead>
      <tr style="background-color: #e9ecef;">
        <th>Jumlah Pelanggan Unik</th>
        <th>Total Transaksi Keseluruhan</th>
        <th>Total Pendapatan Keseluruhan</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><?= $summary["pelanggan"] ?> Orang</td>
        <td><?= $summary["total"] ?> Transaksi</td>
        <td><?= $summary["pendapatan"] ?></td>
      </tr>
    </tbody>
  </table>
</body>

</html>