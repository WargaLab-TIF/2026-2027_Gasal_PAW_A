<?php
include 'cek_login.php';
if ($_SESSION['level'] != 1) { 
    header('Location: index.php');
    exit;
}
include 'koneksi.php';

$level_list = [1 => 'Admin', 2 => 'Kasir', 3 => 'Gudang'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id       = (int) $_POST['id'];
  $username = trim($_POST['username']);
  $password = $_POST['password']; 
  $nama     = trim($_POST['nama']);
  $alamat   = trim($_POST['alamat']);
  $hp       = trim($_POST['hp']);
  $level    = (int) ($_POST['level'] ?? 0);

  if ($username === '' || $nama === '' || $alamat === '' || $hp === '') {
    $error = 'Username, nama, alamat, dan HP wajib diisi.';
  } elseif (!isset($level_list[$level])) {
    $error = 'Level tidak valid.';
  } else {
    $cek = $db->prepare("SELECT COUNT(*) FROM user WHERE username = ? AND id_user <> ?");
    $cek->execute([$username, $id]);
    if ($cek->fetchColumn() > 0) {
      $error = 'Username sudah dipakai user lain.';
    } else {
      if ($password !== '') {
        $stmt = $db->prepare("UPDATE user SET username = ?, password = MD5(?), nama = ?, alamat = ?, hp = ?, level = ? WHERE id_user = ?");
        $stmt->execute([$username, $password, $nama, $alamat, $hp, $level, $id]);
      } else {
        $stmt = $db->prepare("UPDATE user SET username = ?, nama = ?, alamat = ?, hp = ?, level = ? WHERE id_user = ?");
        $stmt->execute([$username, $nama, $alamat, $hp, $level, $id]);
      }
      header('Location: user.php?pesan=diupdate');
      exit;
    }
  }

  $data = [
    'id_user' => $id, 'username' => $username, 'nama' => $nama,
    'alamat' => $alamat, 'hp' => $hp, 'level' => $level,
  ];
} else {
  $id = (int) ($_GET['id'] ?? 0);
  $stmt = $db->prepare("SELECT id_user, username, nama, alamat, hp, level FROM user WHERE id_user = ?");
  $stmt->execute([$id]);
  $data = $stmt->fetch();

  if (!$data) {
    header('Location: user.php');
    exit;
  }
}

include 'header.php';
?>
<h2>Edit Data User</h2>
<hr>

<?php if (isset($error)): ?><div class="pesan gagal"><?= htmlspecialchars($error) ?></div><?php endif; ?>

<form method="post">
  <input type="hidden" name="id" value="<?= (int) $data['id_user'] ?>">
  <div class="baris">
    <label for="username">Username</label>
    <input type="text" id="username" name="username" class="sedang" maxlength="30" required
           value="<?= htmlspecialchars($data['username']) ?>">
  </div>
  <div class="baris">
    <label for="password">Password</label>
    <input type="password" id="password" name="password" class="sedang" placeholder="Kosongkan jika tidak diubah">
  </div>
  <div class="baris">
    <label for="nama">Nama</label>
    <input type="text" id="nama" name="nama" class="lebar" maxlength="50" required
           value="<?= htmlspecialchars($data['nama']) ?>">
  </div>
  <div class="baris">
    <label for="alamat">Alamat</label>
    <input type="text" id="alamat" name="alamat" class="sedang" maxlength="150" required
           value="<?= htmlspecialchars($data['alamat']) ?>">
  </div>
  <div class="baris">
    <label for="hp">HP</label>
    <input type="text" id="hp" name="hp" class="sedang" maxlength="20" required
           value="<?= htmlspecialchars($data['hp']) ?>">
  </div>
  <div class="baris">
    <label for="level">Level</label>
    <select id="level" name="level" style="width:40%" required>
      <?php foreach ($level_list as $k => $v): ?>
        <option value="<?= $k ?>" <?= (int) $data['level'] === $k ? 'selected' : '' ?>><?= $v ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="tombol">
    <button type="submit" class="btn btn-hijau">Update</button>
    <a href="user.php" class="btn btn-merah">Batal</a>
  </div>
</form>
<?php include 'footer.php'; ?>
