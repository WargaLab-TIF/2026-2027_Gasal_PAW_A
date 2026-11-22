<?php
include 'cek_login.php';
if ($_SESSION['level'] != 1) { 
    header('Location: index.php');
    exit;
}
include 'koneksi.php';

$id = (int) ($_GET['id'] ?? 0);

// Cegah menghapus akun yang sedang login.
// Sesuaikan key session ini dengan yang diset di proses login Anda.
if (isset($_SESSION['id_user']) && (int) $_SESSION['id_user'] === $id) {
  header('Location: user.php?pesan=hapus_diri');
  exit;
}

try {
  $stmt = $db->prepare("DELETE FROM user WHERE id_user = ?");
  $stmt->execute([$id]);
  header('Location: user.php?pesan=dihapus');
} catch (PDOException $e) {
  header('Location: user.php?pesan=gagal_hapus');
}
exit;
