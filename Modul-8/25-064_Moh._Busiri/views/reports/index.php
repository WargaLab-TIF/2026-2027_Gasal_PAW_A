<?php
$page_title = "Laporan Penjualan - Sistem Penjualan";
require_once __DIR__ . "/../partials/header.php";
require_once __DIR__ . "/../../services/reports/reports.query.php";

$mulai = parseDate($_GET["mulai"] ?? "");
$selesai = parseDate($_GET["selesai"] ?? "");
$rows = [];
$summary = ["total_transaksi" => 0, "jumlah_pelanggan" => 0, "total_pendapatan" => 0];

if ($mulai && $selesai) {
  if ($mulai > $selesai) {
    [$mulai, $selesai] = [$selesai, $mulai];
  }
  [$rows, $summary] = getSalesReport($conn, $mulai, $selesai);
}

// Menyiapkan data label tanggal dan nominal total harian untuk Chart.js
$labels = array_column($rows, "tgl");
$data_total = array_map("intval", array_column($rows, "total_harian"));
?>

<header>
  <h2>Rekap Laporan Penjualan</h2>
  <?php if ($mulai && $selesai): ?>
    <p><strong>Periode:</strong> <?= sanitazion($mulai) ?> sampai <?= sanitazion($selesai) ?></p>
  <?php else: ?>
    <p style="color: #666; font-size: 13px;">Saring dan lihat grafik serta rekapitulasi data penjualan berdasarkan tanggal transaksi</p>
  <?php endif; ?>
</header>

<!-- FORM FILTER RENTANG TANGGAL & TOMBOL AKSI (disembunyikan saat cetak) -->
<div class="no-print form-container">
  <h3 style="margin-top: 0;">Filter Laporan Penjualan</h3>
  <form method="GET" action="index.php" style="display: inline-block;">
    <input type="hidden" name="page" value="reports">

    <label for="mulai" style="font-weight: bold; margin-right: 6px;">Dari:</label>
    <input type="date" id="mulai" name="mulai" value="<?= sanitazion($mulai ?? '') ?>" required style="padding: 6px 10px; border: 1px solid #ccc; border-radius: 4px; margin-right: 15px;">

    <label for="selesai" style="font-weight: bold; margin-right: 6px;">Sampai:</label>
    <input type="date" id="selesai" name="selesai" value="<?= sanitazion($selesai ?? '') ?>" required style="padding: 6px 10px; border: 1px solid #ccc; border-radius: 4px; margin-right: 15px;">

    <button type="submit" class="btn btn-submit">Tampilkan</button>
  </form>

  <?php if (!empty($rows)): ?>
    <button type="button" class="btn btn-print" id="btnPrint">Cetak</button>
    <a href="export_excel.php?mulai=<?= urlencode($mulai) ?>&selesai=<?= urlencode($selesai) ?>" class="btn btn-excel">Excel</a>
  <?php endif; ?>
</div>

<!-- KONDISI STATUS DATA -->
<?php if (!$mulai || !$selesai): ?>
  <div class="alert alert-info">
    Silakan tentukan rentang tanggal penjualan di atas lalu klik tombol <strong>Tampilkan</strong> untuk memuat grafik dan rekapitulasi data.
  </div>
<?php elseif (empty($rows)): ?>
  <div class="alert alert-warning">
    Tidak ada data transaksi penjualan pada rentang tanggal <strong><?= sanitazion($mulai) ?></strong> sampai <strong><?= sanitazion($selesai) ?></strong>.
  </div>
<?php else: ?>

  <!-- VISUALISASI GRAFIK BATANG (CHART.JS) -->
  <h3>Grafik Penjualan Harian</h3>
  <div class="chart-container">
    <canvas id="chartPenjualan"></canvas>
  </div>

  <!-- TABEL REKAPITULASI PENJUALAN HARIAN -->
  <h3>Rekapitulasi</h3>
  <table class="data-table">
    <thead>
      <tr>
        <th style="width: 60px; text-align: center;">No</th>
        <th>Total</th>
        <th style="width: 200px;">Tanggal</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($rows as $i => $row): ?>
        <tr>
          <td style="text-align: center;"><?= $i + 1 ?></td>
          <td><b>Rp. <?= number_format($row["total_harian"], 0, ',', '.') ?></b></td>
          <td><?= date("d M Y", strtotime($row["tgl"])) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <!-- KARTU RINGKASAN TOTAL KUMULATIF -->
  <h3>Ringkasan Total</h3>
  <table class="summary-card-table">
    <thead>
      <tr>
        <th>Jumlah Pelanggan</th>
        <th>Jumlah Pendapatan</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><?= $summary["jumlah_pelanggan"] ?? 0 ?> Orang</td>
        <td>Rp. <?= number_format($summary["total_pendapatan"] ?? 0, 0, ',', '.') ?></td>
      </tr>
    </tbody>
  </table>

  <!-- JAVASCRIPT: INISIALISASI CHART.JS & FUNGSI CETAK -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const labels = <?= json_encode($labels) ?>;
      const dataTotal = <?= json_encode($data_total) ?>;

      // Inisialisasi Chart.js tipe Bar
      const ctx = document.getElementById('chartPenjualan').getContext('2d');
      new Chart(ctx, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [{
            label: 'Total Penjualan (Rp)',
            data: dataTotal,
            backgroundColor: 'rgba(54, 162, 235, 0.7)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1,
            borderRadius: 4
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: true,
          plugins: {
            legend: {
              display: true,
              position: 'top'
            },
            tooltip: {
              callbacks: {
                label: function (context) {
                  const val = context.raw || 0;
                  return ' Total: Rp ' + val.toLocaleString('id-ID');
                }
              }
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                callback: function (value) {
                  return 'Rp ' + value.toLocaleString('id-ID');
                }
              }
            }
          }
        }
      });

      // Handler Tombol Cetak (Print Dialog)
      const btnPrint = document.getElementById('btnPrint');
      if (btnPrint) {
        btnPrint.addEventListener('click', function () {
          window.print();
        });
      }
    });
  </script>

<?php endif; ?>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>
