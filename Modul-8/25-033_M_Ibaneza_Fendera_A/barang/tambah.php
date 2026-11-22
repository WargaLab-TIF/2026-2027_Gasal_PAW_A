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
    $kode_barang = $_POST['kode_barang'];
    $nama_barang = $_POST['nama_barang'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $supplier_id = $_POST['supplier_id'];

    $stmt = $DBH->prepare("INSERT INTO barang (kode_barang, nama_barang, harga, stok, supplier_id) VALUES (?, ?, ?, ?, ?)");
    $simpan = $stmt->execute([$kode_barang, $nama_barang, $harga, $stok, $supplier_id]);

    if ($simpan) {
        echo "<script>alert('Data barang berhasil ditambahkan!'); window.location='barang.php';</script>";
        exit();
    } else {
        echo "<script>alert('Data barang gagal ditambahkan!'); window.location='barang.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Data Master Barang Baru</title>
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
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <h3>Tambah Data Master Barang Baru</h3>
    
    <form method="POST" action="">
        <div class="form-group">
            <label>Kode Barang</label>
            <input type="text" name="kode_barang" placeholder="Kode Barang (Contoh: BRG001)" required>
        </div>
        <div class="form-group">
            <label>Nama Barang</label>
            <input type="text" name="nama_barang" placeholder="Nama Barang" required>
        </div>
        <div class="form-group">
            <label>Harga</label>
            <input type="number" name="harga" placeholder="Harga" required>
        </div>
        <div class="form-group">
            <label>Stok</label>
            <input type="number" name="stok" placeholder="Stok" required>
        </div>
        <div class="form-group">
            <label>Supplier</label>
            <select name="supplier_id" required>
                <option value="">-- Pilih Supplier --</option>
                <?php
                $sup = $DBH->query("SELECT * FROM supplier");
                while ($row_sup = $sup->fetch()) {
                ?>
                    <option value="<?= $row_sup['id']; ?>"><?= $row_sup['nama']; ?></option>
                <?php } ?>
            </select>
        </div>
        <button type="submit" name="simpan" class="tombol_simpan">Simpan</button>
        <a href="barang.php" class="tombol_batal">Batal</a>
    </form>

</body>
</html>