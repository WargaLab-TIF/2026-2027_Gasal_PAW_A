<?php
require_once dirname(__DIR__) . '/auth.php';
if (!sudah_login()) {
    header('Location: ' . page_url('login'));
    exit;
}
$judul = 'Home';
require __DIR__ . '/partials/header.php';
?>
<section class="page">
  <div class="panel">
    <h1>Selamat datang, <?= e($_SESSION['user']['nama'] ?: $_SESSION['user']['username']) ?>!</h1>
    <p>Anda masuk sebagai <strong><?= (int) $_SESSION['user']['level'] === 1 ? 'Owner' : 'Kasir' ?></strong>.</p>
    <p>Gunakan menu navigasi untuk membuka transaksi dan laporan.</p>
  </div>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>
