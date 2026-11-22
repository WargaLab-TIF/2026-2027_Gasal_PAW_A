<?php
require_once '../cek_login.php';
require_once '../koneksi.php';

$tanggal_mulai   = (!empty($_GET['tanggal_mulai'])) ? $_GET['tanggal_mulai'] : date('Y-m-01');
$tanggal_selesai = (!empty($_GET['tanggal_selesai'])) ? $_GET['tanggal_selesai'] : date('Y-m-d');

if ($tanggal_mulai > $tanggal_selesai) {
    [$tanggal_mulai, $tanggal_selesai] = [$tanggal_selesai, $tanggal_mulai];
}

$stmtHarian = $DBH->prepare("
    SELECT 
        DATE(waktu_transaksi) AS tanggal,
        SUM(total) AS total_pendapatan
    FROM transaksi
    WHERE DATE(waktu_transaksi) BETWEEN :tanggal_mulai AND :tanggal_selesai
    GROUP BY DATE(waktu_transaksi)
    ORDER BY DATE(waktu_transaksi) ASC
");
$stmtHarian->execute([
    ':tanggal_mulai'   => $tanggal_mulai,
    ':tanggal_selesai' => $tanggal_selesai
]);
$rekap_harian = $stmtHarian->fetchAll();

$stmtRingkasan = $DBH->prepare("
    SELECT 
        COUNT(DISTINCT pelanggan_id) AS jumlah_pelanggan_unik,
        COALESCE(SUM(total), 0) AS total_pendapatan_keseluruhan
    FROM transaksi
    WHERE DATE(waktu_transaksi) BETWEEN :tanggal_mulai AND :tanggal_selesai
");
$stmtRingkasan->execute([
    ':tanggal_mulai'   => $tanggal_mulai,
    ':tanggal_selesai' => $tanggal_selesai
]);
$ringkasan = $stmtRingkasan->fetch();

$filename = "Laporan_Penjualan_" . $tanggal_mulai . "_sd_" . $tanggal_selesai . ".xls";
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <h3>LAPORAN REKAPITULASI PENJUALAN</h3>
    <p>Periode: <?= date('d-m-Y', strtotime($tanggal_mulai)) ?> s/d <?= date('d-m-Y', strtotime($tanggal_selesai)) ?></p>
    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr style="background-color: #f1f5f9; font-weight: bold;">
                <th>No</th>
                <th>Total Pendapatan</th>
                <th>Tanggal Transaksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($rekap_harian) > 0): ?>
                <?php $no = 1; foreach ($rekap_harian as $row): ?>
                    <tr>
                        <td align="center"><?= $no++ ?></td>
                        <td align="right"><?= (int) $row['total_pendapatan'] ?></td>
                        <td align="center"><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" align="center">Tidak ada data transaksi pada periode ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr style="font-weight: bold; background-color: #f8fafc;">
                <td colspan="2">Jumlah Pelanggan Unik</td>
                <td align="right"><?= (int) ($ringkasan['jumlah_pelanggan_unik'] ?? 0) ?> Pelanggan</td>
            </tr>
            <tr style="font-weight: bold; background-color: #dcfce7;">
                <td colspan="2">Total Pendapatan Keseluruhan</td>
                <td align="right"><?= (int) ($ringkasan['total_pendapatan_keseluruhan'] ?? 0) ?></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
<?php exit; ?>