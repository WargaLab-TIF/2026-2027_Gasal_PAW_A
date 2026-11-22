<?php
// Daftar transaksi. Variabel dari router: $conn

$sql = <<<SQL
  SELECT t.id, t.waktu_transaksi, p.nama AS pelanggan, t.keterangan, t.total,
         GROUP_CONCAT(CONCAT(b.nama_barang, ' x', d.qty) SEPARATOR ', ') AS barang,
         (SELECT metode FROM pembayaran WHERE transaksi_id = t.id LIMIT 1) AS metode
  FROM transaksi t
  JOIN pelanggan p ON p.id = t.pelanggan_id
  LEFT JOIN transaksi_detail d ON d.transaksi_id = t.id
  LEFT JOIN barang b ON b.id = d.barang_id
  GROUP BY t.id, t.waktu_transaksi, p.nama, t.keterangan, t.total
  ORDER BY t.waktu_transaksi DESC, t.id DESC
  LIMIT 200
SQL;

$rows  = $conn->query($sql)->fetchAll();
$title = "Transaksi";

?>
<?php require_once __DIR__ . "/../partials/header.auth.php" ?>

<div class="page-head">
  <h1>Transaksi</h1>
  <a class="btn" href="<?= e(url('/transaksi/add')) ?>">Tambah transaksi</a>
</div>

<?= render_flash() ?>

<?php if (!$rows): ?>
  <p>Belum ada transaksi.</p>
<?php else: ?>
  <table>
    <tr><th>No</th><th>Tanggal</th><th>Pelanggan</th><th>Barang</th><th>Total</th><th>Bayar</th></tr>
    <?php $no = 1; foreach ($rows as $row): ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= e(date('d M Y', strtotime($row['waktu_transaksi']))) ?></td>
        <td><?= e($row['pelanggan']) ?></td>
        <td><?= e($row['barang']) ?></td>
        <td><?= e(rupiah($row['total'])) ?></td>
        <td><?= e($row['metode']) ?></td>
      </tr>
    <?php endforeach ?>
  </table>
<?php endif ?>

<?php require_once __DIR__ . "/../partials/footer.auth.php" ?>
