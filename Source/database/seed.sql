-- ============================================================
-- SEED DATA AWAL GUDANG SEKOLAH / TKJ (MySQL / MariaDB / phpMyAdmin)
-- ============================================================

-- 1. SEED PENGGUNA
INSERT INTO `pengguna` (`id`, `nama_pengguna`, `nama_lengkap`, `email`, `kata_sandi_hash`, `peran`, `nomor_telepon`)
VALUES 
(UUID(), 'admin_tkj', 'Administrator TKJ', 'admin@sekolah.sch.id', '$2y$10$nfScleRxo11Xkqh/lk8T0.0UhObkIjRny78nqbDDxbzRcjutP/kty', 'admin', '081234567890'),
(UUID(), 'petugas_gudang', 'Budi Santoso (Petugas)', 'petugas@sekolah.sch.id', '$2y$10$0zg6tv3xlQRy18GoNRS0puyj2xflVEuNHgXNdo9WV5LgZt3JLBN7m', 'petugas', '081298765432'),
(UUID(), 'siswa_ahmad', 'Ahmad Rizki (Siswa)', 'ahmad@siswa.sch.id', '$2y$10$OiK260amKcdC.rx3KTdBluFqYltybKM6NWATiMfZ/gHJ1aofSs8c.', 'siswa', '085712345678');

-- 2. SEED KATEGORI
INSERT INTO `kategori` (`id`, `kode_kategori`, `nama_kategori`, `deskripsi`)
VALUES
(UUID(), 'KAT-NET', 'Jaringan & Networking', 'Peralatan Jaringan Komputer seperti Router, Switch, Tang Crimping'),
(UUID(), 'KAT-ELEK', 'Elektronika & Komponen', 'Komponen elektronik dasar, Solder, Multitester'),
(UUID(), 'KAT-KOMP', 'Perangkat Komputer', 'Kabel LAN, Monitor, RAM, Harddisk External');

-- 3. SEED BARANG
INSERT INTO `barang` (`id`, `kategori_id`, `kode_barang`, `nama_barang`, `merek`, `barcode`, `stok_total`, `stok_tersedia`, `jumlah`, `deskripsi`)
VALUES
(UUID(), (SELECT `id` FROM `kategori` WHERE `kode_kategori` = 'KAT-NET' LIMIT 1), 'BRG-001', 'Router Mikrotik RB951Ui-2HnD', 'Mikrotik', '899100100001', 10, 8, 10, 'Router Wi-Fi untuk Praktik Lab TKJ'),
(UUID(), (SELECT `id` FROM `kategori` WHERE `kode_kategori` = 'KAT-NET' LIMIT 1), 'BRG-002', 'Tang Crimping RJ45', 'Tekiro', '899100100002', 15, 12, 15, 'Tang Crimping Kabel UTP RJ45 / RJ11'),
(UUID(), (SELECT `id` FROM `kategori` WHERE `kode_kategori` = 'KAT-ELEK' LIMIT 1), 'BRG-003', 'Digital Multimeter / Avometer', 'Sanwa', '899100100003', 8, 8, 8, 'Multitester Digital untuk Pengukuran Arus & Tegangan');

-- 4. SEED TRANSAKSI BARANG MASUK
INSERT INTO `barang_masuk` (`id`, `barang_id`, `pengguna_id`, `jumlah`, `catatan`)
VALUES
(UUID(), (SELECT `id` FROM `barang` WHERE `kode_barang` = 'BRG-001' LIMIT 1), (SELECT `id` FROM `pengguna` WHERE `nama_pengguna` = 'petugas_gudang' LIMIT 1), 10, 'Pengadaan Barang Baru Lab TKJ 2026'),
(UUID(), (SELECT `id` FROM `barang` WHERE `kode_barang` = 'BRG-002' LIMIT 1), (SELECT `id` FROM `pengguna` WHERE `nama_pengguna` = 'petugas_gudang' LIMIT 1), 15, 'Pembelian Peralatan Praktik Siswa');

-- 5. SEED TRANSAKSI PEMINJAMAN
INSERT INTO `peminjaman` (`id`, `barang_id`, `pengguna_id`, `jumlah`, `status`, `catatan`)
VALUES
(UUID(), (SELECT `id` FROM `barang` WHERE `kode_barang` = 'BRG-001' LIMIT 1), (SELECT `id` FROM `pengguna` WHERE `nama_pengguna` = 'siswa_ahmad' LIMIT 1), 1, 'dipinjam', 'Peminjaman untuk Praktik Routing Mikrotik'),
(UUID(), (SELECT `id` FROM `barang` WHERE `kode_barang` = 'BRG-002' LIMIT 1), (SELECT `id` FROM `pengguna` WHERE `nama_pengguna` = 'siswa_ahmad' LIMIT 1), 2, 'dipinjam', 'Peminjaman untuk Praktik Crimping Kabel UTP');

-- 6. SEED LOG AKTIVITAS
INSERT INTO `log_aktivitas` (`id`, `pengguna_id`, `tindakan`, `deskripsi`)
VALUES
(UUID(), (SELECT `id` FROM `pengguna` WHERE `nama_pengguna` = 'admin_tkj' LIMIT 1), 'Inisialisasi Sistem', 'Membuat skema awal database dan kategori barang'),
(UUID(), (SELECT `id` FROM `pengguna` WHERE `nama_pengguna` = 'petugas_gudang' LIMIT 1), 'Input Barang Masuk', 'Memasukkan 10 unit Router Mikrotik ke dalam stok');
