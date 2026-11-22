<?php
$page_title = "Data Transaksi - Sistem Penjualan";
require_once __DIR__ . "/../partials/header.php";

// Ambil seluruh data master transaksi
$transaksis = $conn->query("
  SELECT t.*, p.nama AS nama_pelanggan, p.telp AS telp_pelanggan
  FROM transaksi t
  JOIN pelanggan p ON t.pelanggan_id = p.id
  ORDER BY t.id DESC
")->fetchAll();

// Tentukan transaksi aktif yang dipilih
$transaksi_id = isset($_GET['transaksi_id']) ? (int)$_GET['transaksi_id'] : 0;
if ($transaksi_id <= 0 && !empty($transaksis)) {
  $transaksi_id = (int)$transaksis[0]['id'];
}

$transaksi_terpilih = null;
$detail_transaksi = [];
$barang_sudah_dipakai = [];

if ($transaksi_id > 0) {
  $stmt_master = $conn->prepare("
    SELECT t.*, p.nama AS nama_pelanggan, p.telp AS telp_pelanggan, p.alamat AS alamat_pelanggan
    FROM transaksi t
    JOIN pelanggan p ON t.pelanggan_id = p.id
    WHERE t.id = ?
  ");
  $stmt_master->execute([$transaksi_id]);
  $transaksi_terpilih = $stmt_master->fetch();

  $stmt_detail = $conn->prepare("
    SELECT td.*, b.kode_barang, b.nama_barang
    FROM transaksi_detail td
    JOIN barang b ON td.barang_id = b.id
    WHERE td.transaksi_id = ?
    ORDER BY td.barang_id ASC
  ");
  $stmt_detail->execute([$transaksi_id]);
  $detail_transaksi = $stmt_detail->fetchAll();

  foreach ($detail_transaksi as $dt) {
    $barang_sudah_dipakai[] = (int)$dt['barang_id'];
  }
}

// Ambil semua barang untuk dropdown
$semua_barang = $conn->query("SELECT * FROM barang ORDER BY nama_barang ASC")->fetchAll();
?>

<h2>Pengelolaan Transaksi (Master-Detail)</h2>
<p style="margin-top: 0; color: #666; font-size: 13px;">Kelola transaksi penjualan dan rincian item barang pesanan</p>

<div style="margin-bottom: 15px;">
  <a href="<?= url('transaksi-add') ?>" class="btn btn-primary">+ Buat Transaksi Baru</a>
</div>

<!-- BAGIAN 1: TABEL MASTER TRANSAKSI -->
<h3>1. Data Master: Tabel Transaksi</h3>
<div style="overflow-x: auto; max-height: 300px; overflow-y: auto; border: 1px solid #dee2e6; margin-bottom: 25px;">
  <table class="data-table" style="margin: 0;">
    <thead>
      <tr>
        <th style="width: 50px; text-align: center;">No</th>
        <th style="width: 120px;">ID Transaksi</th>
        <th style="width: 120px;">Tanggal</th>
        <th>Pelanggan</th>
        <th>Keterangan</th>
        <th style="width: 130px;">Total (Rp)</th>
        <th style="width: 100px; text-align: center;">Status</th>
        <th style="width: 120px; text-align: center;">Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($transaksis)): ?>
        <?php foreach ($transaksis as $i => $t): ?>
          <?php $isSelected = ($t['id'] == $transaksi_id); ?>
          <tr style="<?= $isSelected ? 'background-color: #fff8e1;' : '' ?>">
            <td style="text-align: center;"><?= $i + 1 ?></td>
            <td><b>TRX-<?= str_pad($t['id'], 3, '0', STR_PAD_LEFT) ?></b></td>
            <td><?= date('d M Y', strtotime($t['waktu_transaksi'])) ?></td>
            <td><?= htmlspecialchars($t['nama_pelanggan']) ?></td>
            <td><?= htmlspecialchars($t['keterangan'] ?? '-') ?></td>
            <td style="font-weight: bold; color: #0d6efd;">Rp <?= number_format($t['total'], 0, ',', '.') ?></td>
            <td style="text-align: center;">
              <?= $isSelected ? '<span style="color: #d35400; font-weight: bold;">Terpilih</span>' : '<span style="color: #999;">-</span>' ?>
            </td>
            <td style="text-align: center;">
              <?php if ($isSelected): ?>
                <span class="btn btn-secondary btn-sm" style="cursor: default;">Sedang Dilihat</span>
              <?php else: ?>
                <a href="<?= url('transaksi', ['transaksi_id' => $t['id']]) ?>" class="btn btn-submit btn-sm">
                  Pilih / Detail
                </a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="8" style="text-align: center; color: #888;">Belum ada data transaksi.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- BAGIAN 2: TABEL DETAIL TRANSAKSI TERPILIH -->
<?php if ($transaksi_terpilih): ?>
  <div style="border-top: 2px solid #1a4784; padding-top: 20px; margin-top: 30px;">
    <h3>2. Data Detail Transaksi: TRX-<?= str_pad($transaksi_terpilih['id'], 3, '0', STR_PAD_LEFT) ?></h3>
    
    <!-- Ringkasan Master Terpilih -->
    <div class="form-container" style="padding: 12px 16px; margin-bottom: 15px;">
      <p style="margin: 4px 0;"><strong>Tanggal:</strong> <?= date('d F Y', strtotime($transaksi_terpilih['waktu_transaksi'])) ?> | <strong>Pelanggan:</strong> <?= htmlspecialchars($transaksi_terpilih['nama_pelanggan']) ?> (<?= htmlspecialchars($transaksi_terpilih['telp_pelanggan']) ?>) | <strong>Total Master:</strong> <span style="color: #0d6efd; font-weight: bold;">Rp <?= number_format($transaksi_terpilih['total'], 0, ',', '.') ?></span></p>
      <p style="margin: 4px 0;"><strong>Keterangan:</strong> <?= htmlspecialchars($transaksi_terpilih['keterangan'] ?? '-') ?></p>
    </div>

    <!-- Tabel Rincian Detail -->
    <table class="data-table">
      <thead>
        <tr>
          <th style="width: 50px; text-align: center;">No</th>
          <th style="width: 120px;">Kode Barang</th>
          <th>Nama Barang</th>
          <th style="width: 130px;">Harga Satuan</th>
          <th style="width: 80px; text-align: center;">Qty</th>
          <th style="width: 140px;">Subtotal</th>
          <th style="width: 90px; text-align: center;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($detail_transaksi)): ?>
          <?php $subtotalTotal = 0; ?>
          <?php foreach ($detail_transaksi as $i => $item): ?>
            <?php 
              $subtotal = $item['harga'] * $item['qty']; 
              $subtotalTotal += $subtotal;
            ?>
            <tr>
              <td style="text-align: center;"><?= $i + 1 ?></td>
              <td><b><?= htmlspecialchars($item['kode_barang']) ?></b></td>
              <td><?= htmlspecialchars($item['nama_barang']) ?></td>
              <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
              <td style="text-align: center;"><b><?= $item['qty'] ?></b></td>
              <td style="font-weight: bold;">Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
              <td style="text-align: center;">
                <a href="<?= url('transaksi-detail-delete', ['transaksi_id' => $transaksi_terpilih['id'], 'barang_id' => $item['barang_id']]) ?>" 
                   class="btn btn-danger btn-sm" 
                   onclick="return confirm('Hapus item ini dari transaksi? Total transaksi akan dihitung ulang secara otomatis.');">
                  Hapus
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
          <tr style="background-color: #eef5fb; font-weight: bold;">
            <td colspan="5" style="text-align: right;">Total Detail Kumulatif:</td>
            <td colspan="2" style="color: #0d6efd; font-size: 15px;">Rp <?= number_format($subtotalTotal, 0, ',', '.') ?></td>
          </tr>
        <?php else: ?>
          <tr>
            <td colspan="7" style="text-align: center; color: #888;">Belum ada rincian item barang pada transaksi ini.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>

    <!-- FORM TAMBAH DETAIL BARANG (DENGAN SELECT OPTION DISABLE UNTUK BARANG DUPLIKAT) -->
    <div class="form-container" style="max-width: 750px;">
      <h4 style="margin-top: 0;">Tambah Item Barang ke Transaksi Ini</h4>
      
      <form method="POST" action="<?= url('transaksi-detail-add') ?>">
        <input type="hidden" name="transaksi_id" value="<?= $transaksi_terpilih['id'] ?>">

        <div style="display: flex; gap: 15px; align-items: flex-end;">
          <div style="flex: 3;">
            <label for="barang_id" style="font-weight: bold; font-size: 13px; display: block; margin-bottom: 5px;">Pilih Barang:</label>
            <select name="barang_id" id="barang_id" class="form-control" required>
              <option value="">-- Pilih Barang --</option>
              <?php foreach ($semua_barang as $b): ?>
                <?php $isUsed = in_array((int)$b['id'], $barang_sudah_dipakai); ?>
                <?php if ($isUsed): ?>
                  <!-- BARANG SUDAH ADA: DINONAKTIFKAN (DISABLED) AGAR TIDAK BISA DIPILIH DUA KALI -->
                  <option value="<?= $b['id'] ?>" disabled style="color: #999; background-color: #eee;">
                    [SUDAH DIPILIH] <?= htmlspecialchars($b['kode_barang']) ?> - <?= htmlspecialchars($b['nama_barang']) ?>
                  </option>
                <?php else: ?>
                  <option value="<?= $b['id'] ?>">
                    <?= htmlspecialchars($b['kode_barang']) ?> - <?= htmlspecialchars($b['nama_barang']) ?> (Rp <?= number_format($b['harga'], 0, ',', '.') ?> | Stok: <?= $b['stok'] ?>)
                  </option>
                <?php endif; ?>
              <?php endforeach; ?>
            </select>
          </div>

          <div style="flex: 1;">
            <label for="qty" style="font-weight: bold; font-size: 13px; display: block; margin-bottom: 5px;">Qty:</label>
            <input type="number" class="form-control" id="qty" name="qty" min="1" value="1" required>
          </div>

          <div>
            <button type="submit" class="btn btn-submit" style="height: 38px;">+ Tambah Item</button>
          </div>
        </div>
        <small style="color: #666; display: block; margin-top: 6px;">* Barang yang sudah tercatat di transaksi ini otomatis dinonaktifkan (disabled) pada opsi pilihan.</small>
      </form>
    </div>

  </div>
<?php endif; ?>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>
