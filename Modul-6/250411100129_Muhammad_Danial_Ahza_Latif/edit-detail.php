<?php

require_once 'koneksi.php';

$page_title = "Edit Data Transaksi";
$active_page = "edit";
$body_class = "bg-[#F8F9FA]";
$nav_container = "max-w-5xl";
$show_back_link = true;
$id = $_GET['id'] ?? '';

if (!ctype_digit((string) $id)) {
    die("ID Transaksi tidak valid.");
}

$query = $DBH->prepare("SELECT * FROM transaksi WHERE id = :id");
$query->execute([':id' => $id]);

$transaksi = $query->fetch();

if (!$transaksi) {
    echo "Data Transaksi tidak ditemukan.";
    exit;
} else {
  $query = $DBH->prepare("
    SELECT *
    FROM detail_transaksi
    WHERE transaksi_id = :id
");

$query->execute([':id' => $id]);
$detail = $query->fetchAll();
}

include 'components/header.php';
include 'components/navbar.php';
?>

  <!-- ================= MAIN EDIT FORM CONTAINER (DEDICATED STANDALONE PAGE, NO MODAL) ================= -->
  <main class="py-10 px-4 sm:px-8 flex-1">
    <div class="max-w-3xl mx-auto bg-white border border-slate-200 shadow-sm p-8 sm:p-12">

      <!-- Breadcrumb & Header Title -->
      <div class="pb-6 mb-8 border-b border-slate-100">
        <h1 class="text-2xl sm:text-3xl font-extrabold uppercase tracking-tight text-slate-900 font-serif">
          Edit Informasi Transaksi
        </h1>
        
      </div>
    <!-- MASTER TRANSAKSI -->
    <div class="bg-white p-6 rounded-xl shadow">

        <h1 class="text-2xl font-bold mb-5">Detail Transaksi</h1>

        <div class="grid grid-cols-2 gap-4">

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
      <form method="POST" action="update_Transaksi.php" class="space-y-6">
          <input type="hidden" name="id"
            value="<?= htmlspecialchars($Transaksi['id']) ?>">


        <!-- Action Form Buttons -->
        <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-end gap-3">
          <a href="index.php" class="w-full sm:w-auto text-center px-6 py-2.5 border border-slate-200 hover:border-slate-900 text-slate-700 font-bold text-xs uppercase tracking-wider transition-colors">
            Batal
          </a>
          <button type="submit" class="w-full sm:w-auto px-8 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs uppercase tracking-widest transition-colors shadow-sm flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>Simpan Perubahan</span>
          </button>
        </div>

      </form>

    </div>
  </main>

<?php include 'components/footer.php'; ?>
