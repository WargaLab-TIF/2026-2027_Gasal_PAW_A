<?php
define("DI_INDEX", true);
include "cek_login.php";   // belum login -> diarahkan ke login.php

// Halaman yang dibuka (default: home)
$p = $_GET["p"] ?? "home";
if (!is_string($p)) {
    $p = "home";
}

// Kasir (level 2) tidak boleh membuka halaman Data Master walau lewat URL
if (halaman_owner($p)) {
    wajib_owner();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Penjualan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
    <div class="kiri">
        <a class="brand" href="index.php">Sistem Penjualan</a>
        <ul>
            <li><a href="index.php">Home</a></li>

            <?php if ($_SESSION["level"] == 1) { ?>
            <li class="dropdown">
                <a href="#">Data Master &#9662;</a>
                <ul class="submenu">
                    <li><a href="index.php?p=barang">Data Barang</a></li>
                    <li><a href="index.php?p=supplier">Data Supplier</a></li>
                    <li><a href="index.php?p=pelanggan">Data Pelanggan</a></li>
                    <li><a href="index.php?p=user">Data User</a></li>
                </ul>
            </li>
            <?php } ?>

            <li><a href="index.php?p=transaksi">Transaksi</a></li>
            <li><a href="index.php?p=laporan">Laporan</a></li>
        </ul>
    </div>

    <ul class="kanan">
        <li class="dropdown">
            <a href="#"><?= e($_SESSION["nama"]) ?> &#9662;</a>
            <ul class="submenu">
                <li><a href="aksi.php?aksi=logout">Logout</a></li>
            </ul>
        </li>
    </ul>
</nav>

<div class="isi">
    <?= tampil_flash() ?>
    <?php include "page.php"; ?>
</div>

</body>
</html>
