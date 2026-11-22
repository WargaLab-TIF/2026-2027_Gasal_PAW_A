<?php
session_start();
require_once '../koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location: ../index.php");
    exit();
}

if (isset($_POST['simpan'])) {
    $waktu_transaksi = $_POST['waktu_transaksi'];
    $keterangan      = $_POST['keterangan'];
    $pelanggan_id    = $_POST['pelanggan_id'];
    $total           = 0;

    $stmt = $DBH->prepare("INSERT INTO transaksi (waktu_transaksi, keterangan, total, pelanggan_id) VALUES (?, ?, ?, ?)");
    $simpan = $stmt->execute([$waktu_transaksi, $keterangan, $total, $pelanggan_id]);

    if ($simpan) {
        $id_baru = $DBH->lastInsertId();
        echo "<script>alert('Transaksi berhasil dibuat! Silakan tambahkan barang.'); window.location='detail_transaksi.php?transaksi_id=$id_baru';</script>";
        exit();
    } else {
        echo "<script>alert('Transaksi gagal dibuat')</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Transaksi Baru</title>
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
        input[type="date"], select, textarea {
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

    <h3>Tambah Transaksi Baru</h3>
    
    <form method="POST" action="">
        <div class="form-group">
            <label>Tanggal Transaksi</label>
            <input type="date" name="waktu_transaksi" value="<?= date('Y-m-d'); ?>" required>
        </div>
        <div class="form-group">
            <label>Pelanggan</label>
            <select name="pelanggan_id" required>
                <option value="">-- Pilih Pelanggan --</option>
                <?php
                $pel = $DBH->query("SELECT * FROM pelanggan ORDER BY nama ASC");
                while ($p = $pel->fetch()) {
                ?>
                    <option value="<?= $p['id']; ?>"><?= $p['id']; ?> - <?= $p['nama']; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label>Keterangan</label>
            <textarea name="keterangan" placeholder="Contoh: Pembelian tunai" rows="3" required></textarea>
        </div>
        <button type="submit" name="simpan" class="tombol_simpan">Simpan & Tambah Barang</button>
        <a href="transaksi.php" class="tombol_batal">Batal</a>
    </form>

</body>
</html>