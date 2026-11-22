<?php
$page_title = "Login - Sistem Penjualan";
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $page_title ?></title>
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f4f6f9;
      color: #333;
    }

    .login-container {
      width: 380px;
      margin: 80px auto;
      background: #ffffff;
      padding: 28px 24px;
      border: 1px solid #dee2e6;
      border-radius: 6px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .login-header {
      text-align: center;
      margin-bottom: 24px;
    }

    .login-header h2 {
      margin: 0 0 6px 0;
      color: #1a4784;
      font-size: 22px;
    }

    .login-header p {
      margin: 0;
      font-size: 13px;
      color: #666;
    }

    .form-group {
      margin-bottom: 16px;
    }

    .form-group label {
      display: block;
      font-weight: bold;
      font-size: 13px;
      margin-bottom: 6px;
      color: #333;
    }

    .form-control {
      width: 100%;
      padding: 9px 12px;
      font-size: 14px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
    }

    .form-control:focus {
      border-color: #1a4784;
      outline: none;
    }

    .btn-submit {
      width: 100%;
      background-color: #0d6efd;
      color: #ffffff;
      padding: 10px;
      font-size: 14px;
      font-weight: bold;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      margin-top: 8px;
    }

    .btn-submit:hover {
      background-color: #0b5ed7;
    }

    .alert {
      padding: 10px 14px;
      border-radius: 4px;
      margin-bottom: 18px;
      font-size: 13px;
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

    .demo-account {
      margin-top: 24px;
      padding-top: 14px;
      border-top: 1px solid #eee;
      font-size: 12px;
      color: #555;
      background-color: #fcfcfc;
      padding: 12px;
      border-radius: 4px;
    }

    .demo-account strong {
      color: #1a4784;
    }

    .demo-account ul {
      margin: 6px 0 0 0;
      padding-left: 18px;
    }

    .demo-account li {
      margin-bottom: 4px;
    }
  </style>
</head>
<body>

  <div class="login-container">
    <div class="login-header">
      <h2>Sistem Penjualan</h2>
      <p>Silakan login untuk mengakses aplikasi</p>
    </div>

    <!-- PESAN LOGOUT ATAU FLASH -->
    <?php if (isset($_GET['pesan']) && $_GET['pesan'] === 'logout'): ?>
      <div class="alert alert-success">Anda telah berhasil keluar (logout).</div>
    <?php endif; ?>

    <?php if ($flashError = getFlash('error')): ?>
      <div class="alert alert-danger"><?= $flashError ?></div>
    <?php endif; ?>

    <?php if ($flashSuccess = getFlash('success')): ?>
      <div class="alert alert-success"><?= $flashSuccess ?></div>
    <?php endif; ?>

    <!-- FORM LOGIN -->
    <form method="POST" action="<?= url('sign-in') ?>">
      <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" required autofocus>
      </div>

      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
      </div>

      <button type="submit" class="btn-submit">Login</button>
    </form>

    <!-- PETUNJUK AKUN ASISTENSI -->
    <div class="demo-account">
      <strong>Akun Uji Coba (Asistensi):</strong>
      <ul>
        <li>Level 1 (Owner): <b>budi</b> / password: <b>123456</b></li>
        <li>Level 2 (Kasir): <b>wati</b> / password: <b>123456</b></li>
      </ul>
    </div>
  </div>

</body>
</html>
