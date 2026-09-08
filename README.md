# gudang_tjkt

### Gudang Sekolah / TJKT — Sistem Informasi Inventaris, Transaksi & Peminjaman

Sistem Informasi **Inventaris Gudang Sekolah** berbasis web yang dikembangkan untuk
**SMK Negeri 2 Pangkalpinang**. Aplikasi ini mengelola pendataan barang, rak penyimpanan,
transaksi barang masuk/keluar, sirkulasi peminjaman alat, hingga log aktivitas pengguna —
dengan dukungan **multi-jurusan**, **multi-peran**, **barcode scanner**, dan **PWA (offline-ready)**.

---

## Fitur Utama

- **Autentikasi Multi-Peran** — Login dengan sesi aman (HttpOnly, SameSite, Strict Mode), CSRF protection.
- **Multi-Jurusan** — Data (pengguna, barang, rak, transaksi) dapat di-scope per jurusan dengan **warna tema otomatis** per jurusan.
- **Impersonation / Login Sebagai** — Admin Sekolah dapat masuk ke akun lain lalu kembali kapan saja.
- **Dashboard Statistik** — Kartu statistik real-time (total barang, stok tersedia, transaksi, peminjaman) + grafik (Chart.js).
- **Manajemen Data** — Jurusan, Pengguna, Kategori, Rak, Barang dengan operasi CRUD penuh + **hapus massal (bulk delete)**.
- **Barcode** — Pencarian barang via **scan barcode** (webcam) atau input manual, cetak/tampil barcode (JsBarcode/QRCode).
- **Transaksi** — Pencatatan **Barang Masuk** & **Barang Keluar** yang otomatis meng-update stok (total & tersedia).
- **Sirkulasi Peminjaman** — Pinjam, kembalikan dengan **unggah bukti foto**, alur **persetujuan** (approve/reject) yang merestorasi stok otomatis, status *dipinjam/dikembalikan/terlambat*.
- **Log Aktivitas** — Catatan otomatis seluruh aksi penting pengguna, dapat dibersihkan oleh admin.
- **Import CSV** — Impor data barang massal dari file CSV (udah anti-UTF-8 BOM, idempoten terhadap `kode_barang`).
- **Upload Foto Profil** — Kompresi gambar otomatis ke WebP (max 1200px) disimpan dengan ekstensi `.dat`.
- **PWA** — Service Worker + Manifest untuk mode offline dan kualitas performa.
- **Tema Terang/Gelap** — Dark mode toggle.
- **Dokumentasi API** — UI **Swagger/OpenAPI 3.0** bawaan (khusus Admin Sekolah).
- **Desain Responsif** — Sidebar collapsible + mobile drawer, layout adaptif.

## Peran & Hak Akses

| Fitur | Admin Sekolah | Admin Jurusan | Petugas | Siswa |
| ------------------------------ | :-----------: | :-----------: | :-----: | :---: |
| Dashboard & Data Barang | ✅ | ✅ | ✅ | ✅ (view) |
| Kelola Jurusan | ✅ | ❌ | ❌ | ❌ |
| Kelola Pengguna | ✅ | ✅ (jurusan sendiri) | ❌ | ❌ |
| Kategori, Rak, Barang | ✅ | ✅ | ✅ | ❌ |
| Barang Masuk / Keluar | ✅ | ✅ | ✅ | ❌ |
| Peminjaman & Pengembalian | ✅ | ✅ | ✅ | ✅ (mengajukan) |
| Persetujuan Pengembalian | ✅ | ✅ | ✅ | ❌ |
| Log Aktivitas | ✅ | ✅ (jurusan sendiri) | ❌ | ❌ |
| Impor CSV Barang | ✅ | ✅ | ✅ | ❌ |
| Login Sebagai (Impersonation) | ✅ | ❌ | ❌ | ❌ |
| Swagger API Docs | ✅ | ❌ | ❌ | ❌ |

## Teknologi

- **Backend:** PHP (native, arsitektur sederhana MVC), PDO dengan prepared statements
- **Database:** MySQL / MariaDB (phpMyAdmin / XAMPP compatible), `utf8mb4_unicode_ci`, UUID primary key
- **Frontend:** HTML, Tailwind CSS (`tailwind.min.js`), JavaScript vanilla, Chart.js, JsBarcode, jQuery QRCode
- **PWA:** Service Worker (`sw.js`) + Web App Manifest (`manifest.json`)
- **API:** OpenAPI 3.0.3 / Swagger UI (`swagger.php`, `swagger_ui.php`)

## Struktur Proyek

```
GUDANG_TKJ/
├── ARSITEKTUR/                  # Diagram perancangan (drawio)
│   ├── ERD_DATABASE.drawio
│   ├── USE_CASE_DIAGRAM.drawio  (+ .png)
│   └── example/
└── Source/                      # Kode utama aplikasi
    ├── index.php                # Entry point (redirect login/dashboard)
    ├── login.php / logout.php / register.php / dashboard.php
    ├── api.php                  # Backend AJAX CRUD handler utama
    ├── swagger.php / swagger_ui.php   # Dokumentasi API
    ├── manifest.json / sw.js    # PWA config
    ├── .htaccess
    ├── app/
    │   ├── config/database.php  # Koneksi PDO
    │   ├── controllers/         # AuthController, dll.
    │   ├── helpers/auth.php     # Session, CSRF, upload & kompresi gambar
    │   ├── models/              # Pengguna, Jurusan, Kategori, Rak,
    │   │                        # Barang, BarangMasuk, BarangKeluar,
    │   │                        # Peminjaman, LogAktivitas
    │   └── views/               # layout (sidebar/navbar/modal) & dashboard
    ├── api/                     # REST API endpoint terpisah + openapi.json
    │   ├── login.php, barang.php, peminjaman.php, rak.php,
    │   │   scan.php, return_peminjaman.php, approve/reject_peminjaman.php
    ├── assets/                  # img (ikon/belmoti) & js (chart, barcode, qrcode, tailwind)
    ├── database/                # schema.sql & seed.sql (+ README)
    ├── public/assets/           # Aset statis publik
    ├── uploads/                 # avatar & bukti foto pengembalian
    └── setup_db.php             # Auto-init database via CLI
```

## Instalasi (XAMPP / Laragon / PHP Lokal)

1. **Salin** folder `Source/` ke direktori web server Anda, mis. `C:\xampp\htdocs\gudang_tkj`.
2. **Database:** Buka `http://localhost/phpmyadmin`, buat database `gudang_tkj`
   (Collation: `utf8mb4_unicode_ci`), lalu import `database/schema.sql` dan (opsional) `database/seed.sql`.
   > Alternatif cepat: jalankan `php setup_db.php` — database, tabel, dan data seed otomatis dibuat.
3. **Konfigurasi koneksi:** Periksa `app/config/database.php`. Secara default mencoba kredensial
   `gudang_user`, fallback ke `root` (password kosong) — sesuaikan dengan server Anda.
4. **Jalankan:** Buka `http://localhost/gudang_tkj` → Anda akan diarahkan ke halaman login.
5. **Register** dinonaktifkan; akun dibuat oleh Admin Sekolah, atau gunakan akun seed di bawah.

## Akun Default (Dari Seed)

| Nama User | Kata Sandi | Peran |
| --------- | ---------- | ----- |
| `admin_tkj` | *(sesuai hash pada `database/seed.sql`)* | Admin |
| `petugas_gudang` | *(sesuai hash pada `database/seed.sql`)* | Petugas |
| `siswa_ahmad` | *(sesuai hash pada `database/seed.sql`)* | Siswa |

> Silakan ganti kata sandi setelah login pertama. Hash dapat di-generate ulang dengan
> `password_hash('password', PASSWORD_DEFAULT)`.

## Dokumentasi API

Aplikasi menyediakan REST API dengan spesifikasi **OpenAPI 3.0.3**:

- Spesifikasi: `Source/api/openapi.json`
- UI Swagger: `Source/swagger.php` (khusus **Admin Sekolah**, login terlebih dahulu)

Endpoint utama: login, info barang by ID/barcode, info rak beserta isinya, sirkulasi peminjaman,
pengembalian dengan bukti foto, dan persetujuan/tolak pengembalian.

## Diagram Arsitektur

File perancangan (dapat dibuka dengan [draw.io](https://app.diagrams.net/)) tersedia di folder `ARSITEKTUR/`:

- **ERD Database** — `ERD_DATABASE.drawio`
- **Use Case Diagram** — `USE_CASE_DIAGRAM.drawio` (+ panduan `USE_CASE_DIAGRAM.drawio.png`)

## Lisensi

Proyek ini dikembangkan untuk keperluan internal sekolah / tugas akademik. Dikembangkan oleh
**Belmoti** untuk **SMK Negeri 2 Pangkalpinang**.