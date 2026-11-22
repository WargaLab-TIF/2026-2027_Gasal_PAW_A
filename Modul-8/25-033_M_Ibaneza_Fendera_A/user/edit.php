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

$id = $_GET['id'];
$stmt = $DBH->prepare("SELECT * FROM user WHERE id_user = ?");
$stmt->execute([$id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (isset($_POST['update'])) {
    $username = $_POST['username'];
    $nama     = $_POST['nama'];
    $alamat   = $_POST['alamat'];
    $hp       = $_POST['hp'];
    $level    = $_POST['level'];

    if (!empty($_POST['password'])) {
        $password = md5($_POST['password']);
        $stmt_update = $DBH->prepare("UPDATE user SET username = ?, password = ?, nama = ?, alamat = ?, hp = ?, level = ? WHERE id_user = ?");
        $update = $stmt_update->execute([$username, $password, $nama, $alamat, $hp, $level, $id]);
    } else {
        $stmt_update = $DBH->prepare("UPDATE user SET username = ?, nama = ?, alamat = ?, hp = ?, level = ? WHERE id_user = ?");
        $update = $stmt_update->execute([$username, $nama, $alamat, $hp, $level, $id]);
    }

    if ($update) {
        echo "<script>alert('Data user berhasil diupdate!'); window.location='user.php';</script>";
        exit();
    } else {
        echo "<script>alert('Data user gagal diupdate!'); window.location='user.php';</script>gupdate data";
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Data Master User</title>
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

    <h3>Edit Data Master User</h3>
    
    <form method="POST" action="">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" value="<?= htmlspecialchars($row['username']); ?>" required>
        </div>
        <div class="form-group">
            <label>Password (Kosongkan jika tidak ingin mengganti password)</label>
            <input type="password" name="password" placeholder="Masukkan password baru jika ingin diubah">
        </div>
        <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($row['nama']); ?>" required>
        </div>
        <div class="form-group">
            <label>Alamat</label>
            <textarea name="alamat" rows="3" required><?= htmlspecialchars($row['alamat']); ?></textarea>
        </div>
        <div class="form-group">
            <label>No. HP</label>
            <input type="text" name="hp" value="<?= htmlspecialchars($row['hp']); ?>" required>
        </div>
        <div class="form-group">
            <label>Level</label>
            <select name="level" required>
                <option value="1" <?= ($row['level'] == 1) ? 'selected' : ''; ?>>1 - Owner</option>
                <option value="2" <?= ($row['level'] == 2) ? 'selected' : ''; ?>>2 - Kasir</option>
            </select>
        </div>
        <button type="submit" name="update" class="tombol_simpan">Update</button>
        <a href="user.php" class="tombol_batal">Batal</a>
    </form>

</body>
</html>