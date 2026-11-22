<?php
// Dipanggil lewat route /logout

// UNSET SESSION: hapus semua data session sesuai materi Modul 8
unset($_SESSION['login']);
unset($_SESSION['username']);
unset($_SESSION['user']);
$_SESSION = [];

// Hapus juga cookie session di browser
if (ini_get('session.use_cookies')) {
  $p = session_get_cookie_params();
  setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
}

// Hancurkan session di server, lalu kembali ke halaman login
session_destroy();

redirect("/login");
