<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($judul ?? 'Penjualan') ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f4f6f8; color: #222; }
        nav { background: #1f4e79; padding: 12px 24px; }
        nav a { color: #fff; margin-right: 18px; text-decoration: none; font-weight: bold; }
        .wrap { max-width: 960px; margin: 24px auto; padding: 0 16px; }
        .card { background: #fff; padding: 18px 22px; border-radius: 6px; margin-bottom: 20px;
                box-shadow: 0 1px 3px rgba(0,0,0,.12); }
        h1, h2 { margin-top: 0; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccd; padding: 8px 10px; text-align: left; }
        th { background: #e8eef5; }
        td.angka, th.angka { text-align: right; }
        label { display: block; margin: 10px 0 4px; font-weight: bold; }
        input, select, textarea { padding: 7px; width: 100%; max-width: 360px; box-sizing: border-box; }
        button, .btn { background: #1f4e79; color: #fff; border: 0; padding: 8px 14px;
                       border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 14px; }
        .btn-merah { background: #b3261e; }
        .btn-abu { background: #6b7280; }
        .notif { padding: 12px 16px; border-radius: 4px; margin-bottom: 18px; }
        .sukses { background: #d9f2e0; border: 1px solid #2e7d32; }
        .gagal  { background: #fde2e0; border: 1px solid #b3261e; }
        .info td { border: 0; padding: 3px 10px 3px 0; }
    </style>
</head>
<body>
<nav>
    <a href="index.php">Transaksi</a>
    <a href="transaksi_tambah.php">+ Transaksi Baru</a>
    <a href="barang.php">Data Barang</a>
</nav>
<div class="wrap">
<?php if (!empty($_GET['pesan'])): ?>
    <div class="notif <?= (($_GET['tipe'] ?? '') === 'gagal') ? 'gagal' : 'sukses' ?>">
        <?= e($_GET['pesan']) ?>
    </div>
<?php endif; ?>
