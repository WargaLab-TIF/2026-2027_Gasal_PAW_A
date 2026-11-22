<?php
$mulai   = $_GET['tanggal_mulai']   ?? '';
$selesai = $_GET['tanggal_selesai'] ?? '';
if (empty($mulai) || empty($selesai)) {
    die("Rentang tanggal belum dipilih.");
}

// Header HTTP: isi dikirim sebagai file Excel dan langsung diunduh
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=rekap_penjualan.xls");

$koneksi = mysqli_connect("localhost", "root", "", "penjualan");
$m = mysqli_real_escape_string($koneksi, $mulai);
$s = mysqli_real_escape_string($koneksi, $selesai);

$q = "SELECT DATE(waktu_transaksi) AS tgl, SUM(total) AS total_harian
      FROM transaksi
      WHERE waktu_transaksi BETWEEN '$m' AND '$s'
      GROUP BY DATE(waktu_transaksi) ORDER BY tgl ASC";
$res = mysqli_query($koneksi, $q);
?>
<table border="1">
  <tr><th>No</th><th>Total Pendapatan</th><th>Tanggal Transaksi</th></tr>
  <?php $no = 1; while ($r = mysqli_fetch_assoc($res)): ?>
  <tr>
    <td><?= $no++; ?></td>
    <td><?= $r['total_harian']; ?></td>
    <td><?= date('d M Y', strtotime($r['tgl'])); ?></td>
  </tr>
  <?php endwhile; ?>
</table>
