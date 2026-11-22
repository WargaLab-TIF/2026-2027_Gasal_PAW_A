<?php

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

require_once __DIR__ . "/conn.php";
require_once __DIR__ . "/utils.php";

// DETEKSI ROUTE / PAGE
// Mendukung $_GET['page'] (misal: index.php?page=barang) dan $_SERVER['PATH_INFO'] (misal: index.php/barang)
$page = $_GET['page'] ?? null;
if (!$page && isset($_SERVER['PATH_INFO'])) {
  $page = trim($_SERVER['PATH_INFO'], '/');
}
if (!$page) {
  $page = isset($_SESSION['user_id']) ? 'home' : 'login';
}

// 1. ATURAN PROTEKSI SESI (SESSION & SECURITY)
$public_pages = ['login', 'sign-in'];

// Aturan 1a: Jika user belum login, TIDAK BOLEH mengakses semua menu navigasi
if (!isset($_SESSION['user_id']) && !in_array($page, $public_pages)) {
  header("Location: " . url('login'));
  exit();
}

// Aturan 1b: Jika user sudah login, TIDAK BOLEH mengakses halaman login lagi
if (isset($_SESSION['user_id']) && in_array($page, $public_pages)) {
  header("Location: " . url('home'));
  exit();
}

// 2. ATURAN HAK AKSES ROLE (RBAC): Kasir (Level 2) Dilarang Mengakses Data Master
$master_pages = [
  'barang', 'barang-add', 'barang-edit', 'barang-save', 'barang-update', 'barang-delete',
  'supplier', 'supplier-add', 'supplier-edit', 'supplier-save', 'supplier-update', 'supplier-delete',
  'pelanggan', 'pelanggan-add', 'pelanggan-edit', 'pelanggan-save', 'pelanggan-update', 'pelanggan-delete',
  'user', 'user-add', 'user-edit', 'user-save', 'user-update', 'user-delete'
];

if (isset($_SESSION['level']) && (int)$_SESSION['level'] === 2 && in_array($page, $master_pages)) {
  setFlash('warning', 'Akses Ditolak! Akun Kasir (Level 2) tidak memiliki izin untuk mengakses halaman Data Master.');
  header("Location: " . url('home'));
  exit();
}

// 3. ROUTER & CONTROLLER DISPATCHER
switch ($page) {

  // ================= AUTHENTICATION =================
  case 'login':
    require_once __DIR__ . "/views/login.php";
    break;

  case 'sign-in':
    require_once __DIR__ . "/services/auth/login.php";
    break;

  case 'logout':
    require_once __DIR__ . "/services/auth/logout.php";
    break;

  // ================= HOME / DASHBOARD =================
  case 'home':
    require_once __DIR__ . "/views/home.php";
    break;

  // ================= CRUD DATA BARANG (LEVEL 1 ONLY) =================
  case 'barang':
    require_once __DIR__ . "/views/barang/index.php";
    break;

  case 'barang-add':
    require_once __DIR__ . "/views/barang/form.php";
    break;

  case 'barang-edit':
    $id = (int)($_GET['id'] ?? 0);
    $stmt = $conn->prepare("SELECT * FROM barang WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch();
    if (!$item) {
      setFlash('error', 'Data barang tidak ditemukan.');
      header("Location: " . url('barang'));
      exit();
    }
    require_once __DIR__ . "/views/barang/form.php";
    break;

  case 'barang-save':
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $kode = sanitazion($_POST['kode_barang'] ?? '');
      $nama = sanitazion($_POST['nama_barang'] ?? '');
      $harga = (int)($_POST['harga'] ?? 0);
      $stok = (int)($_POST['stok'] ?? 0);
      $supplier_id = (int)($_POST['supplier_id'] ?? 0);

      $stmt = $conn->prepare("INSERT INTO barang (kode_barang, nama_barang, harga, stok, supplier_id) VALUES (?, ?, ?, ?, ?)");
      $stmt->execute([$kode, $nama, $harga, $stok, $supplier_id]);
      setFlash('success', "Barang '$nama' berhasil ditambahkan.");
    }
    header("Location: " . url('barang'));
    exit();

  case 'barang-update':
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $id = (int)($_POST['id'] ?? 0);
      $kode = sanitazion($_POST['kode_barang'] ?? '');
      $nama = sanitazion($_POST['nama_barang'] ?? '');
      $harga = (int)($_POST['harga'] ?? 0);
      $stok = (int)($_POST['stok'] ?? 0);
      $supplier_id = (int)($_POST['supplier_id'] ?? 0);

      $stmt = $conn->prepare("UPDATE barang SET kode_barang = ?, nama_barang = ?, harga = ?, stok = ?, supplier_id = ? WHERE id = ?");
      $stmt->execute([$kode, $nama, $harga, $stok, $supplier_id, $id]);
      setFlash('success', "Data barang '$nama' berhasil diperbarui.");
    }
    header("Location: " . url('barang'));
    exit();

  case 'barang-delete':
    $id = (int)($_GET['id'] ?? 0);
    // Pengecekan relasi ke transaksi_detail
    $cek = $conn->prepare("SELECT COUNT(*) FROM transaksi_detail WHERE barang_id = ?");
    $cek->execute([$id]);
    if ($cek->fetchColumn() > 0) {
      setFlash('error', 'Barang TIDAK DAPAT dihapus karena masih digunakan pada data transaksi detail!');
    } else {
      $del = $conn->prepare("DELETE FROM barang WHERE id = ?");
      $del->execute([$id]);
      setFlash('success', 'Data barang berhasil dihapus.');
    }
    header("Location: " . url('barang'));
    exit();

  // ================= CRUD DATA SUPPLIER (LEVEL 1 ONLY) =================
  case 'supplier':
    require_once __DIR__ . "/views/supplier/index.php";
    break;

  case 'supplier-add':
    require_once __DIR__ . "/views/supplier/form.php";
    break;

  case 'supplier-edit':
    $id = (int)($_GET['id'] ?? 0);
    $stmt = $conn->prepare("SELECT * FROM supplier WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch();
    if (!$item) {
      setFlash('error', 'Data supplier tidak ditemukan.');
      header("Location: " . url('supplier'));
      exit();
    }
    require_once __DIR__ . "/views/supplier/form.php";
    break;

  case 'supplier-save':
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $nama = sanitazion($_POST['nama'] ?? '');
      $telp = sanitazion($_POST['telp'] ?? '');
      $alamat = sanitazion($_POST['alamat'] ?? '');

      $stmt = $conn->prepare("INSERT INTO supplier (nama, telp, alamat) VALUES (?, ?, ?)");
      $stmt->execute([$nama, $telp, $alamat]);
      setFlash('success', "Supplier '$nama' berhasil ditambahkan.");
    }
    header("Location: " . url('supplier'));
    exit();

  case 'supplier-update':
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $id = (int)($_POST['id'] ?? 0);
      $nama = sanitazion($_POST['nama'] ?? '');
      $telp = sanitazion($_POST['telp'] ?? '');
      $alamat = sanitazion($_POST['alamat'] ?? '');

      $stmt = $conn->prepare("UPDATE supplier SET nama = ?, telp = ?, alamat = ? WHERE id = ?");
      $stmt->execute([$nama, $telp, $alamat, $id]);
      setFlash('success', "Data supplier '$nama' berhasil diperbarui.");
    }
    header("Location: " . url('supplier'));
    exit();

  case 'supplier-delete':
    $id = (int)($_GET['id'] ?? 0);
    $cek = $conn->prepare("SELECT COUNT(*) FROM barang WHERE supplier_id = ?");
    $cek->execute([$id]);
    if ($cek->fetchColumn() > 0) {
      setFlash('error', 'Supplier TIDAK DAPAT dihapus karena masih ada barang pasokan yang terhubung!');
    } else {
      $del = $conn->prepare("DELETE FROM supplier WHERE id = ?");
      $del->execute([$id]);
      setFlash('success', 'Data supplier berhasil dihapus.');
    }
    header("Location: " . url('supplier'));
    exit();

  // ================= CRUD DATA PELANGGAN (LEVEL 1 ONLY) =================
  case 'pelanggan':
    require_once __DIR__ . "/views/pelanggan/index.php";
    break;

  case 'pelanggan-add':
    require_once __DIR__ . "/views/pelanggan/form.php";
    break;

  case 'pelanggan-edit':
    $id = (int)($_GET['id'] ?? 0);
    $stmt = $conn->prepare("SELECT * FROM pelanggan WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch();
    if (!$item) {
      setFlash('error', 'Data pelanggan tidak ditemukan.');
      header("Location: " . url('pelanggan'));
      exit();
    }
    require_once __DIR__ . "/views/pelanggan/form.php";
    break;

  case 'pelanggan-save':
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $nama = sanitazion($_POST['nama'] ?? '');
      $jk = sanitazion($_POST['jenis_kelamin'] ?? 'L');
      $telp = sanitazion($_POST['telp'] ?? '');
      $alamat = sanitazion($_POST['alamat'] ?? '');

      $stmt = $conn->prepare("INSERT INTO pelanggan (nama, jenis_kelamin, telp, alamat) VALUES (?, ?, ?, ?)");
      $stmt->execute([$nama, $jk, $telp, $alamat]);
      setFlash('success', "Pelanggan '$nama' berhasil ditambahkan.");
    }
    header("Location: " . url('pelanggan'));
    exit();

  case 'pelanggan-update':
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $id = (int)($_POST['id'] ?? 0);
      $nama = sanitazion($_POST['nama'] ?? '');
      $jk = sanitazion($_POST['jenis_kelamin'] ?? 'L');
      $telp = sanitazion($_POST['telp'] ?? '');
      $alamat = sanitazion($_POST['alamat'] ?? '');

      $stmt = $conn->prepare("UPDATE pelanggan SET nama = ?, jenis_kelamin = ?, telp = ?, alamat = ? WHERE id = ?");
      $stmt->execute([$nama, $jk, $telp, $alamat, $id]);
      setFlash('success', "Data pelanggan '$nama' berhasil diperbarui.");
    }
    header("Location: " . url('pelanggan'));
    exit();

  case 'pelanggan-delete':
    $id = (int)($_GET['id'] ?? 0);
    $cek = $conn->prepare("SELECT COUNT(*) FROM transaksi WHERE pelanggan_id = ?");
    $cek->execute([$id]);
    if ($cek->fetchColumn() > 0) {
      setFlash('error', 'Pelanggan TIDAK DAPAT dihapus karena memiliki riwayat transaksi!');
    } else {
      $del = $conn->prepare("DELETE FROM pelanggan WHERE id = ?");
      $del->execute([$id]);
      setFlash('success', 'Data pelanggan berhasil dihapus.');
    }
    header("Location: " . url('pelanggan'));
    exit();

  // ================= CRUD DATA USER (LEVEL 1 ONLY) =================
  case 'user':
    require_once __DIR__ . "/views/user/index.php";
    break;

  case 'user-add':
    require_once __DIR__ . "/views/user/form.php";
    break;

  case 'user-edit':
    $id = (int)($_GET['id'] ?? 0);
    $stmt = $conn->prepare("SELECT * FROM user WHERE id_user = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch();
    if (!$item) {
      setFlash('error', 'Data user tidak ditemukan.');
      header("Location: " . url('user'));
      exit();
    }
    require_once __DIR__ . "/views/user/form.php";
    break;

  case 'user-save':
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $username = sanitazion($_POST['username'] ?? '');
      $password = trim($_POST['password'] ?? '');
      $nama = sanitazion($_POST['nama'] ?? '');
      $level = (int)($_POST['level'] ?? 2);
      $hp = sanitazion($_POST['hp'] ?? '');
      $alamat = sanitazion($_POST['alamat'] ?? '');

      // Cek apakah username sudah dipakai
      $cek = $conn->prepare("SELECT COUNT(*) FROM user WHERE username = ?");
      $cek->execute([$username]);
      if ($cek->fetchColumn() > 0) {
        setFlash('error', "Username '$username' sudah digunakan, silakan pilih username lain.");
        header("Location: " . url('user-add'));
        exit();
      }

      $passHash = md5($password);
      $stmt = $conn->prepare("INSERT INTO user (username, password, nama, level, hp, alamat) VALUES (?, ?, ?, ?, ?, ?)");
      $stmt->execute([$username, $passHash, $nama, $level, $hp, $alamat]);
      setFlash('success', "User '$username' berhasil dibuat.");
    }
    header("Location: " . url('user'));
    exit();

  case 'user-update':
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $id = (int)($_POST['id'] ?? 0);
      $username = sanitazion($_POST['username'] ?? '');
      $password = trim($_POST['password'] ?? '');
      $nama = sanitazion($_POST['nama'] ?? '');
      $level = (int)($_POST['level'] ?? 2);
      $hp = sanitazion($_POST['hp'] ?? '');
      $alamat = sanitazion($_POST['alamat'] ?? '');

      // Cek duplikasi username untuk user lain
      $cek = $conn->prepare("SELECT COUNT(*) FROM user WHERE username = ? AND id_user != ?");
      $cek->execute([$username, $id]);
      if ($cek->fetchColumn() > 0) {
        setFlash('error', "Username '$username' sudah dipakai oleh user lain.");
        header("Location: " . url('user-edit', ['id' => $id]));
        exit();
      }

      if (!empty($password)) {
        $passHash = md5($password);
        $stmt = $conn->prepare("UPDATE user SET username = ?, password = ?, nama = ?, level = ?, hp = ?, alamat = ? WHERE id_user = ?");
        $stmt->execute([$username, $passHash, $nama, $level, $hp, $alamat, $id]);
      } else {
        $stmt = $conn->prepare("UPDATE user SET username = ?, nama = ?, level = ?, hp = ?, alamat = ? WHERE id_user = ?");
        $stmt->execute([$username, $nama, $level, $hp, $alamat, $id]);
      }

      // Jika yang di-update adalah akun yang sedang aktif, update juga nama di session
      if ($id == $_SESSION['user_id']) {
        $_SESSION['nama'] = $nama;
        $_SESSION['username'] = $username;
        $_SESSION['level'] = $level;
      }

      setFlash('success', "Data user '$username' berhasil diperbarui.");
    }
    header("Location: " . url('user'));
    exit();

  case 'user-delete':
    $id = (int)($_GET['id'] ?? 0);
    if ($id == $_SESSION['user_id']) {
      setFlash('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif login!');
    } else {
      $del = $conn->prepare("DELETE FROM user WHERE id_user = ?");
      $del->execute([$id]);
      setFlash('success', 'User berhasil dihapus.');
    }
    header("Location: " . url('user'));
    exit();

  // ================= TRANSAKSI & TRANSAKSI DETAIL =================
  case 'transaksi':
    require_once __DIR__ . "/views/transaksi/index.php";
    break;

  case 'transaksi-add':
    require_once __DIR__ . "/views/transaksi/form.php";
    break;

  case 'transaksi-save':
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $pelanggan_id = (int)($_POST['pelanggan_id'] ?? 0);
      $tgl = sanitazion($_POST['waktu_transaksi'] ?? date('Y-m-d'));
      $ket = sanitazion($_POST['keterangan'] ?? '');

      if ($pelanggan_id > 0) {
        $stmt = $conn->prepare("INSERT INTO transaksi (waktu_transaksi, keterangan, total, pelanggan_id) VALUES (?, ?, 0, ?)");
        $stmt->execute([$tgl, $ket, $pelanggan_id]);
        $newId = $conn->lastInsertId();
        setFlash('success', 'Transaksi baru berhasil dibuat. Silakan tambahkan rincian barang.');
        header("Location: " . url('transaksi', ['transaksi_id' => $newId]));
        exit();
      }
    }
    header("Location: " . url('transaksi'));
    exit();

  case 'transaksi-detail-add':
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $transaksi_id = (int)($_POST['transaksi_id'] ?? 0);
      $barang_id = (int)($_POST['barang_id'] ?? 0);
      $qty = (int)($_POST['qty'] ?? 1);

      if ($transaksi_id > 0 && $barang_id > 0 && $qty > 0) {
        // Cek duplikasi barang pada transaksi yang sama
        $cek = $conn->prepare("SELECT COUNT(*) FROM transaksi_detail WHERE transaksi_id = ? AND barang_id = ?");
        $cek->execute([$transaksi_id, $barang_id]);
        if ($cek->fetchColumn() > 0) {
          setFlash('error', 'Barang ini sudah ada dalam detail transaksi!');
        } else {
          // Ambil harga barang
          $stmtB = $conn->prepare("SELECT harga, nama_barang FROM barang WHERE id = ?");
          $stmtB->execute([$barang_id]);
          $b = $stmtB->fetch();

          if ($b) {
            $harga = (int)$b['harga'];
            $ins = $conn->prepare("INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) VALUES (?, ?, ?, ?)");
            $ins->execute([$transaksi_id, $barang_id, $harga, $qty]);

            // Hitung ulang total transaksi: SUM(harga * qty)
            $calc = $conn->prepare("SELECT COALESCE(SUM(harga * qty), 0) FROM transaksi_detail WHERE transaksi_id = ?");
            $calc->execute([$transaksi_id]);
            $totalBaru = $calc->fetchColumn();

            // Simpan hasil ke kolom total pada tabel transaksi
            $upd = $conn->prepare("UPDATE transaksi SET total = ? WHERE id = ?");
            $upd->execute([$totalBaru, $transaksi_id]);

            setFlash('success', "Item '{$b['nama_barang']}' berhasil ditambahkan ke detail. Total transaksi diperbarui menjadi Rp " . number_format($totalBaru, 0, ',', '.'));
          }
        }
        header("Location: " . url('transaksi', ['transaksi_id' => $transaksi_id]));
        exit();
      }
    }
    header("Location: " . url('transaksi'));
    exit();

  case 'transaksi-detail-delete':
    $transaksi_id = (int)($_GET['transaksi_id'] ?? 0);
    $barang_id = (int)($_GET['barang_id'] ?? 0);

    if ($transaksi_id > 0 && $barang_id > 0) {
      $del = $conn->prepare("DELETE FROM transaksi_detail WHERE transaksi_id = ? AND barang_id = ?");
      $del->execute([$transaksi_id, $barang_id]);

      // Hitung ulang total transaksi
      $calc = $conn->prepare("SELECT COALESCE(SUM(harga * qty), 0) FROM transaksi_detail WHERE transaksi_id = ?");
      $calc->execute([$transaksi_id]);
      $totalBaru = $calc->fetchColumn();

      $upd = $conn->prepare("UPDATE transaksi SET total = ? WHERE id = ?");
      $upd->execute([$totalBaru, $transaksi_id]);

      setFlash('success', "Item berhasil dihapus dari detail. Total transaksi otomatis diperbarui.");
      header("Location: " . url('transaksi', ['transaksi_id' => $transaksi_id]));
      exit();
    }
    header("Location: " . url('transaksi'));
    exit();

  // ================= LAPORAN / REPORTING =================
  case 'reports':
    require_once __DIR__ . "/views/reports/index.php";
    break;

  default:
    // Halaman 404 sederhana
    $page_title = "404 - Halaman Tidak Ditemukan";
    require_once __DIR__ . "/views/partials/header.php";
    echo '<div class="alert alert-danger" style="text-align: center; margin: 40px auto; max-width: 600px;">
            <h2>404 - Halaman Tidak Ditemukan</h2>
            <p>Halaman yang Anda tuju tidak tersedia.</p>
            <a href="' . url('home') . '" class="btn btn-submit">Kembali ke Home</a>
          </div>';
    require_once __DIR__ . "/views/partials/footer.php";
    break;
}
