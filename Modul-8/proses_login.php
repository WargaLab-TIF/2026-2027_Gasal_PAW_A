<?php
session_start();
require 'koneksi.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password']; 
    
    // Asumsi password di tabel user tidak di-hash (kalau pakai MD5, ganti jadi md5($password))
    $stmt = $conn->prepare("SELECT * FROM user WHERE username = :username AND password = :password");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();
    
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($data) {
        // Set session
        $_SESSION['username'] = $data['username'];
        $_SESSION['level'] = $data['level']; // 1 untuk owner, 2 untuk kasir
        
        header("Location: index.php");
    } else {
        // Jika gagal, pakai alert javascript sederhana ala praktikum
        echo "<script>
                alert('Username atau Password salah!');
                window.location='login.php';
              </script>";
    }
}
?>