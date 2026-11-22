<?php
include "fungsi.php";

// Jika sudah login, tidak boleh membuka halaman login lagi
if (isset($_SESSION["login"])) {
    header("Location: index.php");
    exit;
}

// Pesan dari aksi.php (dikirim lewat URL)
$pesan = "";
$kelas = "";
if (isset($_GET["error"]) && $_GET["error"] === "kosong") {
    $pesan = "Username dan password wajib diisi.";
    $kelas = "danger";
} elseif (isset($_GET["error"]) && $_GET["error"] === "salah") {
    $pesan = "Username atau password salah.";
    $kelas = "danger";
} elseif (isset($_GET["logout"])) {
    $pesan = "Anda sudah logout.";
    $kelas = "info";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="login-box">
    <h2>LOGIN</h2>
    <?php if ($pesan != "") { ?>
        <div class="alert <?= $kelas ?>"><?= e($pesan) ?></div>
    <?php } ?>
    <form method="POST" action="aksi.php?aksi=login">
        <label>Username</label>
        <input type="text" name="username" autofocus>
        <label>Password</label>
        <input type="password" name="password">
        <button>Masuk</button>
    </form>
</div>
</body>
</html>
