<?php

function sanitazion($input)
{
  return htmlspecialchars(trim((string) $input), ENT_QUOTES, "UTF-8");
}

function baseUrl(string $path = "/", array $params = []): string
{
  $script = $_SERVER["SCRIPT_NAME"] ?? "/index.php";
  $url = rtrim($script, "/") . "/" . ltrim($path, "/");

  if ($params !== []) {
    $url .= "?" . http_build_query($params);
  }

  return $url;
}

function appUrl(string $page, ?string $action = null, array $params = []): string
{
  $path = str_starts_with($page, "/") ? $page : "/" . $page;

  if ($action !== null) {
    $path = rtrim($path, "/") . "/" . $action;
  }

  return baseUrl($path, $params);
}

function apiUrl(string $page, string $action, array $params = []): string
{
  return baseUrl("/api/" . trim($page, "/") . "/" . trim($action, "/"), $params);
}

function redirect(string $page, ?string $action = null, array $params = []): void
{
  header("Location: " . appUrl($page, $action, $params));
  exit;
}

function formatRupiah($value): string
{
  return "Rp " . number_format((int) $value, 0, ",", ".");
}

function formatTanggal($value, string $format = "d/m/Y"): string
{
  if (!$value) {
    return "-";
  }

  $time = strtotime((string) $value);

  return $time ? date($format, $time) : "-";
}

function jenisKelaminLabel($value): string
{
  return match ($value) {
    "L" => "Laki-laki",
    "P" => "Perempuan",
    default => "-",
  };
}

function levelLabel($level): string
{
  return match ((int) $level) {
    1 => "Owner",
    2 => "Kasir",
    default => "-",
  };
}
