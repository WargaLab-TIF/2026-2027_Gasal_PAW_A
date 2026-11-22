<?php
cekAkses([1]);

$error = '';

$supplierList = $DBH->query("SELECT id, nama FROM supplier ORDER BY nama ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode        = bersih($_POST['kode_barang'] ?? '');
    $nama        = bersih($_POST['nama_barang'] ?? '');
    $harga       = bersih($_POST['harga'] ?? '');
    $stok        = bersih($_POST['stok'] ?? '');
    $supplier_id = (int)($_POST['supplier_id'] ?? 0);

    if ($kode === '' || $nama === '' || $harga === '' || $stok === '' || $supplier_id === 0) {
        $error = 'Semua kolom wajib diisi.';
    } elseif (strlen($kode) > 10) {
        $error = 'Kode barang maksimal 10 karakter.';
    } elseif (!is_numeric($harga) || $harga <= 0) {
        $error = 'Harga harus berupa angka lebih dari 0.';
    } elseif (!is_numeric($stok) || $stok < 0) {
        $error = 'Stok harus berupa angka, minimal 0.';
    } else {
        $cek = $DBH->prepare("SELECT id FROM barang WHERE kode_barang = ?");
        $cek->execute([$kode]);

        if ($cek->fetch()) {
            $error = 'Kode barang sudah dipakai.';
        } else {
            // 3. simpan ke database
            $stmt = $DBH->prepare("
                INSERT INTO barang (kode_barang, nama_barang, harga, stok, supplier_id)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([$kode, $nama, $harga, $stok, $supplier_id]);

            set_pesan('Barang berhasil ditambahkan.');
            redirect('/barang');
        }
    }
}

require_once __DIR__ . '/../partials/header.auth.php';
?>

<main class="container my-4">
    <div class="card shadow-sm col-lg-6 mx-auto">
        <div class="card-body">
            <h4 class="mb-3">Tambah Barang</h4>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= e($error); ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= url('/barang/tambah'); ?>">
                <div class="mb-3">
                    <label class="form-label" for="kode_barang">Kode Barang</label>
                    <input class="form-control" type="text" id="kode_barang" name="kode_barang"
                           maxlength="10" value="<?= e($_POST['kode_barang'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="nama_barang">Nama Barang</label>
                    <input class="form-control" type="text" id="nama_barang" name="nama_barang"
                           maxlength="100" value="<?= e($_POST['nama_barang'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="harga">Harga</label>
                    <input class="form-control" type="number" id="harga" name="harga"
                           min="1" value="<?= e($_POST['harga'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="stok">Stok</label>
                    <input class="form-control" type="number" id="stok" name="stok"
                           min="0" value="<?= e($_POST['stok'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="supplier_id">Supplier</label>
                    <select class="form-select" id="supplier_id" name="supplier_id" required>
                        <option value="">-- Pilih supplier --</option>
                        <?php foreach ($supplierList as $s): ?>
                            <option value="<?= $s['id']; ?>"
                                <?= ((int)($_POST['supplier_id'] ?? 0) === (int)$s['id']) ? 'selected' : ''; ?>>
                                <?= e($s['nama']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="<?= url('/barang'); ?>" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../partials/footer.auth.php'; ?>