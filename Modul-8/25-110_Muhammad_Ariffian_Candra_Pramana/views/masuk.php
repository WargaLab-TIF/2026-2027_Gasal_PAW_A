<?php

$pageTitle = "Masuk";

require_once __DIR__ . "/partials/public.header.php";
?>

<section class="auth-section py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-sm-10 col-md-7 col-lg-5">
        <div class="card auth-card border-0 shadow-sm">
          <div class="card-body p-4 p-md-5">
            <div class="auth-icon bg-primary-subtle text-primary mb-3">
              <i class="bi bi-box-arrow-in-right"></i>
            </div>
            <h1 class="h3 fw-bold mb-1">Masuk</h1>
            <p class="text-secondary mb-4">Masuk untuk mengelola data penjualan.</p>

            <form method="post" action="<?= appUrl("sign") ?>">
              <div class="mb-3">
                <label for="username" class="form-label fw-medium">Username</label>
                <input type="text" class="form-control" id="username" name="username"
                  placeholder="Masukkan username" autocomplete="username" required>
              </div>

              <div class="mb-4">
                <label for="password" class="form-label fw-medium">Password</label>
                <input type="password" class="form-control" id="password" name="password"
                  placeholder="Masukkan password" autocomplete="current-password" required>
              </div>

              <button type="submit" class="btn btn-primary w-100 py-2">
                <i class="bi bi-box-arrow-in-right me-1"></i>Masuk
              </button>
            </form>

            <p class="text-center text-secondary small mt-4 mb-0">
              Belum punya akun?
              <a href="<?= appUrl("daftar") ?>" class="text-decoration-none">Daftar</a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . "/partials/public.footer.php"; ?>
