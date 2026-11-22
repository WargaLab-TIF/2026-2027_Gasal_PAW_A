<?php
cekAkses([1]); // hanya owner

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama   = bersih($_POST['nama'] ?? '');
    $jk     = $_POST['jenis_kelamin'] ?? '';
    $telp   = bersih($_POST['telp'] ?? '');
    $alamat = bersih($_POST['alamat'] ?? '');

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
            INSERT INTO pelanggan (nama, jenis_kelamin, telp, alamat)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$nama, $jk, $telp ?: null, $alamat ?: null]);

        set_pesan('Pelanggan berhasil ditambahkan.');
        redirect('/pelanggan');
    }
}

require_once __DIR__ . '/../partials/header.auth.php';
?>

<main class="container my-4">
    <div class="card shadow-sm col-lg-6 mx-auto">
        <div class="card-body">
            <h4 class="mb-3">Tambah Pelanggan</h4>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= e($error); ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= url('/pelanggan/tambah'); ?>">
                <div class="mb-3">
                    <label class="form-label" for="nama">Nama</label>
                    <input class="form-control" type="text" id="nama" name="nama"
                           maxlength="100" value="<?= e($_POST['nama'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="jenis_kelamin">Jenis Kelamin</label>
                    <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                        <option value="">-- Pilih --</option>
                        <option value="L" <?= ($_POST['jenis_kelamin'] ?? '') === 'L' ? 'selected' : ''; ?>>Laki-laki</option>
                        <option value="P" <?= ($_POST['jenis_kelamin'] ?? '') === 'P' ? 'selected' : ''; ?>>Perempuan</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="telp">Telepon</label>
                    <input class="form-control" type="text" id="telp" name="telp"
                           maxlength="12" value="<?= e($_POST['telp'] ?? ''); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="alamat">Alamat</label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="3"><?= e($_POST['alamat'] ?? ''); ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="<?= url('/pelanggan'); ?>" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../partials/footer.auth.php'; ?>
