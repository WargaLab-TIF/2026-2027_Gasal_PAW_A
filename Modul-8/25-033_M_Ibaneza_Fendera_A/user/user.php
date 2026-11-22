<?php
session_start();
require_once '../koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location: ../index.php");
    exit();
}

if ($_SESSION['level'] != 1) {
    echo "<script>alert('Akses ditolak! Halaman ini khusus Owner.'); window.location='../kasir/home.php';</script>";
    exit();
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    $hapus = $DBH->prepare("DELETE FROM user WHERE id_user = ?");
    $hapus->execute([$id]);
    header('location: user.php?status=berhasil');
    exit;
}

if (isset($_GET['status'])) {
    if ($_GET['status'] == 'berhasil') {
        echo "<script>alert('Data user berhasil dihapus!'); window.location='user.php'</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Master Data User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            background-color: white;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #fff3cd; 
        }
        button a {
            text-decoration: none;
            color: white;
        }
        .btn-hapus {
            background-color: red;
            border-radius: 6px;
            padding: 6px 12px;
            border: none;
        }
        .btn-edit {
            background-color: orange;
            border-radius: 6px;
            padding: 6px 12px;
            border: none;
        }
        .btn-tambah {
            background-color: #6f42c1; 
            color: white;
            border-radius: 5px;
            text-decoration: none;
            padding: 10px;
            float: right;
            margin-bottom: 10px;
            border: none;
        }
    </style>
</head>
<body>
    <?php include '../navbar.php'; ?>

    <div class="container">
        <h2>Master Data User</h2>
        <button class="btn-tambah"><a href="tambah.php">Tambah User</a></button>

        <table>
            <tr>
                <th>No</th>
                <th>Username</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>No. HP</th>
                <th>Level</th>
                <th>Aksi</th>
            </tr>
            <?php
            $no = 1;
            $data = $DBH->prepare("SELECT * FROM user");
            $data->execute();
            while ($row = $data->fetch()) {
            ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $row['username']; ?></td>
                    <td><?= $row['nama']; ?></td>
                    <td><?= $row['alamat']; ?></td>
                    <td><?= $row['hp']; ?></td>
                    <td><?= ($row['level'] == 1) ? 'Owner' : 'Kasir'; ?></td>
                    <td>
                        <button class="btn-edit"><a href="edit.php?id=<?= $row['id_user']; ?>">Edit</a></button>
                        <button class="btn-hapus"><a href="user.php?hapus=<?= $row['id_user']; ?>" onclick="return confirm('Yakin menghapus user ini?')">Hapus</a></button>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 