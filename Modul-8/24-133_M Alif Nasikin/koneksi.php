<?php
// Koneksi ke database TP8
$koneksi = mysqli_connect("localhost", "root", "", "penjualan");
if (!$koneksi) {
    die("Koneksi gagal! Pastikan MySQL aktif dan database.sql sudah diimport.");
}
mysqli_set_charset($koneksi, "utf8mb4");

// Menjalankan query memakai prepared statement (aman dari SQL Injection)
// Contoh: db_query("DELETE FROM barang WHERE id=?", "i", $id);
function db_query($sql, $tipe = "", ...$param) {
    global $koneksi;
    $st = mysqli_prepare($koneksi, $sql);
    if ($tipe !== "") {
        mysqli_stmt_bind_param($st, $tipe, ...$param);
    }
    mysqli_stmt_execute($st);
    return $st;
}

// Mengambil semua baris hasil query (array of array)
function ambil_semua($sql, $tipe = "", ...$param) {
    $st = db_query($sql, $tipe, ...$param);
    return mysqli_fetch_all(mysqli_stmt_get_result($st), MYSQLI_ASSOC);
}

// Mengambil satu baris, atau null jika tidak ada
function ambil_satu($sql, $tipe = "", ...$param) {
    $st = db_query($sql, $tipe, ...$param);
    return mysqli_fetch_assoc(mysqli_stmt_get_result($st)) ?: null;
}
