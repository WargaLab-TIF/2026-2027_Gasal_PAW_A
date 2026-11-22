<?php
// form tambah detail transaksi

$pageTitle = 'Tambah Detail Transaksi';

$dataTransaksi = $conn->query("SELECT id, waktu_transaksi, keterangan FROM transaksi ORDER BY id DESC")->fetchAll();
$requestedId = isset($_GET['transaksi_id']) ? (int) $_GET['transaksi_id'] : 0;
$transaksiId = 0;

if (!empty($dataTransaksi)) {
  $validIds = array_map('intval', array_column($dataTransaksi, 'id'));
  $transaksiId = in_array($requestedId, $validIds, true) ? $requestedId : $validIds[0];
}

$barangTerpakai = [];
if ($transaksiId > 0) {
  $stmtDipakai = $conn->prepare("SELECT barang_id FROM transaksi_detail WHERE transaksi_id = ?");
  $stmtDipakai->execute([$transaksiId]);
  $barangTerpakai = array_map('intval', $stmtDipakai->fetchAll(PDO::FETCH_COLUMN));
}

$dataBarang = $conn->query("SELECT id, nama_barang, harga FROM barang ORDER BY id ASC")->fetchAll();

require_once __DIR__ . "/../partials/header.auth.php";
?>

<main class="container my-8">
  <div class="row justify-content-center">
    <div class="md:col-8 lg:col-6">
      <div class="card-panel p-6">
        <h1 class="fs-2xl mb-6">Tambah Detail Transaksi</h1>

        <?php if (empty($dataTransaksi)): ?>
          <div class="alert theme-warning">Belum ada master transaksi. Buat transaksi terlebih dahulu.</div>
          <a class="btn btn-solid theme-primary" href="<?= routeUrl("/transaksi/add") ?>">+ Tambah Transaksi</a>
        <?php else: ?>
          <form method="POST" action="<?= routeUrl("/api/transaksi/detail/add") ?>">
            <div class="form-field mb-5">
              <label class="form-label" for="transaksi_id">ID Transaksi</label>
              <select class="form-control" id="transaksi_id" name="transaksi_id"
                onchange="window.location.href='<?= routeUrl('/transaksi/detail/add') ?>?transaksi_id=' + this.value"
                required>
                <?php foreach ($dataTransaksi as $t): ?>
                  <option value="<?= (int) $t['id'] ?>" <?= ((int) $t['id'] === $transaksiId) ? 'selected' : '' ?>>
                    ID #<?= sanitazion($t['id']) ?> - <?= sanitazion($t['keterangan']) ?>
                  </option>
                <?php endforeach ?>
              </select>
            </div>

            <div class="form-field mb-5">
              <label class="form-label" for="barang_id">Pilih Barang</label>
              <select class="form-control" id="barang_id" name="barang_id" required>
                <option value="">-- Pilih Barang --</option>
                <?php foreach ($dataBarang as $b):
                  $sudahAda = in_array((int) $b['id'], $barangTerpakai, true);
                  ?>
                  <option value="<?= (int) $b['id'] ?>" <?= $sudahAda ? 'disabled' : '' ?>>
                    <?= sanitazion($b['nama_barang']) ?> - <?= formatRupiah($b['harga']) ?>
                    <?= $sudahAda ? '(Sudah dipilih)' : '' ?>
                  </option>
                <?php endforeach ?>
              </select>
            </div>

            <div class="form-field mb-6">
              <label class="form-label" for="qty">Qty (Jumlah Beli)</label>
              <input class="form-control" type="number" id="qty" name="qty" min="1" placeholder="Masukkan jumlah beli"
                required>
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-solid theme-primary">Tambah Detail</button>
              <a class="btn btn-text" href="<?= routeUrl("/transaksi") ?>?transaksi_id=<?= $transaksiId ?>">Batal</a>
            </div>
          </form>
        <?php endif ?>
      </div>
    </div>
  </div>
</main>

<?php require_once __DIR__ . "/../partials/footer.auth.php" ?>
