<?php
// tambah barang

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: " . routeUrl("/barang"));
  exit();
}

$kodeBarang = trim((string) ($_POST['kode_barang'] ?? ''));
$namaBarang = trim((string) ($_POST['nama_barang'] ?? ''));
$harga = (int) ($_POST['harga'] ?? -1);
$stok = (int) ($_POST['stok'] ?? -1);
$supplierId = (int) ($_POST['supplier_id'] ?? 0);

if ($kodeBarang === '' || $namaBarang === '' || $harga < 0 || $stok < 0 || $supplierId <= 0) {
  setFlash('danger', 'Semua field data barang wajib diisi dengan benar!');
  header("Location: " . routeUrl("/barang/add"));
  exit();
}

$stmt = $conn->prepare("
  INSERT INTO barang (kode_barang, nama_barang, harga, stok, supplier_id)
  VALUES (?, ?, ?, ?, ?)
");
$stmt->execute([$kodeBarang, $namaBarang, $harga, $stok, $supplierId]);

setFlash('success', "Data barang '$namaBarang' berhasil ditambahkan.");
header("Location: " . routeUrl("/barang"));
exit();
