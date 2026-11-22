<?php
// simpan user baru

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: " . routeUrl("/user"));
  exit();
}

$username = trim((string) ($_POST['username'] ?? ''));
$password = (string) ($_POST['password'] ?? '');
$nama = trim((string) ($_POST['nama'] ?? ''));
$alamat = trim((string) ($_POST['alamat'] ?? ''));
$hp = trim((string) ($_POST['hp'] ?? ''));
$level = (int) ($_POST['level'] ?? 0);

if ($username === '' || $password === '' || $nama === '' || $alamat === '' || $hp === '' || !in_array($level, [1, 2], true)) {
  setFlash('danger', 'Semua field data user wajib diisi dengan benar!');
  header("Location: " . routeUrl("/user/add"));
  exit();
}

// cek username duplikat
$cek = $conn->prepare("SELECT COUNT(*) FROM user WHERE username = ?");
$cek->execute([$username]);
if ((int) $cek->fetchColumn() > 0) {
  setFlash('danger', "Username '$username' sudah terdaftar. Silakan gunakan username lain.");
  header("Location: " . routeUrl("/user/add"));
  exit();
}

// hash password
$passwordHash = md5($password);

$stmt = $conn->prepare("
  INSERT INTO user (username, password, nama, alamat, hp, level)
  VALUES (?, ?, ?, ?, ?, ?)
");
$stmt->execute([$username, $passwordHash, $nama, $alamat, $hp, $level]);

setFlash('success', "User '$nama' (@$username) berhasil ditambahkan.");
header("Location: " . routeUrl("/user"));
exit();
