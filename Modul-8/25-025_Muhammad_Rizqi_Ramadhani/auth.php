<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function getNamaLevel(int $level): string
{
    return match ($level) {
        1 => "owner",
        2 => "kasir",
        default => "level_" . $level,
    };
}

function isLoggedIn(): bool
{
    return isset($_SESSION["user_id"], $_SESSION["username"], $_SESSION["level"]);
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit;
    }
}

function redirectIfLoggedIn(): void
{
    if (isLoggedIn()) {
        header("Location: dashboard.php");
        exit;
    }
}

function requireLevel(array $allowedLevels): void
{
    requireLogin();
    if (!in_array((int) $_SESSION["level"], $allowedLevels, true)) {
        http_response_code(403);
        echo "Akses ditolak: Anda tidak memiliki izin untuk mengakses halaman ini.";
        exit;
    }
}