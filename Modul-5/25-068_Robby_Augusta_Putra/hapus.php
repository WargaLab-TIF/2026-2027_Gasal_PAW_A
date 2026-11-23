<?php
require 'koneksi.php';

$id = (int) ($_GET['id'] ?? 0);

try {
  $stmt = $DBH->prepare("DELETE FROM supplier WHERE id = ?");
  $stmt->execute([$id]);
  header('Location: index.php?pesan=dihapus');
} catch (PDOException $e) {
  header('Location: index.php?pesan=gagal_hapus');
}
exit;
