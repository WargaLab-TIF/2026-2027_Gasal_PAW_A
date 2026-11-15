<?php
// ==========================================================
// FILE FUNGSI BANTU (HELPER): utils.php
// ==========================================================

// Fungsi untuk membersihkan input/output dari spasi berlebih dan karakter HTML berbahaya (XSS)
function sanitazion($input)
{
  return htmlspecialchars(trim((string) $input));
}

// Fungsi untuk membentuk URL rute yang kompatibel di PHP Built-in Server maupun Apache XAMPP
function routeUrl($path)
{
  if (PHP_SAPI === "cli-server") {
    return $path;
  }
  $scriptName = $_SERVER["SCRIPT_NAME"] ?? "";
  return $path === "/" ? $scriptName : $scriptName . $path;
}
