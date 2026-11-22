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
$stmt = $DBH->prepare("SELECT * FROM barang WHERE id = ?");
$stmt->execute([$id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// Proses Update Data Barang
if (isset($_POST['update'])) {
    $kode_barang = $_POST['kode_barang'];
    $nama_barang = $_POST['nama_barang'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $supplier_id = $_POST['supplier_id'];

    $stmt_update = $DBH->prepare("UPDATE barang SET kode_barang = ?, nama_barang = ?, harga = ?, stok = ?, supplier_id = ? WHERE id = ?");
    $update = $stmt_update->execute([$kode_barang, $nama_barang, $harga, $stok, $supplier_id, $id]);

    if ($update) {
        echo "<script>alert('Data barang berhasil diupdate!'); window.location='barang.php';</script>";
        exit();
    } else {
        echo "<script>alert('Data barang gagal diupdate!'); window.location='barang.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Data Master Barang</title>
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
        input[type="text"], input[type="number"], select, textarea {
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

    <h3>Edit Data Master Barang</h3>
    
    <form method="POST" action="">
        <div class="form-group">
            <label>Kode Barang</label>
            <input type="text" name="kode_barang" value="<?= htmlspecialchars($row['kode_barang']); ?>" required>
        </div>
        <div class="form-group">
            <label>Nama Barang</label>
            <input type="text" name="nama_barang" value="<?= htmlspecialchars($row['nama_barang']); ?>" required>
        </div>
        <div class="form-group">
            <label>Harga</label>
            <input type="number" name="harga" value="<?= htmlspecialchars($row['harga']); ?>" required>
        </div>
        <div class="form-group">
            <label>Stok</label>
            <input type="number" name="stok" value="<?= htmlspecialchars($row['stok']); ?>" required>
        </div>
        <div class="form-group">
            <label>Supplier</label>
            <select name="supplier_id" required>
                <option value="">-- Pilih Supplier --</option>
                <?php
                $sup = $DBH->query("SELECT * FROM supplier");
                while ($row_sup = $sup->fetch()) {
                    $selected = ($row_sup['id'] == $row['supplier_id']) ? 'selected' : '';
                ?>
                    <option value="<?= $row_sup['id']; ?>" <?= $selected; ?>><?= $row_sup['nama']; ?></option>
                <?php } ?>
            </select>
        </div>
        <button type="submit" name="update" class="tombol_simpan">Update</button>
        <a href="barang.php" class="tombol_batal">Batal</a>
    </form>

</body>
</html>