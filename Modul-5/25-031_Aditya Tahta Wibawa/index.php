<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Master Supplier</title>
</head>
<body>
    <h2>Data Master Supplier</h2>
    <a href="tambah.php"><button style="background-color: #5cb85c; color: white; padding: 5px 10px;">Tambah Data</button></a>
    <br><br>
    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; text-align: left;">
        <tr style="background-color: #d9edf7;">
            <th>No</th>
            <th>Nama</th>
            <th>Telp</th>
            <th>Alamat</th>
            <th>Tindakan</th>
        </tr>
        <?php
        $no = 1;
        $sql = "SELECT id, nama, telp, alamat FROM supplier";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            
            while($row = mysqli_fetch_assoc($result)) {
        ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= $row['nama']; ?></td>
            <td><?= $row['telp']; ?></td>
            <td><?= $row['alamat']; ?></td>
            <td>
                
                <a href="edit.php?id=<?= $row['id']; ?>">
                    <button style="background-color: #f0ad4e; color: white; border: none; padding: 5px 10px; cursor: pointer;">Edit</button>
                </a>
                
                
                <a href="hapus.php?id=<?= $row['id']; ?>" onclick="return confirm('Anda yakin akan menghapus supplier ini?');">
                    <button style="background-color: #c9302c; color: white; border: none; padding: 5px 10px; cursor: pointer;">Hapus</button>
                </a>
            </td>
        </tr>
        <?php 
            }
        } else {
            echo "<tr><td colspan='5' style='text-align:center;'>0 results</td></tr>";
        }
        mysqli_close($conn); 
        ?>
    </table>
</body>
</html>