<?php
include 'cek_login.php';
if ($_SESSION['level'] != 1) { 
    header('Location: index.php');
    exit;
}
include 'koneksi.php';

$notif = [
  'tersimpan'   => ['sukses', 'Data user berhasil ditambahkan.'],
  'diupdate'    => ['sukses', 'Data user berhasil diupdate.'],
  'dihapus'     => ['sukses', 'Data user berhasil dihapus.'],
  'gagal_hapus' => ['gagal',  'User tidak bisa dihapus.'],
  'hapus_diri'  => ['gagal',  'Anda tidak bisa menghapus akun yang sedang dipakai login.'],
];

$level = [1 => 'Admin', 2 => 'Kasir', 3 => 'Gudang'];

$user = $db->prepare("SELECT id_user, username, nama, alamat, hp, level FROM user ORDER BY id_user");
$user->execute();
$data = $user->fetchAll();

include 'header.php';
?>

<?php if (isset($_GET['pesan'], $notif[$_GET['pesan']])): ?>
  <div class="pesan <?= $notif[$_GET['pesan']][0] ?>"><?= $notif[$_GET['pesan']][1] ?></div>
<?php endif; ?>

<div class="top">
  <h2>Data User</h2>
  <a href="tambah_user.php" class="btn btn-hijau">Tambah Data</a>
</div>
<hr>
<table class="data">
  <thead>
    <tr>
      <th>No</th>
      <th>Username</th>
      <th>Nama</th>
      <th>Alamat</th>
      <th>HP</th>
      <th>Level</th>
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
      <td><?= htmlspecialchars($row['username']) ?></td>
      <td><?= htmlspecialchars($row['nama']) ?></td>
      <td><?= htmlspecialchars($row['alamat']) ?></td>
      <td><?= htmlspecialchars($row['hp']) ?></td>
      <td><?= htmlspecialchars($level[$row['level']] ?? $row['level']) ?></td>
      <td class="aksi">
        <a href="edit_user.php?id=<?= $row['id_user'] ?>" class="btn oren">Edit</a>
        <a href="hapus_user.php?id=<?= $row['id_user'] ?>" class="btn btn-hps"
           onclick="return confirm('Anda yakin akan menghapus user ini?')">Hapus</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php include 'footer.php'; ?>
