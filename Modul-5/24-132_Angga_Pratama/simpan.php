<?php
include 'koneksi.php';

$nama = $_POST['nama'];
$telp = $_POST['telp'];
$alamat = $_POST['alamat'];

$query = mysqli_query($koneksi, "INSERT INTO supplier (nama, telp, alamat)
VALUES ('$nama', '$telp', '$alamat')");

if ($query) {
    header("Location: index.php");
} else {
    echo "Data gagal disimpan: " . mysqli_error($koneksi);
}
?>