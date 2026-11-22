
<?php

require_once __DIR__ . '/../auth.php';

wajibLogin();

$error = '';

$message = '';

// Mengambil data pelanggan
$pelanggan = $DBH->query(
    'SELECT id, nama_pelanggan
     FROM pelanggan
     ORDER BY nama_pelanggan'
)->fetchAll();

// Mengambil barang yang stoknya tersedia
$barang = $DBH->query(
    'SELECT id, kode_barang, nama_barang, harga, stok
     FROM barang
     WHERE stok > 0
     ORDER BY nama_barang'
)->fetchAll();


// PROSES SIMPAN TRANSAKSI
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    cekCsrf();

    $pelangganId = (int)($_POST['pelanggan_id'] ?? 0);

    $barangId = (int)($_POST['barang_id'] ?? 0);

    $qty = (int)($_POST['qty'] ?? 0);

    if ($barangId < 1 || $qty < 1) {

        $error = 'Pilih barang dan masukkan jumlah minimal 1.';

    } else {

        try {

            $DBH->beginTransaction();

            // Mengambil harga dan stok barang
            $stmt = $DBH->prepare(
                'SELECT id, harga, stok
                 FROM barang
                 WHERE id = ?
                 FOR UPDATE'
            );

            $stmt->execute([$barangId]);

            $item = $stmt->fetch();

            // Memeriksa stok barang
            if (!$item || (int)$item['stok'] < $qty) {

                throw new RuntimeException(
                    'Stok barang tidak mencukupi.'
                );

            }

            // Menghitung total harga
            $total = (float)$item['harga'] * $qty;

            // Menyimpan transaksi utama
            $stmt = $DBH->prepare(
                'INSERT INTO transaksi
                (pelanggan_id, user_id, waktu_transaksi, total)
                VALUES (?, ?, NOW(), ?)'
            );

            $stmt->execute([

                $pelangganId ?: null,

                $_SESSION['user_id'],

                $total

            ]);

            $transaksiId = (int)$DBH->lastInsertId();


            // Menyimpan detail transaksi
            $stmt = $DBH->prepare(
                'INSERT INTO transaksi_detail
                (transaksi_id, barang_id, harga, qty, subtotal)
                VALUES (?, ?, ?, ?, ?)'
            );

            $stmt->execute([

                $transaksiId,

                $barangId,

                $item['harga'],

                $qty,

                $total

            ]);


            // Mengurangi stok barang
            $stmt = $DBH->prepare(
                'UPDATE barang
                 SET stok = stok - ?
                 WHERE id = ?'
            );

            $stmt->execute([$qty, $barangId]);


            // Menyimpan pembayaran
            $stmt = $DBH->prepare(
                'INSERT INTO pembayaran
                (transaksi_id, jumlah_bayar, metode)
                VALUES (?, ?, ?)'
            );

            $stmt->execute([

                $transaksiId,

                $total,

                'Tunai'

            ]);


            // Menyelesaikan transaksi database
            $DBH->commit();

            $message = "Transaksi berhasil disimpan dengan ID #{$transaksiId}.";

            // Memperbarui daftar barang
            $barang = $DBH->query(
                'SELECT id, kode_barang, nama_barang, harga, stok
                 FROM barang
                 WHERE stok > 0
                 ORDER BY nama_barang'
            )->fetchAll();

        } catch (Throwable $ex) {

            if ($DBH->inTransaction()) {

                $DBH->rollBack();

            }

            $error = $ex instanceof RuntimeException
                ? $ex->getMessage()
                : 'Transaksi gagal disimpan. Periksa data barang dan database.';

        }

    }

}


// MENGAMBIL RIWAYAT TRANSAKSI
$history = $DBH->query(

    'SELECT
        t.id,
        t.waktu_transaksi,
        t.total,
        p.nama_pelanggan,
        u.nama AS nama_user

     FROM transaksi t

     LEFT JOIN pelanggan p
        ON p.id = t.pelanggan_id

     JOIN user u
        ON u.id = t.user_id

     ORDER BY t.id DESC

     LIMIT 30'

)->fetchAll();

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Transaksi</title>

    <link rel="stylesheet" href="../assets/style.css">

</head>

<body>

<?php require __DIR__ . '/../partials/nav.php'; ?>

<main class="container">

    <div class="panel">

        <h1>Transaksi Penjualan</h1>

        <?php if ($message): ?>

            <div class="alert success">
                <?= e($message) ?>
            </div>

        <?php endif; ?>

        <?php if ($error): ?>

            <div class="alert error">
                <?= e($error) ?>
            </div>

        <?php endif; ?>


        <form method="post">

            <input
                type="hidden"
                name="csrf_token"
                value="<?= e(csrfToken()) ?>"
            >

            <label>Pelanggan</label>

            <select name="pelanggan_id">

                <option value="">
                    Pelanggan umum / tanpa pilihan
                </option>

                <?php foreach ($pelanggan as $p): ?>

                    <option value="<?= (int)$p['id'] ?>">

                        <?= e($p['nama_pelanggan']) ?>

                    </option>

                <?php endforeach; ?>

            </select>


            <label>Barang</label>

            <select name="barang_id" required>

                <option value="">
                    Pilih barang
                </option>

                <?php foreach ($barang as $b): ?>

                    <option value="<?= (int)$b['id'] ?>">

                        <?= e(
                            $b['kode_barang'] .
                            ' - ' .
                            $b['nama_barang'] .
                            ' | Rp ' .
                            number_format($b['harga'], 0, ',', '.') .
                            ' | Stok ' .
                            $b['stok']
                        ) ?>

                    </option>

                <?php endforeach; ?>

            </select>


            <label>Jumlah</label>

            <input
                type="number"
                name="qty"
                min="1"
                required
            >


            <button class="btn primary" type="submit">

                Simpan Transaksi

            </button>

        </form>

    </div>


    <div class="panel">

        <h2>30 Transaksi Terakhir</h2>

        <div class="table-wrap">

            <table>

                <tr>

                    <th>ID</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Kasir</th>
                    <th>Total</th>

                </tr>

                <?php foreach ($history as $h): ?>

                    <tr>

                        <td><?= (int)$h['id'] ?></td>

                        <td><?= e($h['waktu_transaksi']) ?></td>

                        <td>
                            <?= e($h['nama_pelanggan'] ?? 'Umum') ?>
                        </td>

                        <td><?= e($h['nama_user']) ?></td>

                        <td>
                            Rp <?= number_format(
                                $h['total'],
                                0,
                                ',',
                                '.'
                            ) ?>
                        </td>

                    </tr>

                <?php endforeach; ?>

                <?php if (!$history): ?>

                    <tr>

                        <td colspan="5">
                            Belum ada transaksi.
                        </td>

                    </tr>

                <?php endif; ?>

            </table>

        </div>

    </div>

</main>

</body>
</html>
