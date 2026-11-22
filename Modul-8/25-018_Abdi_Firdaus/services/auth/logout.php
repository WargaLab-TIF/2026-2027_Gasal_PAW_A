<?php
// proses logout

ensureSession();

// kosongkan dan hapus session lama
$_SESSION = [];
session_unset();

session_destroy();

// session baru untuk pesan logout
session_start();
session_regenerate_id(true);
setFlash('info', 'Anda berhasil logout dari Sistem Penjualan.');

header("Location: " . routeUrl("/login"));
exit();
