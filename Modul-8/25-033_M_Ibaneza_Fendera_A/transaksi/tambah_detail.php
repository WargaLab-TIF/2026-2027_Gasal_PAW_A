<?php
session_start();
require_once '../koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location: ../index.php");
    exit();
}

$transaksi_id = isset($_GET['transaksi_id']) ? $_GET['transaksi_id'] : 1; 

$cek_barang = $DBH->prepare("SELECT barang_id FROM transaksi_detail WHERE transaksi_id = ?"); 
$cek_barang->execute([$transaksi_id]);

$barang_terpakai = array(); 
while ($row = $cek_barang->fetch()) {
    $barang_terpakai[] = $row['barang_id']; 
}

if (isset($_POST['submit'])) {
    $id          = $_POST['transaksi_id']; 
    $barang_baru = $_POST['barang_id']; 
    $qty         = $_POST['qty']; 

    // Ambil harga barang
    $cek_harga = $DBH->prepare("SELECT harga FROM barang WHERE id = ?"); 
    $cek_harga->execute([$barang_baru]);
    $harga_barang = $cek_harga->fetch(); 
    $harga = $harga_barang['harga']; 

    // Simpan ke transaksi_detail
    $tambah_data = $DBH->prepare("INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) VALUES (?, ?, ?, ?)"); 
    $tambah_data->execute([$id, $barang_baru, $harga, $qty]);

    // Hitung ulang total dan update ke tabel transaksi
    $total = $DBH->prepare("SELECT SUM(harga * qty) AS total_baru FROM transaksi_detail WHERE transaksi_id = ?"); 
    $total->execute([$id]);
    $data_total = $total->fetch(); 
    $total_baru = $data_total['total_baru']; 

    $update_transaksi = $DBH->prepare("UPDATE transaksi SET total = ? WHERE id = ?"); 
    $update_transaksi->execute([$total_baru, $id]);
    echo "<script>alert('Data berhasil ditambah'); window.location='detail_transaksi.php?transaksi_id=$id';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Detail Transaksi</title>
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
        select, input[type="number"] {
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

    <h3>Tambah Barang ke Transaksi (ID: <?= $transaksi_id; ?>)</h3> 

    <form action="" method="POST">
        <input type="hidden" name="transaksi_id" value="<?= $transaksi_id; ?>"> 

        <div class="form-group">
            <label>Pilih Barang</label>
            <select name="barang_id" required>
                <option value="">-- Pilih Barang --</option>
                <?php
                $barang = $DBH->prepare("SELECT * FROM barang"); 
                $barang->execute();
                while ($b = $barang->fetch()) {
                    $cek = in_array($b['id'], $barang_terpakai) ? "disabled" : ""; 
                ?>
                <option value="<?= $b['id']; ?>" <?= $cek; ?>>
                <?= $b['nama_barang']; ?> - Rp <?= $b['harga']; ?>
            	</option>
                <?php } ?>
            </select>
        </div>

        <div class="form-group">
            <label>Jumlah (Qty)</label>
            <input type="number" name="qty" value="1" min="1" required> 
        </div>

        <button type="submit" name="submit" class="tombol_simpan">Simpan Detail</button>
        <a href="detail_transaksi.php?transaksi_id=<?= $transaksi_id; ?>" class="tombol_batal">Batal</a> 
    </form>

</body>
</html>