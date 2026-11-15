<!-- ==========================================================
     FILE HALAMAN ERROR 404: 404.php
     ========================================================== -->
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>404 - Halaman Tidak Ditemukan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@6.0.0-alpha.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-body-tertiary">

  <!-- Pesan pemberitahuan ketika URL yang diakses tidak terdaftar di router -->
  <main class="container min-vh-75 py-5">
    <div class="row justify-content-center text-center py-5">
      <div class="col-md-8 col-lg-6">
        <div class="display-1 fw-bold text-primary">404</div>
        <h2 class="mb-3">Halaman Tidak Ditemukan</h2>
        <p class="text-body-secondary mb-4">Maaf, halaman yang Anda cari tidak ada, sudah dipindahkan, atau alamatnya salah.</p>
        <div class="d-flex gap-2 justify-content-center">
          <a href="<?= routeUrl("/") ?>" class="btn btn-primary">Kembali ke Beranda</a>
          <a href="javascript:history.back()" class="btn btn-outline-secondary">← Halaman Sebelumnya</a>
        </div>
      </div>
    </div>
  </main>
  <script type="module"
    src="https://cdn.jsdelivr.net/npm/bootstrap@6.0.0-alpha.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
