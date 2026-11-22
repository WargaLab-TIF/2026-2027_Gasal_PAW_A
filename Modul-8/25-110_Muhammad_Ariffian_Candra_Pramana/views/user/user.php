<?php

require_once __DIR__ . "/../../route.php";
$pageTitle = "Data User";
$data = $DBH->query("
  SELECT id_user, username, nama, alamat, hp, level
  FROM `user`
  ORDER BY id_user DESC
")->fetchAll();

require_once __DIR__ . "/../partials/header.php";
?>

<main class="container py-5">
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
      <h1 class="h3 fw-bold text-secondary mb-1">Data User</h1>
      <p class="text-muted mb-0">Kelola data master user dan hak aksesnya.</p>
    </div>
    <a href="<?= appUrl("user", "add") ?>" class="btn btn-primary px-4">
      <i class="bi bi-plus-lg me-1"></i> Tambah User
    </a>
  </div>

  <?php require __DIR__ . "/../partials/alert.php" ?>

  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-striped table-hover align-middle mb-0">
        <thead class="table-primary">
          <tr>
            <th class="text-center" style="width: 60px;">No</th>
            <th>Username</th>
            <th>Nama</th>
            <th>Alamat</th>
            <th>No. HP</th>
            <th class="text-center">Level</th>
            <th class="text-center" style="width: 170px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!$data): ?>
            <tr>
              <td colspan="7" class="text-center text-muted py-4">Belum ada data user.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($data as $i => $row): ?>
              <?php
              $levelClass = match ((int) $row["level"]) {
                1 => "text-bg-primary",
                2 => "text-bg-info",
                default => "text-bg-light",
              };
              ?>
              <tr>
                <td class="text-center"><?= $i + 1 ?></td>
                <td class="fw-medium"><?= sanitazion($row["username"]) ?></td>
                <td><?= sanitazion($row["nama"]) ?></td>
                <td><?= sanitazion($row["alamat"]) ?></td>
                <td><?= sanitazion($row["hp"]) ?></td>
                <td class="text-center">
                  <span class="badge <?= $levelClass ?>"><?= sanitazion(levelLabel($row["level"])) ?></span>
                </td>
                <td class="text-center text-nowrap">
                  <a href="<?= appUrl("user", "edit", ["id" => $row["id_user"]]) ?>"
                    class="btn btn-sm btn-outline-warning">
                    <i class="bi bi-pencil-square"></i> Edit
                  </a>
                  <a href="<?= apiUrl("user", "delete", ["id" => $row["id_user"]]) ?>"
                    class="btn btn-sm btn-outline-danger"
                    onclick="return confirm('Apakah Anda yakin ingin menghapus data user ini?');">
                    <i class="bi bi-trash"></i> Hapus
                  </a>
                </td>
              </tr>
            <?php endforeach ?>
          <?php endif ?>
        </tbody>
      </table>
    </div>
  </div>
</main>

<?php require_once __DIR__ . "/../partials/footer.php" ?>
