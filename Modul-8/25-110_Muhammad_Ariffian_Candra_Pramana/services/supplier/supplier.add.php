<?php

require_once __DIR__ . "/../../route.php";

if (($_SERVER["REQUEST_METHOD"] ?? "GET") !== "POST") {
  redirect("supplier");
}

$nama = trim($_POST["nama"] ?? "");
$telp = trim($_POST["telp"] ?? "");
$alamat = trim($_POST["alamat"] ?? "");

if ($nama === "" || $telp === "" || $alamat === "") {
  redirect("supplier", "add", ["error" => "Semua field wajib diisi dengan benar."]);
}

try {
  $stmt = $DBH->prepare("
    INSERT INTO supplier (nama, telp, alamat)
    VALUES (?, ?, ?)
  ");
  $stmt->execute([$nama, $telp, $alamat]);

  redirect("supplier", null, ["status" => "created"]);
} catch (PDOException $e) {
  redirect("supplier", "add", ["error" => "Gagal menyimpan data supplier."]);
}
