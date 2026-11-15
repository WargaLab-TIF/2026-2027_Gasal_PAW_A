<?php

$root = __DIR__ . "/../..";

// require_once murah dipanggil walau file sudah dimuat oleh laporan.php
require_once $root . "/utils.php";
require_once $root . "/koneksi.php";
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

  if (isset($DBH)) {
    [$harian, $summary] = getPenjualanReport($DBH, $mulai, $selesai);
  } else {
    $error[] = "Koneksi database gagal. Periksa pengaturan di koneksi.php.";
  }
}

$labels = array_column($harian, "tgl");
$data   = array_map("intval", array_column($harian, "total"));
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Laporan Penjualan</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
</head>

<style>
  @media print {

    .filter-laporan,
    .aksi-cetak {
      display: none !important;
    }

    body {
      background: #ffffff;
    }

    .kartu-cetak {
      box-shadow: none !important;
      border-color: #000000 !important;
    }

    @page {
      margin: 12mm;
    }
  }
</style>

<body class="min-h-screen bg-slate-50 font-sans text-slate-800 antialiased">
  <main class="mx-auto w-full max-w-5xl px-4 py-10">

    <header class="mb-6">
      <h1 class="text-2xl font-semibold tracking-tight text-slate-900">
        Laporan Penjualan
        <?php if ($mulai && $selesai): ?>
          <span class="block text-sm font-normal text-slate-600">
            <?= sanitazion($mulai) ?> sampai <?= sanitazion($selesai) ?>
          </span>
        <?php endif ?>
      </h1>
      <p class="mt-1 text-sm text-slate-600">Filter transaksi berdasarkan rentang tanggal.</p>
    </header>

    <form method="get" action="" class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm filter-laporan">
      <fieldset>
        <legend class="px-1 text-sm font-semibold text-slate-900">Filter Rentang Tanggal</legend>
        <div class="mt-4 flex flex-wrap items-end gap-4">
          <div>
            <label for="mulai" class="block text-sm font-medium text-slate-700">Tanggal mulai</label>
            <input type="date" id="mulai" name="mulai" value="<?= sanitazion($mulai ?? "") ?>" required
              class="mt-1 rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-400">
          </div>
          <div>
            <label for="selesai" class="block text-sm font-medium text-slate-700">Tanggal selesai</label>
            <input type="date" id="selesai" name="selesai" value="<?= sanitazion($selesai ?? "") ?>" required
              class="mt-1 rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-400">
          </div>
          <div>
            <button type="submit"
              class="rounded-md bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">
              Tampilkan
            </button>
          </div>
          <?php if ($harian): ?>
            <div class="aksi-cetak flex flex-wrap gap-2">
              <button type="button" id="btnPrint"
                class="rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2">
                Cetak
              </button>
              <a href="services/reports/reports.export.php?mulai=<?= urlencode($mulai) ?>&selesai=<?= urlencode($selesai) ?>"
                class="inline-flex items-center rounded-md border border-emerald-600 bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                Excel
              </a>
            </div>
          <?php endif ?>
        </div>
      </fieldset>
    </form>

    <?php foreach ($error as $pesan): ?>
      <div class="mt-4 rounded-md border-l-4 border-rose-500 bg-rose-50 px-4 py-3 text-sm text-rose-800" role="alert">
        <?= sanitazion($pesan) ?>
      </div>
    <?php endforeach ?>

    <?php if ($error): ?>
      <!-- pesan error sudah ditampilkan di atas -->
    <?php elseif (!$mulai || !$selesai): ?>
      <div class="mt-6 rounded-lg border border-dashed border-slate-300 bg-white px-4 py-6 text-sm text-slate-600" role="alert">
        Pilih rentang tanggal untuk menampilkan laporan.
      </div>
    <?php elseif (!$harian): ?>
      <div class="mt-6 rounded-lg border border-dashed border-slate-300 bg-white px-4 py-6 text-sm text-slate-600" role="alert">
        Tidak ada transaksi pada rentang tanggal tersebut.
      </div>
    <?php else: ?>
      <section class="mb-6">
        <h2 class="mb-2 text-base font-semibold text-slate-900">Grafik penjualan per hari</h2>
        <div class="relative h-80 w-full rounded-lg border border-slate-200 bg-white p-4 shadow-sm kartu-cetak">
          <canvas id="chartPenjualan"></canvas>
        </div>
      </section>

      <section class="mt-6">
        <div class="mb-2 flex flex-wrap items-baseline justify-between gap-2">
          <h2 class="text-base font-semibold text-slate-900">Rekapitulasi penjualan harian</h2>
          <p class="text-sm text-slate-600">
            <?= count($harian) ?> hari, <?= sanitazion($summary["total"]) ?> transaksi
          </p>
        </div>

        <div class="overflow-x-auto rounded-lg border border-slate-200 bg-white shadow-sm kartu-cetak">
          <table class="min-w-full divide-y divide-slate-200 text-sm">
            <caption class="sr-only">
              Rekapitulasi penjualan <?= sanitazion($mulai) ?> sampai <?= sanitazion($selesai) ?>
            </caption>
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
              <tr>
                <th scope="col" class="px-4 py-3 text-right">No</th>
                <th scope="col" class="px-4 py-3 text-right">Total Pendapatan</th>
                <th scope="col" class="px-4 py-3">Tanggal Transaksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <?php foreach ($harian as $i => $row): ?>
                <tr class="hover:bg-slate-50">
                  <td class="px-4 py-3 text-right tabular-nums text-slate-500"><?= $i + 1 ?></td>
                  <td class="whitespace-nowrap px-4 py-3 text-right tabular-nums font-medium text-slate-900">
                    Rp <?= number_format((int) $row["total"], 0, ",", ".") ?>
                  </td>
                  <td class="whitespace-nowrap px-4 py-3 text-slate-700"><?= sanitazion($row["tgl"]) ?></td>
                </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
        <div class="mt-4 grid gap-4 sm:grid-cols-2">
          <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm kartu-cetak">
            <p class="text-sm font-medium text-slate-600">Jumlah pelanggan unik</p>
            <p class="mt-1 text-2xl font-semibold tabular-nums text-slate-900">
              <?= sanitazion($summary["pelanggan"]) ?> orang
            </p>
          </div>
          <div class="rounded-lg border border-slate-900 bg-slate-900 p-4 shadow-sm kartu-cetak">
            <p class="text-sm font-medium text-slate-300">Total pendapatan keseluruhan</p>
            <p class="mt-1 text-2xl font-semibold tabular-nums text-white">
              Rp <?= number_format((int) $summary["pendapatan"], 0, ",", ".") ?>
            </p>
          </div>
        </div>
      </section>

    <?php endif ?>

  </main>
  <?php if ($harian): ?>
    <script>
      new Chart(document.getElementById("chartPenjualan"), {
        type: "bar",
        data: {
          labels: <?= json_encode($labels) ?>,
          datasets: [{
            label: "Total pendapatan",
            data: <?= json_encode($data) ?>,
            backgroundColor: "rgba(15, 23, 42, 0.85)"
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
    </script>
  <?php endif ?>
  <script>
    document.getElementById("btnPrint")?.addEventListener("click", () => window.print());
  </script>
</body>

</html>