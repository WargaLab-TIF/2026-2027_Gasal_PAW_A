<!-- halaman 404 -->
<!doctype html>
<html lang="id" data-bs-theme="light">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>404 - Halaman Tidak Ditemukan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@6.0.0-alpha.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-body-tertiary">
  <main class="container py-9">
    <div class="row justify-content-center text-center py-6">
      <div class="md:col-8 lg:col-6">
        <div class="fs-5xl fw-bold text-primary mb-3">404</div>
        <h1 class="fs-2xl mb-3">Halaman Tidak Ditemukan</h1>
        <p class="mb-6" style="color: #64748b;">
          Maaf, halaman yang Anda cari tidak tersedia atau alamat URL salah.
        </p>
        <div class="d-flex gap-3 justify-content-center">
          <a href="<?= routeUrl(isLoggedIn() ? "/" : "/login") ?>" class="btn btn-solid theme-primary">
            <?= isLoggedIn() ? "Kembali ke Home" : "Ke Halaman Login" ?>
          </a>
          <a href="javascript:history.back()" class="btn btn-outline theme-primary">&larr; Halaman Sebelumnya</a>
        </div>
      </div>
    </div>
  </main>
  <script type="module"
    src="https://cdn.jsdelivr.net/npm/bootstrap@6.0.0-alpha.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
