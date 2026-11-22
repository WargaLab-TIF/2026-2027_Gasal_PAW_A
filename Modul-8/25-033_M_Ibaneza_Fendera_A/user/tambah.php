<?php
session_start();
require '../koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location: ../index.php");
    exit();
}

if ($_SESSION['level'] != 1) {
    echo "<script>alert('Akses ditolak! Halaman ini khusus Owner.'); window.location='../kasir/home.php';</script>";
    exit();
}

if (isset($_POST['simpan'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $nama     = $_POST['nama'];
    $alamat   = $_POST['alamat'];
    $hp       = $_POST['hp'];
    $level    = $_POST['level'];

    $stmt = $DBH->prepare("INSERT INTO user (username, password, nama, alamat, hp, level) VALUES (?, ?, ?, ?, ?, ?)");
    $simpan = $stmt->execute([$username, $password, $nama, $alamat, $hp, $level]);

    if ($simpan) {
        echo "<script>alert('Data user berhasil ditambahkan!'); window.location='user.php';</script>";
        exit();
    } else {
        echo "<script>alert('Data user gagal ditambahkan!'); window.location='user.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Data Master User Baru</title>
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
        input[type="text"], input[type="password"], select, textarea {
            width: 100%; padding: 8px; box-sizing: border-box; 
        }
        .tombol_batal {
            background-color: red;
            color: white;
            border-radius: 5px;
            text-decoration: none;   
            padding: 10px;
        }
        .tombol_simpan {
            background-color: green;
            color: white;
            border-radius: 5px;
            text-decoration: none;   
            padding: 10px;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <h3>Tambah Data Master User Baru</h3>
    
    <form method="POST" action="">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" placeholder="Username" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="nama" placeholder="Nama Lengkap" required>
        </div>
        <div class="form-group">
            <label>Alamat</label>
            <textarea name="alamat" placeholder="Alamat" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label>No. HP</label>
            <input type="text" name="hp" placeholder="Contoh: 08123456789" required>
        </div>
        <div class="form-group">
            <label>Level</label>
            <select name="level" required>
                <option value="">-- Pilih Level --</option>
                <option value="1">1 - Owner</option>
                <option value="2">2 - Kasir</option>
            </select>
        </div>
        <button type="submit" name="simpan" class="tombol_simpan">Simpan</button>
        <a href="user.php" class="tombol_batal">Batal</a>
    </form>

</body>
</html>