<?php
// update data user

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: " . routeUrl("/user"));
  exit();
}

$idUser = (int) ($_GET['id'] ?? $_POST['id_user'] ?? 0);
$username = trim((string) ($_POST['username'] ?? ''));
$password = (string) ($_POST['password'] ?? '');
$nama = trim((string) ($_POST['nama'] ?? ''));
$alamat = trim((string) ($_POST['alamat'] ?? ''));
$hp = trim((string) ($_POST['hp'] ?? ''));
$level = (int) ($_POST['level'] ?? 0);

if ($idUser <= 0 || $username === '' || $nama === '' || $alamat === '' || $hp === '' || !in_array($level, [1, 2], true)) {
  setFlash('danger', 'Data user tidak valid. Pastikan semua field wajib terisi.');
  header("Location: " . routeUrl("/user/edit") . "?id=" . $idUser);
  exit();
}

// cegah owner ubah level sendiri
if ($idUser === (int) ($_SESSION['user_id'] ?? 0) && $level !== 1) {
  setFlash('warning', 'Anda tidak dapat menurunkan level akun sendiri yang sedang aktif login.');
  header("Location: " . routeUrl("/user/edit") . "?id=" . $idUser);
  exit();
}

// cek username duplikat
$cek = $conn->prepare("SELECT COUNT(*) FROM user WHERE username = ? AND id_user != ?");
$cek->execute([$username, $idUser]);
if ((int) $cek->fetchColumn() > 0) {
  setFlash('danger', "Username '$username' sudah digunakan oleh user lain.");
  header("Location: " . routeUrl("/user/edit") . "?id=" . $idUser);
  exit();
}

if ($password !== '') {
  // ganti password kalau diisi
  $stmt = $conn->prepare("
    UPDATE user
    SET username = ?, password = ?, nama = ?, alamat = ?, hp = ?, level = ?
    WHERE id_user = ?
  ");
  $stmt->execute([$username, md5($password), $nama, $alamat, $hp, $level, $idUser]);
} else {
  // pakai password lama kalau kosong
  $stmt = $conn->prepare("
    UPDATE user
    SET username = ?, nama = ?, alamat = ?, hp = ?, level = ?
    WHERE id_user = ?
  ");
  $stmt->execute([$username, $nama, $alamat, $hp, $level, $idUser]);
}

// update session kalau edit akun sendiri
if ($idUser === (int) ($_SESSION['user_id'] ?? 0)) {
  $_SESSION['username'] = $username;
  $_SESSION['nama'] = $nama;
  $_SESSION['level'] = $level;
}

setFlash('success', "Data user '$nama' berhasil diperbarui.");
header("Location: " . routeUrl("/user"));
exit();
