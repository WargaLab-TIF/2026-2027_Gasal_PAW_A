# Modul 6 – Master Detil Data: Cara Menjalankan

Proyek ini berisi 9 file (1 SQL + 8 PHP) di dalam satu folder.

| File | Fungsi |
|------|--------|
| `database.sql` | Membuat database `penjualan`, semua tabel, relasi, dan data awal (Tugas Praktikum 1) |
| `koneksi.php` | Koneksi MySQL + fungsi bantu (termasuk `hitung_ulang_total()` untuk Tugas 4) |
| `header.php` | Tampilan atas + menu (dipakai semua halaman) |
| `footer.php` | Tampilan bawah (dipakai semua halaman) |
| `index.php` | Daftar transaksi / data master |
| `transaksi_tambah.php` | Form membuat transaksi baru |
| `transaksi_detail.php` | Halaman master-detail (Tugas 2 dan 4) |
| `barang.php` | Daftar barang + tombol hapus |
| `barang_hapus.php` | Proses hapus barang dengan pengecekan (Tugas 3) |

---

## A. Persiapan (sekali saja)

1. Pasang **XAMPP** (https://www.apachefriends.org) jika belum ada. Laragon atau WAMP juga bisa.
2. Buka **XAMPP Control Panel**, klik **Start** pada **Apache** dan **MySQL**. Keduanya harus berwarna hijau.

## B. Memasang file

1. Ekstrak ZIP ini sehingga terbentuk folder `modul6` yang berisi file-file di atas.
2. Salin seluruh folder `modul6` ke folder `htdocs` XAMPP:
   - Windows: `C:\xampp\htdocs\modul6`
   - macOS: `/Applications/XAMPP/htdocs/modul6`
   - Linux: `/opt/lampp/htdocs/modul6`
3. Pastikan semua file PHP berada langsung di dalam `modul6` (bukan di subfolder lagi).

## C. Membuat database (Tugas Praktikum 1)

1. Buka browser, ketik `http://localhost/phpmyadmin`.
2. Klik tab **Import**.
3. Klik **Choose File**, pilih `database.sql` dari folder `modul6`.
4. Klik tombol **Import** (atau **Go**) di bagian bawah. Tunggu pesan hijau "Import has been successfully finished".
5. Di panel kiri akan muncul database **penjualan** dengan 7 tabel: `barang`, `pelanggan`, `pembayaran`, `supplier`, `transaksi`, `transaksi_detail`, `user`.

> **Peringatan:** `database.sql` diawali `DROP DATABASE IF EXISTS penjualan;`. Jika kamu sudah punya database bernama `penjualan` yang berisi data penting, hapus baris itu dulu atau backup databasenya.

**Mengecek relasi (untuk screenshot):** pilih database `penjualan` → tab **Designer**. Garis relasi antar tabel harus terlihat.

## D. Menjalankan aplikasi

Buka di browser: **http://localhost/modul6/index.php**

Jika MySQL kamu memakai password atau port selain bawaan XAMPP, ubah baris ini di `koneksi.php`:

```php
$koneksi = new mysqli("localhost", "root", "", "penjualan");
//                     host       user    password  nama_database
```

---

## E. Langkah uji tiap tugas (sekaligus bahan screenshot "Bukti")

### Tugas Praktikum 1 – Database
1. phpMyAdmin → database `penjualan` → tab **Designer** → screenshot relasi.
2. Buka tiap tabel (tab **Browse**) → screenshot isi data (master ≥ 5, transaksi = 10).

### Tugas Praktikum 2 – Master-detail & dropdown tanpa duplikasi
1. Buka `http://localhost/modul6/index.php`, klik **Detail** pada transaksi **#1**.
2. Bagian atas = data master (tanggal, pelanggan, total). Bagian bawah = daftar barang (detail).
3. Pada form **Tambah Barang**, buka dropdown: **Beras 5 Kg** dan **Gula Pasir 1 Kg tidak ada** karena sudah ada di transaksi ini. → screenshot.
4. Pilih **Garam 500 g**, isi qty `2`, klik **Simpan Detail**.
5. Garam masuk ke tabel detail dan **hilang dari dropdown**. → screenshot.
6. Untuk membuat transaksi baru: menu **+ Transaksi Baru** → isi tanggal, pelanggan → **Simpan & Lanjut ke Detail**.

### Tugas Praktikum 3 – Hapus barang dengan pengecekan
1. Klik menu **Data Barang** (`barang.php`). Kolom **Dipakai di Transaksi** menunjukkan berapa kali barang dipakai.
2. Klik **Hapus** pada **Beras 5 Kg (BRG001)** → konfirmasi **OK** → muncul notifikasi **merah**: barang tidak dapat dihapus karena masih digunakan. Data tetap ada. → screenshot.
3. Klik **Hapus** pada **Kecap Manis 600 ml (BRG010)** (belum pernah dipakai) → konfirmasi **OK** → muncul notifikasi **hijau**: berhasil dihapus. → screenshot.

> Catatan: jika di Tugas 2 kamu sudah memakai Garam (BRG009) pada sebuah transaksi, barang itu sekarang tidak bisa dihapus. Gunakan BRG010 (Kecap) untuk uji hapus yang berhasil.

### Tugas Praktikum 4 – Total transaksi otomatis ter-update
1. Buka transaksi **#3** (total awal **Rp 95.000**, berisi Mie Instan Dus × 1). → screenshot **sebelum**.
2. Tambah barang **Sabun Mandi** qty `5` (6.000 × 5 = 30.000).
3. Total di bagian master berubah menjadi **Rp 125.000** tanpa perlu mengedit manual. → screenshot **sesudah**.
4. (Opsional) Klik **Hapus** pada barang tadi di tabel detail → total kembali ke Rp 95.000.
5. Verifikasi di phpMyAdmin tab **SQL**:
   ```sql
   SELECT t.id, t.total, SUM(d.harga * d.qty) AS hitung_manual
   FROM transaksi t
   JOIN transaksi_detail d ON d.transaksi_id = t.id
   GROUP BY t.id, t.total;
   ```
   Kolom `total` dan `hitung_manual` harus sama di semua baris.

---

## F. Mengulang dari awal

Jika data sudah berantakan setelah uji coba, import ulang `database.sql` (langkah C). Database akan dibuat ulang dengan data awal.

## G. Masalah yang sering muncul

| Gejala | Penyebab / solusi |
|--------|-------------------|
| `Connection refused` / halaman error koneksi | MySQL di XAMPP belum di-**Start**. |
| `Unknown database 'penjualan'` | `database.sql` belum di-import (langkah C). |
| `Access denied for user 'root'` | Password MySQL tidak kosong. Ubah argumen ke-3 `mysqli(...)` di `koneksi.php`. |
| Halaman menampilkan kode PHP / file ter-download | File dibuka langsung (`file:///...`). Buka lewat `http://localhost/modul6/...`. |
| `404 Not Found` | Folder bukan di `htdocs`, atau nama folder bukan `modul6`. |
| Apache tidak mau Start (port 80 bentrok) | Tutup Skype/IIS atau ganti port Apache lewat **Config → httpd.conf**. Lalu akses dengan `http://localhost:PORT/modul6/`. |
| Notifikasi tidak muncul setelah simpan/hapus | Pastikan `header.php` dan `footer.php` ada di folder yang sama. |
