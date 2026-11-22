<?php
// form tambah transaksi

$pageTitle = 'Tambah Transaksi';
$pelangganList = $conn->query("SELECT id, nama FROM pelanggan ORDER BY nama ASC")->fetchAll();

require_once __DIR__ . "/../partials/header.auth.php";
?>

<main class="container my-8">
  <div class="row justify-content-center">
    <div class="md:col-8 lg:col-6">
      <div class="card-panel p-6">
        <h1 class="fs-2xl mb-6">Tambah Data Master Transaksi</h1>

        <form method="POST" action="<?= routeUrl("/api/transaksi/add") ?>">
          <div class="form-field mb-5">
            <label class="form-label" for="waktu_transaksi">Waktu Transaksi</label>
            <input class="form-control" type="date" id="waktu_transaksi" name="waktu_transaksi"
              value="<?= date('Y-m-d') ?>" required>
          </div>

          <div class="form-field mb-5">
            <label class="form-label" for="pelanggan_id">Pelanggan</label>
            <select class="form-control" id="pelanggan_id" name="pelanggan_id" required>
              <option value="">-- Pilih Pelanggan --</option>
              <?php foreach ($pelangganList as $p): ?>
                <option value="<?= sanitazion($p['id']) ?>">
                  <?= sanitazion($p['id']) ?> - <?= sanitazion($p['nama']) ?>
                </option>
              <?php endforeach ?>
            </select>
          </div>

          <div class="form-field mb-5">
            <label class="form-label" for="keterangan">Keterangan</label>
            <textarea class="form-control" id="keterangan" name="keterangan" rows="3"
              placeholder="Masukkan keterangan transaksi" required></textarea>
          </div>

          <div class="form-field mb-6">
            <label class="form-label" for="total">Total Awal</label>
            <input class="form-control" type="number" id="total" name="total" value="0" readonly>
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-solid theme-primary">Simpan Transaksi</button>
            <a class="btn btn-text" href="<?= routeUrl("/transaksi") ?>">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</main>

<?php require_once __DIR__ . "/../partials/footer.auth.php" ?>
