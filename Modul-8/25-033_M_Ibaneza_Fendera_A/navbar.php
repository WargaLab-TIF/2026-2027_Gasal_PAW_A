<?php
$level = $_SESSION['level'] ?? '';
if (isset($_SESSION['nama'])) {
    $nama_user = $_SESSION['nama'];
} else {
    $nama_user = 'User';
}

$halaman_awal = ($level == 1) ? '../owner/home.php' : '../kasir/home.php';
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
  <div class="container">
    <a class="navbar-brand fw-bold" href="<?= $halaman_awal; ?>">Sistem Penjualan</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link active" href="<?= $halaman_awal; ?>">Home</a>
        </li>

        <?php if ($level == 1): ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Data Master
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="../barang/barang.php">Data Barang</a></li>
            <li><a class="dropdown-item" href="../supplier/supplier.php">Data Supplier</a></li>
            <li><a class="dropdown-item" href="../pelanggan/pelanggan.php">Data Pelanggan</a></li>
            <li><a class="dropdown-item" href="../user/user.php">Data User</a></li>
          </ul>
        </li>
        <?php endif; ?>

        <li class="nav-item">
          <a class="nav-link" href="../transaksi/transaksi.php">Transaksi</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="../laporan/laporan.php">Laporan</a>
        </li>
      </ul>

      <ul class="navbar-nav ms-auto">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-white fw-semibold" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Halo, <?= htmlspecialchars($nama_user); ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
            <li><a class="dropdown-item text-danger" href="../auth/logout.php" onclick="return confirm('Yakin ingin logout?')">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>