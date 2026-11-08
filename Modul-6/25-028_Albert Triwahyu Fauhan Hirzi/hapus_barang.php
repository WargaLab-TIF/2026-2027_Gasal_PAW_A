<?php
include 'koneksi.php';
$id = $_GET['id'];

$cek_query = "SELECT * FROM transaksi_detail WHERE barang_id = '$id'";
$cek_result = mysqli_query($conn, $cek_query);

if (mysqli_num_rows($cek_result) > 0) {
    echo "<script>
            alert('Gagal! Barang ini tidak dapat dihapus karena sudah dipakai dalam transaksi.');
            window.location='barang.php';
        </script>";
} else {
    $query = "DELETE FROM barang WHERE id = '$id'";
    mysqli_query($conn, $query);
    echo "<script>
            alert('Data barang berhasil dihapus.');
            window.location='barang.php';
        </script>";
}
?>