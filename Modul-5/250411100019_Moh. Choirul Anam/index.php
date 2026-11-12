<?php
include "koneksi.php";
$hasil = mysqli_query($koneksi, "SELECT * FROM supplier ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Master Supplier</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="navbar"></div>
<div class="container">
    <div class="header">
        <h2>Data Master Supplier</h2>
        <a href="tambah.php" class="btn btn-hijau">Tambah Data</a>
    </div>
    <table>
        <thead>
            <tr><th>No</th><th>Nama</th><th>Telp</th><th>Alamat</th><th>Tindakan</th></tr>
        </thead>
        <tbody>
        <?php $no = 1; while ($row = mysqli_fetch_assoc($hasil)) { ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= htmlspecialchars($row['telp']) ?></td>
                <td><?= htmlspecialchars($row['alamat']) ?></td>
                <td>
                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-oranye">Edit</a>
                    <a href="hapus.php?id=<?= $row['id'] ?>" class="btn btn-merah"
                       onclick="return confirm('Anda yakin akan menghapus supplier ini?');">Hapus</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>
