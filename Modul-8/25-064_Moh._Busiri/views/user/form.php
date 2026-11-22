<?php
$isEdit = isset($item);
$page_title = ($isEdit ? "Edit" : "Tambah") . " Data User - Sistem Penjualan";
require_once __DIR__ . "/../partials/header.php";
?>

<h2><?= $isEdit ? "Edit Data User" : "Tambah User Baru" ?></h2>

<div class="form-container" style="max-width: 600px;">
  <form method="POST" action="<?= url($isEdit ? 'user-update' : 'user-save') ?>">
    <?php if ($isEdit): ?>
      <input type="hidden" name="id" value="<?= $item['id_user'] ?>">
    <?php endif; ?>

    <div class="form-group">
      <label for="username">Username:</label>
      <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($item['username'] ?? '') ?>" placeholder="Contoh: kasir1" required>
    </div>

    <div class="form-group">
      <label for="password">Password: <?= $isEdit ? '<small style="color: #666; font-weight: normal;">(Kosongkan jika tidak ingin diubah)</small>' : '' ?></label>
      <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" <?= $isEdit ? '' : 'required' ?>>
    </div>

    <div class="form-group">
      <label for="nama">Nama Lengkap:</label>
      <input type="text" class="form-control" id="nama" name="nama" value="<?= htmlspecialchars($item['nama'] ?? '') ?>" placeholder="Contoh: Budi Santoso" required>
    </div>

    <div class="form-group">
      <label for="level">Hak Akses (Level):</label>
      <select class="form-control" id="level" name="level" required>
        <option value="">-- Pilih Level Hak Akses --</option>
        <option value="1" <?= (isset($item['level']) && $item['level'] == 1) ? 'selected' : '' ?>>Level 1 - Owner (Akses Penuh + Data Master)</option>
        <option value="2" <?= (isset($item['level']) && $item['level'] == 2) ? 'selected' : '' ?>>Level 2 - Kasir (Transaksi & Laporan)</option>
      </select>
    </div>

    <div class="form-group">
      <label for="hp">No. HP / WhatsApp:</label>
      <input type="text" class="form-control" id="hp" name="hp" value="<?= htmlspecialchars($item['hp'] ?? '') ?>" placeholder="Contoh: 081234567890" required>
    </div>

    <div class="form-group">
      <label for="alamat">Alamat Lengkap:</label>
      <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat..." required><?= htmlspecialchars($item['alamat'] ?? '') ?></textarea>
    </div>

    <div style="margin-top: 20px;">
      <button type="submit" class="btn btn-submit">Simpan</button>
      <a href="<?= url('user') ?>" class="btn btn-secondary">Kembali</a>
    </div>
  </form>
</div>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>
