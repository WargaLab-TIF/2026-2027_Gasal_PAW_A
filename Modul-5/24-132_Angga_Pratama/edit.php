<?php
include 'koneksi.php';

$id = $_GET['id'];
$data = mysqli_query($koneksi, "SELECT * FROM supplier WHERE id='$id'");
$supplier = mysqli_fetch_assoc($data);

if (!$supplier) {
    die("Data supplier tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Data Supplier</title>
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

<h2>Edit Data Master Supplier</h2>
<hr>

<form action="update.php" method="POST">
    <input type="hidden" name="id" value="<?php echo $supplier['id']; ?>">

    <label>Nama</label>
    <input type="text" name="nama"
        value="<?php echo htmlspecialchars($supplier['nama']); ?>" required>
    <br>

    <label>Telp</label>
    <input type="text" name="telp"
        value="<?php echo htmlspecialchars($supplier['telp']); ?>" required>
    <br>

    <label>Alamat</label>
    <input type="text" name="alamat"
        value="<?php echo htmlspecialchars($supplier['alamat']); ?>" required>
    <br>

    <label></label>
    <button type="submit">Update</button>
    <a href="index.php">Batal</a>
</form>

</body>
</html>