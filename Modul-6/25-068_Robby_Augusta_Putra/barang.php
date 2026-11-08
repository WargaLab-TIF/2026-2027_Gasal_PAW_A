<?php
include "koneksi.php";

$pesan = "";

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    $stmt = $db->prepare("SELECT COUNT(*) FROM transaksi_detail WHERE barang_id = ?");
    $stmt->execute([$id]);
    $jumlah = $stmt->fetchColumn();

    if ($jumlah > 0) {
        $pesan = "Barang tidak bisa dihapus karena masih digunakan pada transaksi.";
    } else {
        $stmt = $db->prepare("DELETE FROM barang WHERE id = ?");
        $stmt->execute([$id]);
        $pesan = "Barang berhasil dihapus.";
    }
}
$stmt = $db->prepare("SELECT * FROM barang");
$stmt->execute();
$data = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Barang</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <a href="transaksi.php" class="btn btn-transaksi">Transaksi</a> | <a href="barang.php" class="btn btn-transaksi">Barang</a>

    <h2>Data Barang</h2>
    <p><?php echo $pesan; ?></p>
    <table border="1" cellpadding="5">
        <tr>
            <th>ID</th>
            <th>Kode</th>
            <th>Nama Barang</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($data as $row) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['kode_barang']; ?></td>
            <td><?php echo $row['nama_barang']; ?></td>
            <td><?php echo $row['harga']; ?></td>
            <td><?php echo $row['stok']; ?></td>
            <td>
                <a href="barang.php?hapus=<?php echo $row['id']; ?>" class="btn btn-hps"
                   onclick="return confirm('Yakin hapus barang ini?')">Hapus</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
