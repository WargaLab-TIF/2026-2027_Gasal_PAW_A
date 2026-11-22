<?php
session_start();
require_once '../koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location: ../index.php");
    exit();
}

$transaksi_id = isset($_GET['transaksi_id']) ? $_GET['transaksi_id'] : 1;
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Transaksi</title>
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
            text-align: left;
        }
        th {
            background-color: #d1e7dd;
        }
        button a {
            text-decoration: none;
            color: white;
        }
        button {
            border-radius: 10px;
            padding: 10px;
            border: none;
        }
    </style>
</head>
<body>
    <?php include '../navbar.php'; ?>

    <div class="container">
        <h2>Detail Transaksi (ID Transaksi: <?= $transaksi_id; ?>)</h2>
        <div style="margin-bottom: 10px;">
            <button style="background-color: skyblue;"><a href="tambah_detail.php?transaksi_id=<?= $transaksi_id; ?>" style="color: black;">Tambah Item Barang</a></button>
            <button style="background-color: green;"><a href="transaksi.php">Kembali</a></button>
        </div>

        <table border="1" cellpadding="5" cellspacing="0">
            <tr>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Harga</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
            <?php
            $detail = $DBH->prepare("SELECT b.kode_barang, b.nama_barang, b.harga, td.qty from barang b JOIN transaksi_detail td on b.id = td.barang_id where td.transaksi_id = $transaksi_id");
            $detail->execute();

            if ($detail->rowCount() > 0) {
                while ($dt = $detail->fetch()) {
                    $subtotal = $dt['harga'] * $dt['qty'];
                ?>
                    <tr>
                        <td><?= $dt['kode_barang']; ?></td>
                        <td><?= $dt['nama_barang']; ?></td>
                        <td><?= $dt['harga']; ?></td>
                        <td><?= $dt['qty']; ?></td>
                        <td><?= $subtotal; ?></td>
                    </tr>
                <?php 
                }
            } else {
                echo "<tr><td colspan='5'>Belum ada detail barang pada transaksi ini.</td></tr>";
            } 
            ?>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>