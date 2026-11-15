<?php
// Dipanggil lewat route /api/reports/export di index.php
// ($conn sudah tersedia dari conn.php)
require_once __DIR__ . "/reports.query.php";

$mulai   = $_GET['tanggal_mulai']   ?? '';
$selesai = $_GET['tanggal_selesai'] ?? '';

if (!reports_tanggal_valid($mulai) || !reports_tanggal_valid($selesai) || $mulai > $selesai) {
  http_response_code(400);
  die('Rentang tanggal tidak valid.');
}

$data = reports_ambil_laporan($conn, $mulai, $selesai);

// Header HTTP: browser mengunduh respons ini sebagai file .xls
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=rekap_penjualan_{$mulai}_sd_{$selesai}.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">
  <tr><th colspan="3">Rekap Laporan Penjualan <?= $mulai ?> sampai <?= $selesai ?></th></tr>
  <tr><th>No</th><th>Total Pendapatan</th><th>Tanggal Transaksi</th></tr>
  <?php $no = 1; foreach ($data['rekap'] as $row): ?>
    <tr>
      <td><?= $no++ ?></td>
      <td><?= (int)$row['total_harian'] ?></td>
      <td><?= date('d M Y', strtotime($row['tgl'])) ?></td>
    </tr>
  <?php endforeach; ?>
  <tr><td colspan="2"><b>Jumlah Pelanggan Unik</b></td><td><?= $data['total_pelanggan'] ?> Orang</td></tr>
  <tr><td colspan="2"><b>Total Pendapatan Keseluruhan</b></td><td><?= $data['total_pendapatan'] ?></td></tr>
</table>
