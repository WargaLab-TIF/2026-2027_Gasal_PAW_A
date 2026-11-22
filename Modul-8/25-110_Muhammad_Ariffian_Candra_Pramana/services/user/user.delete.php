<?php

require_once __DIR__ . "/../../route.php";

$id = (int) ($_GET["id"] ?? 0);

if ($id <= 0) {
  redirect("user", null, ["error" => "Data user tidak ditemukan."]);
}

try {
  $stmt = $DBH->prepare("DELETE FROM `user` WHERE id_user = ?");
  $stmt->execute([$id]);

  if ($stmt->rowCount() === 0) {
    redirect("user", null, ["error" => "Data user tidak ditemukan."]);
  }

  redirect("user", null, ["status" => "deleted"]);
} catch (PDOException $e) {
  redirect("user", null, ["error" => "Gagal menghapus data user."]);
}
