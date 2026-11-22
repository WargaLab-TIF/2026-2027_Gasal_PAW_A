<?php

function sanitazion($input)
{
  return htmlspecialchars(trim((string) $input), ENT_QUOTES, 'UTF-8');
}

function parseDate($value)
{
  if (empty($value)) {
    return null;
  }
  $date = DateTime::createFromFormat("Y-m-d", (string) $value);
  return ($date && $date->format("Y-m-d") === $value) ? $value : null;
}

function url($page = 'home', $params = [])
{
  $query = array_merge(['page' => $page], $params);
  return 'index.php?' . http_build_query($query);
}

function setFlash($type, $message)
{
  if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
  }
  $_SESSION['flash_' . $type] = $message;
}

function getFlash($type)
{
  if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
  }
  $key = 'flash_' . $type;
  if (isset($_SESSION[$key])) {
    $msg = $_SESSION[$key];
    unset($_SESSION[$key]);
    return $msg;
  }
  return null;
}
