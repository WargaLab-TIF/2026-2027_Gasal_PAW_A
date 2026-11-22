<?php

require_once __DIR__ . "/../../route.php";

if (($_SERVER["REQUEST_METHOD"] ?? "GET") !== "POST") {
  redirect("user");
}

$id = (int) ($_GET["id"] ?? 0);
$username = trim($_POST["username"] ?? "");
$password = $_POST["password"] ?? "";
$nama = trim($_POST["nama"] ?? "");
$alamat = trim($_POST["alamat"] ?? "");
$hp = trim($_POST["hp"] ?? "");
$level = filter_var($_POST["level"] ?? "", FILTER_VALIDATE_INT);

if ($id <= 0) {
  redirect("user", null, ["error" => "Data user tidak ditemukan."]);
}

if (
  $username === "" ||
  ($password !== "" && strlen($password) < 4) ||
  $nama === "" ||
  $alamat === "" ||
  $hp === ""
) {
  redirect("user", "edit", ["id" => $id, "error" => "Semua field wajib diisi dengan benar. Password minimal 4 karakter."]);
}

if (!in_array($level, [1, 2], true)) {
  redirect("user", "edit", ["id" => $id, "error" => "Level user harus Owner atau Kasir."]);
}

try {
  $check = $DBH->prepare("SELECT id_user FROM `user` WHERE id_user = ?");
  $check->execute([$id]);

  if (!$check->fetch()) {
    redirect("user", null, ["error" => "Data user tidak ditemukan."]);
  }

  $usernameCheck = $DBH->prepare("SELECT id_user FROM `user` WHERE username = ? AND id_user <> ?");
  $usernameCheck->execute([$username, $id]);

  if ($usernameCheck->fetch()) {
    redirect("user", "edit", ["id" => $id, "error" => "Username sudah digunakan. Silakan pilih username lain."]);
  }

  if ($password !== "") {
    $stmt = $DBH->prepare("
      UPDATE `user`
      SET username = ?, password = ?, nama = ?, alamat = ?, hp = ?, level = ?
      WHERE id_user = ?
    ");
    $stmt->execute([$username, md5($password), $nama, $alamat, $hp, $level, $id]);
  } else {
    $stmt = $DBH->prepare("
      UPDATE `user`
      SET username = ?, nama = ?, alamat = ?, hp = ?, level = ?
      WHERE id_user = ?
    ");
    $stmt->execute([$username, $nama, $alamat, $hp, $level, $id]);
  }

  redirect("user", null, ["status" => "updated"]);
} catch (PDOException $e) {
  redirect("user", "edit", ["id" => $id, "error" => "Gagal memperbarui data user."]);
}
