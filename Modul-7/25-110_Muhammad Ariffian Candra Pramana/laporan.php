<?php

// Entry point, perannya sama seperti index.php pada modul7-reporting:
// memuat koneksi + helper, lalu memanggil view laporan.
require_once __DIR__ . "/koneksi.php";
require_once __DIR__ . "/utils.php";

require_once __DIR__ . "/views/reports/reports.php";
