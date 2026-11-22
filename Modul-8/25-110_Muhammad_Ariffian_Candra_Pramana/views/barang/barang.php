<?php

require_once __DIR__ . "/../../route.php";
$pageTitle = "Data Barang";
$data = $DBH->query("
  SELECT
    b.id,
    b.kode_barang,
    b.nama_barang,
    b.harga,
    b.stok,
    s.nama AS supplier_nama
  FROM barang AS b
  LEFT JOIN supplier AS s ON b.supplier_id = s.id
  ORDER BY b.id DESC
")->fetchAll();

require_once __DIR__ . "/../partials/header.php";
?>

<main class="container py-5">
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
      <h1 class="h3 fw-bold text-secondary mb-1">Data Barang</h1>
      <p class="text-muted mb-0">Kelola data master barang dan supplier-nya.</p>
    </div>
    <a href="<?= appUrl("barang", "add") ?>" class="btn btn-primary px-4">
      <i class="bi bi-plus-lg me-1"></i> Tambah Barang
    </a>
  </div>

  <?php require __DIR__ . "/../partials/alert.php" ?>

  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-striped table-hover align-middle mb-0">
        <thead class="table-primary">
          <tr>
            <th class="text-center" style="width: 60px;">No</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th class="text-end">Harga</th>
            <th class="text-center">Stok</th>
            <th>Supplier</th>
            <th class="text-center" style="width: 170px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!$data): ?>
            <tr>
              <td colspan="7" class="text-center text-muted py-4">Belum ada data barang.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($data as $i => $row): ?>
              <tr>
                <td class="text-center"><?= $i + 1 ?></td>
                <td><span class="badge text-bg-secondary"><?= sanitazion($row["kode_barang"]) ?></span></td>
                <td class="fw-medium"><?= sanitazion($row["nama_barang"]) ?></td>
                <td class="text-end text-nowrap"><?= formatRupiah($row["harga"]) ?></td>
                <td class="text-center">
                  <span class="badge <?= (int) $row["stok"] <= 10 ? "text-bg-warning" : "text-bg-success" ?>">
                    <?= sanitazion($row["stok"]) ?>
                  </span>
                </td>
                <td><?= sanitazion($row["supplier_nama"] ?? "-") ?></td>
                <td class="text-center text-nowrap">
                  <a href="<?= appUrl("barang", "edit", ["id" => $row["id"]]) ?>"
                    class="btn btn-sm btn-outline-warning">
                    <i class="bi bi-pencil-square"></i> Edit
                  </a>
                  <a href="<?= apiUrl("barang", "delete", ["id" => $row["id"]]) ?>"
                    class="btn btn-sm btn-outline-danger"
                    onclick="return confirm('Apakah Anda yakin ingin menghapus data barang ini?');">
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
