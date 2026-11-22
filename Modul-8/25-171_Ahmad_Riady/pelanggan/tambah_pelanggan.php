
<?php
require_once '../cek_owner.php';
require_once '../koneksi.php';

if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $telp = $_POST['telp'];
    $alamat = $_POST['alamat'];

    $data = $DBH->prepare("INSERT INTO pelanggan (nama, jenis_kelamin, telp, alamat) VALUES (:nama, :jenis_kelamin, :telp, :alamat)");
    $data->execute([
        ':nama' => $nama,
        ':jenis_kelamin' => $jenis_kelamin,
        ':telp' => $telp,
        ':alamat' => $alamat
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
    <title>Tambah Pelanggan</title>
</head>
<body>
    <h2>Tambah Data Pelanggan</h2>
    <form method="POST" action="tambah_pelanggan.php">
        <label>Nama Pelanggan</label><br>
        <input type="text" name="nama" required><br><br>
        <label>Jenis Kelamin</label><br>
        <select name="jenis_kelamin" required>
            <option value="L">Laki-laki</option>
            <option value="P">Perempuan</option>
        </select><br><br>
        <label>Telepon</label><br>
        <input type="text" name="telp"><br><br>
        <label>Alamat</label><br>
        <textarea name="alamat"></textarea><br><br>
        <button type="submit" name="simpan">Simpan Data</button>
        <a href="index.php">Batal</a>
    </form>
</body>
</html>