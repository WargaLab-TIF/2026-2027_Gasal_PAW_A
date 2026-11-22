<?php
require_once __DIR__ . "/koneksi.php";
require_once __DIR__ . "/auth.php";
requireLevel([1]);

$pesan = "";
$edit = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $aksi = $_POST["aksi"] ?? "";
    $id = (int) ($_POST["id"] ?? 0);
    $nama = trim($_POST["nama"] ?? "");
    $telp = trim($_POST["telp"] ?? "") ?: null;
    $alamat = trim($_POST["alamat"] ?? "") ?: null;

    if ($nama === "") {
        $pesan = "Nama supplier wajib diisi.";
    } elseif ($aksi === "tambah") {
        $stmt = $conn->prepare("INSERT INTO supplier (nama, telp, alamat) VALUES (?, ?, ?)");
        $stmt->execute([$nama, $telp, $alamat]);
        header("Location: supplier.php");
        exit;
    } elseif ($aksi === "update" && $id > 0) {
        $stmt = $conn->prepare("UPDATE supplier SET nama = ?, telp = ?, alamat = ? WHERE id = ?");
        $stmt->execute([$nama, $telp, $alamat, $id]);
        header("Location: supplier.php");
        exit;
    }
}

if (isset($_GET["hapus"])) {
    $stmt = $conn->prepare("DELETE FROM supplier WHERE id = ?");
    $stmt->execute([(int) $_GET["hapus"]]);
    header("Location: supplier.php");
    exit;
}

if (isset($_GET["edit"])) {
    $stmt = $conn->prepare("SELECT * FROM supplier WHERE id = ? LIMIT 1");
    $stmt->execute([(int) $_GET["edit"]]);
    $edit = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

$rows = $conn->query("SELECT * FROM supplier ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Supplier</title>
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
        input { width: 100%; padding: 6px; box-sizing: border-box; }
        .alert-error { color: #dc3545; background: #f8d7da; border: 1px solid #f5c6cb; padding: 8px 12px; border-radius: 4px; margin-bottom: 15px; max-width: 500px; }
    </style>
</head>
<body>
    <?php require_once __DIR__ . "/navbar.php"; ?>

    <h2>Data Supplier</h2>

    <?php if ($pesan): ?>
        <div class="alert-error"><?= htmlspecialchars($pesan) ?></div>
    <?php endif; ?>

    <div class="form-container">
        <h3><?= $edit ? "Edit Supplier" : "Tambah Supplier Baru" ?></h3>
        <form method="POST">
            <input type="hidden" name="aksi" value="<?= $edit ? "update" : "tambah" ?>">
            <?php if ($edit): ?>
                <input type="hidden" name="id" value="<?= (int) $edit["id"] ?>">
            <?php endif; ?>

            <div class="form-group">
                <label>Nama:</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($edit["nama"] ?? "") ?>" required>
            </div>

            <div class="form-group">
                <label>Telp:</label>
                <input type="text" name="telp" value="<?= htmlspecialchars($edit["telp"] ?? "") ?>">
            </div>

            <div class="form-group">
                <label>Alamat:</label>
                <input type="text" name="alamat" value="<?= htmlspecialchars($edit["alamat"] ?? "") ?>">
            </div>

            <button type="submit" class="btn btn-success"><?= $edit ? "Update" : "Simpan" ?></button>
            <?php if ($edit): ?>
                <a href="supplier.php" class="btn btn-secondary">Batal</a>
            <?php endif; ?>
        </form>
    </div>

    <table>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Telp</th>
            <th>Alamat</th>
            <th>Aksi</th>
        </tr>
        <?php if (empty($rows)): ?>
            <tr>
                <td colspan="5">Belum ada data supplier.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($rows as $i => $r): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= htmlspecialchars($r["nama"]) ?></td>
                <td><?= htmlspecialchars($r["telp"] ?? "-") ?></td>
                <td><?= htmlspecialchars($r["alamat"] ?? "-") ?></td>
                <td>
                    <a href="supplier.php?edit=<?= (int) $r["id"] ?>" class="btn btn-warning">Edit</a>
                    <a href="supplier.php?hapus=<?= (int) $r["id"] ?>" class="btn btn-danger" onclick="return confirm('Hapus supplier ini?')">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
</body>
</html>