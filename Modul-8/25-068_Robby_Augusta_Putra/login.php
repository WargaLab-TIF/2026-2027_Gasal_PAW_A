<?php
session_start();
if (isset($_SESSION['user_login'])) {
    header('Location: index.php');
    exit;
}
include 'koneksi.php';

$error = '';
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $stmt = $db->prepare("SELECT * FROM user WHERE username = ? AND password = ?");
    $stmt->execute([$username, $password]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user_login'] = $user['nama'];
        $_SESSION['level']      = $user['level'];
        header('Location: index.php');
        exit;
    } else {
        $error = 'Username atau password salah';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Login</h2>
    <form method="POST">
        <?php if ($error != '') { ?>
            <p style="color:red"><?= $error ?></p>
        <?php } ?>
        <div class="baris"><label>Username</label><input type="text" name="username" required></div>
        <div class="baris"><label>Password</label><input type="password" name="password" required></div>
        <div class="tombol"><button type="submit" name="login">Login</button></div>
    </form>
    <p>Belum punya akun? <a href="signup.php">Daftar</a></p>
</body>
</html>