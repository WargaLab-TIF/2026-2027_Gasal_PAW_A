<!DOCTYPE html>
<html>
<head>
  <title>Laporan Penjualan dan Grafik</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    table { border-collapse: collapse; width: 100%; margin-top: 15px; }
    th, td { border: 1px solid #000; padding: 6px; text-align: left; }
    th { background: #d9edf7; }
    .card { display: flex; margin-top: 15px; border: 1px solid #000; }
    .kotak { flex: 1; }
    .kotak + .kotak { border-left: 1px solid #000; }
    .judul { background: #d9edf7; font-weight: bold; padding: 8px;
             border-bottom: 1px solid #000; }
    .nilai { padding: 8px; font-size: 18px; }
    @media print { .no-print { display: none !important; } }
  </style>
</head>
<body>
<?php
// Koneksi ke database MySQL
$koneksi = mysqli_connect("localhost", "root", "", "penjualan");
if (mysqli_connect_error()) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

$mulai   = $_GET['tanggal_mulai']   ?? '';
$selesai = $_GET['tanggal_selesai'] ?? '';
$tm = htmlspecialchars($mulai);    // aman untuk ditampilkan di HTML
$ts = htmlspecialchars($selesai);

$labels = []; $data_total = []; $rekap = [];
$total_pendapatan = 0; $total_pelanggan = 0;

if (!empty($mulai) && !empty($selesai)) {
    $m = mysqli_real_escape_string($koneksi, $mulai);
    $s = mysqli_real_escape_string($koneksi, $selesai);

    // Query agregasi total pendapatan per hari
    $q = "SELECT DATE(waktu_transaksi) AS tgl, SUM(total) AS total_harian
          FROM transaksi
          WHERE waktu_transaksi BETWEEN '$m' AND '$s'
          GROUP BY DATE(waktu_transaksi) ORDER BY tgl ASC";
    $res = mysqli_query($koneksi, $q);
    while ($r = mysqli_fetch_assoc($res)) {
        $labels[]     = $r['tgl'];
        $data_total[] = $r['total_harian'];
        $rekap[]      = $r;
        $total_pendapatan += $r['total_harian'];
    }

    // Query jumlah pelanggan unik
    $qp = "SELECT COUNT(DISTINCT pelanggan_id) AS jml FROM transaksi
           WHERE waktu_transaksi BETWEEN '$m' AND '$s'";
    $rp = mysqli_fetch_assoc(mysqli_query($koneksi, $qp));
    $total_pelanggan = $rp['jml'] ?? 0;
}

$total_rp = number_format($total_pendapatan, 0, ',', '.');
$link_excel = "export_excel.php?tanggal_mulai=" . urlencode($mulai)
            . "&tanggal_selesai=" . urlencode($selesai);
?>
<div class="no-print">
  <h1>Filter Laporan Penjualan</h1>
  <form method="GET">
    Dari:
    <input type="date" name="tanggal_mulai" value="<?= $tm ?>" required>
    Sampai:
    <input type="date" name="tanggal_selesai" value="<?= $ts ?>" required>
    <button type="submit">Tampilkan</button>
  </form>
  <?php if (!empty($labels)): ?>
  <p>
    <button onclick="window.print()">Cetak</button>
    <a href="<?= htmlspecialchars($link_excel) ?>"><button>Excel</button></a>
  </p>
  <?php endif; ?>
</div>

<?php if (!empty($labels)): ?>
<h2>Rekap Laporan Penjualan <small><?= $tm ?> sampai <?= $ts ?></small></h2>

<h3>Grafik Penjualan</h3>
<div style="max-width: 700px;"><canvas id="myChart"></canvas></div>
<script>
  const ctx = document.getElementById('myChart').getContext('2d');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: <?= json_encode($labels); ?>,
      datasets: [{
        label: 'Total Penjualan',
        data: <?= json_encode($data_total); ?>,
        backgroundColor: 'rgba(54, 162, 235, 0.6)'
      }]
    },
    options: {
      scales: {
        x: { title: { display: true, text: 'Tanggal Transaksi' } },
        y: {
          beginAtZero: true,
          title: { display: true, text: 'Total Pendapatan (Rp)' }
        }
      }
    }
  });
</script>

<h3>Rekapitulasi</h3>
<table>
  <tr><th>No</th><th>Total Pendapatan</th><th>Tanggal Transaksi</th></tr>
  <?php $no = 1; foreach ($rekap as $row): ?>
  <tr>
    <td><?= $no++; ?></td>
    <td>Rp. <?= number_format($row['total_harian'], 0, ',', '.'); ?></td>
    <td><?= date('d M Y', strtotime($row['tgl'])); ?></td>
  </tr>
  <?php endforeach; ?>
</table>

<div class="card">
  <div class="kotak">
    <div class="judul">Jumlah Pelanggan Unik</div>
    <div class="nilai"><?= $total_pelanggan; ?> Orang</div>
  </div>
  <div class="kotak">
    <div class="judul">Total Pendapatan Keseluruhan</div>
    <div class="nilai">Rp. <?= $total_rp; ?></div>
  </div>
</div>
<?php elseif (!empty($mulai) && !empty($selesai)): ?>
<p>Tidak ada transaksi pada rentang tanggal tersebut.</p>
<?php endif; ?>
</body>
</html>
