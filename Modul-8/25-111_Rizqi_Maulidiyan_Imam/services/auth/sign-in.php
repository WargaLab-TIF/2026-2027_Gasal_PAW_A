<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/login');
}
if (isset($_SESSION['login'])) {
    redirect('/home');
}

$username = bersih($_POST['username'] ?? '');
$password = md5($_POST['password'] ?? '');

$stmt = $DBH->prepare("
    SELECT id_user, username, nama, level
    FROM user
    WHERE username = ? AND password = ?
");
$stmt->execute([$username, $password]);
$user = $stmt->fetch();

if (!$user) {
    $_SESSION['error'] = 'Username atau password salah.';
    redirect('/login');
}

if (!in_array((int)$user['level'], [1, 2])) {
    $_SESSION['error'] = 'Level user ini tidak memiliki akses ke sistem.';
    redirect('/login');
}

session_regenerate_id(true);
$_SESSION['login']    = true;
$_SESSION['id_user']  = $user['id_user'];
$_SESSION['username'] = $user['username'];
$_SESSION['nama']     = $user['nama'];
$_SESSION['level']    = (int)$user['level'];

redirect('/home');