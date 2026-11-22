<?php
// hapus user

$idUser = (int) ($_GET['id'] ?? 0);

if ($idUser <= 0) {
  setFlash('danger', 'ID user tidak valid.');
  header("Location: " . routeUrl("/user"));
  exit();
}

// jangan hapus akun sendiri
if ($idUser === (int) ($_SESSION['user_id'] ?? 0)) {
  setFlash('warning', 'Tidak dapat menghapus akun yang sedang Anda gunakan untuk login!');
  header("Location: " . routeUrl("/user"));
  exit();
}

$stmt = $conn->prepare("DELETE FROM user WHERE id_user = ?");
$stmt->execute([$idUser]);

setFlash('success', 'Data user berhasil dihapus.');
header("Location: " . routeUrl("/user"));
exit();
