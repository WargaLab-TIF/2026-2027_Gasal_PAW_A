
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function cekLogin()
{
    if (!isset($_SESSION['user_login'])) {
        header("Location: login.php");
        exit;
    }
}

function cekBelumLogin()
{
    if (isset($_SESSION['user_login'])) {
        header("Location: index.php");
        exit;
    }
}

function cekAkses($level_diizinkan)
{
    cekLogin();

    if (!in_array((int) $_SESSION['level'], $level_diizinkan, true)) {
        header("Location: index.php");
        exit;
    }
}
?>
