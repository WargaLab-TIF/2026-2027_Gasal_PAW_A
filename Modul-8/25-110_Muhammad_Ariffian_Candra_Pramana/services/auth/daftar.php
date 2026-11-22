<?php

$nama = $_POST['nama'] ?? '';
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$konfirmasi = $_POST['konfirmasi'] ?? '';

if($nama === '' || $username === '' || $password === '' || $konfirmasi === '') {
    redirect("daftar", null, ["error" => "Semua field wajib diisi."]);
}

if($password !== $konfirmasi) {
    redirect("daftar", null, ["error" => "Konfirmasi password tidak sama."]);
}

$encrypted_password = md5($password);

$stmt = $DBH->prepare("
    INSERT INTO user 
        (username, password, nama, level)
    VALUES
        (?, ?, ?, 2)
");

$stmt->execute([$username, $encrypted_password, $nama]);
redirect('masuk');
exit;

?>