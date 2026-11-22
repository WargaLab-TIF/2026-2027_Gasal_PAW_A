<?php

require_once __DIR__ . "/../../route.php";

if (($_SERVER["REQUEST_METHOD"] ?? "GET") !== "POST") {
  redirect("barang");
}

$id = (int) ($_GET["id"] ?? 0);
$kode_barang = trim($_POST["kode_barang"] ?? "");
$nama_barang = trim($_POST["nama_barang"] ?? "");
$harga = filter_var($_POST["harga"] ?? "", FILTER_VALIDATE_INT);
$stok = filter_var($_POST["stok"] ?? "", FILTER_VALIDATE_INT);
$supplier_id = filter_var($_POST["supplier_id"] ?? "", FILTER_VALIDATE_INT);

if ($id <= 0) {
  redirect("barang", null, ["error" => "Data barang tidak ditemukan."]);
}

if (
  $kode_barang === "" ||
  $nama_barang === "" ||
  $harga === false || $harga < 0 ||
  $stok === false || $stok < 0 ||
  $supplier_id === false || $supplier_id <= 0
) {
  redirect("barang", "edit", ["id" => $id, "error" => "Semua field wajib diisi dengan benar."]);
}

try {
  $check = $DBH->prepare("SELECT id FROM barang WHERE id = ?");
  $check->execute([$id]);

  if (!$check->fetch()) {
    redirect("barang", null, ["error" => "Data barang tidak ditemukan."]);
  }

  $supplierCheck = $DBH->prepare("SELECT id FROM supplier WHERE id = ?");
  $supplierCheck->execute([$supplier_id]);

  if (!$supplierCheck->fetch()) {
    redirect("barang", "edit", ["id" => $id, "error" => "Supplier yang dipilih tidak ditemukan."]);
  }

  $kodeCheck = $DBH->prepare("SELECT id FROM barang WHERE kode_barang = ? AND id <> ?");
  $kodeCheck->execute([$kode_barang, $id]);

  if ($kodeCheck->fetch()) {
    redirect("barang", "edit", ["id" => $id, "error" => "Kode barang sudah digunakan. Silakan gunakan kode lain."]);
  }

  $stmt = $DBH->prepare("
    UPDATE barang
    SET kode_barang = ?, nama_barang = ?, harga = ?, stok = ?, supplier_id = ?
    WHERE id = ?
  ");
  $stmt->execute([$kode_barang, $nama_barang, $harga, $stok, $supplier_id, $id]);

  redirect("barang", null, ["status" => "updated"]);
} catch (PDOException $e) {
  redirect("barang", "edit", ["id" => $id, "error" => "Gagal memperbarui data barang."]);
}
