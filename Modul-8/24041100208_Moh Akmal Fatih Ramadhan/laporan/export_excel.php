
<?php

require_once __DIR__ . '/../auth.php';

wajibLogin();

$mulai = $_GET['mulai'] ?? '';

$selesai = $_GET['selesai'] ?? '';


// Validasi tanggal
$a = DateTime::createFromFormat('!Y-m-d', $mulai);

$b = DateTime::createFromFormat('!Y-m-d', $selesai);

if (
    !$a ||
    !$b ||
    $a->format('Y-m-d') !== $mulai ||
    $b->format('Y-m-d') !== $selesai ||
    $mulai > $selesai
) {

    http_response_code(400);

    exit('Rentang tanggal tidak valid.');

}


// Mengambil data laporan
$stmt = $DBH->prepare(

    'SELECT
        DATE(waktu_transaksi) AS tanggal,
        SUM(total) AS total_harian

     FROM transaksi

     WHERE waktu_transaksi >= ?
     AND waktu_transaksi < DATE_ADD(?, INTERVAL 1 DAY)

     GROUP BY DATE(waktu_transaksi)

     ORDER BY tanggal'

);

$stmt->execute([$mulai, $selesai]);

$rows = $stmt->fetchAll();


// Header file Excel
header(
    'Content-Type: application/vnd.ms-excel; charset=utf-8'
);

header(
    'Content-Disposition: attachment; filename="laporan_penjualan.xls"'
);


// Membuat tabel Excel
echo "<table border='1'>";

echo "
<tr>
    <th>No</th>
    <th>Tanggal</th>
    <th>Total Pendapatan</th>
</tr>
";

foreach ($rows as $i => $r) {

    echo '<tr>';

    echo '<td>' . ($i + 1) . '</td>';

    echo '<td>' .
        htmlspecialchars($r['tanggal']) .
        '</td>';

    echo '<td>' .
        (float)$r['total_harian'] .
        '</td>';

    echo '</tr>';

}

echo '</table>';

