<?php
require_once dirname(__DIR__, 2) . '/auth.php';
wajib_login();

$level = (int) $_SESSION['user']['level'];
?>
<nav class="topbar">
  <a class="brand" href="<?= e(page_url('home')) ?>">Sistem Penjualan</a>

  <div class="navlinks">
    <a href="<?= e(page_url('home')) ?>">Home</a>

    <?php if ($level === 1): ?>
      <details class="nav-dropdown">
        <summary class="nav-dropdown-toggle">
          Data Master <span aria-hidden="true">&#9662;</span>
        </summary>
        <div class="nav-dropdown-menu">
          <a href="<?= e(page_url('master', ['tabel' => 'barang'])) ?>">Data Barang</a>
          <a href="<?= e(page_url('master', ['tabel' => 'supplier'])) ?>">Data Supplier</a>
          <a href="<?= e(page_url('master', ['tabel' => 'pelanggan'])) ?>">Data Pelanggan</a>
          <a href="<?= e(page_url('master', ['tabel' => 'user'])) ?>">Data User</a>
        </div>
      </details>
    <?php endif; ?>

    <a href="<?= e(page_url('transaksi')) ?>">Transaksi</a>
    <a href="<?= e(page_url('reports')) ?>">Laporan</a>
  </div>

  <div class="account">
    <?= e($_SESSION['user']['nama'] ?: $_SESSION['user']['username']) ?>
    (<?= $level === 1 ? 'Owner' : 'Kasir' ?>)
    <a href="<?= e(page_url('logout')) ?>">Logout</a>
  </div>
</nav>
