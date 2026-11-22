<?php
$active_page = $active_page ?? 'katalog';
$nav_container = $nav_container ?? 'max-w-7xl';
?>
<!-- ================= TOP META BAR (Design Reference) ================= -->
<?php include_once __DIR__ . '/topbar.php'; ?>

<!-- ================= MAIN HEADER NAVBAR ================= -->
<header class="border-b border-slate-100 bg-white sticky top-0 z-40">
  <div class="<?= htmlspecialchars($nav_container) ?> mx-auto px-4 sm:px-8 py-5 flex items-center justify-between">

    <!-- Logo Brand (Sesuai design.jpg) -->
    <a href="index.php" class="flex items-center gap-2">
      <span class="text-2xl font-extrabold tracking-widest text-slate-900 uppercase font-serif">Tabel Master Supplier</span>
    </a>

    <!-- Action Link Sesuai Halaman -->
    <div class="flex items-center gap-3">
      <?php if ($active_page === 'katalog'): ?>
        <a href="tambah-supplier.php"
          class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs uppercase tracking-widest transition-colors flex items-center gap-2 shadow-sm">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          <span>+ Tambah Supplier</span>
        </a>
      <?php else: ?>
        <a href="index.php"
          class="px-4 py-2 border border-slate-200 hover:border-slate-900 text-slate-700 hover:text-slate-900 font-bold text-xs uppercase tracking-wider transition-colors inline-flex items-center gap-2">
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
          <span>Batal & Kembali</span>
        </a>
      <?php endif; ?>
    </div>

  </div>
</header>
