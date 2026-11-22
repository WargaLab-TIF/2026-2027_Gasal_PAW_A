<?php
// form edit supplier

$id = (int) ($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM supplier WHERE id = ? LIMIT 1");
$stmt->execute([$id]);
$supplier = $stmt->fetch();

if (!$supplier) {
  setFlash('danger', 'Data supplier tidak ditemukan.');
  header("Location: " . routeUrl("/supplier"));
  exit();
}

$pageTitle = 'Edit Supplier';
require_once __DIR__ . "/../partials/header.auth.php";
?>

<main class="container my-8">
  <div class="row justify-content-center">
    <div class="md:col-8 lg:col-6">
      <div class="card-panel p-6">
        <h1 class="fs-2xl mb-6">Edit Data Master Supplier</h1>

        <form method="POST" action="<?= routeUrl("/api/supplier/edit") ?>?id=<?= (int) $supplier['id'] ?>">
          <div class="form-field mb-5">
            <label class="form-label" for="nama">Nama Supplier</label>
            <input class="form-control" type="text" id="nama" name="nama" maxlength="100"
              value="<?= sanitazion($supplier['nama']) ?>" required>
          </div>

          <div class="form-field mb-5">
            <label class="form-label" for="telp">Telp</label>
            <input class="form-control" type="text" id="telp" name="telp" maxlength="12"
              value="<?= sanitazion($supplier['telp']) ?>" required>
          </div>

          <div class="form-field mb-6">
            <label class="form-label" for="alamat">Alamat</label>
            <textarea class="form-control" id="alamat" name="alamat" rows="3"
              required><?= sanitazion($supplier['alamat']) ?></textarea>
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-solid theme-primary">Update</button>
            <a class="btn btn-text" href="<?= routeUrl("/supplier") ?>">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</main>

<?php require_once __DIR__ . "/../partials/footer.auth.php" ?>
