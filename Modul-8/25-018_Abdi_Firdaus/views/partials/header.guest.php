<?php
// header halaman login

requireGuest();
$pageTitle = $pageTitle ?? 'Login - Sistem Penjualan';
$flash = getFlash();
?>
<!doctype html>
<html lang="id" data-bs-theme="light">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= sanitazion($pageTitle) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@6.0.0-alpha.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      min-height: 100vh;
      background: linear-gradient(135deg, #eef4ff 0%, #f8fafc 60%, #e0ecff 100%);
      display: flex;
      flex-direction: column;
    }

    .login-card {
      background: #ffffff;
      border: 1px solid #dbe4f0;
      border-radius: 12px;
      box-shadow: 0 12px 30px rgba(30, 75, 133, 0.12);
    }

    .login-header {
      background: linear-gradient(90deg, #1a3f73 0%, #245799 100%);
      color: #ffffff;
      border-radius: 11px 11px 0 0;
      padding: 1.35rem 1.5rem;
    }
  </style>
</head>

<body>
