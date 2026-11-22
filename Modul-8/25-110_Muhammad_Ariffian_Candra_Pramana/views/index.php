<?php

$pageTitle = "Penjualan";

$menu = [
  [
    "page" => "pelanggan",
    "title" => "Pelanggan",
    "description" => "Data pelanggan yang melakukan transaksi.",
    "icon" => "bi-people",
    "color" => "primary",
  ],
  [
    "page" => "supplier",
    "title" => "Supplier",
    "description" => "Data pemasok barang untuk penjualan.",
    "icon" => "bi-truck",
    "color" => "success",
  ],
  [
    "page" => "barang",
    "title" => "Barang",
    "description" => "Data barang, harga, stok, dan supplier.",
    "icon" => "bi-box-seam",
    "color" => "warning",
  ],
  [
    "page" => "transaksi",
    "title" => "Transaksi",
    "description" => "Data transaksi penjualan beserta detail barangnya.",
    "icon" => "bi-receipt",
    "color" => "danger",
  ],
  [
    "page" => "reports",
    "title" => "Laporan",
    "description" => "Rekap penjualan harian, grafik, dan ekspor Excel.",
    "icon" => "bi-graph-up",
    "color" => "dark",
  ],
  [
    "page" => "user",
    "title" => "User",
    "description" => "Data pengguna aplikasi dan level aksesnya.",
    "icon" => "bi-person-badge",
    "color" => "info",
  ],
];

require_once __DIR__ . "/partials/public.header.php";
?>

<header class="py-5">
  <div class="container py-lg-5 text-center">
    <h1 class="hero-title mb-3">Penjualan digital</h1>
    <p class="hero-subtitle text-secondary mb-4">
      Cari produk, lihat ketersediaan stok, dan kelola transaksi dalam satu tempat.
    </p>
    <div class="d-flex flex-wrap justify-content-center gap-2">
      <a href="<?= appUrl("masuk") ?>" class="btn btn-primary px-4">
        <i class="bi bi-box-arrow-in-right me-1"></i>Masuk
      </a>
      <a href="<?= appUrl("daftar") ?>" class="btn btn-outline-primary px-4">
        <i class="bi bi-person-plus me-1"></i>Daftar
      </a>
    </div>
  </div>
</header>

<main class="menu-section py-5" id="menu">
  <div class="container">
    <h2 class="visually-hidden">Menu aplikasi</h2>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
      <?php foreach ($menu as $item): ?>
        <div class="col">
          <div class="card menu-card h-100 border-0 shadow-sm">
            <div class="card-body p-4 d-flex flex-column">
              <div class="icon-box bg-<?= $item["color"] ?> mb-3">
                <i class="bi <?= $item["icon"] ?>"></i>
              </div>
              <h3 class="h4 fw-bold mb-2"><?= sanitazion($item["title"]) ?></h3>
              <p class="text-secondary mb-4"><?= sanitazion($item["description"]) ?></p>
              <a href="<?= appUrl($item["page"]) ?>"
                class="btn btn-outline-<?= $item["color"] ?> w-100 mt-auto">
                Kelola data <i class="bi bi-arrow-right ms-1"></i>
              </a>
            </div>
          </div>
        </div>
      <?php endforeach ?>
    </div>
  </div>
</main>

<?php require_once __DIR__ . "/partials/public.footer.php"; ?>
