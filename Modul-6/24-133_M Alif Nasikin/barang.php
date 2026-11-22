<?php
// Daftar barang + tombol hapus (proses hapus ada di barang_hapus.php)
require 'koneksi.php';
$judul = "Data Barang";

$hasil = $koneksi->query(
    "SELECT b.id, b.kode_barang, b.nama_barang, b.harga, b.stok, s.nama AS supplier,
            (SELECT COUNT(*) FROM transaksi_detail d WHERE d.barang_id = b.id) AS dipakai
       FROM barang b
       INNER JOIN supplier s ON s.id = b.supplier_id
      ORDER BY b.kode_barang"
);

include 'header.php';
?>
<div class="card">
    <h1>Data Barang</h1>
    <table>
        <tr>
            <th>Kode</th><th>Nama Barang</th><th class="angka">Harga</th>
            <th class="angka">Stok</th><th>Supplier</th><th class="angka">Dipakai di Transaksi</th><th>Aksi</th>
        </tr>
        <?php while ($r = $hasil->fetch_assoc()): ?>
        <tr>
            <td><?= e($r['kode_barang']) ?></td>
            <td><?= e($r['nama_barang']) ?></td>
            <td class="angka"><?= rupiah($r['harga']) ?></td>
            <td class="angka"><?= e($r['stok']) ?></td>
            <td><?= e($r['supplier']) ?></td>
            <td class="angka"><?= e($r['dipakai']) ?></td>
            <td>
                <form method="post" action="barang_hapus.php" style="display:inline"
                      onsubmit="return confirm('Yakin hapus barang ini?');">
                    <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                    <button type="submit" class="btn-merah">Hapus</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
<?php include 'footer.php'; ?>
