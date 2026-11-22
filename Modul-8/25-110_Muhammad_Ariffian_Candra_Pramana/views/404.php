<?php
http_response_code(404);
$pageTitle = "Halaman Tidak Ditemukan";
require_once __DIR__ . "/partials/header.php";
?>

<main class="container py-5 text-center">
  <h1 class="display-4 fw-bold text-secondary">404</h1>
  <p class="text-muted mb-4">Halaman atau data yang Anda cari tidak ditemukan.</p>
  <a href="<?= appUrl("pelanggan") ?>" class="btn btn-primary">
    <i class="bi bi-arrow-left me-1"></i> Kembali ke Data Pelanggan
  </a>
</main>

<?php require_once __DIR__ . "/partials/footer.php" ?>
