<?php
cekAkses([1]);

$error = '';
$id = (int)($_GET['id'] ?? 0);

$stmt = $DBH->prepare("SELECT * FROM barang WHERE id = ?");
$stmt->execute([$id]);
$barang = $stmt->fetch();

if (!$barang) {
    set_pesan('Barang tidak ditemukan.', 'danger');
    redirect('/barang');
}

$supplierList = $DBH->query("SELECT id, nama FROM supplier ORDER BY nama ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode        = bersih($_POST['kode_barang'] ?? '');
    $nama        = bersih($_POST['nama_barang'] ?? '');
    $harga       = bersih($_POST['harga'] ?? '');
    $stok        = bersih($_POST['stok'] ?? '');
    $supplier_id = (int)($_POST['supplier_id'] ?? 0);

    $barang['kode_barang'] = $kode;
    $barang['nama_barang'] = $nama;
    $barang['harga']       = $harga;
    $barang['stok']        = $stok;
    $barang['supplier_id'] = $supplier_id;

    if ($kode === '' || $nama === '' || $harga === '' || $stok === '' || $supplier_id === 0) {
        $error = 'Semua kolom wajib diisi.';
    } elseif (strlen($kode) > 10) {
        $error = 'Kode barang maksimal 10 karakter.';
    } elseif (!is_numeric($harga) || $harga <= 0) {
        $error = 'Harga harus berupa angka lebih dari 0.';
    } elseif (!is_numeric($stok) || $stok < 0) {
        $error = 'Stok harus berupa angka, minimal 0.';
    } else {
        $cek = $DBH->prepare("SELECT id FROM barang WHERE kode_barang = ? AND id != ?");
        $cek->execute([$kode, $id]);

        if ($cek->fetch()) {
            $error = 'Kode barang sudah dipakai barang lain.';
        } else {
            $stmt = $DBH->prepare("
                UPDATE barang
                SET kode_barang = ?, nama_barang = ?, harga = ?, stok = ?, supplier_id = ?
                WHERE id = ?
            ");
            $stmt->execute([$kode, $nama, $harga, $stok, $supplier_id, $id]);

            set_pesan('Barang berhasil diperbarui.');
            redirect('/barang');
        }
    }
}

require_once __DIR__ . '/../partials/header.auth.php';
?>

<main class="container my-4">
    <div class="card shadow-sm col-lg-6 mx-auto">
        <div class="card-body">
            <h4 class="mb-3">Edit Barang</h4>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= e($error); ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= url('/barang/edit'); ?>?id=<?= $id; ?>">
                <div class="mb-3">
                    <label class="form-label" for="kode_barang">Kode Barang</label>
                    <input class="form-control" type="text" id="kode_barang" name="kode_barang"
                           maxlength="10" value="<?= e($barang['kode_barang']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="nama_barang">Nama Barang</label>
                    <input class="form-control" type="text" id="nama_barang" name="nama_barang"
                           maxlength="100" value="<?= e($barang['nama_barang']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="harga">Harga</label>
                    <input class="form-control" type="number" id="harga" name="harga"
                           min="1" value="<?= e($barang['harga']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="stok">Stok</label>
                    <input class="form-control" type="number" id="stok" name="stok"
                           min="0" value="<?= e($barang['stok']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="supplier_id">Supplier</label>
                    <select class="form-select" id="supplier_id" name="supplier_id" required>
                        <option value="">-- Pilih supplier --</option>
                        <?php foreach ($supplierList as $s): ?>
                            <option value="<?= $s['id']; ?>"
                                <?= ((int)$barang['supplier_id'] === (int)$s['id']) ? 'selected' : ''; ?>>
                                <?= e($s['nama']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="<?= url('/barang'); ?>" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../partials/footer.auth.php'; ?>