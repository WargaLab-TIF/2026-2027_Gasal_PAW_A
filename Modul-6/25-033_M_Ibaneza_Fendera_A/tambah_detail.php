<?php
require_once 'koneksi.php';

$transaksi_id = isset($_GET['transaksi_id']) ? $_GET['transaksi_id'] : 1;

$cek_barang = $DBH->prepare("SELECT barang_id FROM transaksi_detail WHERE transaksi_id = '$transaksi_id'");
$cek_barang->execute();

$barang_terpakai = array();
while ($row = $cek_barang->fetch()) {
    $barang_terpakai[] = $row['barang_id'];
}

if (isset($_POST['submit'])) {
    $id = $_POST['transaksi_id'];
    $barang_baru = $_POST['barang_id'];
    $qty = $_POST['qty'];
    $cek_harga = $DBH->prepare("SELECT harga FROM barang WHERE id = '$barang_baru'");
    $cek_harga->execute();
    $harga_barang = $cek_harga->fetch();
    $harga = $harga_barang['harga'];

    $tambah_data = $DBH->prepare("INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty)  VALUES('$id', '$barang_baru', '$harga', '$qty')") ;
    $tambah_data->execute();

    $total = $DBH->prepare("SELECT SUM(harga * qty) total_baru FROM transaksi_detail WHERE transaksi_id = '$id'");
    $total->execute();
    $data_total = $total->fetch();
    $total_baru = $data_total['total_baru'];

    $update_transaksi = $DBH->prepare("UPDATE transaksi SET total = '$total_baru' WHERE id = '$id'");
    $update_transaksi->execute();

    header("location: master_detail.php?transaksi_id=$id");
};

?>
<!DOCTYPE html>
<html>
<head>
    <title>Tambah Detail Transaksi</title>
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
    select, input[type="number"] {
            padding: 6px;
            margin-top: 5px;
    }
    button {
            background-color: skyblue;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
            display: inline-block;
        }
    a{
        color: white;
        text-decoration: none;
    }
</style>
</head>
<body>

    <h2>Tambah Barang ke Transaksi (ID: <?= $transaksi_id; ?>)</h2>

    <form action="tambah_detail.php?transaksi_id=<?= $transaksi_id; ?>" method="POST">
        <input type="hidden" name="transaksi_id" value="<?= $transaksi_id; ?>">

        <label>Pilih Barang:</label><br>
        <select name="barang_id" required>
            <option value="">-- Pilih Barang --</option>
            <?php
            $barang = $DBH->prepare("SELECT * FROM barang");
            $barang->execute();
            while ($b = $barang->fetch()) {
                $cek = in_array($b['id'], $barang_terpakai) ? "disabled" : "";
            ?>
                <option value="<?= $b['id']; ?>" <?= $cek; ?>>
                    <?= $b['nama_barang']; ?> - Rp <?= $b['harga']; ?>
                </option>
            <?php } ?>
        </select>
        <br><br>

        <label>Jumlah (Qty):</label><br>
        <input type="number" name="qty" value="1" min="1" required>
        <br><br>

        <button type="submit" name="submit">Simpan Detail</button>
        <button class="batal" style="background-color: red"><a href="master_detail.php?transaksi_id=<?= $transaksi_id; ?>">Batal</a></button>
    </form>

</body>
</html>