<?php
cekAkses([1]); // hanya owner

$data = $DBH->query("SELECT * FROM pelanggan ORDER BY id ASC")->fetchAll();

require_once __DIR__ . '/../partials/header.auth.php';
?>

<main class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Data Pelanggan</h2>
        <a href="<?= url('/pelanggan/tambah'); ?>" class="btn btn-primary">Tambah Pelanggan</a>
    </div>

    <?php tampil_pesan(); ?>

    <table class="table table-bordered bg-white text-center align-middle">
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Jenis Kelamin</th>
            <th>Telepon</th>
            <th>Alamat</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($data as $no => $row): ?>
            <tr>
                <td><?= $no + 1; ?></td>
                <td><?= e($row['nama']); ?></td>
                <td><?= $row['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan'; ?></td>
                <td><?= e($row['telp']); ?></td>
                <td><?= e($row['alamat']); ?></td>
                <td>
                    <a href="<?= url('/pelanggan/edit'); ?>?id=<?= $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                    <form method="POST" action="<?= url('/pelanggan/hapus'); ?>" class="d-inline"
                          onsubmit="return confirm('Yakin hapus pelanggan ini?');">
                        <input type="hidden" name="id" value="<?= $row['id']; ?>">
                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</main>

<?php require_once __DIR__ . '/../partials/footer.auth.php'; ?>
