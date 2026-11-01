<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Master Supplier</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header-container { display: flex; justify-content: space-between; align-items: center; }
        h2 { color: #347C98; }
        .btn-tambah { background-color: #79B84C; color: white; padding: 10px 15px; text-decoration: none; border-radius: 4px; border: none;}
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #dceef7; }
        .btn-edit { background-color: #E67E22; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; }
        .btn-hapus { background-color: #C0392B; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; }
    </style>
</head>
<body>
    <div class="header-container">
        <h2>Data Master Supplier</h2>
        <a href="tambah.php" class="btn-tambah">Tambah Data</a>
    </div>
    
    <table>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Telp</th>
            <th>Alamat</th>
            <th>Tindakan</th>
        </tr>
        <?php
        $no = 1;
        $query = mysqli_query($conn, "SELECT * FROM supplier ORDER BY id ASC");
        while ($row = mysqli_fetch_assoc($query)) {
        ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= $row['nama']; ?></td>
            <td><?= $row['telp']; ?></td>
            <td><?= $row['alamat']; ?></td>
            <td>
                <a href="edit.php?id=<?= $row['id']; ?>" class="btn-edit">Edit</a>
                <a href="hapus.php?id=<?= $row['id']; ?>" class="btn-hapus" onclick="return confirm('Anda yakin akan menghapus supplier ini?')">Hapus</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>