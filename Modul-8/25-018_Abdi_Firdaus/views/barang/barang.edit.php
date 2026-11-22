<?php
// form edit barang

$id = (int) ($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM barang WHERE id = ? LIMIT 1");
$stmt->execute([$id]);
$barang = $stmt->fetch();

if (!$barang) {
  setFlash('danger', 'Data barang tidak ditemukan.');
  header("Location: " . routeUrl("/barang"));
  exit();
}

$pageTitle = 'Edit Barang';
$suppliers = $conn->query("SELECT id, nama FROM supplier ORDER BY nama ASC")->fetchAll();

require_once __DIR__ . "/../partials/header.auth.php";
?>

<main class="container my-8">
  <div class="row justify-content-center">
    <div class="md:col-8 lg:col-6">
      <div class="card-panel p-6">
        <h1 class="fs-2xl mb-6">Edit Data Barang</h1>

        <form method="POST" action="<?= routeUrl("/api/barang/edit") ?>?id=<?= (int) $barang['id'] ?>">
          <div class="form-field mb-5">
            <label class="form-label" for="kode_barang">Kode Barang</label>
            <input class="form-control" type="text" id="kode_barang" name="kode_barang" maxlength="10"
              value="<?= sanitazion($barang['kode_barang']) ?>" required>
          </div>

          <div class="form-field mb-5">
            <label class="form-label" for="nama_barang">Nama Barang</label>
            <input class="form-control" type="text" id="nama_barang" name="nama_barang" maxlength="100"
              value="<?= sanitazion($barang['nama_barang']) ?>" required>
          </div>

          <div class="form-field mb-5">
            <label class="form-label" for="harga">Harga (Rp)</label>
            <input class="form-control" type="number" id="harga" name="harga" min="0"
              value="<?= sanitazion($barang['harga']) ?>" required>
          </div>

          <div class="form-field mb-5">
            <label class="form-label" for="stok">Stok</label>
            <input class="form-control" type="number" id="stok" name="stok" min="0"
              value="<?= sanitazion($barang['stok']) ?>" required>
          </div>

          <div class="form-field mb-6">
            <label class="form-label" for="supplier_id">Supplier</label>
            <select class="form-control" id="supplier_id" name="supplier_id" required>
              <option value="">-- Pilih Supplier --</option>
              <?php foreach ($suppliers as $s): ?>
                <option value="<?= (int) $s['id'] ?>" <?= ((int) $s['id'] === (int) $barang['supplier_id']) ? 'selected' : '' ?>>
                  <?= sanitazion($s['id']) ?> - <?= sanitazion($s['nama']) ?>
                </option>
              <?php endforeach ?>
            </select>
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-solid theme-primary">Update</button>
            <a class="btn btn-text" href="<?= routeUrl("/barang") ?>">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</main>

<?php require_once __DIR__ . "/../partials/footer.auth.php" ?>
