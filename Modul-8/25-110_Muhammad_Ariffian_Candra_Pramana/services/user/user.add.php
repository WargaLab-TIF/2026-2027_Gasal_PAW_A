<?php

require_once __DIR__ . "/../../route.php";

if (($_SERVER["REQUEST_METHOD"] ?? "GET") !== "POST") {
  redirect("user");
}

$username = trim($_POST["username"] ?? "");
$password = $_POST["password"] ?? "";
$nama = trim($_POST["nama"] ?? "");
$alamat = trim($_POST["alamat"] ?? "");
$hp = trim($_POST["hp"] ?? "");
$level = filter_var($_POST["level"] ?? "", FILTER_VALIDATE_INT);

if (
  $username === "" ||
  strlen($password) < 4 ||
  $nama === "" ||
  $alamat === "" ||
  $hp === ""
) {
  redirect("user", "add", ["error" => "Semua field wajib diisi dengan benar. Password minimal 4 karakter."]);
}

if (!in_array($level, [1, 2], true)) {
  redirect("user", "add", ["error" => "Level user harus Owner atau Kasir."]);
}

try {
  $check = $DBH->prepare("SELECT id_user FROM `user` WHERE username = ?");
  $check->execute([$username]);

  if ($check->fetch()) {
    redirect("user", "add", ["error" => "Username sudah digunakan. Silakan pilih username lain."]);
  }

  $stmt = $DBH->prepare("
    INSERT INTO `user` (username, password, nama, alamat, hp, level)
    VALUES (?, ?, ?, ?, ?, ?)
  ");
  $stmt->execute([$username, md5($password), $nama, $alamat, $hp, $level]);

  redirect("user", null, ["status" => "created"]);
} catch (PDOException $e) {
  redirect("user", "add", ["error" => "Gagal menyimpan data user."]);
}
