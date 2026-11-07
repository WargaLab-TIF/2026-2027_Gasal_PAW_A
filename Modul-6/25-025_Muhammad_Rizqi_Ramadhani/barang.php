<?php 
require_once 'koneksi.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah_barang'])) {
    $kode_barang = $_POST['kode_barang'];
    $nama_barang = $_POST['nama_barang'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $supplier_id = $_POST['supplier_id'];

    try {
        $stmt = $DBH->prepare("INSERT INTO barang (kode_barang, nama_barang, harga, stok, supplier_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$kode_barang, $nama_barang, $harga, $stok, $supplier_id]);
        echo "<script>alert('Barang baru berhasil ditambahkan!'); window.location='barang.php';</script>";
    } catch (Exception $e) {
        echo "<script>alert('Gagal menambah barang: " . $e->getMessage() . "');</script>";
    }
}

$stmt = $DBH->query("SELECT barang.*, supplier.nama AS nama_supplier FROM barang LEFT JOIN supplier ON barang.supplier_id = supplier.id ORDER BY barang.id DESC");
$stmt_sup = $DBH->query("SELECT * FROM supplier");
$daftar_supplier = $stmt_sup->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Data Barang</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn { padding: 6px 10px; color: white; text-decoration: none; border-radius: 4px; display: inline-block; font-size: 14px; }
        .btn-primary { background: #007bff; }
        .btn-success { background: #28a745; }
        .btn-danger { background: #dc3545; padding: 4px 8px; font-size: 12px; }
        .form-container { background: #f9f9f9; padding: 15px; border: 1px solid #ddd; margin-bottom: 20px; max-width: 500px; }
        .form-group { margin-bottom: 10px; }
        label { display: block; margin-bottom: 3px; font-weight: bold; }
        input, select { width: 100%; padding: 6px; box-sizing: border-box; }
    </style>
</head>
<body>

    <h2>Data Barang</h2>
    <a href="data.php" class="btn btn-primary">&laquo; Kembali ke Daftar Transaksi</a>
    <br><br>

    <div class="form-container">
        <h3>Tambah Barang Baru</h3>
        <form method="POST">
            <div class="form-group">
                <label>Kode Barang:</label>
                <input type="text" name="kode_barang" required placeholder="Contoh: BRG006">
            </div>
            <div class="form-group">
                <label>Nama Barang:</label>
                <input type="text" name="nama_barang" required>
            </div>
            <div class="form-group">
                <label>Harga (Rp):</label>
                <input type="number" name="harga" required>
            </div>
            <div class="form-group">
                <label>Stok:</label>
                <input type="number" name="stok" required>
            </div>
            <div class="form-group">
                <label>Supplier:</label>
                <select name="supplier_id" required>
                    <option value="">-- Pilih Supplier --</option>
                    <?php foreach ($daftar_supplier as $sup): ?>
                        <option value="<?= $sup['id']; ?>"><?= $sup['nama']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" name="tambah_barang" class="btn btn-success">Simpan Barang</button>
        </form>
    </div>

    <table>
        <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Nama Barang</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Supplier</th>
            <th>Aksi</th>
        </tr>
        <?php $no = 1; while ($b = $stmt->fetch()) { ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= $b['kode_barang']; ?></td>
            <td><?= $b['nama_barang']; ?></td>
            <td>Rp <?= number_format($b['harga'], 0, ',', '.'); ?></td>
            <td><?= $b['stok']; ?></td>
            <td><?= $b['nama_supplier']; ?></td>
            <td>
                <a href="hapus.php?id=<?= $b['id']; ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus barang <?= $b['nama_barang']; ?>?')">Hapus</a>
            </td>
        </tr>
        <?php } ?>
    </table>

</body>
</html>