
<?php
require_once 'koneksi.php';

$page_title = "Laporan Penjualan";
$active_page = "laporan";
$body_class = "bg-[#F8F9FA]";
$nav_container = "max-w-7xl";

$tanggal_mulai = $_GET['tanggal_mulai'] ?? '';
$tanggal_selesai = $_GET['tanggal_selesai'] ?? '';

$sql = "
    SELECT
        transaksi.id,
        transaksi.waktu_transaksi,
        transaksi.keterangan,
        transaksi.total,
        transaksi.pelanggan_id,
        pelanggan.nama AS nama_pelanggan
    FROM transaksi
    INNER JOIN pelanggan
        ON transaksi.pelanggan_id = pelanggan.id
";


$params = [];

if (!empty($tanggal_mulai) && !empty($tanggal_selesai)) {

    $sql .= "
        WHERE DATE(transaksi.waktu_transaksi)
        BETWEEN :tanggal_mulai AND :tanggal_selesai
    ";

    $params[':tanggal_mulai'] = $tanggal_mulai;
    $params[':tanggal_selesai'] = $tanggal_selesai;
}

$sql .= " ORDER BY transaksi.waktu_transaksi DESC";

$query = $DBH->prepare($sql);
$query->execute($params);

$data_laporan = $query->fetchAll(PDO::FETCH_ASSOC);

$sql_grafik = "
    SELECT
        DATE(waktu_transaksi) AS tanggal,
        SUM(total) AS total_penjualan
    FROM transaksi
";

if (!empty($tanggal_mulai) && !empty($tanggal_selesai)) {
    $sql_grafik .= "
        WHERE DATE(waktu_transaksi)
        BETWEEN :tanggal_mulai AND :tanggal_selesai
    ";
}

$sql_grafik .= "
    GROUP BY DATE(waktu_transaksi)
    ORDER BY tanggal ASC
";

$query_grafik = $DBH->prepare($sql_grafik);

if (!empty($tanggal_mulai) && !empty($tanggal_selesai)) {
    $query_grafik->execute([
        ':tanggal_mulai' => $tanggal_mulai,
        ':tanggal_selesai' => $tanggal_selesai
    ]);
} else {
    $query_grafik->execute();
}

$data_grafik = $query_grafik->fetchAll(PDO::FETCH_ASSOC);

$label_tanggal = [];
$nilai_penjualan = [];

foreach ($data_grafik as $grafik) {
    $label_tanggal[] = date(
        'd-m-Y',
        strtotime($grafik['tanggal'])
    );

    $nilai_penjualan[] = (int) $grafik['total_penjualan'];
}

$total_transaksi = count($data_laporan);

include 'components/header.php';
include 'components/navbar.php';
?>

<div class="max-w-7xl mx-auto px-4 py-8">

    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">

        <h2 class="text-lg font-semibold text-gray-800 mb-4">
            Filter Laporan
        </h2>

        <form method="GET" action="" class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <div>
                <label for="tanggal_mulai"
                    class="block text-sm font-medium text-gray-700 mb-2">
                    Tanggal Mulai
                </label>

                <input
                    type="date"
                    name="tanggal_mulai"
                    id="tanggal_mulai"
                    value="<?= htmlspecialchars($tanggal_mulai) ?>"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2"
                    required
                >
            </div>

            <div>
                <label for="tanggal_selesai"
                    class="block text-sm font-medium text-gray-700 mb-2">
                    Tanggal Selesai
                </label>

                <input
                    type="date"
                    name="tanggal_selesai"
                    id="tanggal_selesai"
                    value="<?= htmlspecialchars($tanggal_selesai) ?>"
                    min="<?= htmlspecialchars($tanggal_mulai) ?>"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2"
                    required
                >
            </div>

            <div class="flex items-end gap-2">

                <button
                    type="submit"
                    class="bg-black text-white px-5 py-2 rounded-lg"
                >
                    Tampilkan
                </button>

                <a
                    href="laporan-penjualan.php"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2 rounded-lg"
                >
                    Reset
                </a>

            </div>

        </form>
    </div>

    <!-- Grafik -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">

        <div class="mb-5">
            <h2 class="text-lg font-semibold text-gray-800">
                Grafik Tren Penjualan Harian
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Visualisasi total pendapatan berdasarkan tanggal transaksi.
            </p>
        </div>

        <div class="relative w-full" style="height: 350px;">
            <canvas id="grafikPenjualan"></canvas>
        </div>

    </div>

    <!-- Tabel -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">

        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">
                Data Laporan Penjualan
            </h2>

            <?php if (!empty($tanggal_mulai) && !empty($tanggal_selesai)): ?>
                <p class="text-sm text-gray-500 mt-1">
                    Periode:
                    <?= htmlspecialchars($tanggal_mulai) ?>
                    sampai
                    <?= htmlspecialchars($tanggal_selesai) ?>
                </p>
            <?php endif; ?>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm text-left">

                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-6 py-3">No</th>
                        <th class="px-6 py-3">ID Transaksi</th>
                        <th class="px-6 py-3">Tanggal Transaksi</th>
                        <th class="px-6 py-3">Nama Pelanggan</th>
                        <th class="px-6 py-3">Keterangan</th>
                        <th class="px-6 py-3">Total</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">

                    <?php if (count($data_laporan) > 0): ?>

                        <?php $no = 1; ?>

                        <?php foreach ($data_laporan as $data): ?>

                            <tr class="hover:bg-gray-50">

                                <td class="px-6 py-4">
                                    <?= $no++ ?>
                                </td>

                                <td class="px-6 py-4">
                                    <?= htmlspecialchars($data['id']) ?>
                                </td>

                                <td class="px-6 py-4">
                                    <?= date(
                                        'd-m-Y',
                                        strtotime($data['waktu_transaksi'])
                                    ) ?>
                                </td>

                                <td class="px-6 py-4">
                                    <?= htmlspecialchars($data['nama_pelanggan']) ?>
                                </td>

                                <td class="px-6 py-4">
                                    <?= htmlspecialchars($data['keterangan']) ?>
                                </td>

                                <td class="px-6 py-4 font-medium">
                                    Rp <?= number_format(
                                        $data['total'],
                                        0,
                                        ',',
                                        '.'
                                    ) ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="6"
                                class="px-6 py-8 text-center text-gray-500">
                                Tidak ada transaksi pada rentang tanggal tersebut.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<!-- Library Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const tanggal = <?= json_encode($label_tanggal) ?>;
    const penjualan = <?= json_encode($nilai_penjualan) ?>;

    const ctx = document.getElementById('grafikPenjualan');

    new Chart(ctx, {
        type: 'bar',

        data: {
            labels: tanggal,

            datasets: [{
                label: 'Total Penjualan (Rp)',
                data: penjualan,

                backgroundColor: 'rgba(0, 0, 0, 0.7)',
                borderColor: 'rgb(0, 0, 0)',
                borderWidth: 1,
                borderRadius: 6,

                barThickness: 'flex',
                maxBarThickness: 50
            }]
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,

            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },

                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' +
                                new Intl.NumberFormat('id-ID')
                                .format(context.raw);
                        }
                    }
                }
            },

            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Tanggal Transaksi'
                    },

                    grid: {
                        display: false
                    }
                },

                y: {
                    beginAtZero: true,

                    title: {
                        display: true,
                        text: 'Total Pendapatan (Rp)'
                    },

                    ticks: {
                        callback: function(value) {
                            return 'Rp ' +
                                new Intl.NumberFormat('id-ID')
                                .format(value);
                        }
                    }
                }
            }
        }
    });
</script>

<?php require_once 'components/footer.php'; ?>
