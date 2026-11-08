<?php 
include 'koneksi.php'; 

if(isset($_POST['simpan'])){
    $nama = $_POST['nama'];
    $telp = $_POST['telp'];
    $alamat = $_POST['alamat'];

    $sql = "INSERT INTO supplier (nama, telp, alamat) VALUES ('$nama', '$telp', '$alamat')";

    if (mysqli_query($conn, $sql)) {
        header("Location: index.php");
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tambah Data Supplier</title>
</head>
<body>
    <h2>Tambah Data Master Supplier Baru</h2>
    <form method="POST">
        <table cellpadding="5">
            <tr>
                <td><b>Nama</b></td>
                <td><input type="text" name="nama" placeholder="Nama" required style="width: 250px;"></td>
            </tr>
            <tr>
                <td><b>Telp</b></td>
                <td><input type="text" name="telp" placeholder="telp" required style="width: 250px;"></td>
            </tr>
            <tr>
                <td><b>Alamat</b></td>
                <td><input type="text" name="alamat" placeholder="alamat" required style="width: 250px;"></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <button type="submit" name="simpan" style="background-color: #5cb85c; color: white; border: none; padding: 5px 15px; cursor: pointer;">Simpan</button>
                    <a href="index.php">
                        <button type="button" style="background-color: #d9534f; color: white; border: none; padding: 5px 15px; cursor: pointer;">Batal</button>
                    </a>
                </td>
            </tr>
        </table>
    </form>
</body>
</html>