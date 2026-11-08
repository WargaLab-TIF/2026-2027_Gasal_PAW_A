<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Daftar Transaksi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="navbar"></div>
    <div class="container">
        <h1>Daftar Transaksi</h1>
        <a href="barang.php" class="btn-tambah" style="margin-right: 10px;">Master Barang</a>
        <table>
            <tr>
                <th>ID</th>
                <th>Waktu Transaksi</th>
                <th>Pelanggan</th>  
                <th>Keterangan</th>
                <th>Total Transaksi</th>
                <th>Tindakan</th>
            </tr>
            <?php
            $query = "SELECT t.*, p.nama FROM transaksi t JOIN pelanggan p ON t.pelanggan_id = p.id ORDER BY t.id ASC";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['waktu_transaksi'] . "</td>";
                echo "<td>" . $row['nama'] . "</td>";
                echo "<td>" . $row['keterangan'] . "</td>";
                echo "<td>Rp " . number_format($row['total'], 0, ',', '.') . "</td>";
                echo "<td><a href='detail.php?id=" . $row['id'] . "' class='btn-action btn-simpan'>Lihat / Isi Detail</a></td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>