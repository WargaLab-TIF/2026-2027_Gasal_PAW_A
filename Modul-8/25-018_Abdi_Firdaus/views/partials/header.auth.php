<?php
// header dan navbar untuk user yang sudah login

requireLogin();
$pageTitle = $pageTitle ?? 'Sistem Penjualan';
$flash = getFlash();
$isOwnerNav = isOwner();
$userNama = currentUserName();
$userRole = levelLabel(currentUserLevel());
?>
<!doctype html>
<html lang="id" data-bs-theme="light">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= sanitazion($pageTitle) ?> - Sistem Penjualan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@6.0.0-alpha.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root {
      --nav-bg: #1e4b85;
      --nav-bg-hover: rgba(255, 255, 255, 0.14);
      --nav-text: #eaf2ff;
      --nav-text-active: #ffffff;
    }

    body {
      background-color: #f8fafc;
      min-height: 100vh;
    }

    /* navbar utama */
    .app-navbar {
      background: linear-gradient(90deg, #1a3f73 0%, #245799 100%);
      color: var(--nav-text);
      box-shadow: 0 2px 8px rgba(15, 23, 42, 0.14);
      position: sticky;
      top: 0;
      z-index: 1030;
    }

    .app-navbar-inner {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
      min-height: 58px;
      flex-wrap: wrap;
      padding: 0.4rem 0;
    }

    .app-navbar-left {
      display: flex;
      align-items: center;
      gap: 0.35rem;
      flex-wrap: wrap;
    }

    .app-brand {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      color: #ffffff;
      font-weight: 700;
      font-size: 1.05rem;
      text-decoration: none;
      margin-right: 0.85rem;
      padding: 0.35rem 0.25rem;
    }

    .app-brand:hover {
      color: #ffffff;
    }

    .app-nav-link,
    .app-dropdown-btn {
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
      color: var(--nav-text);
      background: transparent;
      border: none;
      border-radius: 6px;
      padding: 0.45rem 0.8rem;
      font-size: 0.94rem;
      font-weight: 500;
      text-decoration: none;
      cursor: pointer;
      transition: background-color 0.15s ease, color 0.15s ease;
    }

    .app-nav-link:hover,
    .app-dropdown-btn:hover,
    .app-nav-link.active,
    .app-dropdown-btn.active {
      background-color: var(--nav-bg-hover);
      color: var(--nav-text-active);
    }

    .app-nav-link.active,
    .app-dropdown-btn.active {
      font-weight: 600;
    }

    .app-dropdown {
      position: relative;
    }

    .app-dropdown-menu {
      display: none;
      position: absolute;
      top: calc(100% + 6px);
      left: 0;
      min-width: 200px;
      background: #ffffff;
      border: 1px solid #dbe4f0;
      border-radius: 8px;
      box-shadow: 0 10px 25px rgba(15, 23, 42, 0.16);
      padding: 0.4rem;
      z-index: 1050;
    }

    .app-dropdown-menu.align-right {
      left: auto;
      right: 0;
    }

    .app-dropdown-menu.open {
      display: block;
    }

    .app-dropdown-item {
      display: block;
      width: 100%;
      padding: 0.5rem 0.75rem;
      color: #1e293b;
      text-decoration: none;
      border-radius: 6px;
      font-size: 0.92rem;
    }

    .app-dropdown-item:hover,
    .app-dropdown-item.active {
      background-color: #eff6ff;
      color: #1d4ed8;
      font-weight: 600;
    }

    .app-dropdown-item.text-logout {
      color: #b91c1c;
    }

    .app-dropdown-item.text-logout:hover {
      background-color: #fef2f2;
      color: #991b1b;
    }

    .app-dropdown-header {
      padding: 0.45rem 0.75rem;
      font-size: 0.8rem;
      color: #64748b;
      border-bottom: 1px solid #e2e8f0;
      margin-bottom: 0.3rem;
    }

    .app-caret {
      font-size: 0.72rem;
      opacity: 0.9;
    }

    .role-pill {
      display: inline-block;
      font-size: 0.72rem;
      font-weight: 600;
      padding: 0.15rem 0.5rem;
      border-radius: 999px;
      background: rgba(255, 255, 255, 0.18);
      color: #ffffff;
    }

    .card-panel {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 10px;
      box-shadow: 0 1px 3px rgba(15, 23, 42, 0.06);
    }

    tr.baris-aktif {
      background-color: #e0f2fe !important;
      font-weight: 600;
    }

    /* sembunyikan navigasi dan alert saat cetak */
    @media print {
      body {
        background: #ffffff !important;
      }
      .no-print,
      .d-print-none,
      .app-navbar,
      .flash-alert {
        display: none !important;
      }
    }
  </style>
</head>

<body>
  <!-- navbar -->
  <nav class="app-navbar d-print-none no-print" aria-label="Navigasi utama">
    <div class="container app-navbar-inner">
      <div class="app-navbar-left">
        <a class="app-brand" href="<?= routeUrl("/") ?>">
          <span aria-hidden="true">&#9635;</span>
          <span>Sistem Penjualan</span>
        </a>

        <a class="app-nav-link <?= isNavActive(['/']) ? 'active' : '' ?>" href="<?= routeUrl("/") ?>">Home</a>

        <?php if ($isOwnerNav): ?>
          <!-- menu data master khusus owner -->
          <div class="app-dropdown">
            <button type="button"
              class="app-dropdown-btn <?= isNavActive(['/barang', '/supplier', '/pelanggan', '/user']) ? 'active' : '' ?>"
              data-dropdown-toggle="menuDataMaster" aria-expanded="false" aria-haspopup="true">
              <span>Data Master</span>
              <span class="app-caret" aria-hidden="true">&#9662;</span>
            </button>
            <div class="app-dropdown-menu" id="menuDataMaster" role="menu">
              <a class="app-dropdown-item <?= isNavActive(['/barang']) ? 'active' : '' ?>" role="menuitem"
                href="<?= routeUrl("/barang") ?>">Data Barang</a>
              <a class="app-dropdown-item <?= isNavActive(['/supplier']) ? 'active' : '' ?>" role="menuitem"
                href="<?= routeUrl("/supplier") ?>">Data Supplier</a>
              <a class="app-dropdown-item <?= isNavActive(['/pelanggan']) ? 'active' : '' ?>" role="menuitem"
                href="<?= routeUrl("/pelanggan") ?>">Data Pelanggan</a>
              <a class="app-dropdown-item <?= isNavActive(['/user']) ? 'active' : '' ?>" role="menuitem"
                href="<?= routeUrl("/user") ?>">Data User</a>
            </div>
          </div>
        <?php endif ?>

        <a class="app-nav-link <?= isNavActive(['/transaksi']) ? 'active' : '' ?>"
          href="<?= routeUrl("/transaksi") ?>">Transaksi</a>

        <a class="app-nav-link <?= isNavActive(['/reports', '/laporan']) ? 'active' : '' ?>"
          href="<?= routeUrl("/reports") ?>">Laporan</a>
      </div>

      <!-- menu user dan logout -->
      <div class="app-dropdown">
        <button type="button" class="app-dropdown-btn" data-dropdown-toggle="menuUser" aria-expanded="false"
          aria-haspopup="true">
          <span><?= sanitazion($userNama) ?></span>
          <span class="role-pill"><?= sanitazion($userRole) ?></span>
          <span class="app-caret" aria-hidden="true">&#9662;</span>
        </button>
        <div class="app-dropdown-menu align-right" id="menuUser" role="menu">
          <div class="app-dropdown-header">
            Login sebagai <strong><?= sanitazion($_SESSION['username'] ?? '') ?></strong><br>
            Level <?= sanitazion(currentUserLevel()) ?> (<?= sanitazion($userRole) ?>)
          </div>
          <a class="app-dropdown-item text-logout" role="menuitem" href="<?= routeUrl("/logout") ?>">Logout</a>
        </div>
      </div>
    </div>
  </nav>

  <?php if ($flash): ?>
    <div class="container mt-5 flash-alert d-print-none no-print">
      <div class="alert theme-<?= sanitazion($flash['type']) ?> mb-0" role="alert">
        <?= sanitazion($flash['message']) ?>
      </div>
    </div>
  <?php endif ?>
