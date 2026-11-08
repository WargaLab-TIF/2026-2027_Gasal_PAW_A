<?php
// =====================================================================
// Halaman MASTER-DETAIL transaksi
//   Atas  : data master (tabel transaksi)
//   Bawah : data detail (tabel transaksi_detail) + form input detail
//
// Tugas Praktikum 2 -> dropdown barang tidak menampilkan barang yang sudah
//                      ada di detail transaksi ini (+ validasi di server).
// Tugas Praktikum 4 -> setiap detail disimpan, kolom total pada tabel
//                      transaksi dihitung ulang otomatis.
// =====================================================================
require 'koneksi.php';

$id = (int)($_GET['id'] ?? 0);
$url_halaman = 'transaksi_detail.php?id=' . $id;

// ---------- Ambil data MASTER ----------
$stmt = $koneksi->prepare(
    "SELECT t.id, t.waktu_transaksi, t.keterangan, t.total, p.id AS pelanggan_id, p.nama AS pelanggan
       FROM transaksi t
       INNER JOIN pelanggan p ON p.id = t.pelanggan_id
      WHERE t.id = ?"
);
$stmt->bind_param("i", $id);
$stmt->execute();
$master = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$master) {
    redirect('index.php', 'Transaksi tidak ditemukan.', 'gagal');
}

// ---------- Proses form (POST) ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'] ?? '';

    // ===== Tambah detail =====
    if ($aksi === 'tambah') {
        $barang_id = (int)($_POST['barang_id'] ?? 0);
        $qty       = (int)($_POST['qty'] ?? 0);

        if ($barang_id <= 0 || $qty < 1) {
            redirect($url_halaman, 'Pilih barang dan isi qty minimal 1.', 'gagal');
        }

        $koneksi->begin_transaction();
        try {
            // Ambil harga barang dari database (bukan dari form, agar tidak bisa dimanipulasi)
            $s = $koneksi->prepare("SELECT harga FROM barang WHERE id = ?");
            $s->bind_param("i", $barang_id);
            $s->execute();
            $barang = $s->get_result()->fetch_assoc();
            $s->close();
            if (!$barang) {
                throw new Exception('Barang tidak ditemukan.');
            }

            // Cek di server: barang yang sama tidak boleh dua kali dalam satu transaksi
            $s = $koneksi->prepare(
                "SELECT COUNT(*) AS n FROM transaksi_detail WHERE transaksi_id = ? AND barang_id = ?"
            );
            $s->bind_param("ii", $id, $barang_id);
            $s->execute();
            $sudah = $s->get_result()->fetch_assoc()['n'];
            $s->close();
            if ($sudah > 0) {
                throw new Exception('Barang tersebut sudah ada di transaksi ini.');
            }

            // Simpan detail
            $s = $koneksi->prepare(
                "INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) VALUES (?, ?, ?, ?)"
            );
            $s->bind_param("iiii", $id, $barang_id, $barang['harga'], $qty);
            $s->execute();
            $s->close();

            // Tugas 4: hitung ulang total transaksi
            hitung_ulang_total($koneksi, $id);

            $koneksi->commit();
        } catch (Exception $ex) {
            $koneksi->rollback();
            redirect($url_halaman, $ex->getMessage(), 'gagal');
        }
        redirect($url_halaman, 'Detail transaksi disimpan dan total diperbarui.');
    }

    // ===== Hapus detail =====
    if ($aksi === 'hapus') {
        $barang_id = (int)($_POST['barang_id'] ?? 0);

        $koneksi->begin_transaction();
        try {
            $s = $koneksi->prepare("DELETE FROM transaksi_detail WHERE transaksi_id = ? AND barang_id = ?");
            $s->bind_param("ii", $id, $barang_id);
            $s->execute();
            $s->close();

            hitung_ulang_total($koneksi, $id);   // total ikut diperbarui
            $koneksi->commit();
        } catch (Exception $ex) {
            $koneksi->rollback();
            redirect($url_halaman, 'Gagal menghapus detail.', 'gagal');
        }
        redirect($url_halaman, 'Detail dihapus dan total diperbarui.');
    }
}

// ---------- Ambil data DETAIL ----------
$stmt = $koneksi->prepare(
    "SELECT d.barang_id, b.kode_barang, b.nama_barang, d.harga, d.qty, (d.harga * d.qty) AS subtotal
       FROM transaksi_detail d
       INNER JOIN barang b ON b.id = d.barang_id
      WHERE d.transaksi_id = ?
      ORDER BY b.nama_barang"
);
$stmt->bind_param("i", $id);
$stmt->execute();
$detail = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// ---------- Pilihan dropdown: hanya barang yang BELUM ada di detail transaksi ini ----------
$stmt = $koneksi->prepare(
    "SELECT id, kode_barang, nama_barang, harga, stok
       FROM barang
      WHERE id NOT IN (SELECT barang_id FROM transaksi_detail WHERE transaksi_id = ?)
      ORDER BY nama_barang"
);
$stmt->bind_param("i", $id);
$stmt->execute();
$pilihan_barang = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$judul = "Detail Transaksi #" . $id;
include 'header.php';
?>
<!-- ============ MASTER ============ -->
<div class="card">
    <h1>Transaksi #<?= e($master['id']) ?> (Master)</h1>
    <table class="info">
        <tr><td><b>Tanggal</b></td><td>: <?= e($master['waktu_transaksi']) ?></td></tr>
        <tr><td><b>Pelanggan</b></td><td>: <?= e($master['pelanggan_id'] . ' - ' . $master['pelanggan']) ?></td></tr>
        <tr><td><b>Keterangan</b></td><td>: <?= e($master['keterangan']) ?></td></tr>
        <tr><td><b>Total</b></td><td>: <b><?= rupiah($master['total']) ?></b></td></tr>
    </table>
    <p><a class="btn btn-abu" href="index.php">&laquo; Kembali ke daftar</a></p>
</div>

<!-- ============ DETAIL ============ -->
<div class="card">
    <h2>Detail Barang</h2>
    <table>
        <tr>
            <th>Kode</th><th>Nama Barang</th><th class="angka">Harga</th>
            <th class="angka">Qty</th><th class="angka">Subtotal</th><th>Aksi</th>
        </tr>
        <?php if (!$detail): ?>
            <tr><td colspan="6">Belum ada barang pada transaksi ini.</td></tr>
        <?php endif; ?>
        <?php foreach ($detail as $d): ?>
        <tr>
            <td><?= e($d['kode_barang']) ?></td>
            <td><?= e($d['nama_barang']) ?></td>
            <td class="angka"><?= rupiah($d['harga']) ?></td>
            <td class="angka"><?= e($d['qty']) ?></td>
            <td class="angka"><?= rupiah($d['subtotal']) ?></td>
            <td>
                <form method="post" style="display:inline" onsubmit="return confirm('Hapus barang ini dari transaksi?');">
                    <input type="hidden" name="aksi" value="hapus">
                    <input type="hidden" name="barang_id" value="<?= (int)$d['barang_id'] ?>">
                    <button type="submit" class="btn-merah">Hapus</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        <tr>
            <th colspan="4" class="angka">TOTAL</th>
            <th class="angka"><?= rupiah($master['total']) ?></th>
            <th></th>
        </tr>
    </table>
</div>

<!-- ============ FORM INPUT DETAIL ============ -->
<div class="card">
    <h2>Tambah Barang</h2>
    <?php if (!$pilihan_barang): ?>
        <p>Semua barang sudah dimasukkan ke transaksi ini.</p>
    <?php else: ?>
    <form method="post">
        <input type="hidden" name="aksi" value="tambah">

        <label for="barang_id">Barang</label>
        <select id="barang_id" name="barang_id" required>
            <option value="">-- pilih barang --</option>
            <?php foreach ($pilihan_barang as $b): ?>
                <option value="<?= (int)$b['id'] ?>">
                    <?= e($b['kode_barang'] . ' - ' . $b['nama_barang'] . ' (' . rupiah($b['harga']) . ')') ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="qty">Qty</label>
        <input type="number" id="qty" name="qty" min="1" value="1" required>

        <p><button type="submit">Simpan Detail</button></p>
    </form>
    <?php endif; ?>
</div>
<?php include 'footer.php'; ?>
