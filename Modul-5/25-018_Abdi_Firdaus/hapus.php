<?php
// Memanggil file koneksi database
include 'koneksi.php';

// Memeriksa apakah parameter id tersedia pada URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk menghapus data supplier berdasarkan id
    $query = "DELETE FROM supplier WHERE id = '$id'";

    // Mengeksekusi query hapus dan mengarahkan kembali ke halaman utama
    if (mysqli_query($conn, $query)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Gagal menghapus data: " . mysqli_error($conn);
    }
} else {
    header("Location: index.php");
    exit;
}
?>
