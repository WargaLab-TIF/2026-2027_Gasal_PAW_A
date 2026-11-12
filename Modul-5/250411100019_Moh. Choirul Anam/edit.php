<?php
include "koneksi.php";

$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);

if (isset($_POST['update'])) {
    $stmt = mysqli_prepare($koneksi, "UPDATE supplier SET nama=?, telp=?, alamat=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "sssi", $_POST['nama'], $_POST['telp'], $_POST['alamat'], $id);
    mysqli_stmt_execute($stmt);
    header("Location: index.php");
    exit;
}

$stmt = mysqli_prepare($koneksi, "SELECT * FROM supplier WHERE id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if (!$data) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Master Supplier</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="navbar"></div>
<div class="container">
    <div class="header"><h2>Edit Data Master Supplier</h2></div>
    <form method="post">
        <input type="hidden" name="id" value="<?= $data['id'] ?>">
        <div class="baris"><label>Nama</label><input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" required></div>
        <div class="baris"><label>Telp</label><input type="text" name="telp" value="<?= htmlspecialchars($data['telp']) ?>" maxlength="12" required></div>
        <div class="baris"><label>Alamat</label><input type="text" name="alamat" value="<?= htmlspecialchars($data['alamat']) ?>" required></div>
        <div class="aksi">
            <button type="submit" name="update" class="btn btn-hijau">Update</button>
            <a href="index.php" class="btn btn-merah">Batal</a>
        </div>
    </form>
</div>
</body>
</html>
