<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$loggedIn = isset($_SESSION["user_id"], $_SESSION["username"], $_SESSION["level"]);
$level    = $loggedIn ? (int) $_SESSION["level"] : 0;
$namaUser = $loggedIn ? ($_SESSION["nama"] ?: $_SESSION["username"]) : "";
?>
<style>
    .topbar { background: #0b4f8a; color: #fff; display: flex; align-items: center; justify-content: space-between; padding: 10px 20px; margin-bottom: 20px; font-family: Arial, sans-serif; font-size: 14px; }
    .topbar-left { display: flex; align-items: center; gap: 18px; }
    .topbar a, .dd-btn { color: #fff; text-decoration: none; background: none; border: none; font: inherit; cursor: pointer; padding: 0; }
    .topbar a:hover { text-decoration: underline; }
    .brand { font-weight: bold; font-size: 16px; }
    .dd { position: relative; display: inline-block; }
    .dd-menu { display: none; position: absolute; top: 100%; left: 0; margin-top: 6px; background: #fff; border: 1px solid #ccc; min-width: 160px; box-shadow: 0 2px 6px rgba(0,0,0,.15); z-index: 50; }
    .dd-menu.show { display: block; }
    .dd-menu a { display: block; padding: 8px 12px; color: #222; text-decoration: none; }
    .dd-menu a:hover { background: #f0f4f8; }
    .dd-right .dd-menu { left: auto; right: 0; }
</style>

<nav class="topbar">
    <?php if (!$loggedIn): ?>
        <div class="topbar-left">
            <span class="brand">Sistem Penjualan</span>
            <span>Silakan <a href="login.php">Login</a> untuk mengakses menu.</span>
        </div>
    <?php else: ?>
        <div class="topbar-left">
            <a href="dashboard.php" class="brand">Sistem Penjualan</a>
            <a href="dashboard.php">Home</a>

            <?php if ($level === 1): ?>
                <div class="dd">
                    <button type="button" class="dd-btn" data-dd="ddMaster">Data Master &#9662;</button>
                    <div class="dd-menu" id="ddMaster">
                        <a href="barang.php">Data Barang</a>
                        <a href="supplier.php">Data Supplier</a>
                        <a href="pelanggan.php">Data Pelanggan</a>
                        <a href="user.php">Data User</a>
                    </div>
                </div>
            <?php endif; ?>

            <a href="transaksi.php">Transaksi</a>
            <a href="laporan.php">Laporan</a>
        </div>

        <div class="dd dd-right">
            <button type="button" class="dd-btn" data-dd="ddUser"><?= htmlspecialchars($namaUser) ?> &#9662;</button>
            <div class="dd-menu" id="ddUser">
                <a href="logout.php">Logout</a>
            </div>
        </div>
    <?php endif; ?>
</nav>

<script>
document.addEventListener("click", function (e) {
    const btn = e.target.closest(".dd-btn");
    const targetMenu = btn ? document.getElementById(btn.dataset.dd) : null;

    document.querySelectorAll(".dd-menu").forEach(function (menu) {
        menu.classList.toggle("show", menu === targetMenu && !menu.classList.contains("show"));
    });
});
</script>