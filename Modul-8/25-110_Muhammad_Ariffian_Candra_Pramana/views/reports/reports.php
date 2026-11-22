<?php

$root = __DIR__ . "/../..";

require_once $root . "/route.php";
require_once $root . "/utils.php";
require_once $root . "/services/reports/reports.query.php";

$mulaiInput = $_GET["mulai"] ?? "";
$selesaiInput = $_GET["selesai"] ?? "";

$mulai = parseDate($mulaiInput);
$selesai = parseDate($selesaiInput);

$harian = [];
$summary = ["total" => 0, "pelanggan" => 0, "pendapatan" => 0];
$error = [];

if (($mulaiInput !== "" && !$mulai) || ($selesaiInput !== "" && !$selesai)) {
  $error[] = "Format tanggal tidak valid.";
} elseif ($mulai && $selesai) {
  if ($mulai > $selesai) {
    [$mulai, $selesai] = [$selesai, $mulai];
  }

  [$harian, $summary] = getPenjualanReport($DBH, $mulai, $selesai);
}

$labels = array_map(
  static fn(array $row): string => formatTanggal($row["tgl"]),
  $harian
);
$data = array_map("intval", array_column($harian, "total"));

$pageTitle = "Laporan Penjualan";
require_once __DIR__ . "/../partials/header.php";
?>

<main class="container py-5">
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
      <h1 class="h3 fw-bold text-secondary mb-1">Laporan Penjualan</h1>
      <p class="text-muted mb-0">
        <?php if ($mulai && $selesai): ?>
          Periode <?= sanitazion(formatTanggal($mulai)) ?> sampai <?= sanitazion(formatTanggal($selesai)) ?>
        <?php else: ?>
          Filter transaksi berdasarkan rentang tanggal.
        <?php endif ?>
      </p>
    </div>
  </div>

  <div class="card shadow-sm mb-4 report-filter">
    <div class="card-header bg-primary text-white py-3">
      <h2 class="h6 mb-0 fw-semibold">Filter Rentang Tanggal</h2>
    </div>
    <div class="card-body p-4">
      <form action="<?= appUrl("reports") ?>" method="get" class="row g-3 align-items-end">
        <div class="col-md-4">
          <label for="mulai" class="form-label fw-medium">Tanggal Mulai</label>
          <input type="date" id="mulai" name="mulai" class="form-control"
            value="<?= sanitazion($mulai ?? "") ?>" required>
        </div>
        <div class="col-md-4">
          <label for="selesai" class="form-label fw-medium">Tanggal Selesai</label>
          <input type="date" id="selesai" name="selesai" class="form-control"
            value="<?= sanitazion($selesai ?? "") ?>" required>
        </div>
        <div class="col-md-4 d-flex flex-wrap gap-2">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-search me-1"></i> Tampilkan
          </button>
          <?php if ($harian): ?>
            <button type="button" id="btnPrint" class="btn btn-outline-secondary">
              <i class="bi bi-printer me-1"></i> Cetak
            </button>
            <a href="<?= appUrl("reports", "export", ["mulai" => $mulai, "selesai" => $selesai]) ?>"
              class="btn btn-success">
              <i class="bi bi-file-earmark-excel me-1"></i> Excel
            </a>
          <?php endif ?>
        </div>
      </form>
    </div>
  </div>

  <?php foreach ($error as $pesan): ?>
    <div class="alert alert-danger" role="alert">
      <i class="bi bi-exclamation-triangle me-1"></i><?= sanitazion($pesan) ?>
    </div>
  <?php endforeach ?>

  <?php if ($error): ?>
    <!-- pesan error sudah ditampilkan di atas -->
  <?php elseif (!$mulai || !$selesai): ?>
    <div class="alert alert-info" role="alert">
      <i class="bi bi-info-circle me-1"></i>Pilih rentang tanggal untuk menampilkan laporan.
    </div>
  <?php elseif (!$harian): ?>
    <div class="alert alert-warning" role="alert">
      <i class="bi bi-inbox me-1"></i>Tidak ada transaksi pada rentang tanggal tersebut.
    </div>
  <?php else: ?>
    <div class="row g-3 mb-4">
      <div class="col-md-4">
        <div class="card shadow-sm h-100 report-card">
          <div class="card-body">
            <div class="text-muted small">Jumlah transaksi</div>
            <div class="fs-3 fw-bold text-primary"><?= (int) $summary["total"] ?></div>
            <div class="text-muted small">transaksi pada periode ini</div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card shadow-sm h-100 report-card">
          <div class="card-body">
            <div class="text-muted small">Pelanggan unik</div>
            <div class="fs-3 fw-bold text-success"><?= (int) $summary["pelanggan"] ?></div>
            <div class="text-muted small">orang pelanggan</div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card shadow-sm h-100 text-bg-dark report-card">
          <div class="card-body">
            <div class="small opacity-75">Total pendapatan</div>
            <div class="fs-4 fw-bold"><?= formatRupiah($summary["pendapatan"]) ?></div>
            <div class="small opacity-75">seluruh transaksi</div>
          </div>
        </div>
      </div>
    </div>

    <div class="card shadow-sm mb-4 report-card">
      <div class="card-header bg-white py-3">
        <h2 class="h6 mb-0 fw-semibold text-secondary">Grafik Penjualan per Hari</h2>
      </div>
      <div class="card-body">
        <div style="position: relative; height: 320px;">
          <canvas id="chartPenjualan"></canvas>
        </div>
      </div>
    </div>

    <div class="card shadow-sm report-card">
      <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
        <h2 class="h6 mb-0 fw-semibold text-secondary">Rekapitulasi Penjualan Harian</h2>
        <span class="text-muted small"><?= count($harian) ?> hari</span>
      </div>
      <div class="table-responsive">
        <table class="table table-striped table-hover align-middle mb-0">
          <thead class="table-primary">
            <tr>
              <th class="text-center" style="width: 70px;">No</th>
              <th>Tanggal Transaksi</th>
              <th class="text-end">Total Pendapatan</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($harian as $i => $row): ?>
              <tr>
                <td class="text-center"><?= $i + 1 ?></td>
                <td class="text-nowrap"><?= sanitazion(formatTanggal($row["tgl"])) ?></td>
                <td class="text-end text-nowrap fw-medium"><?= formatRupiah($row["total"]) ?></td>
              </tr>
            <?php endforeach ?>
            <tr class="table-warning fw-bold">
              <td colspan="2" class="text-end">Total Pendapatan Keseluruhan</td>
              <td class="text-end text-nowrap"><?= formatRupiah($summary["pendapatan"]) ?></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <script>
      new Chart(document.getElementById("chartPenjualan"), {
        type: "bar",
        data: {
          labels: <?= json_encode($labels) ?>,
          datasets: [{
            label: "Total pendapatan",
            data: <?= json_encode($data) ?>,
            backgroundColor: "rgba(13, 110, 253, 0.75)",
            borderRadius: 6
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                callback: (nilai) => "Rp " + Number(nilai).toLocaleString("id-ID")
              }
            }
          },
          plugins: {
            tooltip: {
              callbacks: {
                label: (konteks) => "Rp " + Number(konteks.parsed.y).toLocaleString("id-ID")
              }
            }
          }
        }
      });

      document.getElementById("btnPrint")?.addEventListener("click", () => window.print());
    </script>
  <?php endif ?>
</main>

<?php require_once __DIR__ . "/../partials/footer.php" ?>
