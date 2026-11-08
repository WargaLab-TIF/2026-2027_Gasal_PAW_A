
<?php

require_once 'koneksi.php';

echo "<h3>TES KONEKSI DATABASE</h3>";

// 1. Tes koneksi
if ($DBH) {
    echo "1. Koneksi database berhasil!<br>";
}

// 2. Tes database yang digunakan
$db = $DBH->query("SELECT DATABASE()")->fetchColumn();

echo "2. Database aktif: " . $db . "<br>";

// 3. Tes keberadaan tabel
$check = $DBH->query("SHOW TABLES LIKE 'supplier'")->fetch();

if ($check) {
    echo "3. Tabel supplier ditemukan!<br>";
} else {
    die("3. Tabel supplier tidak ditemukan!");
}

// 4. Tes INSERT
try {

    $query = $DBH->prepare("
        INSERT INTO supplier (nama, telp, alamat)
        VALUES (?, ?, ?)
    ");

    $query->execute([
        'TEST SUPPLIER',
        '081234567890',
        'Alamat Percobaan'
    ]);

    echo "4. INSERT berhasil!<br>";
    echo "ID baru: " . $DBH->lastInsertId();

} catch (PDOException $e) {

    echo "4. INSERT gagal!<br>";
    echo "Pesan error: " . htmlspecialchars($e->getMessage());

}

?>
