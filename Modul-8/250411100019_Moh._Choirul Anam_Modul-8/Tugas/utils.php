<?php

// Level user (kolom user.level)
const LEVEL_OWNER = 1;
const LEVEL_KASIR = 2;

/* ================= HELPER UMUM ================= */

// Escape saat MENAMPILKAN data (cegah XSS)
function e($value): string
{
  return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

// URL aplikasi mengikuti pola proyek ini: index.php/route
function url(string $path = '/'): string
{
  return $_SERVER['SCRIPT_NAME'] . $path;
}

function redirect(string $path): void
{
  header("Location: " . url($path));
  exit;
}

function rupiah($angka): string
{
  return "Rp. " . number_format((int) $angka, 0, ',', '.');
}

/* ================= SESSION & PROTEKSI HALAMAN ================= */

function init_session(): void
{
  if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
      'httponly' => true,   // cookie session tidak bisa dibaca JavaScript
      'samesite' => 'Lax',  // mengurangi risiko CSRF
      'secure'   => !empty($_SERVER['HTTPS']),
    ]);
    session_start();
  }
}

function is_logged_in(): bool
{
  return !empty($_SESSION['login']) && !empty($_SESSION['user']);
}

// Halaman terproteksi: belum login -> /login
function require_login(): void
{
  if (!is_logged_in()) {
    redirect("/login");
  }
  // Cegah cache: tombol Back setelah logout tidak menampilkan halaman lama
  header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
  header("Pragma: no-cache");
}

// Halaman khusus tamu (login): sudah login -> Home
function require_guest(): void
{
  if (is_logged_in()) {
    redirect("/");
  }
}

// Batasi per level, mis. require_level([LEVEL_OWNER])
function require_level(array $allowed): void
{
  require_login();
  if (!in_array((int) $_SESSION['user']['level'], $allowed, true)) {
    http_response_code(403);
    require __DIR__ . "/views/403.php";
    exit;
  }
}

/* ================= FLASH MESSAGE (tampil sekali) ================= */

function set_flash(string $type, string $message): void
{
  $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function render_flash(): string
{
  if (empty($_SESSION['flash'])) {
    return "";
  }
  $f = $_SESSION['flash'];
  unset($_SESSION['flash']);
  $class = $f['type'] === 'success' ? 'success' : 'error';
  return '<div class="alert ' . $class . '">' . e($f['message']) . '</div>';
}

/* ================= CSRF ================= */

function csrf_token(): string
{
  if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
  }
  return $_SESSION['csrf'];
}

function csrf_field(): string
{
  return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

function csrf_check(): void
{
  if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
    http_response_code(419);
    exit("Token form tidak valid. Muat ulang halaman lalu coba lagi.");
  }
}
