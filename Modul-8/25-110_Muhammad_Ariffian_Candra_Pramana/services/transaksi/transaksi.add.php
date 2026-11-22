<?php

require_once __DIR__ . "/../../route.php";

if (($_SERVER["REQUEST_METHOD"] ?? "GET") !== "POST") {
  redirect("transaksi");
}

$waktu_transaksi = trim($_POST["waktu_transaksi"] ?? "");
$keterangan = trim($_POST["keterangan"] ?? "");
$pelanggan_id = trim($_POST["pelanggan_id"] ?? "");

$tanggal = DateTime::createFromFormat("Y-m-d", $waktu_transaksi);

if (!$tanggal || $tanggal->format("Y-m-d") !== $waktu_transaksi) {
  redirect("transaksi", "add", ["error" => "Tanggal transaksi wajib diisi dengan format yang benar."]);
}

try {
  if ($pelanggan_id !== "") {
    $check = $DBH->prepare("SELECT id FROM pelanggan WHERE id = ?");
    $check->execute([$pelanggan_id]);

    if (!$check->fetch()) {
      redirect("transaksi", "add", ["error" => "Pelanggan yang dipilih tidak ditemukan."]);
    }
  } else {
    $pelanggan_id = null;
  }

  $stmt = $DBH->prepare("
    INSERT INTO transaksi (waktu_transaksi, keterangan, total, pelanggan_id)
    VALUES (?, ?, 0, ?)
  ");
  $stmt->execute([$waktu_transaksi, $keterangan !== "" ? $keterangan : null, $pelanggan_id]);

  $id = (int) $DBH->lastInsertId();

  redirect("transaksi", "detail", ["id" => $id, "status" => "created"]);
} catch (PDOException $e) {
  redirect("transaksi", "add", ["error" => "Gagal menyimpan data transaksi."]);
}
