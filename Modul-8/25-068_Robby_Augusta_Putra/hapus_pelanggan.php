<?php
include 'cek_login.php';
if ($_SESSION['level'] != 1) { 
    header('Location: index.php');
    exit;
}
include 'koneksi.php';

$id = $_GET['id'] ?? '';

try {
  $stmt = $db->prepare("DELETE FROM pelanggan WHERE id = ?");
  $stmt->execute([$id]);
  header('Location: pelanggan.php?pesan=dihapus');
} catch (PDOException $e) {
  header('Location: pelanggan.php?pesan=gagal_hapus');
}
exit;
