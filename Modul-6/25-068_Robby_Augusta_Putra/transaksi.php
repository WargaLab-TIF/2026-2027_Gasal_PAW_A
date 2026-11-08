<?php
include "koneksi.php";

$stmt = $db->prepare("SELECT transaksi.*, pelanggan.nama
                      FROM transaksi
                      JOIN pelanggan ON transaksi.pelanggan_id = pelanggan.id");
$stmt->execute();
$data = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Transaksi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <a href="transaksi.php" class="btn btn-transaksi">Transaksi</a> | <a href="barang.php" class="btn btn-transaksi">Barang</a>

    <h2>Data Transaksi</h2>
    <table border="1" cellpadding="5">
        <tr>
            <th>ID</th>
            <th>Tanggal</th>
            <th>Pelanggan</th>
            <th>Keterangan</th>
            <th>Total</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($data as $row) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['waktu_transaksi']; ?></td>
            <td><?php echo $row['nama']; ?></td>
            <td><?php echo $row['keterangan']; ?></td>
            <td><?php echo $row['total']; ?></td>
            <td><a href="detail.php?id=<?php echo $row['id']; ?>" class="btn btn-detail">Detail</a></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
