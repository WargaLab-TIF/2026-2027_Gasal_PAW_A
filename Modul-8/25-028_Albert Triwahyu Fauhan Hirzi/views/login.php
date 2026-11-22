<?php
require_once dirname(__DIR__) . '/auth.php';
if (sudah_login()) {
    header('Location: ' . page_url('home'));
    exit;
}
$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Sistem Penjualan</title>
    <link rel="stylesheet" href="assets/css/auth.css">
</head>
<body class="login-page">
    <main class="login-card">
        <h1>Sistem Penjualan</h1>
        <p>Silakan masuk ke akun Anda.</p>
        <?php if ($error): ?><div class="error"><?= e($error) ?></div><?php endif; ?>
        <form method="post" action="<?= e(page_url('login-submit')) ?>">
            <label>Username<input name="username" autocomplete="username" required autofocus></label>
            <label>Password<input type="password" name="password" autocomplete="current-password" required></label>
            <button type="submit">Masuk</button>
        </form>
        <small>Akun awal: owner / owner123, kasir / kasir123</small>
    </main>
</body>
</html>
