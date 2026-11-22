<?php
include 'cek_login.php';
if ($_SESSION['level'] != 1) { 
    header('Location: index.php');
    exit;
}
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id     = trim($_POST['id']);
  $nama   = trim($_POST['nama']);
  $jk     = $_POST['jenis_kelamin'] ?? '';
  $telp   = trim($_POST['telp']);
  $alamat = trim($_POST['alamat']);

  if ($nama === '' || $jk === '' || $telp === '' || $alamat === '') {
    $error = 'Semua kolom wajib diisi.';
  } elseif (!in_array($jk, ['L', 'P'], true)) {
    $error = 'Jenis kelamin tidak valid.';
  } else {
    $stmt = $db->prepare("UPDATE pelanggan SET nama = ?, jenis_kelamin = ?, telp = ?, alamat = ? WHERE id = ?");
    $stmt->execute([$nama, $jk, $telp, $alamat, $id]);
    header('Location: pelanggan.php?pesan=diupdate');
    exit;
  }

  $data = [
    'id' => $id, 'nama' => $nama, 'jenis_kelamin' => $jk,
    'telp' => $telp, 'alamat' => $alamat,
  ];
} else {
  $id = $_GET['id'] ?? '';
  $stmt = $db->prepare("SELECT * FROM pelanggan WHERE id = ?");
  $stmt->execute([$id]);
  $data = $stmt->fetch();

  if (!$data) {
    header('Location: pelanggan.php');
    exit;
  }
}

include 'header.php';
?>
<h2>Edit Data Pelanggan</h2>
<hr>

<?php if (isset($error)): ?><div class="pesan gagal"><?= htmlspecialchars($error) ?></div><?php endif; ?>

<form method="post">
  <input type="hidden" name="id" value="<?= htmlspecialchars($data['id']) ?>">
  <div class="baris">
    <label>ID</label>
    <input type="text" class="sedang" value="<?= htmlspecialchars($data['id']) ?>" disabled>
  </div>
  <div class="baris">
    <label for="nama">Nama</label>
    <input type="text" id="nama" name="nama" class="lebar" maxlength="100" required
           value="<?= htmlspecialchars($data['nama']) ?>">
  </div>
  <div class="baris">
    <label for="jenis_kelamin">Jenis Kelamin</label>
    <select id="jenis_kelamin" name="jenis_kelamin" style="width:40%" required>
      <option value="L" <?= $data['jenis_kelamin'] === 'L' ? 'selected' : '' ?>>Laki-laki</option>
      <option value="P" <?= $data['jenis_kelamin'] === 'P' ? 'selected' : '' ?>>Perempuan</option>
    </select>
  </div>
  <div class="baris">
    <label for="telp">Telp</label>
    <input type="text" id="telp" name="telp" class="sedang" maxlength="12" required
           value="<?= htmlspecialchars($data['telp']) ?>">
  </div>
  <div class="baris">
    <label for="alamat">Alamat</label>
    <input type="text" id="alamat" name="alamat" class="sedang" required
           value="<?= htmlspecialchars($data['alamat']) ?>">
  </div>
  <div class="tombol">
    <button type="submit" class="btn btn-hijau">Update</button>
    <a href="pelanggan.php" class="btn btn-merah">Batal</a>
  </div>
</form>
<?php include 'footer.php'; ?>
