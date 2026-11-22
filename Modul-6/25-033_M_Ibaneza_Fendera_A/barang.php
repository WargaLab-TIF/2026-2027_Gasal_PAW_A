<?php
require_once 'koneksi.php';

if (isset($_GET['barang_id'])) {
    $id = $_GET['barang_id'];

    $cek = $DBH->prepare("SELECT * FROM transaksi_detail WHERE barang_id = '$id'");
    $cek->execute();

    if ($cek->rowCount() > 0) {
        header('location: barang.php?status=gagal');
        exit;
    } else {
        $hapus = $DBH->prepare("DELETE FROM barang WHERE id = '$id'");
        $hapus->execute();
        echo "<script>alert('Data barang berhasil dihapus!');</script>";
        header('location: barang.php?status=berhasil');
        exit;
    }
}
if (isset($_GET['status'])) {
        if ($_GET['status'] == 'gagal') {
            echo "<script>alert('Barang tidak dapat dihapus karena masih digunakan dalam transaksi!');</script>";
        } elseif ($_GET['status'] == 'berhasil') {
            echo "<script>alert('Data barang berhasil dihapus!');</script>";
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Master Detail barang</title>
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
        color: white;
    }
    button{
        background-color: red;
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

    <h2>Master Data barang</h2>
    <button class="barang"><a href="master.php" style="color: white;">Transaksi</a></button>
    <table border="1" cellpadding="5" cellspacing="0">
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
                <td><button><a href="barang.php?barang_id=<?= $row['id']; ?>" onclick="return confirm('Yakin menghapus data ini?')">Hapus</a></button></td>
            </tr>
        <?php } ?>
    </table>



</body>
</html>