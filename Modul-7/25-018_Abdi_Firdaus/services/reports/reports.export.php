<?php
// ==========================================================
// FILE EKSPOR EXCEL (.XLS): reports.export.php
// ==========================================================

// Memuat fungsi parseDate() dan getSalesReport()
require_once __DIR__ . "/reports.query.php";

// Mengambil dan memvalidasi parameter rentang tanggal dari URL (GET)
$tanggal_mulai = parseDate($_GET["tanggal_mulai"] ?? $_GET["mulai"] ?? "");
$tanggal_selesai = parseDate($_GET["tanggal_selesai"] ?? $_GET["selesai"] ?? "");

// Jika tanggal belum dipilih atau tidak valid, arahkan kembali ke halaman laporan
if (!$tanggal_mulai || !$tanggal_selesai) {
  header("Location: " . routeUrl("/reports"));
  exit();
}

// Jika tanggal mulai lebih besar dari tanggal selesai, tukar posisinya secara otomatis
if ($tanggal_mulai > $tanggal_selesai) {
  [$tanggal_mulai, $tanggal_selesai] = [$tanggal_selesai, $tanggal_mulai];
}

// Mengambil data rekapitulasi harian dan ringkasan total dari database
[$rows, $summary] = getSalesReport($conn, $tanggal_mulai, $tanggal_selesai);

// Mengatur HTTP Response Header agar browser mengunduh output sebagai file Microsoft Excel (.xls)
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=laporan_penjualan_{$tanggal_mulai}_sampai_{$tanggal_selesai}.xls");
header("Pragma: no-cache");
header("Expires: 0");

?>
<html>

<head>
  <meta charset="utf-8">
</head>

<body>
  <!-- Judul dan Periode Laporan pada Lembar Kerja Excel -->
  <h3>Rekap Laporan Penjualan</h3>
  <p>Periode: <?= sanitazion($tanggal_mulai) ?> sampai <?= sanitazion($tanggal_selesai) ?></p>

  <!-- Tabel Rekapitulasi Penjualan Harian -->
  <table border="1">
    <tr><th>No</th><th>Total Pendapatan</th><th>Tanggal Transaksi</th></tr>
    <?php foreach ($rows as $i => $row): ?>
      <tr>
        <td><?= $i + 1 ?></td>
        <td>Rp. <?= number_format((int) $row["total_pendapatan"], 0, ",", ".") ?></td>
        <td><?= date("d M Y", strtotime($row["tgl"])) ?></td>
      </tr>
    <?php endforeach ?>
  </table>

  <br>

  <!-- Tabel Ringkasan Total Kumulatif (Jumlah Pelanggan Unik & Total Pendapatan) -->
  <table border="1">
    <tr><th>Jumlah Pelanggan</th><th>Jumlah Pendapatan</th></tr>
    <tr>
      <td><?= sanitazion($summary["pelanggan"] ?? 0) ?> Orang</td>
      <td>Rp. <?= number_format((int) ($summary["pendapatan"] ?? 0), 0, ",", ".") ?></td>
    </tr>
  </table>
</body>

</html>
