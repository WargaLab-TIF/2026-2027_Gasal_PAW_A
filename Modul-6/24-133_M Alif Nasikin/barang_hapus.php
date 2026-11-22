<?php
// =====================================================================
// Tugas Praktikum 3: hapus barang dengan pengecekan data terkait.
//   1. Cek apakah barang dipakai di tabel transaksi_detail.
//   2. Jika dipakai  -> TIDAK dihapus, tampilkan notifikasi.
//   3. Jika belum    -> hapus seperti biasa.
// (Foreign key ON DELETE RESTRICT di database menjadi pengaman tambahan.)
// =====================================================================
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('barang.php', 'Permintaan tidak valid.', 'gagal');
}

$id = (int)($_POST['id'] ?? 0);

// Ambil nama barang untuk pesan notifikasi
$stmt = $koneksi->prepare("SELECT nama_barang FROM barang WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$barang = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$barang) {
    redirect('barang.php', 'Data barang tidak ditemukan.', 'gagal');
}

// Langkah 1: cek penggunaan di transaksi_detail
$stmt = $koneksi->prepare("SELECT COUNT(*) AS jumlah FROM transaksi_detail WHERE barang_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$jumlah = (int)$stmt->get_result()->fetch_assoc()['jumlah'];
$stmt->close();

// Langkah 2: sudah dipakai -> tolak
if ($jumlah > 0) {
    redirect(
        'barang.php',
        "Barang \"{$barang['nama_barang']}\" tidak dapat dihapus karena masih digunakan pada {$jumlah} detail transaksi.",
        'gagal'
    );
}

// Langkah 3: belum dipakai -> hapus
$stmt = $koneksi->prepare("DELETE FROM barang WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

redirect('barang.php', "Barang \"{$barang['nama_barang']}\" berhasil dihapus.");
