<?php
require_once 'koneksi.php';

$transaksi_id = isset($_GET['transaksi_id']) ? $_GET['transaksi_id'] : 1;

$master = $DBH->prepare("SELECT total FROM transaksi WHERE id = ?");
$master->execute([$transaksi_id]);
$data_master = $master->fetch();
$total_transaksi = $data_master ? $data_master['total'] : 0;
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Transaksi</title>
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
        text-align: left;
    }
    th {
        background-color: aliceblue;
        color: darkslategray;
    }
    a {
        text-decoration: none;
    }
    button {
        border-radius: 6px;
        padding: 8px 14px;
        border: none;
        cursor: pointer;
    }
    button a {
        color: white;
    }
    .tambah {
        background-color: dodgerblue;
    }
    .kembali {
        background-color: slateblue;
    }
    .total {
        background-color: lightyellow;
        font-weight: bold;
    }
    </style>
</head>
<body>
    <h2>Detail Transaksi (ID Transaksi: <?= $transaksi_id; ?>)</h2>

    <div style="margin-bottom: 12px;">
        <button class="tambah"><a href="tambah_detail.php?transaksi_id=<?= $transaksi_id; ?>">Tambah Item Barang</a></button>
        <button class="kembali"><a href="master.php">Kembali</a></button>
    </div>

    <table>
        <tr>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Harga</th>
            <th>Qty</th>
            <th>Subtotal</th>
        </tr>
        <?php
        $detail = $DBH->prepare("SELECT b.kode_barang, b.nama_barang, td.harga, td.qty
                                 FROM barang b
                                 JOIN transaksi_detail td ON b.id = td.barang_id
                                 WHERE td.transaksi_id = ?");
        $detail->execute([$transaksi_id]);

        if ($detail->rowCount() > 0) {
            while ($dt = $detail->fetch()) {
                $subtotal = $dt['harga'] * $dt['qty'];
        ?>
                <tr>
                    <td><?= $dt['kode_barang']; ?></td>
                    <td><?= $dt['nama_barang']; ?></td>
                    <td>Rp <?= number_format($dt['harga'], 0, ',', '.'); ?></td>
                    <td><?= $dt['qty']; ?></td>
                    <td>Rp <?= number_format($subtotal, 0, ',', '.'); ?></td>
                </tr>
        <?php
            }
        } else {
            echo "<tr><td colspan='5'>Belum ada detail barang pada transaksi ini.</td></tr>";
        }
        ?>
        <tr class="total">
            <td colspan="4" style="text-align: right;">TOTAL</td>
            <td>Rp <?= number_format($total_transaksi, 0, ',', '.'); ?></td>
        </tr>
    </table>
</body>
</html>