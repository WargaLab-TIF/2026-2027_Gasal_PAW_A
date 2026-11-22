<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
// Menentukan alamat dasar project secara otomatis, termasuk jika folder project berada di dalam folder lain.
$projectPath = realpath(__DIR__ . '/..');
$documentRoot = realpath($_SERVER['DOCUMENT_ROOT'] ?? '');
$relativePath = ($projectPath && $documentRoot) ? str_replace('\\', '/', substr($projectPath, strlen($documentRoot))) : '';
$segments = array_filter(explode('/', trim($relativePath, '/')), 'strlen');
$encodedSegments = array_map('rawurlencode', $segments);
define('BASE_URL', '/' . implode('/', $encodedSegments));
require_once __DIR__ . '/../config/database.php';
function e($value) { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function is_logged_in() { return isset($_SESSION['user_id'], $_SESSION['level']); }
function require_login() { if (!is_logged_in()) { header('Location: ' . BASE_URL . '/auth/login.php'); exit; } }
function require_owner() { require_login(); if ((int)$_SESSION['level'] !== 1) { http_response_code(403); exit('Akses ditolak. Halaman ini khusus Owner. <a href="../index.php">Kembali</a>'); } }
function csrf_token() { if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32)); return $_SESSION['csrf']; }
function verify_csrf() { if ($_SERVER['REQUEST_METHOD'] === 'POST' && !hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) { http_response_code(419); exit('Permintaan tidak valid. Silakan kembali dan coba lagi.'); } }
