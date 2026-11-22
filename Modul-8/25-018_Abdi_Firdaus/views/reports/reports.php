<?php
// halaman laporan penjualan

require_once __DIR__ . "/../../services/reports/reports.query.php";

$pageTitle = 'Laporan Penjualan';
$tanggal_mulai = parseDate($_GET["tanggal_mulai"] ?? $_GET["mulai"] ?? "");
$tanggal_selesai = parseDate($_GET["tanggal_selesai"] ?? $_GET["selesai"] ?? "");

$rows = [];
$summary = ["pelanggan" => 0, "pendapatan" => 0];
$dateRange = getTransactionDateRange($conn);

if ($tanggal_mulai && $tanggal_selesai) {
  if ($tanggal_mulai > $tanggal_selesai) {
    [$tanggal_mulai, $tanggal_selesai] = [$tanggal_selesai, $tanggal_mulai];
  }
  [$rows, $summary] = getSalesReport($conn, $tanggal_mulai, $tanggal_selesai);
}

$labels = array_column($rows, "tgl");
$data = array_map("intval", array_column($rows, "total_pendapatan"));

require_once __DIR__ . "/../partials/header.auth.php";
?>

<main class="container my-8">
  <div class="card-panel p-6">
    <!-- judul laporan -->
    <h1 class="mb-6">
      Rekap Laporan Penjualan
      <?php if ($tanggal_mulai && $tanggal_selesai): ?>
        <small class="fs-lg" style="color: #64748b;">
          <?= sanitazion($tanggal_mulai) ?> sampai <?= sanitazion($tanggal_selesai) ?>
        </small>
      <?php endif ?>
    </h1>

    <!-- filter tanggal dan tombol cetak/excel -->
    <form class="d-print-none no-print mb-7" action="<?= routeUrl("/reports") ?>" method="GET">
      <div class="row">
        <div class="md:col-4">
          <div class="form-field mb-5">
            <label class="form-label" for="tanggal_mulai">Dari Tanggal</label>
            <input class="form-control" type="date" id="tanggal_mulai" name="tanggal_mulai"
              value="<?= sanitazion($tanggal_mulai ?? "") ?>" required>
          </div>
        </div>
        <div class="md:col-4">
          <div class="form-field mb-5">
            <label class="form-label" for="tanggal_selesai">Sampai Tanggal</label>
            <input class="form-control" type="date" id="tanggal_selesai" name="tanggal_selesai"
              value="<?= sanitazion($tanggal_selesai ?? "") ?>" required>
          </div>
        </div>
      </div>

      <div class="d-flex gap-2 flex-wrap">
        <button type="submit" class="btn btn-solid theme-primary">Tampilkan</button>
        <?php if ($rows): ?>
          <button type="button" class="btn btn-outline theme-primary" id="btnPrint">Cetak</button>
          <a class="btn btn-outline theme-success"
            href="<?= routeUrl("/api/reports/export") ?>?tanggal_mulai=<?= sanitazion($tanggal_mulai) ?>&tanggal_selesai=<?= sanitazion($tanggal_selesai) ?>">Excel</a>
        <?php endif ?>
      </div>
    </form>

    <?php if (!$tanggal_mulai || !$tanggal_selesai): ?>
      <div class="alert theme-info mb-0" role="alert">
        Pilih rentang tanggal transaksi untuk menampilkan laporan penjualan.
        <?php if (!empty($dateRange['min_tgl']) && !empty($dateRange['max_tgl'])): ?>
          (Data transaksi tersedia dari <strong><?= sanitazion($dateRange['min_tgl']) ?></strong> sampai
          <strong><?= sanitazion($dateRange['max_tgl']) ?></strong>).
        <?php endif ?>
      </div>
    <?php elseif (!$rows): ?>
      <div class="alert theme-warning mb-0" role="alert">
        Tidak ada transaksi pada rentang tanggal <?= sanitazion($tanggal_mulai) ?> sampai <?= sanitazion($tanggal_selesai) ?>.
      </div>
    <?php else: ?>
      <!-- grafik batang -->
      <h2 class="fs-xl mb-4">Grafik Penjualan per Hari</h2>
      <div class="mb-7"><canvas id="chartSales"></canvas></div>

      <!-- tabel rekap harian -->
      <h2 class="fs-xl mb-4">Rekapitulasi</h2>
      <div class="table-responsive mb-7">
        <table class="table">
          <thead>
            <tr>
              <th>No</th>
              <th>Total Pendapatan</th>
              <th>Tanggal Transaksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $i => $row): ?>
              <tr>
                <td><?= $i + 1 ?></td>
                <td><?= formatRupiah($row["total_pendapatan"]) ?></td>
                <td><?= date("d M Y", strtotime($row["tgl"])) ?></td>
              </tr>
            <?php endforeach ?>
          </tbody>
        </table>
      </div>

      <!-- ringkasan total -->
      <div class="card p-4">
        <div class="table-responsive">
          <table class="table mb-0">
            <thead>
              <tr>
                <th>Jumlah Pelanggan</th>
                <th>Jumlah Pendapatan</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><?= sanitazion($summary["pelanggan"]) ?> Orang</td>
                <td><?= formatRupiah($summary["pendapatan"]) ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
      <script>
        new Chart(document.getElementById("chartSales"), {
          type: "bar",
          data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [{ label: "Total Pendapatan", data: <?= json_encode($data) ?> }]
          },
          options: { scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
        });

        document.getElementById("btnPrint").addEventListener("click", () => window.print());
      </script>
    <?php endif ?>
  </div>
</main>

<?php require_once __DIR__ . "/../partials/footer.auth.php" ?>
