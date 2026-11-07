<?php
include 'koneksi.php';

$transaksi_id = $_GET['id'] ?? null;
if (!$transaksi_id) {
    header("Location: data.php");
    exit;
}

// Proses Hapus Item Detail & Update Total Otomatis
if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus_item') {
    $barang_id = $_GET['barang_id'];

    try {
        $DBH->beginTransaction();

        $stmt_del = $DBH->prepare("DELETE FROM transaksi_detail WHERE transaksi_id = ? AND barang_id = ?");
        $stmt_del->execute([$transaksi_id, $barang_id]);

        $stmt_sum = $DBH->prepare("SELECT SUM(harga * qty) AS total_baru FROM transaksi_detail WHERE transaksi_id = ?");
        $stmt_sum->execute([$transaksi_id]);
        $result = $stmt_sum->fetch(PDO::FETCH_ASSOC);
        $total_baru = $result['total_baru'] ?? 0;

        $stmt_upd = $DBH->prepare("UPDATE transaksi SET total = ? WHERE id = ?");
        $stmt_upd->execute([$total_baru, $transaksi_id]);

        $DBH->commit();

        echo "<script>alert('Item berhasil dihapus dan total diperbarui!'); window.location='detail.php?id=$transaksi_id';</script>";
        exit;
    } catch (Exception $e) {
        $DBH->rollBack();
        echo "<script>alert('Gagal menghapus: " . $e->getMessage() . "'); window.location='detail.php?id=$transaksi_id';</script>";
        exit;
    }
}

// Ambil data transaksi utama
$stmt_t = $DBH->prepare("SELECT transaksi.*, pelanggan.nama AS nama_pelanggan 
                         FROM transaksi 
                         JOIN pelanggan ON transaksi.pelanggan_id = pelanggan.id 
                         WHERE transaksi.id = ?");
$stmt_t->execute([$transaksi_id]);
$transaksi = $stmt_t->fetch(PDO::FETCH_ASSOC);

// Ambil data detail barang (disiapkan untuk perulangan while)
$stmt_d = $DBH->prepare("SELECT transaksi_detail.*, barang.nama_barang, barang.kode_barang 
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

    <h2>Detail Transaksi #<?= $transaksi['id']; ?></h2>
    <a href="data.php" class="btn btn-secondary">&laquo; Kembali ke Daftar Transaksi</a>

    <p><strong>Tanggal:</strong> <?= $transaksi['waktu_transaksi']; ?><br>
       <strong>Pelanggan:</strong> <?= $transaksi['nama_pelanggan']; ?><br>
       <strong>Keterangan:</strong> <?= $transaksi['keterangan']; ?></p>

    <hr>

    <h3>Daftar Barang Pembelian</h3>
    <a href="tambah_data.php?transaksi_id=<?= $transaksi_id; ?>" class="btn btn-success">+ Tambah Barang ke Transaksi Ini</a>

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
        while ($row = $stmt_d->fetch()) { 
            $ada_data = true;
            $subtotal = $row['harga'] * $row['qty'];
        ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= $row['kode_barang']; ?></td>
            <td><?= $row['nama_barang']; ?></td>
            <td>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
            <td><?= $row['qty']; ?></td>
            <td>Rp <?= number_format($subtotal, 0, ',', '.'); ?></td>
            <td>
                <a href="detail.php?id=<?= $transaksi_id; ?>&aksi=hapus_item&barang_id=<?= $row['barang_id']; ?>" 
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
            <td colspan="2"><strong>Rp <?= number_format($transaksi['total'], 0, ',', '.'); ?></strong></td>
        </tr>
        <?php } else { ?>
        <tr>
            <td colspan="7" style="text-align: center;">Belum ada item barang pada transaksi ini.</td>
        </tr>
        <?php } ?>
    </table>

</body>
</html>