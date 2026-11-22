<?php

require_once __DIR__ . "/../../route.php";

$id = trim((string) ($_GET["id"] ?? ""));

if ($id === "") {
  redirect("pelanggan", null, ["error" => "Data pelanggan tidak ditemukan."]);
}

try {
  $stmt = $DBH->prepare("DELETE FROM pelanggan WHERE id = ?");
  $stmt->execute([$id]);

  if ($stmt->rowCount() === 0) {
    redirect("pelanggan", null, ["error" => "Data pelanggan tidak ditemukan."]);
  }

  redirect("pelanggan", null, ["status" => "deleted"]);
} catch (PDOException $e) {
  redirect("pelanggan", null, [
    "error" => "Data pelanggan tidak bisa dihapus karena masih digunakan oleh transaksi.",
  ]);
}
