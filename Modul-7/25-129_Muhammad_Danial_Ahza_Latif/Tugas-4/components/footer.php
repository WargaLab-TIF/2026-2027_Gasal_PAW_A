<?php
$footer_container = $nav_container ?? 'max-w-7xl';
$show_back_link = $show_back_link ?? false;
?>
  <!-- ================= FOOTER ================= -->
  <footer class="bg-slate-900 text-slate-400 py-8 text-xs border-t border-slate-800">
    <div class="<?= htmlspecialchars($footer_container) ?> mx-auto px-4 sm:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
      <span>&copy; Praktikum PAW 2026</span>
      <?php if ($show_back_link): ?>
        <a href="index.php" class="hover:text-white transition-colors">&larr; Kembali ke Katalog Utama</a>
      <?php endif; ?>
    </div>
  </footer>

</body>

</html>
