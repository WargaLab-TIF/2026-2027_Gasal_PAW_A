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

    $cek = $DBH->prepare("SELECT * FROM barang WHERE supplier_id = ?");
    $cek->execute([$id]);

    if ($cek->rowCount() > 0) {
        header('location: supplier.php?status=gagal');
        exit;
    } else {
        $hapus = $DBH->prepare("DELETE FROM supplier WHERE id = ?");
        $hapus->execute([$id]);
        header('location: supplier.php?status=berhasil');
        exit;
    }
}

if (isset($_GET['status'])) {
    if ($_GET['status'] == 'gagal') {
        echo "<script>alert('Supplier tidak dapat dihapus karena masih memiliki data barang!'); window.location='supplier.php'</script>";
        exit();
    } elseif ($_GET['status'] == 'berhasil') {
        echo "<script>alert('Data supplier berhasil dihapus!'); window.location='supplier.php'</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Master Data Supplier</title>
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
            background-color: #cfe2ff;
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
            background-color: #0d6efd; 
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
        <h2>Master Data Supplier</h2>
        <button class="btn-tambah"><a href="tambah.php">Tambah Supplier</a></button>

        <table>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Telp</th>
                <th>Alamat</th>
                <th>Aksi</th>
            </tr>
            <?php
            $no = 1;
            $data = $DBH->prepare("SELECT * FROM supplier");
            $data->execute();
            while ($row = $data->fetch()) {
            ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $row['nama']; ?></td>
                    <td><?= $row['telp']; ?></td>
                    <td><?= $row['alamat']; ?></td>
                    <td>
                        <button class="btn-edit"><a href="edit.php?id=<?= $row['id']; ?>">Edit</a></button>
                        <button class="btn-hapus"><a href="supplier.php?hapus=<?= $row['id']; ?>" onclick="return confirm('Yakin menghapus data ini?')">Hapus</a></button>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>