<?php
// Hapus Data Master. Dipanggil lewat /api/{entity}/delete (POST)
// Variabel dari router: $conn, $entity, $cfg

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
  redirect("/$entity");
}
csrf_check();

$id = trim($_POST['id'] ?? '');

// Tidak boleh menghapus akun yang sedang login
if ($entity === 'users' && (int) $id === $_SESSION['user']['id']) {
  set_flash('error', 'Anda tidak dapat menghapus akun yang sedang login.');
  redirect("/$entity");
}

try {
  $stmt = $conn->prepare("DELETE FROM `{$cfg['table']}` WHERE `{$cfg['pk']}` = ?");
  $stmt->execute([$id]);
  set_flash('success', 'Data berhasil dihapus.');
} catch (PDOException $e) {
  if ($e->getCode() !== '23000') {
    throw $e;
  }
  // Masih dipakai tabel lain (foreign key), mis. barang yang sudah ada di transaksi
  set_flash('error', 'Data tidak dapat dihapus karena masih dipakai data lain.');
}

redirect("/$entity");
