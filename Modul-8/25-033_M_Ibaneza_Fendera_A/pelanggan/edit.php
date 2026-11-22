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

$id = $_GET['id'];
$stmt = $DBH->prepare("SELECT * FROM pelanggan WHERE id = ?");
$stmt->execute([$id]);
$row = $stmt->fetch();

if (isset($_POST['update'])) {
    $nama = $_POST['nama'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $telp = $_POST['telp'];
    $alamat = $_POST['alamat'];

    $stmt_update = $DBH->prepare("UPDATE pelanggan SET nama = ?, jenis_kelamin = ?, telp = ?, alamat = ? WHERE id = ?");
    $update = $stmt_update->execute([$nama, $jenis_kelamin, $telp, $alamat, $id]);

    if ($update) {
        echo "<script>alert('Data pelanggan berhasil diupdate!'); window.location='pelanggan.php';</script>";
        exit();
    } else {
        echo "<script>alert('Data pelanggan gagal diupdate!'); window.location='pelanggan.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Data Pelanggan</title>
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

    <h2>Edit Data Pelanggan</h2>

    <form action="" method="POST">
        <label>Nama Pelanggan:</label><br>
        <input type="text" name="nama" value="<?= $row['nama']; ?>" required>
        <br><br>

        <label>Jenis Kelamin:</label><br>
        <select name="jenis_kelamin" required>
            <option value="L" <?= ($row['jenis_kelamin'] == 'L') ? 'selected' : ''; ?>>Laki-laki (L)</option>
            <option value="P" <?= ($row['jenis_kelamin'] == 'P') ? 'selected' : ''; ?>>Perempuan (P)</option>
        </select>
        <br><br>

        <label>No. Telp:</label><br>
        <input type="text" name="telp" value="<?= $row['telp']; ?>" required>
        <br><br>

        <label>Alamat:</label><br>
        <textarea name="alamat" rows="3" required><?= $row['alamat']; ?></textarea>
        <br><br>

        <button type="submit" name="update">Update</button>
        <button type="button" style="background-color: red;"><a href="pelanggan.php">Batal</a></button>
    </form>

</body>
</html>