<?php
cekAkses([1, 2]);

$transaksi_id = (int)($_GET['transaksi_id'] ?? 0);

$cek_transaksi = $DBH->prepare("SELECT id FROM transaksi WHERE id = ?");
$cek_transaksi->execute([$transaksi_id]);
if (!$cek_transaksi->fetch()) {
    set_pesan('Transaksi tidak ditemukan.', 'danger');
    redirect('/transaksi');
}

$cek_barang = $DBH->prepare("SELECT barang_id FROM transaksi_detail WHERE transaksi_id = ?");
$cek_barang->execute([$transaksi_id]);
$barang_terpakai = array_map('intval', $cek_barang->fetchAll(PDO::FETCH_COLUMN));

if (isset($_POST['submit'])) {
    $barang_baru = (int)($_POST['barang_id'] ?? 0);
    $qty         = (int)($_POST['qty'] ?? 0);

    $cek_harga = $DBH->prepare("SELECT harga, stok FROM barang WHERE id = ?");
    $cek_harga->execute([$barang_baru]);
    $barang = $cek_harga->fetch();

    if (!$barang || $qty <= 0) {
        set_pesan('Barang atau jumlah tidak valid.', 'danger');
    } elseif (in_array($barang_baru, $barang_terpakai, true)) {
        set_pesan('Barang tersebut sudah ada di transaksi ini.', 'danger');
    } elseif ($qty > (int)$barang['stok']) {
        set_pesan('Stok tidak cukup.', 'danger');
    } else {
        try {
            $DBH->beginTransaction();

            $tambah_data = $DBH->prepare("INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) VALUES (?, ?, ?, ?)");
            $tambah_data->execute([$transaksi_id, $barang_baru, $barang['harga'], $qty]);

            $kurangi_stok = $DBH->prepare("UPDATE barang SET stok = stok - ? WHERE id = ?");
            $kurangi_stok->execute([$qty, $barang_baru]);

            $total = $DBH->prepare("SELECT SUM(harga * qty) AS total_baru FROM transaksi_detail WHERE transaksi_id = ?");
            $total->execute([$transaksi_id]);
            $total_baru = $total->fetch()['total_baru'];

            $update_transaksi = $DBH->prepare("UPDATE transaksi SET total = ? WHERE id = ?");
            $update_transaksi->execute([$total_baru, $transaksi_id]);

            $DBH->commit();
            set_pesan('Item barang berhasil ditambahkan.');
        } catch (PDOException $err) {
            $DBH->rollBack();
            set_pesan('Gagal menambah item: ' . $err->getMessage(), 'danger');
        }
    }

    redirect('/transaksi/detail?transaksi_id=' . $transaksi_id);
}

$barang_list = $DBH->query("SELECT * FROM barang")->fetchAll();

require_once __DIR__ . '/../partials/header.auth.php';
?>

<main class="container my-4">
    <h2>Tambah Barang ke Transaksi (ID: <?= $transaksi_id; ?>)</h2>

    <div class="card shadow-sm" style="max-width: 400px;">
        <div class="card-body">
            <form action="<?= url('/transaksi/detail/tambah'); ?>?transaksi_id=<?= $transaksi_id; ?>" method="POST">
                <div class="mb-3">
                    <label class="form-label">Pilih Barang:</label>
                    <select name="barang_id" class="form-select" required>
                        <option value="">-- Pilih Barang --</option>
                        <?php foreach ($barang_list as $b): ?>
                            <option value="<?= $b['id']; ?>" <?= in_array((int)$b['id'], $barang_terpakai, true) ? 'disabled' : ''; ?>>
                                <?= e($b['nama_barang']); ?> - Rp <?= number_format($b['harga'], 0, ',', '.'); ?> (Stok: <?= (int)$b['stok']; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jumlah (Qty):</label>
                    <input type="number" name="qty" class="form-control" value="1" min="1" required>
                </div>

                <button type="submit" name="submit" class="btn btn-primary">Simpan Detail</button>
                <a class="btn btn-danger" href="<?= url('/transaksi/detail'); ?>?transaksi_id=<?= $transaksi_id; ?>">Batal</a>
            </form>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../partials/footer.auth.php'; ?>