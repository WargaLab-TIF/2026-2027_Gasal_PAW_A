<?php
require_once 'koneksi.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Master Detail Transaksi</title>
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
        text-align: center;
    }
    th {
        background-color: #eee;
    }
    
    a {
        text-decoration: none;
    }
    button{
        background-color: skyblue;
        border-radius: 10px;
        padding: 10px;
        border: none;
    }

    .barang{
        background-color: green;
        color: white;
        border-radius: 10px;
        padding: 10px;
        margin-bottom: 5px;
        border: none;
    }
</style>
</head>
<body>

    <h2>Master Data Transaksi</h2>
    <button class="barang"><a href="barang.php" style="color: white;">Barang</a></button>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Tanggal</th>
            <th>Pelanggan</th>
            <th>Keterangan</th>
            <th>Total</th>
            <th>Aksi</th>
        </tr>
        <?php
        $data = $DBH->prepare("SELECT t.*, p.nama from transaksi t, pelanggan p where t.pelanggan_id = p.id ORDER BY t.id asc");
        $data->execute();
        while ($row = $data->fetch()) {
        ?>
            <tr>
                <td><?= $row['id']; ?></td>
                <td><?= $row['waktu_transaksi']; ?></td>
                <td><?= $row['nama']; ?></td>
                <td><?= $row['keterangan']; ?></td>
                <td><?= $row['total']; ?></td>
                <td><button><a href="master_detail.php?transaksi_id=<?= $row['id']; ?>">Lihat Detail</a></button></td>
            </tr>
        <?php } ?>
    </table>



</body>
</html>