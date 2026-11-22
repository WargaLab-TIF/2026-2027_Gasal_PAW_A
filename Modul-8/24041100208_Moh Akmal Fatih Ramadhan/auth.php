
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config.php';

// Memeriksa apakah user sudah login
function wajibLogin(): void {

    if (empty($_SESSION['user_id'])) {

        header('Location: /Modul-8/login.php');
        exit;

    }
}

// Memeriksa apakah user adalah Owner
function wajibOwner(): void {

    wajibLogin();

    if ((int)($_SESSION['level'] ?? 0) !== 1) {

        http_response_code(403);

        exit('403 - Akses ditolak. Halaman ini hanya untuk Owner.');

    }
}

// Mengamankan output HTML
function e($value): string {

    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES,
        'UTF-8'
    );

}

// Membuat token keamanan
function csrfToken(): string {

    if (empty($_SESSION['csrf_token'])) {

        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

    }

    return $_SESSION['csrf_token'];

}

// Memeriksa token keamanan
function cekCsrf(): void {

    $token = $_POST['csrf_token'] ?? '';

    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {

        http_response_code(419);

        exit('Permintaan tidak valid. Muat ulang halaman lalu coba lagi.');

    }
}
