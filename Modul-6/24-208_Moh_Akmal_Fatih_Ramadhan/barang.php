<?php

require 'config.php';

$pesan = "";

if (isset($_POST['hapus'])) {

    $id = (int) $_POST['id'];

    try {

        // Mengecek barang pada transaksi_detail
        $cek = $DBH->prepare(
            "SELECT COUNT(*) AS jumlah
             FROM transaksi_detail
             WHERE barang_id = ?"
        );

        $cek->execute([$id]);

        $data = $cek->fetch();

        if ($data['jumlah'] > 0) {

            $pesan = "Barang tidak bisa dihapus karena masih digunakan pada transaksi!";

        } else {

            // Menghapus barang
            $hapus = $DBH->prepare(
                "DELETE FROM barang WHERE id = ?"
            );

            $hapus->execute([$id]);

            $pesan = "Data barang berhasil dihapus!";

        }

    } catch (PDOException $err) {

        $pesan = "Terjadi kesalahan: " . $err->getMessage();

    }

}


// MENAMPILKAN DATA BARANG

$query = $DBH->query(
    "SELECT barang.*,
            supplier.nama AS nama_supplier
     FROM barang
     JOIN supplier
     ON barang.supplier_id = supplier.id
     ORDER BY barang.id ASC"
);

$data_barang = $query->fetchAll();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Barang</title>
</head>

<body>

<h2>Data Barang</h2>

<?php if ($pesan != ""): ?>

    <p style="color:blue;">
        <?= htmlspecialchars($pesan) ?>
    </p>

<?php endif; ?>


<!-- TABEL BARANG -->

<table border="1" cellpadding="8" cellspacing="0">

<tr>
    <th>ID</th>
    <th>Kode Barang</th>
    <th>Nama Barang</th>
    <th>Harga</th>
    <th>Stok</th>
    <th>Supplier</th>
    <th>Aksi</th>
</tr>

<?php foreach ($data_barang as $row): ?>

<tr>

    <td><?= $row['id'] ?></td>

    <td><?= htmlspecialchars($row['kode_barang']) ?></td>

    <td><?= htmlspecialchars($row['nama_barang']) ?></td>

    <td>
        Rp <?= number_format($row['harga'], 0, ',', '.') ?>
    </td>

    <td><?= $row['stok'] ?></td>

    <td><?= htmlspecialchars($row['nama_supplier']) ?></td>

    <td>

        <form method="POST"
              onsubmit="return confirm('Yakin ingin menghapus barang ini?')">

            <input
                type="hidden"
                name="id"
                value="<?= $row['id'] ?>"
            >

            <button type="submit" name="hapus">
                Hapus
            </button>

        </form>

    </td>

</tr>

<?php endforeach; ?>

</table>

<br>

<a href="transaksi_detail.php">
    Kembali ke Transaksi
</a>

</body>
</html>
