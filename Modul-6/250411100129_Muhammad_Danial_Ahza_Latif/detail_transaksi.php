
<?php
require_once 'koneksi.php';

$page_title = "Detail Transaksi";
$active_page = "edit";
$body_class = "bg-[#F8F9FA]";
$nav_container = "max-w-5xl";
$show_back_link = true;

$id = $_GET['id'] ?? '';

if (!ctype_digit((string) $id)) {
    die("ID Transaksi tidak valid.");
}

$query = $DBH->prepare("
    SELECT *
    FROM transaksi
    WHERE id = :id
");

$query->execute([':id' => $id]);

$transaksi = $query->fetch();

if (!$transaksi) {
    die("Data Transaksi tidak ditemukan.");
}

$query = $DBH->prepare("
    SELECT
        dt.transaksi_id,
        dt.barang_id,
        b.kode_barang,
        b.nama_barang,
        dt.harga,
        dt.qty,
        (dt.harga * dt.qty) AS subtotal
    FROM detail_transaksi dt
    JOIN barang b ON dt.barang_id = b.id
    WHERE dt.transaksi_id = :id
");

$query->execute([':id' => $id]);
$detail = $query->fetchAll();

$total = 0;

foreach ($detail as $row) {
    $total += $row['subtotal'];
}

$query = $DBH->prepare("
    UPDATE transaksi
    SET total = :total
    WHERE id = :id
");

$query->execute([
    ':total' => $total,
    ':id' => $id
]);

include 'components/header.php';
?>

<!-- DETAIL TRANSAKSI -->
<div class="bg-white p-6 rounded-xl shadow">
    <!-- MASTER TRANSAKSI -->
<div class="bg-white p-6 rounded-xl ">

    <h1 class="text-2xl font-bold mb-5">
        Detail Transaksi
    </h1>

    <div>

        <div>
            <p class="text-gray-500">ID Transaksi</p>
            <p class="font-semibold">
                <?= htmlspecialchars($transaksi['id']) ?>
            </p>
        </div>

        <div>
            <p class="text-gray-500">Tanggal</p>
            <p class="font-semibold">
                <?= htmlspecialchars($transaksi['waktu_transaksi']) ?>
            </p>
        </div>

        <div>
            <p class="text-gray-500">ID Pelanggan</p>
            <p class="font-semibold">
                <?= htmlspecialchars($transaksi['pelanggan_id']) ?>
            </p>
        </div>

        <div>
            <p class="text-gray-500">Keterangan</p>
            <p class="font-semibold">
                <?= htmlspecialchars($transaksi['keterangan'] ?? '-') ?>
            </p>
        </div>

    </div>

</div>
    <div class="flex justify-between items-center mb-5">

        <h2 class="text-xl font-bold">
            Daftar Barang
        </h2>

        <a href="form_tambah_barang.php?id=<?= $transaksi['id'] ?>"
           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Tambah Barang
        </a>

    </div>

    <div class="overflow-x-auto">

        <table class="w-full text-left text-xs">

            <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-[10px] tracking-wider">

                <tr>
                    <th class="py-3 px-4 font-bold">No</th>
                    <th class="py-3 px-4 font-bold">ID Barang</th>
                    <th class="py-3 px-4 font-bold">Nama Barang</th>
                    <th class="py-3 px-4 font-bold">Harga</th>
                    <th class="py-3 px-4 font-bold">Qty</th>
                    <th class="py-3 px-4 font-bold">Subtotal</th>
                    <th class="py-3 px-4 font-bold text-right">Aksi CRUD</th>
                </tr>

            </thead>

            <tbody class="divide-y divide-slate-100">

                <?php $no = 1; ?>

                <?php foreach ($detail as $row): ?>

                    <tr class="hover:bg-slate-50/70 transition-colors">

                        <td class="py-4 px-4 font-mono font-bold text-slate-400">
                            <?= $no++ ?>
                        </td>

                        <td class="py-4 px-4 font-mono text-slate-600">
                            <?= htmlspecialchars($row['barang_id']) ?>
                        </td>

                        <td class="py-4 px-4">
                            <span class="font-bold text-slate-900">
                                <?= htmlspecialchars($row['nama_barang']) ?>
                            </span>
                            <span class="block text-slate-400">
                                <?= htmlspecialchars($row['kode_barang']) ?>
                            </span>
                        </td>

                        <td class="py-4 px-4 font-medium text-slate-700">
                            Rp <?= number_format($row['harga'], 0, ',', '.') ?>
                        </td>

                        <td class="py-4 px-4 text-slate-500">
                            <?= htmlspecialchars($row['qty']) ?>
                        </td>

                        <td class="py-4 px-4 font-medium text-slate-700">
                            Rp <?= number_format($row['subtotal'], 0, ',', '.') ?>
                        </td>

                        <td class="py-4 px-4 text-right space-x-3">


                            <a href="hapus-detail.php?transaksi_id=<?= $id ?>&barang_id=<?= $row['barang_id']?>"
                               onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?')"
                               class="text-rose-600 hover:underline font-bold uppercase text-[11px]">
                                Hapus
                            </a>

                        </td>

                    </tr>

                <?php endforeach; ?>

                <?php if (empty($detail)): ?>

                    <tr>
                        <td colspan="7" class="py-6 text-center text-slate-400">
                            Belum ada data detail transaksi.
                        </td>
                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

    <!-- TOTAL TRANSAKSI -->
    <div class="flex justify-between items-center border-t mt-5 pt-5">

        <a href="index.php"
           class="bg-slate-500 text-white px-4 py-2 rounded-lg hover:bg-slate-600">
            Kembali
        </a>

        <div class="text-right">
            <p class="text-sm text-slate-500">
                Total Transaksi
            </p>

            <p class="text-2xl font-bold text-slate-900">
                Rp <?= number_format($total, 0, ',', '.') ?>
            </p>
        </div>

    </div>

</div>
</div>

</body>
</html>
