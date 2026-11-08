<?php
$page_title = "Tambah Data Supplier";
$active_page = "tambah";
$body_class = "bg-[#F8F9FA]";
$nav_container = "max-w-5xl";
$show_back_link = true;

include 'components/header.php';
include 'components/navbar.php';
?>

  <main class="py-10 px-4 sm:px-8 flex-1">
    <div class="max-w-3xl mx-auto bg-white border border-slate-200 shadow-sm p-8 sm:p-12">

      <!-- Breadcrumb & Header Title -->
      <div class="pb-6 mb-8 border-b border-slate-100">
        <h1 class="text-2xl sm:text-3xl font-extrabold uppercase tracking-tight text-slate-900 font-serif">
          Tambah Supplier Baru
        </h1>
      </div>

      <form method="POST" action="tambah_data.php" class="space-y-6">

        <!-- Nama Supplier (name) -->
        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
            Nama Supplier<span class="text-rose-500">*</span>
          </label>
          <input type="text" name="nama" required placeholder="Nama Supplier"
            class="w-full px-4 py-2.5 border border-slate-200 text-xs text-slate-900 focus:outline-none focus:border-slate-900 placeholder:text-slate-400">
        </div>

        <!-- Telepon & Alamat (telp & alamat) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
          <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
              Nomor Telepon <span class="text-rose-500">*</span>
            </label>
            <input type="text" name="telp" required placeholder="081234567890"
              class="w-full px-4 py-2.5 border border-slate-200 text-xs text-slate-900 focus:outline-none focus:border-slate-900 placeholder:text-slate-400">
          </div>
          <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
              Alamat <span class="text-rose-500">*</span>
            </label>
            <input type="text" name="alamat" required placeholder="Alamat Supplier"
              class="w-full px-4 py-2.5 border border-slate-200 text-xs text-slate-900 focus:outline-none focus:border-slate-900 placeholder:text-slate-400">
          </div>
        </div>

        <!-- Action Form Buttons -->
        <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-end gap-3">
          <a href="index.php"
            class="w-full sm:w-auto text-center px-6 py-2.5 border border-slate-200 hover:border-slate-900 text-slate-700 font-bold text-xs uppercase tracking-wider transition-colors">
            Batal
          </a>
          <button type="submit"
            class="w-full sm:w-auto px-8 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs uppercase tracking-widest transition-colors shadow-sm flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Simpan Data Supplier</span>
          </button>
        </div>

      </form>

    </div>
  </main>

<?php include 'components/footer.php'; ?>
