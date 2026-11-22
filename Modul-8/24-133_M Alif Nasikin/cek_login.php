<?php
// Proteksi halaman: hanya user yang sudah login yang boleh lanjut.
include_once "fungsi.php";

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}
