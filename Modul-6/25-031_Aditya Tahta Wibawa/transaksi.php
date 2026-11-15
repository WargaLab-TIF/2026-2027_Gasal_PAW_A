<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html>
<head><title>Data Transaksi (Master)</title></head>
<body style="font-family: Arial, sans-serif; margin: 20px;">
    <h2>Daftar Transaksi Pembelian</h2>
    <a href="barang.php">=> Ke Halaman Kelola Barang</a><br><br>

    <table border="1" cellpadding="8" cellspacing="0" width="80%">
        <tr style="background-color: #f2f2f2;">
            <th>ID Transaksi</th>
            <th>Tanggal</th>
            <th>Keterangan</th>
            <th>Total Pembayaran</th>
            <th>Aksi</th>
        </tr>
        <?php
        $query = mysqli_query($koneksi, "SELECT * FROM transaksi ORDER BY id ASC");
        while ($data = mysqli_fetch_assoc($query)) {
            echo "<tr>
                    <td align='center'>TRX-{$data['id']}</td>
                    <td align='center'>{$data['waktu_transaksi']}</td>
                    <td>{$data['keterangan']}</td>
                    <td align='right'>Rp " . number_format($data['total'], 0, ',', '.') . "</td>
                    <td align='center'>
                        <a href='detail_transaksi.php?id={$data['id']}'>Kelola Detail Data</a>
                    </td>
                  </tr>";
        }
        ?>
    </table>
</body>
</html>