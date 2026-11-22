<?php
$pageTitle = "Tambah Pelanggan";
require_once __DIR__ . "/../partials/header.php";
?>

<main class="container py-5">
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
      <h1 class="h3 fw-bold text-secondary mb-1">Tambah Pelanggan</h1>
      <p class="text-muted mb-0">Lengkapi formulir data pelanggan baru.</p>
    </div>
    <a href="<?= appUrl("pelanggan") ?>" class="btn btn-outline-secondary">
      <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
  </div>

  <?php require __DIR__ . "/../partials/alert.php" ?>

  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white py-3">
      <h2 class="h6 mb-0 fw-semibold">Formulir Pelanggan Baru</h2>
    </div>
    <div class="card-body p-4">
      <form action="<?= apiUrl("pelanggan", "add") ?>" method="post">
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="nama" class="form-label fw-medium">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" name="nama" id="nama" class="form-control" maxlength="100"
              placeholder="Masukkan nama lengkap" required>
          </div>
          <div class="col-md-6 mt-3 mt-md-0">
            <label for="jenis_kelamin" class="form-label fw-medium">Jenis Kelamin <span
                class="text-danger">*</span></label>
            <select name="jenis_kelamin" id="jenis_kelamin" class="form-select" required>
              <option value="" selected disabled>-- Pilih Jenis Kelamin --</option>
              <option value="L">Laki-laki</option>
              <option value="P">Perempuan</option>
            </select>
          </div>
        </div>

        <div class="mb-3">
          <label for="telp" class="form-label fw-medium">No. Telepon <span class="text-danger">*</span></label>
          <input type="text" name="telp" id="telp" class="form-control" maxlength="12" inputmode="numeric"
            placeholder="Contoh: 081234567890" required>
        </div>

        <div class="mb-4">
          <label for="alamat" class="form-label fw-medium">Alamat Lengkap <span class="text-danger">*</span></label>
          <textarea name="alamat" id="alamat" class="form-control" rows="3"
            placeholder="Masukkan alamat tempat tinggal pelanggan" required></textarea>
        </div>

        <hr class="text-muted">
        <div class="d-flex justify-content-end gap-2 mt-3">
          <button type="reset" class="btn btn-light border">Reset</button>
          <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-save me-1"></i> Simpan Data
          </button>
        </div>
      </form>
    </div>
  </div>
</main>

<?php require_once __DIR__ . "/../partials/footer.php" ?>
