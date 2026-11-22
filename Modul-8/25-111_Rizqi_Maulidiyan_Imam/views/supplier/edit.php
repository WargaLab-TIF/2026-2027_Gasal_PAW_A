<?php
cekAkses([1]); 

$error = '';
$id = (int)($_GET['id'] ?? 0);

// ambil data lama
$stmt = $DBH->prepare("SELECT * FROM supplier WHERE id = ?");
$stmt->execute([$id]);
$supplier = $stmt->fetch();

if (!$supplier) {
    set_pesan('Supplier tidak ditemukan.', 'danger');
    redirect('/supplier');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama   = bersih($_POST['nama'] ?? '');
    $telp   = bersih($_POST['telp'] ?? '');
    $alamat = bersih($_POST['alamat'] ?? '');

    $supplier['nama']   = $nama;
    $supplier['telp']   = $telp;
    $supplier['alamat'] = $alamat;

    if ($nama === '') {
        $error = 'Nama supplier wajib diisi.';
    } elseif ($telp !== '' && !ctype_digit($telp)) {
        $error = 'Telepon hanya boleh berisi angka.';
    } elseif (strlen($telp) > 12) {
        $error = 'Telepon maksimal 12 digit.';
    } else {
        $stmt = $DBH->prepare("UPDATE supplier SET nama = ?, telp = ?, alamat = ? WHERE id = ?");
        $stmt->execute([$nama, $telp ?: null, $alamat ?: null, $id]);

        set_pesan('Supplier berhasil diperbarui.');
        redirect('/supplier');
    }
}

require_once __DIR__ . '/../partials/header.auth.php';
?>

<main class="container my-4">
    <div class="card shadow-sm col-lg-6 mx-auto">
        <div class="card-body">
            <h4 class="mb-3">Edit Supplier</h4>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= e($error); ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= url('/supplier/edit'); ?>?id=<?= $id; ?>">
                <div class="mb-3">
                    <label class="form-label" for="nama">Nama</label>
                    <input class="form-control" type="text" id="nama" name="nama"
                           maxlength="100" value="<?= e($supplier['nama']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="telp">Telepon</label>
                    <input class="form-control" type="text" id="telp" name="telp"
                           maxlength="12" value="<?= e($supplier['telp']); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="alamat">Alamat</label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="3"><?= e($supplier['alamat']); ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="<?= url('/supplier'); ?>" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../partials/footer.auth.php'; ?>
