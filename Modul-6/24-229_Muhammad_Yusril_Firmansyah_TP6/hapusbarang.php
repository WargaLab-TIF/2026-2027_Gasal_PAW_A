<?php
require 'koneksi.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $barang_id = $_GET['id'];

    $check = $pdo->prepare("SELECT COUNT(*) FROM transaksi_detail WHERE barang_id = ?");
    $check->execute([$barang_id]);
    $used_count = $check->fetchColumn();

    if ($used_count > 0) {
        // Ganti 'barang_tampil.php' dengan nama file halaman tabel barang Anda yang benar
        echo '<script>alert("Gagal menghapus! Barang tersebut masih digunakan pada transaksi."); window.location="index.php";</script>';
    } else {
        $delete = $pdo->prepare("DELETE FROM barang WHERE id = ?");
        $delete->execute([$barang_id]);
        echo '<script>alert("Data barang berhasil dihapus."); window.location="index.php";</script>';
    }
} else {
    echo '<script>alert("ID Barang tidak valid!"); window.location="index.php";</script>';
}
?>