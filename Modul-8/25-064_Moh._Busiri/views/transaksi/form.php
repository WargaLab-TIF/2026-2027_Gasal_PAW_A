<?php
$page_title = "Buat Transaksi Baru - Sistem Penjualan";
require_once __DIR__ . "/../partials/header.php";

$pelanggans = $conn->query("SELECT * FROM pelanggan ORDER BY nama ASC")->fetchAll();
?>

<h2>Buat Transaksi Baru</h2>
<p style="margin-top: 0; color: #666; font-size: 13px;">Langkah 1: Masukkan data master transaksi sebelum menambahkan item detail barang</p>

<div class="form-container" style="max-width: 600px;">
  <form method="POST" action="<?= url('transaksi-save') ?>">
    
    <div class="form-group">
      <label for="pelanggan_id">Pilih Pelanggan:</label>
      <select class="form-control" id="pelanggan_id" name="pelanggan_id" required>
        <option value="">-- Pilih Pelanggan --</option>
        <?php foreach ($pelanggans as $p): ?>
          <option value="<?= $p['id'] ?>">
            <?= htmlspecialchars($p['nama']) ?> (<?= $p['jenis_kelamin'] === 'L' ? 'L' : 'P' ?>) - <?= htmlspecialchars($p['telp']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-group">
      <label for="waktu_transaksi">Tanggal Transaksi:</label>
      <input type="date" class="form-control" id="waktu_transaksi" name="waktu_transaksi" value="<?= date('Y-m-d') ?>" required>
    </div>

    <div class="form-group">
      <label for="keterangan">Keterangan / Catatan Transaksi:</label>
      <textarea class="form-control" id="keterangan" name="keterangan" rows="3" placeholder="Contoh: Pembelian sembako rutin..."></textarea>
    </div>

    <div style="margin-top: 20px;">
      <button type="submit" class="btn btn-submit">Simpan & Lanjut ke Detail</button>
      <a href="<?= url('transaksi') ?>" class="btn btn-secondary">Batal</a>
    </div>
  </form>
</div>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>
