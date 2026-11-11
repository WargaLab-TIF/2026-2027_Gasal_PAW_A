<?php
require_once 'koneksi.php';

$id = (int)($_GET['id'] ?? 0);

// ---- MASTER: data transaksi yang dipilih ----
$stmt = $db->prepare("SELECT t.id, t.waktu_transaksi, t.keterangan, t.total,
                             p.id AS pelanggan_id, p.nama AS pelanggan
                      FROM transaksi t
                      JOIN pelanggan p ON p.id = t.pelanggan_id
                      WHERE t.id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$master = $stmt->get_result()->fetch_assoc();

if (!$master) {
    flash('error', 'Transaksi tidak ditemukan.');
    header('Location: transaksi.php');
    exit;
}

// ---- DETAIL: barang pada transaksi ini (relasi lewat foreign key) ----
$stmt = $db->prepare("SELECT d.barang_id, b.kode_barang, b.nama_barang,
                             d.harga, d.qty, d.harga * d.qty AS subtotal
                      FROM transaksi_detail d
                      JOIN barang b ON b.id = d.barang_id
                      WHERE d.transaksi_id = ?
                      ORDER BY b.nama_barang");
$stmt->bind_param('i', $id);
$stmt->execute();
$detail = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// ID barang yang sudah dimasukkan -> dinonaktifkan di dropdown
$terpakai = array_column($detail, 'barang_id');

$semuaBarang = $db->query(
    "SELECT id, kode_barang, nama_barang, harga FROM barang ORDER BY nama_barang"
)->fetch_all(MYSQLI_ASSOC);

// Navigasi antar master
$prev = $db->query("SELECT MAX(id) FROM transaksi WHERE id < $id")->fetch_row()[0];
$next = $db->query("SELECT MIN(id) FROM transaksi WHERE id > $id")->fetch_row()[0];

$judul = 'Detail Transaksi #' . $id;
require 'header.php';
?>
<h2>Detail Transaksi #<?= (int)$master['id'] ?></h2>

<p>
  <a class="btn gray" href="transaksi.php">&laquo; Daftar Transaksi</a>
  <?php if ($prev): ?>
    <a class="btn gray" href="?id=<?= (int)$prev ?>">&lsaquo; Sebelumnya</a>
  <?php endif; ?>
  <?php if ($next): ?>
    <a class="btn gray" href="?id=<?= (int)$next ?>">Berikutnya &rsaquo;</a>
  <?php endif; ?>
</p>

<!-- ===== MASTER ===== -->
<div class="panel">
  <dl>
    <dt>No. Transaksi</dt><dd><?= (int)$master['id'] ?></dd>
    <dt>Tanggal</dt><dd><?= h($master['waktu_transaksi']) ?></dd>
    <dt>Pelanggan</dt>
    <dd><?= h($master['pelanggan_id'] . ' - ' . $master['pelanggan']) ?></dd>
    <dt>Keterangan</dt><dd><?= h($master['keterangan']) ?></dd>
    <dt>Total</dt><dd><strong><?= rupiah($master['total']) ?></strong></dd>
  </dl>
</div>

<!-- ===== DETAIL ===== -->
<h3>Barang pada Transaksi</h3>
<table>
  <tr>
    <th>No</th><th>Kode</th><th>Nama Barang</th>
    <th class="num">Harga</th><th class="num">Qty</th><th class="num">Subtotal</th>
  </tr>
  <?php if (!$detail): ?>
    <tr><td colspan="6">Belum ada detail transaksi.</td></tr>
  <?php endif; ?>
  <?php foreach ($detail as $no => $d): ?>
  <tr>
    <td><?= $no + 1 ?></td>
    <td><?= h($d['kode_barang']) ?></td>
    <td><?= h($d['nama_barang']) ?></td>
    <td class="num"><?= rupiah($d['harga']) ?></td>
    <td class="num"><?= (int)$d['qty'] ?></td>
    <td class="num"><?= rupiah($d['subtotal']) ?></td>
  </tr>
  <?php endforeach; ?>
  <tr>
    <th colspan="5" class="num">Total</th>
    <th class="num"><?= rupiah($master['total']) ?></th>
  </tr>
</table>

<!-- ===== FORM INPUT DETAIL ===== -->
<h3>Tambah Barang</h3>
<form method="post" action="simpan_detail.php" class="inline">
  <input type="hidden" name="transaksi_id" value="<?= (int)$master['id'] ?>">
  <label>Barang
    <select name="barang_id" required>
      <option value="" selected disabled>-- Pilih barang --</option>
      <?php foreach ($semuaBarang as $b):
          $dipakai = in_array($b['id'], $terpakai); ?>
        <option value="<?= (int)$b['id'] ?>" <?= $dipakai ? 'disabled' : '' ?>>
          <?= h($b['kode_barang'] . ' - ' . $b['nama_barang']
              . ' (' . rupiah($b['harga']) . ')') ?>
          <?= $dipakai ? '- sudah dipilih' : '' ?>
        </option>
      <?php endforeach; ?>
    </select>
  </label>
  <label>Qty
    <input type="number" name="qty" min="1" value="1" required>
  </label>
  <button type="submit" class="btn">Simpan Detail</button>
</form>
</div>
</body>
</html>
