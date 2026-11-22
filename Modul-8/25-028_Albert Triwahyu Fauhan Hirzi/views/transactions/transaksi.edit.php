<?php
require_once dirname(__DIR__, 2) . '/conn.php';
require_once dirname(__DIR__, 2) . '/auth.php';
wajib_login();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: 0;
$getTransaction = mysqli_prepare($koneksi, 'SELECT id, waktu_transaksi, keterangan, pelanggan_id FROM transaksi WHERE id = ?');
mysqli_stmt_bind_param($getTransaction, 'i', $id);
mysqli_stmt_execute($getTransaction);
$transaksi = mysqli_fetch_assoc(mysqli_stmt_get_result($getTransaction));
mysqli_stmt_close($getTransaction);

if (!$transaksi) {
    http_response_code(404);
    exit('Transaksi tidak ditemukan.');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal = $_POST['waktu_transaksi'] ?? '';
    $pelangganId = trim($_POST['pelanggan_id'] ?? '');
    $keterangan = trim($_POST['keterangan'] ?? '');
    $date = DateTime::createFromFormat('!Y-m-d', $tanggal);

    if (!$date || $date->format('Y-m-d') !== $tanggal || $pelangganId === '') {
        $error = 'Tanggal dan pelanggan wajib diisi dengan benar.';
    } else {
        $update = mysqli_prepare(
            $koneksi,
            'UPDATE transaksi SET waktu_transaksi = ?, keterangan = ?, pelanggan_id = ? WHERE id = ?'
        );
        mysqli_stmt_bind_param($update, 'sssi', $tanggal, $keterangan, $pelangganId, $id);

        if (mysqli_stmt_execute($update)) {
            mysqli_stmt_close($update);
            header('Location: ' . page_url('transaksi-detail', ['id' => $id, 'msg' => 'updated']));
            exit;
        }

        $error = 'Transaksi gagal diperbarui. Pastikan pelanggan masih tersedia.';
        mysqli_stmt_close($update);
        $transaksi['waktu_transaksi'] = $tanggal;
        $transaksi['keterangan'] = $keterangan;
        $transaksi['pelanggan_id'] = $pelangganId;
    }
}

$customers = mysqli_query($koneksi, 'SELECT id, nama FROM pelanggan ORDER BY nama');
$judul = 'Edit Transaksi';
require dirname(__DIR__) . '/partials/header.php';
?>
<section class="page">
  <div class="panel">
    <div class="table-heading">
      <div>
        <div class="eyebrow">Transaksi #<?= (int) $id ?></div>
        <h1 class="container-title">Edit Transaksi</h1>
      </div>
    </div>

    <?php if ($error): ?>
      <div class="notice error"><?= e($error) ?></div>
    <?php endif; ?>

    <form class="master-form" method="post" action="<?= e(page_url('transaksi-edit', ['id' => $id])) ?>">
      <label>
        Tanggal Transaksi
        <input type="date" name="waktu_transaksi" value="<?= e($transaksi['waktu_transaksi']) ?>" required>
      </label>
      <label>
        Pelanggan
        <select name="pelanggan_id" required>
          <option value="">-- Pilih Pelanggan --</option>
          <?php while ($customer = mysqli_fetch_assoc($customers)): ?>
            <option value="<?= e($customer['id']) ?>" <?= $transaksi['pelanggan_id'] === $customer['id'] ? 'selected' : '' ?>>
              <?= e($customer['nama']) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </label>
      <label>
        Keterangan
        <textarea name="keterangan"><?= e($transaksi['keterangan'] ?? '') ?></textarea>
      </label>
      <div class="form-actions">
        <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
        <a class="btn btn-cancel" href="<?= e(page_url('transaksi-detail', ['id' => $id])) ?>">Batal</a>
      </div>
    </form>
  </div>
</section>
<?php require dirname(__DIR__) . '/partials/footer.php'; ?>
