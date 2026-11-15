<?php
require_once __DIR__ . '/utils.php';
require_once __DIR__ . '/reports.query.php';

[$mulai, $selesai, $error] = ambil_filter();

if ($mulai === '' || $selesai === '' || $error !== '') {
    http_response_code(400);
    die('Filter tanggal tidak valid. Silakan kembali ke halaman laporan.');
}

$rekap     = getRekapHarian($mulai, $selesai);
$ringkasan = getRingkasan($mulai, $selesai);

$namaFile = "Rekap_Penjualan_{$mulai}_sd_{$selesai}.xls";
header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $namaFile . '"');
header('Pragma: no-cache');
header('Expires: 0');
?>
<html>
<head><meta charset="UTF-8"></head>
<body>
    <h3>Rekap Laporan Penjualan</h3>
    <p>Periode: <?= e($mulai) ?> sampai <?= e($selesai) ?></p>

    <table border="1">
        <tr style="background-color:#2563eb;color:#ffffff;">
            <th>No</th>
            <th>Total Pendapatan</th>
            <th>Tanggal Transaksi</th>
        </tr>
        <?php $no = 1; foreach ($rekap as $row): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= rupiah($row['total_harian']) ?></td>
                <td><?= tanggal_indo($row['tgl']) ?></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="3"></td>
        </tr>
        <tr>
            <td><b>Jumlah Pelanggan Unik</b></td>
            <td colspan="2"><?= $ringkasan['jumlah_pelanggan'] ?> Orang</td>
        </tr>
        <tr>
            <td><b>Total Pendapatan Keseluruhan</b></td>
            <td colspan="2"><?= rupiah($ringkasan['total_pendapatan']) ?></td>
        </tr>
    </table>
</body>
</html>
