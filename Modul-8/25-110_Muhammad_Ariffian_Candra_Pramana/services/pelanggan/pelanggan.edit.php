<?php

require_once __DIR__ . "/../../route.php";

if (($_SERVER["REQUEST_METHOD"] ?? "GET") !== "POST") {
  redirect("pelanggan");
}

$id = trim((string) ($_GET["id"] ?? ""));
$nama = trim($_POST["nama"] ?? "");
$jenis_kelamin = $_POST["jenis_kelamin"] ?? "";
$telp = trim($_POST["telp"] ?? "");
$alamat = trim($_POST["alamat"] ?? "");

if ($id === "") {
  redirect("pelanggan", null, ["error" => "Data pelanggan tidak ditemukan."]);
}

if ($nama === "" || !in_array($jenis_kelamin, ["L", "P"], true) || $telp === "" || $alamat === "") {
  redirect("pelanggan", "edit", ["id" => $id, "error" => "Semua field wajib diisi dengan benar."]);
}

try {
  $check = $DBH->prepare("SELECT id FROM pelanggan WHERE id = ?");
  $check->execute([$id]);

  if (!$check->fetch()) {
    redirect("pelanggan", null, ["error" => "Data pelanggan tidak ditemukan."]);
  }

  $stmt = $DBH->prepare("
    UPDATE pelanggan
    SET nama = ?, jenis_kelamin = ?, telp = ?, alamat = ?
    WHERE id = ?
  ");
  $stmt->execute([$nama, $jenis_kelamin, $telp, $alamat, $id]);

  redirect("pelanggan", null, ["status" => "updated"]);
} catch (PDOException $e) {
  redirect("pelanggan", "edit", ["id" => $id, "error" => "Gagal memperbarui data pelanggan."]);
}
