
<?php
require_once '../cek_owner.php';
require_once '../koneksi.php';

if (isset($_POST['simpan'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $telp = $_POST['telp'];
    $alamat = $_POST['alamat'];

    $data = $DBH->prepare("UPDATE pelanggan SET nama = :nama, jenis_kelamin = :jenis_kelamin, telp = :telp, alamat = :alamat WHERE id = :id");
    $data->execute([
        ':nama' => $nama,
        ':jenis_kelamin' => $jenis_kelamin,
        ':telp' => $telp,
        ':alamat' => $alamat,
        ':id' => $id
    ]);

    header('Location: index.php');
    exit;
}

$id = $_GET['id'];
$data = $DBH->prepare("SELECT * FROM pelanggan WHERE id = :id");
$data->execute([':id' => $id]);
$pelanggan = $data->fetch();

if (!$pelanggan) {
    die("Data pelanggan tidak ditemukan");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pelanggan</title>
</head>
<body>
    <h2>Edit Data Pelanggan</h2>
    <form method="POST" action="edit_pelanggan.php?id=<?= $id ?>">
        <input type="hidden" name="id" value="<?= htmlspecialchars($pelanggan['id']) ?>">

        <label>Nama Pelanggan</label><br>
        <input type="text" name="nama" value="<?= htmlspecialchars($pelanggan['nama']) ?>" required><br><br>

        <label>Jenis Kelamin</label><br>
        <select name="jenis_kelamin" required>
            <option value="L" <?= ($pelanggan['jenis_kelamin'] == 'L') ? 'selected' : '' ?>>Laki-laki</option>
            <option value="P" <?= ($pelanggan['jenis_kelamin'] == 'P') ? 'selected' : '' ?>>Perempuan</option>
        </select><br><br>

        <label>Telepon</label><br>
        <input type="text" name="telp" value="<?= htmlspecialchars($pelanggan['telp'] ?? '') ?>"><br><br>

        <label>Alamat</label><br>
        <textarea name="alamat"><?= htmlspecialchars($pelanggan['alamat'] ?? '') ?></textarea><br><br>

        <button type="submit" name="simpan">Simpan Perubahan</button>
        <a href="index.php">Batal</a>
    </form>
</body>
</html>