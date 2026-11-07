<?php
include 'koneksi.php';

$transaksi_id = $_GET['transaksi_id'] ?? null;
if (!$transaksi_id) {
    header("Location: data.php");
    exit;
}

$stmt_pilih = $DBH->prepare("SELECT barang_id FROM transaksi_detail WHERE transaksi_id = ?");
$stmt_pilih->execute([$transaksi_id]);
$barang_terpilih = $stmt_pilih->fetchAll(PDO::FETCH_COLUMN);

$stmt_barang = $DBH->query("SELECT * FROM barang");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $barang_id = $_POST['barang_id'];
    $qty = $_POST['qty'];

    $stmt_b = $DBH->prepare("SELECT harga FROM barang WHERE id = ?");
    $stmt_b->execute([$barang_id]);
    $data_b = $stmt_b->fetch(PDO::FETCH_ASSOC);
    $harga = $data_b['harga'];

    try {
        $DBH->beginTransaction();

        $stmt_ins = $DBH->prepare("INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) VALUES (?, ?, ?, ?)");
        $stmt_ins->execute([$transaksi_id, $barang_id, $harga, $qty]);

        $stmt_sum = $DBH->prepare("SELECT SUM(harga * qty) AS total_baru FROM transaksi_detail WHERE transaksi_id = ?");
        $stmt_sum->execute([$transaksi_id]);
        $total_baru = $stmt_sum->fetch(PDO::FETCH_ASSOC)['total_baru'];

        $stmt_upd = $DBH->prepare("UPDATE transaksi SET total = ? WHERE id = ?");
        $stmt_upd->execute([$total_baru, $transaksi_id]);

        $DBH->commit();

        echo "<script>alert('Barang berhasil ditambahkan dan total transaksi diperbarui!'); window.location='detail.php?id=$transaksi_id';</script>";
    } catch (Exception $e) {
        $DBH->rollBack();
        echo "<script>alert('Gagal menyimpan: " . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Barang Transaksi</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        select, input { width: 100%; max-width: 400px; padding: 8px; box-sizing: border-box; }
        .btn { padding: 8px 15px; color: white; background: #28a745; border: none; cursor: pointer; border-radius: 4px; }
        .btn-back { background: #6c757d; text-decoration: none; display: inline-block; padding: 8px 15px; color: white; border-radius: 4px; margin-top: 10px; }
    </style>
</head>
<body>

    <h2>Tambah Barang untuk Transaksi #<?= $transaksi_id; ?></h2>

    <form method="POST">
        <div class="form-group">
            <label>Pilih Barang:</label>
            <select name="barang_id" required>
                <option value="">-- Pilih Barang --</option>
                <?php while ($b = $stmt_barang->fetch()) { 
                    $sudah_ada = in_array($b['id'], $barang_terpilih);
                ?>
                    <option value="<?= $b['id']; ?>" <?= $sudah_ada ? 'disabled' : ''; ?>>
                        <?= $b['nama_barang'] . ' (Rp ' . number_format($b['harga'], 0, ',', '.') . ' - Stok: ' . $b['stok'] . ')'; ?>
                        <?= $sudah_ada ? ' [Sudah Dipilih]' : ''; ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="form-group">
            <label>Jumlah (Qty):</label>
            <input type="number" name="qty" value="1" min="1" required>
        </div>

        <button type="submit" class="btn">Simpan Barang</button>
    </form>

    <a href="detail.php?id=<?= $transaksi_id; ?>" class="btn-back">&laquo; Kembali ke Detail Transaksi</a>

</body>
</html>