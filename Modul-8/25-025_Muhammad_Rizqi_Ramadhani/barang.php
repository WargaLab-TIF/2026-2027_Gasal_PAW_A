<?php
require_once __DIR__ . "/koneksi.php";
require_once __DIR__ . "/auth.php";
requireLevel([1]);

$editData = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isUpdate    = isset($_POST['update_barang']);
    $id          = (int) ($_POST['id'] ?? 0);
    $kode_barang = trim($_POST['kode_barang'] ?? '');
    $nama_barang = trim($_POST['nama_barang'] ?? '');
    $harga       = (int) ($_POST['harga'] ?? 0);
    $stok        = (int) ($_POST['stok'] ?? 0);
    $supplier_id = ($_POST['supplier_id'] ?? '') !== '' ? $_POST['supplier_id'] : null;

    try {
        if ($isUpdate && $id > 0) {
            $sql = "UPDATE barang SET kode_barang = ?, nama_barang = ?, harga = ?, stok = ?, supplier_id = ? WHERE id = ?";
            $params = [$kode_barang, $nama_barang, $harga, $stok, $supplier_id, $id];
            $pesan = "Data barang berhasil diperbarui!";
        } else {
            $sql = "INSERT INTO barang (kode_barang, nama_barang, harga, stok, supplier_id) VALUES (?, ?, ?, ?, ?)";
            $params = [$kode_barang, $nama_barang, $harga, $stok, $supplier_id];
            $pesan = "Barang baru berhasil ditambahkan!";
        }

        $conn->prepare($sql)->execute($params);
        echo "<script>alert('{$pesan}'); window.location='barang.php';</script>";
        exit;
    } catch (Exception $e) {
        $msg = htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        echo "<script>alert('Gagal menyimpan barang: {$msg}');</script>";
    }
}

if (isset($_GET['edit']) && (int) $_GET['edit'] > 0) {
    $stmtEdit = $conn->prepare("SELECT * FROM barang WHERE id = ?");
    $stmtEdit->execute([(int) $_GET['edit']]);
    $editData = $stmtEdit->fetch(PDO::FETCH_ASSOC) ?: null;
}

$stmt = $conn->query("SELECT barang.*, supplier.nama AS nama_supplier FROM barang LEFT JOIN supplier ON barang.supplier_id = supplier.id ORDER BY barang.id DESC");
$daftar_supplier = $conn->query("SELECT * FROM supplier ORDER BY nama ASC")->fetchAll(PDO::FETCH_ASSOC);
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
        .btn { padding: 6px 10px; color: white; text-decoration: none; border: none; border-radius: 4px; display: inline-block; font-size: 14px; cursor: pointer; }
        .btn-success { background: #28a745; }
        .btn-warning { background: #ffc107; color: #212529; padding: 4px 8px; font-size: 12px; }
        .btn-secondary { background: #6c757d; }
        .btn-danger { background: #dc3545; padding: 4px 8px; font-size: 12px; }
        .form-container { background: #f9f9f9; padding: 15px; border: 1px solid #ddd; margin-bottom: 20px; max-width: 500px; }
        .form-group { margin-bottom: 10px; }
        label { display: block; margin-bottom: 3px; font-weight: bold; }
        input, select { width: 100%; padding: 6px; box-sizing: border-box; }
    </style>
</head>
<body>
    <?php require_once __DIR__ . "/navbar.php"; ?>

    <h2>Data Barang</h2>

    <div class="form-container">
        <h3><?= $editData ? "Edit Barang" : "Tambah Barang Baru" ?></h3>
        <form method="POST">
            <?php if ($editData): ?>
                <input type="hidden" name="id" value="<?= (int) $editData['id'] ?>">
            <?php endif; ?>

            <div class="form-group">
                <label>Kode Barang:</label>
                <input type="text" name="kode_barang" value="<?= htmlspecialchars((string) ($editData['kode_barang'] ?? '')) ?>" required placeholder="Contoh: BRG006">
            </div>
            <div class="form-group">
                <label>Nama Barang:</label>
                <input type="text" name="nama_barang" value="<?= htmlspecialchars((string) ($editData['nama_barang'] ?? '')) ?>" required>
            </div>
            <div class="form-group">
                <label>Harga (Rp):</label>
                <input type="number" name="harga" value="<?= htmlspecialchars((string) ($editData['harga'] ?? '')) ?>" required>
            </div>
            <div class="form-group">
                <label>Stok:</label>
                <input type="number" name="stok" value="<?= htmlspecialchars((string) ($editData['stok'] ?? '')) ?>" required>
            </div>
            <div class="form-group">
                <label>Supplier:</label>
                <select name="supplier_id" required>
                    <option value="">-- Pilih Supplier --</option>
                    <?php foreach ($daftar_supplier as $sup): ?>
                        <option value="<?= htmlspecialchars((string) $sup['id']) ?>" <?= (string) ($editData['supplier_id'] ?? '') === (string) $sup['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars((string) $sup['nama']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" name="<?= $editData ? 'update_barang' : 'tambah_barang' ?>" class="btn btn-success">
                <?= $editData ? "Simpan Perubahan" : "Simpan Barang" ?>
            </button>
            <?php if ($editData): ?>
                <a href="barang.php" class="btn btn-secondary">Batal</a>
            <?php endif; ?>
        </form>
    </div>

    <table>
        <tr>
            <th>No</th><th>Kode</th><th>Nama Barang</th><th>Harga</th><th>Stok</th><th>Supplier</th><th>Aksi</th>
        </tr>
        <?php $no = 1; while ($b = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars((string) $b['kode_barang']) ?></td>
            <td><?= htmlspecialchars((string) $b['nama_barang']) ?></td>
            <td>Rp <?= number_format((int) $b['harga'], 0, ',', '.') ?></td>
            <td><?= htmlspecialchars((string) $b['stok']) ?></td>
            <td><?= htmlspecialchars((string) ($b['nama_supplier'] ?? '-')) ?></td>
            <td>
                <a href="barang.php?edit=<?= urlencode((string) $b['id']) ?>" class="btn btn-warning">Edit</a>
                <a href="hapus.php?id=<?= urlencode((string) $b['id']) ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus barang <?= htmlspecialchars((string) $b['nama_barang'], ENT_QUOTES, 'UTF-8') ?>?')">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>