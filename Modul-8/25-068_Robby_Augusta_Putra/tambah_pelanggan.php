<?php
include 'cek_login.php';
if ($_SESSION['level'] != 1) { 
    header('Location: index.php');
    exit;
}
include 'koneksi.php';

$id = $nama = $jk = $telp = $alamat = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id     = trim($_POST['id']);
  $nama   = trim($_POST['nama']);
  $jk     = $_POST['jenis_kelamin'] ?? '';
  $telp   = trim($_POST['telp']);
  $alamat = trim($_POST['alamat']);

  if ($id === '' || $nama === '' || $jk === '' || $telp === '' || $alamat === '') {
    $error = 'Semua kolom wajib diisi.';
  } elseif (!in_array($jk, ['L', 'P'], true)) {
    $error = 'Jenis kelamin tidak valid.';
  } else {
    $cek = $db->prepare("SELECT COUNT(*) FROM pelanggan WHERE id = ?");
    $cek->execute([$id]);
    if ($cek->fetchColumn() > 0) {
      $error = 'ID pelanggan sudah dipakai.';
    } else {
      $stmt = $db->prepare("INSERT INTO pelanggan (id, nama, jenis_kelamin, telp, alamat) VALUES (?, ?, ?, ?, ?)");
      $stmt->execute([$id, $nama, $jk, $telp, $alamat]);
      header('Location: pelanggan.php?pesan=tersimpan');
      exit;
    }
  }
}

include 'header.php';
?>
<h2>Tambah Data Pelanggan Baru</h2>
<hr>

<?php if (isset($error)): ?><div class="pesan gagal"><?= htmlspecialchars($error) ?></div><?php endif; ?>

<form method="post">
  <div class="baris">
    <label for="id">ID</label>
    <input type="text" id="id" name="id" class="sedang" placeholder="Contoh: P011" maxlength="20" required
           value="<?= htmlspecialchars($id) ?>">
  </div>
  <div class="baris">
    <label for="nama">Nama</label>
    <input type="text" id="nama" name="nama" class="lebar" placeholder="Nama" maxlength="100" required
           value="<?= htmlspecialchars($nama) ?>">
  </div>
  <div class="baris">
    <label for="jenis_kelamin">Jenis Kelamin</label>
    <select id="jenis_kelamin" name="jenis_kelamin" style="width:40%" required>
      <option value="">-- Pilih --</option>
      <option value="L" <?= $jk === 'L' ? 'selected' : '' ?>>Laki-laki</option>
      <option value="P" <?= $jk === 'P' ? 'selected' : '' ?>>Perempuan</option>
    </select>
  </div>
  <div class="baris">
    <label for="telp">Telp</label>
    <input type="text" id="telp" name="telp" class="sedang" placeholder="telp" maxlength="12" required
           value="<?= htmlspecialchars($telp) ?>">
  </div>
  <div class="baris">
    <label for="alamat">Alamat</label>
    <input type="text" id="alamat" name="alamat" class="sedang" placeholder="alamat" required
           value="<?= htmlspecialchars($alamat) ?>">
  </div>
  <div class="tombol">
    <button type="submit" class="btn btn-hijau">Simpan</button>
    <a href="pelanggan.php" class="btn btn-merah">Batal</a>
  </div>
</form>
<?php include 'footer.php'; ?>
