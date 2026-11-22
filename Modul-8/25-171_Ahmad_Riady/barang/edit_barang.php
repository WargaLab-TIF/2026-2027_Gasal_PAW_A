<?php
require_once '../cek_owner.php';
require_once '../koneksi.php';

if (isset($_POST['simpan'])) {
    $id = $_POST['id'];
    $kode_barang = $_POST['kode_barang'];
    $nama_barang = $_POST['nama_barang'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $supplier_id = $_POST['supplier_id'];

    $data = $DBH->prepare("UPDATE barang SET kode_barang = :kode_barang, nama_barang = :nama_barang, harga = :harga, stok = :stok, supplier_id = :supplier_id WHERE id = :id");
    $data->execute([
        ':kode_barang' => $kode_barang,
        ':nama_barang' => $nama_barang,
        ':harga' => $harga,
        ':stok' => $stok,
        ':supplier_id' => $supplier_id,
        ':id' => $id
    ]);

    header('Location: index.php');
    exit;
}

$id = $_GET['id'];

$data = $DBH->prepare("SELECT * FROM barang WHERE id = :id");
$data->execute([':id' => $id]);
$barang = $data->fetch();

if (!$barang) {
    die("Data barang tidak ditemukan");
}

$supplier = $DBH->prepare("SELECT * FROM supplier ORDER BY nama ASC");
$supplier->execute();
$daftar_supplier = $supplier->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Barang</title>
</head>
<body>
    <h2>Edit Data Barang</h2>
    <form method="POST" action="edit_barang.php?id=<?= $id ?>">
        <input type="hidden" name="id" value="<?= htmlspecialchars($barang['id']) ?>">
        <p>
            Kode Barang<br>
            <input type="text" name="kode_barang" value="<?= htmlspecialchars($barang['kode_barang']) ?>" required>
        </p>
        <p>
            Nama Barang<br>
            <input type="text" name="nama_barang" value="<?= htmlspecialchars($barang['nama_barang']) ?>" required>
        </p>
        <p>
            Harga<br>
            <input type="number" name="harga" value="<?= htmlspecialchars($barang['harga']) ?>" required>
        </p>
        <p>
            Stok<br>
            <input type="number" name="stok" value="<?= htmlspecialchars($barang['stok']) ?>" required>
        </p>
        <p>
            Supplier<br>
            <select name="supplier_id" required>
                <option value="">Pilih Supplier</option>
                <?php foreach ($daftar_supplier as $s): ?>
                    <option value="<?= htmlspecialchars($s['id']) ?>" <?= $barang['supplier_id'] == $s['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($s['nama']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        <button type="submit" name="simpan">Simpan Perubahan</button>
        <a href="index.php">Batal</a>
    </form>
</body>
</html>