<?php
// hapus detail transaksi dan hitung ulang total

$transaksiId = (int) ($_GET['transaksi_id'] ?? 0);
$barangId = (int) ($_GET['barang_id'] ?? 0);

if ($transaksiId <= 0 || $barangId <= 0) {
  setFlash('danger', 'Parameter detail transaksi tidak valid.');
  header("Location: " . routeUrl("/transaksi"));
  exit();
}

$stmtDelete = $conn->prepare("
  DELETE FROM transaksi_detail WHERE transaksi_id = ? AND barang_id = ?
");
$stmtDelete->execute([$transaksiId, $barangId]);

// hitung ulang total transaksi
$stmtTotal = $conn->prepare("
  SELECT COALESCE(SUM(harga * qty), 0) AS total_baru
  FROM transaksi_detail
  WHERE transaksi_id = ?
");
$stmtTotal->execute([$transaksiId]);
$totalBaru = (int) $stmtTotal->fetchColumn();

$stmtUpdate = $conn->prepare("UPDATE transaksi SET total = ? WHERE id = ?");
$stmtUpdate->execute([$totalBaru, $transaksiId]);

setFlash('success', "Item detail transaksi berhasil dihapus. Total transaksi #$transaksiId sekarang " . formatRupiah($totalBaru) . ".");
header("Location: " . routeUrl("/transaksi") . "?transaksi_id=" . $transaksiId);
exit();
