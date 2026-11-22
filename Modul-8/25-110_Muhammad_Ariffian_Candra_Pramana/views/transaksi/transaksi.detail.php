<?php

require_once __DIR__ . "/../../route.php";
$id = (int) ($_GET["id"] ?? 0);

$stmt = $DBH->prepare("
  SELECT
    t.id,
    t.waktu_transaksi,
    t.keterangan,
    t.total,
    t.pelanggan_id,
    p.nama AS pelanggan_nama
  FROM transaksi AS t
  LEFT JOIN pelanggan AS p ON t.pelanggan_id = p.id
  WHERE t.id = ?
");
$stmt->execute([$id]);
$transaksi = $stmt->fetch();

if (!$transaksi) {
  http_response_code(404);
  require __DIR__ . "/../404.php";
  return;
}

$stmtDetail = $DBH->prepare("
  SELECT
    td.transaksi_id,
    td.barang_id,
    td.harga,
    td.qty,
    b.kode_barang,
    b.nama_barang
  FROM transaksi_detail AS td
  JOIN barang AS b ON td.barang_id = b.id
  WHERE td.transaksi_id = ?
  ORDER BY b.nama_barang
");
$stmtDetail->execute([$id]);
$details = $stmtDetail->fetchAll();

$barangTerinput = array_map("intval", array_column($details, "barang_id"));
$semuaBarang = $DBH->query("
  SELECT id, kode_barang, nama_barang, harga
  FROM barang
  ORDER BY nama_barang
")->fetchAll();

$barangTersedia = array_filter(
  $semuaBarang,
  static fn(array $item): bool => !in_array((int) $item["id"], $barangTerinput, true)
);

$grandTotal = 0;
foreach ($details as $row) {
  $grandTotal += (int) $row["harga"] * (int) $row["qty"];
}

$pageTitle = "Detail Transaksi #" . $transaksi["id"];
require_once __DIR__ . "/../partials/header.php";
?>

<main class="container py-5">
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
      <h1 class="h3 fw-bold text-secondary mb-1">Detail Transaksi #<?= (int) $transaksi["id"] ?></h1>
      <p class="text-muted mb-0">Kelola barang yang masuk ke transaksi ini.</p>
    </div>
    <div class="d-flex gap-2">
      <a href="<?= appUrl("transaksi", "edit", ["id" => $transaksi["id"]]) ?>"
        class="btn btn-outline-warning">
        <i class="bi bi-pencil-square me-1"></i> Edit Transaksi
      </a>
      <a href="<?= appUrl("transaksi") ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Kembali
      </a>
    </div>
  </div>

  <?php require __DIR__ . "/../partials/alert.php" ?>

  <div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white py-3">
      <h2 class="h6 mb-0 fw-semibold">Transaksi (Master)</h2>
    </div>
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-6 col-lg-3">
          <div class="text-muted small">ID Transaksi</div>
          <div class="fw-semibold">#<?= (int) $transaksi["id"] ?></div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="text-muted small">Tanggal</div>
          <div class="fw-semibold"><?= sanitazion(formatTanggal($transaksi["waktu_transaksi"])) ?></div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="text-muted small">Pelanggan</div>
          <div class="fw-semibold"><?= sanitazion($transaksi["pelanggan_nama"] ?? "-") ?></div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="text-muted small">Total Tersimpan</div>
          <div class="fw-semibold"><?= formatRupiah($transaksi["total"]) ?></div>
        </div>
        <div class="col-12">
          <div class="text-muted small">Keterangan</div>
          <div><?= sanitazion($transaksi["keterangan"] ?: "-") ?></div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-lg-4">
      <div class="card shadow-sm h-100">
        <div class="card-header bg-success text-white py-3">
          <h2 class="h6 mb-0 fw-semibold">Tambah Item Detail</h2>
        </div>
        <div class="card-body p-4">
          <?php if (!$semuaBarang): ?>
            <div class="alert alert-warning mb-0" role="alert">
              Belum ada data barang. Silakan
              <a href="<?= appUrl("barang", "add") ?>" class="alert-link">tambah barang</a>
              terlebih dahulu.
            </div>
          <?php elseif (!$barangTersedia): ?>
            <div class="alert alert-info mb-0" role="alert">
              Semua barang sudah ditambahkan ke transaksi ini. Hapus salah satu item jika ingin menambahkannya kembali.
            </div>
          <?php else: ?>
            <form action="<?= apiUrl("transaksi", "detail/add") ?>" method="post">
              <input type="hidden" name="transaksi_id" value="<?= (int) $transaksi["id"] ?>">

              <div class="mb-3">
                <label for="barang_id" class="form-label fw-medium">Barang <span class="text-danger">*</span></label>
                <select name="barang_id" id="barang_id" class="form-select" required>
                  <option value="" selected disabled>-- Pilih Barang --</option>
                  <?php foreach ($semuaBarang as $item): ?>
                    <?php $sudahAda = in_array((int) $item["id"], $barangTerinput, true); ?>
                    <option value="<?= (int) $item["id"] ?>" <?= $sudahAda ? "disabled" : "" ?>>
                      <?= sanitazion($item["kode_barang"] . " - " . $item["nama_barang"]) ?>
                      (<?= formatRupiah($item["harga"]) ?>)<?= $sudahAda ? " - sudah ditambahkan" : "" ?>
                    </option>
                  <?php endforeach ?>
                </select>
              </div>

              <div class="mb-3">
                <label for="qty" class="form-label fw-medium">Quantity <span class="text-danger">*</span></label>
                <input type="number" name="qty" id="qty" class="form-control" min="1" step="1" value="1"
                  required>
              </div>

              <button type="submit" class="btn btn-success w-100 fw-medium">
                <i class="bi bi-plus-lg me-1"></i> Tambah ke Detail
              </button>
            </form>
          <?php endif ?>
        </div>
      </div>
    </div>

    <div class="col-lg-8">
      <div class="card shadow-sm h-100">
        <div class="card-header bg-warning text-dark py-3">
          <h2 class="h6 mb-0 fw-semibold">Detail Transaksi (Detail)</h2>
        </div>
        <div class="table-responsive">
          <table class="table table-hover table-bordered align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th class="text-center" style="width: 60px;">No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th class="text-end">Harga</th>
                <th class="text-center">Qty</th>
                <th class="text-end">Subtotal</th>
                <th class="text-center" style="width: 90px;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!$details): ?>
                <tr>
                  <td colspan="7" class="text-center text-muted py-4">Belum ada barang di transaksi ini.</td>
                </tr>
              <?php else: ?>
                <?php foreach ($details as $i => $row): ?>
                  <?php $subtotal = (int) $row["harga"] * (int) $row["qty"]; ?>
                  <tr>
                    <td class="text-center"><?= $i + 1 ?></td>
                    <td><span class="badge text-bg-secondary"><?= sanitazion($row["kode_barang"]) ?></span></td>
                    <td class="fw-medium"><?= sanitazion($row["nama_barang"]) ?></td>
                    <td class="text-end text-nowrap"><?= formatRupiah($row["harga"]) ?></td>
                    <td class="text-center"><?= (int) $row["qty"] ?></td>
                    <td class="text-end text-nowrap fw-medium"><?= formatRupiah($subtotal) ?></td>
                    <td class="text-center">
                      <a href="<?= apiUrl("transaksi", "detail/delete", ["id" => $transaksi["id"], "barang_id" => $row["barang_id"]]) ?>"
                        class="btn btn-sm btn-outline-danger"
                        onclick="return confirm('Hapus barang ini dari transaksi?');">
                        <i class="bi bi-trash"></i>
                      </a>
                    </td>
                  </tr>
                <?php endforeach ?>
                <tr class="table-warning fw-bold">
                  <td colspan="5" class="text-end">Total Belanja:</td>
                  <td class="text-end text-nowrap"><?= formatRupiah($grandTotal) ?></td>
                  <td></td>
                </tr>
              <?php endif ?>
            </tbody>
          </table>
        </div>
        <div class="card-footer bg-white text-muted small py-3">
          <i class="bi bi-info-circle me-1"></i>Total transaksi diperbarui otomatis setiap kali item detail ditambah
          atau dihapus.
        </div>
      </div>
    </div>
  </div>
</main>

<?php require_once __DIR__ . "/../partials/footer.php" ?>
