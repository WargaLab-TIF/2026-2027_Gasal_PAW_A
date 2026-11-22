<?php

require_once __DIR__ . "/../../route.php";

if (($_SERVER["REQUEST_METHOD"] ?? "GET") !== "POST") {
  redirect("supplier");
}

$id = (int) ($_GET["id"] ?? 0);
$nama = trim($_POST["nama"] ?? "");
$telp = trim($_POST["telp"] ?? "");
$alamat = trim($_POST["alamat"] ?? "");

if ($id <= 0) {
  redirect("supplier", null, ["error" => "Data supplier tidak ditemukan."]);
}

if ($nama === "" || $telp === "" || $alamat === "") {
  redirect("supplier", "edit", ["id" => $id, "error" => "Semua field wajib diisi dengan benar."]);
}

try {
  $check = $DBH->prepare("SELECT id FROM supplier WHERE id = ?");
  $check->execute([$id]);

  if (!$check->fetch()) {
    redirect("supplier", null, ["error" => "Data supplier tidak ditemukan."]);
  }

  $stmt = $DBH->prepare("
    UPDATE supplier
    SET nama = ?, telp = ?, alamat = ?
    WHERE id = ?
  ");
  $stmt->execute([$nama, $telp, $alamat, $id]);

  redirect("supplier", null, ["status" => "updated"]);
} catch (PDOException $e) {
  redirect("supplier", "edit", ["id" => $id, "error" => "Gagal memperbarui data supplier."]);
}
