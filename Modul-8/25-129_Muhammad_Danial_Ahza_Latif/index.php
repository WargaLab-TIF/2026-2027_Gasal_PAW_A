
<?php
require_once 'koneksi.php';
require_once 'auth.php';

// Memastikan pengguna sudah login
cekLogin();

$page_title = "Dashboard";
$active_page = "dashboard";
$body_class = "bg-[#F8F9FA]";

$nama_user = $_SESSION['user_login'];
$level = (int) $_SESSION['level'];

// Mengambil jumlah data dari database
$stmt = $DBH->query("SELECT COUNT(*) FROM barang");
$total_barang = $stmt->fetchColumn();

$stmt = $DBH->query("SELECT COUNT(*) FROM pelanggan");
$total_pelanggan = $stmt->fetchColumn();

$stmt = $DBH->query("SELECT COUNT(*) FROM supplier");
$total_supplier = $stmt->fetchColumn();

$stmt = $DBH->query("SELECT COUNT(*) FROM transaksi");
$total_transaksi = $stmt->fetchColumn();

// Mengambil total pendapatan transaksi
$stmt = $DBH->query("SELECT COALESCE(SUM(total), 0) FROM transaksi");
$total_pendapatan = $stmt->fetchColumn();

// Format rupiah
function formatRupiah($angka)
{
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

include 'components/header.php';
include 'components/navbar.php';
?>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

</main>

<?php include 'components/footer.php'; ?>
