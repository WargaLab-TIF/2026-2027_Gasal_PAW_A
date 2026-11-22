<?php

require_once "koneksi.php";
require_once "auth.php";

redirectIfLoggedIn();

$error = "";
$oldUsername = "";

function verifyStoredPassword(string $inputPassword, string $storedPassword): bool
{
    $info = password_get_info($storedPassword);
    if (($info["algo"] ?? null) !== null) {
        return password_verify($inputPassword, $storedPassword);
    }

    if (preg_match('/^[a-f0-9]{32}$/i', $storedPassword)) {
        return hash_equals(strtolower($storedPassword), md5($inputPassword));
    }

    return hash_equals($storedPassword, $inputPassword);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $oldUsername = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($oldUsername === "" || $password === "") {
        $error = "Username dan password wajib diisi.";
    } else {
        $stmt = $conn->prepare(
            "SELECT id_user, username, password, nama, level FROM `user` WHERE username = :username LIMIT 1"
        );
        $stmt->execute(["username" => $oldUsername]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $level = $user ? (int) $user["level"] : 0;

        if ($user && in_array($level, [1, 2], true) && verifyStoredPassword($password, $user["password"])) {
            session_regenerate_id(true);

            $_SESSION["user_id"] = (int) $user["id_user"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["nama"] = $user["nama"];
            $_SESSION["level"] = $level;

            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Username atau password tidak valid.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - db_sale</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px 20px; }
        .form-container { background: #f9f9f9; padding: 18px; border: 1px solid #ddd; border-radius: 4px; max-width: 360px; margin: 0 auto; }
        .form-group { margin-bottom: 12px; }
        label { display: block; margin-bottom: 4px; font-weight: bold; }
        input { width: 100%; padding: 7px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        .btn { padding: 8px 14px; background: #0b4f8a; color: #fff; border: none; border-radius: 4px; cursor: pointer; width: 100%; }
        .alert-error { color: #dc3545; background: #f8d7da; border: 1px solid #f5c6cb; padding: 8px 10px; border-radius: 4px; margin-bottom: 12px; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2 style="margin-top: 0;">Login Aplikasi</h2>

        <?php if ($error !== ""): ?>
            <div class="alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($oldUsername) ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
    </div>
</body>
</html>