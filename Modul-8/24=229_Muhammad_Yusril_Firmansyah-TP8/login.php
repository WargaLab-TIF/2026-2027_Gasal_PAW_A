<?php
session_start();
require_once "config/koneksi.php";

if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT id_user, username, password, nama, level FROM `user` WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($data && password_verify($password, $data['password'])) {
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id_user' => $data['id_user'],
            'username' => $data['username'],
            'nama' => $data['nama'],
            'level' => (int)$data['level']
        ];
        header("Location: index.php");
        exit;
    } else {
        $error = "Username atau password salah.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login Sistem Penjualan</title>
<style>
*{box-sizing:border-box}body{margin:0;background:#eaf0f7;font-family:Arial,sans-serif;display:flex;align-items:center;justify-content:center;min-height:100vh}
.login{background:white;padding:30px;width: min(390px,92%);border-radius:12px;box-shadow:0 8px 28px #17395b20}
h2{text-align:center;color:#123f70;margin-top:0}label{font-size:14px;font-weight:bold}
input{width:100%;padding:12px;margin:7px 0 17px;border:1px solid #ccd5e1;border-radius:6px}
button{width:100%;padding:12px;background:#123f70;color:white;border:0;border-radius:6px;font-weight:bold}
.error{background:#ffe6e6;color:#a32626;padding:10px;border-radius:6px;font-size:14px}
.info{font-size:12px;color:#64748b;line-height:1.6;margin-top:18px}
</style>
</head>
<body>
<div class="login">
    <h2>Login Sistem Penjualan</h2>
    <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
    <form method="POST">
        <label>Username</label>
        <input type="text" name="username" required autofocus>
        <label>Password</label>
        <input type="password" name="password" required>
        <button type="submit">Login</button>
    </form>
    <div class="info">
        Akun pengujian:<br>
        Owner: budi / password<br>
        Kasir: wati / password
    </div>
</div>
</body>
</html>
