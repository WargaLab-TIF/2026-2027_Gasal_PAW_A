<?php
require_once __DIR__ . "/koneksi.php";
require_once __DIR__ . "/reports.query.php";

$mulai = parseDate($_GET["mulai"] ?? "");
$selesai = parseDate($_GET["selesai"] ?? "");
$rows = [];
$summary = ["total" => 0, "pelanggan" => 0, "pendapatan" => 0];

if ($mulai && $selesai) {
  if ($mulai > $selesai) {
    [$mulai, $selesai] = [$selesai, $mulai];
  }
  [$rows, $summary] = getLoanReport($conn, $mulai, $selesai);
}

$labels = array_column($rows, "tgl");
$data = array_map("intval", array_column($rows, "pendapatan_harian"));
?>

<main class="container my-5">
  <h1 style="font-size: 2rem; font-weight: bold; margin-bottom: 1.5rem; display: flex; align-items: baseline; gap: 15px;">
    Rekap Laporan Penjualan
    <?php if ($mulai && $selesai): ?>
      <span style="font-size: 1.1rem; font-weight: normal; color: #555;"><?= sanitazion($mulai) ?> sampai <?= sanitazion($selesai) ?></span>
    <?php endif ?>
  </h1>

  <form class="d-print-none" action="" method="GET" style="margin-bottom: 2.5rem;">
    <div style="display: flex; gap: 20px; align-items: flex-end; flex-wrap: wrap; margin-bottom: 15px;">
      <div>
        <label class="form-label" for="mulai" style="display: block; margin-bottom: 5px; font-weight: 500;">Dari tanggal</label>
        <input class="form-control" type="date" id="mulai" name="mulai" value="<?= sanitazion($mulai ?? "") ?>" style="padding: 6px 12px; border: 1px solid #ccc; border-radius: 4px;" required>
      </div>
      <div>
        <label class="form-label" for="selesai" style="display: block; margin-bottom: 5px; font-weight: 500;">Sampai tanggal</label>
        <input class="form-control" type="date" id="selesai" name="selesai" value="<?= sanitazion($selesai ?? "") ?>" style="padding: 6px 12px; border: 1px solid #ccc; border-radius: 4px;" required>
      </div>
    </div>
    <div style="display: flex; align-items: center; gap: 10px;">
      <button type="submit" class="btn btn-solid theme-primary" style="padding: 6px 16px; background-color: #0d6efd; color: #fff; border: none; border-radius: 4px; cursor: pointer;">Tampilkan</button>
      
      <?php if (!empty($rows)): ?>
        <button type="button" class="btn btn-outline theme-primary" id="btnPrint" style="padding: 6px 16px; background: transparent; border: 1px solid #0d6efd; color: #0d6efd; border-radius: 4px; cursor: pointer;">Cetak</button>
        <a class="btn btn-outline theme-success" href="reports.export.php?mulai=<?= sanitazion($mulai) ?>&selesai=<?= sanitazion($selesai) ?>" style="padding: 6px 16px; background: transparent; border: 1px solid #198754; color: #198754; border-radius: 4px; text-decoration: none; display: inline-block;">Excel</a>
      <?php endif ?>
    </div>
  </form>

  <?php if (!$mulai || !$selesai): ?>
    <div class="alert theme-info" role="alert">Pilih rentang tanggal laporan penjualan.</div>
  <?php elseif (!$rows): ?>
    <div class="alert theme-warning" role="alert">Tidak ada laporan penjualan pada rentang tanggal tersebut.</div>
  <?php else: ?>

    <h2 class="fs-xl mb-5">Grafik Pendapatan per Hari</h2>
    <div class="mb-7"><canvas id="chartLoans"></canvas></div>

    <h2 class="fs-xl mb-5">Rekapitulasi Penjualan Harian</h2>
    <div class="table-responsive mb-7">
      <table class="table" border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse: collapse;">
        <thead>
          <tr style="background-color: #f2f2f2;">
            <th>No</th>
            <th>Tanggal Transaksi</th>
            <th>Total Pendapatan Harian</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; foreach ($rows as $row): ?>
            <tr>
              <td><?= $no++; ?></td>
              <td><?= date("d M Y", strtotime($row["tgl"])) ?></td>
              <td>Rp <?= number_format($row['pendapatan_harian'], 0, ',', '.'); ?></td>
            </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>

    <h2 class="fs-xl mb-5">Ringkasan Kumulatif Periode</h2>
    <div class="row" style="display: flex; gap: 20px; margin-bottom: 30px;">
      
      <div class="card" style="flex: 1; padding: 20px; border: 1px solid #ddd; border-radius: 5px; background: #f8f9fa;">
        <h4 style="margin: 0 0 10px 0; color: #555; font-size: 14px;">Jumlah Pelanggan Unik</h4>
        <p style="margin: 0; font-size: 24px; font-weight: bold; color: #007bff;">
          <?= sanitazion($summary["pelanggan"]) ?> Orang
        </p>
      </div>

      <div class="card" style="flex: 1; padding: 20px; border: 1px solid #ddd; border-radius: 5px; background: #f8f9fa;">
        <h4 style="margin: 0 0 10px 0; color: #555; font-size: 14px;">Total Pendapatan Keseluruhan</h4>
        <p style="margin: 0; font-size: 24px; font-weight: bold; color: #28a745;">
          Rp <?= number_format($summary["pendapatan"], 0, ',', '.') ?>
        </p>
      </div>
    </div>

    <div class="table-responsive mb-7">
      <table class="table" border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse: collapse;">
        <thead>
          <tr style="background-color: #f2f2f2;">
            <th>Jumlah pelanggan</th>
            <th>Jumlah transaksi</th>
            <th>Total Pendapatan</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><?= sanitazion($summary["pelanggan"]) ?> Orang</td>
            <td><?= sanitazion($summary["total"]) ?> Transaksi</td>
            <td>Rp <?= number_format($summary["pendapatan"], 0, ',', '.') ?></td>
          </tr>
        </tbody>
      </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <script>
      new Chart(document.getElementById("chartLoans"), {
        type: "bar",
        data: {
          labels: <?= json_encode($labels) ?>,
          datasets: [{ label: "Pendapatan", data: <?= json_encode($data) ?> }]
        },
        options: { scales: { y: { beginAtZero: true } } }
      });
      document.getElementById("btnPrint").addEventListener("click", () => window.print());
    </script>
  <?php endif ?>
</main>