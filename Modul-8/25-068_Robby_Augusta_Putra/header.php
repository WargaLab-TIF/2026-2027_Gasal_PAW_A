<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $judul_halaman ?? 'Sistem Penjualan' ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
        .navbar { background: #1e3a8a; margin: -20px -20px 20px; padding: 0 20px;
                  display: flex; justify-content: space-between; align-items: center; }
        .navbar a, .navbar span { color: #fff; text-decoration: none; padding: 14px; display: block; font-size: 14px; }
        .navbar a:hover { background: #2563eb; }
        .nav-grup { display: flex; align-items: center; }
        .dropdown { position: relative; }
        .dd-menu { display: none; position: absolute; top: 100%; left: 0; min-width: 170px; background: #1e3a8a; z-index: 10; }
        .dropdown:hover .dd-menu { display: block; }
        .flash { padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .flash.sukses { background: #dcfce7; color: #166534; }
        .flash.gagal, .error { background: #fee2e2; color: #991b1b; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        @media print { .navbar { display: none !important; } }
    </style>
    <?= $extra_head ?? '' ?>
</head>
<body>

<nav class="navbar">
    <div class="nav-grup">
        <a href="index.php">Home</a>
        <?php if ($_SESSION['level'] == 1) { ?>
            <div class="dropdown">
                <a href="#">Data Master &#9662;</a>
                <div class="dd-menu">
                    <a href="barang.php">Data Barang</a>
                    <a href="supplier.php">Data Supplier</a>
                    <a href="pelanggan.php">Data Pelanggan</a>
                    <a href="user.php">Data User</a>
                </div>
            </div>
        <?php } ?>
        <a href="transaksi.php">Transaksi</a>
        <a href="laporan.php">Laporan</a>
    </div>
    <div class="nav-grup">
        <span><?= htmlspecialchars($_SESSION['user_login']) ?></span>
        <a href="logout.php">Logout</a>
    </div>
</nav>

<?php if (isset($_SESSION['flash'])) { ?>
    <div class="flash <?= $_SESSION['flash'][0] ?>"><?= $_SESSION['flash'][1] ?></div>
    <?php unset($_SESSION['flash']); ?>
<?php } ?>