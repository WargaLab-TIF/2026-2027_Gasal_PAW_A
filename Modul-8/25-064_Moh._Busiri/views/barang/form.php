<?php
$isEdit = isset($item);
$page_title = ($isEdit ? "Edit" : "Tambah") . " Data Barang - Sistem Penjualan";
require_once __DIR__ . "/../partials/header.php";

$suppliers = $conn->query("SELECT * FROM supplier ORDER BY nama ASC")->fetchAll();
?>

<h2><?= $isEdit ? "Edit Data Barang" : "Tambah Data Barang Baru" ?></h2>

<div class="form-container" style="max-width: 600px;">
  <form method="POST" action="<?= url($isEdit ? 'barang-update' : 'barang-save') ?>">
    <?php if ($isEdit): ?>
      <input type="hidden" name="id" value="<?= $item['id'] ?>">
    <?php endif; ?>

    <div class="form-group">
      <label for="kode_barang">Kode Barang:</label>
      <input type="text" class="form-control" id="kode_barang" name="kode_barang" value="<?= htmlspecialchars($item['kode_barang'] ?? '') ?>" placeholder="Contoh: BRG011" required>
    </div>

    <div class="form-group">
      <label for="nama_barang">Nama Barang:</label>
      <input type="text" class="form-control" id="nama_barang" name="nama_barang" value="<?= htmlspecialchars($item['nama_barang'] ?? '') ?>" placeholder="Contoh: Sabun Mandi Cair 450ml" required>
    </div>

    <div style="display: flex; gap: 15px;">
      <div class="form-group" style="flex: 1;">
        <label for="harga">Harga Satuan (Rp):</label>
        <input type="number" class="form-control" id="harga" name="harga" min="100" value="<?= $item['harga'] ?? '' ?>" placeholder="Contoh: 15000" required>
      </div>

      <div class="form-group" style="flex: 1;">
        <label for="stok">Stok:</label>
        <input type="number" class="form-control" id="stok" name="stok" min="0" value="<?= $item['stok'] ?? 10 ?>" required>
      </div>
    </div>

    <div class="form-group">
      <label for="supplier_id">Supplier:</label>
      <select class="form-control" id="supplier_id" name="supplier_id" required>
        <option value="">-- Pilih Supplier --</option>
        <?php foreach ($suppliers as $s): ?>
          <option value="<?= $s['id'] ?>" <?= (isset($item['supplier_id']) && $item['supplier_id'] == $s['id']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($s['nama']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div style="margin-top: 20px;">
      <button type="submit" class="btn btn-submit">Simpan</button>
      <a href="<?= url('barang') ?>" class="btn btn-secondary">Kembali</a>
    </div>
  </form>
</div>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>
