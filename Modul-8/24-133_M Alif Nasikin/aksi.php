<?php
include_once "fungsi.php";
include_once "koneksi.php";

$aksi = $_GET["aksi"] ?? "";
if (!is_string($aksi)) {
    $aksi = "";
}

// Semua aksi selain login wajib dalam keadaan login
if ($aksi !== "login") {
    include "cek_login.php";
}

/* ===================== LOGIN ===================== */
if ($aksi == "login") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    // Validasi: tidak boleh kosong
    if ($username === "" || $password === "") {
        header("Location: login.php?error=kosong");
        exit;
    }

    // Cari user (prepared statement) lalu cocokkan password dengan hash
    $data = ambil_satu("SELECT * FROM user WHERE username=?", "s", $username);

    if ($data && password_verify($password, $data["password"])) {
        session_regenerate_id(true);          // cegah session fixation
        $_SESSION["login"] = true;
        $_SESSION["id"]    = $data["id"];
        $_SESSION["nama"]  = $data["nama"];
        $_SESSION["level"] = $data["level"];
        header("Location: index.php");
    } else {
        header("Location: login.php?error=salah");
    }
    exit;
}

/* ===================== LOGOUT ===================== */
elseif ($aksi == "logout") {
    $_SESSION = [];                           // hapus semua data session
    if (ini_get("session.use_cookies")) {     // hapus cookie session di browser
        $c = session_get_cookie_params();
        setcookie(session_name(), "", time() - 42000, $c["path"], $c["domain"], $c["secure"], $c["httponly"]);
    }
    session_destroy();
    header("Location: login.php?logout=1");
    exit;
}

/* ============ CRUD BARANG / SUPPLIER / PELANGGAN ============ */
elseif (preg_match('/^(barang|supplier|pelanggan)_(tambah|edit|hapus)$/', $aksi, $m)) {
    wajib_owner();
    include_once "master.php";

    $tabel = $m[1];
    $mode  = $m[2];
    $kolom = $MASTER[$tabel]["kolom"];
    $id    = (int)($_GET["id"] ?? 0);

    // ----- Hapus -----
    if ($mode == "hapus") {
        db_query("DELETE FROM $tabel WHERE id=?", "i", (int)($_POST["id"] ?? 0));
        flash("success", "Data berhasil dihapus.");
        header("Location: index.php?p=$tabel");
        exit;
    }

    // ----- Ambil & validasi input -----
    $nilai = [];
    $error = "";
    foreach ($kolom as $nama => $info) {
        $v = trim($_POST[$nama] ?? "");
        if ($error === "" && $info[2] && $v === "") {
            $error = $info[0] . " wajib diisi.";
        } elseif ($error === "" && $info[1] == "number" && !ctype_digit($v)) {
            $error = $info[0] . " harus berupa angka (0 atau lebih).";
        }
        $nilai[] = $v;
    }

    if ($error !== "") {
        flash("danger", $error);
        header("Location: index.php?p=" . $tabel . ($mode == "edit" ? "_edit&id=$id" : "_tambah"));
        exit;
    }

    $daftar = array_keys($kolom);
    $tipe   = str_repeat("s", count($daftar));

    if ($mode == "tambah") {
        $tanda = implode(",", array_fill(0, count($daftar), "?"));
        db_query("INSERT INTO $tabel (" . implode(",", $daftar) . ") VALUES ($tanda)", $tipe, ...$nilai);
        flash("success", "Data berhasil ditambahkan.");
    } else {
        $set = implode(",", array_map(fn($k) => "$k=?", $daftar));
        $nilai[] = $id;
        db_query("UPDATE $tabel SET $set WHERE id=?", $tipe . "i", ...$nilai);
        flash("success", "Data berhasil diperbarui.");
    }
    header("Location: index.php?p=$tabel");
    exit;
}

/* ===================== CRUD USER ===================== */
elseif (preg_match('/^user_(tambah|edit|hapus)$/', $aksi, $m)) {
    wajib_owner();
    $mode = $m[1];

    // ----- Hapus -----
    if ($mode == "hapus") {
        $idHapus = (int)($_POST["id"] ?? 0);
        if ($idHapus == $_SESSION["id"]) {
            flash("danger", "Akun yang sedang dipakai login tidak dapat dihapus.");
        } else {
            db_query("DELETE FROM user WHERE id=?", "i", $idHapus);
            flash("success", "User berhasil dihapus.");
        }
        header("Location: index.php?p=user");
        exit;
    }

    // ----- Ambil & validasi input -----
    $id       = (int)($_GET["id"] ?? 0);
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";
    $nama     = trim($_POST["nama_lengkap"] ?? "");
    $level    = $_POST["level"] ?? "";

    $error = "";
    if ($username === "" || $nama === "") {
        $error = "Username dan nama lengkap wajib diisi.";
    } elseif ($mode == "tambah" && $password === "") {
        $error = "Password wajib diisi.";
    } elseif ($level !== "1" && $level !== "2") {
        $error = "Level harus 1 (Owner) atau 2 (Kasir).";
    } elseif (ambil_satu("SELECT id FROM user WHERE username=? AND id<>?", "si", $username, $id)) {
        $error = "Username \"$username\" sudah dipakai.";
    } elseif ($mode == "edit" && $id == $_SESSION["id"] && $level != 1) {
        $error = "Owner tidak dapat menurunkan level akunnya sendiri.";
    }

    if ($error !== "") {
        flash("danger", $error);
        header("Location: index.php?p=" . ($mode == "edit" ? "user_edit&id=$id" : "user_tambah"));
        exit;
    }

    $level = (int)$level;

    if ($mode == "tambah") {
        $hash = password_hash($password, PASSWORD_DEFAULT);   // password disimpan sebagai hash
        db_query("INSERT INTO user (username, password, nama, level) VALUES (?,?,?,?)",
                 "sssi", $username, $hash, $nama, $level);
        flash("success", "User berhasil ditambahkan.");
    } else {
        if ($password !== "") {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            db_query("UPDATE user SET username=?, password=?, nama=?, level=? WHERE id=?",
                     "sssii", $username, $hash, $nama, $level, $id);
        } else {
            db_query("UPDATE user SET username=?, nama=?, level=? WHERE id=?",
                     "ssii", $username, $nama, $level, $id);
        }
        if ($id == $_SESSION["id"]) {
            $_SESSION["nama"] = $nama;    // nama di navbar ikut berubah
        }
        flash("success", "User berhasil diperbarui.");
    }
    header("Location: index.php?p=user");
    exit;
}

/* ===================== TRANSAKSI ===================== */
elseif ($aksi == "transaksi") {
    $tanggal = trim($_POST["tanggal_transaksi"] ?? "");
    $total   = trim($_POST["total_pembayaran"] ?? "");
    $plg     = trim($_POST["pelanggan_id"] ?? "");

    $error = "";
    if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $tanggal, $t) || !checkdate((int)$t[2], (int)$t[3], (int)$t[1])) {
        $error = "Tanggal transaksi tidak valid.";
    } elseif (!ctype_digit($total) || (int)$total < 1) {
        $error = "Total pembayaran harus berupa angka lebih dari 0.";
    } elseif ($plg !== "" && !ambil_satu("SELECT id FROM pelanggan WHERE id=?", "i", (int)$plg)) {
        $error = "Pelanggan tidak ditemukan.";
    }

    if ($error !== "") {
        flash("danger", $error);
        header("Location: index.php?p=transaksi");
        exit;
    }

    $pelangganId = ($plg === "") ? null : (int)$plg;
    $total       = (int)$total;
    db_query("INSERT INTO transaksi (tanggal, pelanggan_id, total) VALUES (?,?,?)",
             "sii", $tanggal, $pelangganId, $total);

    flash("success", "Transaksi berhasil disimpan.");
    header("Location: index.php?p=laporan");
    exit;
}

/* ===================== AKSI TIDAK DIKENAL ===================== */
else {
    header("Location: index.php");
    exit;
}
