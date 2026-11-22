<?php

require_once __DIR__ . "/../../route.php";
$suppliers = $DBH->query("SELECT id, nama FROM supplier ORDER BY nama")->fetchAll();

$pageTitle = "Tambah Barang";
require_once __DIR__ . "/../partials/header.php";
?>

<main class="container py-5">
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
      <h1 class="h3 fw-bold text-secondary mb-1">Tambah Barang</h1>
      <p class="text-muted mb-0">Lengkapi formulir data barang baru.</p>
    </div>
    <a href="<?= appUrl("barang") ?>" class="btn btn-outline-secondary">
      <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
  </div>

  <?php require __DIR__ . "/../partials/alert.php" ?>

  <?php if (!$suppliers): ?>
    <div class="alert alert-warning" role="alert">
      Belum ada data supplier. Silakan
      <a href="<?= appUrl("supplier", "add") ?>" class="alert-link">tambah supplier</a>
      terlebih dahulu sebelum menambahkan barang.
    </div>
  <?php endif ?>

  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white py-3">
      <h2 class="h6 mb-0 fw-semibold">Formulir Barang Baru</h2>
    </div>
    <div class="card-body p-4">
      <form action="<?= apiUrl("barang", "add") ?>" method="post">
        <div class="row mb-3">
          <div class="col-md-4">
            <label for="kode_barang" class="form-label fw-medium">Kode Barang <span
                class="text-danger">*</span></label>
            <input type="text" name="kode_barang" id="kode_barang" class="form-control" maxlength="10"
              placeholder="Contoh: BRG011" required>
          </div>
          <div class="col-md-8 mt-3 mt-md-0">
            <label for="nama_barang" class="form-label fw-medium">Nama Barang <span
                class="text-danger">*</span></label>
            <input type="text" name="nama_barang" id="nama_barang" class="form-control" maxlength="100"
              placeholder="Masukkan nama barang" required>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-4">
            <label for="harga" class="form-label fw-medium">Harga <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text">Rp</span>
              <input type="number" name="harga" id="harga" class="form-control" min="0" step="1"
                placeholder="0" required>
            </div>
          </div>
          <div class="col-md-4 mt-3 mt-md-0">
            <label for="stok" class="form-label fw-medium">Stok <span class="text-danger">*</span></label>
            <input type="number" name="stok" id="stok" class="form-control" min="0" step="1"
              placeholder="0" required>
          </div>
          <div class="col-md-4 mt-3 mt-md-0">
            <label for="supplier_id" class="form-label fw-medium">Supplier <span class="text-danger">*</span></label>
            <select name="supplier_id" id="supplier_id" class="form-select" required>
              <option value="" selected disabled>-- Pilih Supplier --</option>
              <?php foreach ($suppliers as $supplier): ?>
                <option value="<?= (int) $supplier["id"] ?>"><?= sanitazion($supplier["nama"]) ?></option>
              <?php endforeach ?>
            </select>
          </div>
        </div>

        <hr class="text-muted">
        <div class="d-flex justify-content-end gap-2 mt-3">
          <button type="reset" class="btn btn-light border">Reset</button>
          <button type="submit" class="btn btn-primary px-4" <?= !$suppliers ? "disabled" : "" ?>>
            <i class="bi bi-save me-1"></i> Simpan Data
          </button>
        </div>
      </form>
    </div>
  </div>
</main>

<?php require_once __DIR__ . "/../partials/footer.php" ?>
