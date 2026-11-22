<nav class="navbar">

    <a class="brand" href="/Modul-8/index.php">
        Sistem Penjualan
    </a>

    <a href="/Modul-8/index.php">
        Home
    </a>

    <?php if ((int)$_SESSION['level'] === 1): ?>

        <div class="dropdown">

            <span>Data Master ▾</span>

            <div class="dropdown-content">

                <a href="/Modul-8/data_master/barang.php">
                    Data Barang
                </a>

                <a href="/Modul-8/data_master/supplier.php">
                    Data Supplier
                </a>

                <a href="/Modul-8/data_master/pelanggan.php">
                    Data Pelanggan
                </a>

                <a href="/Modul-8/data_master/user.php">
                    Data User
                </a>

            </div>

        </div>

    <?php endif; ?>

    <a href="/Modul-8/transaksi/index.php">
        Transaksi
    </a>

    <a href="/Modul-8/laporan/index.php">
        Laporan
    </a>

    <span class="nav-user">

        <?= e($_SESSION['nama']) ?>

        (<?= (int)$_SESSION['level'] === 1 ? 'Owner' : 'Kasir' ?>)

    </span>

    <a href="/Modul-8/logout.php">
        Logout
    </a>

</nav>
