<?php
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama   = trim($_POST['nama']);
  $telp   = trim($_POST['telp']);
  $alamat = trim($_POST['alamat']);

  if ($nama !== '' && $telp !== '' && $alamat !== '') {
    $stmt = $DBH->prepare("INSERT INTO supplier (nama, telp, alamat) VALUES (?, ?, ?)");
    $stmt->execute([$nama, $telp, $alamat]);
    header('Location: index.php?pesan=tersimpan');
    exit;
  }
  $error = 'Semua kolom wajib diisi.';
}

include 'header.php';
?>
<h2>Tambah Data Master Supplier Baru</h2>
<hr>

<?php if (isset($error)): ?><div class="pesan gagal"><?= $error ?></div><?php endif; ?>

<form method="post">
  <div class="baris">
    <label for="nama">Nama</label>
    <input type="text" id="nama" name="nama" class="lebar" placeholder="Nama" maxlength="100" required>
  </div>
  <div class="baris">
    <label for="telp">Telp</label>
    <input type="text" id="telp" name="telp" class="sedang" placeholder="telp" maxlength="12" required>
  </div>
  <div class="baris">
    <label for="alamat">Alamat</label>
    <input type="text" id="alamat" name="alamat" class="sedang" placeholder="alamat" required>
  </div>
  <div class="tombol">
    <button type="submit" class="btn btn-hijau">Simpan</button>
    <a href="index.php" class="btn btn-merah">Batal</a>
  </div>
</form>
<?php include 'footer.php'; ?>
