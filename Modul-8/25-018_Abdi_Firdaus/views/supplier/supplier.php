<?php
// daftar supplier

$pageTitle = 'Data Supplier';
$dataSupplier = $conn->query("SELECT * FROM supplier ORDER BY id ASC")->fetchAll();

require_once __DIR__ . "/../partials/header.auth.php";
?>

<main class="container my-8">
  <div class="card-panel p-6">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-6">
      <div>
        <h1 class="fs-2xl mb-1">Data Master Supplier</h1>
        <p class="mb-0" style="color: #64748b; font-size: 0.9rem;">Kelola daftar pemasok barang.</p>
      </div>
      <a class="btn btn-solid theme-primary" href="<?= routeUrl("/supplier/add") ?>">+ Tambah Supplier</a>
    </div>

    <?php if (empty($dataSupplier)): ?>
      <div class="alert theme-info mb-0">Belum ada data supplier.</div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table mb-0">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama Supplier</th>
              <th>Telp</th>
              <th>Alamat</th>
              <th class="text-end">Tindakan</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($dataSupplier as $i => $s): ?>
              <tr>
                <td><?= $i + 1 ?></td>
                <td><?= sanitazion($s['nama']) ?></td>
                <td><?= sanitazion($s['telp']) ?></td>
                <td><?= sanitazion($s['alamat']) ?></td>
                <td class="text-end">
                  <a class="btn btn-text theme-primary" href="<?= routeUrl("/supplier/edit") ?>?id=<?= (int) $s['id'] ?>">Edit</a>
                  <a class="btn btn-text theme-danger"
                    href="<?= routeUrl("/api/supplier/delete") ?>?id=<?= (int) $s['id'] ?>"
                    onclick="return confirm('Anda yakin akan menghapus supplier ini?');">Hapus</a>
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
