<?php
session_start();
require_once '../koneksi.php';

// Proteksi Login
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location: ../index.php");
    exit();
}

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

$tanggal = []; 
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
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> 
    <style>
        body {
            background-color: #f8f9fa;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            background-color: white;
            margin-top: 15px; 
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f8d7da; 
        }
        button a {
            text-decoration: none;
            color: white;
        }
        .btn-tampil {
            background-color: #0d6efd;
            color: white;
            border-radius: 5px;
            padding: 6px 12px;
            border: none;
        }
        .btn-print {
            background-color: #6c757d;
            color: white;
            border-radius: 5px;
            padding: 6px 12px;
            border: none;
        }
        .btn-excel {
            background-color: #198754;
            color: white;
            border-radius: 5px;
            padding: 6px 12px;
            border: none;
        }
        .ringkasan {
            margin-top: 15px; 
            border: 1px solid #000; 
            background-color: white;
            padding: 12px;
        }
        @media print {
            .tombol, nav { display: none !important; }
        }
    </style>
</head>
<body>
    <?php include '../navbar.php'; ?>

    <div class="container">
        <h2>Laporan Penjualan</h2> 

        <div class="tombol mb-3">
            <form method="GET" action="laporan.php" class="mb-2"> 
                Dari: <input type="date" name="tanggal_mulai" value="<?= $tanggal_mulai; ?>" required> 
                Sampai: <input type="date" name="tanggal_selesai" value="<?= $tanggal_selesai; ?>" required> 
                <button type="submit" class="btn-tampil">Tampilkan</button> 
            </form>
            
            <button type="button" onclick="window.print()" class="btn-print">Cetak (Print)</button> 
            <button type="button" class="btn-excel">
                <a href="export.php?tanggal_mulai=<?= $tanggal_mulai; ?>&tanggal_selesai=<?= $tanggal_selesai; ?>">Ekspor Excel</a> 
            </button>
        </div>

        <div style="max-width: 700px; background-color: white; padding: 15px; border: 1px solid #000;">
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
                        <td>Rp <?= number_format($row['total_pendapatan'], 0, ',', '.'); ?></td> 
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
            <p class="mb-1"><strong>Jumlah Pelanggan Unik:</strong> <?= $data_kedua['jumlah_pelanggan']; ?> Orang</p> 
            <p class="mb-0"><strong>Total Pendapatan Keseluruhan:</strong> Rp <?= number_format($data_kedua['total_keseluruhan'], 0, ',', '.'); ?></p> 
        </div>
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
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script> 

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>