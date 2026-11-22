<?php

require_once __DIR__ . "/utils.php";

// Session dimulai SEKALI untuk semua route
init_session();

try {
  require_once __DIR__ . "/conn.php";

  $master = require __DIR__ . "/config/master.php";
  $path   = $_SERVER['PATH_INFO'] ?? "/";

  // ---------- Data Master: /barang, /barang/add, /barang/edit, /api/barang/add|edit|delete ----------
  // (berlaku sama untuk barang, supplier, pelanggan, users)
  $entity = null;
  $action = null;
  $isApi  = false;
  if (preg_match('#^(/api)?/([a-z]+)(?:/(add|edit|delete))?$#', $path, $m) && isset($master[$m[2]])) {
    $isApi  = ($m[1] === '/api');
    $entity = $m[2];
    $action = $m[3] ?? 'list';
  }

  if ($entity !== null) {
    require_level([LEVEL_OWNER]);                 // Data Master HANYA untuk owner (level 1)
    $cfg  = $master[$entity];
    $file = null;
    if (!$isApi && $action === 'list') {
      $file = "/views/master/master.php";
    } elseif (!$isApi && in_array($action, ['add', 'edit'], true)) {
      $file = "/views/master/master.form.php";
    } elseif ($isApi && in_array($action, ['add', 'edit'], true)) {
      $file = "/services/master/master.save.php";
    } elseif ($isApi && $action === 'delete') {
      $file = "/services/master/master.delete.php";
    }
    require_once __DIR__ . ($file ?? "/views/404.php");

  } else {
    // KONTROL AKSES per route:
    //   require_guest()        -> hanya yang BELUM login
    //   require_login()        -> semua yang sudah login
    //   require_level([1, 2])  -> owner & kasir
    switch ($path) {
      // ---------- Auth ----------
      case '/login':
        require_guest();                          // sudah login -> tidak boleh ke halaman login
        require_once __DIR__ . "/views/login.php";
        break;
      case '/sig-in':
        require_guest();
        require_once __DIR__ . "/services/auth/login.php";
        break;
      case '/logout':
        require_once __DIR__ . "/services/auth/logout.php";
        break;

      // ---------- Home (semua level) ----------
      case '/':
        require_login();                          // belum login -> /login
        require_once __DIR__ . "/views/index.php";
        break;

      // ---------- Transaksi (level 1 & 2) ----------
      case '/transaksi':
        require_level([LEVEL_OWNER, LEVEL_KASIR]);
        require_once __DIR__ . "/views/transaksi/transaksi.php";
        break;
      case '/transaksi/add':
        require_level([LEVEL_OWNER, LEVEL_KASIR]);
        require_once __DIR__ . "/views/transaksi/transaksi.form.php";
        break;
      case '/api/transaksi/add':
        require_level([LEVEL_OWNER, LEVEL_KASIR]);
        require_once __DIR__ . "/services/transaksi/transaksi.add.php";
        break;

      // ---------- Laporan (level 1 & 2) ----------
      case '/reports':
        require_level([LEVEL_OWNER, LEVEL_KASIR]);
        require_once __DIR__ . "/views/reports/reports.php";
        break;
      case '/api/reports/export':
        require_level([LEVEL_OWNER, LEVEL_KASIR]);
        require_once __DIR__ . "/services/reports/reports.export.php";
        break;

      default:
        require_once __DIR__ . "/views/404.php";
        break;
    }
  }

  $conn = null;
} catch (PDOException $e) {
  echo $e->getMessage();
}
