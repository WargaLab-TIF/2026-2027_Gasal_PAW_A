<?php
session_start();

if (isset($_SESSION['id_user'])) {
    header('Location: index.php');
    exit;
}

require_once 'koneksi.php';
$error = '';

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM user WHERE username = :username";
    $stmt = $DBH->prepare($sql);
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch();

    if ($user) {
        $valid = password_verify($password, $user['password']);

        if (!$valid && hash_equals($user['password'], $password)) {
            $valid = true;
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $update = $DBH->prepare("UPDATE user SET password = :password WHERE id_user = :id");
            $update->execute([
                ':password' => $hash,
                ':id' => $user['id_user']
            ]);
        }

        if ($valid) {
            session_regenerate_id(true);
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['level'] = $user['level'];

            header('Location: index.php');
            exit;
        }
    }
    $error = 'Username atau password salah!';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Sistem Penjualan</title>
    <style>
        body { font-family: Arial; background: #ffffff; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .login-box { border: 1px solid black; background: white; padding: 25px; width: 300px; border-radius: 8px; }
        input, button { width: 100%; padding: 10px; margin: 8px 0; box-sizing: border-box; }
        button { background: #0f172a; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <?php if ($error != ''): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <div class="login-box">
        <h2>Login Sistem Penjualan</h2>
        <form method="POST">
            <label>Username</label>
            <input type="text" name="username" required>
            <label>Password</label>
            <input type="password" name="password" required>
            <button type="submit" name="login">Login</button>
        </form>
    </div>
</body>
</html>