<?php
include 'koneksi.php';
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $telp = $_POST['telp'];
    $alamat = $_POST['alamat'];

    $query = "UPDATE supplier SET nama='$nama', telp='$telp', alamat='$alamat' WHERE id='$id'";
    if (mysqli_query($conn, $query)) {
        header("Location: index.php");
    } else {
        echo "Gagal mengupdate data: " . mysqli_error($conn);
    }
}
?>