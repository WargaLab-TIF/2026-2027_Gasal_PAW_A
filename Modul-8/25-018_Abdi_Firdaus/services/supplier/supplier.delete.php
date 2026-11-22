<?php
// hapus supplier

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
  setFlash('danger', 'ID supplier tidak valid.');
  header("Location: " . routeUrl("/supplier"));
  exit();
}

// cek apakah supplier masih dipakai data barang
$cek = $conn->prepare("SELECT COUNT(*) FROM barang WHERE supplier_id = ?");
$cek->execute([$id]);
if ((int) $cek->fetchColumn() > 0) {
  setFlash('danger', 'Data supplier tidak dapat dihapus karena masih memiliki data barang terkait!');
  header("Location: " . routeUrl("/supplier"));
  exit();
}

$stmt = $conn->prepare("DELETE FROM supplier WHERE id = ?");
$stmt->execute([$id]);

setFlash('success', 'Data supplier berhasil dihapus.');
header("Location: " . routeUrl("/supplier"));
exit();
