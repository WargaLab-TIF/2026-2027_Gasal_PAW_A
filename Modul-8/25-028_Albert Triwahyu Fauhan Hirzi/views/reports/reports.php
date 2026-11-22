<?php
require_once dirname(__DIR__, 2) . '/auth.php';
wajib_login();
require_once dirname(__DIR__, 2) . '/conn.php';

$tanggal_mulai = $_GET['tanggal_mulai'] ?? '';
$tanggal_selesai = $_GET['tanggal_selesai'] ?? '';

$labels = [];
$data_total = [];
$rekap = [];
$total_pelanggan = 0;
$total_keseluruhan = 0;
$sudah_filter = false;
$pesan_error = '';

if (!empty($tanggal_mulai) && !empty($tanggal_selesai)) {
    if ($tanggal_mulai > $tanggal_selesai) {
        $pesan_error = 'Tanggal mulai tidak boleh lebih besar dari tanggal selesai.';
    } else {
        $sudah_filter = true;
        $mulai_safe = mysqli_real_escape_string($koneksi, $tanggal_mulai);
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
                $data_total[] = (int) $row['total_harian'];
                $rekap[]      = $row;
            }
        } else {
            $pesan_error = 'Gagal mengambil data grafik: ' . mysqli_error($koneksi);
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
        } else {
            $pesan_error = 'Gagal mengambil ringkasan laporan: ' . mysqli_error($koneksi);
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
    <link rel="stylesheet" href="assets/css/auth.css">
    <link rel="stylesheet" href="assets/css/table-theme.css">
    <link rel="stylesheet" href="assets/css/app.css">
    <link rel="stylesheet" href="assets/css/reports.css">
    <style>
      .chart-container canvas { display: block; width: 100%; height: 360px; }
    </style>
</head>
<body>
    <?php require dirname(__DIR__) . '/partials/navbar.php'; ?>
    <main class="page">
      <section class="panel report-panel">
        <header class="report-header">
          <h2>
            Rekap Laporan Penjualan
            <?php if ($sudah_filter): ?>
              <small class="subtitle-periode">
                (<?= htmlspecialchars($tanggal_mulai); ?> sampai <?= htmlspecialchars($tanggal_selesai); ?>)
              </small>
            <?php endif; ?>
          </h2>
        </header>

        <div class="filter-box no-print">
          <form method="GET" action="index.php">
            <input type="hidden" name="page" value="reports">
            <label for="tanggal_mulai">
              Dari Tanggal
              <input
                type="date"
                id="tanggal_mulai"
                name="tanggal_mulai"
                value="<?= htmlspecialchars($tanggal_mulai); ?>"
                required
              >
            </label>
            <label for="tanggal_selesai">
              Sampai Tanggal
              <input
                type="date"
                id="tanggal_selesai"
                name="tanggal_selesai"
                value="<?= htmlspecialchars($tanggal_selesai); ?>"
                required
              >
            </label>
            <button type="submit" class="btn btn-primary">Tampilkan</button>
          </form>
        </div>

        <div class="report-results">
          <?php if (!empty($pesan_error)): ?>
            <div class="alert-error no-print"><?= htmlspecialchars($pesan_error); ?></div>
          <?php elseif ($sudah_filter): ?>
            <?php if (!empty($rekap)): ?>

              <div class="action-buttons no-print">
                <button type="button" class="btn btn-warning" onclick="window.print()">
                  Cetak
                </button>
                <a
                  href="<?= e(page_url('reports-export', ['tanggal_mulai' => $tanggal_mulai, 'tanggal_selesai' => $tanggal_selesai])) ?>"
                  class="btn btn-success"
                >Excel</a>
              </div>

              <div class="chart-container">
                <canvas id="grafikPenjualan"></canvas>
              </div>

              <script>
                const canvas = document.getElementById('grafikPenjualan');
                const labels = <?= json_encode($labels); ?>;
                const values = <?= json_encode($data_total); ?>;

                if (window.Chart) {
                  new Chart(canvas.getContext('2d'), {
                  type: 'bar',
                  data: {
                    labels,
                    datasets: [{
                      label: 'Total Pendapatan',
                      data: values,
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
                } else {
                  const ctx = canvas.getContext('2d');
                  const drawFallback = () => {
                    const width = canvas.clientWidth;
                    const height = 360;
                    const ratio = window.devicePixelRatio || 1;
                    canvas.width = width * ratio;
                    canvas.height = height * ratio;
                    ctx.setTransform(ratio, 0, 0, ratio, 0, 0);
                    ctx.clearRect(0, 0, width, height);
                    const left = 64, right = 16, top = 20, bottom = 58;
                    const plotWidth = width - left - right;
                    const plotHeight = height - top - bottom;
                    const max = Math.max(...values, 1);
                    ctx.font = '12px Arial';
                    ctx.textAlign = 'right';
                    ctx.fillStyle = '#64748b';
                    for (let i = 0; i <= 4; i++) {
                      const y = top + plotHeight * i / 4;
                      ctx.strokeStyle = '#e0e0e0';
                      ctx.beginPath(); ctx.moveTo(left, y); ctx.lineTo(width - right, y); ctx.stroke();
                      ctx.fillText(Math.round(max * (4 - i) / 4).toLocaleString('id-ID'), left - 8, y + 4);
                    }
                    const slot = plotWidth / values.length;
                    values.forEach((value, i) => {
                      const barHeight = value / max * plotHeight;
                      const x = left + slot * i + slot * 0.2;
                      ctx.fillStyle = 'rgba(180, 180, 180, 0.7)';
                      ctx.fillRect(x, top + plotHeight - barHeight, slot * 0.6, barHeight);
                      ctx.fillStyle = '#64748b';
                      ctx.textAlign = 'center';
                      ctx.fillText(labels[i], x + slot * 0.3, height - 30);
                    });
                  };
                  drawFallback();
                  window.addEventListener('resize', drawFallback);
                }
              </script>

              <div class="report-table-wrap">
                <table>
                  <thead>
                    <tr>
                      <th class="col-no">No</th>
                      <th>Total Pendapatan</th>
                      <th>Tanggal Transaksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $no = 1; ?>
                    <?php foreach ($rekap as $row): ?>
                      <tr>
                        <td><?= $no++; ?></td>
                        <td>Rp. <?= number_format($row['total_harian'], 0, ',', '.'); ?></td>
                        <td><?= date('d M Y', strtotime($row['tgl'])); ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>

              <div class="report-table-wrap summary-wrap">
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
              </div>

            <?php else: ?>
              <p>
                Tidak ditemukan data transaksi pada rentang tanggal
                <b><?= htmlspecialchars($tanggal_mulai); ?></b> sampai
                <b><?= htmlspecialchars($tanggal_selesai); ?></b>.
              </p>
            <?php endif; ?>
          <?php else: ?>
            <p class="no-print">
              Silakan pilih rentang tanggal mulai dan tanggal selesai, lalu klik tombol
              <b>Tampilkan</b>.
            </p>
          <?php endif; ?>
        </div>
      </section>
    </main>
</body>
</html>
