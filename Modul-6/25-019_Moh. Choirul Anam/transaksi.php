<?php
require_once 'koneksi.php';

// ---- Tambah data master transaksi ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal    = $_POST['waktu_transaksi'] ?? '';
    $pelanggan  = $_POST['pelanggan_id'] ?? '';
    $keterangan = trim($_POST['keterangan'] ?? '');

    if ($tanggal === '' || $pelanggan === '') {
        flash('error', 'Tanggal dan pelanggan wajib diisi.');
    } else {
        try {
            $stmt = $db->prepare("INSERT INTO transaksi
                (waktu_transaksi, keterangan, total, pelanggan_id)
                VALUES (?, ?, 0, ?)");
            $stmt->bind_param('sss', $tanggal, $keterangan, $pelanggan);
            $stmt->execute();
            flash('success', 'Transaksi baru dibuat. Silakan isi detailnya.');
            header('Location: transaksi_detail.php?id=' . $db->insert_id);
            exit;
        } catch (mysqli_sql_exception $e) {
            flash('error', 'Gagal menyimpan transaksi (data tidak valid).');
        }
    }
    header('Location: transaksi.php');
    exit;
}

// ---- Data untuk tampilan ----
$judul = 'Data Transaksi';
$transaksi = $db->query(
    "SELECT t.id, t.waktu_transaksi, p.nama AS pelanggan, t.keterangan, t.total,
            (SELECT COUNT(*) FROM transaksi_detail d
              WHERE d.transaksi_id = t.id) AS jml_item
     FROM transaksi t
     JOIN pelanggan p ON p.id = t.pelanggan_id
     ORDER BY t.id DESC");
$pelanggan = $db->query("SELECT id, nama FROM pelanggan ORDER BY nama");

require 'header.php';
?>
<h2>Data Transaksi (Master)</h2>
<table>
  <tr>
    <th>ID</th><th>Tanggal</th><th>Pelanggan</th><th>Keterangan</th>
    <th class="num">Total</th><th>Jml Item</th><th>Aksi</th>
  </tr>
  <?php while ($t = $transaksi->fetch_assoc()): ?>
  <tr>
    <td><?= (int)$t['id'] ?></td>
    <td><?= h($t['waktu_transaksi']) ?></td>
    <td><?= h($t['pelanggan']) ?></td>
    <td><?= h($t['keterangan']) ?></td>
    <td class="num"><?= rupiah($t['total']) ?></td>
    <td><?= (int)$t['jml_item'] ?></td>
    <td>
      <a class="btn" href="transaksi_detail.php?id=<?= (int)$t['id'] ?>">Detail</a>
    </td>
  </tr>
  <?php endwhile; ?>
</table>

<h3>Tambah Transaksi Baru</h3>
<form method="post" class="inline">
  <label>Tanggal
    <input type="date" name="waktu_transaksi" value="<?= date('Y-m-d') ?>" required>
  </label>
  <label>Pelanggan
    <select name="pelanggan_id" required>
      <option value="">-- Pilih pelanggan --</option>
      <?php while ($p = $pelanggan->fetch_assoc()): ?>
        <option value="<?= h($p['id']) ?>">
          <?= h($p['id'] . ' - ' . $p['nama']) ?>
        </option>
      <?php endwhile; ?>
    </select>
  </label>
  <label>Keterangan
    <input type="text" name="keterangan" size="30">
  </label>
  <button type="submit" class="btn">Simpan</button>
</form>
</div>
</body>
</html>
