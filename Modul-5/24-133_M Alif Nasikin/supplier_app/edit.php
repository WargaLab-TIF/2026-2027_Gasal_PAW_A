<?php
require 'koneksi.php';
$id = (int) ($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = mysqli_prepare($koneksi, 'UPDATE supplier SET nama = ?, telp = ?, alamat = ? WHERE id = ?');
    mysqli_stmt_bind_param($stmt, 'sssi', $_POST['nama'], $_POST['telp'], $_POST['alamat'], $id);
    mysqli_stmt_execute($stmt);
    header('Location: index.php');
    exit;
}

$stmt = mysqli_prepare($koneksi, 'SELECT * FROM supplier WHERE id = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if (!$row) {
    header('Location: index.php');
    exit;
}

$judul = 'Edit Data Master Supplier';
include 'header.php';
?>
<h2 style="border-bottom: 1px solid #eee; padding-bottom: 15px;">Edit Data Master Supplier</h2>

<form method="post" class="form-horizontal" style="margin-top: 30px;">
  <div class="form-group">
    <label class="col-sm-4 control-label">Nama</label>
    <div class="col-sm-6">
      <input type="text" name="nama" class="form-control" maxlength="100" required
             value="<?= htmlspecialchars($row['nama']) ?>">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-4 control-label">Telp</label>
    <div class="col-sm-4">
      <input type="text" name="telp" class="form-control" maxlength="12" required
             value="<?= htmlspecialchars($row['telp']) ?>">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-4 control-label">Alamat</label>
    <div class="col-sm-4">
      <input type="text" name="alamat" class="form-control" required
             value="<?= htmlspecialchars($row['alamat']) ?>">
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-4 col-sm-6">
      <button type="submit" class="btn btn-success">Update</button>
      <a href="index.php" class="btn btn-danger">Batal</a>
    </div>
  </div>
</form>
<?php include 'footer.php'; ?>
