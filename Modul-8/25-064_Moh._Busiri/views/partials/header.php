<?php
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
  session_start();
}

$userLevel = (int)($_SESSION['level'] ?? 0);
$userName = $_SESSION['nama'] ?? 'Pengguna';
$currentPage = $_GET['page'] ?? 'home';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $page_title ?? 'Sistem Penjualan' ?></title>
  <!-- Chart.js (untuk modul laporan) -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      color: #333;
      background-color: #ffffff;
    }

    h1, h2, h3, h4 {
      color: #2c3e50;
      margin-top: 10px;
      margin-bottom: 12px;
    }

    /* Navbar Menu Utama Modul 8 */
    .navbar {
      background-color: #1a4784;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0 20px;
      min-height: 48px;
    }

    .navbar-brand {
      color: #ffffff;
      font-size: 16px;
      font-weight: bold;
      text-decoration: none;
      padding-right: 20px;
    }

    .nav-menu {
      display: flex;
      align-items: center;
      list-style: none;
      margin: 0;
      padding: 0;
    }

    .nav-item {
      position: relative;
    }

    .nav-link {
      display: block;
      color: #ffffff;
      text-decoration: none;
      padding: 14px 16px;
      font-size: 14px;
      font-weight: bold;
    }

    .nav-link:hover,
    .nav-link.active {
      background-color: #12335e;
      color: #ffffff;
    }

    /* Dropdown CSS Murni untuk Data Master */
    .dropdown {
      position: relative;
    }

    .dropdown-content {
      display: none;
      position: absolute;
      background-color: #ffffff;
      min-width: 180px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
      border: 1px solid #dee2e6;
      border-radius: 4px;
      z-index: 1000;
      top: 100%;
      left: 0;
    }

    .dropdown-content a {
      color: #333;
      padding: 10px 16px;
      text-decoration: none;
      display: block;
      font-size: 13px;
      font-weight: normal;
      border-bottom: 1px solid #f0f0f0;
    }

    .dropdown-content a:last-child {
      border-bottom: none;
    }

    .dropdown-content a:hover,
    .dropdown-content a.active {
      background-color: #e9ecef;
      color: #1a4784;
      font-weight: bold;
    }

    .dropdown:hover .dropdown-content {
      display: block;
    }

    /* Area Pengguna Kanan */
    .nav-user {
      display: flex;
      align-items: center;
      color: #ffffff;
      font-size: 13px;
      gap: 12px;
    }

    .btn-logout {
      background-color: #dc3545;
      color: #ffffff;
      padding: 5px 12px;
      font-size: 12px;
      font-weight: bold;
      border-radius: 4px;
      text-decoration: none;
      border: 1px solid transparent;
    }

    .btn-logout:hover {
      background-color: #bb2d3b;
    }

    /* Container Halaman (gaya Modul 7) */
    .container {
      max-width: 1100px;
      margin: 20px auto;
      padding: 0 15px;
    }

    /* Form Container (gaya Modul 7) */
    .form-container {
      background: #f8f9fa;
      padding: 16px 20px;
      border: 1px solid #dee2e6;
      border-radius: 6px;
      margin-bottom: 24px;
    }

    .form-group {
      margin-bottom: 14px;
    }

    .form-group label {
      display: block;
      font-weight: bold;
      margin-bottom: 6px;
      font-size: 14px;
    }

    .form-control {
      width: 100%;
      padding: 8px 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
      font-size: 14px;
      box-sizing: border-box;
    }

    .form-control:focus {
      border-color: #1a4784;
      outline: none;
    }

    /* Tombol-tombol standar Modul 7 */
    .btn {
      display: inline-block;
      padding: 7px 14px;
      font-size: 13px;
      font-weight: bold;
      border-radius: 4px;
      cursor: pointer;
      text-decoration: none;
      border: 1px solid transparent;
      margin-right: 6px;
    }

    .btn-submit,
    .btn-primary {
      background-color: #0d6efd;
      color: #ffffff;
    }

    .btn-submit:hover,
    .btn-primary:hover {
      background-color: #0b5ed7;
    }

    .btn-print {
      background-color: #e67e22;
      color: #ffffff;
    }

    .btn-print:hover {
      background-color: #d35400;
    }

    .btn-excel {
      background-color: #198754;
      color: #ffffff;
    }

    .btn-excel:hover {
      background-color: #157347;
    }

    .btn-danger {
      background-color: #dc3545;
      color: #ffffff;
    }

    .btn-danger:hover {
      background-color: #bb2d3b;
    }

    .btn-secondary {
      background-color: #6c757d;
      color: #ffffff;
    }

    .btn-secondary:hover {
      background-color: #5c636a;
    }

    .btn-warning {
      background-color: #ffc107;
      color: #212529;
    }

    .btn-warning:hover {
      background-color: #ffca2c;
    }

    .btn-sm {
      padding: 4px 8px;
      font-size: 12px;
      margin-right: 4px;
    }

    /* Tabel Data (persis seperti Modul 7) */
    table.data-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
      margin-bottom: 25px;
    }

    table.data-table th,
    table.data-table td {
      border: 1px solid #dee2e6;
      padding: 9px 12px;
      text-align: left;
      font-size: 14px;
    }

    table.data-table thead {
      background-color: #d0e8f2;
    }

    table.data-table tbody tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    table.data-table tbody tr:hover {
      background-color: #f1f5f9;
    }

    /* Summary Card Table (persis Modul 7) */
    .summary-card-table {
      width: 420px;
      border-collapse: collapse;
      margin-top: 10px;
    }

    .summary-card-table th {
      background-color: #cfe2ff;
      border: 1px solid #b6d4fe;
      padding: 10px;
      font-size: 13px;
      text-align: center;
    }

    .summary-card-table td {
      border: 1px solid #b6d4fe;
      padding: 12px;
      font-size: 18px;
      font-weight: bold;
      text-align: center;
      background-color: #f8faff;
      color: #0d6efd;
    }

    /* Alert Notifikasi */
    .alert {
      padding: 12px 16px;
      border-radius: 4px;
      margin-bottom: 20px;
      font-size: 14px;
    }

    .alert-success {
      background-color: #d1e7dd;
      border: 1px solid #badbcc;
      color: #0f5132;
    }

    .alert-danger {
      background-color: #f8d7da;
      border: 1px solid #f5c2c7;
      color: #842029;
    }

    .alert-info {
      background-color: #cff4fc;
      border: 1px solid #b6effb;
      color: #055160;
    }

    .alert-warning {
      background-color: #fff3cd;
      border: 1px solid #ffecb5;
      color: #664d03;
    }

    .chart-container {
      max-width: 750px;
      margin: 20px auto 40px auto;
    }

    /* CSS Aturan Khusus Cetak (@media print) */
    @media print {
      .no-print, .navbar, .btn, .alert, footer {
        display: none !important;
      }
      body {
        margin: 0;
        padding: 10px;
        color: #000;
        background: #ffffff;
      }
      table.data-table th, table.data-table td {
        border: 1px solid #000 !important;
      }
      .summary-card-table th, .summary-card-table td {
        border: 1px solid #000 !important;
        color: #000 !important;
      }
      .chart-container {
        max-width: 100% !important;
        page-break-inside: avoid;
      }
    }
  </style>
</head>
<body>

  <!-- NAVBAR MENU UTAMA -->
  <div class="navbar no-print">
    <div style="display: flex; align-items: center;">
      <a href="<?= url('home') ?>" class="navbar-brand">Sistem Penjualan</a>
      
      <ul class="nav-menu">
        <!-- Home -->
        <li class="nav-item">
          <a class="nav-link <?= $currentPage === 'home' ? 'active' : '' ?>" href="<?= url('home') ?>">Home</a>
        </li>

        <!-- DATA MASTER (HANYA MUNCUL JIKA LEVEL = 1 / OWNER) -->
        <?php if ($userLevel === 1): ?>
          <?php 
            $isMasterActive = in_array($currentPage, ['barang', 'supplier', 'pelanggan', 'user']); 
          ?>
          <li class="nav-item dropdown">
            <a class="nav-link <?= $isMasterActive ? 'active' : '' ?>" href="javascript:void(0)">
              Data Master &#9662;
            </a>
            <div class="dropdown-content">
              <a class="<?= $currentPage === 'barang' ? 'active' : '' ?>" href="<?= url('barang') ?>">Data Barang</a>
              <a class="<?= $currentPage === 'supplier' ? 'active' : '' ?>" href="<?= url('supplier') ?>">Data Supplier</a>
              <a class="<?= $currentPage === 'pelanggan' ? 'active' : '' ?>" href="<?= url('pelanggan') ?>">Data Pelanggan</a>
              <a class="<?= $currentPage === 'user' ? 'active' : '' ?>" href="<?= url('user') ?>">Data User</a>
            </div>
          </li>
        <?php endif; ?>

        <!-- Transaksi -->
        <li class="nav-item">
          <a class="nav-link <?= $currentPage === 'transaksi' ? 'active' : '' ?>" href="<?= url('transaksi') ?>">Transaksi</a>
        </li>

        <!-- Laporan -->
        <li class="nav-item">
          <a class="nav-link <?= $currentPage === 'reports' ? 'active' : '' ?>" href="<?= url('reports') ?>">Laporan</a>
        </li>
      </ul>
    </div>

    <!-- NAMA PENGGUNA & LOGOUT -->
    <div class="nav-user">
      <span>Halo, <b><?= htmlspecialchars($userName) ?></b> (<?= $userLevel === 1 ? 'Owner' : 'Kasir' ?>)</span>
      <a href="<?= url('logout') ?>" class="btn-logout" onclick="return confirm('Apakah Anda yakin ingin logout?');">Logout</a>
    </div>
  </div>

  <!-- CONTAINER HALAMAN UTAMA -->
  <div class="container">

    <!-- PESAN FLASH / NOTIFIKASI -->
    <?php if ($flashSuccess = getFlash('success')): ?>
      <div class="alert alert-success no-print"><?= $flashSuccess ?></div>
    <?php endif; ?>

    <?php if ($flashError = getFlash('error')): ?>
      <div class="alert alert-danger no-print"><?= $flashError ?></div>
    <?php endif; ?>

    <?php if ($flashWarning = getFlash('warning')): ?>
      <div class="alert alert-warning no-print"><?= $flashWarning ?></div>
    <?php endif; ?>

    <?php if ($flashInfo = getFlash('info')): ?>
      <div class="alert alert-info no-print"><?= $flashInfo ?></div>
    <?php endif; ?>
