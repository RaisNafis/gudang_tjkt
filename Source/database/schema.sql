-- ============================================================
-- SKEMA DATABASE GUDANG SEKOLAH / TKJ
-- Dialek: MySQL / MariaDB (phpMyAdmin Compatible)
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------
-- 1. TABEL PENGGUNA (users)
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `pengguna`;
CREATE TABLE `pengguna` (
    `id` VARCHAR(36) NOT NULL PRIMARY KEY,
    `nama_pengguna` VARCHAR(255) NOT NULL UNIQUE,
    `nama_lengkap` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) DEFAULT NULL UNIQUE,
    `kata_sandi_hash` VARCHAR(255) NOT NULL,
    `peran` ENUM('admin', 'petugas', 'siswa') NOT NULL DEFAULT 'siswa',
    `foto_url` VARCHAR(255) DEFAULT NULL,
    `nomor_telepon` VARCHAR(50) DEFAULT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 2. TABEL LOG AKTIVITAS (log_aktivitas)
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `log_aktivitas`;
CREATE TABLE `log_aktivitas` (
    `id` VARCHAR(36) NOT NULL PRIMARY KEY,
    `pengguna_id` VARCHAR(36) DEFAULT NULL,
    `tindakan` VARCHAR(100) NOT NULL,
    `deskripsi` TEXT DEFAULT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_log_pengguna` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 3. TABEL KATEGORI (categories)
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `kategori`;
CREATE TABLE `kategori` (
    `id` VARCHAR(36) NOT NULL PRIMARY KEY,
    `kode_kategori` VARCHAR(50) NOT NULL UNIQUE,
    `nama_kategori` VARCHAR(255) NOT NULL,
    `deskripsi` TEXT DEFAULT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 3.5. TABEL RAK (raks)
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `rak`;
CREATE TABLE `rak` (
    `id` VARCHAR(36) NOT NULL PRIMARY KEY,
    `jurusan_id` VARCHAR(36) DEFAULT NULL,
    `nama_rak` VARCHAR(255) NOT NULL,
    `kategori_rak` VARCHAR(255) DEFAULT NULL,
    `keterangan` TEXT DEFAULT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_rak_jurusan` FOREIGN KEY (`jurusan_id`) REFERENCES `jurusan` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 4. TABEL BARANG (items)
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `barang`;
CREATE TABLE `barang` (
    `id` VARCHAR(36) NOT NULL PRIMARY KEY,
    `kategori_id` VARCHAR(36) DEFAULT NULL,
    `rak_id` VARCHAR(36) DEFAULT NULL,
    `kode_barang` VARCHAR(100) NOT NULL UNIQUE,
    `nama_barang` VARCHAR(255) NOT NULL,
    `merek` VARCHAR(100) DEFAULT NULL,
    `barcode` VARCHAR(100) DEFAULT NULL UNIQUE,
    `stok_total` INT NOT NULL DEFAULT 0,
    `stok_tersedia` INT NOT NULL DEFAULT 0,
    `jumlah` INT NOT NULL DEFAULT 0,
    `deskripsi` TEXT DEFAULT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_barang_kategori` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `fk_barang_rak` FOREIGN KEY (`rak_id`) REFERENCES `rak` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 5. TABEL BARANG MASUK (stock_in_transactions)
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `barang_masuk`;
CREATE TABLE `barang_masuk` (
    `id` VARCHAR(36) NOT NULL PRIMARY KEY,
    `barang_id` VARCHAR(36) NOT NULL,
    `pengguna_id` VARCHAR(36) DEFAULT NULL,
    `nama_pemasok` VARCHAR(255) DEFAULT NULL,
    `jumlah` INT NOT NULL,
    `tanggal_masuk` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `catatan` TEXT DEFAULT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_masuk_barang` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_masuk_pengguna` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 6. TABEL BARANG KELUAR (stock_out_transactions)
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `barang_keluar`;
CREATE TABLE `barang_keluar` (
    `id` VARCHAR(36) NOT NULL PRIMARY KEY,
    `barang_id` VARCHAR(36) NOT NULL,
    `pengguna_id` VARCHAR(36) DEFAULT NULL,
    `nama_penerima` VARCHAR(255) DEFAULT NULL,
    `jumlah` INT NOT NULL,
    `tanggal_keluar` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `catatan` TEXT DEFAULT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_keluar_barang` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_keluar_pengguna` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 7. TABEL PEMINJAMAN (borrowings)
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `peminjaman`;
CREATE TABLE `peminjaman` (
    `id` VARCHAR(36) NOT NULL PRIMARY KEY,
    `barang_id` VARCHAR(36) NOT NULL,
    `pengguna_id` VARCHAR(36) NOT NULL,
    `jumlah` INT NOT NULL DEFAULT 1,
    `tanggal_pinjam` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `tanggal_kembali` DATETIME DEFAULT NULL,
    `status` ENUM('dipinjam', 'dikembalikan', 'terlambat') NOT NULL DEFAULT 'dipinjam',
    `catatan` TEXT DEFAULT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_peminjaman_barang` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_peminjaman_pengguna` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
