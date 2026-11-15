<?php
// ==========================================================
// FILE TAMBAH BARANG: tambah_barang.php
// Digunakan untuk menambahkan data barang baru (PDO)
// ==========================================================

require_once 'koneksi.php';

// Mengambil seluruh data supplier untuk pilihan dropdown
$stmt_supplier = $DBH->prepare("SELECT * FROM supplier");
$stmt_supplier->execute();
$supplier = $stmt_supplier->fetchAll();

// Memeriksa apakah tombol Simpan telah ditekan
if (isset($_POST['simpan'])) {
    $kode_barang = $_POST['kode_barang'];
    $nama_barang = $_POST['nama_barang'];
    $harga       = $_POST['harga'];
    $stok        = $_POST['stok'];
    $supplier_id = $_POST['supplier_id'];

    // Menyimpan data barang baru ke tabel barang menggunakan PDO
    $insert = $DBH->prepare("
        INSERT INTO barang (kode_barang, nama_barang, harga, stok, supplier_id)
        VALUES ('$kode_barang', '$nama_barang', '$harga', '$stok', '$supplier_id')
    ");
    $insert->execute();

    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Data Barang</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            max-width: 600px;
        }
    </style>
</head>
<body>

<div class="card">
    <h2>Tambah Data Barang</h2>

    <!-- Form input tambah barang -->
    <form method="POST" action="">
        <div class="form-group">
            <label>Kode Barang</label>
            <input type="text" name="kode_barang" placeholder="Contoh: BRG011" required>
        </div>

        <div class="form-group">
            <label>Nama Barang</label>
            <input type="text" name="nama_barang" placeholder="Masukkan nama barang" required>
        </div>

        <div class="form-group">
            <label>Harga</label>
            <input type="number" name="harga" placeholder="Masukkan harga satuan" required>
        </div>

        <div class="form-group">
            <label>Stok</label>
            <input type="number" name="stok" placeholder="Masukkan jumlah stok" required>
        </div>

        <div class="form-group">
            <label>Supplier</label>
            <select name="supplier_id" required>
                <option value="">-- Pilih Supplier --</option>
                <?php foreach ($supplier as $row): ?>
                    <option value="<?= $row['id'] ?>">
                        <?= $row['id'] ?> - <?= $row['nama'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" name="simpan" class="btn btn-hijau">Simpan Barang</button>
        <a href="index.php" class="btn btn-merah">Batal</a>
    </form>
</div>

</body>
</html>
