<?php
// Form tambah transaksi. Variabel dari router: $conn

$pelanggan = $conn->query("SELECT id, nama FROM pelanggan ORDER BY nama")->fetchAll(PDO::FETCH_KEY_PAIR);
$barang    = $conn->query("SELECT id, kode_barang, nama_barang, harga, stok FROM barang WHERE stok > 0 ORDER BY nama_barang")->fetchAll();
$title     = "Tambah Transaksi";

?>
<?php require_once __DIR__ . "/../partials/header.auth.php" ?>

<h1>Tambah Transaksi</h1>
<?= render_flash() ?>

<form class="form" method="POST" action="<?= e(url('/api/transaksi/add')) ?>" style="max-width: 640px">
  <?= csrf_field() ?>

  <label for="pelanggan_id">Pelanggan</label>
  <select id="pelanggan_id" name="pelanggan_id" required>
    <option value="">- pilih pelanggan -</option>
    <?php foreach ($pelanggan as $pid => $pnama): ?>
      <option value="<?= e($pid) ?>"><?= e($pid) ?> - <?= e($pnama) ?></option>
    <?php endforeach ?>
  </select>

  <label for="waktu_transaksi">Tanggal</label>
  <input type="date" id="waktu_transaksi" name="waktu_transaksi" value="<?= date('Y-m-d') ?>" required>

  <label for="keterangan">Keterangan</label>
  <input type="text" id="keterangan" name="keterangan">

  <label>Barang (maksimal 5 jenis, baris kosong diabaikan)</label>
  <table>
    <tr><th>Barang</th><th style="width: 100px">Qty</th></tr>
    <?php for ($i = 0; $i < 5; $i++): ?>
      <tr>
        <td>
          <select name="barang_id[]">
            <option value="">- pilih barang -</option>
            <?php foreach ($barang as $b): ?>
              <option value="<?= (int) $b['id'] ?>"><?= e($b['kode_barang']) ?> - <?= e($b['nama_barang']) ?> (<?= e(rupiah($b['harga'])) ?>, stok <?= (int) $b['stok'] ?>)</option>
            <?php endforeach ?>
          </select>
        </td>
        <td><input type="number" name="qty[]" min="1" placeholder="Qty"></td>
      </tr>
    <?php endfor ?>
  </table>

  <label for="metode">Metode pembayaran</label>
  <select id="metode" name="metode" required>
    <option value="TUNAI">Tunai</option>
    <option value="TRANSFER">Transfer</option>
    <option value="EDC">EDC</option>
  </select>

  <div class="aksi-form">
    <button type="submit" class="btn">Simpan</button>
    <a class="btn btn-abu" href="<?= e(url('/transaksi')) ?>">Batal</a>
  </div>
</form>

<?php require_once __DIR__ . "/../partials/footer.auth.php" ?>
