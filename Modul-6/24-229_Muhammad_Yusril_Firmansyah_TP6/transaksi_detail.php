<?php
require 'koneksi.php';

// Ambil ID Transaksi aktif dari URL (Contoh: transaksi_detail.php?id=1)
$transaksi_id = isset($_GET['id']) ? $_GET['id'] : 1;

// Proses Simpan Detail Transaksi Baru
if (isset($_POST['tambah_detail'])) {
    $barang_id = $_POST['barang_id'];
    $qty = $_POST['qty'];

    // Ambil harga asli dari tabel barang
    $q_barang = $pdo->prepare("SELECT harga FROM barang WHERE id = ?");
    $q_barang->execute([$barang_id]);
    $data_barang = $q_barang->fetch();
    $harga = $data_barang['harga'];

    // 1. Simpan data ke tabel transaksi_detail
    $stmt = $pdo->prepare("INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) VALUES (?, ?, ?, ?)");
    $stmt->execute([$transaksi_id, $barang_id, $harga, $qty]);

    // 2. Hitung ulang total transaksi dan update otomatis ke tabel transaksi (Poin 4)
    $updateTotal = $pdo->prepare("
        UPDATE transaksi 
        SET total = (
            SELECT COALESCE(SUM(harga * qty), 0) 
            FROM transaksi_detail 
            WHERE transaksi_id = ?
        ) 
        WHERE id = ?
    ");
    $updateTotal->execute([$transaksi_id, $transaksi_id]);

    header("Location: transaksi_detail.php?id=$transaksi_id");
    exit;
}

// Ambil informasi master transaksi
$q_transaksi = $pdo->prepare("SELECT t.*, p.nama AS nama_pelanggan FROM transaksi t JOIN pelanggan p ON t.pelanggan_id = p.id WHERE t.id = ?");
$q_transaksi->execute([$transaksi_id]);
$transaksi = $q_transaksi->fetch();

// Ambil daftar detail transaksi
$q_detail = $pdo->prepare("SELECT td.*, b.nama_barang FROM transaksi_detail td JOIN barang b ON td.barang_id = b.id WHERE td.transaksi_id = ?");
$q_detail->execute([$transaksi_id]);
$list_detail = $q_detail->fetchAll();

// Ambil barang yang BELUM DIPILIH pada transaksi ini (Poin 2)
$q_opsi_barang = $pdo->prepare("SELECT * FROM barang WHERE id NOT IN (SELECT barang_id FROM transaksi_detail WHERE transaksi_id = ?)");
$q_opsi_barang->execute([$transaksi_id]);
$list_barang = $q_opsi_barang->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Master Detail Transaksi</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
        .form-group { margin-bottom: 10px; }
    </style>
</head>
<body>

    <h2>Detail Transaksi ID: <?php echo $transaksi['id']; ?></h2>
    <p><strong>Tanggal:</strong> <?php echo $transaksi['waktu_transaksi']; ?></p>
    <p><strong>Pelanggan:</strong> <?php echo $transaksi['nama_pelanggan']; ?></p>
    <p><strong>Total Belanja:</strong> <span style="color: green; font-weight: bold;">Rp <?php echo number_format($transaksi['total'], 0, ',', '.'); ?></span></p>

    <hr>

    <h3>Tambah Item Barang</h3>
    <form method="POST" action="">
        <div class="form-group">
            <label>Pilih Barang:</label>
            <select name="barang_id" required>
                <option value="">-- Pilih Barang --</option>
                <?php foreach ($list_barang as $b): ?>
                    <option value="<?php echo $b['id']; ?>">
                        <?php echo $b['nama_barang']; ?> (Stok: <?php echo $b['stok']; ?> - Rp <?php echo number_format($b['harga'], 0, ',', '.'); ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Quantity (Qty):</label>
            <input type="number" name="qty" value="1" min="1" required>
        </div>
        <button type="submit" name="tambah_detail">Tambah Item</button>
    </form>

    <h3>Daftar Barang dalam Transaksi Ini</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Harga Satuan</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($list_detail as $row): ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $row['nama_barang']; ?></td>
                <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                <td><?php echo $row['qty']; ?></td>
                <td>Rp <?php echo number_format($row['harga'] * $row['qty'], 0, ',', '.'); ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($list_detail)): ?>
            <tr>
                <td colspan="5" style="text-align: center;">Belum ada item barang yang ditambahkan.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <p><a href="javascript:history.back()">Kembali</a></p>

</body>
</html>