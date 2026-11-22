<?php
require_once __DIR__ . '/partials/header.auth.php';

$tanggal_mulai   = isset($_GET['tanggal_mulai']) ? $_GET['tanggal_mulai'] : '';
$tanggal_selesai = isset($_GET['tanggal_selesai']) ? $_GET['tanggal_selesai'] : '';

// Query 1: total pendapatan per hari
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

// Query 2: pelanggan unik + total keseluruhan
$data2 = $DBH->prepare("
    SELECT COUNT(DISTINCT pelanggan_id) AS jumlah_pelanggan,
           COALESCE(SUM(total), 0) AS total_keseluruhan
    FROM transaksi
    WHERE waktu_transaksi BETWEEN ? AND ?
");
$data2->execute([$tanggal_mulai, $tanggal_selesai]);
$data_kedua = $data2->fetch();
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    @media print {
        .navbar, .tombol { display: none !important; }
    }
</style>

<main class="container my-4">
    <h2>Laporan Penjualan</h2>

    <div class="tombol mb-3">
        <form method="GET" action="<?= url('/laporan'); ?>" class="mb-2">
            Dari: <input type="date" name="tanggal_mulai" value="<?= $tanggal_mulai; ?>" required>
            Sampai: <input type="date" name="tanggal_selesai" value="<?= $tanggal_selesai; ?>" required>
            <button type="submit" class="btn btn-primary btn-sm">Tampilkan</button>
        </form>
        <button onclick="window.print()" class="btn btn-secondary btn-sm">Cetak (Print)</button>
        <a href="<?= url('/laporan/export'); ?>?tanggal_mulai=<?= $tanggal_mulai; ?>&tanggal_selesai=<?= $tanggal_selesai; ?>"
           class="btn btn-success btn-sm">Ekspor Excel</a>
    </div>

    <div style="width: 600px; max-width: 100%;">
        <canvas id="chart"></canvas>
    </div>

    <table class="table table-bordered mt-3" style="max-width: 600px;">
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

    <div class="border p-3" style="max-width: 600px;">
        <p><strong>Jumlah Pelanggan Unik:</strong> <?= $data_kedua['jumlah_pelanggan']; ?> Orang</p>
        <p class="mb-0"><strong>Total Pendapatan Keseluruhan:</strong> Rp <?= number_format($data_kedua['total_keseluruhan']); ?></p>
    </div>
</main>

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
</script>

<?php require_once __DIR__ . '/partials/footer.auth.php'; ?>