# Database Gudang Sekolah / TKJ (MySQL / MariaDB / phpMyAdmin)

Dokumentasi dan file SQL DDL yang **100% kompatibel dengan MySQL & MariaDB (phpMyAdmin / XAMPP)**.

---

## File dalam Direktori Ini

1. **[`schema.sql`](file:///c:/Users/nafuser/Desktop/Hello/GUDANG_TKJ/Source/db/schema.sql)**: Skema DDL MySQL/MariaDB yang memuat 7 tabel utama, Foreign Key `ENGINE=InnoDB`, `ENUM`, dan penanda waktu `created_at` & `updated_at`.
2. **[`seed.sql`](file:///c:/Users/nafuser/Desktop/Hello/GUDANG_TKJ/Source/db/seed.sql)**: Data dummy awal menggunakan fungsi `UUID()` MySQL.

---

## Cara Import di phpMyAdmin

1. Buka **phpMyAdmin** di browser (`http://localhost/phpmyadmin`).
2. Buat database baru bernama **`gudang_tkj`** (Pilih Collation: `utf8mb4_unicode_ci`).
3. Pilih database **`gudang_tkj`**, lalu klik menu **Import** (atau menu **SQL**).
4. **Langkah 1**: Upload dan import file [`schema.sql`](file:///c:/Users/nafuser/Desktop/Hello/GUDANG_TKJ/Source/db/schema.sql) untuk membuat tabel.
5. **Langkah 2 (Opsional)**: Upload dan import file [`seed.sql`](file:///c:/Users/nafuser/Desktop/Hello/GUDANG_TKJ/Source/db/seed.sql) untuk mengisi data awal.
