<?php
require_once dirname(__DIR__, 2) . '/conn.php';
require_once dirname(__DIR__, 2) . '/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . page_url('login'));
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$query = 'SELECT id_user, username, password, nama, level
          FROM user
          WHERE username = ?
          LIMIT 1';
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, 's', $username);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

$valid = $user && (
    password_verify($password, $user['password'])
    || hash_equals((string) $user['password'], $password)
);
if (!$valid) {
    $_SESSION['login_error'] = 'Username atau password salah.';
    header('Location: ' . page_url('login'));
    exit;
}

if (password_needs_rehash($user['password'], PASSWORD_DEFAULT) || !password_verify($password, $user['password'])) {
    mysqli_query($koneksi, 'ALTER TABLE `user` MODIFY `password` VARCHAR(255)');
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $update = mysqli_prepare($koneksi, 'UPDATE user SET password = ? WHERE id_user = ?');
    mysqli_stmt_bind_param($update, 'si', $hash, $user['id_user']);
    mysqli_stmt_execute($update);
    mysqli_stmt_close($update);
}

session_regenerate_id(true);
$_SESSION['user'] = [
    'id_user' => (int) $user['id_user'],
    'username' => $user['username'],
    'nama' => $user['nama'],
    'level' => (int) $user['level'],
];
header('Location: ' . page_url('home'));
exit;
