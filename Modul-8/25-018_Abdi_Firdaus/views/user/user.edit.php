<?php
// form edit user

$idUser = (int) ($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT id_user, username, nama, alamat, hp, level FROM user WHERE id_user = ? LIMIT 1");
$stmt->execute([$idUser]);
$user = $stmt->fetch();

if (!$user) {
  setFlash('danger', 'Data user tidak ditemukan.');
  header("Location: " . routeUrl("/user"));
  exit();
}

$pageTitle = 'Edit User';
require_once __DIR__ . "/../partials/header.auth.php";
?>

<main class="container my-8">
  <div class="row justify-content-center">
    <div class="md:col-8 lg:col-6">
      <div class="card-panel p-6">
        <h1 class="fs-2xl mb-6">Edit Data User</h1>

        <form method="POST" action="<?= routeUrl("/api/user/edit") ?>?id=<?= (int) $user['id_user'] ?>">
          <div class="form-field mb-5">
            <label class="form-label" for="username">Username</label>
            <input class="form-control" type="text" id="username" name="username" maxlength="30"
              value="<?= sanitazion($user['username']) ?>" autocomplete="username" required>
          </div>

          <div class="form-field mb-5">
            <label class="form-label" for="password">Password Baru (Opsional)</label>
            <input class="form-control" type="password" id="password" name="password"
              autocomplete="new-password" placeholder="Kosongkan jika tidak ingin mengubah password">
          </div>

          <div class="form-field mb-5">
            <label class="form-label" for="nama">Nama User</label>
            <input class="form-control" type="text" id="nama" name="nama" maxlength="50"
              value="<?= sanitazion($user['nama']) ?>" required>
          </div>

          <div class="form-field mb-5">
            <label class="form-label" for="alamat">Alamat</label>
            <textarea class="form-control" id="alamat" name="alamat" rows="3" maxlength="150"
              required><?= sanitazion($user['alamat']) ?></textarea>
          </div>

          <div class="form-field mb-5">
            <label class="form-label" for="hp">Nomor HP</label>
            <input class="form-control" type="text" id="hp" name="hp" maxlength="20"
              value="<?= sanitazion($user['hp']) ?>" required>
          </div>

          <div class="form-field mb-6">
            <label class="form-label" for="level">Jenis User (Level)</label>
            <select class="form-control" id="level" name="level" required>
              <option value="1" <?= ((int) $user['level'] === 1) ? 'selected' : '' ?>>Level 1 - Owner (Admin)</option>
              <option value="2" <?= ((int) $user['level'] === 2) ? 'selected' : '' ?>>Level 2 - Kasir (User Biasa)</option>
            </select>
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-solid theme-primary">Update</button>
            <a class="btn btn-text" href="<?= routeUrl("/user") ?>">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</main>

<?php require_once __DIR__ . "/../partials/footer.auth.php" ?>
