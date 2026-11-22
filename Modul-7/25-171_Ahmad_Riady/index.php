<?php
require_once 'koneksi.php';

$nilai_tanggal_mulai = array_key_exists('tanggal_mulai', $_GET)
    ? (is_string($_GET['tanggal_mulai']) ? $_GET['tanggal_mulai'] : '')
    : '';
$nilai_tanggal_selesai = array_key_exists('tanggal_selesai', $_GET)
    ? (is_string($_GET['tanggal_selesai']) ? $_GET['tanggal_selesai'] : '')
    : '';
$ada_filter_tanggal = array_key_exists('tanggal_mulai', $_GET) || array_key_exists('tanggal_selesai', $_GET);

$parseTanggal = static function ($nilai) {
    $tanggal = DateTime::createFromFormat('!d-m-Y', $nilai);
    $error = DateTime::getLastErrors();

    if (
        $tanggal === false
        || ($error !== false && ($error['warning_count'] > 0 || $error['error_count'] > 0))
        || $tanggal->format('d-m-Y') !== $nilai
    ) {
        return null;
    }

    return $tanggal->format('Y-m-d');
};

$tanggal_mulai = $parseTanggal($nilai_tanggal_mulai);
$tanggal_selesai = $parseTanggal($nilai_tanggal_selesai);
$tampilkan_laporan = $ada_filter_tanggal && $tanggal_mulai !== null && $tanggal_selesai !== null;
$pesan_tanggal = $ada_filter_tanggal && !$tampilkan_laporan
    ? 'Tanggal harus menggunakan format dd-mm-yyyy dan merupakan tanggal yang valid.'
    : '';

if ($tampilkan_laporan && $tanggal_mulai > $tanggal_selesai) {
    [$tanggal_mulai, $tanggal_selesai] = [$tanggal_selesai, $tanggal_mulai];
}

if ($tampilkan_laporan) {
    $nilai_tanggal_mulai = date('d-m-Y', strtotime($tanggal_mulai));
    $nilai_tanggal_selesai = date('d-m-Y', strtotime($tanggal_selesai));
}

$rekap_harian = [];
$chart_labels = [];
$chart_values = [];
$jumlah_pelanggan = 0;
$total_keseluruhan = 0;

if ($tampilkan_laporan) {
    // 1. Query rekapitulasi total pendapatan per hari
    $stmtHarian = $DBH->prepare("
        SELECT 
            DATE(waktu_transaksi) AS tanggal,
            SUM(total) AS total_pendapatan
        FROM transaksi
        WHERE DATE(waktu_transaksi) BETWEEN :tanggal_mulai AND :tanggal_selesai
        GROUP BY DATE(waktu_transaksi)
        ORDER BY DATE(waktu_transaksi) ASC
    ");
    $stmtHarian->execute([
        ':tanggal_mulai'   => $tanggal_mulai,
        ':tanggal_selesai' => $tanggal_selesai
    ]);
    $rekap_harian = $stmtHarian->fetchAll();

    foreach ($rekap_harian as $row) {
        $chart_labels[] = date('d-m-Y', strtotime($row['tanggal']));
        $chart_values[] = (int) $row['total_pendapatan'];
    }

    // 2. Query ringkasan kumulatif: Pelanggan Unik & Total Pendapatan Keseluruhan
    $stmtRingkasan = $DBH->prepare("
        SELECT 
            COUNT(DISTINCT pelanggan_id) AS jumlah_pelanggan_unik,
            COALESCE(SUM(total), 0) AS total_pendapatan_keseluruhan
        FROM transaksi
        WHERE DATE(waktu_transaksi) BETWEEN :tanggal_mulai AND :tanggal_selesai
    ");
    $stmtRingkasan->execute([
        ':tanggal_mulai'   => $tanggal_mulai,
        ':tanggal_selesai' => $tanggal_selesai
    ]);
    $ringkasan = $stmtRingkasan->fetch();

    $jumlah_pelanggan  = (int) ($ringkasan['jumlah_pelanggan_unik'] ?? 0);
    $total_keseluruhan = (int) ($ringkasan['total_pendapatan_keseluruhan'] ?? 0);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, sans-serif; background: #f8f9fa; color: #1e293b; }
        header { background: #0f172a; color: white; padding: 25px 8%; }
        header h2 { margin: 0; font-size: 22px; }
        .container { max-width: 1200px; margin: auto; padding: 35px 20px; }
        .banner { display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 15px; margin-bottom: 10px; }
        .banner h1 { font-size: 30px; margin: 0 0 6px; }
        .content { background: white; border: 1px solid #e2e8f0; margin-top: 25px; padding: 25px; }
        .content h2 { font-size: 18px; margin: 0 0 18px; color: #0f172a; }

        .filter-form, .action-buttons { display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end; }
        .form-group { display: flex; flex-direction: column; min-width: 200px; flex: 1; }
        .form-group label { font-size: 13px; font-weight: bold; margin-bottom: 6px; color: #475569; }
        .date-input-wrap { position: relative; }
        .date-input-wrap input { width: 100%; padding: 10px 42px 10px 10px; border: 1px solid #cbd5e1; font-size: 13px; font-family: Arial, sans-serif; }
        .calendar-button { position: absolute; top: 1px; right: 1px; bottom: 1px; width: 38px; border: 0; background: transparent; cursor: pointer; color: #334155; }
        .calendar-button:hover { background: #f1f5f9; }
        .calendar-button svg { width: 18px; height: 18px; }
        .calendar-popup { position: absolute; z-index: 10; top: calc(100% + 6px); right: 0; width: 280px; max-width: calc(100vw - 40px); padding: 12px; border: 1px solid #cbd5e1; background: white; box-shadow: 0 8px 20px #0002; }
        .calendar-popup[hidden] { display: none; }
        .calendar-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .calendar-month-label { font-size: 14px; font-weight: bold; }
        .calendar-nav { width: 30px; height: 30px; border: 1px solid #cbd5e1; background: white; cursor: pointer; font-size: 18px; }
        .calendar-weekdays, .calendar-days { display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; text-align: center; }
        .calendar-weekdays { margin-bottom: 4px; color: #64748b; font-size: 11px; }
        .calendar-days button { height: 30px; border: 0; background: white; color: #1e293b; cursor: pointer; }
        .calendar-days button:hover, .calendar-days button.is-selected { background: #0f172a; color: white; }
        .date-error { color: #b91c1c; font-size: 13px; margin: 15px 0 0; }

        .btn { padding: 10px 18px; border: none; cursor: pointer; font-size: 13px; font-weight: bold; text-decoration: none; display: inline-block; }
        .btn-primary { background: #0f172a; color: white; }
        .btn-print { background: #475569; color: white; }
        .btn-excel { background: #15803d; color: white; }
        .btn-primary:hover { background: #1e293b; }
        .btn-print:hover { background: #334155; }
        .btn-excel:hover { background: #166534; }

        .chart-wrapper { position: relative; width: 100%; height: 340px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f1f5f9; color: #475569; padding: 14px 15px; text-align: left; font-size: 12px; text-transform: uppercase; border-bottom: 1px solid #e2e8f0; }
        td { padding: 14px 15px; border-bottom: 1px solid #e2e8f0; font-size: 13px; }
        tr:hover { background: #f8fafc; }
        .text-center { text-align: center; }

        .summary-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px; margin-top: 25px; }
        .summary-card { background: #f8fafc; border: 1px solid #cbd5e1; border-left: 5px solid #0f172a; padding: 20px; }
        .summary-label { font-size: 12px; text-transform: uppercase; color: #64748b; font-weight: bold; margin-bottom: 8px; }
        .summary-value { font-size: 24px; font-weight: bold; color: #0f172a; margin: 0; }
        .summary-sub { font-size: 12px; color: #64748b; margin-top: 6px; display: block; }
        footer { text-align: center; color: #94a3b8; padding: 25px; font-size: 12px; }

        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            header { background: #0f172a !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .container { max-width: 100%; padding: 15px 0; }
            .content { border: 1px solid #cbd5e1; margin-top: 15px; padding: 18px; page-break-inside: avoid; }
            .chart-wrapper { height: 270px; }
            .summary-card { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>
    <header>
        <h2>DATABASE MANAGEMENT</h2>
    </header>
    <main class="container">
        <section class="banner">
            <div>
                <h1>Laporan Rekapitulasi Penjualan</h1>
            </div>

            <div class="action-buttons no-print">
                <button type="button" onclick="window.print()" class="btn btn-print">Cetak (Print)</button>
                <a href="export_excel.php?tanggal_mulai=<?= urlencode($tanggal_mulai) ?>&tanggal_selesai=<?= urlencode($tanggal_selesai) ?>" class="btn btn-excel">
                    Ekspor Excel (.xls)
                </a>
            </div>
        </section>

        <section class="content no-print">
            <h2>Filter Rentang Tanggal</h2>
            <form method="GET" action="index.php" class="filter-form">
                <div class="form-group">
                    <label for="tanggal_mulai">Tanggal Mulai</label>
                    <div class="date-input-wrap">
                        <input type="text" id="tanggal_mulai" name="tanggal_mulai" value="<?= htmlspecialchars($nilai_tanggal_mulai) ?>" placeholder="dd-mm-yyyy" inputmode="numeric" pattern="[0-9]{2}-[0-9]{2}-[0-9]{4}" required>
                        <button type="button" class="calendar-button" aria-label="Pilih tanggal mulai" title="Pilih tanggal mulai">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="3" y="5" width="18" height="16" rx="2"></rect>
                                <path d="M16 3v4M8 3v4M3 11h18"></path>
                            </svg>
                        </button>
                        <div class="calendar-popup" hidden>
                            <div class="calendar-header">
                                <button type="button" class="calendar-nav" data-month-change="-1" aria-label="Bulan sebelumnya">&lsaquo;</button>
                                <span class="calendar-month-label"></span>
                                <button type="button" class="calendar-nav" data-month-change="1" aria-label="Bulan berikutnya">&rsaquo;</button>
                            </div>
                            <div class="calendar-weekdays" aria-hidden="true">
                                <span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span><span>Min</span>
                            </div>
                            <div class="calendar-days"></div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="tanggal_selesai">Tanggal Selesai</label>
                    <div class="date-input-wrap">
                        <input type="text" id="tanggal_selesai" name="tanggal_selesai" value="<?= htmlspecialchars($nilai_tanggal_selesai) ?>" placeholder="dd-mm-yyyy" inputmode="numeric" pattern="[0-9]{2}-[0-9]{2}-[0-9]{4}" required>
                        <button type="button" class="calendar-button" aria-label="Pilih tanggal selesai" title="Pilih tanggal selesai">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="3" y="5" width="18" height="16" rx="2"></rect>
                                <path d="M16 3v4M8 3v4M3 11h18"></path>
                            </svg>
                        </button>
                        <div class="calendar-popup" hidden>
                            <div class="calendar-header">
                                <button type="button" class="calendar-nav" data-month-change="-1" aria-label="Bulan sebelumnya">&lsaquo;</button>
                                <span class="calendar-month-label"></span>
                                <button type="button" class="calendar-nav" data-month-change="1" aria-label="Bulan berikutnya">&rsaquo;</button>
                            </div>
                            <div class="calendar-weekdays" aria-hidden="true">
                                <span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span><span>Min</span>
                            </div>
                            <div class="calendar-days"></div>
                        </div>
                    </div>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">Tampilkan</button>
                </div>
            </form>
            <?php if ($pesan_tanggal !== ''): ?>
                <p class="date-error" role="alert"><?= htmlspecialchars($pesan_tanggal) ?></p>
            <?php endif; ?>
        </section>

        <?php if ($tampilkan_laporan): ?>
        <section class="content">
            <h2>Grafik Tren Pendapatan Harian</h2>
            <?php if (count($rekap_harian) > 0): ?>
                <div class="chart-wrapper">
                    <canvas id="grafikPendapatan"></canvas>
                </div>
            <?php else: ?>
                <p style="color: #64748b; font-size: 13px;">Tidak ada data transaksi pada rentang tanggal yang dipilih.</p>
            <?php endif; ?>
        </section>

        <section class="content">
            <h2>Tabel Rekapitulasi Penjualan Harian</h2>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 70px;" class="text-center">No</th>
                            <th>Total Pendapatan</th>
                            <th>Tanggal Transaksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($rekap_harian) > 0): ?>
                            <?php $no = 1; foreach ($rekap_harian as $row): ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td><strong>Rp <?= number_format($row['total_pendapatan'], 0, ',', '.') ?></strong></td>
                                    <td><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center" style="color: #64748b; padding: 20px;">
                                    Belum ada data penjualan pada periode tersebut.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="summary-grid">
                <div class="summary-card">
                    <div class="summary-label">Jumlah Pelanggan Unik</div>
                    <p class="summary-value"><?= number_format($jumlah_pelanggan, 0, ',', '.') ?> Pelanggan</p>
                    <span class="summary-sub">Dihitung tanpa duplikasi pada periode terpilih</span>
                </div>
                <div class="summary-card revenue">
                    <div class="summary-label">Total Pendapatan Keseluruhan</div>
                    <p class="summary-value">Rp <?= number_format($total_keseluruhan, 0, ',', '.') ?></p>
                    <span class="summary-sub">Akumulasi seluruh transaksi pada periode terpilih</span>
                </div>
            </div>
        </section>
        <?php endif; ?>
    </main>
    <footer class="no-print">Rian vs EveryBody</footer>

    <script>
        document.querySelectorAll('.date-input-wrap').forEach(function (wrapper) {
            const textInput = wrapper.querySelector('input[type="text"]');
            const calendarButton = wrapper.querySelector('.calendar-button');
            const calendar = wrapper.querySelector('.calendar-popup');
            const monthLabel = wrapper.querySelector('.calendar-month-label');
            const daysContainer = wrapper.querySelector('.calendar-days');
            let visibleMonth = new Date();
            visibleMonth.setDate(1);

            function parseDate(value) {
                const match = value.match(/^(\d{2})-(\d{2})-(\d{4})$/);
                if (!match) return null;

                const date = new Date(Number(match[3]), Number(match[2]) - 1, Number(match[1]));
                if (date.getFullYear() !== Number(match[3]) || date.getMonth() !== Number(match[2]) - 1 || date.getDate() !== Number(match[1])) {
                    return null;
                }

                return date;
            }

            function renderCalendar() {
                const year = visibleMonth.getFullYear();
                const month = visibleMonth.getMonth();
                const selectedDate = parseDate(textInput.value);
                const firstWeekday = (new Date(year, month, 1).getDay() + 6) % 7;
                const daysInMonth = new Date(year, month + 1, 0).getDate();

                monthLabel.textContent = new Intl.DateTimeFormat('id-ID', {
                    month: 'long',
                    year: 'numeric'
                }).format(visibleMonth);
                daysContainer.replaceChildren();

                for (let blank = 0; blank < firstWeekday; blank++) {
                    daysContainer.appendChild(document.createElement('span'));
                }

                for (let day = 1; day <= daysInMonth; day++) {
                    const dateButton = document.createElement('button');
                    const date = new Date(year, month, day);
                    dateButton.type = 'button';
                    dateButton.textContent = day;
                    dateButton.setAttribute('aria-label', day + ' ' + monthLabel.textContent);

                    if (selectedDate && selectedDate.getTime() === date.getTime()) {
                        dateButton.classList.add('is-selected');
                    }

                    dateButton.addEventListener('click', function () {
                        textInput.value = String(day).padStart(2, '0') + '-' +
                            String(month + 1).padStart(2, '0') + '-' + year;
                        calendar.hidden = true;
                        calendarButton.setAttribute('aria-expanded', 'false');
                        textInput.dispatchEvent(new Event('input', { bubbles: true }));
                        textInput.dispatchEvent(new Event('change', { bubbles: true }));
                    });
                    daysContainer.appendChild(dateButton);
                }
            }

            calendarButton.setAttribute('aria-haspopup', 'dialog');
            calendarButton.setAttribute('aria-expanded', 'false');
            calendarButton.addEventListener('click', function () {
                if (calendar.hidden) {
                    const selectedDate = parseDate(textInput.value) || new Date();
                    visibleMonth = new Date(selectedDate.getFullYear(), selectedDate.getMonth(), 1);
                    renderCalendar();
                    calendar.hidden = false;
                    calendarButton.setAttribute('aria-expanded', 'true');
                } else {
                    calendar.hidden = true;
                    calendarButton.setAttribute('aria-expanded', 'false');
                }
            });

            wrapper.querySelectorAll('[data-month-change]').forEach(function (button) {
                button.addEventListener('click', function () {
                    visibleMonth.setMonth(visibleMonth.getMonth() + Number(button.dataset.monthChange));
                    renderCalendar();
                });
            });

            document.addEventListener('click', function (event) {
                if (!wrapper.contains(event.target)) {
                    calendar.hidden = true;
                    calendarButton.setAttribute('aria-expanded', 'false');
                }
            });

            wrapper.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && !calendar.hidden) {
                    calendar.hidden = true;
                    calendarButton.setAttribute('aria-expanded', 'false');
                    calendarButton.focus();
                }
            });
        });
    </script>    
    <?php if ($tampilkan_laporan && count($rekap_harian) > 0): ?>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('grafikPendapatan').getContext('2d');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode($chart_labels) ?>,
                    datasets: [{ label: 'Total Pendapatan', data: <?= json_encode($chart_values) ?>}]
                },
                options: { responsive: true, maintainAspectRatio: false}
            });
        </script>
    <?php endif; ?>
</body>
</html>