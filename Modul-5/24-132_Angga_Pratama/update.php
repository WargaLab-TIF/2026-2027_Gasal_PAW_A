<?php
include 'koneksi.php';

$id = $_POST['id'];
$nama = $_POST['nama'];
$telp = $_POST['telp'];
$alamat = $_POST['alamat'];

$query = mysqli_query($koneksi, "UPDATE supplier SET
    nama='$nama',
    telp='$telp',
    alamat='$alamat'
    WHERE id='$id'");

if ($query) {
    header("Location: index.php");
} else {
    echo "Data gagal diubah: " . mysqli_error($koneksi);
}
?>