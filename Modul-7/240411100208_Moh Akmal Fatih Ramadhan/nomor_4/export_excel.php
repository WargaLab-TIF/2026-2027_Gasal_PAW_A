<?php

require_once 'config.php';

$tanggal_mulai = $_GET['tanggal_mulai'] ?? '';
$tanggal_selesai = $_GET['tanggal_selesai'] ?? '';

$tglMulai = DateTime::createFromFormat('!Y-m-d', $tanggal_mulai);
$tglSelesai = DateTime::createFromFormat('!Y-m-d', $tanggal_selesai);

if (
    !$tglMulai ||
    !$tglSelesai ||
    $tglMulai->format('Y-m-d') !== $tanggal_mulai ||
    $tglSelesai->format('Y-m-d') !== $tanggal_selesai ||
    $tanggal_mulai > $tanggal_selesai
) {
    die("Rentang tanggal tidak valid.");
}

$sql = "SELECT
            DATE(waktu_transaksi) AS tanggal,
            SUM(total) AS total_harian
        FROM transaksi
        WHERE DATE(waktu_transaksi)
        BETWEEN :mulai AND :selesai
        GROUP BY DATE(waktu_transaksi)
        ORDER BY tanggal ASC";

$stmt = $DBH->prepare($sql);

$stmt->execute([
    ':mulai' => $tanggal_mulai,
    ':selesai' => $tanggal_selesai
]);

$data = $stmt->fetchAll();

header("Content-Type: application/vnd.ms-excel; charset=utf-8");

header("Content-Disposition: attachment; filename=laporan_penjualan.xls");

header("Pragma: no-cache");

header("Expires: 0");

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
</head>

<body>

    <h2>Laporan Penjualan</h2>

    <p>
        Periode:
        <?= htmlspecialchars($tanggal_mulai) ?>
        sampai
        <?= htmlspecialchars($tanggal_selesai) ?>
    </p>

    <table border="1">

        <thead>

            <tr>
                <th>No</th>
                <th>Total Pendapatan</th>
                <th>Tanggal Transaksi</th>
            </tr>

        </thead>

        <tbody>

            <?php if (!empty($data)): ?>

                <?php $no = 1; ?>

                <?php foreach ($data as $row): ?>

                    <tr>

                        <td><?= $no++ ?></td>

                        <td>
                            <?= $row['total_harian'] ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['tanggal']) ?>
                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="3">
                        Tidak ada data penjualan.
                    </td>
                </tr>

            <?php endif; ?>

        </tbody>

    </table>

</body>
</html>
