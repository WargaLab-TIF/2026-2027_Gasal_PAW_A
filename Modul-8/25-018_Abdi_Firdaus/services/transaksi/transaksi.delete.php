<?php
// hapus transaksi

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
  setFlash('danger', 'ID transaksi tidak valid.');
  header("Location: " . routeUrl("/transaksi"));
  exit();
}

$stmt = $conn->prepare("DELETE FROM transaksi WHERE id = ?");
$stmt->execute([$id]);

setFlash('success', "Transaksi #$id berhasil dihapus.");
header("Location: " . routeUrl("/transaksi"));
exit();
