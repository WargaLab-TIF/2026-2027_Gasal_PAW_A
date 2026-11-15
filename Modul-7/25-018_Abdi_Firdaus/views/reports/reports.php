<?php
// ==========================================================
// FILE TAMPILAN LAPORAN PENJUALAN: reports.php
// ==========================================================

// Memuat fungsi query dan validasi tanggal
require_once __DIR__ . "/../../services/reports/reports.query.php";

// Mengambil parameter tanggal_mulai dan tanggal_selesai dari input form (GET)
$tanggal_mulai = parseDate($_GET["tanggal_mulai"] ?? $_GET["mulai"] ?? "");
$tanggal_selesai = parseDate($_GET["tanggal_selesai"] ?? $_GET["selesai"] ?? "");

// Nilai awal sebelum data diambil dari database
$rows = [];
$summary = ["pelanggan" => 0, "pendapatan" => 0];

// Jika kedua rentang tanggal valid, jalankan query laporan penjualan
if ($tanggal_mulai && $tanggal_selesai) {
  // Tukar posisi tanggal jika tanggal mulai lebih besar dari tanggal selesai
  if ($tanggal_mulai > $tanggal_selesai) {
    [$tanggal_mulai, $tanggal_selesai] = [$tanggal_selesai, $tanggal_mulai];
  }
  [$rows, $summary] = getSalesReport($conn, $tanggal_mulai, $tanggal_selesai);
}

// Menyiapkan array label tanggal (Sumbu X) dan array nominal pendapatan (Sumbu Y) untuk Chart.js
$labels = array_column($rows, "tgl");
$data = array_map("intval", array_column($rows, "total_pendapatan"));

?>

<?php require_once __DIR__ . "/../partials/header.auth.php" ?>

<main class="container my-9">
  <!-- Judul Halaman Laporan beserta keterangan periode tanggal yang dipilih -->
  <h1 class="mb-7">
    Rekap Laporan Penjualan
    <?php if ($tanggal_mulai && $tanggal_selesai): ?>
      <small class="fs-lg"><?= sanitazion($tanggal_mulai) ?> sampai <?= sanitazion($tanggal_selesai) ?></small>
    <?php endif ?>
  </h1>

  <!-- 1. Form Filter Rentang Tanggal & Tombol Aksi (Disembunyikan saat cetak via d-print-none / no-print) -->
  <form class="d-print-none no-print mb-7" action="<?= routeUrl("/reports") ?>" method="GET">
    <div class="row">
      <div class="md:col-4">
        <div class="form-field mb-5">
          <label class="form-label" for="tanggal_mulai">Dari tanggal</label>
          <input class="form-control" type="date" id="tanggal_mulai" name="tanggal_mulai" value="<?= sanitazion($tanggal_mulai ?? "") ?>" required>
        </div>
      </div>
      <div class="md:col-4">
        <div class="form-field mb-5">
          <label class="form-label" for="tanggal_selesai">Sampai tanggal</label>
          <input class="form-control" type="date" id="tanggal_selesai" name="tanggal_selesai" value="<?= sanitazion($tanggal_selesai ?? "") ?>" required>
        </div>
      </div>
    </div>
    <button type="submit" class="btn btn-solid theme-primary">Tampilkan</button>
    <?php if ($rows): ?>
      <!-- Tombol Cetak (window.print) dan Ekspor Excel (.xls) -->
      <button type="button" class="btn btn-outline theme-primary" id="btnPrint">Cetak</button>
      <a class="btn btn-outline theme-success" href="<?= routeUrl("/api/reports/export") ?>?tanggal_mulai=<?= sanitazion($tanggal_mulai) ?>&tanggal_selesai=<?= sanitazion($tanggal_selesai) ?>">Excel</a>
    <?php endif ?>
  </form>

  <!-- Validasi Tampilan: tampilkan pesan info jika belum difilter atau pesan peringatan jika data kosong -->
  <?php if (!$tanggal_mulai || !$tanggal_selesai): ?>
    <div class="alert theme-info" role="alert">Pilih rentang tanggal transaksi untuk menampilkan laporan.</div>
  <?php elseif (!$rows): ?>
    <div class="alert theme-warning" role="alert">Tidak ada transaksi pada rentang tanggal tersebut.</div>
  <?php else: ?>

    <!-- 2. Visualisasi Grafik Batang (Bar Chart) menggunakan Chart.js -->
    <h2 class="fs-xl mb-5">Grafik penjualan per hari</h2>
    <div class="mb-7"><canvas id="chartSales"></canvas></div>

    <!-- 3. Tabel Rekapitulasi Data Penjualan Harian -->
    <h2 class="fs-xl mb-5">Rekapitulasi</h2>
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
              <td>Rp. <?= number_format((int) $row["total_pendapatan"], 0, ",", ".") ?></td>
              <td><?= date("d M Y", strtotime($row["tgl"])) ?></td>
            </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>

    <!-- 4. Card Ringkasan Informasi Total Kumulatif (Jumlah Pelanggan Unik & Total Pendapatan) -->
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
              <td>Rp. <?= number_format((int) $summary["pendapatan"], 0, ",", ".") ?></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Pustaka Chart.js & Konfigurasi Render Grafik Batang + Event Tombol Cetak -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <script>
      // Merender grafik batang pada elemen <canvas id="chartSales">
      new Chart(document.getElementById("chartSales"), {
        type: "bar",
        data: {
          labels: <?= json_encode($labels) ?>,
          datasets: [{ label: "Total Pendapatan", data: <?= json_encode($data) ?> }]
        },
        options: { scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
      });

      // Memicu dialog cetak browser saat tombol Cetak diklik
      document.getElementById("btnPrint").addEventListener("click", () => window.print());
    </script>
  <?php endif ?>
</main>

<?php require_once __DIR__ . "/../partials/footer.auth.php" ?>
