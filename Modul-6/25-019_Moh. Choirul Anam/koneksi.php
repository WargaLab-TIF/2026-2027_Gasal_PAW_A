<?php
session_start();

// Laporkan error mysqli sebagai exception agar bisa ditangkap dengan try/catch
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$db = new mysqli('localhost', 'root', '', 'penjualan');
$db->set_charset('utf8mb4');

// Escape output agar aman dari XSS
function h($teks) {
    return htmlspecialchars((string)$teks, ENT_QUOTES, 'UTF-8');
}

// Format angka ke Rupiah
function rupiah($angka) {
    return 'Rp ' . number_format((int)$angka, 0, ',', '.');
}

// Simpan pesan notifikasi (ditampilkan satu kali di header.php)
function flash($tipe, $pesan) {
    $_SESSION['flash'] = ['tipe' => $tipe, 'pesan' => $pesan];
}
