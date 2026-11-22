<?php

try {
  require_once __DIR__ . "/route.php";
  require_once __DIR__ . "/utils.php";

  $path = $_SERVER['PATH_INFO'] ?? "/";

  // Cek level user
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }
  $level = $_SESSION['user_level'] ?? 0;

  // Ambil nama menu dari alamat
  $bagian = explode('/', trim($path, '/'));
  $menu = ($bagian[0] === 'api') ? ($bagian[1] ?? '') : $bagian[0];

  // Menu khusus admin
  $menuAdmin = ['pelanggan', 'supplier', 'barang', 'user'];

  // Kalau menu khusus admin tapi level bukan 1, tolak
  if (in_array($menu, $menuAdmin) && $level !== 1) {
    header("Location: " . appUrl("transaksi"));
    exit;
  }

  switch ($path) {
    case '/':
      require_once __DIR__ . "/views/index.php";
      break;
    case '/masuk':
      require_once __DIR__ . "/views/masuk.php";
      break;
    case '/daftar':
      require_once __DIR__ . "/views/daftar.php";
      break;
    case '/register':
      require_once __DIR__ . "/services/auth/daftar.php";
      break;
    case '/sign':
      require_once __DIR__ . "/services/auth/masuk.php";
      break;
    case '/logout':
      require_once __DIR__ . "/services/auth/logout.php";
      break;

    // Pelanggan
    case '/pelanggan':
      require_once __DIR__ . "/views/pelanggan/pelanggan.php";
      break;
    case '/pelanggan/add':
      require_once __DIR__ . "/views/pelanggan/pelanggan.form.php";
      break;
    case '/pelanggan/edit':
      require_once __DIR__ . "/views/pelanggan/pelanggan.edit.php";
      break;
    case '/api/pelanggan/add':
      require_once __DIR__ . "/services/pelanggan/pelanggan.add.php";
      break;
    case '/api/pelanggan/edit':
      require_once __DIR__ . "/services/pelanggan/pelanggan.edit.php";
      break;
    case '/api/pelanggan/delete':
      require_once __DIR__ . "/services/pelanggan/pelanggan.delete.php";
      break;

    // Supplier
    case '/supplier':
      require_once __DIR__ . "/views/supplier/supplier.php";
      break;
    case '/supplier/add':
      require_once __DIR__ . "/views/supplier/supplier.form.php";
      break;
    case '/supplier/edit':
      require_once __DIR__ . "/views/supplier/supplier.edit.php";
      break;
    case '/api/supplier/add':
      require_once __DIR__ . "/services/supplier/supplier.add.php";
      break;
    case '/api/supplier/edit':
      require_once __DIR__ . "/services/supplier/supplier.edit.php";
      break;
    case '/api/supplier/delete':
      require_once __DIR__ . "/services/supplier/supplier.delete.php";
      break;

    // Barang
    case '/barang':
      require_once __DIR__ . "/views/barang/barang.php";
      break;
    case '/barang/add':
      require_once __DIR__ . "/views/barang/barang.form.php";
      break;
    case '/barang/edit':
      require_once __DIR__ . "/views/barang/barang.edit.php";
      break;
    case '/api/barang/add':
      require_once __DIR__ . "/services/barang/barang.add.php";
      break;
    case '/api/barang/edit':
      require_once __DIR__ . "/services/barang/barang.edit.php";
      break;
    case '/api/barang/delete':
      require_once __DIR__ . "/services/barang/barang.delete.php";
      break;

    // User
    case '/user':
      require_once __DIR__ . "/views/user/user.php";
      break;
    case '/user/add':
      require_once __DIR__ . "/views/user/user.form.php";
      break;
    case '/user/edit':
      require_once __DIR__ . "/views/user/user.edit.php";
      break;
    case '/api/user/add':
      require_once __DIR__ . "/services/user/user.add.php";
      break;
    case '/api/user/edit':
      require_once __DIR__ . "/services/user/user.edit.php";
      break;
    case '/api/user/delete':
      require_once __DIR__ . "/services/user/user.delete.php";
      break;

    // Transaksi
    case '/transaksi':
      require_once __DIR__ . "/views/transaksi/transaksi.php";
      break;
    case '/transaksi/add':
      require_once __DIR__ . "/views/transaksi/transaksi.form.php";
      break;
    case '/transaksi/edit':
      require_once __DIR__ . "/views/transaksi/transaksi.edit.php";
      break;
    case '/transaksi/detail':
      require_once __DIR__ . "/views/transaksi/transaksi.detail.php";
      break;
    case '/api/transaksi/add':
      require_once __DIR__ . "/services/transaksi/transaksi.add.php";
      break;
    case '/api/transaksi/edit':
      require_once __DIR__ . "/services/transaksi/transaksi.edit.php";
      break;
    case '/api/transaksi/delete':
      require_once __DIR__ . "/services/transaksi/transaksi.delete.php";
      break;
    case '/api/transaksi/detail/add':
      require_once __DIR__ . "/services/transaksi/transaksi_detail.add.php";
      break;
    case '/api/transaksi/detail/delete':
      require_once __DIR__ . "/services/transaksi/transaksi_detail.delete.php";
      break;

    // Laporan
    case '/reports':
      require_once __DIR__ . "/views/reports/reports.php";
      break;
    case '/reports/export':
      require_once __DIR__ . "/services/reports/reports.export.php";
      break;

    default:
      require_once __DIR__ . "/views/404.php";
      break;
  }

  $DBH = null;
} catch (PDOException $e) {
  echo $e->getMessage();
}
