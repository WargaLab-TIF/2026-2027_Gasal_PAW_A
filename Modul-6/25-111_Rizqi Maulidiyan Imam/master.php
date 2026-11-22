<?php
require_once 'koneksi.php';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Master Transaksi</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        padding: 24px;
        background-color: whitesmoke;
    }
    h2 {
        color: darkslategray;
    }
    table {
        border-collapse: collapse;
        width: 100%;
        background-color: white;
    }
    th, td {
        border: 1px solid silver;
        padding: 10px;
        text-align: center;
    }
    th {
        background-color: aliceblue;
        color: darkslategray;
    }
    tr:hover td {
        background-color: ghostwhite;
    }
    a {
        text-decoration: none;
    }
    button {
        background-color: dodgerblue;
        border-radius: 6px;
        padding: 8px 14px;
        border: none;
        cursor: pointer;
    }
    button a {
        color: white;
    }
    .nav {
        background-color: slateblue;
        margin-bottom: 12px;
    }
    </style>
</head>
<body>

    <h2>Master Data Transaksi</h2>
    <button class="nav"><a href="barang.php">Data Barang</a></button>

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
        $data = $DBH->prepare("SELECT t.*, p.nama
                               FROM transaksi t
                               JOIN pelanggan p ON t.pelanggan_id = p.id
                               ORDER BY t.id ASC");
        $data->execute();
        while ($row = $data->fetch()) {
        ?>
            <tr>
                <td><?= $row['id']; ?></td>
                <td><?= $row['waktu_transaksi']; ?></td>
                <td><?= $row['nama']; ?></td>
                <td><?= $row['keterangan']; ?></td>
                <td>Rp <?= number_format($row['total'], 0, ',', '.'); ?></td>
                <td><button><a href="master_detail.php?transaksi_id=<?= $row['id']; ?>">Lihat Detail</a></button></td>
            </tr>
        <?php } ?>
    </table>

</body>
</html>