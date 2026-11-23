<?php
include 'cek_login.php';
if ($_SESSION['level'] != 1) { 
    header('Location: index.php');
    exit;
}
include 'koneksi.php';

$notif = [
  'tersimpan'   => ['sukses', 'Data barang berhasil ditambahkan.'],
  'diupdate'    => ['sukses', 'Data barang berhasil diupdate.'],
  'dihapus'     => ['sukses', 'Data barang berhasil dihapus.'],
  'gagal_hapus' => ['gagal',  'Barang tidak bisa dihapus karena masih dipakai di tabel transaksi detail.'],
];

$barang = $db->prepare("SELECT b.*, s.nama AS nama_supplier
                        FROM barang b
                        JOIN supplier s ON s.id = b.supplier_id
                        ORDER BY b.id");
$barang->execute();
$data = $barang->fetchAll();

include 'header.php';
?>

<?php if (isset($_GET['pesan'], $notif[$_GET['pesan']])): ?>
  <div class="pesan <?= $notif[$_GET['pesan']][0] ?>"><?= $notif[$_GET['pesan']][1] ?></div>
<?php endif; ?>

<div class="top">
  <h2>Data Barang</h2>
  <a href="tambah_barang.php" class="btn btn-hijau">Tambah Data</a>
</div>
<hr>
<table class="data">
  <thead>
    <tr>
      <th>No</th>
      <th>Kode</th>
      <th>Nama Barang</th>
      <th>Harga</th>
      <th>Stok</th>
      <th>Supplier</th>
      <th>Tindakan</th>
    </tr>
  </thead>
  <tbody>
  <?php if (count($data) === 0): ?>
    <tr><td colspan="7">Belum ada data.</td></tr>
  <?php endif; ?>
  <?php foreach ($data as $i => $row): ?>
    <tr>
      <td><?= $i + 1 ?></td>
      <td><?= htmlspecialchars($row['kode_barang']) ?></td>
      <td><?= htmlspecialchars($row['nama_barang']) ?></td>
      <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
      <td><?= (int) $row['stok'] ?></td>
      <td><?= htmlspecialchars($row['nama_supplier']) ?></td>
      <td class="aksi">
        <a href="edit_barang.php?id=<?= $row['id'] ?>" class="btn oren">Edit</a>
        <a href="hapus_barang.php?id=<?= $row['id'] ?>" class="btn btn-hps"
           onclick="return confirm('Anda yakin akan menghapus barang ini?')">Hapus</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php include 'footer.php'; ?>
