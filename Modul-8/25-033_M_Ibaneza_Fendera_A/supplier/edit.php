<?php
include '../koneksi.php';

session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:../index.php");
    exit();
}

if ($_SESSION['level'] != 1) {
    echo "<script>alert('Akses ditolak! Halaman ini khusus Owner.'); window.location='../kasir/home.php';</script>";
    exit();
}

$id = $_GET['id'];
$stmt = $DBH->prepare("SELECT * FROM supplier WHERE id = ?");
$stmt->execute([$id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (isset($_POST['update'])) {
    $nama   = $_POST['nama'];
    $telp   = $_POST['telp'];
    $alamat = $_POST['alamat'];

    $stmt_update = $DBH->prepare("UPDATE supplier SET nama = ?, telp = ?, alamat = ? WHERE id = ?");
    $update = $stmt_update->execute([$nama, $telp, $alamat, $id]);

    if ($update) {
        echo "<script>alert('Data berhasil diupdate'); window.location='supplier.php';</script>";
        exit();
    } else {
        echo "<script>alert('Data gagal diupdate'); window.location='suppleir.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Data Master Supplier</title>
    <style>
        body {
            font-family: Arial, sans-serif; margin: 20px; 
        }
        .form-group {
            margin-bottom: 15px; 
        }
        label {
            display: block; margin-bottom: 5px; font-weight: bold; 
        }
        input[type="text"], textarea {
             width: 100%; padding: 8px; box-sizing: border-box; 
        }
        .tombol_batal{
            background-color: red;
            color: white;
            border-radius: 5px;
            text-decoration: none;   
            padding: 10px;
        }
        .tombol_update{
            background-color: green;
            color: white;
            border-radius: 5px;
            text-decoration: none;   
            padding: 10px;
        }

    </style>
</head>
<body>

    <h3>Edit Data Master Supplier</h3>
    
    <form method="POST" action="">
        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama" value="<?php echo $row['nama']; ?>" required>
        </div>
        <div class="form-group">
            <label>Telp</label>
            <input type="text" name="telp" value="<?php echo $row['telp']; ?>" required>
        </div>
        <div class="form-group">
            <label>Alamat</label>
            <textarea name="alamat" rows="4" required><?php echo $row['alamat']; ?></textarea>
        </div>
        <button type="submit" name="update" class="tombol_update">Update</button>
        <a href="supplier.php" class="tombol_batal">Batal</a>
    </form>

</body>
</html>