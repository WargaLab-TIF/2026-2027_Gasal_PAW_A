<?php
require 'koneksi.php';
$judul = 'Data Master Supplier';
$hasil = mysqli_query($koneksi, 'SELECT * FROM supplier ORDER BY id');
include 'header.php';
?>
<div class="clearfix" style="border-bottom: 1px solid #eee; margin-bottom: 15px;">
  <a href="tambah.php" class="btn btn-success btn-sm pull-right">Tambah Data</a>
  <h3 style="margin-top: 5px;">Data Master Supplier</h3>
</div>

<table class="table table-bordered table-hover">
  <thead>
    <tr class="info">
      <th>No</th><th>Nama</th><th>Telp</th><th>Alamat</th><th>Tindakan</th>
    </tr>
  </thead>
  <tbody>
    <?php $no = 1; while ($row = mysqli_fetch_assoc($hasil)) { ?>
    <tr>
      <td><?= $no++ ?></td>
      <td><?= htmlspecialchars($row['nama']) ?></td>
      <td><?= htmlspecialchars($row['telp']) ?></td>
      <td><?= htmlspecialchars($row['alamat']) ?></td>
      <td>
        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-xs">Edit</a>
        <form action="hapus.php" method="post" style="display:inline"
              onsubmit="return confirm('Anda yakin akan menghapus supplier ini?');">
          <input type="hidden" name="id" value="<?= $row['id'] ?>">
          <button type="submit" class="btn btn-danger btn-xs">Hapus</button>
        </form>
      </td>
    </tr>
    <?php } ?>
  </tbody>
</table>
<?php include 'footer.php'; ?>
