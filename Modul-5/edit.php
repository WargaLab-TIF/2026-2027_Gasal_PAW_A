<?php
include 'koneksi.php';
$id = $_GET['id'];
$query = "SELECT * FROM supplier WHERE id = $id";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Data Master Supplier</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="navbar"></div>

    <div class="form-container">
        <h2>Edit Data Master Supplier</h2>
        <form action="proses_edit.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
            
            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="nama" value="<?php echo $data['nama']; ?>">
            </div>
            <div class="form-group">
                <label>Telp</label>
                <input type="text" name="telp" value="<?php echo $data['telp']; ?>">
            </div>
            <div class="form-group">
                <label>Alamat</label>
                <input type="text" name="alamat" value="<?php echo $data['alamat']; ?>">
            </div>
            <div class="button-group">
                <button type="submit" class="btn btn-update">Update</button>
                <a href="index.php" class="btn btn-batal">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>