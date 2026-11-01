<?php
include "koneksi.php";

if (isset($_POST['simpan'])) {
    $stmt = mysqli_prepare($koneksi, "INSERT INTO supplier (nama, telp, alamat) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sss", $_POST['nama'], $_POST['telp'], $_POST['alamat']);
    mysqli_stmt_execute($stmt);
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Data Master Supplier</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="navbar"></div>
<div class="container">
    <div class="header"><h2>Tambah Data Master Supplier Baru</h2></div>
    <form method="post">
        <div class="baris"><label>Nama</label><input type="text" name="nama" placeholder="Nama" required></div>
        <div class="baris"><label>Telp</label><input type="text" name="telp" placeholder="telp" maxlength="12" required></div>
        <div class="baris"><label>Alamat</label><input type="text" name="alamat" placeholder="alamat" required></div>
        <div class="aksi">
            <button type="submit" name="simpan" class="btn btn-hijau">Simpan</button>
            <a href="index.php" class="btn btn-merah">Batal</a>
        </div>
    </form>
</div>
</body>
</html>
