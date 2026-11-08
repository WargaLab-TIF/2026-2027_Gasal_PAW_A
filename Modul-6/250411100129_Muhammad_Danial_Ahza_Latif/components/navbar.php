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
      <span class="text-2xl font-extrabold tracking-widest text-slate-900 uppercase font-serif">Tabel Master Transaksi</span>
    </a>
  </div>
</header>
