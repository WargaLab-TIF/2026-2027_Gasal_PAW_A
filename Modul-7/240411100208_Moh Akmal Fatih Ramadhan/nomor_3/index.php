<?php
require_once 'config.php';

$tanggal_mulai = $_GET['tanggal_mulai'] ?? '';
$tanggal_selesai = $_GET['tanggal_selesai'] ?? '';

$rekap = [];
$labels = [];
$data_total = [];

$total_pendapatan = 0;
$total_pelanggan = 0;
$error = '';

if (!empty($tanggal_mulai) && !empty($tanggal_selesai)) {

    if ($tanggal_mulai > $tanggal_selesai) {

        $error = "Tanggal mulai tidak boleh melebihi tanggal selesai.";

    } else {

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

        $rekap = $stmt->fetchAll();

        // Menyiapkan data grafik
        foreach ($rekap as $row) {

            $labels[] = $row['tanggal'];

            $data_total[] = (float) $row['total_harian'];

        }

        // Menghitung pelanggan unik dan total pendapatan
        $sqlTotal = "SELECT
                        COALESCE(SUM(total), 0) AS total_pendapatan,
                        COUNT(DISTINCT pelanggan_id) AS total_pelanggan
                     FROM transaksi
                     WHERE DATE(waktu_transaksi)
                     BETWEEN :mulai AND :selesai";

        $stmtTotal = $DBH->prepare($sqlTotal);

        $stmtTotal->execute([
            ':mulai' => $tanggal_mulai,
            ':selesai' => $tanggal_selesai
        ]);

        $hasil = $stmtTotal->fetch();

        $total_pendapatan = $hasil['total_pendapatan'];

        $total_pelanggan = $hasil['total_pelanggan'];

    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Nomor 3 - Rekapitulasi Penjualan</title>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>

        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background: #f5f5f5;
        }

        .container {
            background: white;
            padding: 25px;
            border-radius: 10px;
        }

        input, button {
            padding: 10px;
            margin: 5px;
        }

        button {
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .chart-container {
            width: 100%;
            max-width: 850px;
            margin: 30px auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        th {
            background: #2563eb;
            color: white;
        }

        .card-container {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .card {
            background: #e8f0fe;
            padding: 20px;
            border-radius: 10px;
            flex: 1;
            min-width: 200px;
        }

        .card h3 {
            color: #555;
        }

        .card h2 {
            color: #2563eb;
        }

        .error {
            color: red;
        }

    </style>

</head>

<body>

<div class="container">

    <h1>Rekapitulasi Laporan Penjualan</h1>


    <form method="GET">

        <label>Tanggal Mulai:</label>

        <input type="date"
               name="tanggal_mulai"
               value="<?= htmlspecialchars($tanggal_mulai) ?>"
               required>

        <label>Tanggal Selesai:</label>

        <input type="date"
               name="tanggal_selesai"
               value="<?= htmlspecialchars($tanggal_selesai) ?>"
               required>

        <button type="submit">Tampilkan</button>

    </form>

    <hr>

    <?php if ($error): ?>

        <p class="error">
            <?= htmlspecialchars($error) ?>
        </p>

    <?php elseif (!empty($tanggal_mulai) && !empty($tanggal_selesai)): ?>

        <p>
            Periode laporan:
            <?= htmlspecialchars($tanggal_mulai) ?>
            sampai
            <?= htmlspecialchars($tanggal_selesai) ?>
        </p>

        <h2>Grafik Penjualan Harian</h2>

        <?php if (!empty($rekap)): ?>

            <div class="chart-container">

                <canvas id="grafikPenjualan"></canvas>

            </div>

            <script>

                const ctx = document.getElementById('grafikPenjualan');

                new Chart(ctx, {

                    type: 'bar',

                    data: {

                        labels: <?= json_encode($labels) ?>,

                        datasets: [{

                            label: 'Total Pendapatan (Rp)',

                            data: <?= json_encode($data_total) ?>,

                            backgroundColor: 'rgba(54, 162, 235, 0.6)',

                            borderColor: 'rgb(54, 162, 235)',

                            borderWidth: 1

                        }]

                    },

                    options: {

                        responsive: true,

                        scales: {

                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Total Pendapatan (Rp)'
                                }
                            },

                            x: {
                                title: {
                                    display: true,
                                    text: 'Tanggal Transaksi'
                                }
                            }

                        }

                    }

                });

            </script>

        <?php else: ?>

            <p>Tidak ada data penjualan pada periode tersebut.</p>

        <?php endif; ?>


        <h2>Rekapitulasi Penjualan Harian</h2>

        <table>

            <thead>

                <tr>
                    <th>No</th>
                    <th>Total Pendapatan</th>
                    <th>Tanggal Transaksi</th>
                </tr>

            </thead>

            <tbody>

                <?php if (!empty($rekap)): ?>

                    <?php $no = 1; ?>

                    <?php foreach ($rekap as $row): ?>

                        <tr>

                            <td><?= $no++ ?></td>

                            <td>
                                Rp <?= number_format(
                                    $row['total_harian'],
                                    0,
                                    ',',
                                    '.'
                                ) ?>
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


        <h2>Ringkasan Laporan</h2>

        <div class="card-container">

            <div class="card">

                <h3>Jumlah Pelanggan Unik</h3>

                <h2>
                    <?= $total_pelanggan ?> Orang
                </h2>

            </div>

            <div class="card">

                <h3>Total Pendapatan Keseluruhan</h3>

                <h2>
                    Rp <?= number_format(
                        $total_pendapatan,
                        0,
                        ',',
                        '.'
                    ) ?>
                </h2>

            </div>

        </div>

    <?php endif; ?>

</div>

</body>
</html>
