<?php
$isEdit = isset($item);
$page_title = ($isEdit ? "Edit" : "Tambah") . " Data Pelanggan - Sistem Penjualan";
require_once __DIR__ . "/../partials/header.php";
?>

<h2><?= $isEdit ? "Edit Data Pelanggan" : "Tambah Data Pelanggan Baru" ?></h2>

<div class="form-container" style="max-width: 600px;">
  <form method="POST" action="<?= url($isEdit ? 'pelanggan-update' : 'pelanggan-save') ?>">
    <?php if ($isEdit): ?>
      <input type="hidden" name="id" value="<?= $item['id'] ?>">
    <?php endif; ?>

    <div class="form-group">
      <label for="nama">Nama Pelanggan:</label>
      <input type="text" class="form-control" id="nama" name="nama" value="<?= htmlspecialchars($item['nama'] ?? '') ?>" placeholder="Contoh: Ahmad Fauzi" required>
    </div>

    <div class="form-group">
      <label>Jenis Kelamin:</label>
      <div style="margin-top: 6px;">
        <label style="display: inline-block; font-weight: normal; margin-right: 20px; cursor: pointer;">
          <input type="radio" name="jenis_kelamin" value="L" <?= (!isset($item['jenis_kelamin']) || $item['jenis_kelamin'] === 'L') ? 'checked' : '' ?>> Laki-laki (L)
        </label>
        <label style="display: inline-block; font-weight: normal; cursor: pointer;">
          <input type="radio" name="jenis_kelamin" value="P" <?= (isset($item['jenis_kelamin']) && $item['jenis_kelamin'] === 'P') ? 'checked' : '' ?>> Perempuan (P)
        </label>
      </div>
    </div>

    <div class="form-group">
      <label for="telp">No. Telepon / WhatsApp:</label>
      <input type="text" class="form-control" id="telp" name="telp" value="<?= htmlspecialchars($item['telp'] ?? '') ?>" placeholder="Contoh: 081234567890" maxlength="12" required>
    </div>

    <div class="form-group">
      <label for="alamat">Alamat Lengkap:</label>
      <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap pelanggan..." required><?= htmlspecialchars($item['alamat'] ?? '') ?></textarea>
    </div>

    <div style="margin-top: 20px;">
      <button type="submit" class="btn btn-submit">Simpan</button>
      <a href="<?= url('pelanggan') ?>" class="btn btn-secondary">Kembali</a>
    </div>
  </form>
</div>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>
