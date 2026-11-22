<?php

require_once __DIR__ . "/../../route.php";

$id = (int) ($_GET["id"] ?? 0);

if ($id <= 0) {
  redirect("transaksi", null, ["error" => "Data transaksi tidak ditemukan."]);
}

try {
  $check = $DBH->prepare("SELECT id FROM transaksi WHERE id = ?");
  $check->execute([$id]);

  if (!$check->fetch()) {
    redirect("transaksi", null, ["error" => "Data transaksi tidak ditemukan."]);
  }

  $bayarCheck = $DBH->prepare("SELECT COUNT(*) FROM pembayaran WHERE transaksi_id = ?");
  $bayarCheck->execute([$id]);

  if ((int) $bayarCheck->fetchColumn() > 0) {
    redirect("transaksi", null, [
      "error" => "Transaksi tidak bisa dihapus karena sudah memiliki data pembayaran.",
    ]);
  }

  $DBH->beginTransaction();

  $hapusDetail = $DBH->prepare("DELETE FROM transaksi_detail WHERE transaksi_id = ?");
  $hapusDetail->execute([$id]);

  $hapusTransaksi = $DBH->prepare("DELETE FROM transaksi WHERE id = ?");
  $hapusTransaksi->execute([$id]);

  $DBH->commit();

  redirect("transaksi", null, ["status" => "deleted"]);
} catch (PDOException $e) {
  if ($DBH->inTransaction()) {
    $DBH->rollBack();
  }

  redirect("transaksi", null, ["error" => "Gagal menghapus data transaksi."]);
}
