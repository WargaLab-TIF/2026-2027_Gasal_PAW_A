<?php
require_once 'koneksi.php';

$tanggal_mulai   = isset($_GET['tanggal_mulai']) ? $_GET['tanggal_mulai'] : '';
$tanggal_selesai = isset($_GET['tanggal_selesai']) ? $_GET['tanggal_selesai'] : '';

$data1 = $DBH->prepare("
    SELECT waktu_transaksi, SUM(total) AS total_pendapatan
    FROM transaksi
    WHERE waktu_transaksi BETWEEN ? AND ?
    GROUP BY waktu_transaksi
    ORDER BY waktu_transaksi ASC
");
$data1->execute([$tanggal_mulai, $tanggal_selesai]);
$data_pertama = $data1->fetchAll();
$tanggal    = [];
$pendapatan = [];
foreach ($data_pertama as $row) {
    $tanggal[]    = $row['waktu_transaksi'];
    $pendapatan[] = (int)$row['total_pendapatan'];
}
$data2 = $DBH->prepare("
    SELECT
        COUNT(DISTINCT pelanggan_id) AS jumlah_pelanggan,
        COALESCE(SUM(total), 0) AS total_keseluruhan
    FROM transaksi
    WHERE waktu_transaksi BETWEEN ? AND ?
");
$data2->execute([$tanggal_mulai, $tanggal_selesai]);
$data_kedua = $data2->fetch();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        button { margin-right: 10px; }
        table { border-collapse: collapse; width: 600px; margin-top: 15px; }
        th, td { border: 1px solid black; padding: 6px; text-align: left; }
        .ringkasan { margin-top: 15px; border: 1px solid black; padding: 10px; width: 580px; }
        @media print {
    .tombol { display: none; }
}
    </style>
</head>
<body>
    <h2>Laporan Penjualan</h2>

    <div class="tombol">
    <form method="GET" action="reporting.php">
        Dari: <input type="date" name="tanggal_mulai"
                     value="<?= htmlspecialchars($tanggal_mulai); ?>" required>
        Sampai: <input type="date" name="tanggal_selesai"
                       value="<?= htmlspecialchars($tanggal_selesai); ?>" required>
        <button type="submit">Tampilkan</button>
    </form>
    <br>
    <button onclick="window.print()">Cetak (Print)</button>
    <a href="export.php?tanggal_mulai=<?= $tanggal_mulai; ?>&tanggal_selesai=<?= $tanggal_selesai; ?>">        
    <button type="button">Ekspor Excel</button>
    </a>
</div>

    <pre><div style="width: 600px; margin-top: 20px;">
    <canvas id="chart"></canvas>
</div>
<table>
    <tr>
        <th>No</th>
        <th>Total Pendapatan</th>
        <th>Tanggal Transaksi</th>
    </tr>
    <?php
    if ($data_pertama) {
        $no = 1;
        foreach ($data_pertama as $row) {
    ?>
        <tr>
            <td><?= $no++; ?></td>
            <td>Rp <?= number_format($row['total_pendapatan']); ?></td>
            <td><?= $row['waktu_transaksi']; ?></td>
        </tr>
    <?php
        }
    } else {
    ?>
        <tr>
            <td colspan="3">Tidak ada data transaksi.</td>
        </tr>
    <?php
    }
    ?>
</table>

<div class="ringkasan">
    <p><strong>Jumlah Pelanggan Unik:</strong> <?= $data_kedua['jumlah_pelanggan']; ?> Orang</p>
    <p><strong>Total Pendapatan Keseluruhan:</strong> Rp <?= number_format($data_kedua['total_keseluruhan']); ?></p>
</div>

<script>
    new Chart(document.getElementById('chart'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($tanggal); ?>,
            datasets: [{
                label: 'Total Pendapatan',
                data: <?= json_encode($pendapatan); ?>,
                borderWidth: 1
            }]
        },
        options: {
            scales: { y: { beginAtZero: true } }
        }
    });
</script></pre>
</body>
</html>