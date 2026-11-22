<?php
// edit supplier

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: " . routeUrl("/supplier"));
  exit();
}

$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
$nama = trim((string) ($_POST['nama'] ?? ''));
$telp = trim((string) ($_POST['telp'] ?? ''));
$alamat = trim((string) ($_POST['alamat'] ?? ''));

if ($id <= 0 || $nama === '' || $telp === '' || $alamat === '') {
  setFlash('danger', 'Semua field data supplier wajib diisi!');
  header("Location: " . routeUrl("/supplier/edit") . "?id=" . $id);
  exit();
}

$stmt = $conn->prepare("UPDATE supplier SET nama = ?, telp = ?, alamat = ? WHERE id = ?");
$stmt->execute([$nama, $telp, $alamat, $id]);

setFlash('success', "Data supplier '$nama' berhasil diperbarui.");
header("Location: " . routeUrl("/supplier"));
exit();
