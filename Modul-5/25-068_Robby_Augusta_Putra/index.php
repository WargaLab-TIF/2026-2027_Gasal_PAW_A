<?php

$notif = [
  'tersimpan'   => ['sukses', 'Data supplier berhasil ditambahkan.'],
  'diupdate'    => ['sukses', 'Data supplier berhasil diupdate.'],
  'dihapus'     => ['sukses', 'Data supplier berhasil dihapus.'],
  'gagal_hapus' => ['gagal',  'Supplier tidak bisa dihapus karena masih dipakai di tabel barang.'],
];

include 'header.php';
?>
<div class="top">
  <h3>Data Master Supplier</h3>
  <a href="tambah.php" class="btn btn-hijau">Tambah Data</a>
</div>
<hr>

<?php if (isset($_GET['pesan'], $notif[$_GET['pesan']])): ?>
  <div class="pesan <?= $notif[$_GET['pesan']][0] ?>"><?= $notif[$_GET['pesan']][1] ?></div>
<?php endif; ?>

<?php include 'table.inc'; ?>
<?php include 'footer.php'; ?>
