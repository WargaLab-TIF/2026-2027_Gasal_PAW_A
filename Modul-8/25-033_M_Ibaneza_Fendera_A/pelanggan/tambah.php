<?php
session_start();
require_once '../koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location: ../index.php");
    exit();
}
if ($_SESSION['level'] != 1) {
    echo "<script>alert('Akses ditolak! Halaman ini khusus Owner.'); window.location='../kasir/home.php';</script>";
    exit();
}

if (isset($_POST['simpan'])) {
    $data = $DBH->query("SELECT MAX(id) AS id_akhir FROM pelanggan")->fetch();
    $id   = $data['id_akhir'] + 1;

    $nama          = $_POST['nama'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $telp          = $_POST['telp'];
    $alamat        = $_POST['alamat'];

    $stmt = $DBH->prepare("INSERT INTO pelanggan (id, nama, jenis_kelamin, telp, alamat) VALUES (?, ?, ?, ?, ?)");
    $simpan = $stmt->execute([$id, $nama, $jenis_kelamin, $telp, $alamat]);

    if ($simpan) {
        echo "<script>alert('Data pelanggan berhasil ditambahkan!'); window.location='pelanggan.php';</script>";
        exit();
    } else {
        echo "<script>alert('Data pelanggan gagal ditambahkan!'); window.location='pelanggan.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Data Pelanggan</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
        }
        select, input[type="text"], textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            box-sizing: border-box;
        }
        button {
            background-color: green;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            display: inline-block;
        }
        button a {
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>

    <h2>Tambah Data Pelanggan</h2>

    <form action="" method="POST">
        <label>Nama Pelanggan:</label><br>
        <input type="text" name="nama" required>
        <br><br>

        <label>Jenis Kelamin:</label><br>
        <select name="jenis_kelamin" required>
            <option value="">-- Pilih Jenis Kelamin --</option>
            <option value="L">Laki-laki (L)</option>
            <option value="P">Perempuan (P)</option>
        </select>
        <br><br>

        <label>No. Telp:</label><br>
        <input type="text" name="telp" required>
        <br><br>

        <label>Alamat:</label><br>
        <textarea name="alamat" rows="3" required></textarea>
        <br><br>

        <button type="submit" name="simpan">Simpan</button>
        <button type="button" style="background-color: red;"><a href="pelanggan.php">Batal</a></button>
    </form>

</body>
</html>