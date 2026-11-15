<?php
require_once __DIR__ . '/utils.php';
require_once __DIR__ . '/reports.query.php';

[$mulai, $selesai, $error] = ambil_filter();
$sudahFilter = ($mulai !== '' && $selesai !== '' && $error === '');

$rekap = [];
$ringkasan = ['jumlah_pelanggan' => 0, 'total_pendapatan' => 0];

if ($sudahFilter) {
    $rekap     = getRekapHarian($mulai, $selesai);
    $ringkasan = getRingkasan($mulai, $selesai);
}

$labels = array_column($rekap, 'tgl');
$data   = array_column($rekap, 'total_harian');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        form.filter { max-width: none; display: flex; flex-wrap: wrap; gap: 12px; align-items: flex-end; }
        form.filter .item { display: flex; flex-direction: column; font-weight: bold; }
        form.filter input[type="date"] { padding: 6px; border: 1px solid #d1d5db; border-radius: 4px; }
        .aksi { margin: 15px 0; display: flex; gap: 8px; }
        .chart-box { background: #fff; border: 1px solid #d1d5db; padding: 15px; max-width: 900px; }
        .cards { display: flex; gap: 15px; margin-bottom: 20px; }
        .card { background: #fff; border: 1px solid #d1d5db; border-left: 5px solid #2563eb;
                padding: 12px 20px; min-width: 200px; }
        .card small { color: #6b7280; }
        .card strong { display: block; font-size: 20px; margin-top: 4px; }
        .error { background: #fee2e2; color: #991b1b; padding: 10px; border-radius: 4px; margin-top: 10px; }
        .periode { font-size: 14px; font-weight: normal; color: #6b7280; }

        @media print {
            .no-print { display: none !important; }
            body { background: #fff; padding: 0; }
        }
    </style>
</head>
<body>

<div class="top">
    <h2>Rekap Laporan Penjualan
        <?php if ($sudahFilter): ?>
            <span class="periode"><?= e($mulai) ?> sampai <?= e($selesai) ?></span>
        <?php endif; ?>
    </h2>
</div>

<div class="no-print">
    <form method="GET" class="filter">
        <div class="item">
            <label for="tanggal_mulai">Tanggal Mulai</label>
            <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="<?= e($mulai) ?>" required>
        </div>
        <div class="item">
            <label for="tanggal_selesai">Tanggal Selesai</label>
            <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="<?= e($selesai) ?>" required>
        </div>
        <button type="submit">Tampilkan</button>
    </form>

    <?php if ($error): ?>
        <div class="error"><?= e($error) ?></div>
    <?php endif; ?>

    <?php if ($sudahFilter && !empty($rekap)): ?>
        <div class="aksi">
            <button type="button" class="btn oren" onclick="window.print()">Cetak</button>
            <a class="btn oren"
               href="export_excel.php?tanggal_mulai=<?= urlencode($mulai) ?>&tanggal_selesai=<?= urlencode($selesai) ?>">Excel</a>
        </div>
    <?php endif; ?>
</div>

<?php if ($sudahFilter && empty($rekap)): ?>
    <p>Tidak ada transaksi pada rentang tanggal tersebut.</p>

<?php elseif ($sudahFilter): ?>

    <h2>Grafik Penjualan</h2>
    <div class="chart-box">
        <canvas id="grafikPenjualan" height="110"></canvas>
    </div>
    <script>
        new Chart(document.getElementById('grafikPenjualan'), {
            type: 'bar',
            data: {
                labels: <?= json_encode($labels) ?>,
                datasets: [{
                    label: 'Total Pendapatan',
                    data: <?= json_encode($data) ?>,
                    backgroundColor: '#2563eb'
                }]
            },
            options: {
                animation: false,
                responsive: true,
                scales: {
                    x: { title: { display: true, text: 'Tanggal Transaksi' } },
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Total Pendapatan (Rp)' },
                        ticks: { callback: v => v.toLocaleString('id-ID') }
                    }
                }
            }
        });
    </script>

    <h2>Rekapitulasi</h2>
    <table>
        <tr>
            <th>No</th>
            <th>Total Pendapatan</th>
            <th>Tanggal Transaksi</th>
        </tr>
        <?php $no = 1; foreach ($rekap as $row): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= rupiah($row['total_harian']) ?></td>
                <td><?= tanggal_indo($row['tgl']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <div class="cards">
        <div class="card">
            <small>Jumlah Pelanggan Unik</small>
            <strong><?= $ringkasan['jumlah_pelanggan'] ?> Orang</strong>
        </div>
        <div class="card">
            <small>Total Pendapatan Keseluruhan</small>
            <strong><?= rupiah($ringkasan['total_pendapatan']) ?></strong>
        </div>
    </div>

<?php endif; ?>

</body>
</html>
