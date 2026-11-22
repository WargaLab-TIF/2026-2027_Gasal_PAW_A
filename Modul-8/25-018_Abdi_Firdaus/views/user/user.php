<?php
// daftar user

$pageTitle = 'Data User';
$dataUser = $conn->query("
  SELECT id_user, username, nama, alamat, hp, level
  FROM user
  ORDER BY id_user ASC
")->fetchAll();
$activeUserId = (int) ($_SESSION['user_id'] ?? 0);

require_once __DIR__ . "/../partials/header.auth.php";
?>

<main class="container my-8">
  <div class="card-panel p-6">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-6">
      <div>
        <h1 class="fs-2xl mb-1">Daftar User</h1>
        <p class="mb-0" style="color: #64748b; font-size: 0.9rem;">
          Kelola akun pengguna dengan peran Owner (Level 1) dan Kasir (Level 2).
        </p>
      </div>
      <a class="btn btn-solid theme-primary" href="<?= routeUrl("/user/add") ?>">+ Tambah User</a>
    </div>

    <?php if (empty($dataUser)): ?>
      <div class="alert theme-info mb-0">Belum ada data user.</div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table mb-0">
          <thead>
            <tr>
              <th>No</th>
              <th>Username</th>
              <th>Nama</th>
              <th>Alamat</th>
              <th>HP</th>
              <th>Level</th>
              <th class="text-end">Tindakan</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($dataUser as $i => $u): ?>
              <tr>
                <td><?= $i + 1 ?></td>
                <td><code><?= sanitazion($u['username']) ?></code></td>
                <td>
                  <?= sanitazion($u['nama']) ?>
                  <?php if ((int) $u['id_user'] === $activeUserId): ?>
                    <span class="badge theme-success">Sedang Login</span>
                  <?php endif ?>
                </td>
                <td><?= sanitazion($u['alamat']) ?></td>
                <td><?= sanitazion($u['hp']) ?></td>
                <td>
                  <span class="badge theme-<?= ((int) $u['level'] === 1) ? 'primary' : 'info' ?>">
                    Level <?= (int) $u['level'] ?> (<?= levelLabel($u['level']) ?>)
                  </span>
                </td>
                <td class="text-end">
                  <a class="btn btn-text theme-primary"
                    href="<?= routeUrl("/user/edit") ?>?id=<?= (int) $u['id_user'] ?>">Edit</a>
                  <?php if ((int) $u['id_user'] !== $activeUserId): ?>
                    <a class="btn btn-text theme-danger"
                      href="<?= routeUrl("/api/user/delete") ?>?id=<?= (int) $u['id_user'] ?>"
                      onclick="return confirm('Anda yakin akan menghapus user <?= sanitazion($u['username']) ?>?');">Hapus</a>
                  <?php endif ?>
                </td>
              </tr>
            <?php endforeach ?>
          </tbody>
        </table>
      </div>
    <?php endif ?>
  </div>
</main>

<?php require_once __DIR__ . "/../partials/footer.auth.php" ?>
