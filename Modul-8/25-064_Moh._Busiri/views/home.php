<?php
$page_title = "Home - Sistem Penjualan";
require_once __DIR__ . "/partials/header.php";

// Ambil beberapa statistik data toko
$totalBarang = $conn->query("SELECT COUNT(*) FROM barang")->fetchColumn();
$totalSupplier = $conn->query("SELECT COUNT(*) FROM supplier")->fetchColumn();
$totalPelanggan = $conn->query("SELECT COUNT(*) FROM pelanggan")->fetchColumn();
$totalTransaksi = $conn->query("SELECT COUNT(*) FROM transaksi")->fetchColumn();
$totalPendapatan = $conn->query("SELECT COALESCE(SUM(total), 0) FROM transaksi")->fetchColumn();
?>

<h2>Selamat Datang di Sistem Penjualan</h2>
<p>Halo, <strong><?= htmlspecialchars($_SESSION['nama']) ?></strong>! Anda saat ini login sebagai <strong><?= $_SESSION['level'] === 1 ? 'Owner / Pemilik (Level 1)' : 'Kasir (Level 2)' ?></strong>.</p>

<div style="margin: 20px 0;">
  <a href="<?= url('transaksi') ?>" class="btn btn-primary">Buka Transaksi</a>
  <a href="<?= url('reports') ?>" class="btn btn-excel">Buka Laporan</a>
</div>

<h3>Ringkasan Data Toko</h3>
<table class="data-table" style="max-width: 650px;">
  <thead>
    <tr>
      <th>Kategori</th>
      <th style="text-align: right; width: 200px;">Jumlah / Total</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Total Data Barang</td>
      <td style="text-align: right;"><b><?= number_format($totalBarang) ?></b> item</td>
    </tr>
    <tr>
      <td>Total Data Supplier</td>
      <td style="text-align: right;"><b><?= number_format($totalSupplier) ?></b> pemasok</td>
    </tr>
    <tr>
      <td>Total Data Pelanggan</td>
      <td style="text-align: right;"><b><?= number_format($totalPelanggan) ?></b> pelanggan</td>
    </tr>
    <tr>
      <td>Total Transaksi Penjualan</td>
      <td style="text-align: right;"><b><?= number_format($totalTransaksi) ?></b> transaksi</td>
    </tr>
    <tr>
      <td>Total Akumulasi Pendapatan</td>
      <td style="text-align: right; color: #0d6efd; font-size: 15px;"><b>Rp <?= number_format($totalPendapatan, 0, ',', '.') ?></b></td>
    </tr>
  </tbody>
</table>

<div class="form-container" style="margin-top: 25px;">
  <h3>Keterangan Hak Akses Sistem</h3>
  <?php if ($_SESSION['level'] === 1): ?>
    <p>Sebagai <strong>Owner (Level 1)</strong>, Anda memiliki hak akses penuh ke seluruh fitur aplikasi:</p>
    <ul>
      <li><strong>Home:</strong> Melihat ringkasan data toko.</li>
      <li><strong>Data Master:</strong> Mengelola proses CRUD untuk Data Barang, Data Supplier, Data Pelanggan, dan Data User.</li>
      <li><strong>Transaksi:</strong> Mengelola data master transaksi dan detail barang.</li>
      <li><strong>Laporan:</strong> Meninjau grafik penjualan harian, tabel rekap, cetak print, dan ekspor ke Excel.</li>
    </ul>
  <?php else: ?>
    <p>Sebagai <strong>Kasir (Level 2)</strong>, hak akses Anda dikhususkan untuk kasir/penjualan:</p>
    <ul>
      <li><strong>Home:</strong> Melihat ringkasan data toko.</li>
      <li><strong>Transaksi:</strong> Melakukan input penjualan dan kelola detail barang belanjaan.</li>
      <li><strong>Laporan:</strong> Melihat grafik penjualan, tabel rekap, cetak print, dan ekspor ke Excel.</li>
      <li><em>Catatan: Menu Data Master disembunyikan dan diproteksi dari akun kasir.</em></li>
    </ul>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . "/partials/footer.php"; ?>
