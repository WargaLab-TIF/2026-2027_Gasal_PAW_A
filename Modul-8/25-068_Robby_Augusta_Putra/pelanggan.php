<?php
include 'cek_login.php';
if ($_SESSION['level'] != 1) { 
    header('Location: index.php');
    exit;
}
include 'koneksi.php';

$notif = [
  'tersimpan'   => ['sukses', 'Data pelanggan berhasil ditambahkan.'],
  'diupdate'    => ['sukses', 'Data pelanggan berhasil diupdate.'],
  'dihapus'     => ['sukses', 'Data pelanggan berhasil dihapus.'],
  'gagal_hapus' => ['gagal',  'Pelanggan tidak bisa dihapus karena masih dipakai di tabel transaksi.'],
];

$pelanggan = $db->prepare("SELECT * FROM pelanggan ORDER BY id");
$pelanggan->execute();
$data = $pelanggan->fetchAll();

include 'header.php';
?>

<?php if (isset($_GET['pesan'], $notif[$_GET['pesan']])): ?>
  <div class="pesan <?= $notif[$_GET['pesan']][0] ?>"><?= $notif[$_GET['pesan']][1] ?></div>
<?php endif; ?>

<div class="top">
  <h2>Data Pelanggan</h2>
  <a href="tambah_pelanggan.php" class="btn btn-hijau">Tambah Data</a>
</div>
<hr>
<table class="data">
  <thead>
    <tr>
      <th>No</th>
      <th>ID</th>
      <th>Nama</th>
      <th>Jenis Kelamin</th>
      <th>Telp</th>
      <th>Alamat</th>
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
      <td><?= htmlspecialchars($row['id']) ?></td>
      <td><?= htmlspecialchars($row['nama']) ?></td>
      <td><?= $row['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
      <td><?= htmlspecialchars($row['telp']) ?></td>
      <td><?= htmlspecialchars($row['alamat']) ?></td>
      <td class="aksi">
        <a href="edit_pelanggan.php?id=<?= urlencode($row['id']) ?>" class="btn oren">Edit</a>
        <a href="hapus_pelanggan.php?id=<?= urlencode($row['id']) ?>" class="btn btn-hps"
           onclick="return confirm('Anda yakin akan menghapus pelanggan ini?')">Hapus</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php include 'footer.php'; ?>
