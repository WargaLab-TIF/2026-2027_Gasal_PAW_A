<?php
// daftar pelanggan

$pageTitle = 'Data Pelanggan';
$dataPelanggan = $conn->query("
  SELECT * FROM pelanggan ORDER BY CAST(id AS UNSIGNED) ASC, id ASC
")->fetchAll();

require_once __DIR__ . "/../partials/header.auth.php";
?>

<main class="container my-8">
  <div class="card-panel p-6">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-6">
      <div>
        <h1 class="fs-2xl mb-1">Data Master Pelanggan</h1>
        <p class="mb-0" style="color: #64748b; font-size: 0.9rem;">Kelola daftar pelanggan yang terdaftar di sistem.</p>
      </div>
      <a class="btn btn-solid theme-primary" href="<?= routeUrl("/pelanggan/add") ?>">+ Tambah Pelanggan</a>
    </div>

    <?php if (empty($dataPelanggan)): ?>
      <div class="alert theme-info mb-0">Belum ada data pelanggan.</div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table mb-0">
          <thead>
            <tr>
              <th>No</th>
              <th>ID</th>
              <th>Nama Pelanggan</th>
              <th>Jenis Kelamin</th>
              <th>Telp</th>
              <th>Alamat</th>
              <th class="text-end">Tindakan</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($dataPelanggan as $i => $p): ?>
              <tr>
                <td><?= $i + 1 ?></td>
                <td><code><?= sanitazion($p['id']) ?></code></td>
                <td><?= sanitazion($p['nama']) ?></td>
                <td><?= $p['jenis_kelamin'] === 'L' ? 'Laki-laki (L)' : 'Perempuan (P)' ?></td>
                <td><?= sanitazion($p['telp']) ?></td>
                <td><?= sanitazion($p['alamat']) ?></td>
                <td class="text-end">
                  <a class="btn btn-text theme-primary"
                    href="<?= routeUrl("/pelanggan/edit") ?>?id=<?= urlencode($p['id']) ?>">Edit</a>
                  <a class="btn btn-text theme-danger"
                    href="<?= routeUrl("/api/pelanggan/delete") ?>?id=<?= urlencode($p['id']) ?>"
                    onclick="return confirm('Anda yakin akan menghapus pelanggan ini?');">Hapus</a>
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
