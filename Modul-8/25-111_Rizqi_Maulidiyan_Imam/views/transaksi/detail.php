<?php
cekAkses([1, 2]);

$transaksi_id = (int)($_GET['transaksi_id'] ?? 0);

$master = $DBH->prepare("
    SELECT t.total, t.waktu_transaksi, p.nama
    FROM transaksi t
    JOIN pelanggan p ON t.pelanggan_id = p.id
    WHERE t.id = ?
");
$master->execute([$transaksi_id]);
$data_master = $master->fetch();

if (!$data_master) {
    set_pesan('Transaksi tidak ditemukan.', 'danger');
    redirect('/transaksi');
}

$detail = $DBH->prepare("
    SELECT b.kode_barang, b.nama_barang, td.harga, td.qty
    FROM barang b
    JOIN transaksi_detail td ON b.id = td.barang_id
    WHERE td.transaksi_id = ?
");
$detail->execute([$transaksi_id]);
$rows = $detail->fetchAll();

require_once __DIR__ . '/../partials/header.auth.php';
?>

<main class="container my-4">
    <h2>Detail Transaksi (ID Transaksi: <?= $transaksi_id; ?>)</h2>
    <p class="text-muted">
        Tanggal: <?= e($data_master['waktu_transaksi']); ?> |
        Pelanggan: <?= e($data_master['nama']); ?>
    </p>

    <?php tampil_pesan(); ?>

    <div class="mb-3">
        <a class="btn btn-primary" href="<?= url('/transaksi/detail/tambah'); ?>?transaksi_id=<?= $transaksi_id; ?>">Tambah Item Barang</a>
        <a class="btn btn-secondary" href="<?= url('/transaksi'); ?>">Kembali</a>
    </div>

    <table class="table table-bordered bg-white">
        <tr>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Harga</th>
            <th>Qty</th>
            <th>Subtotal</th>
        </tr>
        <?php foreach ($rows as $dt): ?>
            <tr>
                <td><?= e($dt['kode_barang']); ?></td>
                <td><?= e($dt['nama_barang']); ?></td>
                <td>Rp <?= number_format($dt['harga'], 0, ',', '.'); ?></td>
                <td><?= $dt['qty']; ?></td>
                <td>Rp <?= number_format($dt['harga'] * $dt['qty'], 0, ',', '.'); ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$rows): ?>
            <tr><td colspan="5">Belum ada detail barang pada transaksi ini.</td></tr>
        <?php endif; ?>
        <tr class="table-warning fw-bold">
            <td colspan="4" class="text-end">TOTAL</td>
            <td>Rp <?= number_format($data_master['total'], 0, ',', '.'); ?></td>
        </tr>
    </table>
</main>

<?php require_once __DIR__ . '/../partials/footer.auth.php'; ?>