<?php
// form edit pelanggan

$id = trim((string) ($_GET['id'] ?? ''));
$stmt = $conn->prepare("SELECT * FROM pelanggan WHERE id = ? LIMIT 1");
$stmt->execute([$id]);
$pelanggan = $stmt->fetch();

if (!$pelanggan) {
  setFlash('danger', 'Data pelanggan tidak ditemukan.');
  header("Location: " . routeUrl("/pelanggan"));
  exit();
}

$pageTitle = 'Edit Pelanggan';
require_once __DIR__ . "/../partials/header.auth.php";
?>

<main class="container my-8">
  <div class="row justify-content-center">
    <div class="md:col-8 lg:col-6">
      <div class="card-panel p-6">
        <h1 class="fs-2xl mb-6">Edit Data Pelanggan</h1>

        <form method="POST" action="<?= routeUrl("/api/pelanggan/edit") ?>?id=<?= urlencode($pelanggan['id']) ?>">
          <div class="form-field mb-5">
            <label class="form-label" for="id_readonly">ID Pelanggan</label>
            <input class="form-control" type="text" id="id_readonly" value="<?= sanitazion($pelanggan['id']) ?>" readonly>
          </div>

          <div class="form-field mb-5">
            <label class="form-label" for="nama">Nama Pelanggan</label>
            <input class="form-control" type="text" id="nama" name="nama" maxlength="100"
              value="<?= sanitazion($pelanggan['nama']) ?>" required>
          </div>

          <div class="form-field mb-5">
            <label class="form-label" for="jenis_kelamin">Jenis Kelamin</label>
            <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
              <option value="L" <?= $pelanggan['jenis_kelamin'] === 'L' ? 'selected' : '' ?>>Laki-laki (L)</option>
              <option value="P" <?= $pelanggan['jenis_kelamin'] === 'P' ? 'selected' : '' ?>>Perempuan (P)</option>
            </select>
          </div>

          <div class="form-field mb-5">
            <label class="form-label" for="telp">Telp</label>
            <input class="form-control" type="text" id="telp" name="telp" maxlength="12"
              value="<?= sanitazion($pelanggan['telp']) ?>" required>
          </div>

          <div class="form-field mb-6">
            <label class="form-label" for="alamat">Alamat</label>
            <textarea class="form-control" id="alamat" name="alamat" rows="3"
              required><?= sanitazion($pelanggan['alamat']) ?></textarea>
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-solid theme-primary">Update</button>
            <a class="btn btn-text" href="<?= routeUrl("/pelanggan") ?>">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</main>

<?php require_once __DIR__ . "/../partials/footer.auth.php" ?>
