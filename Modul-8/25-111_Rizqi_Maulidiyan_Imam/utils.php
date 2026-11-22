<?php

function bersih($input)
{
    return trim($input);
}

function e($teks)
{
    return htmlspecialchars((string)$teks);
}

function url($path = '')
{
    return str_replace(' ', '%20', $_SERVER['SCRIPT_NAME']) . $path;
}

function redirect($path)
{
    header('Location: ' . url($path));
    exit;
}
function cekAkses($levelBoleh = null)
{
    if (!isset($_SESSION['login'])) {
        redirect('/login');
    }
    if ($levelBoleh !== null && !in_array($_SESSION['level'], $levelBoleh)) {
        $_SESSION['error'] = 'Anda tidak memiliki hak akses ke halaman tersebut.';
        redirect('/home');
    }
}

function set_pesan($teks, $tipe = 'success')
{
    $_SESSION['pesan'] = ['teks' => $teks, 'tipe' => $tipe];
}

function tampil_pesan()
{
    if (isset($_SESSION['pesan'])) {
        $p = $_SESSION['pesan'];
        unset($_SESSION['pesan']);
        echo '<div class="alert alert-' . e($p['tipe']) . '">' . e($p['teks']) . '</div>';
    }
}