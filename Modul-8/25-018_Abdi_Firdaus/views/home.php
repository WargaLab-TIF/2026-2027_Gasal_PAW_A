<?php
// halaman home

$pageTitle = 'Home';

$totalTransaksi = (int) $conn->query("SELECT COUNT(*) FROM transaksi")->fetchColumn();
$totalPendapatan = (int) $conn->query("SELECT COALESCE(SUM(total), 0) FROM transaksi")->fetchColumn();
$totalBarang = (int) $conn->query("SELECT COUNT(*) FROM barang")->fetchColumn();

require_once __DIR__ . "/partials/header.auth.php";
?>

<main class="container my-8">
  <div class="card-panel p-6 mb-7">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
      <div>
        <h1 class="fs-3xl mb-2">Selamat Datang, <?= sanitazion(currentUserName()) ?></h1>
        <p class="mb-0" style="color: #475569;">
          Anda masuk sebagai <strong><?= sanitazion(levelLabel(currentUserLevel())) ?></strong>
          (Level <?= sanitazion(currentUserLevel()) ?> &mdash; username: <code><?= sanitazion($_SESSION['username'] ?? '') ?></code>).
        </p>
      </div>
      <div>
        <span class="badge theme-<?= isOwner() ? 'primary' : 'info' ?>">
          Hak Akses: <?= isOwner() ? 'Penuh (Data Master, Transaksi, Laporan)' : 'Kasir (Transaksi & Laporan)' ?>
        </span>
      </div>
    </div>
  </div>

  <!-- ringkasan data -->
  <div class="row mb-7">
    <div class="md:col-4 mb-5">
      <div class="card-panel p-5 h-100">
        <div style="color: #64748b; font-size: 0.9rem;">Total Transaksi</div>
        <div class="fs-3xl fw-bold mt-1"><?= sanitazion($totalTransaksi) ?></div>
        <a class="btn btn-text theme-primary px-0 mt-3" href="<?= routeUrl("/transaksi") ?>">Kelola Transaksi &rarr;</a>
      </div>
    </div>

    <div class="md:col-4 mb-5">
      <div class="card-panel p-5 h-100">
        <div style="color: #64748b; font-size: 0.9rem;">Total Akumulasi Pendapatan</div>
        <div class="fs-3xl fw-bold mt-1"><?= formatRupiah($totalPendapatan) ?></div>
        <a class="btn btn-text theme-primary px-0 mt-3" href="<?= routeUrl("/reports") ?>">Lihat Laporan &rarr;</a>
      </div>
    </div>

    <div class="md:col-4 mb-5">
      <div class="card-panel p-5 h-100">
        <div style="color: #64748b; font-size: 0.9rem;">Total Data Barang</div>
        <div class="fs-3xl fw-bold mt-1"><?= sanitazion($totalBarang) ?> Item</div>
        <?php if (isOwner()): ?>
          <a class="btn btn-text theme-primary px-0 mt-3" href="<?= routeUrl("/barang") ?>">Kelola Data Barang &rarr;</a>
        <?php else: ?>
          <div class="mt-3" style="font-size: 0.85rem; color: #64748b;">Data Master dikelola oleh Owner</div>
        <?php endif ?>
      </div>
    </div>
  </div>
</main>

<?php require_once __DIR__ . "/partials/footer.auth.php" ?>
