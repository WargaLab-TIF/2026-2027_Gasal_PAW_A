<?php
if (!isset($_SESSION['login'])) {
    redirect('/login');
}

$tanggal_mulai   = isset($_GET['tanggal_mulai']) ? $_GET['tanggal_mulai'] : '';
$tanggal_selesai = isset($_GET['tanggal_selesai']) ? $_GET['tanggal_selesai'] : '';

$nama_file = "Laporan_Penjualan_" . $tanggal_mulai . "_sd_" . $tanggal_selesai . ".xls";
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$nama_file\"");
header("Pragma: no-cache");
header("Expires: 0");

$data = $DBH->prepare("
    SELECT waktu_transaksi, SUM(total) AS total_pendapatan
    FROM transaksi
    WHERE waktu_transaksi BETWEEN :mulai AND :selesai
    GROUP BY waktu_transaksi
    ORDER BY waktu_transaksi ASC
");
$data->execute([':mulai' => $tanggal_mulai, ':selesai' => $tanggal_selesai]);
$data_pertama = $data->fetchAll();

$data2 = $DBH->prepare("
    SELECT COUNT(DISTINCT pelanggan_id) AS jumlah_pelanggan,
           COALESCE(SUM(total), 0) AS total_keseluruhan
    FROM transaksi
    WHERE waktu_transaksi BETWEEN :mulai AND :selesai
");
$data2->execute([':mulai' => $tanggal_mulai, ':selesai' => $tanggal_selesai]);
$data_kedua = $data2->fetch();
?>
<h3>Laporan Rekapitulasi Penjualan</h3>
<table border="1">
    <thead>
        <tr>
            <th>No</th>
            <th>Total Pendapatan</th>
            <th>Tanggal Transaksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (count($data_pertama) > 0) {
            $no = 1;
            foreach ($data_pertama as $row) {
        ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $row['total_pendapatan']; ?></td>
                <td><?= $row['waktu_transaksi']; ?></td>
            </tr>
        <?php
            }
        } else {
        ?>
            <tr>
                <td colspan="3">Tidak ada data transaksi</td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>
<br>
<table border="1">
    <tr>
        <th>Jumlah Pelanggan Unik</th>
        <td><?= $data_kedua['jumlah_pelanggan']; ?></td>
    </tr>
    <tr>
        <th>Total Pendapatan Keseluruhan</th>
        <td><?= $data_kedua['total_keseluruhan']; ?></td>
    </tr>
</table>