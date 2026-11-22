<?php
cekAkses([1]); 

$data = $DBH->query("SELECT * FROM supplier ORDER BY id ASC")->fetchAll();

require_once __DIR__ . '/../partials/header.auth.php';
?>

<main class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Data Supplier</h2>
        <a href="<?= url('/supplier/tambah'); ?>" class="btn btn-primary">Tambah Supplier</a>
    </div>

    <?php tampil_pesan(); ?>

    <table class="table table-bordered bg-white text-center align-middle">
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Telepon</th>
            <th>Alamat</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($data as $no => $row): ?>
            <tr>
                <td><?= $no + 1; ?></td>
                <td><?= e($row['nama']); ?></td>
                <td><?= e($row['telp']); ?></td>
                <td><?= e($row['alamat']); ?></td>
                <td>
                    <a href="<?= url('/supplier/edit'); ?>?id=<?= $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                    <form method="POST" action="<?= url('/supplier/hapus'); ?>" class="d-inline"
                          onsubmit="return confirm('Yakin hapus supplier ini?');">
                        <input type="hidden" name="id" value="<?= $row['id']; ?>">
                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</main>

<?php require_once __DIR__ . '/../partials/footer.auth.php'; ?>
