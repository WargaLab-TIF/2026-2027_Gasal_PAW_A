<?php
// Daftar transaksi (MASTER). Klik "Detail" untuk membuka halaman master-detail.
require 'koneksi.php';
$judul = "Daftar Transaksi";

$sql = "SELECT t.id, t.waktu_transaksi, t.keterangan, t.total, p.nama AS pelanggan,
               COUNT(d.barang_id) AS jumlah_item
          FROM transaksi t
          INNER JOIN pelanggan p ON p.id = t.pelanggan_id
          LEFT JOIN transaksi_detail d ON d.transaksi_id = t.id
         GROUP BY t.id, t.waktu_transaksi, t.keterangan, t.total, p.nama
         ORDER BY t.id DESC";
$hasil = $koneksi->query($sql);

include 'header.php';
?>
<div class="card">
    <h1>Daftar Transaksi (Master)</h1>
    <table>
        <tr>
            <th>No. Transaksi</th><th>Tanggal</th><th>Pelanggan</th>
            <th>Keterangan</th><th class="angka">Item</th><th class="angka">Total</th><th>Aksi</th>
        </tr>
        <?php while ($r = $hasil->fetch_assoc()): ?>
        <tr>
            <td>#<?= e($r['id']) ?></td>
            <td><?= e($r['waktu_transaksi']) ?></td>
            <td><?= e($r['pelanggan']) ?></td>
            <td><?= e($r['keterangan']) ?></td>
            <td class="angka"><?= e($r['jumlah_item']) ?></td>
            <td class="angka"><?= rupiah($r['total']) ?></td>
            <td><a class="btn" href="transaksi_detail.php?id=<?= (int)$r['id'] ?>">Detail</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
<?php include 'footer.php'; ?>
