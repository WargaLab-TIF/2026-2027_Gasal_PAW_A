<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt_cek = $DBH->prepare("SELECT COUNT(*) FROM transaksi_detail WHERE barang_id = ?");
    $stmt_cek->execute([$id]);
    $jumlah_pakai = $stmt_cek->fetchColumn();

    if ($jumlah_pakai > 0) {
        echo "<script>
                alert('Gagal menghapus! Barang ini masih digunakan pada transaksi.'); 
                window.location='barang.php';
              </script>";
    } else {
        $stmt_hapus = $DBH->prepare("DELETE FROM barang WHERE id = ?");
        $stmt_hapus->execute([$id]);

        echo "<script>
                alert('Data barang berhasil dihapus.'); 
                window.location='barang.php';
              </script>";
    }
} else {
    header("Location: barang.php");
}
?>