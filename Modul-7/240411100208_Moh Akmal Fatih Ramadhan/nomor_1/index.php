<?php
require_once 'config.php';

$tanggal_mulai = $_GET['tanggal_mulai'] ?? '';
$tanggal_selesai = $_GET['tanggal_selesai'] ?? '';

$data = [];
$error = '';

if (!empty($tanggal_mulai) && !empty($tanggal_selesai)) {

    // Validasi tanggal
    if ($tanggal_mulai > $tanggal_selesai) {
        $error = "Tanggal mulai tidak boleh melebihi tanggal selesai.";
    } else {

        $sql = "SELECT
                    DATE(waktu_transaksi) AS tanggal,
                    total,
                    pelanggan_id
                FROM transaksi
                WHERE DATE(waktu_transaksi)
                BETWEEN :mulai AND :selesai
                ORDER BY waktu_transaksi ASC";

        $stmt = $DBH->prepare($sql);

        $stmt->execute([
            ':mulai' => $tanggal_mulai,
            ':selesai' => $tanggal_selesai
        ]);

        $data = $stmt->fetchAll();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nomor 1 - Filter Laporan Penjualan</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background: #f5f5f5;
        }

        .container {
            background: white;
            padding: 25px;
            border-radius: 10px;
        }

        input, button {
            padding: 10px;
            margin: 5px;
        }

        button {
            cursor: pointer;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        th {
            background: #2563eb;
            color: white;
        }

        .error {
            color: red;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Laporan Penjualan</h1>

    <!-- FORM FILTER TANGGAL -->

    <form method="GET">

        <label>Tanggal Mulai:</label>

        <input type="date"
               name="tanggal_mulai"
               value="<?= htmlspecialchars($tanggal_mulai) ?>"
               required>

        <label>Tanggal Selesai:</label>

        <input type="date"
               name="tanggal_selesai"
               value="<?= htmlspecialchars($tanggal_selesai) ?>"
               required>

        <button type="submit">Tampilkan</button>

    </form>

    <hr>

    <?php if ($error): ?>

        <p class="error">
            <?= htmlspecialchars($error) ?>
        </p>

    <?php elseif (!empty($tanggal_mulai) && !empty($tanggal_selesai)): ?>

        <h3>Hasil Laporan Penjualan</h3>

        <p>
            Periode:
            <?= htmlspecialchars($tanggal_mulai) ?>
            sampai
            <?= htmlspecialchars($tanggal_selesai) ?>
        </p>

        <table>

            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal Transaksi</th>
                    <th>ID Pelanggan</th>
                    <th>Total Pendapatan</th>
                </tr>
            </thead>

            <tbody>

                <?php if (!empty($data)): ?>

                    <?php $no = 1; ?>

                    <?php foreach ($data as $row): ?>

                        <tr>
                            <td><?= $no++ ?></td>

                            <td>
                                <?= htmlspecialchars($row['tanggal']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['pelanggan_id']) ?>
                            </td>

                            <td>
                                Rp <?= number_format(
                                    $row['total'],
                                    0,
                                    ',',
                                    '.'
                                ) ?>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="4">
                            Tidak ada transaksi pada periode tersebut.
                        </td>
                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    <?php endif; ?>

</div>

</body>
</html>
