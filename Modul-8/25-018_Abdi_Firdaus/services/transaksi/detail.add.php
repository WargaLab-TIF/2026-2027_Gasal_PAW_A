<?php
// tambah detail transaksi dan hitung ulang total

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: " . routeUrl("/transaksi"));
  exit();
}

$transaksiId = (int) ($_POST['transaksi_id'] ?? 0);
$barangId = (int) ($_POST['barang_id'] ?? 0);
$qty = (int) ($_POST['qty'] ?? 0);

if ($transaksiId <= 0 || $barangId <= 0 || $qty <= 0) {
  setFlash('danger', 'Transaksi, barang, dan jumlah (qty minimal 1) wajib diisi.');
  header("Location: " . routeUrl("/transaksi") . ($transaksiId > 0 ? "?transaksi_id=$transaksiId" : ""));
  exit();
}

// cek barang duplikat di transaksi yang sama
$cekDup = $conn->prepare("
  SELECT COUNT(*) FROM transaksi_detail WHERE transaksi_id = ? AND barang_id = ?
");
$cekDup->execute([$transaksiId, $barangId]);
if ((int) $cekDup->fetchColumn() > 0) {
  setFlash('warning', 'Barang tersebut sudah ada pada detail transaksi #' . $transaksiId . '.');
  header("Location: " . routeUrl("/transaksi") . "?transaksi_id=" . $transaksiId);
  exit();
}

// ambil harga barang
$stmtBarang = $conn->prepare("SELECT harga FROM barang WHERE id = ? LIMIT 1");
$stmtBarang->execute([$barangId]);
$barang = $stmtBarang->fetch();
if (!$barang) {
  setFlash('danger', 'Data barang tidak ditemukan.');
  header("Location: " . routeUrl("/transaksi") . "?transaksi_id=" . $transaksiId);
  exit();
}

$harga = (int) $barang['harga'];

// Simpan detail transaksi
$insert = $conn->prepare("
  INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty)
  VALUES (?, ?, ?, ?)
");
$insert->execute([$transaksiId, $barangId, $harga, $qty]);

// hitung ulang total transaksi
$stmtTotal = $conn->prepare("
  SELECT COALESCE(SUM(harga * qty), 0) AS total_baru
  FROM transaksi_detail
  WHERE transaksi_id = ?
");
$stmtTotal->execute([$transaksiId]);
$totalBaru = (int) $stmtTotal->fetchColumn();

$update = $conn->prepare("UPDATE transaksi SET total = ? WHERE id = ?");
$update->execute([$totalBaru, $transaksiId]);

setFlash('success', "Detail transaksi berhasil ditambahkan. Total transaksi #$transaksiId diperbarui menjadi " . formatRupiah($totalBaru) . ".");
header("Location: " . routeUrl("/transaksi") . "?transaksi_id=" . $transaksiId);
exit();
