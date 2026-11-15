<?php

$host = "localhost";
$user = "root";
$pass = "";
$db   = "penjualan";

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

$tanggal_mulai   = $_GET['tanggal_mulai'] ?? '';
$tanggal_selesai = $_GET['tanggal_selesai'] ?? '';

if (empty($tanggal_mulai) || empty($tanggal_selesai)) {
    die("Parameter rentang tanggal tidak valid.");
}

$mulai_safe   = mysqli_real_escape_string($koneksi, $tanggal_mulai);
$selesai_safe = mysqli_real_escape_string($koneksi, $tanggal_selesai);


$nama_file = "Rekap_Penjualan_" . $tanggal_mulai . "_sd_" . $tanggal_selesai . ".xls";
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$nama_file\"");
header("Pragma: no-cache");
header("Expires: 0");


$query_harian = "SELECT DATE(waktu_transaksi) AS tgl, SUM(total) AS total_harian
                 FROM transaksi
                 WHERE waktu_transaksi BETWEEN '$mulai_safe' AND '$selesai_safe'
                 GROUP BY DATE(waktu_transaksi)
                 ORDER BY tgl ASC";
$res_harian = mysqli_query($koneksi, $query_harian);


$query_total = "SELECT COUNT(DISTINCT pelanggan_id) AS jml_pelanggan,
                       SUM(total) AS total_pendapatan
                FROM transaksi
                WHERE waktu_transaksi BETWEEN '$mulai_safe' AND '$selesai_safe'";
$res_total = mysqli_fetch_assoc(mysqli_query($koneksi, $query_total));

$total_pelanggan   = $res_total['jml_pelanggan'] ?? 0;
$total_keseluruhan = $res_total['total_pendapatan'] ?? 0;
?>

<h3>Rekap Laporan Penjualan (<?= htmlspecialchars($tanggal_mulai); ?> s/d <?= htmlspecialchars($tanggal_selesai); ?>)</h3>

<table border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr style="background-color: #d9edf7;">
            <th>No</th>
            <th>Total Pendapatan</th>
            <th>Tanggal Transaksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        while ($row = mysqli_fetch_assoc($res_harian)):
        ?>
            <tr>
                <td><?= $no++; ?></td>
                <td>Rp. <?= number_format($row['total_harian'], 0, ',', '.'); ?></td>
                <td><?= date('d M Y', strtotime($row['tgl'])); ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<br>

<table border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr style="background-color: #d9edf7;">
            <th>Jumlah Pelanggan</th>
            <th>Jumlah Pendapatan</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?= $total_pelanggan; ?> Orang</td>
            <td>Rp. <?= number_format($total_keseluruhan, 0, ',', '.'); ?></td>
        </tr>
    </tbody>
</table>