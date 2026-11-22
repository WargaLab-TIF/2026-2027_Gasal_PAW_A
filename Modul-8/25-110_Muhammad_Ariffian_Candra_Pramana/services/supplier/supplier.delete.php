<?php

require_once __DIR__ . "/../../route.php";

$id = (int) ($_GET["id"] ?? 0);

if ($id <= 0) {
  redirect("supplier", null, ["error" => "Data supplier tidak ditemukan."]);
}

try {
  $stmt = $DBH->prepare("DELETE FROM supplier WHERE id = ?");
  $stmt->execute([$id]);

  if ($stmt->rowCount() === 0) {
    redirect("supplier", null, ["error" => "Data supplier tidak ditemukan."]);
  }

  redirect("supplier", null, ["status" => "deleted"]);
} catch (PDOException $e) {
  redirect("supplier", null, [
    "error" => "Data supplier tidak bisa dihapus karena masih digunakan oleh barang.",
  ]);
}
