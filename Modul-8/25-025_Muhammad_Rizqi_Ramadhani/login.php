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
</head>
<body>
    <h2>Login Aplikasi</h2>

    <?php if ($error !== ""): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <div>
            <label for="username">Username</label><br>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($oldUsername) ?>" required>
        </div>
        <br>
        <div>
            <label for="password">Password</label><br>
            <input type="password" id="password" name="password" required>
        </div>
        <br>
        <button type="submit">Login</button>
    </form>
</body>
</html>