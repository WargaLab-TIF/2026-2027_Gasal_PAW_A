<?php
// hapus pelanggan

$id = trim((string) ($_GET['id'] ?? ''));

if ($id === '') {
  setFlash('danger', 'ID pelanggan tidak valid.');
  header("Location: " . routeUrl("/pelanggan"));
  exit();
}

// cek apakah pelanggan masih punya transaksi
$cek = $conn->prepare("SELECT COUNT(*) FROM transaksi WHERE pelanggan_id = ?");
$cek->execute([$id]);
if ((int) $cek->fetchColumn() > 0) {
  setFlash('danger', 'Data pelanggan tidak dapat dihapus karena masih memiliki riwayat transaksi!');
  header("Location: " . routeUrl("/pelanggan"));
  exit();
}

$stmt = $conn->prepare("DELETE FROM pelanggan WHERE id = ?");
$stmt->execute([$id]);

setFlash('success', 'Data pelanggan berhasil dihapus.');
header("Location: " . routeUrl("/pelanggan"));
exit();
