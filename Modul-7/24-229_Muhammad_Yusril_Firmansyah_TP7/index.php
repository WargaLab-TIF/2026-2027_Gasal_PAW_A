<?php
require_once 'config.php';
require_once 'functions.php';

$tanggalMulai = $_GET['tanggal_mulai'] ?? date('Y-m-01');
$tanggalSelesai = $_GET['tanggal_selesai'] ?? date('Y-m-d');

if (!validDate($tanggalMulai) || !validDate($tanggalSelesai)) {
    exit('Format tanggal tidak valid. Gunakan format YYYY-MM-DD.');
}
if ($tanggalMulai > $tanggalSelesai) {
    exit('Tanggal mulai tidak boleh lebih besar dari tanggal selesai.');
}

$sql = "SELECT DATE(waktu_transaksi) AS tanggal_transaksi,
               SUM(total) AS total_pendapatan
        FROM transaksi
        WHERE waktu_transaksi BETWEEN :mulai AND :selesai
        GROUP BY DATE(waktu_transaksi)
        ORDER BY DATE(waktu_transaksi) ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute(['mulai' => $tanggalMulai, 'selesai' => $tanggalSelesai]);
$laporan = $stmt->fetchAll();

$sqlRingkasan = "SELECT COUNT(DISTINCT pelanggan_id) AS jumlah_pelanggan,
                        COALESCE(SUM(total), 0) AS total_pendapatan
                 FROM transaksi
                 WHERE waktu_transaksi BETWEEN :mulai AND :selesai";
$stmtRingkasan = $pdo->prepare($sqlRingkasan);
$stmtRingkasan->execute(['mulai' => $tanggalMulai, 'selesai' => $tanggalSelesai]);
$ringkasan = $stmtRingkasan->fetch();

$labels = array_column($laporan, 'tanggal_transaksi');
$values = array_map('intval', array_column($laporan, 'total_pendapatan'));

function e($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporting Penjualan</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; padding: 25px; font-family: Arial, sans-serif; background: #f3f5f9; color: #222; }
        .container { max-width: 1100px; margin: auto; }
        h1 { margin-bottom: 6px; }
        .subjudul { color: #666; margin-top: 0; }
        .panel, .card { background: white; padding: 20px; border-radius: 10px; margin: 18px 0; box-shadow: 0 2px 8px #0000000d; }
        .filter { display: flex; flex-wrap: wrap; align-items: end; gap: 14px; }
        label { display: block; margin-bottom: 7px; font-weight: bold; }
        input { padding: 10px; border: 1px solid #ccc; border-radius: 6px; }
        button, .btn { border: 0; border-radius: 6px; padding: 11px 16px; cursor: pointer; text-decoration: none; color: white; display: inline-block; font-size: 14px; }
        .primary { background: #2563eb; } .green { background: #16834a; } .gray { background: #555; }
        .cards { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px; }
        .card small { color: #666; } .angka { font-size: 25px; font-weight: bold; margin-top: 9px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 11px; text-align: left; }
        th { background: #eef2ff; }
        .chart-wrap { height: 350px; }
        .aksi { display: flex; gap: 10px; flex-wrap: wrap; }
        .kosong { text-align: center; color: #777; }
        @media (max-width: 650px) {
            body { padding: 12px; } .cards { grid-template-columns: 1fr; }
            .filter { align-items: stretch; flex-direction: column; }
            input { width: 100%; }
        }
        /* Saat dicetak, bagian kontrol tidak ikut tercetak. */
        @media print {
            body { background: white; padding: 0; }
            .no-print, .filter-panel, .aksi { display: none !important; }
            .panel, .card { box-shadow: none; border: 1px solid #ddd; break-inside: avoid; }
            .container { max-width: 100%; }
            h1 { text-align: center; }
            .chart-wrap { height: 300px; }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Laporan Penjualan</h1>
    <p class="subjudul">Rekapitulasi pendapatan berdasarkan tanggal transaksi</p>

    <section class="panel filter-panel no-print">
        <form method="GET" class="filter">
            <div>
                <label for="tanggal_mulai">Tanggal Mulai</label>
                <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="<?= e($tanggalMulai) ?>" required>
            </div>
            <div>
                <label for="tanggal_selesai">Tanggal Selesai</label>
                <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="<?= e($tanggalSelesai) ?>" required>
            </div>
            <button type="submit" class="primary">Tampilkan</button>
        </form>
    </section>

    <section class="cards">
        <div class="card">
            <small>Jumlah Pelanggan Unik</small>
            <div class="angka"><?= number_format((int)$ringkasan['jumlah_pelanggan'], 0, ',', '.') ?> pelanggan</div>
        </div>
        <div class="card">
            <small>Total Pendapatan Keseluruhan</small>
            <div class="angka"><?= rupiah($ringkasan['total_pendapatan']) ?></div>
        </div>
    </section>

    <section class="panel">
        <h2>Grafik Total Pendapatan Harian</h2>
        <div class="chart-wrap"><canvas id="grafikPenjualan"></canvas></div>
    </section>

    <section class="panel">
        <h2>Tabel Rekapitulasi Penjualan</h2>
        <div style="overflow-x:auto">
            <table>
                <thead>
                    <tr><th>No</th><th>Total Pendapatan</th><th>Tanggal Transaksi</th></tr>
                </thead>
                <tbody>
                <?php if (count($laporan) > 0): ?>
                    <?php foreach ($laporan as $i => $row): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= rupiah($row['total_pendapatan']) ?></td>
                        <td><?= e($row['tanggal_transaksi']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="3" class="kosong">Tidak ada transaksi pada rentang tanggal tersebut.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <div class="aksi no-print">
        <button class="gray" onclick="window.print()">Cetak Laporan</button>
        <a class="btn green" href="export_excel.php?tanggal_mulai=<?= e($tanggalMulai) ?>&tanggal_selesai=<?= e($tanggalSelesai) ?>">Ekspor Excel</a>
    </div>
</div>

<script>
const labelTanggal = <?= json_encode($labels) ?>;
const dataPendapatan = <?= json_encode($values) ?>;

new Chart(document.getElementById('grafikPenjualan'), {
    type: 'bar',
    data: {
        labels: labelTanggal,
        datasets: [{
            label: 'Total Pendapatan (Rp)',
            data: dataPendapatan,
            backgroundColor: 'rgba(37, 99, 235, 0.65)',
            borderColor: 'rgb(37, 99, 235)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: { beginAtZero: true, title: { display: true, text: 'Pendapatan (Rp)' } },
            x: { title: { display: true, text: 'Tanggal Transaksi' } }
        }
    }
});
</script>
</body>
</html>
