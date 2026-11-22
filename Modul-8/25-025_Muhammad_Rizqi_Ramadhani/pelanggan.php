<?php
require_once __DIR__ . "/koneksi.php";
require_once __DIR__ . "/auth.php";
requireLevel([1]);

$pesan = "";
$edit = null;
$isEdit = isset($_GET["edit"]) && $_GET["edit"] !== "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $aksi = $_POST["aksi"] ?? "";
    $id = trim($_POST["id"] ?? "");
    $nama = trim($_POST["nama"] ?? "");
    $jk = ($_POST["jenis_kelamin"] ?? "") !== "" ? $_POST["jenis_kelamin"] : null;
    $telp = trim($_POST["telp"] ?? "") ?: null;
    $alamat = trim($_POST["alamat"] ?? "") ?: null;

    if ($id === "" || $nama === "") {
        $pesan = "ID dan nama pelanggan wajib diisi.";
    } elseif ($aksi === "tambah") {
        $stmt = $conn->prepare("INSERT INTO pelanggan (id, nama, jenis_kelamin, telp, alamat) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$id, $nama, $jk, $telp, $alamat]);
        header("Location: pelanggan.php");
        exit;
    } elseif ($aksi === "update") {
        $stmt = $conn->prepare("UPDATE pelanggan SET nama = ?, jenis_kelamin = ?, telp = ?, alamat = ? WHERE id = ?");
        $stmt->execute([$nama, $jk, $telp, $alamat, $id]);
        header("Location: pelanggan.php");
        exit;
    }
}

if (isset($_GET["hapus"]) && $_GET["hapus"] !== "") {
    $stmt = $conn->prepare("DELETE FROM pelanggan WHERE id = ?");
    $stmt->execute([$_GET["hapus"]]);
    header("Location: pelanggan.php");
    exit;
}

if ($isEdit) {
    $stmt = $conn->prepare("SELECT * FROM pelanggan WHERE id = ? LIMIT 1");
    $stmt->execute([$_GET["edit"]]);
    $edit = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

$rows = $conn->query("SELECT * FROM pelanggan ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pelanggan</title>
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
        .alert-error { color: #dc3545; background: #f8d7da; border: 1px solid #f5c6cb; padding: 8px 12px; border-radius: 4px; margin-bottom: 15px; max-width: 500px; }
    </style>
</head>
<body>
    <?php require_once __DIR__ . "/navbar.php"; ?>

    <h2>Data Pelanggan</h2>

    <?php if ($pesan): ?>
        <div class="alert-error"><?= htmlspecialchars($pesan) ?></div>
    <?php endif; ?>

    <div class="form-container">
        <h3><?= $edit ? "Edit Pelanggan" : "Tambah Pelanggan Baru" ?></h3>
        <form method="POST">
            <input type="hidden" name="aksi" value="<?= $edit ? "update" : "tambah" ?>">

            <div class="form-group">
                <label>ID Pelanggan:</label>
                <input type="text" name="id" value="<?= htmlspecialchars($edit["id"] ?? "") ?>" <?= $edit ? "readonly" : "required" ?>>
            </div>

            <div class="form-group">
                <label>Nama:</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($edit["nama"] ?? "") ?>" required>
            </div>

            <div class="form-group">
                <label>Jenis Kelamin:</label>
                <select name="jenis_kelamin">
                    <option value="">-- Pilih --</option>
                    <option value="L" <?= ($edit["jenis_kelamin"] ?? "") === "L" ? "selected" : "" ?>>Laki-laki</option>
                    <option value="P" <?= ($edit["jenis_kelamin"] ?? "") === "P" ? "selected" : "" ?>>Perempuan</option>
                </select>
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
                <a href="pelanggan.php" class="btn btn-secondary">Batal</a>
            <?php endif; ?>
        </form>
    </div>

    <table>
        <tr>
            <th>No</th>
            <th>ID</th>
            <th>Nama</th>
            <th>JK</th>
            <th>Telp</th>
            <th>Alamat</th>
            <th>Aksi</th>
        </tr>
        <?php if (empty($rows)): ?>
            <tr>
                <td colspan="7">Belum ada data pelanggan.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($rows as $i => $r): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= htmlspecialchars($r["id"]) ?></td>
                <td><?= htmlspecialchars($r["nama"]) ?></td>
                <td><?= htmlspecialchars($r["jenis_kelamin"] ?? "-") ?></td>
                <td><?= htmlspecialchars($r["telp"] ?? "-") ?></td>
                <td><?= htmlspecialchars($r["alamat"] ?? "-") ?></td>
                <td>
                    <a href="pelanggan.php?edit=<?= urlencode($r["id"]) ?>" class="btn btn-warning">Edit</a>
                    <a href="pelanggan.php?hapus=<?= urlencode($r["id"]) ?>" class="btn btn-danger" onclick="return confirm('Hapus pelanggan ini?')">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
</body>
</html>