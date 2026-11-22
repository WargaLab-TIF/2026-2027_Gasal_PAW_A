<?php
// hapus barang

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
  setFlash('danger', 'ID barang tidak valid.');
  header("Location: " . routeUrl("/barang"));
  exit();
}

// cek apakah barang masih dipakai di detail transaksi
$cek = $conn->prepare("SELECT COUNT(*) FROM transaksi_detail WHERE barang_id = ?");
$cek->execute([$id]);
if ((int) $cek->fetchColumn() > 0) {
  setFlash('danger', 'Data barang tidak dapat dihapus karena masih digunakan pada detail transaksi!');
  header("Location: " . routeUrl("/barang"));
  exit();
}

$stmt = $conn->prepare("DELETE FROM barang WHERE id = ?");
$stmt->execute([$id]);

setFlash('success', 'Data barang berhasil dihapus.');
header("Location: " . routeUrl("/barang"));
exit();
