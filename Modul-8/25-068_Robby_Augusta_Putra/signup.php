<?php
require_once __DIR__ . '/koneksi.php';

$error = '';
$data  = ['username' => '', 'nama' => '', 'alamat' => '', 'hp' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'username' => trim($_POST['username'] ?? ''),
        'nama'     => trim($_POST['nama'] ?? ''),
        'alamat'   => trim($_POST['alamat'] ?? ''),
        'hp'       => trim($_POST['hp'] ?? ''),
    ];
    $password   = $_POST['password'] ?? '';
    $konfirmasi = $_POST['konfirmasi'] ?? '';

    if (!preg_match('/^[A-Za-z0-9_.]{3,30}$/', $data['username'])) {
        $error = 'Username 3-30 karakter (huruf, angka, titik, underscore).';
    } elseif ($data['nama'] === '') {
        $error = 'Nama wajib diisi.';
    } elseif (!preg_match('/^[0-9+]{0,20}$/', $data['hp'])) {
        $error = 'No. HP hanya boleh angka.';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter.';
    } elseif ($password !== $konfirmasi) {
        $error = 'Konfirmasi password tidak sama.';
    } else {
        try {
            $db->prepare("INSERT INTO `user` (username, password, nama, alamat, hp, level)
                          VALUES (?, ?, ?, ?, ?, 2)")
               ->execute([$data['username'], md5($password), $data['nama'], $data['alamat'], $data['hp']]);

            $_SESSION['flash'] = ['sukses', 'Akun berhasil dibuat. Silakan login.'];
            header('Location: login.php');
            exit;
        } catch (PDOException $ex) {
            if ($ex->getCode() === '23000') $error = 'Username sudah dipakai.';
            else throw $ex;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Daftar</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="login-box">
    <h2>Daftar Akun</h2>
    <form method="POST" autocomplete="off">
        <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

        <div class="baris"><label>Username</label>
            <input type="text" name="username" maxlength="30" value="<?= htmlspecialchars($data['username']) ?>" required></div>
        <div class="baris"><label>Nama</label>
            <input type="text" name="nama" maxlength="50" value="<?= htmlspecialchars($data['nama']) ?>" required></div>
        <div class="baris"><label>Alamat</label>
            <input type="text" name="alamat" maxlength="150" value="<?= htmlspecialchars($data['alamat']) ?>"></div>
        <div class="baris"><label>No. HP</label>
            <input type="text" name="hp" maxlength="20" value="<?= htmlspecialchars($data['hp']) ?>"></div>
        <div class="baris"><label>Password</label>
            <input type="password" name="password" required></div>
        <div class="baris"><label>Ulangi Password</label>
            <input type="password" name="konfirmasi" required></div>
        <div class="tombol"><button type="submit">Daftar</button></div>

        <p style="text-align:center">Sudah punya akun? <a href="login.php">Login</a></p>
    </form>
</div>

<?php include __DIR__ . '/footer.php'; ?>