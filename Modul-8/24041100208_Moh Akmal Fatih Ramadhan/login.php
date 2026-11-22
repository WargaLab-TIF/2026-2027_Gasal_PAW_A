
<?php

session_start();

require_once __DIR__ . '/auth.php';

// Jika user sudah login, arahkan ke Home
if (!empty($_SESSION['user_id'])) {

    header('Location: index.php');
    exit;

}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {

        $error = 'Username dan password wajib diisi.';

    } else {

        $stmt = $DBH->prepare(
            'SELECT id, nama, username, password, level
             FROM user
             WHERE username = ?
             LIMIT 1'
        );

        $stmt->execute([$username]);

        $user = $stmt->fetch();

        if (
            $user &&
            password_verify($password, $user['password']) &&
            in_array((int)$user['level'], [1, 2], true)
        ) {

            session_regenerate_id(true);

            $_SESSION['user_id'] = (int)$user['id'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['level'] = (int)$user['level'];

            header('Location: index.php');
            exit;

        } else {

            $error = 'Username atau password salah.';

        }

    }

}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login - Sistem Penjualan</title>

    <link rel="stylesheet" href="assets/style.css">
</head>

<body class="login-page">

<div class="login-card">

    <h1>Sistem Penjualan</h1>

    <p class="muted">
        Silakan masuk menggunakan akun Owner atau Kasir.
    </p>

    <?php if ($error): ?>

        <div class="alert error">
            <?= e($error) ?>
        </div>

    <?php endif; ?>

    <form method="post">

        <label>Username</label>

        <input
            name="username"
            required
            autofocus
        >

        <label>Password</label>

        <input
            type="password"
            name="password"
            required
        >

        <button type="submit" class="btn primary full">
            Login
        </button>

    </form>

</div>

</body>
</html>
