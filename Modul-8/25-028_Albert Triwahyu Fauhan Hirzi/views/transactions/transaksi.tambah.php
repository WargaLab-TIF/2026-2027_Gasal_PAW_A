<?php
require_once dirname(__DIR__, 2) . '/conn.php';
require_once dirname(__DIR__, 2) . '/auth.php';
wajib_login();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tgl = $_POST['waktu_transaksi'] ?? '';
    $pelanggan = trim($_POST['pelanggan_id'] ?? '');
    $keterangan = trim($_POST['keterangan'] ?? '');
    $date = DateTime::createFromFormat('!Y-m-d', $tgl);

    if (!$date || $date->format('Y-m-d') !== $tgl || $pelanggan === '') {
        $error = 'Tanggal dan pelanggan wajib diisi dengan benar.';
    } else {
        $query = 'INSERT INTO transaksi
                    (waktu_transaksi, keterangan, total, pelanggan_id)
                  VALUES (?, ?, 0, ?)';
        $stmt = mysqli_prepare($koneksi, $query);
        mysqli_stmt_bind_param($stmt, 'sss', $tgl, $keterangan, $pelanggan);

        if (mysqli_stmt_execute($stmt)) {
            header('Location: ' . page_url('transaksi-detail', ['id' => mysqli_insert_id($koneksi)]));
            exit;
        }

        $error = 'Transaksi tidak dapat dibuat. Pastikan pelanggan tersedia.';
        mysqli_stmt_close($stmt);
    }
}

$customers = mysqli_query($koneksi, 'SELECT id, nama FROM pelanggan ORDER BY nama');
$judul = 'Transaksi Baru';
require dirname(__DIR__) . '/partials/header.php';
?>
<section class="page">
  <div class="panel">
    <div class="table-heading">
      <div>
        <div class="eyebrow">Transaksi</div>
        <h1 class="container-title">Buat Transaksi</h1>
      </div>
    </div>

    <?php if ($error): ?>
      <div class="notice error"><?= e($error) ?></div>
    <?php endif; ?>

    <?php if (mysqli_num_rows($customers) === 0): ?>
      <p class="notice">Tambahkan data pelanggan terlebih dahulu sebelum membuat transaksi.</p>
    <?php else: ?>
      <form class="master-form" method="post">
        <label>
          Tanggal Transaksi
          <input
            type="date"
            name="waktu_transaksi"
            value="<?= e($_POST['waktu_transaksi'] ?? date('Y-m-d')) ?>"
            required
          >
        </label>
        <label>
          Pelanggan
          <select name="pelanggan_id" required>
            <option value="">-- Pilih Pelanggan --</option>
            <?php while ($customer = mysqli_fetch_assoc($customers)): ?>
              <option
                value="<?= e($customer['id']) ?>"
                <?= ($_POST['pelanggan_id'] ?? '') === $customer['id'] ? 'selected' : '' ?>
              ><?= e($customer['nama']) ?></option>
            <?php endwhile; ?>
          </select>
        </label>
        <label>
          Keterangan
          <textarea name="keterangan"><?= e($_POST['keterangan'] ?? '') ?></textarea>
        </label>
        <div class="form-actions">
          <button class="btn btn-primary" type="submit">Buat dan Isi Barang</button>
          <a class="btn btn-cancel" href="<?= e(page_url('transaksi')) ?>">Batal</a>
        </div>
      </form>
    <?php endif; ?>
  </div>
</section>
<?php require dirname(__DIR__) . '/partials/footer.php'; ?>
