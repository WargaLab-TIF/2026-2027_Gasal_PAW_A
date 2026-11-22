<?php
require_once 'koneksi.php';

if (isset($_POST['simpan'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $telp = $_POST['telp'];
    $alamat = $_POST['alamat'];
    $data = $DBH->prepare("UPDATE supplier SET nama = :nama, telp = :telp, alamat = :alamat WHERE id = :id");
    $data->execute([
        ':nama' => $nama,
        ':telp' => $telp,
        ':alamat' => $alamat,
        ':id' => $id
    ]);
    header('Location: index.php');
    exit;
}

$id = $_GET['id'];
$data = $DBH->prepare("SELECT * FROM supplier WHERE id = :id");
$data->execute([':id' => $id]);
$supplier = $data->fetch();
if (!$supplier) {
    die("Data supplier tidak ditemukan");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Supplier</title>
</head>
<body>
    <h2>Edit Data Supplier</h2>
    <form method="POST" action="edit_supplier.php">
        <input type="hidden" name="id" value="<?= htmlspecialchars($supplier['id']) ?>">
        <label>Nama Supplier</label><br>
        <input type="text" name="nama" value="<?= htmlspecialchars($supplier['nama']) ?>" required><br><br>
        <label>Telepon</label><br>
        <input type="text" name="telp" value="<?= htmlspecialchars($supplier['telp'] ?? '') ?>"><br><br>
        <label>Alamat</label><br>
        <textarea name="alamat"><?= htmlspecialchars($supplier['alamat'] ?? '') ?></textarea><br><br>
        <button type="submit" name="simpan">Simpan Perubahan</button>
        <button type="submit"><a href="index.php">Batal</a></button>
    </form>
</body>
</html>