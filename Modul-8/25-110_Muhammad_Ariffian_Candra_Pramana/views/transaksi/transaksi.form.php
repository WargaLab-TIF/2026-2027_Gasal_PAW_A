<?php

require_once __DIR__ . "/../../route.php";
$pelanggan = $DBH->query("SELECT id, nama FROM pelanggan ORDER BY nama")->fetchAll();

$pageTitle = "Tambah Transaksi";
require_once __DIR__ . "/../partials/header.php";
?>

<main class="container py-5">
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
      <h1 class="h3 fw-bold text-secondary mb-1">Tambah Transaksi</h1>
      <p class="text-muted mb-0">Lengkapi data transaksi, lalu tambahkan detail barangnya.</p>
    </div>
    <a href="<?= appUrl("transaksi") ?>" class="btn btn-outline-secondary">
      <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
  </div>

  <?php require __DIR__ . "/../partials/alert.php" ?>

  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white py-3">
      <h2 class="h6 mb-0 fw-semibold">Formulir Transaksi Baru</h2>
    </div>
    <div class="card-body p-4">
      <form action="<?= apiUrl("transaksi", "add") ?>" method="post">
        <div class="row mb-3">
          <div class="col-md-4">
            <label for="waktu_transaksi" class="form-label fw-medium">Tanggal Transaksi <span
                class="text-danger">*</span></label>
            <input type="date" name="waktu_transaksi" id="waktu_transaksi" class="form-control"
              value="<?= date("Y-m-d") ?>" required>
          </div>
          <div class="col-md-8 mt-3 mt-md-0">
            <label for="pelanggan_id" class="form-label fw-medium">Pelanggan</label>
            <select name="pelanggan_id" id="pelanggan_id" class="form-select">
              <option value="">-- Tanpa Pelanggan --</option>
              <?php foreach ($pelanggan as $item): ?>
                <option value="<?= sanitazion($item["id"]) ?>"><?= sanitazion($item["nama"]) ?></option>
              <?php endforeach ?>
            </select>
          </div>
        </div>

        <div class="mb-3">
          <label for="keterangan" class="form-label fw-medium">Keterangan</label>
          <textarea name="keterangan" id="keterangan" class="form-control" rows="3"
            placeholder="Catatan tambahan untuk transaksi ini (opsional)"></textarea>
        </div>

        <div class="alert alert-info d-flex align-items-start gap-2 mb-0" role="alert">
          <i class="bi bi-info-circle mt-1"></i>
          <div>Total transaksi dihitung otomatis dari detail barang. Setelah transaksi disimpan, Anda akan diarahkan ke
            halaman detail untuk menambahkan barang.</div>
        </div>

        <hr class="text-muted">
        <div class="d-flex justify-content-end gap-2 mt-3">
          <button type="reset" class="btn btn-light border">Reset</button>
          <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-save me-1"></i> Simpan Transaksi
          </button>
        </div>
      </form>
    </div>
  </div>
</main>

<?php require_once __DIR__ . "/../partials/footer.php" ?>
