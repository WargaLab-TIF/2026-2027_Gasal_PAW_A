<?php
cekAkses([1]);

$error = '';
$id = (int)($_GET['id'] ?? 0);

$stmt = $DBH->prepare("SELECT id_user, username, nama, alamat, hp, level FROM user WHERE id_user = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    set_pesan('User tidak ditemukan.', 'danger');
    redirect('/user');
}

$levelAsli = (int)$user['level'];
$pilihanLevel = [1, 2];
if (!in_array($levelAsli, $pilihanLevel, true)) {
    $pilihanLevel[] = $levelAsli;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = bersih($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';  
    $nama     = bersih($_POST['nama'] ?? '');
    $alamat   = bersih($_POST['alamat'] ?? '');
    $hp       = bersih($_POST['hp'] ?? '');
    $level    = (int)($_POST['level'] ?? 0);

    $user['username'] = $username;
    $user['nama']     = $nama;
    $user['alamat']   = $alamat;
    $user['hp']       = $hp;
    $user['level']    = $level;

    if ($username === '' || $nama === '') {
        $error = 'Username dan nama wajib diisi.';
    } elseif (strlen($username) > 30) {
        $error = 'Username maksimal 30 karakter.';
    } elseif ($password !== '' && strlen($password) < 3) {
        $error = 'Password minimal 3 karakter.';
    } elseif (!in_array($level, $pilihanLevel, true)) {
        $error = 'Pilih level user.';
    } elseif ($hp !== '' && !ctype_digit($hp)) {
        $error = 'HP hanya boleh berisi angka.';
    } else {
        $cek = $DBH->prepare("SELECT id_user FROM user WHERE username = ? AND id_user != ?");
        $cek->execute([$username, $id]);

        if ($cek->fetch()) {
            $error = 'Username sudah dipakai user lain.';
        } else {
            if ($password !== '') {
                // password ikut diganti
                $stmt = $DBH->prepare("
                    UPDATE user
                    SET username = ?, password = ?, nama = ?, alamat = ?, hp = ?, level = ?
                    WHERE id_user = ?
                ");
                $stmt->execute([$username, md5($password), $nama, $alamat ?: null, $hp ?: null, $level, $id]);
            } else {
                $stmt = $DBH->prepare("
                    UPDATE user
                    SET username = ?, nama = ?, alamat = ?, hp = ?, level = ?
                    WHERE id_user = ?
                ");
                $stmt->execute([$username, $nama, $alamat ?: null, $hp ?: null, $level, $id]);
            }

            if ($id === (int)$_SESSION['id_user']) {
                $_SESSION['username'] = $username;
                $_SESSION['nama']     = $nama;
                $_SESSION['level']    = $level;
            }

            set_pesan('User berhasil diperbarui.');
            redirect('/user');
        }
    }
}

require_once __DIR__ . '/../partials/header.auth.php';
?>

<main class="container my-4">
    <div class="card shadow-sm col-lg-6 mx-auto">
        <div class="card-body">
            <h4 class="mb-3">Edit User</h4>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= e($error); ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= url('/user/edit'); ?>?id=<?= $id; ?>">
                <div class="mb-3">
                    <label class="form-label" for="username">Username</label>
                    <input class="form-control" type="text" id="username" name="username"
                           maxlength="30" value="<?= e($user['username']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="password">Password</label>
                    <input class="form-control" type="password" id="password" name="password">
                    <div class="form-text">Kosongkan jika tidak ingin mengganti password.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="nama">Nama</label>
                    <input class="form-control" type="text" id="nama" name="nama"
                           maxlength="50" value="<?= e($user['nama']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="alamat">Alamat</label>
                    <input class="form-control" type="text" id="alamat" name="alamat"
                           maxlength="150" value="<?= e($user['alamat']); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="hp">HP</label>
                    <input class="form-control" type="text" id="hp" name="hp"
                           maxlength="20" value="<?= e($user['hp']); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="level">Level</label>
                    <select class="form-select" id="level" name="level" required>
                        <?php foreach ($pilihanLevel as $lv): ?>
                            <option value="<?= $lv; ?>" <?= (int)$user['level'] === $lv ? 'selected' : ''; ?>>
                                <?php
                                if ($lv === 1)      echo '1 - Owner';
                                elseif ($lv === 2)  echo '2 - Kasir';
                                else                echo $lv . ' - (tidak bisa login)';
                                ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="<?= url('/user'); ?>" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../partials/footer.auth.php'; ?>
