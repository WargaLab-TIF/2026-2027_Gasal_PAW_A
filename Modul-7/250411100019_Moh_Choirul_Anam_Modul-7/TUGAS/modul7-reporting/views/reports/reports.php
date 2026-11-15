<?php
// Dipanggil lewat route /reports di index.php
// ($conn sudah tersedia dari conn.php)
require_once __DIR__ . "/../../services/reports/reports.query.php";

$mulai   = $_GET['tanggal_mulai']   ?? '';
$selesai = $_GET['tanggal_selesai'] ?? '';
$error   = '';
$tampil  = false;
$data    = null;

if ($mulai !== '' || $selesai !== '') {
  if (!reports_tanggal_valid($mulai) || !reports_tanggal_valid($selesai)) {
    $error = 'Format tanggal tidak valid.';
  } elseif ($mulai > $selesai) {
    $error = 'Tanggal mulai tidak boleh lebih besar dari tanggal selesai.';
  } else {
    $tampil = true;
    $data   = reports_ambil_laporan($conn, $mulai, $selesai);
  }
}

$labels = $tampil ? array_column($data['rekap'], 'tgl') : [];
$nilai  = $tampil ? array_map('intval', array_column($data['rekap'], 'total_harian')) : [];
$qs     = http_build_query(['tanggal_mulai' => $mulai, 'tanggal_selesai' => $selesai]);
$exportUrl = $_SERVER['SCRIPT_NAME'] . '/api/reports/export?' . $qs;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Laporan Penjualan</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body { font-family: Arial, sans-serif; margin: 24px; color: #222; }
    h1 small { font-size: 14px; color: #666; font-weight: normal; }
    form { margin-bottom: 12px; }
    input, button { padding: 6px 10px; }
    .btn { display: inline-block; padding: 6px 12px; background: #e8590c; color: #fff;
           border: 0; border-radius: 3px; text-decoration: none; cursor: pointer; font-size: 14px; }
    .btn-biru { background: #0d6efd; }
    .error { color: #b00020; margin: 8px 0; }
    .chart-box { max-width: 800px; height: 320px; margin-top: 16px; }
    table { border-collapse: collapse; width: 100%; max-width: 800px; margin-top: 16px; }
    th, td { border: 1px solid #ccc; padding: 6px 10px; text-align: left; }
    th { background: #d9edf7; }
    .card { display: inline-block; margin-top: 16px; border: 1px solid #ccc; }
    .card .judul { background: #d9edf7; padding: 6px 16px; display: flex; gap: 60px; font-weight: bold; }
    .card .isi { padding: 8px 16px; display: flex; gap: 60px; justify-content: space-between; }

    @media print {
      .no-print { display: none !important; }
      body { margin: 0; }
    }
  </style>
</head>
<body>

<h1>Rekap Laporan Penjualan
  <?php if ($tampil): ?><small><?= htmlspecialchars($mulai) ?> sampai <?= htmlspecialchars($selesai) ?></small><?php endif; ?>
</h1>

<div class="no-print">
  <!-- action dikosongkan: form dikirim ke URL saat ini (index.php/reports) -->
  <form method="GET" action="<?= htmlspecialchars($_SERVER['SCRIPT_NAME']) ?>/reports">
    Dari: <input type="date" name="tanggal_mulai" value="<?= htmlspecialchars($mulai) ?>" required>
    Sampai: <input type="date" name="tanggal_selesai" value="<?= htmlspecialchars($selesai) ?>" required>
    <button type="submit" class="btn btn-biru">Tampilkan</button>
  </form>

  <?php if ($tampil && !empty($data['rekap'])): ?>
    <button type="button" class="btn" onclick="window.print()">Cetak</button>
    <a class="btn" href="<?= htmlspecialchars($exportUrl) ?>">Excel</a>
  <?php endif; ?>
</div>

<?php if ($error): ?>
  <p class="error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if ($tampil && empty($data['rekap'])): ?>
  <p>Tidak ada transaksi pada rentang tanggal tersebut.</p>
<?php elseif ($tampil): ?>

  <div class="chart-box"><canvas id="grafik"></canvas></div>

  <table>
    <tr><th>No</th><th>Total Pendapatan</th><th>Tanggal Transaksi</th></tr>
    <?php $no = 1; foreach ($data['rekap'] as $row): ?>
      <tr>
        <td><?= $no++ ?></td>
        <td>Rp. <?= number_format($row['total_harian'], 0, ',', '.') ?></td>
        <td><?= date('d M Y', strtotime($row['tgl'])) ?></td>
      </tr>
    <?php endforeach; ?>
  </table>

  <div class="card">
    <div class="judul"><span>Jumlah Pelanggan</span><span>Jumlah Pendapatan</span></div>
    <div class="isi">
      <span><?= $data['total_pelanggan'] ?> Orang</span>
      <span>Rp. <?= number_format($data['total_pendapatan'], 0, ',', '.') ?></span>
    </div>
  </div>

  <script>
    new Chart(document.getElementById('grafik'), {
      type: 'bar',
      data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
          label: 'Total Penjualan',
          data: <?= json_encode($nilai) ?>,
          backgroundColor: 'rgba(13, 110, 253, 0.6)'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: false, // agar grafik langsung tampil di print preview
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

<?php endif; ?>

</body>
</html>
