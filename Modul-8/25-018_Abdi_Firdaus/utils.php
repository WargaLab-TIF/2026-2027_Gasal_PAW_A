<?php
// utils.php - helper session, url, dan proteksi halaman

// mulai session kalau belum aktif
function ensureSession()
{
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }
}

// escape output html
function sanitazion($input)
{
  return htmlspecialchars(trim((string) $input), ENT_QUOTES, 'UTF-8');
}

// bentuk url rute
function routeUrl($path)
{
  if (PHP_SAPI === "cli-server") {
    return $path;
  }
  $scriptName = $_SERVER["SCRIPT_NAME"] ?? "";
  return $path === "/" ? $scriptName : $scriptName . $path;
}

function currentPath()
{
  $path = $_SERVER['PATH_INFO'] ?? null;
  if ($path === null) {
    if (PHP_SAPI === 'cli-server') {
      $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    } else {
      $path = '/';
    }
  }
  $normalized = '/' . trim((string) $path, '/');
  return $normalized === '' ? '/' : $normalized;
}

// tandai menu aktif di navbar
function isNavActive($prefixes)
{
  $current = currentPath();
  foreach ((array) $prefixes as $prefix) {
    if ($prefix === '/') {
      if ($current === '/' || $current === '/home') {
        return true;
      }
    } elseif ($current === $prefix || str_starts_with($current, $prefix . '/')) {
      return true;
    }
  }
  return false;
}

function formatRupiah($amount)
{
  return 'Rp. ' . number_format((int) $amount, 0, ',', '.');
}

// simpan flash message
function setFlash($type, $message)
{
  ensureSession();
  $_SESSION['flash'] = [
    'type' => $type,
    'message' => $message,
  ];
}

// ambil lalu hapus flash message
function getFlash()
{
  ensureSession();
  if (!isset($_SESSION['flash'])) {
    return null;
  }
  $flash = $_SESSION['flash'];
  unset($_SESSION['flash']);
  return $flash;
}

// cek login
function isLoggedIn()
{
  ensureSession();
  return isset($_SESSION['is_login'], $_SESSION['user_id'], $_SESSION['level'])
    && $_SESSION['is_login'] === true;
}

function isOwner()
{
  return isLoggedIn() && (int) $_SESSION['level'] === 1;
}

function currentUserName()
{
  ensureSession();
  return (string) ($_SESSION['nama'] ?? $_SESSION['username'] ?? 'Pengguna');
}

function currentUserLevel()
{
  ensureSession();
  return (int) ($_SESSION['level'] ?? 0);
}

function levelLabel($level)
{
  return (int) $level === 1 ? 'Owner' : 'Kasir';
}

// wajib login
function requireLogin()
{
  ensureSession();
  header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
  header("Pragma: no-cache");

  if (!isLoggedIn()) {
    setFlash('warning', 'Anda harus login terlebih dahulu untuk mengakses halaman tersebut.');
    header("Location: " . routeUrl("/login"));
    exit();
  }
}

// khusus tamu, kalau sudah login pindah ke home
function requireGuest()
{
  ensureSession();
  if (isLoggedIn()) {
    header("Location: " . routeUrl("/"));
    exit();
  }
}

// khusus owner (level 1)
function requireOwner()
{
  requireLogin();
  if (!isOwner()) {
    setFlash('danger', 'Akses ditolak! Menu Data Master hanya dapat diakses oleh Owner (Level 1).');
    header("Location: " . routeUrl("/"));
    exit();
  }
}
