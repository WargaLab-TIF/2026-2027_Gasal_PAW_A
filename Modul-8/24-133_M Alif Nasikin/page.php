<?php
// Isi halaman. Hanya boleh dipanggil dari index.php.
if (!defined("DI_INDEX")) {
    header("Location: index.php");
    exit;
}
include_once "koneksi.php";
include_once "master.php";

$level = $_SESSION["level"];

/* ===================== HOME ===================== */
if ($p == "home") {
?>
    <h2>Selamat Datang di Sistem Penjualan</h2>
    <p>Anda login sebagai <b><?= e($_SESSION["nama"]) ?></b> (<?= $level == 1 ? "Owner" : "Kasir" ?>).</p>
    <p>Silahkan pilih menu di atas untuk mulai mengelola data.</p>
<?php

/* ============ DATA BARANG / SUPPLIER / PELANGGAN ============ */
} elseif (preg_match('/^(barang|supplier|pelanggan)(_tambah|_edit)?$/', $p, $m)) {
    $tabel = $m[1];
    $mode  = $m[2] ?? "";
    $judul = $MASTER[$tabel]["judul"];
    $kolom = $MASTER[$tabel]["kolom"];

    if ($mode == "") {
        // ----- Daftar data -----
        $daftar = ambil_semua("SELECT * FROM $tabel ORDER BY id");
?>
    <h2>Data <?= e($judul) ?></h2>
    <a class="tombol" href="index.php?p=<?= $tabel ?>_tambah">+ Tambah <?= e($judul) ?></a>
    <table>
        <tr>
            <?php foreach ($kolom as $info) { ?>
                <th><?= e($info[0]) ?></th>
            <?php } ?>
            <th>Aksi</th>
        </tr>
        <?php foreach ($daftar as $baris) { ?>
        <tr>
            <?php foreach ($kolom as $nama => $info) { ?>
                <td><?= e($baris[$nama]) ?></td>
            <?php } ?>
            <td>
                <a href="index.php?p=<?= $tabel ?>_edit&id=<?= $baris["id"] ?>">Edit</a>
                <form class="inline" method="POST" action="aksi.php?aksi=<?= $tabel ?>_hapus"
                      onsubmit="return confirm('Hapus data ini?')">
                    <input type="hidden" name="id" value="<?= $baris["id"] ?>">
                    <button class="link-hapus">Hapus</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
<?php
    } else {
        // ----- Form tambah / edit -----
        $edit = ($mode == "_edit");
        $id   = (int)($_GET["id"] ?? 0);
        $data = $edit ? ambil_satu("SELECT * FROM $tabel WHERE id=?", "i", $id) : [];

        if ($edit && !$data) {
            echo "<p>Data tidak ditemukan.</p>";
        } else {
?>
    <h2><?= $edit ? "Edit" : "Tambah" ?> <?= e($judul) ?></h2>
    <form method="POST" action="aksi.php?aksi=<?= $tabel ?>_<?= $edit ? "edit&id=" . $id : "tambah" ?>">
        <?php foreach ($kolom as $nama => $info) { ?>
            <label><?= e($info[0]) ?></label>
            <input type="<?= $info[1] ?>" name="<?= $nama ?>" value="<?= e($data[$nama] ?? "") ?>"
                   <?= $info[1] == "number" ? 'min="0"' : "" ?> <?= $info[2] ? "required" : "" ?>>
        <?php } ?>
        <br>
        <button><?= $edit ? "Update" : "Simpan" ?></button>
        <a href="index.php?p=<?= $tabel ?>">Batal</a>
    </form>
<?php
        }
    }

/* ===================== DATA USER ===================== */
} elseif (preg_match('/^user(_tambah|_edit)?$/', $p, $m)) {
    $mode = $m[1] ?? "";

    if ($mode == "") {
        $daftar = ambil_semua("SELECT * FROM user ORDER BY id");
?>
    <h2>Data User</h2>
    <a class="tombol" href="index.php?p=user_tambah">+ Tambah User</a>
    <table>
        <tr>
            <th>Username</th>
            <th>Nama Lengkap</th>
            <th>Level</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($daftar as $u) { ?>
        <tr>
            <td><?= e($u["username"]) ?></td>
            <td><?= e($u["nama"]) ?></td>
            <td><?= $u["level"] == 1 ? "1 (Owner)" : "2 (Kasir)" ?></td>
            <td>
                <a href="index.php?p=user_edit&id=<?= $u["id"] ?>">Edit</a>
                <?php if ($u["id"] != $_SESSION["id"]) { ?>
                <form class="inline" method="POST" action="aksi.php?aksi=user_hapus"
                      onsubmit="return confirm('Hapus user ini?')">
                    <input type="hidden" name="id" value="<?= $u["id"] ?>">
                    <button class="link-hapus">Hapus</button>
                </form>
                <?php } ?>
            </td>
        </tr>
        <?php } ?>
    </table>
<?php
    } else {
        $edit = ($mode == "_edit");
        $id   = (int)($_GET["id"] ?? 0);
        $data = $edit ? ambil_satu("SELECT * FROM user WHERE id=?", "i", $id) : [];

        if ($edit && !$data) {
            echo "<p>Data tidak ditemukan.</p>";
        } else {
?>
    <h2><?= $edit ? "Edit" : "Tambah" ?> User</h2>
    <form method="POST" action="aksi.php?aksi=user_<?= $edit ? "edit&id=" . $id : "tambah" ?>">
        <label>Username</label>
        <input name="username" value="<?= e($data["username"] ?? "") ?>" required>

        <label>Password</label>
        <input type="password" name="password"
               placeholder="<?= $edit ? "Kosongkan jika tidak diganti" : "" ?>" <?= $edit ? "" : "required" ?>>

        <label>Nama Lengkap</label>
        <input name="nama_lengkap" value="<?= e($data["nama"] ?? "") ?>" required>

        <label>Level</label>
        <select name="level">
            <option value="1" <?= ($data["level"] ?? 2) == 1 ? "selected" : "" ?>>1 - Owner</option>
            <option value="2" <?= ($data["level"] ?? 2) == 2 ? "selected" : "" ?>>2 - Kasir</option>
        </select>
        <br>
        <button><?= $edit ? "Update" : "Simpan" ?></button>
        <a href="index.php?p=user">Batal</a>
    </form>
<?php
        }
    }

/* ===================== TRANSAKSI ===================== */
} elseif ($p == "transaksi") {
    $pelanggan = ambil_semua("SELECT id, nama_pelanggan FROM pelanggan ORDER BY nama_pelanggan");
?>
    <h2>Transaksi Penjualan</h2>
    <form method="POST" action="aksi.php?aksi=transaksi">
        <label>Tanggal Transaksi</label>
        <input type="date" name="tanggal_transaksi" value="<?= date("Y-m-d") ?>" required>

        <label>Pelanggan</label>
        <select name="pelanggan_id">
            <option value="">- Umum -</option>
            <?php foreach ($pelanggan as $pl) { ?>
                <option value="<?= $pl["id"] ?>"><?= e($pl["nama_pelanggan"]) ?></option>
            <?php } ?>
        </select>

        <label>Total Pembayaran</label>
        <input type="number" name="total_pembayaran" min="1" required>
        <br>
        <button>Simpan Transaksi</button>
    </form>
<?php

/* ===================== LAPORAN ===================== */
} elseif ($p == "laporan") {
    $daftar = ambil_semua(
        "SELECT t.tanggal, p.nama_pelanggan, t.total
         FROM transaksi t LEFT JOIN pelanggan p ON t.pelanggan_id = p.id
         ORDER BY t.tanggal DESC, t.id DESC"
    );
    $jumlah = 0;
?>
    <h2>Laporan Transaksi</h2>
    <table>
        <tr>
            <th>Tanggal</th>
            <th>Pelanggan</th>
            <th>Total</th>
        </tr>
        <?php foreach ($daftar as $baris) { $jumlah += $baris["total"]; ?>
        <tr>
            <td><?= e($baris["tanggal"]) ?></td>
            <td><?= e($baris["nama_pelanggan"] ?? "Umum") ?></td>
            <td><?= number_format($baris["total"], 0, ",", ".") ?></td>
        </tr>
        <?php } ?>
        <tr>
            <th colspan="2">Total Omzet</th>
            <th><?= number_format($jumlah, 0, ",", ".") ?></th>
        </tr>
    </table>
<?php

/* ===================== LAINNYA ===================== */
} else {
    echo "<h2>Halaman tidak ditemukan</h2>";
}
