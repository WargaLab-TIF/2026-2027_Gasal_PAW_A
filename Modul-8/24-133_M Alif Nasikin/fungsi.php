<?php
// Dipanggil oleh SEMUA halaman: memulai session dan menyediakan fungsi bantu.
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(["httponly" => true, "samesite" => "Lax"]);
    session_start();
}

// Halaman tidak boleh disimpan di cache browser,
// sehingga tombol Back setelah logout tidak menampilkan halaman lama.
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

// Mengamankan teks sebelum ditampilkan (mencegah XSS)
function e($teks) {
    return htmlspecialchars((string)$teks, ENT_QUOTES, "UTF-8");
}

// Menyimpan pesan sekali tampil (flash message)
function flash($tipe, $pesan) {
    $_SESSION["flash"] = [$tipe, $pesan];
}

function tampil_flash() {
    if (!isset($_SESSION["flash"])) {
        return "";
    }
    [$tipe, $pesan] = $_SESSION["flash"];
    unset($_SESSION["flash"]);
    return "<div class='alert $tipe'>" . e($pesan) . "</div>";
}

// Halaman / aksi yang hanya boleh dibuka owner (Data Master)
function halaman_owner($nama) {
    return preg_match('/^(barang|supplier|pelanggan|user)(_tambah|_edit|_hapus)?$/', $nama) === 1;
}

// Tolak jika user yang login bukan owner (level 1)
function wajib_owner() {
    if (($_SESSION["level"] ?? 0) != 1) {
        flash("danger", "Anda tidak memiliki hak akses ke halaman tersebut.");
        header("Location: index.php");
        exit;
    }
}
