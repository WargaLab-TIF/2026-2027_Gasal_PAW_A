<?php
require_once "koneksi.php";
require_once "auth.php";
requireLevel([1]);

$pesan = "";
$edit = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $aksi = $_POST["aksi"] ?? "tambah";
    $id = (int) ($_POST["id_user"] ?? 0);
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";
    $nama = trim($_POST["nama"] ?? "");
    $alamat = trim($_POST["alamat"] ?? "") ?: null;
    $hp = trim($_POST["hp"] ?? "") ?: null;
    $level = (int) ($_POST["level"] ?? 2);

    if ($username === "" || $nama === "") {
        $pesan = "Username dan nama wajib diisi.";
    } elseif ($aksi === "tambah" && $password === "") {
        $pesan = "Password wajib diisi.";
    } elseif ($aksi === "tambah") {
        $stmt = $conn->prepare("INSERT INTO `user` (username, password, nama, alamat, hp, level) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$username, md5($password), $nama, $alamat, $hp, $level]);
        header("Location: user.php");
        exit;
    } elseif ($aksi === "update" && $id > 0) {
        $sql = "UPDATE `user` SET username = ?, nama = ?, alamat = ?, hp = ?, level = ?"
             . ($password !== "" ? ", password = ?" : "")
             . " WHERE id_user = ?";
        $params = [$username, $nama, $alamat, $hp, $level];
        if ($password !== "") $params[] = md5($password);
        $params[] = $id;

        $conn->prepare($sql)->execute($params);
        header("Location: user.php");
        exit;
    }
}

if (isset($_GET["hapus"])) {
    $conn->prepare("DELETE FROM `user` WHERE id_user = ?")->execute([(int) $_GET["hapus"]]);
    header("Location: user.php");
    exit;
}

if (isset($_GET["edit"])) {
    $stmt = $conn->prepare("SELECT id_user, username, nama, alamat, hp, level FROM `user` WHERE id_user = ?");
    $stmt->execute([(int) $_GET["edit"]]);
    $edit = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

$stmt = $conn->query("SELECT id_user, username, nama, alamat, hp, level FROM `user` ORDER BY id_user ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data User</title>
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
    <?php require_once "navbar.php"; ?>

    <h2>Data User</h2>
    <?php if ($pesan !== ""): ?><div class="alert-error"><?= htmlspecialchars($pesan) ?></div><?php endif; ?>

    <div class="form-container">
        <h3><?= $edit ? "Edit User" : "Tambah User Baru" ?></h3>
        <form method="POST">
            <input type="hidden" name="aksi" value="<?= $edit ? "update" : "tambah" ?>">
            <input type="hidden" name="id_user" value="<?= (int) ($edit["id_user"] ?? 0) ?>">

            <div class="form-group">
                <label>Username:</label>
                <input type="text" name="username" value="<?= htmlspecialchars($edit["username"] ?? "") ?>" required>
            </div>
            <div class="form-group">
                <label>Password<?= $edit ? " (kosongkan jika tidak diubah)" : "" ?>:</label>
                <input type="password" name="password" <?= $edit ? "" : "required" ?>>
            </div>
            <div class="form-group">
                <label>Nama:</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($edit["nama"] ?? "") ?>" required>
            </div>
            <div class="form-group">
                <label>Alamat:</label>
                <input type="text" name="alamat" value="<?= htmlspecialchars($edit["alamat"] ?? "") ?>">
            </div>
            <div class="form-group">
                <label>No. HP:</label>
                <input type="text" name="hp" value="<?= htmlspecialchars($edit["hp"] ?? "") ?>">
            </div>
            <div class="form-group">
                <label>Level:</label>
                <select name="level">
                    <option value="1" <?= ((int) ($edit["level"] ?? 0)) === 1 ? "selected" : "" ?>>1 - Owner</option>
                    <option value="2" <?= ((int) ($edit["level"] ?? 0)) === 2 ? "selected" : "" ?>>2 - Kasir</option>
                    <option value="3" <?= ((int) ($edit["level"] ?? 0)) === 3 ? "selected" : "" ?>>3 - Gudang</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success"><?= $edit ? "Update" : "Simpan" ?></button>
            <?php if ($edit): ?><a href="user.php" class="btn btn-secondary">Batal</a><?php endif; ?>
        </form>
    </div>

    <table>
        <tr>
            <th>No</th><th>Username</th><th>Nama</th><th>Alamat</th><th>HP</th><th>Level</th><th>Aksi</th>
        </tr>
        <?php $no = 1; while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row["username"]) ?></td>
            <td><?= htmlspecialchars($row["nama"]) ?></td>
            <td><?= htmlspecialchars($row["alamat"] ?? "-") ?></td>
            <td><?= htmlspecialchars($row["hp"] ?? "-") ?></td>
            <td><?= (int) $row["level"] ?></td>
            <td>
                <a href="user.php?edit=<?= (int) $row["id_user"] ?>" class="btn btn-warning">Edit</a>
                <a href="user.php?hapus=<?= (int) $row["id_user"] ?>" class="btn btn-danger" onclick="return confirm('Hapus user ini?')">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>