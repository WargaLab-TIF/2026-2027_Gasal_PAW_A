<?php
require_once dirname(__DIR__, 2) . '/conn.php';
require_once dirname(__DIR__, 2) . '/auth.php';
wajib_login();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: 0;
$query = 'SELECT t.*, p.nama AS pelanggan
          FROM transaksi AS t
          LEFT JOIN pelanggan AS p ON p.id = t.pelanggan_id
          WHERE t.id = ?';
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$transaksi = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$transaksi) {
    http_response_code(404);
    exit('Transaksi tidak ditemukan.');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $barangId = filter_input(INPUT_POST, 'barang_id', FILTER_VALIDATE_INT) ?: 0;
    $qty = filter_input(INPUT_POST, 'qty', FILTER_VALIDATE_INT) ?: 0;
    $itemQuery = 'SELECT harga, stok FROM barang WHERE id = ?';
    $itemStmt = mysqli_prepare($koneksi, $itemQuery);
    mysqli_stmt_bind_param($itemStmt, 'i', $barangId);
    mysqli_stmt_execute($itemStmt);
    $barang = mysqli_fetch_assoc(mysqli_stmt_get_result($itemStmt));
    mysqli_stmt_close($itemStmt);

    if (!$barang || $qty < 1) {
        $error = 'Pilih barang dan jumlah yang valid.';
    } elseif ($qty > (int) $barang['stok']) {
        $error = 'Jumlah melebihi stok yang tersedia.';
    } else {
        mysqli_begin_transaction($koneksi);

        try {
            $harga = (int) $barang['harga'];
            $insertQuery = 'INSERT INTO transaksi_detail
                              (transaksi_id, barang_id, harga, qty)
                            VALUES (?, ?, ?, ?)';
            $add = mysqli_prepare($koneksi, $insertQuery);
            mysqli_stmt_bind_param($add, 'iiii', $id, $barangId, $harga, $qty);
            mysqli_stmt_execute($add);
            mysqli_stmt_close($add);

            $stockQuery = 'UPDATE barang
                           SET stok = stok - ?
                           WHERE id = ? AND stok >= ?';
            $stock = mysqli_prepare($koneksi, $stockQuery);
            mysqli_stmt_bind_param($stock, 'iii', $qty, $barangId, $qty);
            mysqli_stmt_execute($stock);

            if (mysqli_stmt_affected_rows($stock) !== 1) {
                throw new RuntimeException('Stok tidak mencukupi.');
            }
            mysqli_stmt_close($stock);

            $totalQuery = 'UPDATE transaksi
                           SET total = (
                             SELECT COALESCE(SUM(harga * qty), 0)
                             FROM transaksi_detail
                             WHERE transaksi_id = ?
                           )
                           WHERE id = ?';
            $total = mysqli_prepare($koneksi, $totalQuery);
            mysqli_stmt_bind_param($total, 'ii', $id, $id);
            mysqli_stmt_execute($total);
            mysqli_stmt_close($total);

            mysqli_commit($koneksi);
            header('Location: ' . page_url('transaksi-detail', ['id' => $id, 'msg' => 'added']));
            exit;
        } catch (Throwable $e) {
            mysqli_rollback($koneksi);
            $error = 'Barang gagal ditambahkan ke transaksi.';
        }
    }
}

$detailsQuery = 'SELECT d.*, b.nama_barang
                 FROM transaksi_detail AS d
                 JOIN barang AS b ON b.id = d.barang_id
                 WHERE d.transaksi_id = ?
                 ORDER BY d.id_detail';
$detailsStmt = mysqli_prepare($koneksi, $detailsQuery);
mysqli_stmt_bind_param($detailsStmt, 'i', $id);
mysqli_stmt_execute($detailsStmt);
$details = mysqli_stmt_get_result($detailsStmt);

$products = mysqli_query(
    $koneksi,
    'SELECT id, kode_barang, nama_barang, harga, stok
     FROM barang
     WHERE stok > 0
     ORDER BY nama_barang'
);
$judul = 'Detail Transaksi';
require dirname(__DIR__) . '/partials/header.php';
?>
<section class="page">
  <div class="panel">
    <div class="table-heading">
      <div>
        <div class="eyebrow">Nota #<?= (int) $id ?></div>
        <h1 class="container-title">Detail Transaksi</h1>
      </div>
      <a href="<?= e(page_url('transaksi')) ?>">Kembali</a>
    </div>

    <p>
      <strong>Pelanggan:</strong> <?= e($transaksi['pelanggan'] ?? '-') ?>
      &nbsp;
      <strong>Tanggal:</strong> <?= e($transaksi['waktu_transaksi']) ?>
    </p>

    <?php if (isset($_GET['msg'])): ?>
      <div class="notice">Barang berhasil dimasukkan ke transaksi.</div>
    <?php endif; ?>
    <?php if ($error): ?>
      <div class="notice error"><?= e($error) ?></div>
    <?php endif; ?>

    <form class="inline-form" method="post">
      <label>
        Barang
        <select name="barang_id" required>
          <option value="">-- Pilih Barang --</option>
          <?php while ($product = mysqli_fetch_assoc($products)): ?>
            <option value="<?= (int) $product['id'] ?>">
              <?= e($product['kode_barang'] . ' - ' . $product['nama_barang']) ?>
              · Rp <?= number_format((int) $product['harga'], 0, ',', '.') ?>
              · stok <?= (int) $product['stok'] ?>
            </option>
          <?php endwhile; ?>
        </select>
      </label>
      <label>
        Qty
        <input type="number" name="qty" min="1" value="1" required>
      </label>
      <button class="btn btn-primary" type="submit">Tambah ke Nota</button>
    </form>

    <div class="table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>No</th>
            <th>Barang</th>
            <th>Harga</th>
            <th>Qty</th>
            <th>Subtotal</th>
          </tr>
        </thead>
        <tbody>
          <?php $number = 1; ?>
          <?php $grandTotal = 0; ?>
          <?php while ($detail = mysqli_fetch_assoc($details)): ?>
            <?php $subtotal = (int) $detail['harga'] * (int) $detail['qty']; ?>
            <?php $grandTotal += $subtotal; ?>
            <tr>
              <td><?= $number++ ?></td>
              <td><?= e($detail['nama_barang']) ?></td>
              <td>Rp <?= number_format((int) $detail['harga'], 0, ',', '.') ?></td>
              <td><?= (int) $detail['qty'] ?></td>
              <td>Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
            </tr>
          <?php endwhile; ?>
          <?php if (mysqli_num_rows($details) === 0): ?>
            <tr>
              <td colspan="5" class="empty-state">Belum ada barang pada transaksi ini.</td>
            </tr>
          <?php endif; ?>
        </tbody>
        <tfoot>
          <tr>
            <th colspan="4" class="text-right">Total Nota</th>
            <th>Rp <?= number_format($grandTotal, 0, ',', '.') ?></th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</section>
<?php
mysqli_stmt_close($detailsStmt);
require dirname(__DIR__) . '/partials/footer.php';
?>
