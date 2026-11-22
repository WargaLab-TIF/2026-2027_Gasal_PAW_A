<?php
$page_title = "Data Barang - Sistem Penjualan";
require_once __DIR__ . "/../partials/header.php";

$query = "
  SELECT b.*, s.nama AS nama_supplier,
         (SELECT COUNT(*) FROM transaksi_detail td WHERE td.barang_id = b.id) AS jumlah_terpakai
  FROM barang b
  LEFT JOIN supplier s ON b.supplier_id = s.id
  ORDER BY b.id ASC
";
$stmt = $conn->query($query);
$barangs = $stmt->fetchAll();
?>

<h2>Data Master Barang</h2>
<p style="margin-top: 0; color: #666; font-size: 13px;">Kelola inventaris dan katalog produk toko</p>

<div style="margin-bottom: 15px;">
  <a href="<?= url('barang-add') ?>" class="btn btn-primary">+ Tambah Barang</a>
</div>

<table class="data-table">
  <thead>
    <tr>
      <th style="width: 50px; text-align: center;">No</th>
      <th style="width: 100px;">Kode</th>
      <th>Nama Barang</th>
      <th style="width: 130px;">Harga Satuan</th>
      <th style="width: 80px; text-align: center;">Stok</th>
      <th>Supplier</th>
      <th style="width: 140px;">Status Relasi</th>
      <th style="width: 130px; text-align: center;">Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($barangs)): ?>
      <?php foreach ($barangs as $i => $row): ?>
        <tr>
          <td style="text-align: center;"><?= $i + 1 ?></td>
          <td><b><?= htmlspecialchars($row['kode_barang']) ?></b></td>
          <td><?= htmlspecialchars($row['nama_barang']) ?></td>
          <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
          <td style="text-align: center;"><?= $row['stok'] ?></td>
          <td><?= htmlspecialchars($row['nama_supplier'] ?? '-') ?></td>
          <td>
            <?php if ($row['jumlah_terpakai'] > 0): ?>
              <span style="color: #0d6efd; font-size: 12px;">Dipakai (<?= $row['jumlah_terpakai'] ?> transaksi)</span>
            <?php else: ?>
              <span style="color: #888; font-size: 12px;">Belum terpakai</span>
            <?php endif; ?>
          </td>
          <td style="text-align: center;">
            <a href="<?= url('barang-edit', ['id' => $row['id']]) ?>" class="btn btn-warning btn-sm">Edit</a>
            <a href="<?= url('barang-delete', ['id' => $row['id']]) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus barang <?= htmlspecialchars(addslashes($row['nama_barang'])) ?>?');">Hapus</a>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr>
        <td colspan="8" style="text-align: center; color: #888;">Belum ada data barang.</td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>
