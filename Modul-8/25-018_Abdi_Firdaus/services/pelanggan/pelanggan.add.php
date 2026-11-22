<?php
// tambah pelanggan

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: " . routeUrl("/pelanggan"));
  exit();
}

$id = trim((string) ($_POST['id'] ?? ''));
$nama = trim((string) ($_POST['nama'] ?? ''));
$jenisKelamin = trim((string) ($_POST['jenis_kelamin'] ?? ''));
$telp = trim((string) ($_POST['telp'] ?? ''));
$alamat = trim((string) ($_POST['alamat'] ?? ''));

if ($nama === '' || !in_array($jenisKelamin, ['L', 'P'], true) || $telp === '' || $alamat === '') {
  setFlash('danger', 'Nama, jenis kelamin, telepon, dan alamat pelanggan wajib diisi!');
  header("Location: " . routeUrl("/pelanggan/add"));
  exit();
}

// buat id otomatis kalau dikosongkan
if ($id === '') {
  $nextNum = (int) $conn->query("SELECT COALESCE(MAX(CAST(id AS UNSIGNED)), 0) + 1 FROM pelanggan")->fetchColumn();
  $cekStmt = $conn->prepare("SELECT COUNT(*) FROM pelanggan WHERE id = ?");
  while (true) {
    $candidate = (string) $nextNum;
    $cekStmt->execute([$candidate]);
    if ((int) $cekStmt->fetchColumn() === 0) {
      $id = $candidate;
      break;
    }
    $nextNum++;
  }
} else {
  $cekId = $conn->prepare("SELECT COUNT(*) FROM pelanggan WHERE id = ?");
  $cekId->execute([$id]);
  if ((int) $cekId->fetchColumn() > 0) {
    setFlash('danger', "ID pelanggan '$id' sudah digunakan. Gunakan ID lain atau kosongkan untuk otomatis.");
    header("Location: " . routeUrl("/pelanggan/add"));
    exit();
  }
}

$stmt = $conn->prepare("
  INSERT INTO pelanggan (id, nama, jenis_kelamin, telp, alamat)
  VALUES (?, ?, ?, ?, ?)
");
$stmt->execute([$id, $nama, $jenisKelamin, $telp, $alamat]);

setFlash('success', "Data pelanggan '$nama' (ID: $id) berhasil ditambahkan.");
header("Location: " . routeUrl("/pelanggan"));
exit();
