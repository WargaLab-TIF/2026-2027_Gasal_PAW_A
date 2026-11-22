<?php

require_once "koneksi.php";
require_once "auth.php";

requireLevel([1, 2]);

$level = (int) $_SESSION["level"];
$namaUser = $_SESSION["nama"] ?: $_SESSION["username"];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Home / Dashboard</title>
</head>
<body>
    <?php require_once "navbar.php"; ?>

    <h1>Selamat Datang, <?= htmlspecialchars($namaUser) ?>!</h1>
    <p>Username: <strong><?= htmlspecialchars($_SESSION["username"]) ?></strong></p>
    <p>Level: <strong><?= $level ?> (<?= htmlspecialchars(getNamaLevel($level)) ?>)</strong></p>
</body>
</html>