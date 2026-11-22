<?php

require_once __DIR__ . "/../../route.php";

if (($_SERVER["REQUEST_METHOD"] ?? "GET") !== "POST") {
  redirect("barang");
}

$kode_barang = trim($_POST["kode_barang"] ?? "");
$nama_barang = trim($_POST["nama_barang"] ?? "");
$harga = filter_var($_POST["harga"] ?? "", FILTER_VALIDATE_INT);
$stok = filter_var($_POST["stok"] ?? "", FILTER_VALIDATE_INT);
$supplier_id = filter_var($_POST["supplier_id"] ?? "", FILTER_VALIDATE_INT);

if (
  $kode_barang === "" ||
  $nama_barang === "" ||
  $harga === false || $harga < 0 ||
  $stok === false || $stok < 0 ||
  $supplier_id === false || $supplier_id <= 0
) {
  redirect("barang", "add", ["error" => "Semua field wajib diisi dengan benar."]);
}

try {
  $check = $DBH->prepare("SELECT id FROM supplier WHERE id = ?");
  $check->execute([$supplier_id]);

  if (!$check->fetch()) {
    redirect("barang", "add", ["error" => "Supplier yang dipilih tidak ditemukan."]);
  }

  $kodeCheck = $DBH->prepare("SELECT id FROM barang WHERE kode_barang = ?");
  $kodeCheck->execute([$kode_barang]);

  if ($kodeCheck->fetch()) {
    redirect("barang", "add", ["error" => "Kode barang sudah digunakan. Silakan gunakan kode lain."]);
  }

  $stmt = $DBH->prepare("
    INSERT INTO barang (kode_barang, nama_barang, harga, stok, supplier_id)
    VALUES (?, ?, ?, ?, ?)
  ");
  $stmt->execute([$kode_barang, $nama_barang, $harga, $stok, $supplier_id]);

  redirect("barang", null, ["status" => "created"]);
} catch (PDOException $e) {
  redirect("barang", "add", ["error" => "Gagal menyimpan data barang."]);
}
