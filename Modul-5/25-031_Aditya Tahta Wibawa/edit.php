<?php 
include 'koneksi.php'; 
$id = $_GET['id'];

$sql_select = "SELECT id, nama, telp, alamat FROM supplier WHERE id=$id";
$result = mysqli_query($conn, $sql_select);
$data = mysqli_fetch_assoc($result);

if(isset($_POST['update'])){
    $nama = $_POST['nama'];
    $telp = $_POST['telp'];
    $alamat = $_POST['alamat'];

    $sql_update = "UPDATE supplier SET nama='$nama', telp='$telp', alamat='$alamat' WHERE id=$id";

    if (mysqli_query($conn, $sql_update)) {
        header("Location: index.php");
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Data Supplier</title>
</head>
<body>
    <h2>Edit Data Master Supplier</h2>
    <form method="POST">
        <table cellpadding="5">
            <tr>
                <td><b>Nama</b></td>
                <td><input type="text" name="nama" value="<?= $data['nama']; ?>" required style="width: 250px;"></td>
            </tr>
            <tr>
                <td><b>Telp</b></td>
                <td><input type="text" name="telp" value="<?= $data['telp']; ?>" required style="width: 250px;"></td>
            </tr>
            <tr>
                <td><b>Alamat</b></td>
                <td><input type="text" name="alamat" value="<?= $data['alamat']; ?>" required style="width: 250px;"></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <button type="submit" name="update" style="background-color: #5cb85c; color: white; border: none; padding: 5px 15px; cursor: pointer;">Update</button>
                    <a href="index.php">
                        <button type="button" style="background-color: #d9534f; color: white; border: none; padding: 5px 15px; cursor: pointer;">Batal</button>
                    </a>
                </td>
            </tr>
        </table>
    </form>
</body>
</html>