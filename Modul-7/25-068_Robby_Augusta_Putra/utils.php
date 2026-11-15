<?php

require_once __DIR__ . '/koneksi.php';

function e($teks): string
{
    return htmlspecialchars((string) $teks, ENT_QUOTES, 'UTF-8');
}

function rupiah($angka): string
{
    return 'Rp. ' . number_format((float) $angka, 0, ',', '.');
}

function tanggal_indo(string $tgl): string
{
    $bulan = [1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
              'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    $t = strtotime($tgl);
    return date('d', $t) . ' ' . $bulan[(int) date('n', $t)] . ' ' . date('Y', $t);
}

function valid_tanggal(string $tgl): bool
{
    $d = DateTime::createFromFormat('Y-m-d', $tgl);
    return $d !== false && $d->format('Y-m-d') === $tgl;
}

function ambil_filter(): array
{
    $mulai   = trim($_GET['tanggal_mulai']   ?? '');
    $selesai = trim($_GET['tanggal_selesai'] ?? '');
    $error   = '';

    if ($mulai === '' && $selesai === '') {
        return ['', '', ''];
    }
    if (!valid_tanggal($mulai) || !valid_tanggal($selesai)) {
        $error = 'Format tanggal tidak valid.';
    } elseif ($mulai > $selesai) {
        $error = 'Tanggal mulai tidak boleh lebih besar dari tanggal selesai.';
    }
    return [$mulai, $selesai, $error];
}
