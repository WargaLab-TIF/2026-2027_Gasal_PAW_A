<?php
// form tambah pelanggan

$pageTitle = 'Tambah Pelanggan';
require_once __DIR__ . "/../partials/header.auth.php";
?>

<main class="container my-8">
  <div class="row justify-content-center">
    <div class="md:col-8 lg:col-6">
      <div class="card-panel p-6">
        <h1 class="fs-2xl mb-6">Tambah Data Pelanggan</h1>

        <form method="POST" action="<?= routeUrl("/api/pelanggan/add") ?>">
          <div class="form-field mb-5">
            <label class="form-label" for="id">ID Pelanggan (Opsional)</label>
            <input class="form-control" type="text" id="id" name="id" maxlength="20"
              placeholder="Kosongkan untuk ID otomatis">
          </div>

          <div class="form-field mb-5">
            <label class="form-label" for="nama">Nama Pelanggan</label>
            <input class="form-control" type="text" id="nama" name="nama" maxlength="100"
              placeholder="Masukkan nama pelanggan" required>
          </div>

          <div class="form-field mb-5">
            <label class="form-label" for="jenis_kelamin">Jenis Kelamin</label>
            <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
              <option value="">-- Pilih Jenis Kelamin --</option>
              <option value="L">Laki-laki (L)</option>
              <option value="P">Perempuan (P)</option>
            </select>
          </div>

          <div class="form-field mb-5">
            <label class="form-label" for="telp">Telp</label>
            <input class="form-control" type="text" id="telp" name="telp" maxlength="12"
              placeholder="Contoh: 081111222311" required>
          </div>

          <div class="form-field mb-6">
            <label class="form-label" for="alamat">Alamat</label>
            <textarea class="form-control" id="alamat" name="alamat" rows="3"
              placeholder="Masukkan alamat pelanggan" required></textarea>
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-solid theme-primary">Simpan</button>
            <a class="btn btn-text" href="<?= routeUrl("/pelanggan") ?>">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</main>

<?php require_once __DIR__ . "/../partials/footer.auth.php" ?>
