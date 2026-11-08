
<?php
require_once 'koneksi.php';

$query = $DBH->prepare("
    SELECT *
    FROM transaksi
");

$query->execute();
$transaksi = $query->fetchAll();
?>

<div class="overflow-x-auto">
    <table class="w-full text-left text-xs">
        <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-[10px] tracking-wider">
            <tr>
                <th class="py-3 px-4 font-bold">No</th>
                <th class="py-3 px-4 font-bold">ID</th>
                <th class="py-3 px-4 font-bold">Waktu</th>
                <th class="py-3 px-4 font-bold">Keterangan</th>
                <th class="py-3 px-4 font-bold">total</th>
                <th class="py-3 px-4 font-bold">id pelanggan</th>
                <th class="py-3 px-4 font-bold text-right">Aksi CRUD</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-slate-100">

            <?php $no = 1; ?>

            <?php foreach ($transaksi as $row): ?>

                <tr class="hover:bg-slate-50/70 transition-colors">

                    <td class="py-4 px-4 font-mono font-bold text-slate-400">
                        <?= $no++ ?>
                    </td>

                    <td class="py-4 px-4 font-mono text-slate-600">
                        <?= htmlspecialchars($row['id']) ?>
                    </td>

                    <td class="py-4 px-4">
                        <span class="font-bold text-slate-900 block text-xs">
                            <?= htmlspecialchars($row['waktu_transaksi']) ?>
                        </span>
                    </td>

                    <td class="py-4 px-4 font-medium text-slate-700">
                        <?= htmlspecialchars($row['keterangan']) ?>
                    </td>

                    <td class="py-4 px-4 text-slate-500">
                        <?= htmlspecialchars($row['total']) ?>
                    </td>
                    <td class="py-4 px-4 text-slate-500">
                        <?= htmlspecialchars($row['pelanggan_id']) ?>
                    </td>

                    <td class="py-4 px-4 text-right space-x-3">

                        <!-- Detail transaksi -->
                        <a href="detail_transaksi.php?id=<?= $row['id'] ?>"
                           class="text-slate-900 hover:underline font-bold uppercase text-[11px]">
                            Detail
                        </a>

                        <!-- HAPUS transaksi -->
                        <a href="hapus.php?id=<?= $row['id'] ?>"
                           onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')"
                           class="text-rose-600 hover:underline font-bold uppercase text-[11px]">
                            Hapus
                        </a>

                    </td>
                </tr>

            <?php endforeach; ?>

            <?php if (empty($transaksi)): ?>
                <tr>
                    <td colspan="6" class="py-6 text-center text-slate-400">
                        Belum ada data transaksi.
                    </td>
                </tr>
            <?php endif; ?>

        </tbody>
    </table>
</div>
