<?php
include 'cek_login.php';
if ($_SESSION['level'] != 1) { 
    header('Location: index.php');
    exit;
}
include 'koneksi.php';

$notif = [
  'tersimpan'   => ['sukses', 'Data supplier berhasil ditambahkan.'],
  'diupdate'    => ['sukses', 'Data supplier berhasil diupdate.'],
  'dihapus'     => ['sukses', 'Data supplier berhasil dihapus.'],
  'gagal_hapus' => ['gagal',  'Supplier tidak bisa dihapus karena masih dipakai di tabel barang.'],
];
?>

<?php if (isset($_GET['pesan'], $notif[$_GET['pesan']])): ?>
  <div class="pesan <?= $notif[$_GET['pesan']][0] ?>"><?= $notif[$_GET['pesan']][1] ?></div>
<?php endif; ?>
<?php

require_once 'koneksi.php';

$supplier = $db->prepare("SELECT * FROM supplier ORDER BY id");
$supplier->execute();
$data = $supplier->fetchAll();

include 'header.php';
?>

<div class="top">
  <h2>Data Supplier</h2>
  <a href="tambah_supplier.php" class="btn btn-hijau">Tambah Data</a>
</div>
<hr>
<table class="data">
  <thead>
    <tr>
      <th>No</th>
      <th>Nama</th>
      <th>Telp</th>
      <th>Alamat</th>
      <th>Tindakan</th>
    </tr>
  </thead>
  <tbody>
  <?php if (count($data) === 0): ?>
    <tr><td colspan="5">Belum ada data.</td></tr>
  <?php endif; ?>
  <?php foreach ($data as $i => $row): ?>
    <tr>
      <td><?= $i + 1 ?></td>
      <td><?= htmlspecialchars($row['nama']) ?></td>
      <td><?= htmlspecialchars($row['telp']) ?></td>
      <td><?= htmlspecialchars($row['alamat']) ?></td>
      <td class="aksi">
        <a href="edit_supplier.php?id=<?= $row['id'] ?>" class="btn oren">Edit</a>
        <a href="hapus_supplier.php?id=<?= $row['id'] ?>" class="btn btn-hps"
           onclick="return confirm('Anda yakin akan menghapus supplier ini?')">Hapus</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php include 'footer.php'; ?>