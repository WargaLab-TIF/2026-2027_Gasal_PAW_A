
<?php
require_once __DIR__ . '/../auth.php';

cekLogin();

$level = (int) $_SESSION['level'];
$nama_user = $_SESSION['user_login'];

$current_page = basename($_SERVER['PHP_SELF']);
?>

<nav class="bg-white border-b border-slate-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex justify-between items-center h-16">


            <!-- Menu Navigasi -->
            <div class="hidden md:flex items-center gap-6">
                <a href="index.php"
                   class="text-xl font-bold text-slate-800">
                    Sistem penjualan
                </a>
                <!-- Home -->
                <a href="index.php"
                   class="<?= $current_page === 'index.php'?>">
                    Home
                </a>

                <!-- Menu khusus Level 1
                <?php if ($level === 1): ?>

                    <div class="relative group">
                        <button type="button"
                            class="text-slate-600 hover:text-black">
                            Data Master
                        </button>

                        <div class="absolute left-0 top-full hidden group-hover:block
                                    bg-white border border-slate-200 shadow-lg
                                    rounded-md w-48 py-2 z-50">

                            <a href="barang.php"
                               class="block px-4 py-2 hover:bg-slate-100">
                                Data Barang
                            </a>

                            <a href="supplier.php"
                               class="block px-4 py-2 hover:bg-slate-100">
                                Data Supplier
                            </a>

                            <a href="pelanggan.php"
                               class="block px-4 py-2 hover:bg-slate-100">
                                Data Pelanggan
                            </a>

                            <a href="user.php"
                               class="block px-4 py-2 hover:bg-slate-100">
                                Data User
                            </a>

                        </div>
                    </div>

                <?php endif; ?>

                <!-- Transaksi -->
                <a href="transaksi.php"
                   class="<?= $current_page === 'transaksi.php'
                       ? 'text-black font-semibold'
                       : 'text-slate-600 hover:text-black' ?>">
                    Transaksi
                </a>

                <!-- Laporan -->
                <a href="laporan.php"
                   class="<?= $current_page === 'laporan.php'
                       ? 'text-black font-semibold'
                       : 'text-slate-600 hover:text-black' ?>">
                    Laporan
                </a>

            </div>

            <!-- Informasi Pengguna -->
            <div class="flex items-center gap-4">

                <div class="text-right hidden sm:block">
                    <p class="text-sm font-semibold text-slate-800">
                        <?= htmlspecialchars($nama_user) ?>
                    </p>

                    <p class="text-xs text-slate-500">
                        <?= $level === 1 ? 'Owner' : 'Kasir' ?>
                    </p>
                </div>

                <!-- Logout -->
                <a href="logout.php"
                   onclick="return confirm('Apakah Anda yakin ingin logout?')"
                   class="bg-black hover:bg-black text-white
                          px-4 py-2 rounded-md text-sm font-medium">
                    Logout
                </a>

            </div>

        </div>
    </div>
</nav>
