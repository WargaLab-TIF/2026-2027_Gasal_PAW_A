<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Master Barang</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="navbar"></div>
    <div class="container">
        <h1>Data Master Barang</h1>
        <a href="index.php" class="btn-tambah" style="background-color: #004b87;">Kembali ke Transaksi</a>
        <table>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Tindakan</th>
            </tr>
            <?php
            $query = "SELECT * FROM barang";
            $result = mysqli_query($conn, $query);
            $no = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $no++ . "</td>";
                echo "<td>" . $row['kode_barang'] . "</td>";
                echo "<td>" . $row['nama_barang'] . "</td>";
                echo "<td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>";
                echo "<td>" . $row['stok'] . "</td>";
                echo "<td>
                        <a href='hapus_barang.php?id=" . $row['id'] . "' class='btn-action btn-hapus' onclick=\"return confirm('Hapus barang ini?');\">Hapus</a>
                    </td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>