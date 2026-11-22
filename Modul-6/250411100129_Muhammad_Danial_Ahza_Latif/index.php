<?php
$active_page = "katalog";
$body_class = "bg-white";

include 'components/header.php';
include 'components/navbar.php';
?>

  <!-- ================= PAGE BANNER ================= -->

  <main class="py-10 px-4 sm:px-8 flex-1">
        <!-- table -->
        <div class="overflow-x-auto border border-slate-200 bg-white">
          <?php include 'table.inc'; ?>
        </div>
      </div>

    </div>
  </main>

<?php include 'components/footer.php'; ?>
