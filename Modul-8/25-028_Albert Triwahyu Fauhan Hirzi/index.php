<?php
require_once __DIR__ . '/auth.php';

$routes = [
    'home' => __DIR__ . '/views/home.php',
    'login' => __DIR__ . '/views/login.php',
    'login-submit' => __DIR__ . '/services/auth/login.php',
    'master' => __DIR__ . '/views/master/master.php',
    'transaksi' => __DIR__ . '/views/transactions/transaksi.php',
    'transaksi-tambah' => __DIR__ . '/views/transactions/transaksi.tambah.php',
    'transaksi-detail' => __DIR__ . '/views/transactions/transaksi.detail.php',
    'transaksi-edit' => __DIR__ . '/views/transactions/transaksi.edit.php',
    'transaksi-hapus' => __DIR__ . '/services/transactions/delete.php',
    'reports' => __DIR__ . '/views/reports/reports.php',
    'logout' => __DIR__ . '/services/auth/logout.php',
    'reports-export' => __DIR__ . '/views/reports/reports.export.php',
];

$page = $_GET['page'] ?? 'home';
if (!isset($routes[$page])) {
    http_response_code(404);
    exit('Halaman tidak ditemukan.');
}

require $routes[$page];
