<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Master Supplier</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="navbar"></div>
    
    <div class="container">
        <h1>Data Master Supplier</h1>
        <a href="tambah.php" class="btn-tambah">Tambah Data</a>
        
        <table>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Telp</th>
                <th>Alamat</th>
                <th>Tindakan</th>
            </tr>
            <?php
            $query = "SELECT * FROM supplier";
            $result = mysqli_query($conn, $query);
            $no = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $no++ . "</td>";
                echo "<td>" . $row['nama'] . "</td>";
                echo "<td>" . $row['telp'] . "</td>";
                echo "<td>" . $row['alamat'] . "</td>";
                echo "<td>
                        <a href='edit.php?id=" . $row['id'] . "' class='btn-action btn-edit'>Edit</a> 
                        <a href='hapus.php?id=" . $row['id'] . "' class='btn-action btn-hapus' onclick=\"return confirm('Anda yakin akan menghapus supplier ini?');\">Hapus</a>
                    </td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>