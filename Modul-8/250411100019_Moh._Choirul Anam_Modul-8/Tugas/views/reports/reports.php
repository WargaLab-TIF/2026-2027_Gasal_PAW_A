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
$exportUrl = url('/api/reports/export') . '?' . $qs;

// Untuk header.auth.php (navbar + <head>)
$title     = 'Laporan Penjualan';
$extraHead = '<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>';
?>
<?php require_once __DIR__ . "/../partials/header.auth.php" ?>

<h1>Rekap Laporan Penjualan
  <?php if ($tampil): ?><small><?= htmlspecialchars($mulai) ?> sampai <?= htmlspecialchars($selesai) ?></small><?php endif; ?>
</h1>

<div class="no-print">
  <form method="GET" action="<?= e(url('/reports')) ?>">
    Dari: <input type="date" name="tanggal_mulai" value="<?= htmlspecialchars($mulai) ?>" required>
    Sampai: <input type="date" name="tanggal_selesai" value="<?= htmlspecialchars($selesai) ?>" required>
    <button type="submit" class="btn btn-biru">Tampilkan</button>
  </form>

  <?php if ($tampil && !empty($data['rekap'])): ?>
    <p>
      <button type="button" class="btn btn-oranye" onclick="window.print()">Cetak</button>
      <a class="btn btn-oranye" href="<?= htmlspecialchars($exportUrl) ?>">Excel</a>
    </p>
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

<?php require_once __DIR__ . "/../partials/footer.auth.php" ?>
