<?php
$page_title = "Data Supplier - Sistem Penjualan";
require_once __DIR__ . "/../partials/header.php";

$suppliers = $conn->query("
  SELECT s.*, (SELECT COUNT(*) FROM barang b WHERE b.supplier_id = s.id) AS total_barang
  FROM supplier s
  ORDER BY s.id ASC
")->fetchAll();
?>

<h2>Data Master Supplier</h2>
<p style="margin-top: 0; color: #666; font-size: 13px;">Kelola informasi pemasok dan distributor barang</p>

<div style="margin-bottom: 15px;">
  <a href="<?= url('supplier-add') ?>" class="btn btn-primary">+ Tambah Supplier</a>
</div>

<table class="data-table">
  <thead>
    <tr>
      <th style="width: 50px; text-align: center;">No</th>
      <th>Nama Supplier</th>
      <th style="width: 150px;">No. Telepon</th>
      <th>Alamat</th>
      <th style="width: 160px; text-align: center;">Pasokan Barang</th>
      <th style="width: 130px; text-align: center;">Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($suppliers)): ?>
      <?php foreach ($suppliers as $i => $row): ?>
        <tr>
          <td style="text-align: center;"><?= $i + 1 ?></td>
          <td><b><?= htmlspecialchars($row['nama']) ?></b></td>
          <td><?= htmlspecialchars($row['telp']) ?></td>
          <td><?= htmlspecialchars($row['alamat']) ?></td>
          <td style="text-align: center;"><?= $row['total_barang'] ?> jenis barang</td>
          <td style="text-align: center;">
            <a href="<?= url('supplier-edit', ['id' => $row['id']]) ?>" class="btn btn-warning btn-sm">Edit</a>
            <a href="<?= url('supplier-delete', ['id' => $row['id']]) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus supplier <?= htmlspecialchars(addslashes($row['nama'])) ?>?');">Hapus</a>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr>
        <td colspan="6" style="text-align: center; color: #888;">Belum ada data supplier.</td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>
