<?php
require_once 'koneksi.php';

if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $telp = $_POST['telp'];
    $alamat = $_POST['alamat'];

    $data = $DBH->prepare("INSERT INTO supplier (nama, telp, alamat) VALUES (:nama, :telp, :alamat)");
    $data->execute([
        ':nama' => $nama,
        ':telp' => $telp,
        ':alamat' => $alamat
    ]);

    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Supplier</title>
</head>
<body>
    <h2>Tambah Data Supplier</h2>
    <form method="POST" action="tambah_supplier.php">
        <label>Nama Supplier</label><br>
        <input type="text" name="nama" required><br><br>
        <label>Telepon</label><br>
        <input type="text" name="telp"><br><br>
        <label>Alamat</label><br>
        <textarea name="alamat"></textarea><br><br>
        <button type="submit" name="simpan">Simpan Data</button>
        <button type="submit"><a href="index.php">Batal</a></button>
    </form>
</body>
</html>