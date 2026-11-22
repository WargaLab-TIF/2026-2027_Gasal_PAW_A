<?php

require_once __DIR__ . "/../../route.php";
$id = (int) ($_GET["id"] ?? 0);

$stmt = $DBH->prepare("
  SELECT id, waktu_transaksi, keterangan, total, pelanggan_id
  FROM transaksi
  WHERE id = ?
");
$stmt->execute([$id]);
$transaksi = $stmt->fetch();

if (!$transaksi) {
  http_response_code(404);
  require __DIR__ . "/../404.php";
  return;
}

$pelanggan = $DBH->query("SELECT id, nama FROM pelanggan ORDER BY nama")->fetchAll();

$pageTitle = "Edit Transaksi";
require_once __DIR__ . "/../partials/header.php";
?>

<main class="container py-5">
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
      <h1 class="h3 fw-bold text-secondary mb-1">Edit Transaksi #<?= (int) $transaksi["id"] ?></h1>
      <p class="text-muted mb-0">Perbarui data utama transaksi.</p>
    </div>
    <div class="d-flex gap-2">
      <a href="<?= appUrl("transaksi", "detail", ["id" => $transaksi["id"]]) ?>"
        class="btn btn-outline-primary">
        <i class="bi bi-list-check me-1"></i> Lihat Detail
      </a>
      <a href="<?= appUrl("transaksi") ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Kembali
      </a>
    </div>
  </div>

  <?php require __DIR__ . "/../partials/alert.php" ?>

  <div class="card shadow-sm">
    <div class="card-header bg-warning text-dark py-3">
      <h2 class="h6 mb-0 fw-semibold">Formulir Edit Transaksi</h2>
    </div>
    <div class="card-body p-4">
      <form action="<?= apiUrl("transaksi", "edit", ["id" => $transaksi["id"]]) ?>" method="post">
        <div class="row mb-3">
          <div class="col-md-4">
            <label for="waktu_transaksi" class="form-label fw-medium">Tanggal Transaksi <span
                class="text-danger">*</span></label>
            <input type="date" name="waktu_transaksi" id="waktu_transaksi" class="form-control"
              value="<?= sanitazion(date("Y-m-d", strtotime((string) $transaksi["waktu_transaksi"]))) ?>" required>
          </div>
          <div class="col-md-8 mt-3 mt-md-0">
            <label for="pelanggan_id" class="form-label fw-medium">Pelanggan</label>
            <select name="pelanggan_id" id="pelanggan_id" class="form-select">
              <option value="">-- Tanpa Pelanggan --</option>
              <?php foreach ($pelanggan as $item): ?>
                <option value="<?= sanitazion($item["id"]) ?>"
                  <?= (string) $item["id"] === (string) $transaksi["pelanggan_id"] ? "selected" : "" ?>>
                  <?= sanitazion($item["nama"]) ?>
                </option>
              <?php endforeach ?>
            </select>
          </div>
        </div>

        <div class="mb-3">
          <label for="keterangan" class="form-label fw-medium">Keterangan</label>
          <textarea name="keterangan" id="keterangan" class="form-control"
            rows="3"><?= sanitazion($transaksi["keterangan"]) ?></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label fw-medium">Total Transaksi</label>
          <div class="form-control bg-light" readonly><?= formatRupiah($transaksi["total"]) ?></div>
          <div class="form-text">Total dihitung otomatis dari detail barang dan tidak dapat diubah di sini.</div>
        </div>

        <hr class="text-muted">
        <div class="d-flex justify-content-end gap-2 mt-3">
          <button type="reset" class="btn btn-light border">Reset</button>
          <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-save me-1"></i> Update Transaksi
          </button>
        </div>
      </form>
    </div>
  </div>
</main>

<?php require_once __DIR__ . "/../partials/footer.php" ?>
