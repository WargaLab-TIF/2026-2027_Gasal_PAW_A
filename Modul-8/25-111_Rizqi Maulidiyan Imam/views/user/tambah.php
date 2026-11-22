<?php
cekAkses([1]);

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = bersih($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $nama     = bersih($_POST['nama'] ?? '');
    $alamat   = bersih($_POST['alamat'] ?? '');
    $hp       = bersih($_POST['hp'] ?? '');
    $level    = (int)($_POST['level'] ?? 0);

    if ($username === '' || $password === '' || $nama === '') {
        $error = 'Username, password, dan nama wajib diisi.';
    } elseif (strlen($username) > 30) {
        $error = 'Username maksimal 30 karakter.';
    } elseif (strlen($password) < 3) {
        $error = 'Password minimal 3 karakter.';
    } elseif (!in_array($level, [1, 2], true)) {
        $error = 'Pilih level user.';
    } elseif ($hp !== '' && !ctype_digit($hp)) {
        $error = 'HP hanya boleh berisi angka.';
    } else {
        $cek = $DBH->prepare("SELECT id_user FROM user WHERE username = ?");
        $cek->execute([$username]);

        if ($cek->fetch()) {
            $error = 'Username sudah dipakai.';
        } else {
            $stmt = $DBH->prepare("
                INSERT INTO user (username, password, nama, alamat, hp, level)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$username, md5($password), $nama, $alamat ?: null, $hp ?: null, $level]);

            set_pesan('User berhasil ditambahkan.');
            redirect('/user');
        }
    }
}

require_once __DIR__ . '/../partials/header.auth.php';
?>

<main class="container my-4">
    <div class="card shadow-sm col-lg-6 mx-auto">
        <div class="card-body">
            <h4 class="mb-3">Tambah User</h4>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= e($error); ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= url('/user/tambah'); ?>">
                <div class="mb-3">
                    <label class="form-label" for="username">Username</label>
                    <input class="form-control" type="text" id="username" name="username"
                           maxlength="30" value="<?= e($_POST['username'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="password">Password</label>
                    <input class="form-control" type="password" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="nama">Nama</label>
                    <input class="form-control" type="text" id="nama" name="nama"
                           maxlength="50" value="<?= e($_POST['nama'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="alamat">Alamat</label>
                    <input class="form-control" type="text" id="alamat" name="alamat"
                           maxlength="150" value="<?= e($_POST['alamat'] ?? ''); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="hp">HP</label>
                    <input class="form-control" type="text" id="hp" name="hp"
                           maxlength="20" value="<?= e($_POST['hp'] ?? ''); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="level">Level</label>
                    <select class="form-select" id="level" name="level" required>
                        <option value="">-- Pilih level --</option>
                        <option value="1" <?= (int)($_POST['level'] ?? 0) === 1 ? 'selected' : ''; ?>>1 - Owner</option>
                        <option value="2" <?= (int)($_POST['level'] ?? 0) === 2 ? 'selected' : ''; ?>>2 - Kasir</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="<?= url('/user'); ?>" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../partials/footer.auth.php'; ?>
