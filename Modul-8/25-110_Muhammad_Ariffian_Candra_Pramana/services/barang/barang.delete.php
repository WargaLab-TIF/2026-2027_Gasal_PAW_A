<?php

require_once __DIR__ . "/../../route.php";

$id = (int) ($_GET["id"] ?? 0);

if ($id <= 0) {
  redirect("barang", null, ["error" => "Data barang tidak ditemukan."]);
}

try {
  $stmt = $DBH->prepare("DELETE FROM barang WHERE id = ?");
  $stmt->execute([$id]);

  if ($stmt->rowCount() === 0) {
    redirect("barang", null, ["error" => "Data barang tidak ditemukan."]);
  }

  redirect("barang", null, ["status" => "deleted"]);
} catch (PDOException $e) {
  redirect("barang", null, [
    "error" => "Data barang tidak bisa dihapus karena masih digunakan oleh transaksi.",
  ]);
}
