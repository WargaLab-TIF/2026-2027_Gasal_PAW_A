<?php
include "koneksi.php";

$id = $_GET['id'];
$pesan = "";

if (isset($_POST['simpan'])) {
    $barang_id = $_POST['barang_id'];
    $qty = $_POST['qty'];

    $stmt = $db->prepare("SELECT harga FROM barang WHERE id = ?");
    $stmt->execute([$barang_id]);
    $harga = $stmt->fetchColumn();

    $stmt = $db->prepare("INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty)
                          VALUES (?, ?, ?, ?)");
    $stmt->execute([$id, $barang_id, $harga, $qty]);

    $stmt = $db->prepare("SELECT SUM(harga * qty) FROM transaksi_detail WHERE transaksi_id = ?");
    $stmt->execute([$id]);
    $total = $stmt->fetchColumn();

    $stmt = $db->prepare("UPDATE transaksi SET total = ? WHERE id = ?");
    $stmt->execute([$total, $id]);

    $pesan = "Detail transaksi berhasil disimpan.";
}

$stmt = $db->prepare("SELECT transaksi.*, pelanggan.nama
                      FROM transaksi
                      JOIN pelanggan ON transaksi.pelanggan_id = pelanggan.id
                      WHERE transaksi.id = ?");
$stmt->execute([$id]);
$master = $stmt->fetch();

$stmt = $db->prepare("SELECT transaksi_detail.*, barang.nama_barang
                      FROM transaksi_detail
                      JOIN barang ON transaksi_detail.barang_id = barang.id
                      WHERE transaksi_detail.transaksi_id = ?");
$stmt->execute([$id]);
$detail = $stmt->fetchAll();

$stmt = $db->prepare("SELECT barang_id FROM transaksi_detail WHERE transaksi_id = ?");
$stmt->execute([$id]);
$terpakai = $stmt->fetchAll(PDO::FETCH_COLUMN);

$stmt = $db->prepare("SELECT * FROM barang");
$stmt->execute();
$semua_barang = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detail Transaksi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <a href="transaksi.php" class="btn btn-transaksi">Transaksi</a> | <a href="barang.php" class="btn btn-transaksi">Barang</a>

    <h2>Master Transaksi</h2>
    <p>ID Transaksi : <?php echo $master['id']; ?></p>
    <p>Tanggal : <?php echo $master['waktu_transaksi']; ?></p>
    <p>Pelanggan : <?php echo $master['nama']; ?></p>
    <p>Keterangan : <?php echo $master['keterangan']; ?></p>
    <p>Total : <?php echo $master['total']; ?></p>

    <h2>Detail Transaksi</h2>
    <table border="1" cellpadding="5">
        <tr>
            <th>Barang</th>
            <th>Harga</th>
            <th>Qty</th>
            <th>Subtotal</th>
        </tr>
        <?php foreach ($detail as $d) { ?>
        <tr>
            <td><?php echo $d['nama_barang']; ?></td>
            <td><?php echo $d['harga']; ?></td>
            <td><?php echo $d['qty']; ?></td>
            <td><?php echo $d['harga'] * $d['qty']; ?></td>
        </tr>
        <?php } ?>
    </table>

    <h2>Tambah Barang</h2>
    <p><?php echo $pesan; ?></p>
    <form method="post">
        Barang :
        <select name="barang_id">
            <?php foreach ($semua_barang as $b) { ?>
                <?php if (in_array($b['id'], $terpakai)) { ?>
                    <option value="<?php echo $b['id']; ?>" disabled>
                        <?php echo $b['nama_barang']; ?> (sudah dipilih)
                    </option>
                <?php } else { ?>
                    <option value="<?php echo $b['id']; ?>">
                        <?php echo $b['nama_barang']; ?> - <?php echo $b['harga']; ?>
                    </option>
                <?php } ?>
            <?php } ?>
        </select>
        <br><br>
        Qty : <input type="number" name="qty" min="1" value="1">
        <br><br>
        <button type="submit" name="simpan">Simpan</button>
    </form>
</body>
</html>
