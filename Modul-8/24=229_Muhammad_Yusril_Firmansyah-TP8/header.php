<?php
require_once __DIR__ . "/auth.php";
wajibLogin();
$level = (int)$_SESSION['user']['level'];
$nama = htmlspecialchars($_SESSION['user']['nama']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Penjualan</title>
    <style>
        *{box-sizing:border-box} body{margin:0;font-family:Arial,sans-serif;background:#f3f6fb;color:#243047}
        .navbar{background:#123f70;color:white;padding:14px 5%;display:flex;align-items:center;gap:22px;flex-wrap:wrap}
        .brand{font-weight:bold;margin-right:auto}.navbar a{color:white;text-decoration:none;font-size:14px}
        .navbar a:hover{text-decoration:underline}.user{font-size:13px;background:#ffffff20;padding:8px 12px;border-radius:6px}
        .container{max-width:1100px;margin:28px auto;padding:0 18px}
        .card{background:white;border-radius:10px;padding:22px;margin-bottom:18px;box-shadow:0 2px 10px #102b4d12}
        h1{font-size:25px;margin-top:0} h2{font-size:19px}
        table{width:100%;border-collapse:collapse;background:white} th,td{padding:11px;border-bottom:1px solid #e5eaf1;text-align:left;font-size:14px}
        th{background:#edf3fa} input,select,textarea{width:100%;padding:10px;border:1px solid #ccd5e1;border-radius:6px;margin:6px 0 14px}
        label{font-size:14px;font-weight:bold} .btn{display:inline-block;border:0;border-radius:6px;padding:9px 14px;background:#1769aa;color:white;text-decoration:none;cursor:pointer}
        .btn-danger{background:#c0392b}.btn-secondary{background:#64748b}.btn-small{padding:6px 9px;font-size:12px}
        .grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(210px,1fr));gap:15px}
        .muted{color:#64748b;font-size:13px}.alert{padding:11px;border-radius:6px;background:#fff4d6;margin-bottom:15px}
        @media(max-width:650px){.navbar{gap:12px}.brand{width:100%}table{display:block;overflow-x:auto}}
    </style>
</head>
<body>
<nav class="navbar">
    <span class="brand">Sistem Penjualan</span>
    <a href="index.php">Home</a>
    <?php if ($level === 1): ?>
        <a href="master.php?jenis=barang">Data Master</a>
    <?php endif; ?>
    <a href="transaksi.php">Transaksi</a>
    <a href="laporan.php">Laporan</a>
    <span class="user"><?= $nama ?> (<?= $level === 1 ? 'Owner' : 'Kasir' ?>)</span>
    <a href="logout.php">Logout</a>
</nav>
<div class="container">
