<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function wajibLogin() {
    if (!isset($_SESSION['user'])) {
        header("Location: login.php");
        exit;
    }
}

function wajibOwner() {
    wajibLogin();
    if ((int)$_SESSION['user']['level'] !== 1) {
        http_response_code(403);
        exit("Akses ditolak. Halaman ini hanya dapat diakses oleh Owner. <a href='index.php'>Kembali</a>");
    }
}
?>
