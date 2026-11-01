<?php

require_once 'koneksi.php';

$supplier = $DBH->prepare("SELECT * FROM supplier ORDER BY id");
$supplier->execute();
$data = $supplier->fetchAll();

?>
<table class="data">
  <thead>
    <tr>
      <th>No</th>
      <th>Nama</th>
      <th>Telp</th>
      <th>Alamat</th>
      <th>Tindakan</th>
    </tr>
  </thead>
  <tbody>
  <?php if (count($data) === 0): ?>
    <tr><td colspan="5">Belum ada data.</td></tr>
  <?php endif; ?>

  <?php foreach ($data as $i => $row): ?>
    <tr>
      <td><?= $i + 1 ?></td>
      <td><?= htmlspecialchars($row['nama']) ?></td>
      <td><?= htmlspecialchars($row['telp']) ?></td>
      <td><?= htmlspecialchars($row['alamat']) ?></td>
      <td class="aksi">
        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-oranye">Edit</a>
        <a href="hapus.php?id=<?= $row['id'] ?>" class="btn btn-merah"
           onclick="return confirm('Anda yakin akan menghapus supplier ini?')">Hapus</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
