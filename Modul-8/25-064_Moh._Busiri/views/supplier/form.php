<?php
$isEdit = isset($item);
$page_title = ($isEdit ? "Edit" : "Tambah") . " Data Supplier - Sistem Penjualan";
require_once __DIR__ . "/../partials/header.php";
?>

<h2><?= $isEdit ? "Edit Data Supplier" : "Tambah Data Supplier Baru" ?></h2>

<div class="form-container" style="max-width: 600px;">
  <form method="POST" action="<?= url($isEdit ? 'supplier-update' : 'supplier-save') ?>">
    <?php if ($isEdit): ?>
      <input type="hidden" name="id" value="<?= $item['id'] ?>">
    <?php endif; ?>

    <div class="form-group">
      <label for="nama">Nama Supplier:</label>
      <input type="text" class="form-control" id="nama" name="nama" value="<?= htmlspecialchars($item['nama'] ?? '') ?>" placeholder="Contoh: PT. Sumber Makmur" required>
    </div>

    <div class="form-group">
      <label for="telp">No. Telepon / HP:</label>
      <input type="text" class="form-control" id="telp" name="telp" value="<?= htmlspecialchars($item['telp'] ?? '') ?>" placeholder="Contoh: 081234567890" maxlength="12" required>
    </div>

    <div class="form-group">
      <label for="alamat">Alamat:</label>
      <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap..." required><?= htmlspecialchars($item['alamat'] ?? '') ?></textarea>
    </div>

    <div style="margin-top: 20px;">
      <button type="submit" class="btn btn-submit">Simpan</button>
      <a href="<?= url('supplier') ?>" class="btn btn-secondary">Kembali</a>
    </div>
  </form>
</div>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>
