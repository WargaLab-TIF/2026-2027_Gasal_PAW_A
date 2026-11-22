<?php

require_once __DIR__ . "/../../route.php";
$pageTitle = "Data Supplier";
$data = $DBH->query("
  SELECT id, nama, telp, alamat
  FROM supplier
  ORDER BY id DESC
")->fetchAll();

require_once __DIR__ . "/../partials/header.php";
?>

<main class="container py-5">
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
      <h1 class="h3 fw-bold text-secondary mb-1">Data Supplier</h1>
      <p class="text-muted mb-0">Kelola data master supplier.</p>
    </div>
    <a href="<?= appUrl("supplier", "add") ?>" class="btn btn-primary px-4">
      <i class="bi bi-plus-lg me-1"></i> Tambah Supplier
    </a>
  </div>

  <?php require __DIR__ . "/../partials/alert.php" ?>

  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-striped table-hover align-middle mb-0">
        <thead class="table-primary">
          <tr>
            <th class="text-center" style="width: 60px;">No</th>
            <th>Nama Supplier</th>
            <th>No. Telepon</th>
            <th>Alamat</th>
            <th class="text-center" style="width: 170px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!$data): ?>
            <tr>
              <td colspan="5" class="text-center text-muted py-4">Belum ada data supplier.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($data as $i => $row): ?>
              <tr>
                <td class="text-center"><?= $i + 1 ?></td>
                <td class="fw-medium"><?= sanitazion($row["nama"]) ?></td>
                <td><?= sanitazion($row["telp"]) ?></td>
                <td><?= sanitazion($row["alamat"]) ?></td>
                <td class="text-center text-nowrap">
                  <a href="<?= appUrl("supplier", "edit", ["id" => $row["id"]]) ?>"
                    class="btn btn-sm btn-outline-warning">
                    <i class="bi bi-pencil-square"></i> Edit
                  </a>
                  <a href="<?= apiUrl("supplier", "delete", ["id" => $row["id"]]) ?>"
                    class="btn btn-sm btn-outline-danger"
                    onclick="return confirm('Apakah Anda yakin ingin menghapus data supplier ini?');">
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
