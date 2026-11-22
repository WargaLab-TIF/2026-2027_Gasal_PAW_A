<?php
// edit barang

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: " . routeUrl("/barang"));
  exit();
}

$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
$kodeBarang = trim((string) ($_POST['kode_barang'] ?? ''));
$namaBarang = trim((string) ($_POST['nama_barang'] ?? ''));
$harga = (int) ($_POST['harga'] ?? -1);
$stok = (int) ($_POST['stok'] ?? -1);
$supplierId = (int) ($_POST['supplier_id'] ?? 0);

if ($id <= 0 || $kodeBarang === '' || $namaBarang === '' || $harga < 0 || $stok < 0 || $supplierId <= 0) {
  setFlash('danger', 'Data barang tidak valid. Pastikan semua field terisi dengan benar.');
  header("Location: " . routeUrl("/barang/edit") . "?id=" . $id);
  exit();
}

$stmt = $conn->prepare("
  UPDATE barang
  SET kode_barang = ?, nama_barang = ?, harga = ?, stok = ?, supplier_id = ?
  WHERE id = ?
");
$stmt->execute([$kodeBarang, $namaBarang, $harga, $stok, $supplierId, $id]);

setFlash('success', "Data barang '$namaBarang' berhasil diperbarui.");
header("Location: " . routeUrl("/barang"));
exit();
