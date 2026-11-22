<?php
// halaman form login

$pageTitle = 'Login - Sistem Penjualan';
$oldUsername = $_SESSION['old_username'] ?? '';
unset($_SESSION['old_username']);
require_once __DIR__ . "/partials/header.guest.php";
?>

<main class="container my-auto py-8">
  <div class="row justify-content-center">
    <div class="md:col-8 lg:col-5">
      <div class="login-card">
        <div class="login-header">
          <h1 class="fs-2xl mb-0 text-white">Sistem Penjualan</h1>
        </div>

        <div class="p-6">
          <?php if ($flash): ?>
            <div class="alert theme-<?= sanitazion($flash['type']) ?> mb-5" role="alert">
              <?= sanitazion($flash['message']) ?>
            </div>
          <?php endif ?>

          <form method="POST" action="<?= routeUrl("/sign-in") ?>">
            <div class="form-field mb-5">
              <label class="form-label" for="username">Username</label>
              <input class="form-control" type="text" id="username" name="username"
                value="<?= sanitazion($oldUsername) ?>" maxlength="30" autocomplete="username"
                placeholder="Masukkan username" required autofocus>
            </div>

            <div class="form-field mb-6">
              <label class="form-label" for="password">Password</label>
              <input class="form-control" type="password" id="password" name="password"
                autocomplete="current-password" placeholder="Masukkan password" required>
            </div>

            <button type="submit" class="btn btn-solid theme-primary w-100">Login</button>
          </form>

          <hr class="my-6">

          <div style="font-size: 0.85rem; color: #475569;">
            <div class="fw-semibold mb-2">Akun Pengujian:</div>
            <div>&bull; <strong>Owner (Level 1):</strong> <code>admin</code> / <code>admin123</code></div>
            <div>&bull; <strong>Kasir (Level 2):</strong> <code>kasir1</code> / <code>kasir123</code></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<?php require_once __DIR__ . "/partials/footer.guest.php" ?>
