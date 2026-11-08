<?php
require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: barang.php');
    exit;
}

$id = (int)($_POST['id'] ?? 0);

// 1. Pastikan barang ada
$stmt = $db->prepare("SELECT nama_barang FROM barang WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$barang = $stmt->get_result()->fetch_assoc();

if (!$barang) {
    flash('error', 'Data barang tidak ditemukan.');
    header('Location: barang.php');
    exit;
}

// 2. Cek apakah barang sudah dipakai pada transaksi_detail
$stmt = $db->prepare("SELECT COUNT(*) AS jml FROM transaksi_detail
                      WHERE barang_id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$jml = (int)$stmt->get_result()->fetch_assoc()['jml'];

if ($jml > 0) {
    // Sudah dipakai -> TOLAK penghapusan
    flash('error', 'Barang "' . $barang['nama_barang']
        . '" tidak dapat dihapus karena masih digunakan pada '
        . $jml . ' detail transaksi.');
} else {
    // Belum dipakai -> boleh dihapus
    try {
        $stmt = $db->prepare("DELETE FROM barang WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        flash('success', 'Barang "' . $barang['nama_barang']
            . '" berhasil dihapus.');
    } catch (mysqli_sql_exception $e) {
        // Jaring pengaman: foreign key RESTRICT di database
        flash('error', 'Barang tidak dapat dihapus karena masih berelasi '
            . 'dengan data lain.');
    }
}

header('Location: barang.php');
exit;
