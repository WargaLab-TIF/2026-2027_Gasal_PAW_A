<?php
// edit pelanggan

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: " . routeUrl("/pelanggan"));
  exit();
}

$id = trim((string) ($_GET['id'] ?? $_POST['id'] ?? ''));
$nama = trim((string) ($_POST['nama'] ?? ''));
$jenisKelamin = trim((string) ($_POST['jenis_kelamin'] ?? ''));
$telp = trim((string) ($_POST['telp'] ?? ''));
$alamat = trim((string) ($_POST['alamat'] ?? ''));

if ($id === '' || $nama === '' || !in_array($jenisKelamin, ['L', 'P'], true) || $telp === '' || $alamat === '') {
  setFlash('danger', 'Semua field data pelanggan wajib diisi dengan benar!');
  header("Location: " . routeUrl("/pelanggan/edit") . "?id=" . urlencode($id));
  exit();
}

$stmt = $conn->prepare("
  UPDATE pelanggan
  SET nama = ?, jenis_kelamin = ?, telp = ?, alamat = ?
  WHERE id = ?
");
$stmt->execute([$nama, $jenisKelamin, $telp, $alamat, $id]);

setFlash('success', "Data pelanggan '$nama' berhasil diperbarui.");
header("Location: " . routeUrl("/pelanggan"));
exit();
