<?php
cekAkses([1, 2]);

$pesan = '';
$barangList    = $DBH->query("SELECT id, kode_barang, nama_barang, harga, stok FROM barang ORDER BY id ASC")->fetchAll();
$pelangganList = $DBH->query("SELECT id, nama FROM pelanggan ORDER BY id ASC")->fetchAll();

$selectedPelanggan = trim($_POST['pelanggan_id'] ?? ($_GET['pelanggan_id'] ?? ''));

$sudahDibeli = [];
if ($selectedPelanggan !== '') {
    $stmtBeli = $DBH->prepare("
        SELECT DISTINCT td.barang_id
        FROM transaksi_detail td
        JOIN transaksi t ON td.transaksi_id = t.id
        WHERE t.pelanggan_id = ?
    ");
    $stmtBeli->execute([$selectedPelanggan]);
    $sudahDibeli = array_map('intval', $stmtBeli->fetchAll(PDO::FETCH_COLUMN, 0));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pelangganId = trim($_POST['pelanggan_id'] ?? '');
    $barangId    = (int)($_POST['barang_id'] ?? 0);
    $qty         = (int)($_POST['qty'] ?? 0);
    $tanggal     = ($_POST['waktu_transaksi'] ?? '') ?: date('Y-m-d');
    $keterangan  = trim($_POST['keterangan'] ?? '');

    $stmt = $DBH->prepare("SELECT harga, stok FROM barang WHERE id = ?");
    $stmt->execute([$barangId]);
    $barang = $stmt->fetch();

    $cek = $DBH->prepare("
        SELECT COUNT(*)
        FROM transaksi_detail td
        JOIN transaksi t ON td.transaksi_id = t.id
        WHERE t.pelanggan_id = ? AND td.barang_id = ?
    ");
    $cek->execute([$pelangganId, $barangId]);

    if ($pelangganId === '' || !$barang || $qty <= 0) {
        $pesan = 'Lengkapi data pelanggan, barang, dan jumlah.';
    } elseif ($cek->fetchColumn() > 0) {
        $pesan = 'Pelanggan ini sudah pernah membeli barang tersebut.';
    } elseif ($qty > (int)$barang['stok']) {
        $pesan = 'Stok tidak cukup.';
    } else {
        $total = (int)$barang['harga'] * $qty;

        try {
            $DBH->beginTransaction();

            $stmt = $DBH->prepare("INSERT INTO transaksi (waktu_transaksi, keterangan, total, pelanggan_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([$tanggal, $keterangan ?: null, $total, $pelangganId]);
            $idTransaksi = (int)$DBH->lastInsertId();

            $stmt = $DBH->prepare("INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) VALUES (?, ?, ?, ?)");
            $stmt->execute([$idTransaksi, $barangId, (int)$barang['harga'], $qty]);

            $stmt = $DBH->prepare("UPDATE barang SET stok = stok - ? WHERE id = ?");
            $stmt->execute([$qty, $barangId]);

            $DBH->commit();
        } catch (PDOException $err) {
            $DBH->rollBack();
            $pesan = 'Gagal menyimpan transaksi: ' . $err->getMessage();
        }

        if ($pesan === '') {
            set_pesan('Transaksi baru berhasil ditambahkan.');
            redirect('/transaksi');
        }
    }
}

require_once __DIR__ . '/../partials/header.auth.php';
?>

<main class="container my-4">
    <h2>Tambah Transaksi Baru</h2>
    <a href="<?= url('/transaksi'); ?>" class="btn btn-secondary btn-sm mb-3">&laquo; Kembali ke Data Transaksi</a>

    <?php if ($pesan !== ''): ?>
        <div class="alert alert-danger"><?= e($pesan); ?></div>
    <?php endif; ?>

    <div class="card shadow-sm" style="max-width: 500px;">
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Tanggal:</label>
                    <input type="date" class="form-control" name="waktu_transaksi" value="<?= date('Y-m-d'); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Pelanggan:</label>
                    <select class="form-select" name="pelanggan_id" required
                            onchange="if(this.value){window.location='<?= url('/transaksi/baru'); ?>?pelanggan_id='+encodeURIComponent(this.value);}">
                        <option value="">-- Pilih Pelanggan --</option>
                        <?php foreach ($pelangganList as $p): ?>
                            <option value="<?= e($p['id']); ?>" <?= $selectedPelanggan === (string)$p['id'] ? 'selected' : ''; ?>>
                                <?= e($p['id'] . ' - ' . $p['nama']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Barang:</label>
                    <select class="form-select" name="barang_id" required>
                        <option value="">-- Pilih Barang --</option>
                        <?php foreach ($barangList as $b): ?>
                            <?php $terkunci = in_array((int)$b['id'], $sudahDibeli, true); ?>
                            <option value="<?= (int)$b['id']; ?>" <?= $terkunci ? 'disabled' : ''; ?>>
                                <?= e($b['nama_barang']); ?> (Rp <?= number_format((int)$b['harga'], 0, ',', '.'); ?>, Stok: <?= (int)$b['stok']; ?>)<?= $terkunci ? ' - Sudah dibeli' : ''; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jumlah:</label>
                    <input type="number" class="form-control" name="qty" min="1" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Keterangan:</label>
                    <input type="text" class="form-control" name="keterangan">
                </div>

                <button type="submit" class="btn btn-success">Simpan Transaksi</button>
            </form>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../partials/footer.auth.php'; ?>