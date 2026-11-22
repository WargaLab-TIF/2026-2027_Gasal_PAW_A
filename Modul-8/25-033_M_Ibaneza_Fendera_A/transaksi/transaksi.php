<?php
session_start();
require_once '../koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Transaksi</title>
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
            background-color: #d1e7dd; 
        }
        button a {
            text-decoration: none;
            color: white;
        }
        .btn-detail {
            background-color: #0dcaf0;
            border-radius: 6px;
            padding: 6px 12px;
            border: none;
        }
        .btn-tambah {
            background-color: #198754;
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
        <h2>Data Transaksi</h2>
        <button class="btn-tambah"><a href="tambah_transaksi.php">Tambah Transaksi</a></button>

        <table>
            <tr>
                <th>ID</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Keterangan</th>
                <th>Total</th>
                <th>Aksi</th>
            </tr>
            <?php
            $data = $DBH->prepare("SELECT t.*, p.nama FROM transaksi t JOIN pelanggan p ON t.pelanggan_id = p.id ORDER BY t.id ASC");
            $data->execute();
            while ($row = $data->fetch()) {
            ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= $row['waktu_transaksi']; ?></td>
                    <td><?= $row['nama']; ?></td>
                    <td><?= $row['keterangan']; ?></td>
                    <td>Rp <?= number_format($row['total'], 0, ',', '.'); ?></td>
                    <td>
                        <button class="btn-detail"><a href="detail_transaksi.php?transaksi_id=<?= $row['id']; ?>" style="color: black;">Lihat Detail</a></button>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>