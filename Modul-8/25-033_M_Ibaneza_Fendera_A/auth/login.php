<?php
session_start();
require '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $DBH->prepare("SELECT * FROM user WHERE username = ? AND password = ?");
    $stmt->execute([$username, $password]);
    $user_data = $stmt->fetch();

    if ($user_data) {
        $_SESSION['status'] = "login";
        $_SESSION['nama'] = $user_data['nama'];
        $_SESSION['level']  = $user_data['level'];

        if ($user_data['level'] == 1) {
            header('location: ../owner/home.php');
        } else {
            header('location: ../kasir/home.php');
        }
        exit();
    } else {
        header('location: ../index.php');
        exit();
    }
} else {
    header('location: ../index.php');
    exit();
}
?>