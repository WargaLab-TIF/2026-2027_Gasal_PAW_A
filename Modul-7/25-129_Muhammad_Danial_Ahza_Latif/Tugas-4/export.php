
<?php
require_once 'koneksi.php';

// Mengambil tanggal dari URL
$tanggal_mulai = $_GET['tanggal_mulai'] ?? '';
$tanggal_selesai = $_GET['tanggal_selesai'] ?? '';

// Validasi format tanggal
if (
    ($tanggal_mulai !== '' && !DateTime::createFromFormat('Y-m-d', $tanggal_mulai)) ||
    ($tanggal_selesai !== '' && !DateTime::createFromFormat('Y-m-d', $tanggal_selesai))
) {
    die("Format tanggal tidak valid.");
}

// Validasi rentang tanggal
if (
    !empty($tanggal_mulai) &&
    !empty($tanggal_selesai) &&
    $tanggal_mulai > $tanggal_selesai
) {
    die("Tanggal mulai tidak boleh melebihi tanggal selesai.");
}

$sql = "
    SELECT
        DATE(waktu_transaksi) AS tanggal,
        SUM(total) AS total_penjualan
    FROM transaksi
";

$params = [];

if (!empty($tanggal_mulai) && !empty($tanggal_selesai)) {
    $sql .= "
        WHERE DATE(waktu_transaksi)
        BETWEEN :tanggal_mulai AND :tanggal_selesai
    ";

    $params[':tanggal_mulai'] = $tanggal_mulai;
    $params[':tanggal_selesai'] = $tanggal_selesai;
}

$sql .= "
    GROUP BY DATE(waktu_transaksi)
    ORDER BY tanggal ASC
";

$query = $DBH->prepare($sql);
$query->execute($params);

$data_laporan = $query->fetchAll(PDO::FETCH_ASSOC);

// Nama file Excel
$filename = "laporan-penjualan-" . date('Ymd-His') . ".xls";

header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Cache-Control: max-age=0");

echo "\xEF\xBB\xBF";
?>

<html>
<head>
    <meta charset="UTF-8">
</head>

<body>

    <table border="1">

        <thead>
            <tr>
                <th colspan="3">
                    LAPORAN REKAPITULASI PENJUALAN HARIAN
                </th>
            </tr>

            <tr>
                <th>No</th>
                <th>Total Pendapatan</th>
                <th>Tanggal Transaksi</th>
            </tr>
        </thead>

        <tbody>

            <?php
            $no = 1;
            $total_keseluruhan = 0;
            ?>

            <?php foreach ($data_laporan as $data): ?>

                <tr>
                    <td><?= $no++ ?></td>

                    <td>
                        <?= (int) $data['total_penjualan'] ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($data['tanggal']) ?>
                    </td>
                </tr>

                <?php
                $total_keseluruhan += $data['total_penjualan'];
                ?>

            <?php endforeach; ?>

            <?php if (count($data_laporan) === 0): ?>
                <tr>
                    <td colspan="3">
                        Tidak ada data penjualan.
                    </td>
                </tr>
            <?php endif; ?>

        </tbody>

        <tfoot>
            <tr>
                <th>Total Keseluruhan</th>

                <th>
                    <?= $total_keseluruhan ?>
                </th>

                <th>
                    <?= count($data_laporan) ?> Hari
                </th>
            </tr>
        </tfoot>

    </table>

</body>
</html>
