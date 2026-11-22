<?php
$pageTitle = $pageTitle ?? "Penjualan";

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (!isset($_SESSION["user_login"])) {
  header("Location: " . appUrl("masuk"));
  exit;
}

$level = (int) ($_SESSION["user_level"] ?? 0);



$masterItems = [
  "pelanggan" => ["label" => "Pelanggan", "icon" => "bi-people"],
  "supplier" => ["label" => "Supplier", "icon" => "bi-truck"],
  "barang" => ["label" => "Barang", "icon" => "bi-box-seam"],
  "user" => ["label" => "User", "icon" => "bi-person-badge"],
];

$navItems = [
  "transaksi" => ["label" => "Transaksi", "icon" => "bi-receipt"],
  "reports"   => ["label" => "Laporan",   "icon" => "bi-graph-up"],
];


$activePage = $page ?? "";

if ($activePage === "") {
  $currentPath = trim($_SERVER["PATH_INFO"] ?? "", "/");
  $activePage = explode("/", $currentPath)[0] ?? "";
}

?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= sanitazion($pageTitle) ?> - Penjualan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.2/font/bootstrap-icons.min.css">
  <style>
    body {
      background-color: #f5f7fb;
    }

    .table thead th {
      white-space: nowrap;
    }

    .card {
      border: 0;
    }

    @media print {

      .report-filter,
      .report-actions {
        display: none !important;
      }

      body {
        background-color: #ffffff;
      }

      .report-card {
        border: 1px solid #000000 !important;
        box-shadow: none !important;
      }
    }
  </style>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-semibold" href="<?= appUrl("/") ?>">
      <i class="bi bi-shop me-1"></i> Sistem Penjualan
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav"
      aria-controls="adminNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="adminNav">
      <ul class="navbar-nav me-auto gap-lg-1">
        <li class="nav-item">
          <a class="nav-link <?= $activePage === "" ? "active fw-semibold" : "" ?>" href="<?= appUrl("/") ?>">
            <i class="bi bi-house me-1"></i>Home
          </a>
        </li>

        <?php if ($level === 1): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle <?= isset($masterItems[$activePage]) ? "active fw-semibold" : "" ?>"
              href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-database me-1"></i>Data Master
            </a>
            <ul class="dropdown-menu">
              <?php foreach ($masterItems as $key => $item): ?>
                <li>
                  <a class="dropdown-item <?= $activePage === $key ? "active" : "" ?>" href="<?= appUrl($key) ?>">
                    <i class="bi <?= $item["icon"] ?> me-1"></i><?= sanitazion($item["label"]) ?>
                  </a>
                </li>
              <?php endforeach ?>
            </ul>
          </li>
        <?php endif ?>

        <?php foreach ($navItems as $key => $item): ?>
          <li class="nav-item">
            <a class="nav-link <?= $activePage === $key ? "active fw-semibold" : "" ?>" href="<?= appUrl($key) ?>">
              <i class="bi <?= $item["icon"] ?> me-1"></i><?= sanitazion($item["label"]) ?>
            </a>
          </li>
        <?php endforeach ?>
      </ul>

      <ul class="navbar-nav">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle me-1"></i><?= sanitazion($_SESSION["user_login"]) ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li>
              <a class="dropdown-item" href="<?= appUrl("logout") ?>">
                <i class="bi bi-box-arrow-right me-1"></i>Keluar
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
