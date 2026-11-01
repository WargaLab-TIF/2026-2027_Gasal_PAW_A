<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Data Master Supplier</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2 { color: #347C98; }
        .form-group { margin-bottom: 15px; display: flex; }
        .form-group label { width: 100px; font-weight: bold; margin-top: 5px;}
        .form-group input { padding: 8px; width: 300px; border: 1px solid #ccc; border-radius: 4px; }
        .btn-simpan { background-color: #79B84C; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 3px; }
        .btn-batal { background-color: #C0392B; color: white; text-decoration: none; padding: 8px 20px; border-radius: 3px; margin-left: 5px; }
    </style>
</head>
<body>
    <h2>Tambah Data Master Supplier Baru</h2>
    <form action="proses_tambah.php" method="POST">
        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama" placeholder="Nama" required>
        </div>
        <div class="form-group">
            <label>Telp</label>
            <input type="text" name="telp" placeholder="telp" required>
        </div>
        <div class="form-group">
            <label>Alamat</label>
            <input type="text" name="alamat" placeholder="alamat" required>
        </div>
        <div style="margin-left: 100px;">
            <button type="submit" name="submit" class="btn-simpan">Simpan</button>
            <a href="index.php" class="btn-batal">Batal</a>
        </div>
    </form>
</body>
</html>