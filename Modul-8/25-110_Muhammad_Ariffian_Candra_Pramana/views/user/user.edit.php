<?php

require_once __DIR__ . "/../../route.php";
$id = (int) ($_GET["id"] ?? 0);

$stmt = $DBH->prepare("
  SELECT id_user, username, nama, alamat, hp, level
  FROM `user`
  WHERE id_user = ?
");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
  http_response_code(404);
  require __DIR__ . "/../404.php";
  return;
}

$pageTitle = "Edit User";
require_once __DIR__ . "/../partials/header.php";
?>

<main class="container py-5">
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
      <h1 class="h3 fw-bold text-secondary mb-1">Edit User</h1>
      <p class="text-muted mb-0">Perbarui data user yang dipilih.</p>
    </div>
    <a href="<?= appUrl("user") ?>" class="btn btn-outline-secondary">
      <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
  </div>

  <?php require __DIR__ . "/../partials/alert.php" ?>

  <div class="card shadow-sm">
    <div class="card-header bg-warning text-dark py-3">
      <h2 class="h6 mb-0 fw-semibold">Formulir Edit User</h2>
    </div>
    <div class="card-body p-4">
      <form action="<?= apiUrl("user", "edit", ["id" => $user["id_user"]]) ?>" method="post">
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="username" class="form-label fw-medium">Username <span class="text-danger">*</span></label>
            <input type="text" name="username" id="username" class="form-control" maxlength="30"
              autocomplete="username" value="<?= sanitazion($user["username"]) ?>" required>
          </div>
          <div class="col-md-6 mt-3 mt-md-0">
            <label for="password" class="form-label fw-medium">Password</label>
            <input type="password" name="password" id="password" class="form-control" minlength="4"
              autocomplete="new-password" placeholder="Kosongkan jika tidak diubah">
            <div class="form-text">Biarkan kosong apabila password tidak ingin diubah.</div>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label for="nama" class="form-label fw-medium">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" name="nama" id="nama" class="form-control" maxlength="50"
              value="<?= sanitazion($user["nama"]) ?>" required>
          </div>
          <div class="col-md-6 mt-3 mt-md-0">
            <label for="hp" class="form-label fw-medium">No. HP <span class="text-danger">*</span></label>
            <input type="text" name="hp" id="hp" class="form-control" maxlength="20" inputmode="numeric"
              value="<?= sanitazion($user["hp"]) ?>" required>
          </div>
        </div>

        <div class="mb-3">
          <label for="level" class="form-label fw-medium">Level <span class="text-danger">*</span></label>
          <select name="level" id="level" class="form-select" required>
            <option value="1" <?= (int) $user["level"] === 1 ? "selected" : "" ?>>Owner</option>
            <option value="2" <?= (int) $user["level"] !== 1 ? "selected" : "" ?>>Kasir</option>
          </select>
        </div>

        <div class="mb-4">
          <label for="alamat" class="form-label fw-medium">Alamat Lengkap <span class="text-danger">*</span></label>
          <textarea name="alamat" id="alamat" class="form-control" rows="3" maxlength="150"
            required><?= sanitazion($user["alamat"]) ?></textarea>
        </div>

        <hr class="text-muted">
        <div class="d-flex justify-content-end gap-2 mt-3">
          <button type="reset" class="btn btn-light border">Reset</button>
          <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-save me-1"></i> Update Data
          </button>
        </div>
      </form>
    </div>
  </div>
</main>

<?php require_once __DIR__ . "/../partials/footer.php" ?>
