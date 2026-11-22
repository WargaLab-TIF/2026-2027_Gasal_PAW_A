
<?php
require_once 'config.php';

// Data akun Owner dan Admin
$users = [
    [
        'nama' => 'Owner',
        'username' => 'owner',
        'password' => password_hash('owner123', PASSWORD_DEFAULT),
        'level' => 1
    ],
    [
        'nama' => 'Admin',
        'username' => 'admin',
        'password' => password_hash('admin123', PASSWORD_DEFAULT),
        'level' => 2
    ]
];

foreach ($users as $user) {
    // Cek apakah username sudah ada
    $cek = $DBH->prepare(
        "SELECT id FROM user WHERE username = ?"
    );
    $cek->execute([$user['username']]);

    if ($cek->rowCount() == 0) {
        $query = $DBH->prepare(
            "INSERT INTO user (nama, username, password, level)
             VALUES (?, ?, ?, ?)"
        );

        $query->execute([
            $user['nama'],
            $user['username'],
            $user['password'],
            $user['level']
        ]);

        echo "Akun " . $user['nama'] . " berhasil dibuat!<br>";
    } else {
        echo "Akun " . $user['nama'] . " sudah tersedia.<br>";
    }
}
?>
