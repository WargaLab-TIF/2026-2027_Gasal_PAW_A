<?php
$page_title = "Data Pelanggan - Sistem Penjualan";
require_once __DIR__ . "/../partials/header.php";

$pelanggans = $conn->query("
  SELECT p.*, (SELECT COUNT(*) FROM transaksi t WHERE t.pelanggan_id = p.id) AS total_transaksi
  FROM pelanggan p
  ORDER BY p.id ASC
")->fetchAll();
?>

<h2>Data Master Pelanggan</h2>
<p style="margin-top: 0; color: #666; font-size: 13px;">Kelola basis data pelanggan dan riwayat transaksi mereka</p>

<div style="margin-bottom: 15px;">
  <a href="<?= url('pelanggan-add') ?>" class="btn btn-primary">+ Tambah Pelanggan</a>
</div>

<table class="data-table">
  <thead>
    <tr>
      <th style="width: 50px; text-align: center;">No</th>
      <th>Nama Pelanggan</th>
      <th style="width: 130px; text-align: center;">Jenis Kelamin</th>
      <th style="width: 140px;">No. Telepon</th>
      <th>Alamat</th>
      <th style="width: 150px; text-align: center;">Riwayat Belanja</th>
      <th style="width: 130px; text-align: center;">Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($pelanggans)): ?>
      <?php foreach ($pelanggans as $i => $row): ?>
        <tr>
          <td style="text-align: center;"><?= $i + 1 ?></td>
          <td><b><?= htmlspecialchars($row['nama']) ?></b></td>
          <td style="text-align: center;"><?= $row['jenis_kelamin'] === 'L' ? 'Laki-laki (L)' : 'Perempuan (P)' ?></td>
          <td><?= htmlspecialchars($row['telp']) ?></td>
          <td><?= htmlspecialchars($row['alamat']) ?></td>
          <td style="text-align: center;"><?= $row['total_transaksi'] ?> transaksi</td>
          <td style="text-align: center;">
            <a href="<?= url('pelanggan-edit', ['id' => $row['id']]) ?>" class="btn btn-warning btn-sm">Edit</a>
            <a href="<?= url('pelanggan-delete', ['id' => $row['id']]) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus pelanggan <?= htmlspecialchars(addslashes($row['nama'])) ?>?');">Hapus</a>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr>
        <td colspan="7" style="text-align: center; color: #888;">Belum ada data pelanggan.</td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>
