<?php
include 'koneksi.php';

if (isset($_POST['simpan'])) {
    $nama   = $_POST['nama'];
    $telp   = $_POST['telp'];
    $alamat = $_POST['alamat'];

    $stmt = $koneksi->prepare("INSERT INTO supplier (nama, telp, alamat) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nama, $telp, $alamat);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit;
    } else {
        $error = "Gagal menyimpan: " . $stmt->error;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Data Master Supplier Baru</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px 40px; color: #333; }
        h2 { color: #3a87ad; font-weight: normal; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .baris { display: flex; align-items: flex-start; margin-bottom: 15px; }
        label { width: 120px; text-align: right; padding: 8px 15px 0 0; font-weight: bold; font-size: 14px; }
        input[type="text"], textarea { width: 400px; padding: 8px; border: 1px solid #ccc;
                                       border-radius: 3px; box-sizing: border-box; font-family: inherit; }
        .btn { color: #fff; padding: 7px 14px; border-radius: 3px; border: none;
               text-decoration: none; font-size: 13px; cursor: pointer; }
        .simpan { background: #5cb85c; }
        .batal  { background: #d9534f; }
        .tombol { margin-left: 135px; }
        .error  { color: #d9534f; margin: 10px 0 10px 135px; }
    </style>
</head>
<body>
    <h2>Tambah Data Master Supplier Baru</h2>

    <?php if (isset($error)) { echo "<div class='error'>" . htmlspecialchars($error) . "</div>"; } ?>

    <form method="POST" action="">
        <div class="baris">
            <label for="nama">Nama</label>
            <input type="text" id="nama" name="nama" placeholder="Nama" maxlength="100" required>
        </div>
        <div class="baris">
            <label for="telp">Telp</label>
            <input type="text" id="telp" name="telp" placeholder="telp" maxlength="12" required>
        </div>
        <div class="baris">
            <label for="alamat">Alamat</label>
            <textarea id="alamat" name="alamat" rows="3" placeholder="alamat" required></textarea>
        </div>
        <div class="tombol">
            <button type="submit" name="simpan" class="btn simpan">Simpan</button>
            <a href="index.php" class="btn batal">Batal</a>
        </div>
    </form>
</body>
</html>