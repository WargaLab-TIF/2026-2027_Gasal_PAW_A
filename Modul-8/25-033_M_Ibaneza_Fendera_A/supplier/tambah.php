<?php
include '../koneksi.php';
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location: ../index.php");
    exit();
}

if ($_SESSION['level'] != 1) {
    echo "<script>alert('Akses ditolak! Halaman ini khusus Owner.'); window.location='../kasir/home.php';</script>";
    exit();
}
if (isset($_POST['simpan'])) {
    $nama   = $_POST['nama'];
    $telp   = $_POST['telp'];
    $alamat = $_POST['alamat'];

    $stmt = $DBH->prepare("INSERT INTO supplier (nama, telp, alamat) VALUES (?, ?, ?)");
    $simpan = $stmt->execute([$nama, $telp, $alamat]);

    if ($simpan) {
        echo "<script>alert('Data berhasil disimpan'); window.location='supplier.php';</script>";
        exit();
    } else {
        echo "<script>alert('Data gagal disimpan'); window.location='supplier.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Data Master Supplier Baru</title>
    <style>
        body {
            font-family: Arial, sans-serif; margin: 20px; 
        }
        .form-group {
            margin-bottom: 15px; 
        }
        label {
            display: block; margin-bottom: 5px; font-weight: bold; 
        }
        input[type="text"], textarea {
             width: 100%; padding: 8px; box-sizing: border-box; 
        }
        .tombol_batal{
            background-color: red;
            color: white;
            border-radius: 5px;
            text-decoration: none;   
            padding: 10px;
        }
        .tombol_simpan{
            background-color: green;
            color: white;
            border-radius: 5px;
            text-decoration: none;   
            padding: 10px;
        }

    </style>
</head>
<body>

    <h3>Tambah Data Master Supplier Baru</h3>
    
    <form method="POST" action="">
        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama" placeholder="Nama" required>
        </div>
        <div class="form-group">
            <label>Telp</label>
            <input type="text" name="telp" placeholder="telp" required>
        </div>
        <div class="form-group">
            <label>Alamat</label>
            <textarea name="alamat" placeholder="alamat" rows="4" required></textarea>
        </div>
        <button type="submit" name="simpan" class="tombol_simpan">Simpan</button>
        <a href="supplier.php" class="tombol_batal">Batal</a>
    </form>

</body>
</html>