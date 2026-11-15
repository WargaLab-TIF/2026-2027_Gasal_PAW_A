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

$labels            = [];
$data_total        = [];
$rekap             = [];
$total_pelanggan   = 0;
$total_keseluruhan = 0;
$sudah_filter      = false;

if (!empty($tanggal_mulai) && !empty($tanggal_selesai)) {
    $sudah_filter = true;
    $mulai_safe   = mysqli_real_escape_string($koneksi, $tanggal_mulai);
    $selesai_safe = mysqli_real_escape_string($koneksi, $tanggal_selesai);

    
    $query_harian = "SELECT DATE(waktu_transaksi) AS tgl, SUM(total) AS total_harian
                     FROM transaksi
                     WHERE waktu_transaksi BETWEEN '$mulai_safe' AND '$selesai_safe'
                     GROUP BY DATE(waktu_transaksi)
                     ORDER BY tgl ASC";
    $res_harian = mysqli_query($koneksi, $query_harian);

    while ($row = mysqli_fetch_assoc($res_harian)) {
        $labels[]     = $row['tgl'];
        $data_total[] = (int)$row['total_harian'];
        $rekap[]      = $row;
    }

    
    $query_total = "SELECT COUNT(DISTINCT pelanggan_id) AS jml_pelanggan,
                           SUM(total) AS total_pendapatan
                    FROM transaksi
                    WHERE waktu_transaksi BETWEEN '$mulai_safe' AND '$selesai_safe'";
    $res_total = mysqli_fetch_assoc(mysqli_query($koneksi, $query_total));

    $total_pelanggan   = $res_total['jml_pelanggan'] ?? 0;
    $total_keseluruhan = $res_total['total_pendapatan'] ?? 0;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Laporan Penjualan</title>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 25px;
            color: #333;
        }
        h2 {
            margin-bottom: 15px;
        }
        .filter-box {
            background: #f8f9fa;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            margin-bottom: 15px;
        }
        .filter-box input[type="date"] {
            padding: 6px 10px;
            margin: 0 10px 0 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .btn {
            padding: 7px 14px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
            color: #fff;
        }
        .btn-primary { background-color: #0d6efd; }
        .btn-warning { background-color: #e67e22; }
        .btn-success { background-color: #198754; }
        .action-buttons {
            margin-bottom: 20px;
        }
        .chart-container {
            width: 100%;
            max-width: 750px;
            margin-bottom: 25px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 25px;
        }
        th, td {
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #d9edf7;
            color: #2c3e50;
        }
        
        .summary-card {
            display: inline-table;
            border-collapse: collapse;
            width: 420px;
            border: 1px solid #dee2e6;
        }
        .summary-card th {
            background-color: #d9edf7;
            text-align: left;
            width: 50%;
        }
        .summary-card td {
            font-size: 16px;
            font-weight: bold;
            color: #0d6efd;
            text-align: right;
            padding: 14px 12px;
        }

        
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                margin: 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

    <h2>
        Rekap Laporan Penjualan
        <?php if ($sudah_filter): ?>
            <small style="font-weight: normal; font-size: 16px;">
                (<?= htmlspecialchars($tanggal_mulai); ?> sampai <?= htmlspecialchars($tanggal_selesai); ?>)
            </small>
        <?php endif; ?>
    </h2>

    
    <div class="filter-box no-print">
        <form method="GET" action="">
            <label for="tanggal_mulai">Dari Tanggal:</label>
            <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="<?= htmlspecialchars($tanggal_mulai); ?>" required>

            <label for="tanggal_selesai">Sampai Tanggal:</label>
            <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="<?= htmlspecialchars($tanggal_selesai); ?>" required>

            <button type="submit" class="btn btn-primary">Tampilkan</button>
        </form>
    </div>

    <?php if ($sudah_filter): ?>
        <?php if (!empty($rekap)): ?>

            
            <div class="action-buttons no-print">
                <button type="button" class="btn btn-warning" onclick="window.print()">Cetak</button>
                <a href="export_excel.php?tanggal_mulai=<?= urlencode($tanggal_mulai); ?>&tanggal_selesai=<?= urlencode($tanggal_selesai); ?>" class="btn btn-success">Excel</a>
            </div>

            
            <div class="chart-container">
                <canvas id="grafikPenjualan"></canvas>
            </div>

            <script>
                const ctx = document.getElementById('grafikPenjualan').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: <?= json_encode($labels); ?>,
                        datasets: [{
                            label: 'Total Pendapatan',
                            data: <?= json_encode($data_total); ?>,
                            backgroundColor: 'rgba(180, 180, 180, 0.7)',
                            borderColor: 'rgba(130, 130, 130, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            </script>

            
            <table>
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th>Total Pendapatan</th>
                        <th>Tanggal Transaksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($rekap as $row): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td>Rp. <?= number_format($row['total_harian'], 0, ',', '.'); ?></td>
                            <td><?= date('d M Y', strtotime($row['tgl'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            
            <table class="summary-card">
                <thead>
                    <tr>
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

        <?php else: ?>
            <p>Tidak ditemukan data transaksi pada rentang tanggal <b><?= htmlspecialchars($tanggal_mulai); ?></b> sampai <b><?= htmlspecialchars($tanggal_selesai); ?></b>.</p>
        <?php endif; ?>
    <?php else: ?>
        <p class="no-print">Silakan pilih rentang tanggal mulai dan tanggal selesai, lalu klik tombol <b>Tampilkan</b>.</p>
    <?php endif; ?>

</body>
</html>