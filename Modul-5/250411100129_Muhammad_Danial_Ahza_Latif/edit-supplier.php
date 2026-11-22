<?php

require_once 'koneksi.php';

$page_title = "Edit Data Supplier";
$active_page = "edit";
$body_class = "bg-[#F8F9FA]";
$nav_container = "max-w-5xl";
$show_back_link = true;
$id = $_GET['id'] ?? '';

if (!ctype_digit((string) $id)) {
    die("ID supplier tidak valid.");
}

$query = $DBH->prepare("SELECT * FROM supplier WHERE id = :id");
$query->execute([':id' => $id]);

$supplier = $query->fetch();

if (!$supplier) {
    echo "Data Supplier tidak ditemukan.";
    exit;
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
          Edit Informasi Supplier
        </h1>
        
      </div>

      <form method="POST" action="update_supplier.php" class="space-y-6">
          <input type="hidden" name="id"
            value="<?= htmlspecialchars($supplier['id']) ?>">
        <!-- Nama Supplier (nama) -->
        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
            Nama Supplier<span class="text-rose-500">*</span>
          </label>
          <input type="text" name="nama" value="<?= htmlspecialchars($supplier['nama']) ?>" required
            class="w-full px-4 py-2.5 border border-slate-200 text-xs text-slate-900 focus:outline-none focus:border-slate-900">
        </div>

        <!-- Telepon & Alamat (telp & alamat) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
          <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
              Nomor Telepon <span class="text-rose-500">*</span>
            </label>
            <input type="text" name="telp" value="<?= htmlspecialchars($supplier['telp']) ?>" required
              class="w-full px-4 py-2.5 border border-slate-200 text-xs font-mono text-slate-900 focus:outline-none focus:border-slate-900">
          </div>
          <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
              Alamat <span class="text-rose-500">*</span>
            </label>
            <input type="text" name="alamat" value="<?= htmlspecialchars($supplier['alamat']) ?>" required
              class="w-full px-4 py-2.5 border border-slate-200 text-xs font-mono text-slate-900 focus:outline-none focus:border-slate-900">
          </div>
        </div>

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
