<?php
// tambah transaksi

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: " . routeUrl("/transaksi"));
  exit();
}

$waktuTransaksi = trim((string) ($_POST['waktu_transaksi'] ?? ''));
$pelangganId = trim((string) ($_POST['pelanggan_id'] ?? ''));
$keterangan = trim((string) ($_POST['keterangan'] ?? ''));

if ($waktuTransaksi === '' || $pelangganId === '' || $keterangan === '') {
  setFlash('danger', 'Semua field transaksi wajib diisi!');
  header("Location: " . routeUrl("/transaksi/add"));
  exit();
}

$stmt = $conn->prepare("
  INSERT INTO transaksi (waktu_transaksi, keterangan, total, pelanggan_id)
  VALUES (?, ?, 0, ?)
");
$stmt->execute([$waktuTransaksi, $keterangan, $pelangganId]);
$newId = (int) $conn->lastInsertId();

setFlash('success', "Transaksi #$newId berhasil ditambahkan. Silakan input detail barang.");
header("Location: " . routeUrl("/transaksi") . "?transaksi_id=" . $newId);
exit();
