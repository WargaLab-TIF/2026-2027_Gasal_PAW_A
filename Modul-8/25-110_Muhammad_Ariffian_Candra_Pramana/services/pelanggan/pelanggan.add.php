<?php

require_once __DIR__ . "/../../route.php";

if (($_SERVER["REQUEST_METHOD"] ?? "GET") !== "POST") {
  redirect("pelanggan");
}

$nama = trim($_POST["nama"] ?? "");
$jenis_kelamin = $_POST["jenis_kelamin"] ?? "";
$telp = trim($_POST["telp"] ?? "");
$alamat = trim($_POST["alamat"] ?? "");

if ($nama === "" || !in_array($jenis_kelamin, ["L", "P"], true) || $telp === "" || $alamat === "") {
  redirect("pelanggan", "add", ["error" => "Semua field wajib diisi dengan benar."]);
}

try {
  // Tabel pelanggan memakai id varchar (contoh: PLG01), sehingga id dibuat manual.
  $next = $DBH->query("
    SELECT COALESCE(MAX(CAST(SUBSTRING(id, 4) AS UNSIGNED)), 0) + 1 AS next_id
    FROM pelanggan
    WHERE id LIKE 'PLG%'
  ")->fetch();

  $id = "PLG" . str_pad((string) $next["next_id"], 2, "0", STR_PAD_LEFT);

  $stmt = $DBH->prepare("
    INSERT INTO pelanggan (id, nama, jenis_kelamin, telp, alamat)
    VALUES (?, ?, ?, ?, ?)
  ");
  $stmt->execute([$id, $nama, $jenis_kelamin, $telp, $alamat]);

  redirect("pelanggan", null, ["status" => "created"]);
} catch (PDOException $e) {
  redirect("pelanggan", "add", ["error" => "Gagal menyimpan data pelanggan."]);
}
