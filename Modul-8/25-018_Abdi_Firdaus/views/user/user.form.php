<?php
// form tambah user

$pageTitle = 'Tambah User Baru';
require_once __DIR__ . "/../partials/header.auth.php";
?>

<main class="container my-8">
  <div class="row justify-content-center">
    <div class="md:col-8 lg:col-6">
      <div class="card-panel p-6">
        <h1 class="fs-2xl mb-6">Tambah User Baru</h1>

        <form method="POST" action="<?= routeUrl("/api/user/add") ?>">
          <div class="form-field mb-5">
            <label class="form-label" for="username">Username</label>
            <input class="form-control" type="text" id="username" name="username" maxlength="30"
              autocomplete="username" placeholder="Masukkan username" required>
          </div>

          <div class="form-field mb-5">
            <label class="form-label" for="password">Password</label>
            <input class="form-control" type="password" id="password" name="password"
              autocomplete="new-password" placeholder="Masukkan password" required>
          </div>

          <div class="form-field mb-5">
            <label class="form-label" for="nama">Nama User</label>
            <input class="form-control" type="text" id="nama" name="nama" maxlength="50"
              placeholder="Masukkan nama lengkap" required>
          </div>

          <div class="form-field mb-5">
            <label class="form-label" for="alamat">Alamat</label>
            <textarea class="form-control" id="alamat" name="alamat" rows="3" maxlength="150"
              placeholder="Masukkan alamat" required></textarea>
          </div>

          <div class="form-field mb-5">
            <label class="form-label" for="hp">Nomor HP</label>
            <input class="form-control" type="text" id="hp" name="hp" maxlength="20"
              placeholder="Contoh: 081234567890" required>
          </div>

          <div class="form-field mb-6">
            <label class="form-label" for="level">Jenis User (Level)</label>
            <select class="form-control" id="level" name="level" required>
              <option value="">-- Pilih Jenis User --</option>
              <option value="1">Level 1 - Owner (Admin)</option>
              <option value="2">Level 2 - Kasir (User Biasa)</option>
            </select>
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-solid theme-primary">Simpan</button>
            <a class="btn btn-text" href="<?= routeUrl("/user") ?>">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</main>

<?php require_once __DIR__ . "/../partials/footer.auth.php" ?>
