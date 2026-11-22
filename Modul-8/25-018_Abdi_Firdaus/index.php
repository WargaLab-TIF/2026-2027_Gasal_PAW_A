<?php
// index.php - router utama

try {
  require_once __DIR__ . "/conn.php";
  require_once __DIR__ . "/utils.php";

  ensureSession();
  $path = currentPath();

  switch ($path) {
    // autentikasi
    case '/login':
      requireGuest();
      require_once __DIR__ . "/views/login.php";
      break;

    case '/sign-in':
      requireGuest();
      require_once __DIR__ . "/services/auth/login.php";
      break;

    case '/logout':
      require_once __DIR__ . "/services/auth/logout.php";
      break;

    // halaman umum (owner dan kasir)
    case '/':
    case '/home':
      requireLogin();
      require_once __DIR__ . "/views/home.php";
      break;

    // transaksi
    case '/transaksi':
      requireLogin();
      require_once __DIR__ . "/views/transaksi/transaksi.php";
      break;
    case '/transaksi/add':
      requireLogin();
      require_once __DIR__ . "/views/transaksi/transaksi.form.php";
      break;
    case '/transaksi/detail/add':
      requireLogin();
      require_once __DIR__ . "/views/transaksi/detail.form.php";
      break;
    case '/api/transaksi/add':
      requireLogin();
      require_once __DIR__ . "/services/transaksi/transaksi.add.php";
      break;
    case '/api/transaksi/delete':
      requireLogin();
      require_once __DIR__ . "/services/transaksi/transaksi.delete.php";
      break;
    case '/api/transaksi/detail/add':
      requireLogin();
      require_once __DIR__ . "/services/transaksi/detail.add.php";
      break;
    case '/api/transaksi/detail/delete':
      requireLogin();
      require_once __DIR__ . "/services/transaksi/detail.delete.php";
      break;

    // laporan
    case '/reports':
    case '/laporan':
      requireLogin();
      require_once __DIR__ . "/views/reports/reports.php";
      break;
    case '/api/reports/export':
      requireLogin();
      require_once __DIR__ . "/services/reports/reports.export.php";
      break;

    // data master (khusus owner)

    // barang
    case '/barang':
      requireOwner();
      require_once __DIR__ . "/views/barang/barang.php";
      break;
    case '/barang/add':
      requireOwner();
      require_once __DIR__ . "/views/barang/barang.form.php";
      break;
    case '/barang/edit':
      requireOwner();
      require_once __DIR__ . "/views/barang/barang.edit.php";
      break;
    case '/api/barang/add':
      requireOwner();
      require_once __DIR__ . "/services/barang/barang.add.php";
      break;
    case '/api/barang/edit':
      requireOwner();
      require_once __DIR__ . "/services/barang/barang.edit.php";
      break;
    case '/api/barang/delete':
      requireOwner();
      require_once __DIR__ . "/services/barang/barang.delete.php";
      break;

    // supplier
    case '/supplier':
      requireOwner();
      require_once __DIR__ . "/views/supplier/supplier.php";
      break;
    case '/supplier/add':
      requireOwner();
      require_once __DIR__ . "/views/supplier/supplier.form.php";
      break;
    case '/supplier/edit':
      requireOwner();
      require_once __DIR__ . "/views/supplier/supplier.edit.php";
      break;
    case '/api/supplier/add':
      requireOwner();
      require_once __DIR__ . "/services/supplier/supplier.add.php";
      break;
    case '/api/supplier/edit':
      requireOwner();
      require_once __DIR__ . "/services/supplier/supplier.edit.php";
      break;
    case '/api/supplier/delete':
      requireOwner();
      require_once __DIR__ . "/services/supplier/supplier.delete.php";
      break;

    // pelanggan
    case '/pelanggan':
      requireOwner();
      require_once __DIR__ . "/views/pelanggan/pelanggan.php";
      break;
    case '/pelanggan/add':
      requireOwner();
      require_once __DIR__ . "/views/pelanggan/pelanggan.form.php";
      break;
    case '/pelanggan/edit':
      requireOwner();
      require_once __DIR__ . "/views/pelanggan/pelanggan.edit.php";
      break;
    case '/api/pelanggan/add':
      requireOwner();
      require_once __DIR__ . "/services/pelanggan/pelanggan.add.php";
      break;
    case '/api/pelanggan/edit':
      requireOwner();
      require_once __DIR__ . "/services/pelanggan/pelanggan.edit.php";
      break;
    case '/api/pelanggan/delete':
      requireOwner();
      require_once __DIR__ . "/services/pelanggan/pelanggan.delete.php";
      break;

    // user
    case '/user':
      requireOwner();
      require_once __DIR__ . "/views/user/user.php";
      break;
    case '/user/add':
      requireOwner();
      require_once __DIR__ . "/views/user/user.form.php";
      break;
    case '/user/edit':
      requireOwner();
      require_once __DIR__ . "/views/user/user.edit.php";
      break;
    case '/api/user/add':
      requireOwner();
      require_once __DIR__ . "/services/user/user.add.php";
      break;
    case '/api/user/edit':
      requireOwner();
      require_once __DIR__ . "/services/user/user.edit.php";
      break;
    case '/api/user/delete':
      requireOwner();
      require_once __DIR__ . "/services/user/user.delete.php";
      break;

    // 404
    default:
      http_response_code(404);
      require_once __DIR__ . "/views/404.php";
      break;
  }

  $conn = null;
} catch (PDOException $e) {
  http_response_code(500);
  echo "Terjadi kesalahan database: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
}
