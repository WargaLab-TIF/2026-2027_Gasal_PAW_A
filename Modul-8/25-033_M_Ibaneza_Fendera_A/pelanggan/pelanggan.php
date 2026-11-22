<?php
session_start();
require_once '../koneksi.php';

// Proteksi Login & Level Owner
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location: ../index.php");
    exit();
}
if ($_SESSION['level'] != 1) {
    echo "<script>alert('Akses ditolak! Halaman ini khusus Owner.'); window.location='../kasir/home.php';</script>";
    exit();
}

// Proses Hapus Pelanggan (Cek apakah dipakai di tabel transaksi)
if (isset($_GET['pelanggan_id'])) {
    $id = $_GET['pelanggan_id'];

    $cek = $DBH->prepare("SELECT * FROM transaksi WHERE pelanggan_id = ?");
    $cek->execute([$id]);

    if ($cek->rowCount() > 0) {
        header('location: pelanggan.php?status=gagal');
        exit;
    } else {
        $hapus = $DBH->prepare("DELETE FROM pelanggan WHERE id = ?");
        $hapus->execute([$id]);
        header('location: pelanggan.php?status=berhasil');
        exit;
    }
}

if (isset($_GET['status'])) {
    if ($_GET['status'] == 'gagal') {
        echo "<script>alert('Pelanggan tidak dapat dihapus karena memiliki riwayat transaksi!'); window.location='pelanggan.php'</script>";
        exit();
    } elseif ($_GET['status'] == 'berhasil') {
        echo "<script>alert('Data pelanggan berhasil dihapus!'); window.location='pelanggan.php'</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Master Data Pelanggan</title>
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
        .btn-tambah {
            background-color: green; color: white; border-radius: 5px;
            text-decoration: none; padding: 10px; float: right; margin-bottom: 10px;
        }
        .btn-edit {
            background-color: orange;
            border-radius: 10px;
            padding: 8px 12px;
            border: none;
        }
        .btn-hapus {
            background-color: red;
            border-radius: 10px;
            padding: 8px 12px;
            border: none;
        }
    </style>
</head>
<body>

    <?php include '../navbar.php'; ?>

    <div class="container">
        <h2>Master Data Pelanggan</h2>
        <button class="btn-tambah"><a href="tambah.php">Tambah Pelanggan</a></button>

        <table border="1" cellpadding="5" cellspacing="0">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Jenis Kelamin</th>
                <th>Telp</th>
                <th>Alamat</th>
                <th>Aksi</th>
            </tr>
            <?php
            $no = 1;
            $data = $DBH->prepare("SELECT * FROM pelanggan ORDER BY id ASC");
            $data->execute();
            while ($row = $data->fetch()) {
            ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $row['nama']; ?></td>
                    <td><?= $row['jenis_kelamin']; ?></td>
                    <td><?= $row['telp']; ?></td>
                    <td><?= $row['alamat']; ?></td>
                    <td>
                        <button class="btn-edit"><a href="edit.php?id=<?= $row['id']; ?>">Edit</a></button>
                        <button class="btn-hapus"><a href="pelanggan.php?pelanggan_id=<?= $row['id']; ?>" onclick="return confirm('Yakin menghapus data pelanggan ini?')">Hapus</a></button>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>