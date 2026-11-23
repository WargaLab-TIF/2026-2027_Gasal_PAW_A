<?php
include 'cek_login.php';
if ($_SESSION['level'] != 1) { 
    header('Location: index.php');
    exit;
}
include 'koneksi.php';

$level_list = [1 => 'Admin', 2 => 'Kasir', 3 => 'Gudang'];

$username = $nama = $alamat = $hp = '';
$level = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username']);
  $password = $_POST['password'];
  $nama     = trim($_POST['nama']);
  $alamat   = trim($_POST['alamat']);
  $hp       = trim($_POST['hp']);
  $level    = (int) ($_POST['level'] ?? 0);

  if ($username === '' || $password === '' || $nama === '' || $alamat === '' || $hp === '') {
    $error = 'Semua kolom wajib diisi.';
  } elseif (!isset($level_list[$level])) {
    $error = 'Level tidak valid.';
  } else {
    $cek = $db->prepare("SELECT COUNT(*) FROM user WHERE username = ?");
    $cek->execute([$username]);
    if ($cek->fetchColumn() > 0) {
      $error = 'Username sudah dipakai.';
    } else {
      $stmt = $db->prepare("INSERT INTO user (username, password, nama, alamat, hp, level) VALUES (?, MD5(?), ?, ?, ?, ?)");
      $stmt->execute([$username, $password, $nama, $alamat, $hp, $level]);
      header('Location: user.php?pesan=tersimpan');
      exit;
    }
  }
}

include 'header.php';
?>
<h2>Tambah Data User Baru</h2>
<hr>

<?php if (isset($error)): ?><div class="pesan gagal"><?= htmlspecialchars($error) ?></div><?php endif; ?>

<form method="post">
  <div class="baris">
    <label for="username">Username</label>
    <input type="text" id="username" name="username" class="sedang" placeholder="Username" maxlength="30" required
           value="<?= htmlspecialchars($username) ?>">
  </div>
  <div class="baris">
    <label for="password">Password</label>
    <input type="password" id="password" name="password" class="sedang" placeholder="Password" required>
  </div>
  <div class="baris">
    <label for="nama">Nama</label>
    <input type="text" id="nama" name="nama" class="lebar" placeholder="Nama" maxlength="50" required
           value="<?= htmlspecialchars($nama) ?>">
  </div>
  <div class="baris">
    <label for="alamat">Alamat</label>
    <input type="text" id="alamat" name="alamat" class="sedang" placeholder="alamat" maxlength="150" required
           value="<?= htmlspecialchars($alamat) ?>">
  </div>
  <div class="baris">
    <label for="hp">HP</label>
    <input type="text" id="hp" name="hp" class="sedang" placeholder="No. HP" maxlength="20" required
           value="<?= htmlspecialchars($hp) ?>">
  </div>
  <div class="baris">
    <label for="level">Level</label>
    <select id="level" name="level" style="width:40%" required>
      <option value="">-- Pilih Level --</option>
      <?php foreach ($level_list as $k => $v): ?>
        <option value="<?= $k ?>" <?= $level === $k ? 'selected' : '' ?>><?= $v ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="tombol">
    <button type="submit" class="btn btn-hijau">Simpan</button>
    <a href="user.php" class="btn btn-merah">Batal</a>
  </div>
</form>
<?php include 'footer.php'; ?>
