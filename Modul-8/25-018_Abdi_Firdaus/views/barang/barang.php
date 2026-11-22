<?php
// daftar barang

$pageTitle = 'Data Barang';

$dataBarang = $conn->query("
  SELECT b.id, b.kode_barang, b.nama_barang, b.harga, b.stok, s.nama AS nama_supplier
  FROM barang AS b
  LEFT JOIN supplier AS s ON b.supplier_id = s.id
  ORDER BY b.id ASC
")->fetchAll();

require_once __DIR__ . "/../partials/header.auth.php";
?>

<main class="container my-8">
  <div class="card-panel p-6">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-6">
      <div>
        <h1 class="fs-2xl mb-1">Data Master Barang</h1>
        <p class="mb-0" style="color: #64748b; font-size: 0.9rem;">Kelola daftar barang, harga, stok, dan supplier.</p>
      </div>
      <a class="btn btn-solid theme-primary" href="<?= routeUrl("/barang/add") ?>">+ Tambah Barang</a>
    </div>

    <?php if (empty($dataBarang)): ?>
      <div class="alert theme-info mb-0">Belum ada data barang.</div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table mb-0">
          <thead>
            <tr>
              <th>No</th>
              <th>Kode Barang</th>
              <th>Nama Barang</th>
              <th>Harga</th>
              <th>Stok</th>
              <th>Nama Supplier</th>
              <th class="text-end">Tindakan</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($dataBarang as $i => $b): ?>
              <tr>
                <td><?= $i + 1 ?></td>
                <td><code><?= sanitazion($b['kode_barang']) ?></code></td>
                <td><?= sanitazion($b['nama_barang']) ?></td>
                <td><?= formatRupiah($b['harga']) ?></td>
                <td><?= sanitazion($b['stok']) ?></td>
                <td><?= sanitazion($b['nama_supplier'] ?? '-') ?></td>
                <td class="text-end">
                  <a class="btn btn-text theme-primary" href="<?= routeUrl("/barang/edit") ?>?id=<?= (int) $b['id'] ?>">Edit</a>
                  <a class="btn btn-text theme-danger"
                    href="<?= routeUrl("/api/barang/delete") ?>?id=<?= (int) $b['id'] ?>"
                    onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?');">Hapus</a>
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
