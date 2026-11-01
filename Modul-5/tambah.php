<!DOCTYPE html>
<html>
<head>
    <title>Tambah Data Master Supplier Baru</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="navbar"></div>

    <div class="form-container">
        <h2>Tambah Data Master Supplier Baru</h2>
        <form action="proses_tambah.php" method="POST">
            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="nama" placeholder="Nama">
            </div>
            <div class="form-group">
                <label>Telp</label>
                <input type="text" name="telp" placeholder="telp">
            </div>
            <div class="form-group">
                <label>Alamat</label>
                <input type="text" name="alamat" placeholder="alamat">
            </div>
            <div class="button-group">
                <button type="submit" class="btn btn-simpan">Simpan</button>
                <a href="index.php" class="btn btn-batal">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>