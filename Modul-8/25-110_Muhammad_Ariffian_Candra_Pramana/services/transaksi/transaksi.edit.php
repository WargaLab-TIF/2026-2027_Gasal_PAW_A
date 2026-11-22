<?php

require_once __DIR__ . "/../../route.php";

if (($_SERVER["REQUEST_METHOD"] ?? "GET") !== "POST") {
  redirect("transaksi");
}

$id = (int) ($_GET["id"] ?? 0);
$waktu_transaksi = trim($_POST["waktu_transaksi"] ?? "");
$keterangan = trim($_POST["keterangan"] ?? "");
$pelanggan_id = trim($_POST["pelanggan_id"] ?? "");

if ($id <= 0) {
  redirect("transaksi", null, ["error" => "Data transaksi tidak ditemukan."]);
}

$tanggal = DateTime::createFromFormat("Y-m-d", $waktu_transaksi);

if (!$tanggal || $tanggal->format("Y-m-d") !== $waktu_transaksi) {
  redirect("transaksi", "edit", ["id" => $id, "error" => "Tanggal transaksi wajib diisi dengan format yang benar."]);
}

try {
  $check = $DBH->prepare("SELECT id FROM transaksi WHERE id = ?");
  $check->execute([$id]);

  if (!$check->fetch()) {
    redirect("transaksi", null, ["error" => "Data transaksi tidak ditemukan."]);
  }

  if ($pelanggan_id !== "") {
    $pelangganCheck = $DBH->prepare("SELECT id FROM pelanggan WHERE id = ?");
    $pelangganCheck->execute([$pelanggan_id]);

    if (!$pelangganCheck->fetch()) {
      redirect("transaksi", "edit", ["id" => $id, "error" => "Pelanggan yang dipilih tidak ditemukan."]);
    }
  } else {
    $pelanggan_id = null;
  }

  $stmt = $DBH->prepare("
    UPDATE transaksi
    SET waktu_transaksi = ?, keterangan = ?, pelanggan_id = ?
    WHERE id = ?
  ");
  $stmt->execute([$waktu_transaksi, $keterangan !== "" ? $keterangan : null, $pelanggan_id, $id]);

  redirect("transaksi", "detail", ["id" => $id, "status" => "updated"]);
} catch (PDOException $e) {
  redirect("transaksi", "edit", ["id" => $id, "error" => "Gagal memperbarui data transaksi."]);
}
