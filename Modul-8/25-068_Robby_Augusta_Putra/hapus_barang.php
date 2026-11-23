<?php
include 'cek_login.php';
if ($_SESSION['level'] != 1) { 
    header('Location: index.php');
    exit;
}
include 'koneksi.php';

$id = (int) ($_GET['id'] ?? 0);

try {
  $stmt = $db->prepare("DELETE FROM barang WHERE id = ?");
  $stmt->execute([$id]);
  header('Location: barang.php?pesan=dihapus');
} catch (PDOException $e) {
  header('Location: barang.php?pesan=gagal_hapus');
}
exit;
