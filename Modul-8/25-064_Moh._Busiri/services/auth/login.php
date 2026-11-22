<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: ' . url('login'));
  exit();
}

$username = sanitazion($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($username) || empty($password)) {
  setFlash('error', 'Silakan masukkan username dan password.');
  header('Location: ' . url('login'));
  exit();
}

$stmt = $conn->prepare("SELECT * FROM user WHERE username = ? LIMIT 1");
$stmt->execute([$username]);
$user = $stmt->fetch();

$isValid = false;
if ($user) {
  $hash_md5 = md5($password);
  if ($user['password'] === $hash_md5 || $user['password'] === $password || password_verify($password, $user['password'])) {
    $isValid = true;
  }
}

if ($isValid) {
  $_SESSION['user_id'] = $user['id_user'];
  $_SESSION['username'] = $user['username'];
  $_SESSION['nama'] = $user['nama'];
  $_SESSION['level'] = (int)$user['level'];

  setFlash('success', 'Selamat datang, ' . htmlspecialchars($user['nama']) . '!');
  header('Location: ' . url('home'));
  exit();
} else {
  setFlash('error', 'Username atau password yang Anda masukkan salah.');
  header('Location: ' . url('login'));
  exit();
}
