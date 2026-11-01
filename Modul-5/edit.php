<?php

// Cek apakah parameter id ada di URL
if (!isset($_GET['id'])) {
    // Jika tidak ada, arahkan kembali ke halaman daftar data
    header("Location: index.php"); // Ganti index.php dengan nama halaman daftar supplier Anda
    exit;
}

$id = $_GET['id'];

// ... (lanjutkan dengan kode query database Anda di bawah ini) ...

include 'koneksi.php';
$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM supplier WHERE id='$id'");
$data = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Master Supplier</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2 { color: #347C98; }
        .form-group { margin-bottom: 15px; display: flex; }
        .form-group label { width: 100px; font-weight: bold; margin-top: 5px; }
        .form-group input { padding: 8px; width: 300px; border: 1px solid #ccc; border-radius: 4px; }
        .btn-update { background-color: #79B84C; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 3px; }
        .btn-batal { background-color: #C0392B; color: white; text-decoration: none; padding: 8px 20px; border-radius: 3px; margin-left: 5px; }
    </style>
</head>
<body>
    <h2>Edit Data Master Supplier</h2>
    <form action="proses_edit.php" method="POST">
        <input type="hidden" name="id" value="<?= $data['id'] ?>">
        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama" value="<?= $data['nama'] ?>" required>
        </div>
        <div class="form-group">
            <label>Telp</label>
            <input type="text" name="telp" value="<?= $data['telp'] ?>" required>
        </div>
        <div class="form-group">
            <label>Alamat</label>
            <input type="text" name="alamat" value="<?= $data['alamat'] ?>" required>
        </div>
        <div style="margin-left: 100px;">
            <button type="submit" name="update" class="btn-update">Update</button>
            <a href="index.php" class="btn-batal">Batal</a>
        </div>
    </form>
</body>
</html>