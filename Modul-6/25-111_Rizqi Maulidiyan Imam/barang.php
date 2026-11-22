<?php
require_once 'koneksi.php';

if (isset($_POST['hapus'])) {
    $id = $_POST['barang_id'];

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
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Master Barang</title>
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
        background-color: crimson;
        color: white;
        border-radius: 6px;
        padding: 8px 14px;
        border: none;
        cursor: pointer;
    }
    .nav {
        background-color: slateblue;
        margin-bottom: 12px;
    }
    .nav a {
        color: white;
    }
    form {
        margin: 0;
    }
    </style>
</head>
<body>

    <?php
    if (isset($_GET['status'])) {
        if ($_GET['status'] == 'gagal') {
            echo "<script>alert('Barang tidak dapat dihapus karena masih digunakan dalam transaksi!');</script>";
        } elseif ($_GET['status'] == 'berhasil') {
            echo "<script>alert('Data barang berhasil dihapus!');</script>";
        }
    }
    ?>

    <h2>Master Data Barang</h2>
    <button class="nav"><a href="master.php">Data Transaksi</a></button>

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
                <td>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                <td><?= $row['stok']; ?></td>
                <td>
                    <form method="POST" onsubmit="return confirm('Yakin menghapus data ini?')">
                        <input type="hidden" name="barang_id" value="<?= $row['id']; ?>">
                        <button type="submit" name="hapus">Hapus</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>

</body>
</html>