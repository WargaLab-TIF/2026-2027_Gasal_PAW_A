<?php
// form tambah barang

$pageTitle = 'Tambah Barang';
$suppliers = $conn->query("SELECT id, nama FROM supplier ORDER BY nama ASC")->fetchAll();

require_once __DIR__ . "/../partials/header.auth.php";
?>

<main class="container my-8">
  <div class="row justify-content-center">
    <div class="md:col-8 lg:col-6">
      <div class="card-panel p-6">
        <h1 class="fs-2xl mb-6">Tambah Data Barang</h1>

        <form method="POST" action="<?= routeUrl("/api/barang/add") ?>">
          <div class="form-field mb-5">
            <label class="form-label" for="kode_barang">Kode Barang</label>
            <input class="form-control" type="text" id="kode_barang" name="kode_barang" maxlength="10"
              placeholder="Contoh: BRG011" required>
          </div>

          <div class="form-field mb-5">
            <label class="form-label" for="nama_barang">Nama Barang</label>
            <input class="form-control" type="text" id="nama_barang" name="nama_barang" maxlength="100"
              placeholder="Masukkan nama barang" required>
          </div>

          <div class="form-field mb-5">
            <label class="form-label" for="harga">Harga (Rp)</label>
            <input class="form-control" type="number" id="harga" name="harga" min="0" placeholder="Masukkan harga satuan"
              required>
          </div>

          <div class="form-field mb-5">
            <label class="form-label" for="stok">Stok</label>
            <input class="form-control" type="number" id="stok" name="stok" min="0" placeholder="Masukkan jumlah stok"
              required>
          </div>

          <div class="form-field mb-6">
            <label class="form-label" for="supplier_id">Supplier</label>
            <select class="form-control" id="supplier_id" name="supplier_id" required>
              <option value="">-- Pilih Supplier --</option>
              <?php foreach ($suppliers as $s): ?>
                <option value="<?= (int) $s['id'] ?>"><?= sanitazion($s['id']) ?> - <?= sanitazion($s['nama']) ?></option>
              <?php endforeach ?>
            </select>
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-solid theme-primary">Simpan</button>
            <a class="btn btn-text" href="<?= routeUrl("/barang") ?>">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</main>

<?php require_once __DIR__ . "/../partials/footer.auth.php" ?>
