<?php
include 'koneksi.php';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Pop up konfirmasi sudah dihandle via javascript (onclick) di tombol Hapus pada index.php
    $query = "DELETE FROM supplier WHERE id='$id'";
    
    // Perhatikan: Menghapus supplier yang ID-nya digunakan di table 'barang' akan menyebabkan foreign key constraint error. 
    if (mysqli_query($conn, $query)) {
        header("Location: index.php");
    } else {
        echo "Gagal menghapus data (Data kemungkinan sedang dipakai di tabel Barang): " . mysqli_error($conn);
    }
}
?>