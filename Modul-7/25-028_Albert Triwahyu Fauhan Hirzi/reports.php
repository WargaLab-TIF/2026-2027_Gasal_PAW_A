<?php
include 'conn.php';

$tanggal_mulai   = $_GET['tanggal_mulai'] ?? '';
$tanggal_selesai = $_GET['tanggal_selesai'] ?? '';

$labels            = [];
$data_total        = [];
$rekap             = [];
$total_pelanggan   = 0;
$total_keseluruhan = 0;
$sudah_filter      = false;
$pesan_error       = '';

if (!empty($tanggal_mulai) && !empty($tanggal_selesai)) {
    if ($tanggal_mulai > $tanggal_selesai) {
        $pesan_error = "Tanggal mulai tidak boleh lebih besar dari tanggal selesai.";
    } else {
        $sudah_filter = true;
        $mulai_safe   = mysqli_real_escape_string($koneksi, $tanggal_mulai);
        $selesai_safe = mysqli_real_escape_string($koneksi, $tanggal_selesai);

        $query_harian = "SELECT DATE(waktu_transaksi) AS tgl, SUM(total) AS total_harian
                         FROM transaksi
                         WHERE waktu_transaksi BETWEEN '$mulai_safe' AND '$selesai_safe'
                         GROUP BY DATE(waktu_transaksi)
                         ORDER BY tgl ASC";
        $res_harian = mysqli_query($koneksi, $query_harian);

        if ($res_harian) {
            while ($row = mysqli_fetch_assoc($res_harian)) {
                $labels[]     = $row['tgl'];
                $data_total[] = (int)$row['total_harian'];
                $rekap[]      = $row;
            }
        }

        $query_total = "SELECT COUNT(DISTINCT pelanggan_id) AS jml_pelanggan,
                               SUM(total) AS total_pendapatan
                        FROM transaksi
                        WHERE waktu_transaksi BETWEEN '$mulai_safe' AND '$selesai_safe'";
        $res_query_total = mysqli_query($koneksi, $query_total);

        if ($res_query_total) {
            $res_total         = mysqli_fetch_assoc($res_query_total);
            $total_pelanggan   = $res_total['jml_pelanggan'] ?? 0;
            $total_keseluruhan = $res_total['total_pendapatan'] ?? 0;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Laporan Penjualan</title>
    <link rel="stylesheet" href="reports.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

    <h2>
        Rekap Laporan Penjualan
        <?php if ($sudah_filter): ?>
            <small class="subtitle-periode">
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

    <?php if (!empty($pesan_error)): ?>
        <div class="alert-error no-print"><?= htmlspecialchars($pesan_error); ?></div>
    <?php elseif ($sudah_filter): ?>
        <?php if (!empty($rekap)): ?>

            <div class="action-buttons no-print">
                <button type="button" class="btn btn-warning" onclick="window.print()">Cetak</button>
                <a href="reports.export.php?tanggal_mulai=<?= urlencode($tanggal_mulai); ?>&tanggal_selesai=<?= urlencode($tanggal_selesai); ?>" class="btn btn-success">Excel</a>
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
                        <th class="col-no">No</th>
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