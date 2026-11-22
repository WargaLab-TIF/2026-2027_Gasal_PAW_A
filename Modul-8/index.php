<?php
session_start();

// Proteksi halaman: Jika belum login, tendang balik ke form login
if (!isset($_SESSION['level'])) {
    header("Location: login.php");
    exit;
}

$level = $_SESSION['level'];
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Sistem Penjualan</a>
            <!-- Tambahan tombol toggler agar responsif di HP -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    
                    <!-- Menu Home bisa diakses semua level -->
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    
                    <!-- Menu khusus Level 1 (Owner) -->
                    <?php if ($level == 1) : ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Data Master</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?page=barang">Data Barang</a></li>
                            <li><a class="dropdown-item" href="?page=supplier">Data Supplier</a></li>
                            <li><a class="dropdown-item" href="?page=pelanggan">Data Pelanggan</a></li>
                            <li><a class="dropdown-item" href="?page=user">Data User</a></li>
                        </ul>
                    </li>
                    <?php endif; ?>

                    <!-- Menu Transaksi & Laporan bisa diakses Level 1, 2, 3, dst -->
                    <li class="nav-item">
                        <a class="nav-link" href="?page=transaksi">Transaksi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?page=laporan">Laporan</a>
                    </li>
                </ul>
                
                <span class="navbar-text me-3">
                    Halo, <?php echo htmlspecialchars($username); ?> (Level: <?php echo $level; ?>)
                </span>
                <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php 
        // Tangkap parameter page dari URL
        $page = isset($_GET['page']) ? $_GET['page'] : '';

        // Menampilkan pesan selamat datang HANYA jika berada di halaman home (page kosong)
        if ($page == '') {
            echo "<h3>Selamat datang, " . htmlspecialchars($username) . "!</h3>";
            
            // Penentuan nama jabatan berdasarkan level user
            if ($level == 1) {
                echo "<p>Anda login sebagai Owner.</p>";
            } elseif ($level == 2) {
                echo "<p>Anda login sebagai Kasir.</p>";
            } elseif ($level == 3) {
                echo "<p>Anda login sebagai Gudang.</p>"; 
            } else {
                echo "<p>Anda login sebagai User.</p>";
            }
        }

        // Routing halaman (Include modul sesuai dengan menu yang diklik)
        if ($page == 'laporan') {
            require_once 'koneksi.php'; // Panggil koneksi database
            include 'laporan.php';      // Muat file laporan.php
        } 
        elseif ($page == 'transaksi') {
             require_once 'koneksi.php';
             include 'transaksi.php';
        }
        
        // PROTEKSI LEVEL 1 UNTUK DATA MASTER (Mencegah bypass URL)
        elseif ($page == 'barang' && $level == 1) {
             require_once 'koneksi.php';
             include 'barang.php';
        }
        elseif ($page == 'supplier' && $level == 1) {
             require_once 'koneksi.php';
             include 'supplier.php';
        }
        elseif ($page == 'pelanggan' && $level == 1) {
             require_once 'koneksi.php';
             include 'pelanggan.php';
        }
        elseif ($page == 'user' && $level == 1) {
             require_once 'koneksi.php';
             include 'user.php';
        }
        
        // Peringatan jika level 2 (atau selain level 1) mencoba mengetik URL Data Master secara manual
        elseif (in_array($page, ['barang', 'supplier', 'pelanggan', 'user']) && $level != 1) {
             echo "<div class='alert alert-danger'>
                    <h4>Akses Ditolak!</h4>
                    <p>Anda login sebagai Kasir. Anda tidak memiliki hak akses untuk mengelola Data Master.</p>
                   </div>";
        }
        ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>