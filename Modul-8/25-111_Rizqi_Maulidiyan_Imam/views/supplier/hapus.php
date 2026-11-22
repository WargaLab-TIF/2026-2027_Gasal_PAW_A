<?php
cekAkses([1]); 

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/supplier');
}

$id = (int)($_POST['id'] ?? 0);

try {
    $stmt = $DBH->prepare("DELETE FROM supplier WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        set_pesan('Supplier berhasil dihapus.');
    } else {
        set_pesan('Supplier tidak ditemukan.', 'danger');
    }
} catch (PDOException $err) {
    // gagal karena supplier masih dipakai di tabel barang (foreign key)
    set_pesan('Supplier tidak bisa dihapus karena masih memiliki data barang.', 'danger');
}

redirect('/supplier');
