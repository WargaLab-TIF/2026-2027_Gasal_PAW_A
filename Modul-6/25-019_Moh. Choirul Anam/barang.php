<?php
require_once 'koneksi.php';

$judul = 'Data Barang';
$sql = "SELECT b.id, b.kode_barang, b.nama_barang, b.harga, b.stok,
               s.nama AS supplier,
               (SELECT COUNT(*) FROM transaksi_detail d
                 WHERE d.barang_id = b.id) AS dipakai
        FROM barang b
        JOIN supplier s ON s.id = b.supplier_id
        ORDER BY b.id";
$barang = $db->query($sql);

require 'header.php';
?>
<h2>Data Barang</h2>
<table>
  <tr>
    <th>No</th><th>Kode</th><th>Nama Barang</th><th class="num">Harga</th>
    <th class="num">Stok</th><th>Supplier</th><th>Dipakai</th><th>Aksi</th>
  </tr>
  <?php $no = 1; while ($r = $barang->fetch_assoc()): ?>
  <tr>
    <td><?= $no++ ?></td>
    <td><?= h($r['kode_barang']) ?></td>
    <td><?= h($r['nama_barang']) ?></td>
    <td class="num"><?= rupiah($r['harga']) ?></td>
    <td class="num"><?= (int)$r['stok'] ?></td>
    <td><?= h($r['supplier']) ?></td>
    <td><?= (int)$r['dipakai'] ?> detail transaksi</td>
    <td>
      <form method="post" action="hapus_barang.php"
            onsubmit="return confirm('Yakin hapus barang ini?');">
        <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
        <button type="submit" class="btn danger">Hapus</button>
      </form>
    </td>
  </tr>
  <?php endwhile; ?>
</table>
</div>
</body>
</html>
