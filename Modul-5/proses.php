<?php
include 'koneksi.php';
if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $telp = $_POST['telp'];
    $alamat = $_POST['alamat'];

    $query = "INSERT INTO supplier (nama, telp, alamat) VALUES ('$nama', '$telp', '$alamat')";
    if (mysqli_query($conn, $query)) {
        header("Location: index.php");
    } else {
        echo "Gagal menambah data: " . mysqli_error($conn);
    }
}
?>