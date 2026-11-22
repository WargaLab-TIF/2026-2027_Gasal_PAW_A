<?php
cekAkses([1]); // hanya owner

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/user');
}

$id = (int)($_POST['id'] ?? 0);

if ($id === (int)$_SESSION['id_user']) {
    set_pesan('Anda tidak bisa menghapus akun yang sedang dipakai login.', 'danger');
    redirect('/user');
}

try {
    $stmt = $DBH->prepare("DELETE FROM user WHERE id_user = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        set_pesan('User berhasil dihapus.');
    } else {
        set_pesan('User tidak ditemukan.', 'danger');
    }
} catch (PDOException $err) {
    set_pesan('User tidak bisa dihapus.', 'danger');
}

redirect('/user');
