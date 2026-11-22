<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function sudah_login(): bool
{
    return isset($_SESSION['user']['id_user'], $_SESSION['user']['level']);
}

function page_url(string $page = 'home', array $params = []): string
{
    return 'index.php?' . http_build_query(array_merge(['page' => $page], $params));
}

function wajib_login(): void
{
    if (!sudah_login()) {
        header('Location: ' . page_url('login'));
        exit;
    }
}

function wajib_owner(): void {
    wajib_login();
    if ((int) $_SESSION['user']['level'] !== 1) {
        http_response_code(403);
        exit('Akses ditolak. Halaman ini hanya dapat dibuka oleh owner.');
    }
}

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
