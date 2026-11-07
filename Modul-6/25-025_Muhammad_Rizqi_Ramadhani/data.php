<?php 
require_once 'koneksi.php'; 
$stmt = $DBH->query("SELECT t.*, p.nama AS nama_pelanggan
    FROM transaksi t
    JOIN pelanggan p ON t.pelanggan_id = p.id
    ORDER BY t.waktu_transaksi DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Transaksi</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn { padding: 6px 10px; color: white; text-decoration: none; border-radius: 4px; display: inline-block; font-size: 14px; }
        .btn-primary { background: #007bff; }
        .btn-success { background: #28a745; margin-bottom: 15px; }
        .btn-danger { background: #dc3545; }
    </style>
</head>
<body>
    <h2>Data Transaksi</h2>
    <a href="barang.php" class="btn btn-primary">Kelola Barang</a>
    <br><br>
    <table>
        <tr>
            <th>ID</th>
            <th>Tanggal</th>
            <th>Pelanggan</th>
            <th>Keterangan</th>
            <th>Total</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = $stmt->fetch()) { ?>
        <tr>
            <td>#<?= $row['id']; ?></td>
            <td><?= $row['waktu_transaksi']; ?></td>
            <td><?= $row['nama_pelanggan']; ?></td>
            <td><?= $row['keterangan']; ?></td>
            <td>Rp <?= number_format($row['total'], 0, ',', '.'); ?></td>
            <td><a href="detail.php?id=<?= $row['id']; ?>" class="btn btn-primary">Lihat Detail</a></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>