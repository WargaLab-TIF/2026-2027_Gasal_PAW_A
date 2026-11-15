<!-- ==========================================================
     FILE HEADER HALAMAN: header.auth.php
     ========================================================== -->
<!doctype html>
<html lang="id" data-bs-theme="light">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Laporan Penjualan - Sistem Penjualan</title>
  <!-- Memuat CSS Bootstrap 6 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@6.0.0-alpha.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* Aturan CSS khusus saat halaman dicetak (window.print):
       Menyembunyikan form filter, tombol cetak/ekspor, dan navbar */
    @media print {
      .no-print,
      .d-print-none,
      nav.navbar {
        display: none !important;
      }
    }
  </style>
</head>

<body>
  <!-- Navigasi Utama Aplikasi (Disembunyikan saat proses cetak) -->
  <nav class="navbar md:navbar-expand bg-1 d-print-none no-print">
    <div class="container">
      <a class="navbar-brand" href="<?= routeUrl("/") ?>">Penjualan</a>
      <button class="btn-icon navbar-toggler" type="button" data-bs-toggle="drawer" data-bs-target="#menu"
        aria-controls="menu" aria-expanded="false" aria-label="Buka menu"><span class="navbar-toggler-icon"
          aria-hidden="true"></span></button>
      <dialog class="drawer drawer-end" tabindex="-1" id="menu" aria-labelledby="menuLabel">
        <div class="drawer-header">
          <h5 class="drawer-title" id="menuLabel">Menu</h5><button type="button" class="btn-close"
            data-bs-dismiss="drawer" aria-label="Tutup"></button>
        </div>
        <div class="drawer-body">
          <ul class="nav navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link active" href="<?= routeUrl("/reports") ?>">Reports</a></li>
          </ul>
        </div>
      </dialog>
    </div>
  </nav>
