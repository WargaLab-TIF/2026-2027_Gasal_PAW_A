<?php
cekAkses([1, 2]);

$data = $DBH->query("
    SELECT t.*, p.nama
    FROM transaksi t
    JOIN pelanggan p ON t.pelanggan_id = p.id
    ORDER BY t.id ASC
")->fetchAll();

require_once __DIR__ . '/../partials/header.auth.php';
?>

<main class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Master Data Transaksi</h2>
        <a href="<?= url('/transaksi/baru'); ?>" class="btn btn-primary">Transaksi Baru</a>
    </div>

    <?php tampil_pesan(); ?>

    <table class="table table-bordered bg-white text-center">
        <tr>
            <th>ID</th>
            <th>Tanggal</th>
            <th>Pelanggan</th>
            <th>Keterangan</th>
            <th>Total</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($data as $row): ?>
            <tr>
                <td><?= $row['id']; ?></td>
                <td><?= e($row['waktu_transaksi']); ?></td>
                <td><?= e($row['nama']); ?></td>
                <td><?= e($row['keterangan']); ?></td>
                <td>Rp <?= number_format($row['total'], 0, ',', '.'); ?></td>
                <td>
                    <a class="btn btn-sm btn-info"
                       href="<?= url('/transaksi/detail'); ?>?transaksi_id=<?= $row['id']; ?>">Lihat Detail</a>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$data): ?>
            <tr><td colspan="6">Belum ada transaksi.</td></tr>
        <?php endif; ?>
    </table>
</main>

<?php require_once __DIR__ . '/../partials/footer.auth.php'; ?>