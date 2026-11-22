<?php

require_once __DIR__ . "/../../route.php";

$transaksi_id = (int) ($_GET["id"] ?? 0);
$barang_id = (int) ($_GET["barang_id"] ?? 0);

if ($transaksi_id <= 0) {
  redirect("transaksi", null, ["error" => "Data transaksi tidak ditemukan."]);
}

if ($barang_id <= 0) {
  redirect("transaksi", "detail", [
    "id" => $transaksi_id,
    "error" => "Item detail yang ingin dihapus tidak ditemukan.",
  ]);
}

try {
  $DBH->beginTransaction();

  $hapus = $DBH->prepare("
    DELETE FROM transaksi_detail WHERE transaksi_id = ? AND barang_id = ?
  ");
  $hapus->execute([$transaksi_id, $barang_id]);

  if ($hapus->rowCount() === 0) {
    $DBH->rollBack();

    redirect("transaksi", "detail", [
      "id" => $transaksi_id,
      "error" => "Item detail yang ingin dihapus tidak ditemukan.",
    ]);
  }

  $sum = $DBH->prepare("
    SELECT COALESCE(SUM(harga * qty), 0) FROM transaksi_detail WHERE transaksi_id = ?
  ");
  $sum->execute([$transaksi_id]);
  $total = (int) $sum->fetchColumn();

  $update = $DBH->prepare("UPDATE transaksi SET total = ? WHERE id = ?");
  $update->execute([$total, $transaksi_id]);

  $DBH->commit();

  redirect("transaksi", "detail", ["id" => $transaksi_id, "status" => "deleted"]);
} catch (PDOException $e) {
  if ($DBH->inTransaction()) {
    $DBH->rollBack();
  }

  redirect("transaksi", "detail", [
    "id" => $transaksi_id,
    "error" => "Gagal menghapus item dari detail transaksi.",
  ]);
}
