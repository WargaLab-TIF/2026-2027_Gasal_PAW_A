
<?php
require_once '../cek_owner.php';
require_once '../koneksi.php';

if (isset($_POST['simpan'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $hp = $_POST['hp'];
    $level = $_POST['level'];

    $data = $DBH->prepare("INSERT INTO user (username, password, nama, alamat, hp, level) VALUES (:username, :password, :nama, :alamat, :hp, :level)");
    $data->execute([
        ':username' => $username,
        ':password' => $password,
        ':nama' => $nama,
        ':alamat' => $alamat,
        ':hp' => $hp,
        ':level' => $level
    ]);

    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah User</title>
</head>
<body>
    <h2>Tambah Data User</h2>
    <form method="POST" action="tambah_user.php">
        <label>Username</label><br>
        <input type="text" name="username" required><br><br>
        <label>Password</label><br>
        <input type="password" name="password" required><br><br>
        <label>Nama</label><br>
        <input type="text" name="nama" required><br><br>
        <label>Alamat</label><br>
        <textarea name="alamat"></textarea><br><br>
        <label>Nomor HP</label><br>
        <input type="text" name="hp"><br><br>
        <label>Level</label><br>
        <select name="level" required>
            <option value="1">Owner</option>
            <option value="2">Kasir</option>
        </select><br><br>
        <button type="submit" name="simpan">Simpan Data</button>
        <a href="index.php">Batal</a>
    </form>
</body>
</html>