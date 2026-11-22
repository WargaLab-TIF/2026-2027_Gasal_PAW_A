<?php
require_once dirname(__DIR__, 2) . '/conn.php';
require_once dirname(__DIR__, 2) . '/auth.php';
wajib_login();

$judul = 'Transaksi';
$query = 'SELECT
            t.id,
            t.waktu_transaksi,
            t.keterangan,
            t.total,
            p.nama AS pelanggan
          FROM transaksi AS t
          LEFT JOIN pelanggan AS p ON p.id = t.pelanggan_id
          ORDER BY t.id DESC';
$result = mysqli_query($koneksi, $query);

require dirname(__DIR__) . '/partials/header.php';
?>
<section class="page">
  <div class="panel">
    <div class="table-heading">
      <div>
        <div class="eyebrow">Data Penjualan</div>
        <h1 class="container-title">Daftar Transaksi</h1>
      </div>
      <div class="heading-actions">
        <span><?= mysqli_num_rows($result) ?> transaksi</span>
        <a class="btn btn-primary" href="<?= e(page_url('transaksi-tambah')) ?>">
          + Transaksi Baru
        </a>
      </div>
    </div>

    <div class="table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>No</th>
            <th>ID Transaksi</th>
            <th>Tanggal Transaksi</th>
            <th>Pelanggan</th>
            <th>Keterangan</th>
            <th class="text-right">Total</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; ?>
          <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
              <td class="row-number"><?= $no++ ?></td>
              <td class="mono"><?= (int) $row['id'] ?></td>
              <td><?= e($row['waktu_transaksi']) ?></td>
              <td class="strong"><?= e($row['pelanggan'] ?? '-') ?></td>
              <td><?= e($row['keterangan'] ?: '-') ?></td>
              <td class="text-right strong">
                Rp <?= number_format((int) $row['total'], 0, ',', '.') ?>
              </td>
              <td>
                <a
                  class="action-edit"
                  href="<?= e(page_url('transaksi-detail', ['id' => (int) $row['id']])) ?>"
                >Lihat / Isi Detail</a>
              </td>
            </tr>
          <?php endwhile; ?>

          <?php if (mysqli_num_rows($result) === 0): ?>
            <tr>
              <td colspan="7" class="empty-state">Belum ada data transaksi.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>
<?php require dirname(__DIR__) . '/partials/footer.php'; ?>
