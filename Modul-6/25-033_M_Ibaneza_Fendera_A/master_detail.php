<?php
require_once 'koneksi.php';

$transaksi_id = isset($_GET['transaksi_id']) ? $_GET['transaksi_id'] : 1;
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Master Detail</title>
    <style>
    body {
        font-family: sans-serif;
        padding: 20px;
    }
    table {
        border-collapse: collapse;
        width: 100%;
    }
    th, td {
        border: 1px solid #000;
        padding: 8px;
        text-align: left;
    }
    th {
        background-color: #eee;
    }
    a {
        text-decoration: none;
    }
    button{
        border-radius: 10px;
        padding: 10px;
        border: none;
    }
</style>
</head>
<body>
    <h2>Detail Transaksi (ID Transaksi: <?= $transaksi_id; ?>)</h2>
    <div style="margin-bottom: 10px;">
        <button style="background-color: skyblue;"><a href="tambah_detail.php?transaksi_id=<?= $transaksi_id; ?>">Tambah Item Barang</a></button>
        <button style="background-color: green;"><a href="master.php" style="color: white;">Kembali</a></button>
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
</body>
</html>