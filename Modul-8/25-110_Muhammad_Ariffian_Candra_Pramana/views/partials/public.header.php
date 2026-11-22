<?php
$pageTitle = $pageTitle ?? "Penjualan";

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (isset($_SESSION["user_login"])) {
  header("Location: " . appUrl("pelanggan"));
  exit;
}
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= sanitazion($pageTitle) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.2/font/bootstrap-icons.min.css">
  <style>
    body {
      background-color: #ffffff;
      color: #212529;
    }

    .site-navbar {
      background-color: #f8f9fa;
    }

    .hero-title {
      font-size: clamp(2.75rem, 6vw, 4.5rem);
      font-weight: 700;
      letter-spacing: -0.03em;
      line-height: 1.05;
    }

    .hero-subtitle {
      font-size: 1.125rem;
      max-width: 660px;
      margin-inline: auto;
    }

    .feature-link {
      color: #0d6efd;
      text-decoration: underline;
      text-underline-offset: 5px;
    }

    .feature-link:hover {
      color: #0a58ca;
    }

    .menu-section {
      background-color: #f5f7fb;
    }

    .menu-card {
      border-radius: 10px;
      transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .menu-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.08) !important;
    }

    .icon-box {
      width: 48px;
      height: 48px;
      border-radius: 8px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      color: #ffffff;
      font-size: 1.35rem;
    }

    .auth-section {
      background-color: #f5f7fb;
      min-height: calc(100vh - 172px);
      display: flex;
      align-items: center;
    }

    .auth-card {
      border-radius: 12px;
    }

    .auth-icon {
      width: 56px;
      height: 56px;
      border-radius: 12px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg site-navbar py-3">
    <div class="container">
      <a class="navbar-brand fw-bold" href="<?= appUrl("/") ?>">Penjualan</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#publicNav"
        aria-controls="publicNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>
  </nav>
