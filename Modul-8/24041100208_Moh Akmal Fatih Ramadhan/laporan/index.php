
<?php

require_once __DIR__ . '/../auth.php';

wajibLogin();

$mulai = $_GET['mulai'] ?? date('Y-m-01');

$selesai = $_GET['selesai'] ?? date('Y-m-d');

$error = '';

$rows = [];

$totalPendapatan = 0;

$totalPelanggan = 0;


// Validasi tanggal
$start = DateTime::createFromFormat('!Y-m-d', $mulai);

$end = DateTime::createFromFormat('!Y-m-d', $selesai);

if (
    !$start ||
    !$end ||
    $start->format('Y-m-d') !== $mulai ||
    $end->format('Y-m-d') !== $selesai ||
    $mulai > $selesai
) {

    $error = 'Rentang tanggal tidak valid.';

} else {

    // Mengambil total penjualan setiap hari
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


    // Menghitung total pendapatan
    foreach ($rows as $r) {

        $totalPendapatan += (float)$r['total_harian'];

    }


    // Menghitung pelanggan unik yang memiliki transaksi
    $stmt = $DBH->prepare(

        'SELECT COUNT(DISTINCT pelanggan_id)

         FROM transaksi

         WHERE waktu_transaksi >= ?
         AND waktu_transaksi < DATE_ADD(?, INTERVAL 1 DAY)

         AND pelanggan_id IS NOT NULL'

    );

    $stmt->execute([$mulai, $selesai]);

    $totalPelanggan = (int)$stmt->fetchColumn();

}


// Menyiapkan data grafik
$labels = array_column($rows, 'tanggal');

$values = array_map(
    'floatval',
    array_column($rows, 'total_harian')
);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laporan Penjualan</title>

    <link rel="stylesheet" href="../assets/style.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>

<?php require __DIR__ . '/../partials/nav.php'; ?>

<main class="container">

    <div class="panel">

        <h1>Laporan Penjualan</h1>


        <!-- FILTER TANGGAL -->
        <form method="get" class="toolbar">

            <label>

                Dari

                <input
                    type="date"
                    name="mulai"
                    value="<?= e($mulai) ?>"
                    required
                >

            </label>


            <label>

                Sampai

                <input
                    type="date"
                    name="selesai"
                    value="<?= e($selesai) ?>"
                    required
                >

            </label>


            <button class="btn primary" type="submit">

                Tampilkan

            </button>


            <?php if (!$error): ?>

                <a
                    class="btn secondary"
                    href="export_excel.php?mulai=<?= urlencode($mulai) ?>&selesai=<?= urlencode($selesai) ?>"
                >

                    Ekspor Excel

                </a>

            <?php endif; ?>


            <button
                class="btn secondary"
                type="button"
                onclick="window.print()"
            >

                Cetak

            </button>

        </form>


        <?php if ($error): ?>

            <div class="alert error">

                <?= e($error) ?>

            </div>

        <?php else: ?>


            <!-- RINGKASAN PENJUALAN -->
            <div class="cards">

                <div class="card">

                    <span>Total Pendapatan</span>

                    <strong>

                        Rp <?= number_format(
                            $totalPendapatan,
                            0,
                            ',',
                            '.'
                        ) ?>

                    </strong>

                </div>


                <div class="card">

                    <span>Pelanggan Terdata</span>

                    <strong>

                        <?= $totalPelanggan ?>

                    </strong>

                </div>

            </div>


            <!-- GRAFIK PENJUALAN -->
            <h2>Grafik Penjualan Harian</h2>


            <?php if ($rows): ?>

                <canvas id="salesChart"></canvas>

                <script>

                    new Chart(

                        document.getElementById('salesChart'),

                        {

                            type: 'bar',

                            data: {

                                labels: <?= json_encode($labels) ?>,

                                datasets: [{

                                    label: 'Total Penjualan (Rp)',

                                    data: <?= json_encode($values) ?>

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

                        }

                    );

                </script>

            <?php else: ?>

                <p>
                    Tidak ada transaksi pada periode ini.
                </p>

            <?php endif; ?>


            <!-- REKAPITULASI -->
            <h2>Rekapitulasi Penjualan</h2>

            <div class="table-wrap">

                <table>

                    <tr>

                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Total Pendapatan</th>

                    </tr>


                    <?php foreach ($rows as $i => $r): ?>

                        <tr>

                            <td><?= $i + 1 ?></td>

                            <td><?= e($r['tanggal']) ?></td>

                            <td>

                                Rp <?= number_format(
                                    $r['total_harian'],
                                    0,
                                    ',',
                                    '.'
                                ) ?>

                            </td>

                        </tr>

                    <?php endforeach; ?>


                    <?php if (!$rows): ?>

                        <tr>

                            <td colspan="3">
                                Tidak ada data.
                            </td>

                        </tr>

                    <?php endif; ?>

                </table>

            </div>

        <?php endif; ?>

    </div>

</main>

</body>
</html>
