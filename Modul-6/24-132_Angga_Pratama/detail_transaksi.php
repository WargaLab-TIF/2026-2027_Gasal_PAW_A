<?php
include 'koneksi.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$transaksi = mysqli_query($koneksi, "
    SELECT * FROM transaksi WHERE id = $id
");

$data_transaksi = mysqli_fetch_assoc($transaksi);

if (!$data_transaksi) {
    die("Transaksi tidak ditemukan!");
}

$detail = mysqli_query($koneksi, "
    SELECT transaksi_detail.*, barang.kode_barang, barang.nama_barang
    FROM transaksi_detail
    JOIN barang ON transaksi_detail.barang_id = barang.id
    WHERE transaksi_detail.transaksi_id = $id
");

$barang = mysqli_query($koneksi, "
    SELECT * FROM barang
    WHERE id NOT IN (
        SELECT barang_id
        FROM transaksi_detail
        WHERE transaksi_id = $id
    )
    ORDER BY nama_barang ASC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detail Transaksi</title>
</head>
<body>

<h2>Detail Transaksi</h2>

<p>ID Transaksi: <?php echo $data_transaksi['id']; ?></p>
<p>Tanggal: <?php echo $data_transaksi['waktu_transaksi']; ?></p>
<p>Pelanggan ID: <?php echo $data_transaksi['pelanggan_id']; ?></p>

<h3>Daftar Barang</h3>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>No</th>
        <th>Kode Barang</th>
        <th>Nama Barang</th>
        <th>Harga</th>
        <th>Qty</th>
        <th>Subtotal</th>
    </tr>

    <?php
    $no = 1;
    while ($d = mysqli_fetch_assoc($detail)) {
    ?>
    <tr>
        <td><?php echo $no++; ?></td>
        <td><?php echo htmlspecialchars($d['kode_barang']); ?></td>
        <td><?php echo htmlspecialchars($d['nama_barang']); ?></td>
        <td>Rp <?php echo number_format($d['harga'], 0, ',', '.'); ?></td>
        <td><?php echo $d['qty']; ?></td>
        <td>Rp <?php echo number_format($d['harga'] * $d['qty'], 0, ',', '.'); ?></td>
    </tr>
    <?php } ?>
</table>

<h3>Tambah Detail Transaksi</h3>

<form action="simpan_detail.php" method="POST">

    <input type="hidden" name="transaksi_id"
           value="<?php echo $id; ?>">

    <label>Barang:</label>

    <select name="barang_id" required>
        <option value="">-- Pilih Barang --</option>

        <?php while ($b = mysqli_fetch_assoc($barang)) { ?>
            <option value="<?php echo $b['id']; ?>">
                <?php echo htmlspecialchars($b['nama_barang']); ?>
            </option>
        <?php } ?>
    </select>

    <br><br>

    <label>Jumlah:</label>
    <input type="number" name="qty" min="1" required>

    <br><br>

    <button type="submit">Simpan Detail</button>

</form>

<br>
<a href="index.php">Kembali ke Transaksi</a>

</body>
</html>