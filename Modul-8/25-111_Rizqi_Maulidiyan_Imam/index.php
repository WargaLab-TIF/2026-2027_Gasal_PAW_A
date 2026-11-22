<?php
session_start();

require_once __DIR__ . '/koneksi.php';
require_once __DIR__ . '/utils.php';

$path = $_SERVER['PATH_INFO'] ?? '/';

switch ($path) {
    case '/':
        redirect(isset($_SESSION['login']) ? '/home' : '/login');
        break;

    case '/login':
        require_once __DIR__ . '/views/login.php';
        break;
    case '/sign-in':
        require_once __DIR__ . '/services/auth/sign-in.php';
        break;
    case '/logout':
        require_once __DIR__ . '/services/auth/logout.php';
        break;

    case '/home':
        require_once __DIR__ . '/views/home.php';
        break;

    case '/barang':
        require_once __DIR__ . '/views/barang/barang.php';
        break;
    case '/barang/tambah':
        require_once __DIR__ . '/views/barang/tambah.php';
        break;
    case '/barang/edit':
        require_once __DIR__ . '/views/barang/edit.php';
        break;
    case '/barang/hapus':
        require_once __DIR__ . '/views/barang/hapus.php';
        break;

    case '/transaksi':
        require_once __DIR__ . '/views/transaksi/transaksi.php';
        break;
    case '/transaksi/baru':
        require_once __DIR__ . '/views/transaksi/baru.php';
        break;
    case '/transaksi/detail':
        require_once __DIR__ . '/views/transaksi/detail.php';
        break;
    case '/transaksi/detail/tambah':
        require_once __DIR__ . '/views/transaksi/tambah_detail.php';
        break;

        case '/supplier':
        require_once __DIR__ . '/views/supplier/supplier.php';
        break;
    case '/supplier/tambah':
        require_once __DIR__ . '/views/supplier/tambah.php';
        break;
    case '/supplier/edit':
        require_once __DIR__ . '/views/supplier/edit.php';
        break;
    case '/supplier/hapus':
        require_once __DIR__ . '/views/supplier/hapus.php';
        break;

    case '/pelanggan':
        require_once __DIR__ . '/views/pelanggan/pelanggan.php';
        break;
    case '/pelanggan/tambah':
        require_once __DIR__ . '/views/pelanggan/tambah.php';
        break;
    case '/pelanggan/edit':
        require_once __DIR__ . '/views/pelanggan/edit.php';
        break;
    case '/pelanggan/hapus':
        require_once __DIR__ . '/views/pelanggan/hapus.php';
        break;

    case '/user':
        require_once __DIR__ . '/views/user/user.php';
        break;
    case '/user/tambah':
        require_once __DIR__ . '/views/user/tambah.php';
        break;
    case '/user/edit':
        require_once __DIR__ . '/views/user/edit.php';
        break;
    case '/user/hapus':
        require_once __DIR__ . '/views/user/hapus.php';
        break;

    case '/laporan':
        require_once __DIR__ . '/views/laporan.php';
        break;
    case '/laporan/export':
        require_once __DIR__ . '/services/laporan/export.php';
        break;

    default:
        http_response_code(404);
        echo '404 - Halaman tidak ditemukan';
        break;
}