<?php

require 'config.php';

$pesan = "";

// Mengambil ID transaksi
$transaksi_id = isset($_GET['transaksi_id'])
    ? (int) $_GET['transaksi_id']
    : 0;


// PROSES SIMPAN DETAIL TRANSAKSI

if (isset($_POST['simpan'])) {

    $transaksi_id = (int) $_POST['transaksi_id'];
    $barang_id = (int) $_POST['barang_id'];
    $qty = (int) $_POST['qty'];

    try {

        if ($qty <= 0) {
            throw new Exception("Jumlah harus lebih dari 0!");
        }

        // Mengecek transaksi
        $cek_transaksi = $DBH->prepare(
            "SELECT id FROM transaksi WHERE id = ?"
        );

        $cek_transaksi->execute([$transaksi_id]);

        if (!$cek_transaksi->fetch()) {
            throw new Exception("Transaksi tidak ditemukan!");
        }

        // Mengecek barang yang sudah dipilih
        $cek = $DBH->prepare(
            "SELECT barang_id FROM transaksi_detail
             WHERE transaksi_id = ? AND barang_id = ?"
        );

        $cek->execute([$transaksi_id, $barang_id]);

        if ($cek->fetch()) {
            throw new Exception("Barang sudah dimasukkan!");
        }

        // Mengambil harga barang
        $query_barang = $DBH->prepare(
            "SELECT harga FROM barang WHERE id = ?"
        );

        $query_barang->execute([$barang_id]);

        $barang = $query_barang->fetch();

        if (!$barang) {
            throw new Exception("Barang tidak ditemukan!");
        }

        $harga = $barang['harga'];

        // Menyimpan detail transaksi
        $insert = $DBH->prepare(
            "INSERT INTO transaksi_detail
            (transaksi_id, barang_id, harga, qty)
            VALUES (?, ?, ?, ?)"
        );

        $insert->execute([
            $transaksi_id,
            $barang_id,
            $harga,
            $qty
        ]);

        // Menghitung ulang total transaksi
        //---------------------------------
        $update = $DBH->prepare(
            "UPDATE transaksi
             SET total = (
                SELECT COALESCE(SUM(harga * qty), 0)
                FROM transaksi_detail
                WHERE transaksi_id = ?
             )
             WHERE id = ?"
        );

        $update->execute([
            $transaksi_id,
            $transaksi_id
        ]);


        header(
            "Location: transaksi_detail.php?transaksi_id=" . $transaksi_id
        );

        exit;

    } catch (Exception $err) {

        $pesan = $err->getMessage();

    }
}


$query_transaksi = $DBH->query(
    "SELECT transaksi.*, pelanggan.nama AS nama_pelanggan
     FROM transaksi
     JOIN pelanggan
     ON transaksi.pelanggan_id = pelanggan.id
     ORDER BY transaksi.id ASC"
);

$data_transaksi = $query_transaksi->fetchAll();


// MENAMPILKAN DATA DETAIL TRANSAKSI

$detail = [];

if ($transaksi_id > 0) {

    $query_detail = $DBH->prepare(
        "SELECT transaksi_detail.*,
                barang.kode_barang,
                barang.nama_barang
         FROM transaksi_detail
         JOIN barang
         ON transaksi_detail.barang_id = barang.id
         WHERE transaksi_detail.transaksi_id = ?"
    );

    $query_detail->execute([$transaksi_id]);

    $detail = $query_detail->fetchAll();

}


// MENGAMBIL BARANG YANG BELUM DIPILIH

$barang_tersedia = [];

if ($transaksi_id > 0) {

    $query_barang = $DBH->prepare(
        "SELECT * FROM barang
         WHERE id NOT IN (
             SELECT barang_id
             FROM transaksi_detail
             WHERE transaksi_id = ?
         )
         ORDER BY nama_barang ASC"
    );

    $query_barang->execute([$transaksi_id]);

    $barang_tersedia = $query_barang->fetchAll();

}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Master Detail Transaksi</title>
</head>

<body>

<h2>Master Detail Transaksi</h2>

<?php if ($pesan != ""): ?>

    <p style="color:red;">
        <?= htmlspecialchars($pesan) ?>
    </p>

<?php endif; ?>

<h3>Data Transaksi</h3>

<table border="1" cellpadding="8" cellspacing="0">

<tr>
    <th>ID Transaksi</th>
    <th>Tanggal</th>
    <th>Pelanggan</th>
    <th>Total</th>
    <th>Aksi</th>
</tr>

<?php foreach ($data_transaksi as $row): ?>

<tr>

    <td><?= $row['id'] ?></td>

    <td><?= $row['waktu_transaksi'] ?></td>

    <td><?= htmlspecialchars($row['nama_pelanggan']) ?></td>

    <td>
        Rp <?= number_format($row['total'], 0, ',', '.') ?>
    </td>

    <td>
        <a href="transaksi_detail.php?transaksi_id=<?= $row['id'] ?>">
            Pilih
        </a>
    </td>

</tr>

<?php endforeach; ?>

</table>


<?php if ($transaksi_id > 0): ?>

<hr>

<h3>Detail Transaksi ID: <?= $transaksi_id ?></h3>

<table border="1" cellpadding="8" cellspacing="0">

<tr>
    <th>Kode Barang</th>
    <th>Nama Barang</th>
    <th>Harga</th>
    <th>Qty</th>
    <th>Subtotal</th>
</tr>

<?php if (count($detail) > 0): ?>

    <?php foreach ($detail as $row): ?>

    <tr>

        <td><?= htmlspecialchars($row['kode_barang']) ?></td>

        <td><?= htmlspecialchars($row['nama_barang']) ?></td>

        <td>
            Rp <?= number_format($row['harga'], 0, ',', '.') ?>
        </td>

        <td><?= $row['qty'] ?></td>

        <td>
            Rp <?= number_format(
                $row['harga'] * $row['qty'],
                0,
                ',',
                '.'
            ) ?>
        </td>

    </tr>

    <?php endforeach; ?>

<?php else: ?>

    <tr>
        <td colspan="5">Belum ada detail transaksi.</td>
    </tr>

<?php endif; ?>

</table>

<h3>Tambah Detail Transaksi</h3>

<form method="POST">

    <input
        type="hidden"
        name="transaksi_id"
        value="<?= $transaksi_id ?>"
    >

    <label>Barang:</label>

    <select name="barang_id" required>

        <option value="">-- Pilih Barang --</option>

        <?php foreach ($barang_tersedia as $b): ?>

            <option value="<?= $b['id'] ?>">

                <?= htmlspecialchars($b['nama_barang']) ?>

                - Rp <?= number_format(
                    $b['harga'],
                    0,
                    ',',
                    '.'
                ) ?>

            </option>

        <?php endforeach; ?>

    </select>

    <br><br>

    <label>Jumlah:</label>

    <input
        type="number"
        name="qty"
        min="1"
        required
    >

    <br><br>

    <button type="submit" name="simpan">
        Simpan Detail
    </button>

</form>

<?php endif; ?>

</body>
</html>
