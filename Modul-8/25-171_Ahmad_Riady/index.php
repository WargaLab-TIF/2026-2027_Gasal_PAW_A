
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$sudah_login = isset($_SESSION['id_user']);
$nama = $sudah_login ? $_SESSION['nama'] : '';
$level = $sudah_login ? $_SESSION['level'] : 0;

$link_barang = ($sudah_login && $level == 1) ? 'barang/index.php' : 'login.php';
$link_supplier = ($sudah_login && $level == 1) ? 'supplier/index.php' : 'login.php';
$link_pelanggan = ($sudah_login && $level == 1) ? 'pelanggan/index.php' : 'login.php';
$link_user = ($sudah_login && $level == 1) ? 'user/index.php' : 'login.php';
$link_transaksi = $sudah_login ? 'transaksi/index.php' : 'login.php';
$link_laporan = $sudah_login ? 'laporan/index.php' : 'login.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Sistem Penjualan</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, sans-serif; background: #f8f9fa; color: #1e293b; }
        header { background: #0f172a; color: white; padding: 25px 8%; display: flex; justify-content: space-between; align-items: center; }
        header h2 { margin: 0; font-size: 22px; }
        header a { color: white; text-decoration: none; font-size: 13px; font-weight: bold; border: 1px solid white; padding: 9px 18px; }
        header a:hover { background: white; color: #0f172a; }
        nav { background: white; padding: 18px 8%; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; }
        nav a { color: #0369a1; text-decoration: none; font-size: 20px; font-weight: bold; }
        .dropdown-btn { color: #0369a1; font-size: 20px; font-weight: bold; cursor: pointer; }
        .dropdown-content a { display: block; padding: 20px; font-size: 15px; }
        nav a:hover { color: #0f172a; }
        .nav-kiri, .nav-kanan { display: flex; align-items: center; gap: 30px; }
        .nav-kanan { margin-left: auto; }
        .dropdown { position: relative; display: inline-block; }
        .dropdown-btn { color: #0369a1; font-size: 20px; font-weight: bold; cursor: pointer; }
        .dropdown-content { display: none; position: absolute; top: 100%; left: 0; background: white; min-width: 150px; border: 1px solid #e2e8f0; z-index: 10; padding-top: 10px; }
        .dropdown-content a { display: block; padding: 12px; }
        .dropdown-content a:hover { background: #f1f5f9; }
        .dropdown:hover .dropdown-content { display: block; }
        .container { max-width: 1200px; margin: auto; padding: 35px 20px; }
        .banner h1 { font-size: 30px; margin: 10px 0; }
        .content { background: white; border: 1px solid #e2e8f0; margin-top: 25px; padding: 25px; }
        .content h3 { font-size: 20px; margin-top: 0; }
        .content p { font-size: 14px; color: #475569; }
        footer { text-align: center; color: #94a3b8; padding: 25px; font-size: 12px; }
        @media (max-width: 600px) {
            header { padding: 20px; }
            nav { padding: 15px 20px; flex-wrap: wrap; gap: 15px; }
            .nav-kiri, .nav-kanan { flex-wrap: wrap; }
            .nav-kanan { margin-left: 0; }
            .banner h1 { font-size: 23px; }
            .content { padding: 15px; }
        }
    </style>
</head>
<body>
    <header>
        <h2>SISTEM PENJUALAN</h2>
        <?php if ($sudah_login): ?>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </header>
    <nav>
        <div class="nav-kiri">
            <a href="index.php">Home</a>
            <?php if ($level == 1 || !$sudah_login): ?>
                <div class="dropdown">
                    <span class="dropdown-btn">Data Master</span>
                    <div class="dropdown-content">
                        <a href="<?= $link_barang ?>">Barang</a>
                        <a href="<?= $link_supplier ?>">Supplier</a>
                        <a href="<?= $link_pelanggan ?>">Pelanggan</a>
                        <a href="<?= $link_user ?>">User</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="nav-kanan">
            <a href="<?= $link_transaksi ?>">Transaksi</a>
            <a href="<?= $link_laporan ?>">Laporan</a>
        </div>
    </nav>
    <main class="container">
        <section class="banner">
            <h1>Dashboard</h1>
        </section>
        <section class="content">
            <?php if ($sudah_login): ?>
                <h3>Selamat Datang, <?= htmlspecialchars($nama) ?>!</h3>
                <p>Anda login sebagai <?= ($level == 1) ? 'Owner' : 'Kasir' ?>.</p>
            <?php else: ?>
                <h3>Selamat Datang!</h3>
                <p>Selamat datang di aplikasi Sistem Penjualan.</p>
            <?php endif; ?>
        </section>
    </main>
    <footer>Rian vs Everybody</footer>
</body>
</html>