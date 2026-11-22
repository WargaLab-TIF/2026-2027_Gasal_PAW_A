<?php
cekAkses([1]);

$data = $DBH->query("
    SELECT b.*, s.nama AS nama_supplier
    FROM barang b
    JOIN supplier s ON b.supplier_id = s.id
    ORDER BY b.id ASC
")->fetchAll();

require_once __DIR__ . '/../partials/header.auth.php';
?>

<main class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Data Barang</h2>
        <a href="<?= url('/barang/tambah'); ?>" class="btn btn-primary">Tambah Barang</a>
    </div>

    <?php tampil_pesan(); ?>

    <table class="table table-bordered bg-white text-center align-middle">
        <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Nama Barang</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Supplier</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($data as $no => $row): ?>
            <tr>
                <td><?= $no + 1; ?></td>
                <td><?= e($row['kode_barang']); ?></td>
                <td><?= e($row['nama_barang']); ?></td>
                <td>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                <td><?= e($row['stok']); ?></td>
                <td><?= e($row['nama_supplier']); ?></td>
                <td>
                    <a href="<?= url('/barang/edit'); ?>?id=<?= $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                    <form method="POST" action="<?= url('/barang/hapus'); ?>" class="d-inline"
                          onsubmit="return confirm('Yakin hapus barang ini?');">
                        <input type="hidden" name="id" value="<?= $row['id']; ?>">
                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</main>

<?php require_once __DIR__ . '/../partials/footer.auth.php'; ?>