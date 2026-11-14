<!DOCTYPE html>
<html>
<head>
    <title>Tambah Data Supplier</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
        }

        h2 {
            color: #5796a9;
            font-weight: normal;
        }

        label {
            display: inline-block;
            width: 100px;
            margin-bottom: 15px;
        }

        input[type="text"] {
            width: 250px;
            padding: 8px;
        }

        button, a {
            padding: 8px 15px;
            text-decoration: none;
            border: none;
            color: white;
            border-radius: 3px;
        }

        button {
            background: #70ad47;
        }

        a {
            background: #c9302c;
        }
    </style>
</head>
<body>

<h2>Tambah Data Master Supplier Baru</h2>
<hr>

<form action="simpan.php" method="POST">
    <label>Nama</label>
    <input type="text" name="nama" placeholder="Nama" required>
    <br>

    <label>Telp</label>
    <input type="text" name="telp" placeholder="telp" required>
    <br>

    <label>Alamat</label>
    <input type="text" name="alamat" placeholder="alamat" required>
    <br>

    <label></label>
    <button type="submit">Simpan</button>
    <a href="index.php">Batal</a>
</form>

</body>
</html>