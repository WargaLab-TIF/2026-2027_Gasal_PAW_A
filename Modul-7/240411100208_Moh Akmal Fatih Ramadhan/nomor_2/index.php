<?php
require_once 'config.php';

$tanggal_mulai = $_GET['tanggal_mulai'] ?? '';
$tanggal_selesai = $_GET['tanggal_selesai'] ?? '';

$labels = [];
$data_total = [];
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

        $data = $stmt->fetchAll();

        foreach ($data as $row) {

            $labels[] = $row['tanggal'];

            $data_total[] = (float) $row['total_harian'];

        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Nomor 2 - Grafik Penjualan</title>

    <!-- Memanggil pustaka Chart.js -->
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

        .error {
            color: red;
        }

    </style>

</head>

<body>

<div class="container">

    <h1>Grafik Laporan Penjualan</h1>

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

        <h2>Grafik Penjualan Harian</h2>

        <p>
            Periode:
            <?= htmlspecialchars($tanggal_mulai) ?>
            sampai
            <?= htmlspecialchars($tanggal_selesai) ?>
        </p>

        <?php if (!empty($labels)): ?>


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

    <?php endif; ?>

</div>

</body>
</html>
