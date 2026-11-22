<?php
cekAkses([1]); // hanya owner

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/pelanggan');
}

$id = (int)($_POST['id'] ?? 0);

try {
    $stmt = $DBH->prepare("DELETE FROM pelanggan WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        set_pesan('Pelanggan berhasil dihapus.');
    } else {
        set_pesan('Pelanggan tidak ditemukan.', 'danger');
    }
} catch (PDOException $err) {
    // gagal karena pelanggan sudah punya transaksi (foreign key)
    set_pesan('Pelanggan tidak bisa dihapus karena sudah memiliki transaksi.', 'danger');
}

redirect('/pelanggan');
