<?php
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id     = (int) $_POST['id'];
  $nama   = trim($_POST['nama']);
  $telp   = trim($_POST['telp']);
  $alamat = trim($_POST['alamat']);

  $stmt = $DBH->prepare("UPDATE supplier SET nama = ?, telp = ?, alamat = ? WHERE id = ?");
  $stmt->execute([$nama, $telp, $alamat, $id]);
  header('Location: index.php?pesan=diupdate');
  exit;
}

$id = (int) ($_GET['id'] ?? 0);
$stmt = $DBH->prepare("SELECT * FROM supplier WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch();

if (!$data) {
  header('Location: index.php');
  exit;
}

include 'header.php';
?>
<h2>Edit Data Master Supplier</h2>
<hr>

<form method="post">
  <input type="hidden" name="id" value="<?= $data['id'] ?>">
  <div class="baris">
    <label for="nama">Nama</label>
    <input type="text" id="nama" name="nama" class="lebar" required
           value="<?= htmlspecialchars($data['nama']) ?>">
  </div>
  <div class="baris">
    <label for="telp">Telp</label>
    <input type="text" id="telp" name="telp" class="sedang" required
           value="<?= htmlspecialchars($data['telp']) ?>">
  </div>
  <div class="baris">
    <label for="alamat">Alamat</label>
    <input type="text" id="alamat" name="alamat" class="sedang" required
           value="<?= htmlspecialchars($data['alamat']) ?>">
  </div>
  <div class="tombol">
    <button type="submit" class="btn btn-hijau">Update</button>
    <a href="index.php" class="btn btn-merah">Batal</a>
  </div>
</form>
<?php include 'footer.php'; ?>
