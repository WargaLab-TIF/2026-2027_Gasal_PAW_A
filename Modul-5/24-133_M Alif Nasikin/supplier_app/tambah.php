<?php
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = mysqli_prepare($koneksi, 'INSERT INTO supplier (nama, telp, alamat) VALUES (?, ?, ?)');
    mysqli_stmt_bind_param($stmt, 'sss', $_POST['nama'], $_POST['telp'], $_POST['alamat']);
    mysqli_stmt_execute($stmt);
    header('Location: index.php');
    exit;
}

$judul = 'Tambah Data Master Supplier Baru';
include 'header.php';
?>
<h2 style="border-bottom: 1px solid #eee; padding-bottom: 15px;">Tambah Data Master Supplier Baru</h2>

<form method="post" class="form-horizontal" style="margin-top: 30px;">
  <div class="form-group">
    <label class="col-sm-4 control-label">Nama</label>
    <div class="col-sm-6">
      <input type="text" name="nama" class="form-control" placeholder="Nama" maxlength="100" required>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-4 control-label">Telp</label>
    <div class="col-sm-4">
      <input type="text" name="telp" class="form-control" placeholder="telp" maxlength="12" required>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-4 control-label">Alamat</label>
    <div class="col-sm-4">
      <input type="text" name="alamat" class="form-control" placeholder="alamat" required>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-4 col-sm-6">
      <button type="submit" class="btn btn-success">Simpan</button>
      <a href="index.php" class="btn btn-danger">Batal</a>
    </div>
  </div>
</form>
<?php include 'footer.php'; ?>
