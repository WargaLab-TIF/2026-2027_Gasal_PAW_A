<?php
// tambah supplier

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: " . routeUrl("/supplier"));
  exit();
}

$nama = trim((string) ($_POST['nama'] ?? ''));
$telp = trim((string) ($_POST['telp'] ?? ''));
$alamat = trim((string) ($_POST['alamat'] ?? ''));

if ($nama === '' || $telp === '' || $alamat === '') {
  setFlash('danger', 'Nama, telepon, dan alamat supplier wajib diisi!');
  header("Location: " . routeUrl("/supplier/add"));
  exit();
}

$stmt = $conn->prepare("INSERT INTO supplier (nama, telp, alamat) VALUES (?, ?, ?)");
$stmt->execute([$nama, $telp, $alamat]);

setFlash('success', "Data supplier '$nama' berhasil ditambahkan.");
header("Location: " . routeUrl("/supplier"));
exit();
