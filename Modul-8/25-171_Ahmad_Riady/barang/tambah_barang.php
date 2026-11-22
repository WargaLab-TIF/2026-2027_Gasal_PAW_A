<?php
require_once '../cek_owner.php';
require_once '../koneksi.php';

if (isset($_POST['simpan'])) {
    $kode_barang = $_POST['kode_barang'];
    $nama_barang = $_POST['nama_barang'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $supplier_id = $_POST['supplier_id'];

    $data = $DBH->prepare("INSERT INTO barang (kode_barang, nama_barang, harga, stok, supplier_id) VALUES (:kode_barang, :nama_barang, :harga, :stok, :supplier_id)");
    $data->execute([
        ':kode_barang' => $kode_barang,
        ':nama_barang' => $nama_barang,
        ':harga' => $harga,
        ':stok' => $stok,
        ':supplier_id' => $supplier_id
    ]);

    header('Location: index.php');
    exit;
}

$supplier = $DBH->prepare("SELECT * FROM supplier ORDER BY nama ASC");
$supplier->execute();
$daftar_supplier = $supplier->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Barang</title>
</head>
<body>
    <h2>Tambah Data Barang</h2>
    <form method="POST" action="tambah_barang.php">
        <p>
            Kode Barang<br>
            <input type="text" name="kode_barang" required>
        </p>
        <p>
            Nama Barang<br>
            <input type="text" name="nama_barang" required>
        </p>
        <p>
            Harga<br>
            <input type="number" name="harga" required>
        </p>
        <p>
            Stok<br>
            <input type="number" name="stok" required>
        </p>
        <p>
            Supplier<br>
            <select name="supplier_id" required>
                <option value="">Pilih Supplier</option>
                <?php foreach ($daftar_supplier as $s): ?>
                    <option value="<?= htmlspecialchars($s['id']) ?>">
                        <?= htmlspecialchars($s['nama']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        <button type="submit" name="simpan">Simpan Data</button>
        <a href="index.php">Batal</a>
    </form>
</body>
</html>