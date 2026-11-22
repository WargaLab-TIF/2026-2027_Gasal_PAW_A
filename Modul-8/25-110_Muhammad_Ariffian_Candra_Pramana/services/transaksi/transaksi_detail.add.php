<?php

require_once __DIR__ . "/../../route.php";

if (($_SERVER["REQUEST_METHOD"] ?? "GET") !== "POST") {
  redirect("transaksi");
}

$transaksi_id = filter_var($_POST["transaksi_id"] ?? "", FILTER_VALIDATE_INT);
$barang_id = filter_var($_POST["barang_id"] ?? "", FILTER_VALIDATE_INT);
$qty = filter_var($_POST["qty"] ?? "", FILTER_VALIDATE_INT);

if ($transaksi_id === false || $transaksi_id <= 0) {
  redirect("transaksi", null, ["error" => "Data transaksi tidak ditemukan."]);
}

if ($barang_id === false || $barang_id <= 0 || $qty === false || $qty <= 0) {
  redirect("transaksi", "detail", [
    "id" => $transaksi_id,
    "error" => "Barang dan quantity wajib diisi dengan benar.",
  ]);
}

try {
  $transaksiCheck = $DBH->prepare("SELECT id FROM transaksi WHERE id = ?");
  $transaksiCheck->execute([$transaksi_id]);

  if (!$transaksiCheck->fetch()) {
    redirect("transaksi", null, ["error" => "Data transaksi tidak ditemukan."]);
  }

  $barangCheck = $DBH->prepare("SELECT id, harga FROM barang WHERE id = ?");
  $barangCheck->execute([$barang_id]);
  $barang = $barangCheck->fetch();

  if (!$barang) {
    redirect("transaksi", "detail", [
      "id" => $transaksi_id,
      "error" => "Barang yang dipilih tidak ditemukan.",
    ]);
  }

  $detailCheck = $DBH->prepare("
    SELECT 1 FROM transaksi_detail WHERE transaksi_id = ? AND barang_id = ?
  ");
  $detailCheck->execute([$transaksi_id, $barang_id]);

  if ($detailCheck->fetch()) {
    redirect("transaksi", "detail", [
      "id" => $transaksi_id,
      "error" => "Barang tersebut sudah ada di transaksi ini. Hapus dulu itemnya jika ingin mengubah quantity.",
    ]);
  }

  $DBH->beginTransaction();

  $insert = $DBH->prepare("
    INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty)
    VALUES (?, ?, ?, ?)
  ");
  $insert->execute([$transaksi_id, $barang_id, (int) $barang["harga"], $qty]);

  $sum = $DBH->prepare("
    SELECT COALESCE(SUM(harga * qty), 0) FROM transaksi_detail WHERE transaksi_id = ?
  ");
  $sum->execute([$transaksi_id]);
  $total = (int) $sum->fetchColumn();

  $update = $DBH->prepare("UPDATE transaksi SET total = ? WHERE id = ?");
  $update->execute([$total, $transaksi_id]);

  $DBH->commit();

  redirect("transaksi", "detail", ["id" => $transaksi_id, "status" => "created"]);
} catch (PDOException $e) {
  if ($DBH->inTransaction()) {
    $DBH->rollBack();
  }

  redirect("transaksi", "detail", [
    "id" => $transaksi_id,
    "error" => "Gagal menambahkan item ke detail transaksi.",
  ]);
}
