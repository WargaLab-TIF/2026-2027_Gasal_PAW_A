<?php
include 'koneksi.php';

$id = $_GET['id'];

$query = mysqli_query($koneksi, "DELETE FROM supplier WHERE id='$id'");

if ($query) {
    header("Location: index.php");
} else {
    echo "Data gagal dihapus: " . mysqli_error($koneksi);
}
?>