<?php
// Dipanggil lewat route /sig-in (POST dari form login)

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
  redirect("/login");
}
csrf_check();

$username = trim($_POST['username'] ?? "");
$password = $_POST['password'] ?? "";   // password tidak di-trim / tidak di-escape

if ($username === "" || $password === "") {
  set_flash('error', 'Username dan password wajib diisi.');
  redirect("/login");
}

// Prepared statement -> aman dari SQL Injection
$stmt = $conn->prepare("SELECT id_user, username, password, nama, level FROM `user` WHERE username = ? LIMIT 1");
$stmt->execute([$username]);
$user = $stmt->fetch();

$valid  = false;
$legacy = false;
if ($user) {
  $stored = $user['password'];
  if (substr($stored, 0, 1) === '$') {
    // Hash modern hasil password_hash() (bcrypt/argon2)
    $valid = password_verify($password, $stored);
  } elseif (preg_match('/^[a-f0-9]{32}$/i', $stored)) {
    // Hash MD5 lama dari data dummy: dicocokkan sekali, lalu langsung di-upgrade di bawah
    $valid  = hash_equals(strtolower($stored), md5($password));
    $legacy = $valid;
  }
}

if ($valid && in_array((int) $user['level'], [LEVEL_OWNER, LEVEL_KASIR], true)) {

  if ($legacy) {
    try {
      $up = $conn->prepare("UPDATE `user` SET password = ? WHERE id_user = ?");
      $up->execute([password_hash($password, PASSWORD_DEFAULT), $user['id_user']]);
    } catch (PDOException $e) {
      // kolom password belum diperlebar (database/auth.sql belum dijalankan): login tetap lanjut
    }
  }

  session_regenerate_id(true);          // ganti session ID setelah login (cegah session fixation)

  // SET SESSION (sesuai materi Modul 8)
  $_SESSION['login']    = true;
  $_SESSION['username'] = $user['username'];
  $_SESSION['user']     = [
    'id'       => (int) $user['id_user'],
    'username' => $user['username'],
    'nama'     => $user['nama'],        // ditampilkan di navbar
    'level'    => (int) $user['level'], // 1 = owner, 2 = kasir
  ];

  redirect("/");
}

// Pesan sengaja umum: tidak membocorkan username atau password mana yang salah
set_flash('error', 'Username atau password salah.');
redirect("/login");
