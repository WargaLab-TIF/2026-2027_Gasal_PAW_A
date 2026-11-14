<?php
include 'koneksi.php';

$query = mysqli_query($koneksi, "
    SELECT transaksi.*, pelanggan.nama AS nama_pelanggan
    FROM transaksi
    LEFT JOIN pelanggan
    ON transaksi.pelanggan_id = pelanggan.id
    ORDER BY transaksi.id ASC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Transaksi</title>
</head>
<body>

<h2>Data Transaksi</h2>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>No</th>
        <th>Tanggal</th>
        <th>Pelanggan</th>
        <th>Keterangan</th>
        <th>Total</th>
        <th>Tindakan</th>
    </tr>

    <?php
    $no = 1;
    while ($data = mysqli_fetch_assoc($query)) {
    ?>
    <tr>
        <td><?php echo $no++; ?></td>
        <td><?php echo $data['waktu_transaksi']; ?></td>
        <td><?php echo htmlspecialchars($data['nama_pelanggan'] ?? '-'); ?></td>
        <td><?php echo htmlspecialchars($data['keterangan']); ?></td>
        <td>Rp <?php echo number_format($data['total'], 0, ',', '.'); ?></td>
        <td>
            <a href="detail_transaksi.php?id=<?php echo $data['id']; ?>">
                Lihat Detail
            </a>
        </td>
    </tr>
    <?php } ?>

</table>

<br>
<a href="barang.php">Kelola Data Barang</a>

</body>
</html> 