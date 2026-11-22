<?php
// Tambah data MASTER transaksi baru (total awal 0), lalu lanjut ke halaman detail.
require 'koneksi.php';
$judul = "Transaksi Baru";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal    = $_POST['waktu_transaksi'] ?? '';
    $pelanggan  = $_POST['pelanggan_id'] ?? '';
    $keterangan = trim($_POST['keterangan'] ?? '');

    if ($tanggal === '' || $pelanggan === '') {
        redirect('transaksi_tambah.php', 'Tanggal dan pelanggan wajib diisi.', 'gagal');
    }

    $stmt = $koneksi->prepare(
        "INSERT INTO transaksi (waktu_transaksi, keterangan, total, pelanggan_id) VALUES (?, ?, 0, ?)"
    );
    $stmt->bind_param("sss", $tanggal, $keterangan, $pelanggan);
    $stmt->execute();
    $id_baru = $koneksi->insert_id;
    $stmt->close();

    redirect('transaksi_detail.php?id=' . $id_baru, 'Transaksi dibuat. Silakan tambahkan barang.');
}

$daftar_pelanggan = $koneksi->query("SELECT id, nama FROM pelanggan ORDER BY nama");

include 'header.php';
?>
<div class="card">
    <h1>Transaksi Baru</h1>
    <form method="post">
        <label for="waktu_transaksi">Tanggal Transaksi</label>
        <input type="date" id="waktu_transaksi" name="waktu_transaksi" value="<?= date('Y-m-d') ?>" required>

        <label for="pelanggan_id">Pelanggan</label>
        <select id="pelanggan_id" name="pelanggan_id" required>
            <option value="">-- pilih pelanggan --</option>
            <?php while ($p = $daftar_pelanggan->fetch_assoc()): ?>
                <option value="<?= e($p['id']) ?>"><?= e($p['id'] . ' - ' . $p['nama']) ?></option>
            <?php endwhile; ?>
        </select>

        <label for="keterangan">Keterangan</label>
        <textarea id="keterangan" name="keterangan" rows="3"></textarea>

        <p><button type="submit">Simpan &amp; Lanjut ke Detail</button>
           <a class="btn btn-abu" href="index.php">Batal</a></p>
    </form>
</div>
<?php include 'footer.php'; ?>
