<?php
$page_title = "Data User - Sistem Penjualan";
require_once __DIR__ . "/../partials/header.php";

$users = $conn->query("SELECT * FROM user ORDER BY id_user ASC")->fetchAll();
?>

<h2>Data Master User / Pengguna</h2>
<p style="margin-top: 0; color: #666; font-size: 13px;">Kelola akun dan level hak akses pengguna sistem (Owner & Kasir)</p>

<div style="margin-bottom: 15px;">
  <a href="<?= url('user-add') ?>" class="btn btn-primary">+ Tambah User</a>
</div>

<table class="data-table">
  <thead>
    <tr>
      <th style="width: 50px; text-align: center;">No</th>
      <th style="width: 120px;">Username</th>
      <th>Nama Lengkap</th>
      <th style="width: 140px; text-align: center;">Level Hak Akses</th>
      <th style="width: 130px;">No. HP</th>
      <th>Alamat</th>
      <th style="width: 130px; text-align: center;">Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($users)): ?>
      <?php foreach ($users as $i => $row): ?>
        <tr>
          <td style="text-align: center;"><?= $i + 1 ?></td>
          <td><b><?= htmlspecialchars($row['username']) ?></b></td>
          <td>
            <?= htmlspecialchars($row['nama']) ?>
            <?php if ($row['id_user'] == $_SESSION['user_id']): ?>
              <span style="font-size: 11px; color: #198754; font-weight: bold;">(Sedang Login)</span>
            <?php endif; ?>
          </td>
          <td style="text-align: center;">
            <b><?= $row['level'] == 1 ? 'Owner (Level 1)' : 'Kasir (Level 2)' ?></b>
          </td>
          <td><?= htmlspecialchars($row['hp']) ?></td>
          <td><?= htmlspecialchars($row['alamat']) ?></td>
          <td style="text-align: center;">
            <a href="<?= url('user-edit', ['id' => $row['id_user']]) ?>" class="btn btn-warning btn-sm">Edit</a>
            <?php if ($row['id_user'] != $_SESSION['user_id']): ?>
              <a href="<?= url('user-delete', ['id' => $row['id_user']]) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus user <?= htmlspecialchars(addslashes($row['username'])) ?>?');">Hapus</a>
            <?php else: ?>
              <span style="font-size: 12px; color: #999;">-</span>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr>
        <td colspan="7" style="text-align: center; color: #888;">Belum ada data user.</td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>
