<?php
include 'koneksi.php';

$query = mysqli_query($koneksi, "
    SELECT * FROM barang ORDER BY id ASC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Barang</title>
</head>
<body>

<h2>Data Master Barang</h2>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>No</th>
        <th>Kode Barang</th>
        <th>Nama Barang</th>
        <th>Harga</th>
        <th>Stok</th>
        <th>Tindakan</th>
    </tr>

    <?php
    $no = 1;
    while ($data = mysqli_fetch_assoc($query)) {
    ?>
    <tr>
        <td><?php echo $no++; ?></td>
        <td><?php echo htmlspecialchars($data['kode_barang']); ?></td>
        <td><?php echo htmlspecialchars($data['nama_barang']); ?></td>
        <td>Rp <?php echo number_format($data['harga'], 0, ',', '.'); ?></td>
        <td><?php echo $data['stok']; ?></td>
        <td>
            <a href="hapus_barang.php?id=<?php echo $data['id']; ?>"
               onclick="return confirm('Yakin ingin menghapus barang ini?')">
                Hapus
            </a>
        </td>
    </tr>
    <?php } ?>

</table>

<br>
<a href="index.php">Kembali ke Transaksi</a>

</body>
</html>