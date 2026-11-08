<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html>
<head><title>Data Barang</title></head>
<body style="font-family: Arial, sans-serif; margin: 20px;">
    <h2>Master Data Barang</h2>
    <a href="transaksi.php"><= Ke Halaman Transaksi</a><br><br>
    
    <table border="1" cellpadding="8" cellspacing="0" width="80%">
        <tr style="background-color: #f2f2f2;">
            <th>Kode</th>
            <th>Nama Barang</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
        <?php
        $query = mysqli_query($koneksi, "SELECT * FROM barang ORDER BY id ASC");
        while ($data = mysqli_fetch_assoc($query)) {
        ?>
            <tr>
                <td align='center'><?= $data['kode_barang'] ?></td>
                <td><?= $data['nama_barang'] ?></td>
                <td align='right'>Rp <?= number_format($data['harga'], 0, ',', '.') ?></td>
                <td align='center'><?= $data['stok'] ?></td>
                <td align='center'>
                    <a href="hapus_barang.php?id=<?= $data['id'] ?>" 
                       onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>