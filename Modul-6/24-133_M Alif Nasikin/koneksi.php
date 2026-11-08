<?php
// Koneksi database + fungsi bantu (dipakai semua halaman)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$koneksi = new mysqli("localhost", "root", "", "tppenjualan");
$koneksi->set_charset("utf8mb4");

// Escape output agar aman dari XSS
function e($teks) {
    return htmlspecialchars((string)$teks, ENT_QUOTES, 'UTF-8');
}

function rupiah($angka) {
    return "Rp " . number_format((int)$angka, 0, ',', '.');
}

// Tugas Praktikum 4: hitung ulang total transaksi dari seluruh detailnya
// total = SUM(harga * qty) untuk transaksi_id yang sama, lalu simpan ke transaksi.total
function hitung_ulang_total($koneksi, $transaksi_id) {
    $stmt = $koneksi->prepare(
        "UPDATE transaksi
            SET total = (SELECT COALESCE(SUM(harga * qty), 0)
                           FROM transaksi_detail
                          WHERE transaksi_id = ?)
          WHERE id = ?"
    );
    $stmt->bind_param("ii", $transaksi_id, $transaksi_id);
    $stmt->execute();
    $stmt->close();
}

// Redirect dengan pesan notifikasi
function redirect($url, $pesan, $tipe = 'sukses') {
    $pemisah = (strpos($url, '?') === false) ? '?' : '&';
    header("Location: " . $url . $pemisah . "pesan=" . urlencode($pesan) . "&tipe=" . $tipe);
    exit;
}
