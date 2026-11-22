<?php
cekAkses([1]);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/barang');
}

$id = (int)($_POST['id'] ?? 0);

try {
    $stmt = $DBH->prepare("DELETE FROM barang WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        set_pesan('Barang berhasil dihapus.');
    } else {
        set_pesan('Barang tidak ditemukan.', 'danger');
    }
} catch (PDOException $err) {
    set_pesan('Barang tidak bisa dihapus karena sudah dipakai di transaksi.', 'danger');
}

redirect('/barang');