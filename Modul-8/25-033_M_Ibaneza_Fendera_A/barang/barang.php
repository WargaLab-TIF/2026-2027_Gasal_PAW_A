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

if (isset($_GET['barang_id'])) {
    $id = $_GET['barang_id'];

    $cek = $DBH->prepare("SELECT * FROM transaksi_detail WHERE barang_id = ?");
    $cek->execute([$id]);

    if ($cek->rowCount() > 0) {
        header('location: barang.php?status=gagal');
        exit;
    } else {
        $hapus = $DBH->prepare("DELETE FROM barang WHERE id = ?");
        $hapus->execute([$id]);
        header('location: barang.php?status=berhasil');
        exit;
    }
}

if (isset($_GET['status'])) {
    if ($_GET['status'] == 'gagal') {
        echo "<script>alert('Barang tidak dapat dihapus karena masih digunakan dalam transaksi!'); window.location='barang.php'</script>";
        exit();
    } elseif ($_GET['status'] == 'berhasil') {
        echo "<script>alert('Data barang berhasil dihapus!'); window.location='barang.php'</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Master Data Barang</title>
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
            background-color: #eee;
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
            background-color: green; color: white; border-radius: 5px;
            text-decoration: none; padding: 10px; float: right; margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <?php include '../navbar.php'; ?>

    <div class="container">
        <h2>Master Data Barang</h2>
        <button class="btn-tambah"><a href="tambah.php">Tambah Barang</a></button>

        <table>
            <tr>
                <th>ID</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
            <?php
            $data = $DBH->prepare("SELECT * FROM barang");
            $data->execute();
            while ($row = $data->fetch()) {
            ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= $row['kode_barang']; ?></td>
                    <td><?= $row['nama_barang']; ?></td>
                    <td><?= $row['harga']; ?></td>
                    <td><?= $row['stok']; ?></td>
                    <td>
                        <button class="btn-edit"><a href="edit.php?id=<?= $row['id']; ?>">Edit</a></button>
                        <button class="btn-hapus"><a href="barang.php?barang_id=<?= $row['id']; ?>" onclick="return confirm('Yakin menghapus data ini?')">Hapus</a></button>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>