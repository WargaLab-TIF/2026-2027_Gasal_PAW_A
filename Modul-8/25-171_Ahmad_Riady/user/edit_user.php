<?php
require_once '../cek_owner.php';
require_once '../koneksi.php';

if (isset($_POST['simpan'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $hp = $_POST['hp'];
    $level = $_POST['level'];

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $data = $DBH->prepare("UPDATE user SET username = :username, password = :password, nama = :nama, alamat = :alamat, hp = :hp, level = :level WHERE id_user = :id");
        $data->execute([
            ':username' => $username,
            ':password' => $password,
            ':nama' => $nama,
            ':alamat' => $alamat,
            ':hp' => $hp,
            ':level' => $level,
            ':id' => $id
        ]);
    } else {
        $data = $DBH->prepare("UPDATE user SET username = :username, nama = :nama, alamat = :alamat, hp = :hp, level = :level WHERE id_user = :id");
        $data->execute([
            ':username' => $username,
            ':nama' => $nama,
            ':alamat' => $alamat,
            ':hp' => $hp,
            ':level' => $level,
            ':id' => $id
        ]);
    }

    header('Location: index.php');
    exit;
}

$id = $_GET['id'];
$data = $DBH->prepare("SELECT * FROM user WHERE id_user = :id");
$data->execute([':id' => $id]);
$user = $data->fetch();

if (!$user) {
    die("Data user tidak ditemukan");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
</head>
<body>
    <h2>Edit Data User</h2>
    <form method="POST" action="edit_user.php?id=<?= $id ?>">
        <input type="hidden" name="id" value="<?= htmlspecialchars($user['id_user']) ?>">

        <label>Username</label><br>
        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required><br><br>

        <label>Password</label><br>
        <input type="password" name="password" placeholder="Kosongkan jika tidak diubah"><br><br>

        <label>Nama</label><br>
        <input type="text" name="nama" value="<?= htmlspecialchars($user['nama']) ?>" required><br><br>

        <label>Alamat</label><br>
        <textarea name="alamat"><?= htmlspecialchars($user['alamat'] ?? '') ?></textarea><br><br>

        <label>Nomor HP</label><br>
        <input type="text" name="hp" value="<?= htmlspecialchars($user['hp'] ?? '') ?>"><br><br>

        <label>Level</label><br>
        <select name="level" required>
            <option value="1" <?= ($user['level'] == 1) ? 'selected' : '' ?>>Owner</option>
            <option value="2" <?= ($user['level'] == 2) ? 'selected' : '' ?>>Kasir</option>
        </select><br><br>

        <button type="submit" name="simpan">Simpan Perubahan</button>
        <a href="index.php">Batal</a>
    </form>
</body>
</html>
