<?php

try {
  require_once __DIR__ . "/conn.php";

  $path = $_SERVER['PATH_INFO'] ?? "/";

  switch ($path) {
    case '/':
      header("Location: " . $_SERVER['SCRIPT_NAME'] . "/reports");
      exit;

    // Reports
    case '/reports':
      require_once __DIR__ . "/views/reports/reports.php";
      break;
    case '/api/reports/export':
      require_once __DIR__ . "/services/reports/reports.export.php";
      break;

    default:
      require_once __DIR__ . "/views/404.php";
      break;
  }

  $conn = null;
} catch (PDOException $e) {
  echo $e->getMessage();
}
