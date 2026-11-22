<?php
cekAkses([1]); // hanya owner

$error = '';
$id = (int)($_GET['id'] ?? 0);

// ambil data lama
$stmt = $DBH->prepare("SELECT * FROM pelanggan WHERE id = ?");
$stmt->execute([$id]);
$pelanggan = $stmt->fetch();

if (!$pelanggan) {
    set_pesan('Pelanggan tidak ditemukan.', 'danger');
    redirect('/pelanggan');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama   = bersih($_POST['nama'] ?? '');
    $jk     = $_POST['jenis_kelamin'] ?? '';
    $telp   = bersih($_POST['telp'] ?? '');
    $alamat = bersih($_POST['alamat'] ?? '');

    // tampilkan lagi isian terakhir kalau ada error
    $pelanggan['nama']          = $nama;
    $pelanggan['jenis_kelamin'] = $jk;
    $pelanggan['telp']          = $telp;
    $pelanggan['alamat']        = $alamat;

    if ($nama === '') {
        $error = 'Nama pelanggan wajib diisi.';
    } elseif (!in_array($jk, ['L', 'P'], true)) {
        $error = 'Pilih jenis kelamin.';
    } elseif ($telp !== '' && !ctype_digit($telp)) {
        $error = 'Telepon hanya boleh berisi angka.';
    } elseif (strlen($telp) > 12) {
        $error = 'Telepon maksimal 12 digit.';
    } else {
        $stmt = $DBH->prepare("
            UPDATE pelanggan
            SET nama = ?, jenis_kelamin = ?, telp = ?, alamat = ?
            WHERE id = ?
        ");
        $stmt->execute([$nama, $jk, $telp ?: null, $alamat ?: null, $id]);

        set_pesan('Pelanggan berhasil diperbarui.');
        redirect('/pelanggan');
    }
}

require_once __DIR__ . '/../partials/header.auth.php';
?>

<main class="container my-4">
    <div class="card shadow-sm col-lg-6 mx-auto">
        <div class="card-body">
            <h4 class="mb-3">Edit Pelanggan</h4>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= e($error); ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= url('/pelanggan/edit'); ?>?id=<?= $id; ?>">
                <div class="mb-3">
                    <label class="form-label" for="nama">Nama</label>
                    <input class="form-control" type="text" id="nama" name="nama"
                           maxlength="100" value="<?= e($pelanggan['nama']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="jenis_kelamin">Jenis Kelamin</label>
                    <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                        <option value="">-- Pilih --</option>
                        <option value="L" <?= $pelanggan['jenis_kelamin'] === 'L' ? 'selected' : ''; ?>>Laki-laki</option>
                        <option value="P" <?= $pelanggan['jenis_kelamin'] === 'P' ? 'selected' : ''; ?>>Perempuan</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="telp">Telepon</label>
                    <input class="form-control" type="text" id="telp" name="telp"
                           maxlength="12" value="<?= e($pelanggan['telp']); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="alamat">Alamat</label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="3"><?= e($pelanggan['alamat']); ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="<?= url('/pelanggan'); ?>" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../partials/footer.auth.php'; ?>
