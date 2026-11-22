<?php
require_once 'koneksi.php';

$transaksi_id = isset($_GET['transaksi_id']) ? $_GET['transaksi_id'] : 1;

$cek_barang = $DBH->prepare("SELECT barang_id FROM transaksi_detail WHERE transaksi_id = ?");
$cek_barang->execute([$transaksi_id]);

$barang_terpakai = array();
while ($row = $cek_barang->fetch()) {
    $barang_terpakai[] = $row['barang_id'];
}

if (isset($_POST['submit'])) {
    $id         = $_POST['transaksi_id'];
    $barang_baru = $_POST['barang_id'];
    $qty         = $_POST['qty'];

    if (!in_array($barang_baru, $barang_terpakai)) {

        $cek_harga = $DBH->prepare("SELECT harga FROM barang WHERE id = ?");
        $cek_harga->execute([$barang_baru]);
        $harga_barang = $cek_harga->fetch();
        $harga = $harga_barang['harga'];

        $tambah_data = $DBH->prepare("INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty)
                                      VALUES (?, ?, ?, ?)");
        $tambah_data->execute([$id, $barang_baru, $harga, $qty]);

        $total = $DBH->prepare("SELECT SUM(harga * qty) AS total_baru
                                FROM transaksi_detail WHERE transaksi_id = ?");
        $total->execute([$id]);
        $data_total = $total->fetch();
        $total_baru = $data_total['total_baru'];

        $update_transaksi = $DBH->prepare("UPDATE transaksi SET total = ? WHERE id = ?");
        $update_transaksi->execute([$total_baru, $id]);
    }

    header("location: master_detail.php?transaksi_id=$id");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tambah Detail Transaksi</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        padding: 24px;
        background-color: whitesmoke;
    }
    h2 {
        color: darkslategray;
    }
    form {
        background-color: white;
        border: 1px solid silver;
        border-radius: 8px;
        padding: 20px;
        max-width: 380px;
    }
    select, input[type="number"] {
        padding: 8px;
        margin-top: 6px;
        width: 100%;
        box-sizing: border-box;
    }
    button {
        color: white;
        padding: 8px 14px;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        cursor: pointer;
    }
    .simpan {
        background-color: dodgerblue;
    }
    .batal {
        background-color: crimson;
    }
    a {
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
                    <?= $b['nama_barang']; ?> - Rp <?= number_format($b['harga'], 0, ',', '.'); ?>
                </option>
            <?php } ?>
        </select>
        <br><br>

        <label>Jumlah (Qty):</label><br>
        <input type="number" name="qty" value="1" min="1" required>
        <br><br>

        <button type="submit" name="submit" class="simpan">Simpan Detail</button>
        <button type="button" class="batal"><a href="master_detail.php?transaksi_id=<?= $transaksi_id; ?>">Batal</a></button>
    </form>

</body>
</html>