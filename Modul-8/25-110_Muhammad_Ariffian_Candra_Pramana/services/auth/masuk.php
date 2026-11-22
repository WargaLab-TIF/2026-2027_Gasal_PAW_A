<?php
$username = $_POST['username'] ?? '';
$password = md5($_POST['password'] ?? '');

$stmt = $DBH->prepare("SELECT * FROM user WHERE username = ? AND password = ?");
$stmt->execute([$username, $password]);

$user_data = $stmt->fetch();

if($user_data && count($user_data) > 0) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['user_login'] = $user_data['nama'];
    $_SESSION['user_level'] = (int) $user_data['level'];
    $tujuan = $_SESSION['user_level'] === 1 ? "pelanggan" : "transaksi";
    header("Location: ". appUrl($tujuan));
} else {
    header("Location: ". appUrl("masuk"));
}
exit;
?>