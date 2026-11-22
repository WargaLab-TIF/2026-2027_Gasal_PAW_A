<?php
require_once __DIR__ . "/koneksi.php";
require_once __DIR__ . "/auth.php";
requireLevel([1, 2]);

$transaksi_id = $_GET['id'] ?? null;
if (!$transaksi_id) {
    header("Location: transaksi.php");
    exit;
}

if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus_item') {
    $barang_id = $_GET['barang_id'] ?? null;

    if ($barang_id !== null && $barang_id !== '') {
        try {
            $conn->beginTransaction();

            $stmt_del = $conn->prepare("DELETE FROM transaksi_detail WHERE transaksi_id = ? AND barang_id = ?");
            $stmt_del->execute([$transaksi_id, $barang_id]);

            $stmt_sum = $conn->prepare("SELECT SUM(harga * qty) AS total_baru FROM transaksi_detail WHERE transaksi_id = ?");
            $stmt_sum->execute([$transaksi_id]);
            $result = $stmt_sum->fetch(PDO::FETCH_ASSOC);
            $total_baru = $result['total_baru'] ?? 0;

            $stmt_upd = $conn->prepare("UPDATE transaksi SET total = ? WHERE id = ?");
            $stmt_upd->execute([$total_baru, $transaksi_id]);

            $conn->commit();

            $id_url = urlencode((string) $transaksi_id);
            echo "<script>alert('Item berhasil dihapus dan total diperbarui!'); window.location='detail.php?id={$id_url}';</script>";
            exit;
        } catch (Exception $e) {
            $conn->rollBack();
            $id_url = urlencode((string) $transaksi_id);
            $msg = htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
            echo "<script>alert('Gagal menghapus: {$msg}'); window.location='detail.php?id={$id_url}';</script>";
            exit;
        }
    }
}

$stmt_t = $conn->prepare("SELECT transaksi.*, pelanggan.nama AS nama_pelanggan 
                         FROM transaksi 
                         LEFT JOIN pelanggan ON transaksi.pelanggan_id = pelanggan.id 
                         WHERE transaksi.id = ?");
$stmt_t->execute([$transaksi_id]);
$transaksi = $stmt_t->fetch(PDO::FETCH_ASSOC);

if (!$transaksi) {
    echo "<script>alert('Data transaksi tidak ditemukan.'); window.location='data.php';</script>";
    exit;
}

$stmt_d = $conn->prepare("SELECT transaksi_detail.*, barang.nama_barang, barang.kode_barang 
                         FROM transaksi_detail 
                         JOIN barang ON transaksi_detail.barang_id = barang.id 
                         WHERE transaksi_detail.transaksi_id = ?");
$stmt_d->execute([$transaksi_id]);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Transaksi</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn { padding: 6px 10px; color: white; text-decoration: none; border-radius: 4px; display: inline-block; font-size: 14px; }
        .btn-secondary { background: #6c757d; margin-bottom: 15px; }
        .btn-success { background: #28a745; margin-bottom: 15px; }
        .btn-danger { background: #dc3545; padding: 4px 8px; font-size: 12px; }
    </style>
</head>
<body>
    <?php require_once __DIR__ . "/navbar.php"; ?>

    <h2>Detail Transaksi #<?= htmlspecialchars((string) $transaksi['id']); ?></h2>
    <a href="transaksi.php" class="btn btn-secondary">&laquo; Kembali ke Daftar Transaksi</a>

    <p><strong>Tanggal:</strong> <?= htmlspecialchars((string) $transaksi['waktu_transaksi']); ?><br>
       <strong>Pelanggan:</strong> <?= htmlspecialchars((string) ($transaksi['nama_pelanggan'] ?? '-')); ?><br>
       <strong>Keterangan:</strong> <?= htmlspecialchars((string) ($transaksi['keterangan'] ?? '')); ?></p>

    <hr>

    <h3>Daftar Barang Pembelian</h3>
    <a href="tambah_data.php?transaksi_id=<?= urlencode((string) $transaksi_id); ?>" class="btn btn-success">+ Tambah Barang ke Transaksi Ini</a>

    <table>
        <tr>
            <th>No</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Harga Satuan</th>
            <th>Qty</th>
            <th>Subtotal</th>
            <th>Aksi</th>
        </tr>
        <?php 
        $no = 1; 
        $ada_data = false;
        while ($row = $stmt_d->fetch(PDO::FETCH_ASSOC)) { 
            $ada_data = true;
            $subtotal = (int) $row['harga'] * (int) $row['qty'];
        ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars((string) $row['kode_barang']); ?></td>
            <td><?= htmlspecialchars((string) $row['nama_barang']); ?></td>
            <td>Rp <?= number_format((int) $row['harga'], 0, ',', '.'); ?></td>
            <td><?= htmlspecialchars((string) $row['qty']); ?></td>
            <td>Rp <?= number_format($subtotal, 0, ',', '.'); ?></td>
            <td>
                <a href="detail.php?id=<?= urlencode((string) $transaksi_id); ?>&aksi=hapus_item&barang_id=<?= urlencode((string) $row['barang_id']); ?>" 
                   class="btn btn-danger" 
                   onclick="return confirm('Yakin ingin menghapus barang ini dari transaksi?')">
                   Hapus
                </a>
            </td>
        </tr>
        <?php } ?>

        <?php if ($ada_data) { ?>
        <tr>
            <td colspan="5" style="text-align: right;"><strong>Total Keseluruhan:</strong></td>
            <td colspan="2"><strong>Rp <?= number_format((int) $transaksi['total'], 0, ',', '.'); ?></strong></td>
        </tr>
        <?php } else { ?>
        <tr>
            <td colspan="7" style="text-align: center;">Belum ada item barang pada transaksi ini.</td>
        </tr>
        <?php } ?>
    </table>

</body>
</html>