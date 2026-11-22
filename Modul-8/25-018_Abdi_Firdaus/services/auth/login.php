<?php
// proses login

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: " . routeUrl("/login"));
  exit();
}

$username = trim((string) ($_POST['username'] ?? ''));
$password = (string) ($_POST['password'] ?? '');

// cek input kosong
if ($username === '' || $password === '') {
  $_SESSION['old_username'] = $username;
  setFlash('danger', 'Username dan password wajib diisi!');
  header("Location: " . routeUrl("/login"));
  exit();
}

// hash password
$passwordHash = md5($password);

$stmt = $conn->prepare("
  SELECT id_user, username, nama, alamat, hp, level
  FROM user
  WHERE username = ? AND password = ?
  LIMIT 1
");
$stmt->execute([$username, $passwordHash]);
$userData = $stmt->fetch();

if ($userData) {
  // ganti session id baru
  session_regenerate_id(true);

  // simpan data user ke session
  $_SESSION['is_login'] = true;
  $_SESSION['user_id'] = (int) $userData['id_user'];
  $_SESSION['username'] = $userData['username'];
  $_SESSION['nama'] = $userData['nama'];
  $_SESSION['level'] = (int) $userData['level'];

  unset($_SESSION['old_username']);
  setFlash('success', 'Login berhasil! Selamat datang, ' . $userData['nama'] . ' (' . levelLabel($userData['level']) . ').');
  header("Location: " . routeUrl("/"));
  exit();
}

$_SESSION['old_username'] = $username;
setFlash('danger', 'Login gagal! Username atau password salah.');
header("Location: " . routeUrl("/login"));
exit();
