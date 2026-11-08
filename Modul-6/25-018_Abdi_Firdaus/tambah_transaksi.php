<?php
// ==========================================================
// FILE TAMBAH TRANSAKSI: tambah_transaksi.php
// Digunakan untuk menambahkan data master transaksi baru (PDO)
// ==========================================================

require_once 'koneksi.php';

// Mengambil seluruh data pelanggan untuk ditampilkan pada dropdown
$stmt_pelanggan = $DBH->prepare("SELECT * FROM pelanggan");
$stmt_pelanggan->execute();
$pelanggan = $stmt_pelanggan->fetchAll();

// Memeriksa apakah tombol Simpan telah ditekan
if (isset($_POST['simpan'])) {
    $waktu_transaksi = $_POST['waktu_transaksi'];
    $keterangan      = $_POST['keterangan'];
    $pelanggan_id    = $_POST['pelanggan_id'];
    $total           = 0; // Nilai awal total transaksi diset 0

    // Menyimpan data transaksi baru ke tabel transaksi menggunakan PDO
    $insert = $DBH->prepare("
        INSERT INTO transaksi (waktu_transaksi, keterangan, total, pelanggan_id)
        VALUES ('$waktu_transaksi', '$keterangan', '$total', '$pelanggan_id')
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
    <title>Tambah Data Transaksi</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            max-width: 600px;
        }
    </style>
</head>
<body>

<div class="card">
    <h2>Tambah Data Master Transaksi</h2>

    <!-- Form input data master transaksi -->
    <form method="POST" action="">
        <div class="form-group">
            <label>Waktu Transaksi</label>
            <input type="date" name="waktu_transaksi" value="<?= date('Y-m-d') ?>" required>
        </div>

        <div class="form-group">
            <label>Pelanggan</label>
            <select name="pelanggan_id" required>
                <option value="">-- Pilih Pelanggan --</option>
                <?php foreach ($pelanggan as $row): ?>
                    <option value="<?= $row['id'] ?>">
                        <?= $row['id'] ?> - <?= $row['nama'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Keterangan</label>
            <textarea name="keterangan" rows="3" placeholder="Masukkan keterangan transaksi" required></textarea>
        </div>

        <div class="form-group">
            <label>Total</label>
            <input type="number" name="total" value="0" readonly>
        </div>

        <button type="submit" name="simpan" class="btn btn-hijau">Simpan Transaksi</button>
        <a href="index.php" class="btn btn-merah">Batal</a>
    </form>
</div>

</body>
</html>
