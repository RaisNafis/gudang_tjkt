<?php
require_once __DIR__ . '/../../models/Pengguna.php';
require_once __DIR__ . '/../../models/Jurusan.php';
require_once __DIR__ . '/../../models/Kategori.php';
require_once __DIR__ . '/../../models/Rak.php';
require_once __DIR__ . '/../../models/Barang.php';
require_once __DIR__ . '/../../models/BarangMasuk.php';
require_once __DIR__ . '/../../models/BarangKeluar.php';
require_once __DIR__ . '/../../models/Peminjaman.php';
require_once __DIR__ . '/../../models/LogAktivitas.php';

$user = currentUser();
$title = "Dashboard Overview - Gudang " . ((!empty($user['peran']) && $user['peran'] === 'admin_sekolah') ? "Sekolah" : (!empty($user['nama_jurusan']) ? $user['nama_jurusan'] : "Sekolah"));
require_once __DIR__ . '/../layouts/header.php';

$flash = getFlash();
$currentJurusanId = (!empty($user['peran']) && $user['peran'] === 'admin_sekolah') ? null : ($user['jurusan_id'] ?? null);

$dbJurusan = Jurusan::getAll();
$stats = Barang::getStats($currentJurusanId);
$dbPengguna = Pengguna::getAll($currentJurusanId);
$dbKategori = Kategori::getAll($currentJurusanId);
$dbRak = Rak::getAll($currentJurusanId);
$dbBarang = Barang::getAll($currentJurusanId);
$dbBarangMasuk = BarangMasuk::getAll($currentJurusanId);
$dbBarangKeluar = BarangKeluar::getAll($currentJurusanId);
$dbPeminjaman = Peminjaman::getAll($currentJurusanId);
$dbLogAktivitas = LogAktivitas::getAll($currentJurusanId);

$jurusanColors = [];
$presetsColor = ['kuning'=>'#EAB308','orange'=>'#EA580C','hijau'=>'#2E7D32','merah'=>'#DC2626','biru'=>'#2563EB','ungu'=>'#7C3AED','pink'=>'#E11D48','cyan'=>'#0891B2'];
foreach ($dbJurusan as $jItem) {
    $cVal = $jItem['warna_tema'] ?? '#2E7D32';
    if (isset($presetsColor[strtolower($cVal)])) {
        $jurusanColors[$jItem['id']] = $presetsColor[strtolower($cVal)];
    } else {
        $jurusanColors[$jItem['id']] = (str_starts_with($cVal, '#') ? '' : '#') . $cVal;
    }
}

$isSuperAdmin = (!empty($user['peran']) && $user['peran'] === 'admin_sekolah');
$totalUsersCount = count($dbPengguna);
$totalJurusanCount = count($dbJurusan);
$totalKategoriCount = count($dbKategori);
$totalBarangCount = $stats['total_items'] ?? 0;
$totalStokTersedia = $stats['total_available'] ?? 0;

$cntAdminSekolah = 0;
$cntAdminJurusan = 0;
$cntPetugas = 0;
$cntSiswa = 0;
foreach ($dbPengguna as $pUser) {
    $r = $pUser['peran'] ?? 'siswa';
    if ($r === 'admin_sekolah') $cntAdminSekolah++;
    elseif ($r === 'admin_jurusan') $cntAdminJurusan++;
    elseif ($r === 'petugas') $cntPetugas++;
    else $cntSiswa++;
}

$totalUnitMasuk = array_reduce($dbBarangMasuk, fn($acc, $i) => $acc + intval($i['jumlah'] ?? 1), 0);
$totalUnitKeluar = array_reduce($dbBarangKeluar, fn($acc, $i) => $acc + intval($i['jumlah'] ?? 1), 0);

$pinjamBelumKembali = 0;
$pinjamSudahKembali = 0;
foreach ($dbPeminjaman as $pmItem) {
    if (($pmItem['status'] ?? '') === 'dipinjam') {
        $pinjamBelumKembali++;
    } else {
        $pinjamSudahKembali++;
    }
}
$totalLogsCount = $stats['total_logs'] ?? 0;
?>

<div class="flex h-screen w-screen bg-sage-50/60 font-sans overflow-hidden">
    
    <!-- Sidebar Navigation -->
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col h-full min-w-0 overflow-hidden">
        
        <!-- Top Bar Header: Mobile Hamburger, Page Title, User Profile & Controls -->
        <header class="bg-white dark:bg-slate-900 border-b border-sage-200/80 dark:border-slate-800 px-4 sm:px-6 py-3.5 sm:py-4 flex items-center justify-between shadow-sm flex-shrink-0 z-10 gap-2">
            <!-- UJUNG KIRI: Hamburger Menu Button (Mobile) + Text Title Halaman -->
            <div class="flex items-center gap-3 min-w-0">
                <button type="button" onclick="openMobileSidebar()" class="lg:hidden p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:text-sage-700 hover:bg-sage-100 dark:hover:bg-slate-800 transition-colors border border-sage-200 dark:border-slate-800 shrink-0" title="Buka Menu Sidebar">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <h2 class="text-base sm:text-xl font-bold text-slate-800 dark:text-white truncate" id="pageTitle">Dashboard Overview</h2>
            </div>

            <!-- UJUNG KANAN: Theme Toggle, Sekolah Badge & Profile -->
            <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                <button type="button" onclick="toggleTheme()" class="p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors flex items-center justify-center border border-sage-100 dark:border-slate-800" title="Beralih Mode Gelap / Terang">
                    <svg class="themeSunIcon w-5 h-5 hidden text-slate-700 dark:text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <svg class="themeMoonIcon w-5 h-5 text-slate-600 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>
                <span class="hidden md:inline-block px-3 py-1 text-xs font-semibold bg-sage-100 dark:bg-sage-900/40 text-sage-800 dark:text-sage-300 rounded-full border border-sage-200/80 dark:border-sage-800/60">SMK NEGERI 2 PANGKALPINANG</span>
                <?php 
                $topRole = $user['peran'] ?? 'siswa';
                if ($topRole === 'admin_sekolah') {
                    $topRoleText = 'Admin Sekolah';
                } elseif ($topRole === 'admin_jurusan') {
                    $jName = !empty($user['nama_jurusan']) ? $user['nama_jurusan'] : '';
                    if (preg_match('/\(([^)]+)\)/', $jName, $m)) $jName = $m[1];
                    $topRoleText = 'Admin Jurusan' . ($jName ? ' (' . $jName . ')' : '');
                } elseif ($topRole === 'petugas') {
                    $topRoleText = 'Petugas Gudang';
                } else {
                    $topRoleText = 'Siswa';
                }
                ?>
                <div class="pl-2 sm:pl-3 border-l border-sage-200 dark:border-slate-800 text-right">
                    <span class="block text-xs font-bold text-slate-800 dark:text-white leading-tight truncate max-w-[110px] sm:max-w-none">
                        <?= htmlspecialchars($user['nama_lengkap'] ?? $user['nama_pengguna'] ?? 'User'); ?>
                    </span>
                    <span class="block text-[10px] font-bold text-sage-600 dark:text-sage-400 leading-tight mt-0.5 truncate">
                        <?= htmlspecialchars($topRoleText); ?>
                    </span>
                </div>
            </div>
        </header>

        <!-- Flash Alert Notification -->
        <?php if ($flash): ?>
            <div class="px-6 pt-4 flex-shrink-0 animate-fade-in-up">
                <div class="p-4 rounded-xl text-xs font-bold flex items-center justify-between <?= $flash['type'] === 'success' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-red-100 text-red-800 border border-red-200'; ?>">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span><?= htmlspecialchars($flash['message']); ?></span>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Main Body Scrollable Container with Custom Soft Green Scrollbar -->
        <main class="flex-1 overflow-y-auto p-3 sm:p-6 space-y-4 sm:space-y-6">
            
            <!-- SECTION 1: TAB DASHBOARD -->
            <div id="tab-dashboard" class="tab-content animate-fade-in-up space-y-6">
                <!-- Statistics Cards Row Live from MySQL Database -->
                <?php if ($isSuperAdmin): ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                        <div class="bg-white p-5 rounded-2xl border border-sage-200/80 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Users</span>
                                <div class="w-10 h-10 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                            </div>
                            <h3 class="text-2xl font-extrabold text-slate-800"><?= $totalUsersCount; ?> User</h3>
                        </div>

                        <div class="bg-white p-5 rounded-2xl border border-sage-200/80 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Jurusan</span>
                                <div class="w-10 h-10 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </div>
                            </div>
                            <h3 id="statSuperTotalJurusan" class="text-2xl font-extrabold text-slate-800"><?= $totalJurusanCount; ?> Jurusan</h3>
                        </div>

                        <div class="bg-white p-5 rounded-2xl border border-sage-200/80 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kategori Barang</span>
                                <div class="w-10 h-10 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 11h.01M7 15h.01M11 7h8M11 11h8M11 15h8"/></svg>
                                </div>
                            </div>
                            <h3 id="statSuperTotalKategori" class="text-2xl font-extrabold text-slate-800"><?= $totalKategoriCount; ?> Kategori</h3>
                        </div>

                        <div class="bg-white p-5 rounded-2xl border border-sage-200/80 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Barang</span>
                                <div class="w-10 h-10 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                </div>
                            </div>
                            <h3 id="statSuperTotalBarang" class="text-2xl font-extrabold text-slate-800"><?= $totalBarangCount; ?> Item</h3>
                        </div>

                        <div class="bg-white p-5 rounded-2xl border border-sage-200/80 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Stok Tersedia</span>
                                <div class="w-10 h-10 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                            </div>
                            <h3 id="statSuperStokTersedia" class="text-2xl font-extrabold text-slate-800"><?= $totalStokTersedia; ?> Unit</h3>
                        </div>

                        <div class="bg-white p-5 rounded-2xl border border-sage-200/80 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Belum Dikembalikan</span>
                                <div class="w-10 h-10 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                            </div>
                            <h3 id="statSuperBelumKembali" class="text-2xl font-extrabold text-slate-800"><?= $pinjamBelumKembali; ?> Transaksi</h3>
                        </div>

                        <div class="bg-white p-5 rounded-2xl border border-sage-200/80 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Sudah Dikembalikan</span>
                                <div class="w-10 h-10 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                            </div>
                            <h3 id="statSuperSudahKembali" class="text-2xl font-extrabold text-slate-800"><?= $pinjamSudahKembali; ?> Transaksi</h3>
                        </div>

                        <div class="bg-white p-5 rounded-2xl border border-sage-200/80 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Log Aktivitas</span>
                                <div class="w-10 h-10 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                            </div>
                            <h3 id="statSuperTotalLogs" class="text-2xl font-extrabold text-slate-800"><?= $totalLogsCount; ?> Catatan</h3>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                        <div class="bg-white p-5 rounded-2xl border border-sage-200/80 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Barang</span>
                                <div class="w-10 h-10 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                </div>
                            </div>
                            <h3 id="statTotalBarang" class="text-2xl font-extrabold text-slate-800"><?= $totalBarangCount; ?> Item</h3>
                        </div>

                        <div class="bg-white p-5 rounded-2xl border border-sage-200/80 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Stok Tersedia</span>
                                <div class="w-10 h-10 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                            </div>
                            <h3 id="statStokTersedia" class="text-2xl font-extrabold text-slate-800"><?= $totalStokTersedia; ?> Unit</h3>
                        </div>

                        <div class="bg-white p-5 rounded-2xl border border-sage-200/80 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kategori Barang</span>
                                <div class="w-10 h-10 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 11h.01M7 15h.01M11 7h8M11 11h8M11 15h8"/></svg>
                                </div>
                            </div>
                            <h3 id="statTotalKategori" class="text-2xl font-extrabold text-slate-800"><?= $totalKategoriCount; ?> Kategori</h3>
                        </div>

                        <div class="bg-white p-5 rounded-2xl border border-sage-200/80 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Belum Dikembalikan</span>
                                <div class="w-10 h-10 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                            </div>
                            <h3 id="statBelumKembali" class="text-2xl font-extrabold text-slate-800"><?= $pinjamBelumKembali; ?> Transaksi</h3>
                        </div>

                        <div class="bg-white p-5 rounded-2xl border border-sage-200/80 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Sudah Dikembalikan</span>
                                <div class="w-10 h-10 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                            </div>
                            <h3 id="statSudahKembali" class="text-2xl font-extrabold text-slate-800"><?= $pinjamSudahKembali; ?> Transaksi</h3>
                        </div>

                        <div class="bg-white p-5 rounded-2xl border border-sage-200/80 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Log Aktivitas</span>
                                <div class="w-10 h-10 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                            </div>
                            <h3 id="statTotalLogs" class="text-2xl font-extrabold text-slate-800"><?= $totalLogsCount; ?> Catatan</h3>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Chart Row Grid: Left Column Chart (8 Cols) & Right Pie Chart (4 Cols) -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    
                    <!-- Left: Column Chart (8 Cols) -->
                    <div class="lg:col-span-8 bg-white p-6 rounded-2xl border border-sage-200/80 shadow-sm">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                            <div>
                                <h3 class="text-base font-bold text-slate-800">
                                    <?= $isSuperAdmin ? 'Grafik Sirkulasi & Stok Inventaris per Jurusan' : 'Grafik Sirkulasi Inventaris & Peminjaman'; ?>
                                </h3>
                                <p class="text-xs text-slate-500">
                                    <?= $isSuperAdmin ? 'Perbandingan Barang Masuk, Barang Keluar, dan Peminjaman Alat per Jurusan' : 'Perbandingan Barang Masuk, Barang Keluar, dan Peminjaman Alat per Bulan'; ?>
                                </p>
                            </div>
                            <div class="flex items-center gap-4 text-xs font-semibold text-slate-600">
                                <span class="inline-flex items-center gap-1.5">
                                    <span class="w-3 h-3 rounded-sm bg-sage-600 inline-block"></span> Masuk
                                </span>
                                <span class="inline-flex items-center gap-1.5">
                                    <span class="w-3 h-3 rounded-sm bg-amber-500 inline-block"></span> Keluar
                                </span>
                                <span class="inline-flex items-center gap-1.5">
                                    <span class="w-3 h-3 rounded-sm bg-sky-500 inline-block"></span> Peminjaman
                                </span>
                            </div>
                        </div>

                        <div class="relative h-72 w-full">
                            <canvas id="inventoryColumnChart"></canvas>
                        </div>
                    </div>

                    <!-- Right: Pie Chart (4 Cols) -->
                    <div class="lg:col-span-4 bg-white p-6 rounded-2xl border border-sage-200/80 shadow-sm flex flex-col justify-between">
                        <div>
                            <h3 class="text-base font-bold text-slate-800">
                                <?= $isSuperAdmin ? 'Sebaran Total Stok per Jurusan' : 'Komposisi Kategori Barang'; ?>
                            </h3>
                            <p class="text-xs text-slate-500 mb-4">
                                <?= $isSuperAdmin ? 'Persentase total stok barang inventaris di setiap jurusan' : 'Persentase jumlah stok barang per kategori'; ?>
                            </p>
                        </div>

                        <div class="relative h-64 w-full flex items-center justify-center">
                            <canvas id="categoryPieChart"></canvas>
                        </div>
                    </div>

                </div>

                <!-- Dashboard Summary Row (Peminjaman Terbaru Live MySQL) -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <div class="lg:col-span-8 bg-white rounded-2xl border border-sage-200/80 shadow-sm p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3.5 mb-5">
                            <div>
                                <h3 class="text-base font-bold text-slate-800">Sirkulasi Peminjaman Alat Terbaru</h3>
                                <p class="text-xs text-slate-500">Daftar transaksi peminjaman barang oleh siswa (MySQL Live)</p>
                            </div>
                            <button onclick="switchTab('peminjaman')" class="text-xs font-bold text-sage-600 hover:text-sage-700">Lihat Semua &rarr;</button>
                        </div>
                        <div class="overflow-x-auto max-w-full w-full block align-middle rounded-xl border border-sage-100">
                            <table class="w-full text-left text-xs text-slate-600">
                                <thead class="bg-sage-50 text-slate-700 font-bold border-b border-sage-200">
                                    <tr>
                                        <th class="py-3 px-4">Nama Barang</th>
                                        <th class="py-3 px-4">Peminjam (Siswa)</th>
                                        <th class="py-3 px-4">Jumlah</th>
                                        <th class="py-3 px-4">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <?php if (!empty($dbPeminjaman)): ?>
                                        <?php foreach (array_slice($dbPeminjaman, 0, 5) as $pm): ?>
                                            <tr class="hover:bg-sage-50/50">
                                                <td class="py-3.5 px-4 font-bold text-slate-800"><?= htmlspecialchars($pm['nama_barang']); ?></td>
                                                <td class="py-3.5 px-4"><?= htmlspecialchars($pm['nama_peminjam']); ?></td>
                                                <td class="py-3.5 px-4 font-semibold"><?= htmlspecialchars($pm['jumlah']); ?> <?= htmlspecialchars($pm['satuan'] ?? 'Unit'); ?></td>
                                                <td class="py-3.5 px-4">
                                                    <span class="<?= $pm['status'] === 'dipinjam' ? 'text-amber-500 font-bold' : 'text-emerald-500 font-bold'; ?>">
                                                        <?= htmlspecialchars(ucfirst($pm['status'])); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="4" class="py-4 text-center text-slate-400">Belum ada data peminjaman</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="lg:col-span-4 bg-white rounded-2xl border border-sage-200/80 shadow-sm p-6 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-base font-bold text-slate-800">Aktivitas Barang Masuk & Keluar</h3>
                                    <p class="text-xs text-slate-500">Catatan transaksi barang terbaru</p>
                                </div>
                            </div>
                            <?php 
                            $recentLogMasukKeluar = [];
                            foreach ($dbBarangMasuk as $m) {
                                $recentLogMasukKeluar[] = [
                                    'type' => 'masuk',
                                    'nama_barang' => $m['nama_barang'] ?? 'Barang',
                                    'pihak' => $m['nama_pemasok'] ?? 'Pemasok',
                                    'jumlah' => $m['jumlah'] ?? 1,
                                    'satuan' => $m['satuan'] ?? 'Unit',
                                    'tanggal' => $m['tanggal_masuk'] ?? $m['created_at'] ?? date('Y-m-d H:i')
                                ];
                            }
                            foreach ($dbBarangKeluar as $k) {
                                $recentLogMasukKeluar[] = [
                                    'type' => 'keluar',
                                    'nama_barang' => $k['nama_barang'] ?? 'Barang',
                                    'pihak' => $k['nama_penerima'] ?? 'Penerima',
                                    'jumlah' => $k['jumlah'] ?? 1,
                                    'satuan' => $k['satuan'] ?? 'Unit',
                                    'tanggal' => $k['tanggal_keluar'] ?? $k['created_at'] ?? date('Y-m-d H:i')
                                ];
                            }
                            usort($recentLogMasukKeluar, function($a, $b) {
                                return strtotime($b['tanggal']) - strtotime($a['tanggal']);
                            });
                            $recentLogMasukKeluar = array_slice($recentLogMasukKeluar, 0, 4);
                            ?>
                            <div class="space-y-3">
                                <?php if (!empty($recentLogMasukKeluar)): ?>
                                    <?php foreach ($recentLogMasukKeluar as $log): ?>
                                        <div class="p-3 rounded-xl <?= $log['type'] === 'masuk' ? 'bg-emerald-50/50 border border-emerald-200/60' : 'bg-orange-50/50 border border-orange-200/60'; ?> flex items-center justify-between transition-all">
                                            <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                                                <div class="w-8 h-8 rounded-lg <?= $log['type'] === 'masuk' ? 'bg-emerald-600 text-white' : 'bg-orange-600 text-white'; ?> flex items-center justify-center text-xs font-bold shrink-0">
                                                    <?php if ($log['type'] === 'masuk'): ?>
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                                                    <?php else: ?>
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                                                    <?php endif; ?>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-bold text-slate-800 line-clamp-1"><?= htmlspecialchars($log['nama_barang']); ?></p>
                                                    <p class="text-[10px] text-slate-500 font-semibold"><?= $log['type'] === 'masuk' ? 'Dari: ' : 'Untuk: '; ?><?= htmlspecialchars($log['pihak']); ?></p>
                                                </div>
                                            </div>
                                            <div class="text-right shrink-0">
                                                <span class="text-xs font-extrabold <?= $log['type'] === 'masuk' ? 'text-emerald-700' : 'text-orange-700'; ?>">
                                                    <?= $log['type'] === 'masuk' ? '+' : '-'; ?><?= htmlspecialchars($log['jumlah']); ?> <?= htmlspecialchars($log['satuan']); ?>
                                                </span>
                                                <p class="text-[9px] text-slate-400 font-medium mt-0.5"><?= date('d/m/H:i', strtotime($log['tanggal'])); ?></p>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="p-6 text-center text-slate-400 text-xs italic">
                                        Belum ada aktivitas barang masuk / keluar
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: TAB DATA PENGGUNA (MySQL Live) -->
            <div id="tab-pengguna" class="tab-content hidden space-y-6">
                <!-- Stat Cards Row for Data Pengguna -->
                <div class="grid grid-cols-1 sm:grid-cols-2 <?= $isSuperAdmin ? 'lg:grid-cols-5' : 'lg:grid-cols-4'; ?> gap-5">
                    <div class="bg-white p-5 rounded-2xl border border-sage-200/80 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total User</span>
                            <div class="w-9 h-9 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            </div>
                        </div>
                        <h3 id="statTabPenggunaTotal" class="text-2xl font-extrabold text-slate-800"><?= $totalUsersCount; ?> User</h3>
                    </div>
                    <?php if ($isSuperAdmin): ?>
                    <div class="bg-white p-5 rounded-2xl border border-sage-200/80 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Admin Sekolah</span>
                            <div class="w-9 h-9 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                        </div>
                        <h3 class="text-2xl font-extrabold text-slate-800"><?= $cntAdminSekolah; ?> Admin</h3>
                    </div>
                    <?php endif; ?>
                    <div class="bg-white p-5 rounded-2xl border border-sage-200/80 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Admin Jurusan</span>
                            <div class="w-9 h-9 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                        </div>
                        <h3 class="text-2xl font-extrabold text-slate-800"><?= $cntAdminJurusan; ?> Admin</h3>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-sage-200/80 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Petugas Gudang</span>
                            <div class="w-9 h-9 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                        </div>
                        <h3 class="text-2xl font-extrabold text-slate-800"><?= $cntPetugas; ?> Petugas</h3>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-sage-200/80 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Siswa</span>
                            <div class="w-9 h-9 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                            </div>
                        </div>
                        <h3 class="text-2xl font-extrabold text-slate-800"><?= $cntSiswa; ?> Siswa</h3>
                    </div>
                </div>

                <!-- Charts Row for Data Pengguna -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <div class="lg:col-span-7 bg-white p-6 rounded-2xl border border-sage-200/80 shadow-sm">
                        <h3 class="text-base font-bold text-slate-800 mb-1">Distribusi Pengguna per Peran (Role)</h3>
                        <p class="text-xs text-slate-500 mb-4">Jumlah Admin Sekolah, Admin Jurusan, Petugas, dan Siswa</p>
                        <div class="relative h-64 w-full"><canvas id="userRoleChart"></canvas></div>
                    </div>
                    <div class="lg:col-span-5 bg-white p-6 rounded-2xl border border-sage-200/80 shadow-sm flex flex-col justify-between">
                        <div>
                            <h3 class="text-base font-bold text-slate-800 mb-1"><?= $isSuperAdmin ? 'Sebaran Pengguna per Jurusan' : 'Persentase Peran Pengguna'; ?></h3>
                            <p class="text-xs text-slate-500 mb-4"><?= $isSuperAdmin ? 'Persentase anggota di setiap jurusan sekolah' : 'Proporsi Admin Jurusan, Petugas Gudang, dan Siswa'; ?></p>
                        </div>
                        <div class="relative h-56 w-full flex items-center justify-center"><canvas id="userJurusanChart"></canvas></div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-sage-200/80 shadow-sm p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3.5 mb-5">
                        <div>
                            <h3 class="text-base font-bold text-slate-800">Data Pengguna Sistem</h3>
                            <p class="text-xs text-slate-500">Kelola data Admin, Petugas, dan Siswa</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                            <button onclick="exportTableToCSV('tablePengguna', 'data_pengguna.csv')" class="px-3.5 py-2 bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200 rounded-xl font-bold text-xs flex items-center gap-1.5 transition-colors" title="Download data sebagai CSV">
                                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Export CSV</span>
                            </button>
                            <button onclick="openModal('modalPengguna', 'Tambah Data Pengguna Baru')" class="px-4 py-2 bg-sage-600 text-white rounded-xl font-bold text-xs shadow-md shadow-sage-600/20 hover:bg-sage-700 transition-colors">+ Tambah Pengguna</button>
                        </div>
                    </div>
                    <div class="overflow-x-auto max-w-full w-full block align-middle rounded-xl border border-sage-100">
                        <table id="tablePengguna" class="w-full text-left text-xs text-slate-600">
                            <thead class="bg-sage-50 text-slate-700 font-bold border-b border-sage-200">
                                <tr>
                                    <th class="py-3 px-3 w-10 text-center"><input type="checkbox" class="select-all-checkbox rounded accent-sage-600 cursor-pointer" onchange="toggleSelectAll(this)"></th>
                                    <th class="py-3 px-4 w-12 text-center">No</th>
                                    <th class="py-3 px-4">Nama Pengguna</th>
                                    <th class="py-3 px-4">Nama Lengkap</th>
                                    <th class="py-3 px-4">Email</th>
                                    <th class="py-3 px-4">Jurusan</th>
                                    <th class="py-3 px-4">Peran</th>
                                    <th class="py-3 px-4">Nomor Telepon</th>
                                    <th class="py-3 px-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php $no = 1; foreach ($dbPengguna as $p): ?>
                                    <tr class="hover:bg-sage-50/50">
                                        <td class="py-3.5 px-3 text-center"><input type="checkbox" class="row-checkbox rounded accent-sage-600 cursor-pointer" value="<?= htmlspecialchars($p['id']); ?>" onchange="updateBatchDeleteBar()"></td>
                                        <td class="py-3.5 px-4 text-center font-bold text-slate-500 row-number-cell"><?= $no++; ?></td>
                                         <td class="py-3.5 px-4 font-bold text-slate-800 flex items-center gap-2.5">
                                             <?php if (!empty($p['foto_url']) && file_exists(__DIR__ . '/../../../' . $p['foto_url'])): ?>
                                                 <img src="<?= htmlspecialchars($p['foto_url']);  ?>" class="w-7 h-7 rounded-full object-cover border border-sage-200 shrink-0 shadow-sm" alt="Avatar">
                                             <?php else: ?>
                                                 <div class="w-7 h-7 rounded-full bg-sage-600 text-white font-bold flex items-center justify-center text-[10px] shrink-0 shadow-sm">
                                                     <?= strtoupper(substr($p['nama_lengkap'] ?? 'U', 0, 1)); ?>
                                                 </div>
                                             <?php endif; ?>
                                             <span><?= htmlspecialchars($p['nama_pengguna']); ?></span>
                                         </td>
                                        <td class="py-3.5 px-4"><?= htmlspecialchars($p['nama_lengkap']); ?></td>
                                        <td class="py-3.5 px-4"><?= htmlspecialchars($p['email'] ?? '-'); ?></td>
                                        <td class="py-3.5 px-4 font-bold text-sage-700">
                                            <?= ($p['peran'] === 'admin_sekolah') ? '<span class="text-slate-400 font-normal">-</span>' : htmlspecialchars($p['nama_jurusan'] ?? '-'); ?>
                                        </td>
                                        <td class="py-3.5 px-4 font-semibold">
                                            <?php
                                                $r = $p['peran'] ?? 'siswa';
                                                if ($r === 'admin_sekolah') echo 'Admin Sekolah';
                                                elseif ($r === 'admin_jurusan') echo 'Admin Jurusan';
                                                elseif ($r === 'petugas') echo 'Petugas Gudang';
                                                else echo 'Siswa';
                                            ?>
                                        </td>
                                        <td class="py-3.5 px-4"><?= htmlspecialchars($p['nomor_telepon'] ?? '-'); ?></td>
                                        <td class="py-3.5 px-4">
                                            <div class="flex items-center gap-1.5">
                                                <?php if (!empty($user['peran']) && $user['peran'] === 'admin_sekolah' && $p['id'] !== $user['id']): ?>
                                                <button type="button" onclick="loginAsUser('<?= htmlspecialchars($p['id']); ?>', '<?= htmlspecialchars(addslashes($p['nama_pengguna'])); ?>')" class="px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] shadow-sm transition-all flex items-center gap-1" title="Login Sebagai Akun Ini">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                                                    <span>Login Akun</span>
                                                </button>
                                                <?php endif; ?>
                                                <button type="button" onclick="editPengguna('<?= htmlspecialchars($p['id']); ?>')" class="p-1.5 rounded-lg bg-sage-600 hover:bg-sage-700 text-white shadow-sm transition-all" title="Edit Data Pengguna">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 01-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                </button>
                                                <button type="button" onclick="deletePengguna('<?= htmlspecialchars($p['id']); ?>', '<?= htmlspecialchars(addslashes($p['nama_pengguna'])); ?>')" class="p-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white shadow-sm transition-all" title="Hapus Data Pengguna">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- SECTION 2.5: TAB DATA JURUSAN (MySQL Live) -->
            <div id="tab-jurusan" class="tab-content hidden space-y-6">
                <!-- Stat Card Row for Data Jurusan -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div class="bg-white p-5 rounded-2xl border border-sage-200/80 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Jurusan</span>
                            <div class="w-10 h-10 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                        </div>
                        <h3 class="text-2xl font-extrabold text-slate-800"><?= $totalJurusanCount; ?> Jurusan</h3>
                    </div>
                </div>
                <div class="bg-white rounded-2xl border border-sage-200/80 shadow-sm p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3.5 mb-5">
                        <div>
                            <h3 class="text-base font-bold text-slate-800">Master Data Jurusan / Departemen</h3>
                            <p class="text-xs text-slate-500">Kelola daftar jurusan keahlian sekolah</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                            <button onclick="exportTableToCSV('tableJurusan', 'data_jurusan.csv')" class="px-3.5 py-2 bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200 rounded-xl font-bold text-xs flex items-center gap-1.5 transition-colors" title="Download data sebagai CSV">
                                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Export CSV</span>
                            </button>
                            <button onclick="openModal('modalJurusan', 'Tambah Jurusan Baru')" class="px-4 py-2 bg-sage-600 text-white rounded-xl font-bold text-xs shadow-md shadow-sage-600/20 hover:bg-sage-700 transition-colors">+ Tambah Jurusan</button>
                        </div>
                    </div>
                    <div class="overflow-x-auto max-w-full w-full block align-middle rounded-xl border border-sage-100">
                        <table id="tableJurusan" class="w-full text-left text-xs text-slate-600">
                            <thead class="bg-sage-50 text-slate-700 font-bold border-b border-sage-200">
                                <tr>
                                    <th class="py-3 px-3 w-10 text-center"><input type="checkbox" class="select-all-checkbox rounded accent-sage-600 cursor-pointer" onchange="toggleSelectAll(this)"></th>
                                    <th class="py-3 px-4 w-12 text-center">No</th>
                                    <th class="py-3 px-4">Nama Jurusan</th>
                                    <th class="py-3 px-4">Warna Tema</th>
                                    <th class="py-3 px-4">Deskripsi</th>
                                    <th class="py-3 px-4">Tgl Dibuat</th>
                                    <th class="py-3 px-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php $no = 1; foreach ($dbJurusan as $j): ?>
                                    <?php 
                                        $colorVal = $j['warna_tema'] ?? '#EAB308';
                                        $presets = ['kuning'=>'#EAB308','orange'=>'#EA580C','hijau'=>'#2E7D32','merah'=>'#DC2626','biru'=>'#2563EB','ungu'=>'#7C3AED','pink'=>'#E11D48','cyan'=>'#0891B2'];
                                        if (isset($presets[strtolower($colorVal)])) {
                                            $hexDisplay = $presets[strtolower($colorVal)];
                                        } else {
                                            $hexDisplay = (str_starts_with($colorVal, '#') ? '' : '#') . strtoupper($colorVal);
                                        }
                                    ?>
                                    <tr class="hover:bg-sage-50/50">
                                        <td class="py-3.5 px-3 text-center"><input type="checkbox" class="row-checkbox rounded accent-sage-600 cursor-pointer" value="<?= htmlspecialchars($j['id']); ?>" onchange="updateBatchDeleteBar()"></td>
                                        <td class="py-3.5 px-4 text-center font-bold text-slate-500 row-number-cell"><?= $no++; ?></td>
                                        <td class="py-3.5 px-4 font-bold text-sage-700"><?= htmlspecialchars($j['nama_jurusan']); ?></td>
                                        <td class="py-3.5 px-4 font-bold">
                                            <span class="inline-flex items-center gap-2 px-2.5 py-1 rounded-lg text-xs font-mono font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                                <span class="w-3.5 h-3.5 rounded-md shrink-0 shadow-sm border border-black/10" style="background-color: <?= htmlspecialchars($hexDisplay); ?>;"></span>
                                                <span><?= htmlspecialchars($hexDisplay); ?></span>
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4"><?= htmlspecialchars($j['deskripsi'] ?? '-'); ?></td>
                                        <td class="py-3.5 px-4"><?= date('d M Y', strtotime($j['created_at'])); ?></td>
                                        <td class="py-3.5 px-4">
                                            <div class="flex items-center gap-1.5">
                                                <button type="button" onclick="editJurusan('<?= htmlspecialchars($j['id']); ?>')" class="p-1.5 rounded-lg bg-sage-600 hover:bg-sage-700 text-white shadow-sm transition-all" title="Edit Data Jurusan">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 01-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                </button>
                                                <button type="button" onclick="deleteJurusan('<?= htmlspecialchars($j['id']); ?>', '<?= htmlspecialchars(addslashes($j['nama_jurusan'])); ?>')" class="p-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white shadow-sm transition-all" title="Hapus Data Jurusan">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: TAB KATEGORI BARANG (MySQL Live) -->
            <div id="tab-kategori" class="tab-content hidden space-y-6">
                <!-- Stat Card Row for Kategori -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div class="bg-white p-5 rounded-2xl border border-sage-200/80 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Kategori</span>
                            <div class="w-10 h-10 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 11h.01M7 15h.01M11 7h8M11 11h8M11 15h8"/></svg>
                            </div>
                        </div>
                        <h3 class="text-2xl font-extrabold text-slate-800"><?= $totalKategoriCount; ?> Kategori</h3>
                    </div>
                </div>

                <?php if ($isSuperAdmin): ?>
                <!-- Charts Row for Kategori -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <div class="lg:col-span-7 bg-white p-6 rounded-2xl border border-sage-200/80 shadow-sm">
                        <h3 class="text-base font-bold text-slate-800 mb-1">Jumlah Kategori Barang per Jurusan</h3>
                        <p class="text-xs text-slate-500 mb-4">Perbandingan kategori alat praktik di masing-masing jurusan</p>
                        <div class="relative h-64 w-full"><canvas id="kategoriColumnChart"></canvas></div>
                    </div>
                    <div class="lg:col-span-5 bg-white p-6 rounded-2xl border border-sage-200/80 shadow-sm flex flex-col justify-between">
                        <div>
                            <h3 class="text-base font-bold text-slate-800 mb-1">Persentase Kategori per Jurusan</h3>
                            <p class="text-xs text-slate-500 mb-4">Proporsi sebaran kategori barang di seluruh jurusan</p>
                        </div>
                        <div class="relative h-56 w-full flex items-center justify-center"><canvas id="kategoriPieChart"></canvas></div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="bg-white rounded-2xl border border-sage-200/80 shadow-sm p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3.5 mb-5">
                        <div>
                            <h3 class="text-base font-bold text-slate-800">Kategori Barang Inventaris</h3>
                            <p class="text-xs text-slate-500">Kelola kelompok kategori alat praktik</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                            <button onclick="exportTableToCSV('tableKategori', 'kategori_barang.csv')" class="px-3.5 py-2 bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200 rounded-xl font-bold text-xs flex items-center gap-1.5 transition-colors" title="Download data sebagai CSV">
                                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Export CSV</span>
                            </button>
                            <button onclick="openModal('modalKategori', 'Tambah Kategori Barang Baru')" class="px-4 py-2 bg-sage-600 text-white rounded-xl font-bold text-xs shadow-md shadow-sage-600/20 hover:bg-sage-700 transition-colors">+ Tambah Kategori</button>
                        </div>
                    </div>
                    <div class="overflow-x-auto max-w-full w-full block align-middle rounded-xl border border-sage-100">
                        <table id="tableKategori" class="w-full text-left text-xs text-slate-600">
                            <thead class="bg-sage-50 text-slate-700 font-bold border-b border-sage-200">
                                <tr>
                                    <th class="py-3 px-3 w-10 text-center"><input type="checkbox" class="select-all-checkbox rounded accent-sage-600 cursor-pointer" onchange="toggleSelectAll(this)"></th>
                                    <th class="py-3 px-4 w-12 text-center">No</th>
                                    <th class="py-3 px-4">Nama Kategori</th>
                                    <?php if (!empty($user['peran']) && $user['peran'] === 'admin_sekolah'): ?><th class="py-3 px-4">Jurusan</th><?php endif; ?>
                                    <th class="py-3 px-4">Deskripsi</th>
                                    <th class="py-3 px-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php $no = 1; foreach ($dbKategori as $kat): ?>
                                    <tr class="hover:bg-sage-50/50">
                                        <td class="py-3.5 px-3 text-center"><input type="checkbox" class="row-checkbox rounded accent-sage-600 cursor-pointer" value="<?= htmlspecialchars($kat['id']); ?>" onchange="updateBatchDeleteBar()"></td>
                                        <td class="py-3.5 px-4 text-center font-bold text-slate-500 row-number-cell"><?= $no++; ?></td>
                                        <td class="py-3.5 px-4 font-bold text-slate-800"><?= htmlspecialchars($kat['nama_kategori']); ?></td>
                                        <?php if (!empty($user['peran']) && $user['peran'] === 'admin_sekolah'): ?><td class="py-3.5 px-4 font-bold text-sage-700"><?= htmlspecialchars($kat['nama_jurusan'] ?? 'Semua Jurusan'); ?></td><?php endif; ?>
                                        <td class="py-3.5 px-4"><?= htmlspecialchars($kat['deskripsi'] ?? '-'); ?></td>
                                        <td class="py-3.5 px-4">
                                            <div class="flex items-center gap-1.5">
                                                <button type="button" onclick="editKategori('<?= htmlspecialchars($kat['id']); ?>')" class="p-1.5 rounded-lg bg-sage-600 hover:bg-sage-700 text-white shadow-sm transition-all" title="Edit Kategori">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                </button>
                                                <button type="button" onclick="deleteKategori('<?= htmlspecialchars($kat['id']); ?>', '<?= htmlspecialchars(addslashes($kat['nama_kategori'])); ?>')" class="p-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white shadow-sm transition-all" title="Hapus Kategori">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- SECTION 3.5: TAB DATA RAK PENYIMPANAN (MySQL Live) -->
            <div id="tab-rak" class="tab-content hidden space-y-6">
                <!-- Stat Card Row for Rak -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div class="bg-white p-5 rounded-2xl border border-sage-200/80 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Rak Penyimpanan</span>
                            <div class="w-10 h-10 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                            </div>
                        </div>
                        <h3 id="statTabRakTotal" class="text-2xl font-extrabold text-slate-800"><?= count($dbRak); ?> Rak</h3>
                    </div>
                </div>
                <div class="bg-white rounded-2xl border border-sage-200/80 shadow-sm p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3.5 mb-5">
                        <div>
                            <h3 class="text-base font-bold text-slate-800">Master Data Rak Penyimpanan</h3>
                            <p class="text-xs text-slate-500">Kelola tata letak fisik rak dan barang di dalamnya</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                            <button onclick="exportTableToCSV('tableRak', 'data_rak_penyimpanan.csv')" class="px-3.5 py-2 bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200 rounded-xl font-bold text-xs flex items-center gap-1.5 transition-colors" title="Download data sebagai CSV">
                                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Export CSV</span>
                            </button>
                            <button onclick="openModal('modalRak', 'Tambah Rak Penyimpanan Baru')" class="px-4 py-2 bg-sage-600 text-white rounded-xl font-bold text-xs shadow-md shadow-sage-600/20 hover:bg-sage-700 transition-colors">+ Tambah Rak</button>
                        </div>
                    </div>
                    <div class="overflow-x-auto max-w-full w-full block align-middle rounded-xl border border-sage-100">
                        <table id="tableRak" class="w-full text-left text-xs text-slate-600">
                            <thead class="bg-sage-50 text-slate-700 font-bold border-b border-sage-200">
                                <tr>
                                    <th class="py-3 px-3 w-10 text-center"><input type="checkbox" class="select-all-checkbox rounded accent-sage-600 cursor-pointer" onchange="toggleSelectAll(this)"></th>
                                    <th class="py-3 px-4 w-12 text-center">No</th>
                                    <th class="py-3 px-4">Nama Rak</th>
                                    <th class="py-3 px-4">Barcode Rak</th>
                                    <th class="py-3 px-4">Kategori Rak</th>
                                    <?php if (!empty($user['peran']) && $user['peran'] === 'admin_sekolah'): ?><th class="py-3 px-4">Jurusan</th><?php endif; ?>
                                    <th class="py-3 px-4">Keterangan / Lokasi</th>
                                    <th class="py-3 px-4 text-center">Jumlah Jenis Barang</th>
                                    <th class="py-3 px-4 text-center">Total Stok Tersedia</th>
                                    <th class="py-3 px-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php $no = 1; foreach ($dbRak as $rk): ?>
                                    <tr class="hover:bg-sage-50/50">
                                        <td class="py-3.5 px-3 text-center"><input type="checkbox" class="row-checkbox rounded accent-sage-600 cursor-pointer" value="<?= htmlspecialchars($rk['id']); ?>" onchange="updateBatchDeleteBar()"></td>
                                        <td class="py-3.5 px-4 text-center font-bold text-slate-500 row-number-cell"><?= $no++; ?></td>
                                        <td class="py-3.5 px-4 font-bold text-slate-800"><?= htmlspecialchars($rk['nama_rak']); ?></td>
                                        <td class="py-3.5 px-4 font-mono text-sage-700">
                                            <button type="button" onclick="showRakBarcodeModal('<?= htmlspecialchars($rk['barcode'] ?? ''); ?>', '<?= htmlspecialchars(addslashes($rk['nama_rak'])); ?>', '<?= htmlspecialchars($rk['id']); ?>')" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-sage-50 hover:bg-sage-100 text-sage-800 border border-sage-200 font-bold transition-all group" title="Klik untuk preview / scan QR Code Rak">
                                                <svg class="w-3.5 h-3.5 text-sage-600 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                                <span><?= htmlspecialchars($rk['barcode'] ?? '-'); ?></span>
                                            </button>
                                        </td>
                                        <td class="py-3.5 px-4 font-semibold text-slate-700"><?= htmlspecialchars($rk['kategori_rak'] ?? '-'); ?></td>
                                        <?php if (!empty($user['peran']) && $user['peran'] === 'admin_sekolah'): ?><td class="py-3.5 px-4 font-bold text-sage-700"><?= htmlspecialchars($rk['nama_jurusan'] ?? 'Semua Jurusan'); ?></td><?php endif; ?>
                                        <td class="py-3.5 px-4"><?= htmlspecialchars($rk['keterangan'] ?? '-'); ?></td>
                                        <td class="py-3.5 px-4 text-center font-bold text-slate-800"><?= intval($rk['total_barang'] ?? 0); ?> Jenis</td>
                                        <td class="py-3.5 px-4 text-center font-extrabold text-sage-600"><?= intval($rk['total_stok_tersedia'] ?? 0); ?> Unit</td>
                                        <td class="py-3.5 px-4">
                                            <div class="flex items-center gap-1.5">
                                                <button type="button" onclick="showBarangInRakModal('<?= htmlspecialchars($rk['id']); ?>', '<?= htmlspecialchars(addslashes($rk['nama_rak'])); ?>')" class="px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] shadow-sm transition-all flex items-center gap-1" title="Lihat Daftar Barang di Rak">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                    <span>Lihat Barang</span>
                                                </button>
                                                <button type="button" onclick="editRak('<?= htmlspecialchars($rk['id']); ?>')" class="p-1.5 rounded-lg bg-sage-600 hover:bg-sage-700 text-white shadow-sm transition-all" title="Edit Data Rak">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 01-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                </button>
                                                <button type="button" onclick="deleteRak('<?= htmlspecialchars($rk['id']); ?>', '<?= htmlspecialchars(addslashes($rk['nama_rak'])); ?>')" class="p-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white shadow-sm transition-all" title="Hapus Data Rak">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- SECTION 4: TAB MASTER BARANG & BARCODE (MySQL Live) -->
            <div id="tab-barang" class="tab-content hidden space-y-6">
                <!-- Stat Cards Row for Barang -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div class="bg-white p-5 rounded-2xl border border-sage-200/80 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Barang</span>
                            <div class="w-10 h-10 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </div>
                        </div>
                        <h3 id="statTabBarangTotalItem" class="text-2xl font-extrabold text-slate-800"><?= $totalBarangCount; ?> Item</h3>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-sage-200/80 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Stok Tersedia</span>
                            <div class="w-10 h-10 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                        </div>
                        <h3 id="statTabBarangTotalStok" class="text-2xl font-extrabold text-slate-800"><?= $totalStokTersedia; ?> Unit</h3>
                    </div>
                </div>

                <?php if ($isSuperAdmin): ?>
                <!-- Charts Row for Barang -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <div class="lg:col-span-7 bg-white p-6 rounded-2xl border border-sage-200/80 shadow-sm">
                        <h3 class="text-base font-bold text-slate-800 mb-1">Total Stok Barang per Jurusan</h3>
                        <p class="text-xs text-slate-500 mb-4">Total stok seluruh alat inventaris di masing-masing jurusan</p>
                        <div class="relative h-64 w-full"><canvas id="barangColumnChart"></canvas></div>
                    </div>
                    <div class="lg:col-span-5 bg-white p-6 rounded-2xl border border-sage-200/80 shadow-sm flex flex-col justify-between">
                        <div>
                            <h3 class="text-base font-bold text-slate-800 mb-1">Persentase Stok Barang per Jurusan</h3>
                            <p class="text-xs text-slate-500 mb-4">Proporsi stok barang di seluruh jurusan</p>
                        </div>
                        <div class="relative h-56 w-full flex items-center justify-center"><canvas id="barangPieChart"></canvas></div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="bg-white rounded-2xl border border-sage-200/80 shadow-sm p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3.5 mb-5">
                        <div>
                            <h3 class="text-base font-bold text-slate-800">Data Barang</h3>
                            <p class="text-xs text-slate-500">Kelola data alat, stok total/tersedia, dan kode barcode</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                            <button onclick="exportTableToCSV('tableBarang', 'master_barang.csv')" class="px-3.5 py-2 bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200 rounded-xl font-bold text-xs flex items-center gap-1.5 transition-colors" title="Download data sebagai CSV">
                                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Export CSV</span>
                            </button>
                            <?php if (!empty($user['peran']) && $user['peran'] !== 'siswa'): ?>
                            <input type="file" id="directCsvFileInput" accept=".csv" class="hidden" onchange="handleDirectCsvImport(event)">
                            <button onclick="document.getElementById('directCsvFileInput').click()" class="px-3.5 py-2 bg-sage-50 text-sage-700 hover:bg-sage-100 border border-sage-200 rounded-xl font-bold text-xs flex items-center gap-1.5 transition-colors" title="Import data barang dari file CSV">+ Import CSV</button>
                            <button onclick="openModal('modalBarang', 'Tambah Data Barang Baru')" class="px-4 py-2 bg-sage-600 text-white rounded-xl font-bold text-xs shadow-md shadow-sage-600/20 hover:bg-sage-700 transition-colors">+ Tambah Barang Baru</button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="overflow-x-auto max-w-full w-full block align-middle rounded-xl border border-sage-100">
                        <table id="tableBarang" class="w-full text-left text-xs text-slate-600">
                            <thead class="bg-sage-50 text-slate-700 font-bold border-b border-sage-200">
                                <tr>
                                    <th class="py-3 px-3 w-10 text-center"><input type="checkbox" class="select-all-checkbox rounded accent-sage-600 cursor-pointer" onchange="toggleSelectAll(this)"></th>
                                    <th class="py-3 px-4 w-12 text-center">No</th>
                                    <th class="py-3 px-4">Nama Barang</th>
                                    <th class="py-3 px-4">Kategori</th>
                                    <th class="py-3 px-4">Rak</th>
                                    <?php if (!empty($user['peran']) && $user['peran'] === 'admin_sekolah'): ?><th class="py-3 px-4">Jurusan</th><?php endif; ?>
                                    <th class="py-3 px-4">Merek</th>
                                    <th class="py-3 px-4">Barcode</th>
                                    <th class="py-3 px-4">Stock Total</th>
                                    <th class="py-3 px-4">Stock Tersedia</th>
                                    <th class="py-3 px-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php $no = 1; foreach ($dbBarang as $b): ?>
                                    <?php 
                                        $jColor = (!empty($b['jurusan_id']) && isset($jurusanColors[$b['jurusan_id']])) ? $jurusanColors[$b['jurusan_id']] : '#2E7D32';
                                    ?>
                                    <tr id="row-barang-<?= htmlspecialchars($b['id']); ?>" class="hover:bg-sage-50/50 transition-all duration-300">
                                        <td class="py-3.5 px-3 text-center"><input type="checkbox" class="row-checkbox rounded accent-sage-600 cursor-pointer" value="<?= htmlspecialchars($b['id']); ?>" onchange="updateBatchDeleteBar()"></td>
                                        <td class="py-3.5 px-4 text-center font-bold text-slate-500 row-number-cell"><?= $no++; ?></td>
                                        <td class="py-3.5 px-4 font-extrabold text-xs nama-barang-cell" style="color: <?= htmlspecialchars($jColor); ?>;"><?= htmlspecialchars($b['nama_barang']); ?></td>
                                        <td class="py-3.5 px-4 font-semibold text-slate-700"><?= htmlspecialchars($b['nama_kategori'] ?? '-'); ?></td>
                                        <td class="py-3.5 px-4 font-semibold text-slate-700"><?= htmlspecialchars($b['nama_rak'] ?? '-'); ?></td>
                                        <?php if (!empty($user['peran']) && $user['peran'] === 'admin_sekolah'): ?><td class="py-3.5 px-4 font-bold text-sage-700"><?= htmlspecialchars($b['nama_jurusan'] ?? 'Semua Jurusan'); ?></td><?php endif; ?>
                                        <td class="py-3.5 px-4"><?= htmlspecialchars($b['merek'] ?? '-'); ?></td>
                                        <td class="py-3.5 px-4 font-mono text-sage-700">
                                            <?php if (!empty($b['barcode'])): ?>
                                                <button type="button" onclick="showBarcodeModal('<?= htmlspecialchars($b['barcode']); ?>', '<?= htmlspecialchars(addslashes($b['nama_barang'])); ?>')" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-sage-50 hover:bg-sage-100 text-sage-800 border border-sage-200 font-bold transition-all group" title="Klik untuk preview / simpan barcode">
                                                    <svg class="w-4 h-4 text-sage-600 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                                    </svg>
                                                    <span><?= htmlspecialchars($b['barcode']); ?></span>
                                                </button>
                                            <?php else: ?>
                                                <span class="text-slate-400">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-3.5 px-4 font-bold"><?= htmlspecialchars($b['stok_total']); ?> <?= htmlspecialchars($b['satuan'] ?? 'Unit'); ?></td>
                                        <td class="py-3.5 px-4 font-bold text-sage-600"><?= htmlspecialchars($b['stok_tersedia']); ?> <?= htmlspecialchars($b['satuan'] ?? 'Unit'); ?></td>
                                        <td class="py-3.5 px-4">
                                            <?php if (!empty($user['peran']) && $user['peran'] !== 'siswa'): ?>
                                            <div class="flex items-center gap-1.5">
                                                <button type="button" onclick="editBarang('<?= htmlspecialchars($b['id']); ?>')" class="p-1.5 rounded-lg bg-sage-600 hover:bg-sage-700 text-white shadow-sm transition-all" title="Edit Master Barang">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 01-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                </button>
                                                <button type="button" onclick="deleteBarang('<?= htmlspecialchars($b['id']); ?>', '<?= htmlspecialchars(addslashes($b['nama_barang'])); ?>')" class="p-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white shadow-sm transition-all" title="Hapus Data Barang">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </div>
                                            <?php else: ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-sage-50 text-sage-700 border border-sage-200">Read Only</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- SECTION 5: TAB BARANG MASUK (MySQL Live) -->
            <div id="tab-barang-masuk" class="tab-content hidden space-y-6">
                <!-- Stat Cards Row for Barang Masuk -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div class="bg-white p-5 rounded-2xl border border-sage-200/80 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Transaksi Masuk</span>
                            <div class="w-10 h-10 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            </div>
                        </div>
                        <h3 id="statTabMasukTotal" class="text-2xl font-extrabold text-slate-800"><?= count($dbBarangMasuk); ?> Transaksi</h3>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-sage-200/80 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Unit Masuk</span>
                            <div class="w-10 h-10 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                        </div>
                        <h3 id="statTabMasukTotalItem" class="text-2xl font-extrabold text-slate-800">+<?= $totalUnitMasuk; ?> Unit</h3>
                    </div>
                </div>

                <?php if ($isSuperAdmin): ?>
                <!-- Charts Row for Barang Masuk -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <div class="lg:col-span-7 bg-white p-6 rounded-2xl border border-sage-200/80 shadow-sm">
                        <h3 class="text-base font-bold text-slate-800 mb-1">Barang Masuk per Jurusan</h3>
                        <p class="text-xs text-slate-500 mb-4">Total unit barang yang diterima di masing-masing jurusan</p>
                        <div class="relative h-64 w-full"><canvas id="masukColumnChart"></canvas></div>
                    </div>
                    <div class="lg:col-span-5 bg-white p-6 rounded-2xl border border-sage-200/80 shadow-sm flex flex-col justify-between">
                        <div>
                            <h3 class="text-base font-bold text-slate-800 mb-1">Persentase Barang Masuk per Jurusan</h3>
                            <p class="text-xs text-slate-500 mb-4">Proporsi penerimaan barang per jurusan</p>
                        </div>
                        <div class="relative h-56 w-full flex items-center justify-center"><canvas id="masukPieChart"></canvas></div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="bg-white rounded-2xl border border-sage-200/80 shadow-sm p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3.5 mb-5">
                        <div>
                            <h3 class="text-base font-bold text-slate-800">Transaksi Barang Masuk</h3>
                            <p class="text-xs text-slate-500">Catatan pengadaan dan penerimaan barang</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                            <button onclick="exportTableToCSV('tableBarangMasuk', 'barang_masuk.csv')" class="px-3.5 py-2 bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200 rounded-xl font-bold text-xs flex items-center gap-1.5 transition-colors" title="Download data sebagai CSV">
                                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Export CSV</span>
                            </button>
                            <button onclick="openModal('modalBarangMasuk')" class="px-4 py-2 bg-sage-600 text-white rounded-xl font-bold text-xs shadow-md shadow-sage-600/20 hover:bg-sage-700 transition-colors">+ Catat Barang Masuk</button>
                        </div>
                    </div>
                    <div class="overflow-x-auto max-w-full w-full block align-middle rounded-xl border border-sage-100">
                        <table id="tableBarangMasuk" class="w-full text-left text-xs text-slate-600">
                            <thead class="bg-sage-50 text-slate-700 font-bold border-b border-sage-200">
                                <tr>
                                    <th class="py-3 px-3 w-10 text-center"><input type="checkbox" class="select-all-checkbox rounded accent-sage-600 cursor-pointer" onchange="toggleSelectAll(this)"></th>
                                    <th class="py-3 px-4 w-12 text-center">No</th>
                                    <th class="py-3 px-4">Barang</th>
                                    <?php if (!empty($user['peran']) && $user['peran'] === 'admin_sekolah'): ?><th class="py-3 px-4">Jurusan</th><?php endif; ?>
                                    <th class="py-3 px-4">Nama Pemasok</th>
                                    <th class="py-3 px-4">Jumlah</th>
                                    <th class="py-3 px-4">Petugas</th>
                                    <th class="py-3 px-4">Tgl Masuk</th>
                                    <th class="py-3 px-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php $no = 1; foreach ($dbBarangMasuk as $bm): ?>
                                    <tr class="hover:bg-sage-50/50">
                                        <td class="py-3.5 px-3 text-center"><input type="checkbox" class="row-checkbox rounded accent-sage-600 cursor-pointer" value="<?= htmlspecialchars($bm['id']); ?>" onchange="updateBatchDeleteBar()"></td>
                                        <td class="py-3.5 px-4 text-center font-bold text-slate-500 row-number-cell"><?= $no++; ?></td>
                                        <td class="py-3.5 px-4 font-bold text-slate-800"><?= htmlspecialchars($bm['nama_barang']); ?></td>
                                        <?php if (!empty($user['peran']) && $user['peran'] === 'admin_sekolah'): ?><td class="py-3.5 px-4 font-bold text-sage-700"><?= htmlspecialchars($bm['nama_jurusan'] ?? '-'); ?></td><?php endif; ?>
                                        <td class="py-3.5 px-4"><?= htmlspecialchars($bm['nama_pemasok'] ?? '-'); ?></td>
                                        <td class="py-3.5 px-4 font-bold text-emerald-600">+<?= htmlspecialchars($bm['jumlah']); ?> <?= htmlspecialchars($bm['satuan'] ?? 'Unit'); ?></td>
                                        <td class="py-3.5 px-4"><?= htmlspecialchars($bm['nama_petugas'] ?? 'Petugas'); ?></td>
                                        <td class="py-3.5 px-4"><?= date('d M Y', strtotime($bm['created_at'])); ?></td>
                                        <td class="py-3.5 px-4">
                                            <div class="flex items-center gap-1.5">
                                                <button type="button" onclick="editBarangMasuk('<?= htmlspecialchars($bm['id']); ?>')" class="p-1.5 rounded-lg bg-sage-600 hover:bg-sage-700 text-white shadow-sm transition-all" title="Edit Transaksi Barang Masuk">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 01-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                </button>
                                                <button type="button" onclick="deleteBarangMasuk('<?= htmlspecialchars($bm['id']); ?>', '<?= htmlspecialchars(addslashes($bm['nama_barang'])); ?>')" class="p-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white shadow-sm transition-all" title="Hapus Transaksi Barang Masuk">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- SECTION 6: TAB BARANG KELUAR (MySQL Live) -->
            <div id="tab-barang-keluar" class="tab-content hidden space-y-6">
                <!-- Stat Cards Row for Barang Keluar -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div class="bg-white p-5 rounded-2xl border border-sage-200/80 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Transaksi Keluar</span>
                            <div class="w-10 h-10 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                            </div>
                        </div>
                        <h3 id="statTabKeluarTotal" class="text-2xl font-extrabold text-slate-800"><?= count($dbBarangKeluar); ?> Transaksi</h3>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-sage-200/80 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Unit Keluar</span>
                            <div class="w-10 h-10 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7"/></svg>
                            </div>
                        </div>
                        <h3 id="statTabKeluarTotalItem" class="text-2xl font-extrabold text-slate-800">-<?= $totalUnitKeluar; ?> Unit</h3>
                    </div>
                </div>

                <?php if ($isSuperAdmin): ?>
                <!-- Charts Row for Barang Keluar -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <div class="lg:col-span-7 bg-white p-6 rounded-2xl border border-sage-200/80 shadow-sm">
                        <h3 class="text-base font-bold text-slate-800 mb-1">Barang Keluar per Jurusan</h3>
                        <p class="text-xs text-slate-500 mb-4">Total unit barang yang dikeluarkan di masing-masing jurusan</p>
                        <div class="relative h-64 w-full"><canvas id="keluarColumnChart"></canvas></div>
                    </div>
                    <div class="lg:col-span-5 bg-white p-6 rounded-2xl border border-sage-200/80 shadow-sm flex flex-col justify-between">
                        <div>
                            <h3 class="text-base font-bold text-slate-800 mb-1">Persentase Barang Keluar per Jurusan</h3>
                            <p class="text-xs text-slate-500 mb-4">Proporsi pengeluaran barang per jurusan</p>
                        </div>
                        <div class="relative h-56 w-full flex items-center justify-center"><canvas id="keluarPieChart"></canvas></div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="bg-white rounded-2xl border border-sage-200/80 shadow-sm p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3.5 mb-5">
                        <div>
                            <h3 class="text-base font-bold text-slate-800">Transaksi Barang Keluar</h3>
                            <p class="text-xs text-slate-500">Catatan pengeluaran barang inventaris</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                            <button onclick="exportTableToCSV('tableBarangKeluar', 'barang_keluar.csv')" class="px-3.5 py-2 bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200 rounded-xl font-bold text-xs flex items-center gap-1.5 transition-colors" title="Download data sebagai CSV">
                                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Export CSV</span>
                            </button>
                            <button onclick="openModal('modalBarangKeluar')" class="px-4 py-2 bg-sage-600 text-white rounded-xl font-bold text-xs shadow-md shadow-sage-600/20 hover:bg-sage-700 transition-colors">+ Catat Barang Keluar</button>
                        </div>
                    </div>
                    <div class="overflow-x-auto max-w-full w-full block align-middle rounded-xl border border-sage-100">
                        <table id="tableBarangKeluar" class="w-full text-left text-xs text-slate-600">
                            <thead class="bg-sage-50 text-slate-700 font-bold border-b border-sage-200">
                                <tr>
                                    <th class="py-3 px-3 w-10 text-center"><input type="checkbox" class="select-all-checkbox rounded accent-sage-600 cursor-pointer" onchange="toggleSelectAll(this)"></th>
                                    <th class="py-3 px-4 w-12 text-center">No</th>
                                    <th class="py-3 px-4">Barang</th>
                                    <?php if (!empty($user['peran']) && $user['peran'] === 'admin_sekolah'): ?><th class="py-3 px-4">Jurusan</th><?php endif; ?>
                                    <th class="py-3 px-4">Nama Penerima</th>
                                    <th class="py-3 px-4">Jumlah</th>
                                    <th class="py-3 px-4">Petugas</th>
                                    <th class="py-3 px-4">Tgl Keluar</th>
                                    <th class="py-3 px-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php $no = 1; foreach ($dbBarangKeluar as $bk): ?>
                                    <tr class="hover:bg-sage-50/50">
                                        <td class="py-3.5 px-3 text-center"><input type="checkbox" class="row-checkbox rounded accent-sage-600 cursor-pointer" value="<?= htmlspecialchars($bk['id']); ?>" onchange="updateBatchDeleteBar()"></td>
                                        <td class="py-3.5 px-4 text-center font-bold text-slate-500 row-number-cell"><?= $no++; ?></td>
                                        <td class="py-3.5 px-4 font-bold text-slate-800"><?= htmlspecialchars($bk['nama_barang']); ?></td>
                                        <?php if (!empty($user['peran']) && $user['peran'] === 'admin_sekolah'): ?><td class="py-3.5 px-4 font-bold text-sage-700"><?= htmlspecialchars($bk['nama_jurusan'] ?? '-'); ?></td><?php endif; ?>
                                        <td class="py-3.5 px-4"><?= htmlspecialchars($bk['nama_penerima'] ?? '-'); ?></td>
                                        <td class="py-3.5 px-4 font-bold text-amber-600">-<?= htmlspecialchars($bk['jumlah']); ?> <?= htmlspecialchars($bk['satuan'] ?? 'Unit'); ?></td>
                                        <td class="py-3.5 px-4"><?= htmlspecialchars($bk['nama_petugas'] ?? 'Petugas'); ?></td>
                                        <td class="py-3.5 px-4"><?= date('d M Y', strtotime($bk['created_at'])); ?></td>
                                        <td class="py-3.5 px-4">
                                            <div class="flex items-center gap-1.5">
                                                <button type="button" onclick="editBarangKeluar('<?= htmlspecialchars($bk['id']); ?>')" class="p-1.5 rounded-lg bg-sage-600 hover:bg-sage-700 text-white shadow-sm transition-all" title="Edit Transaksi Barang Keluar">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 01-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                </button>
                                                <button type="button" onclick="deleteBarangKeluar('<?= htmlspecialchars($bk['id']); ?>', '<?= htmlspecialchars(addslashes($bk['nama_barang'])); ?>')" class="p-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white shadow-sm transition-all" title="Hapus Transaksi Barang Keluar">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- SECTION 7: TAB PEMINJAMAN ALAT (MySQL Live) -->
            <div id="tab-peminjaman" class="tab-content hidden space-y-6">
                <!-- Stat Cards Row for Peminjaman -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div class="bg-white p-5 rounded-2xl border border-sage-200/80 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Peminjaman</span>
                            <div class="w-10 h-10 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                            </div>
                        </div>
                        <h3 id="statTabPinjamTotal" class="text-2xl font-extrabold text-slate-800"><?= count($dbPeminjaman); ?> Transaksi</h3>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-sage-200/80 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Belum Dikembalikan</span>
                            <div class="w-10 h-10 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                        </div>
                        <h3 id="statTabPinjamBelumKembali" class="text-2xl font-extrabold text-slate-800"><?= $pinjamBelumKembali; ?> Transaksi</h3>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-sage-200/80 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Sudah Dikembalikan</span>
                            <div class="w-10 h-10 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                        </div>
                        <h3 id="statTabPinjamSudahKembali" class="text-2xl font-extrabold text-slate-800"><?= $pinjamSudahKembali; ?> Transaksi</h3>
                    </div>
                </div>

                <?php if ($isSuperAdmin): ?>
                <!-- Charts Row for Peminjaman -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <div class="lg:col-span-7 bg-white p-6 rounded-2xl border border-sage-200/80 shadow-sm">
                        <h3 class="text-base font-bold text-slate-800 mb-1">Total Peminjaman per Jurusan</h3>
                        <p class="text-xs text-slate-500 mb-4">Jumlah transaksi peminjaman alat praktik di masing-masing jurusan</p>
                        <div class="relative h-64 w-full"><canvas id="pinjamColumnChart"></canvas></div>
                    </div>
                    <div class="lg:col-span-5 bg-white p-6 rounded-2xl border border-sage-200/80 shadow-sm flex flex-col justify-between">
                        <div>
                            <h3 class="text-base font-bold text-slate-800 mb-1">Persentase Peminjaman per Jurusan</h3>
                            <p class="text-xs text-slate-500 mb-4">Proporsi peminjaman barang per jurusan</p>
                        </div>
                        <div class="relative h-56 w-full flex items-center justify-center"><canvas id="pinjamPieChart"></canvas></div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="bg-white rounded-2xl border border-sage-200/80 shadow-sm p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3.5 mb-5">
                        <div>
                            <h3 class="text-base font-bold text-slate-800">Sirkulasi Peminjaman Alat</h3>
                            <p class="text-xs text-slate-500">Kelola persetujuan & pengembalian barang pinjaman siswa</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                            <button onclick="exportTableToCSV('tablePeminjaman', 'peminjaman_alat.csv')" class="px-3.5 py-2 bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200 rounded-xl font-bold text-xs flex items-center gap-1.5 transition-colors" title="Download data sebagai CSV">
                                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Export CSV</span>
                            </button>
                            <button onclick="openModal('modalPeminjaman')" class="px-4 py-2 bg-sage-600 text-white rounded-xl font-bold text-xs shadow-md shadow-sage-600/20 hover:bg-sage-700 transition-colors">+ Peminjaman</button>
                        </div>
                    </div>
                    <div class="overflow-x-auto max-w-full w-full block align-middle rounded-xl border border-sage-100">
                        <table id="tablePeminjaman" class="w-full text-left text-xs text-slate-600">
                            <thead class="bg-sage-50 text-slate-700 font-bold border-b border-sage-200">
                                <tr>
                                    <th class="py-3 px-3 w-10 text-center"><input type="checkbox" class="select-all-checkbox rounded accent-sage-600 cursor-pointer" onchange="toggleSelectAll(this)"></th>
                                    <th class="py-3 px-4 w-12 text-center">No</th>
                                    <th class="py-3 px-4">Barang</th>
                                    <?php if (!empty($user['peran']) && $user['peran'] === 'admin_sekolah'): ?><th class="py-3 px-4">Jurusan</th><?php endif; ?>
                                    <th class="py-3 px-4">Peminjam (Siswa)</th>
                                    <th class="py-3 px-4">Petugas</th>
                                    <th class="py-3 px-4">Jumlah</th>
                                    <th class="py-3 px-4">Tugas</th>
                                    <th class="py-3 px-4">Thn Ajaran</th>
                                    <th class="py-3 px-4">Tgl Pinjam</th>
                                    <th class="py-3 px-4">Tgl Kembali</th>
                                    <th class="py-3 px-4">Status</th>
                                    <th class="py-3 px-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php $no = 1; foreach ($dbPeminjaman as $pm): ?>
                                    <tr class="hover:bg-sage-50/50">
                                        <td class="py-3.5 px-3 text-center"><input type="checkbox" class="row-checkbox rounded accent-sage-600 cursor-pointer" value="<?= htmlspecialchars($pm['id']); ?>" onchange="updateBatchDeleteBar()"></td>
                                        <td class="py-3.5 px-4 text-center font-bold text-slate-500 row-number-cell"><?= $no++; ?></td>
                                        <td class="py-3.5 px-4 font-bold text-slate-800"><?= htmlspecialchars($pm['nama_barang']); ?></td>
                                        <?php if (!empty($user['peran']) && $user['peran'] === 'admin_sekolah'): ?><td class="py-3.5 px-4 font-bold text-sage-700"><?= htmlspecialchars($pm['nama_jurusan'] ?? '-'); ?></td><?php endif; ?>
                                        <td class="py-3.5 px-4"><?= htmlspecialchars($pm['nama_peminjam']); ?></td>
                                        <td class="py-3.5 px-4 font-semibold text-slate-700"><?= htmlspecialchars($pm['nama_petugas'] ?? '-'); ?></td>
                                        <td class="py-3.5 px-4 font-semibold"><?= htmlspecialchars($pm['jumlah']); ?> Unit</td>
                                        <td class="py-3.5 px-4 font-semibold text-slate-700"><?= htmlspecialchars($pm['tugas'] ?? '-'); ?></td>
                                        <td class="py-3.5 px-4 font-semibold text-sage-700"><?= htmlspecialchars($pm['tahun_ajaran'] ?? '2025/2026'); ?></td>
                                        <td class="py-3.5 px-4"><?= date('d M Y', strtotime($pm['tanggal_pinjam'])); ?></td>
                                        <td class="py-3.5 px-4 font-mono">
                                            <?php if (!empty($pm['tanggal_kembali']) && $pm['status'] === 'dikembalikan'): ?>
                                                <?= date('d M Y (H:i)', strtotime($pm['tanggal_kembali'])); ?>
                                            <?php else: ?>
                                                <span class="text-slate-400 font-normal">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-3.5 px-4 text-center">
                                            <?php if ($pm['status'] === 'dipinjam'): ?>
                                                <span class="text-amber-500 font-extrabold">Dipinjam</span>
                                            <?php elseif ($pm['status'] === 'pending'): ?>
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-amber-100 text-amber-800 border border-amber-300">
                                                    <svg class="w-3 h-3 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    Pending
                                                </span>
                                            <?php elseif ($pm['status'] === 'ditolak'): ?>
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-red-100 text-red-800 border border-red-300">
                                                    Ditolak
                                                </span>
                                            <?php else: ?>
                                                <span class="font-extrabold" style="color: <?= $activeThemePalette['600'] ?? '#2e7d32'; ?>;">
                                                    Dikembalikan
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-3.5 px-4">
                                            <div class="flex items-center gap-1.5">
                                                <?php if ($pm['status'] === 'dipinjam'): ?>
                                                    <button type="button" onclick="kembalikanPeminjaman('<?= htmlspecialchars($pm['id']); ?>')" class="px-2.5 py-1 rounded-lg bg-sage-600 hover:bg-sage-700 text-white font-bold text-[11px] shadow-sm transition-all" title="Kembalikan Alat">
                                                        Kembalikan
                                                    </button>
                                                <?php elseif ($pm['status'] === 'pending'): ?>
                                                    <button type="button" onclick="approvePeminjaman('<?= htmlspecialchars($pm['id']); ?>')" class="px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] shadow-sm transition-all flex items-center gap-1" title="Setujui Pengembalian">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                        Setujui
                                                    </button>
                                                    <button type="button" onclick="rejectPeminjaman('<?= htmlspecialchars($pm['id']); ?>')" class="px-2.5 py-1 rounded-lg bg-red-600 hover:bg-red-700 text-white font-bold text-[11px] shadow-sm transition-all flex items-center gap-1" title="Tolak Pengembalian">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                        Tolak
                                                    </button>
                                                    <?php if (!empty($pm['bukti_foto'])): ?>
                                                        <button type="button" onclick="showFotoPreview('<?= htmlspecialchars($pm['bukti_foto']); ?>', 'Bukti Foto Pengembalian Alat', 'Peminjam: <?= htmlspecialchars(addslashes($pm['nama_peminjam'] ?? '')); ?> | Alat: <?= htmlspecialchars(addslashes($pm['nama_barang'] ?? '')); ?>')" class="p-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow-sm transition-all" title="Lihat Bukti Foto">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                        </button>
                                                    <?php endif; ?>
                                                <?php elseif ($pm['status'] === 'ditolak'): ?>
                                                    <button type="button" onclick="kembalikanPeminjaman('<?= htmlspecialchars($pm['id']); ?>')" class="px-2.5 py-1 rounded-lg bg-amber-600 hover:bg-amber-700 text-white font-bold text-[11px] shadow-sm transition-all flex items-center gap-1" title="Upload Ulang Bukti Foto Pengembalian">
                                                        Upload Ulang
                                                    </button>
                                                    <?php if (!empty($pm['bukti_foto'])): ?>
                                                        <button type="button" onclick="showFotoPreview('<?= htmlspecialchars($pm['bukti_foto']); ?>', 'Bukti Foto Pengembalian Alat (Ditolak)', 'Peminjam: <?= htmlspecialchars(addslashes($pm['nama_peminjam'] ?? '')); ?> | Alat: <?= htmlspecialchars(addslashes($pm['nama_barang'] ?? '')); ?>')" class="p-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow-sm transition-all" title="Lihat Bukti Foto Ditolak">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                        </button>
                                                    <?php endif; ?>
                                                <?php endif; ?>

                                                <?php if ($pm['status'] !== 'dikembalikan'): ?>
                                                    <button type="button" onclick="editPeminjaman('<?= htmlspecialchars($pm['id']); ?>')" class="p-1.5 rounded-lg bg-sage-600 hover:bg-sage-700 text-white shadow-sm transition-all" title="Edit Transaksi Peminjaman">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 01-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                    </button>
                                                    <button type="button" onclick="deletePeminjaman('<?= htmlspecialchars($pm['id']); ?>', '<?= htmlspecialchars(addslashes($pm['nama_peminjam'])); ?>')" class="p-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white shadow-sm transition-all" title="Hapus Transaksi Peminjaman">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                <?php else: ?>
                                                    <?php if (!empty($pm['bukti_foto'])): ?>
                                                        <button type="button" onclick="showFotoPreview('<?= htmlspecialchars($pm['bukti_foto']); ?>', 'Bukti Foto Pengembalian Alat', 'Peminjam: <?= htmlspecialchars(addslashes($pm['nama_peminjam'] ?? '')); ?> | Alat: <?= htmlspecialchars(addslashes($pm['nama_barang'] ?? '')); ?>')" class="p-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow-sm transition-all" title="Lihat Bukti Foto">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                        </button>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- LOGS RIWAYAT PEMINJAMAN ALAT -->
                <div class="bg-white rounded-2xl border border-sage-200/80 shadow-sm p-6 mt-6">
                    <div class="flex items-center justify-between mb-4 border-b border-sage-100 pb-3">
                        <div>
                            <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                                <svg class="w-5 h-5 text-sage-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Logs Riwayat Peminjaman Alat</span>
                            </h3>
                            <p class="text-xs text-slate-500">Histori kronologis seluruh pengajuan & transaksi pengembalian barang pinjaman</p>
                        </div>
                        <button onclick="exportTableToCSV('tableLogPeminjaman', 'log_riwayat_peminjaman.csv')" class="px-3.5 py-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200 rounded-xl font-bold text-xs flex items-center gap-1.5 transition-colors">
                            <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Export Log CSV</span>
                        </button>
                    </div>
                    <div class="overflow-x-auto max-w-full w-full block align-middle rounded-xl border border-sage-100">
                        <table id="tableLogPeminjaman" class="w-full text-left text-xs text-slate-600">
                            <thead class="bg-sage-50 text-slate-700 font-bold border-b border-sage-200">
                                <tr>
                                    <th class="py-3 px-4 w-12 text-center">No</th>
                                    <th class="py-3 px-4">Nama Peminjam</th>
                                    <th class="py-3 px-4">Barang Pinjaman</th>
                                    <?php if (!empty($user['peran']) && $user['peran'] === 'admin_sekolah'): ?><th class="py-3 px-4">Jurusan</th><?php endif; ?>
                                    <th class="py-3 px-4">Petugas</th>
                                    <th class="py-3 px-4 text-center">Jumlah</th>
                                    <th class="py-3 px-4">Tanggal Pinjam</th>
                                    <th class="py-3 px-4">Tanggal Kembali</th>
                                    <th class="py-3 px-4 text-center">Status</th>
                                    <th class="py-3 px-4">Keperluan / Catatan</th>
                                    <th class="py-3 px-4">Bukti Foto</th>
                                    <th class="py-3 px-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php $lNo = 1; foreach ($dbPeminjaman as $logPm): ?>
                                    <tr class="hover:bg-sage-50/50">
                                        <td class="py-3 px-4 text-center font-bold text-slate-500 row-number-cell"><?= $lNo++; ?></td>
                                        <td class="py-3 px-4 font-bold text-slate-800"><?= htmlspecialchars($logPm['nama_peminjam'] ?? '-'); ?></td>
                                        <td class="py-3 px-4 font-semibold text-slate-700"><?= htmlspecialchars($logPm['nama_barang'] ?? '-'); ?></td>
                                        <?php if (!empty($user['peran']) && $user['peran'] === 'admin_sekolah'): ?><td class="py-3 px-4 font-bold text-sage-700"><?= htmlspecialchars($logPm['nama_jurusan'] ?? 'Semua Jurusan'); ?></td><?php endif; ?>
                                        <td class="py-3 px-4 font-semibold text-slate-700"><?= htmlspecialchars($logPm['nama_petugas'] ?? '-'); ?></td>
                                        <td class="py-3 px-4 text-center font-bold text-slate-800"><?= intval($logPm['jumlah'] ?? 1); ?> <?= htmlspecialchars($logPm['satuan'] ?? 'Unit'); ?></td>
                                        <td class="py-3 px-4 font-mono text-[11px] text-slate-600"><?= !empty($logPm['tanggal_pinjam']) ? date('d M Y H:i', strtotime($logPm['tanggal_pinjam'])) : '-'; ?></td>
                                        <td class="py-3 px-4 font-mono text-[11px] text-slate-600"><?= !empty($logPm['tanggal_kembali']) ? date('d M Y H:i', strtotime($logPm['tanggal_kembali'])) : '-'; ?></td>
                                        <td class="py-3 px-4 text-center font-bold">
                                            <?php if ($logPm['status'] === 'dipinjam'): ?>
                                                <span class="text-amber-500 font-extrabold">Dipinjam</span>
                                            <?php elseif ($logPm['status'] === 'pending'): ?>
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-amber-100 text-amber-800 border border-amber-300">
                                                    <svg class="w-3 h-3 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    Pending
                                                </span>
                                            <?php elseif ($logPm['status'] === 'ditolak'): ?>
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-red-100 text-red-800 border border-red-300">
                                                    Ditolak
                                                </span>
                                            <?php else: ?>
                                                <span class="font-extrabold" style="color: <?= $activeThemePalette['600'] ?? '#2e7d32'; ?>;">Dikembalikan</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-3 px-4 text-slate-600"><?= htmlspecialchars($logPm['tugas'] ?? $logPm['catatan'] ?? '-'); ?></td>
                                        <td class="py-3 px-4">
                                            <?php if (!empty($logPm['bukti_foto'])): ?>
                                                <button type="button" onclick="showFotoPreview('<?= htmlspecialchars($logPm['bukti_foto']); ?>', 'Bukti Foto Pengembalian Alat', 'Peminjam: <?= htmlspecialchars(addslashes($logPm['nama_peminjam'] ?? '')); ?> | Alat: <?= htmlspecialchars(addslashes($logPm['nama_barang'] ?? '')); ?>')" class="px-3 py-1 rounded-xl bg-sage-600 hover:bg-sage-700 text-white font-bold text-xs shadow-md shadow-sage-600/20 transition-all inline-flex items-center gap-1.5" title="Lihat Foto Bukti Pengembalian Alat">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                    <span>View</span>
                                                </button>
                                            <?php else: ?>
                                                <span class="text-slate-400 font-normal">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-3 px-4">
                                            <?php if ($logPm['status'] === 'dikembalikan'): ?>
                                                <?php if (!empty($user['peran']) && $user['peran'] !== 'siswa'): ?>
                                                    <button type="button" onclick="editPeminjaman('<?= htmlspecialchars($logPm['id']); ?>')" class="p-1.5 rounded-lg bg-sage-600 hover:bg-sage-700 text-white shadow-sm transition-all flex items-center gap-1 text-xs font-bold px-2.5" title="Edit Status Peminjaman">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 01-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                        <span>Edit Status</span>
                                                    </button>
                                                <?php else: ?>
                                                    <span class="text-slate-400 font-normal">-</span>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-slate-400 font-normal">-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- SECTION 8: TAB LOG AKTIVITAS (MySQL Live) -->
            <div id="tab-log-aktivitas" class="tab-content hidden space-y-6">
                <!-- Stat Card Row for Log Aktivitas -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div class="bg-white p-5 rounded-2xl border border-sage-200/80 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Log Aktivitas</span>
                            <div class="w-10 h-10 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                        </div>
                        <h3 id="statTabLogAktivitasTotal" class="text-2xl font-extrabold text-slate-800"><?= $totalLogsCount; ?> Catatan</h3>
                    </div>
                </div>

                <?php if ($isSuperAdmin): ?>
                <!-- Charts Row for Log Aktivitas -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <div class="lg:col-span-7 bg-white p-6 rounded-2xl border border-sage-200/80 shadow-sm">
                        <h3 class="text-base font-bold text-slate-800 mb-1">Catatan Log Aktivitas per Jurusan</h3>
                        <p class="text-xs text-slate-500 mb-4">Total riwayat kegiatan sistem di setiap jurusan</p>
                        <div class="relative h-64 w-full"><canvas id="logColumnChart"></canvas></div>
                    </div>
                    <div class="lg:col-span-5 bg-white p-6 rounded-2xl border border-sage-200/80 shadow-sm flex flex-col justify-between">
                        <div>
                            <h3 class="text-base font-bold text-slate-800 mb-1">Persentase Aktivitas per Jurusan</h3>
                            <p class="text-xs text-slate-500 mb-4">Proporsi aktivitas sistem per jurusan</p>
                        </div>
                        <div class="relative h-56 w-full flex items-center justify-center"><canvas id="logPieChart"></canvas></div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="bg-white rounded-2xl border border-sage-200/80 shadow-sm p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3.5 mb-5">
                        <div>
                            <h3 class="text-base font-bold text-slate-800">Log Aktivitas Sistem</h3>
                            <p class="text-xs text-slate-500">Catatan riwayat seluruh kegiatan pengguna</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                            <button onclick="exportTableToCSV('tableLogAktivitas', 'log_aktivitas.csv')" class="px-3.5 py-2 bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200 rounded-xl font-bold text-xs flex items-center gap-1.5 transition-colors" title="Download data sebagai CSV">
                                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Export CSV</span>
                            </button>
                            <?php if (!empty($user['peran']) && ($user['peran'] === 'admin_sekolah' || $user['peran'] === 'admin_jurusan')): ?>
                            <button type="button" onclick="clearAllLogs()" class="px-3.5 py-2 bg-red-50 text-red-700 hover:bg-red-100 border border-red-200 rounded-xl font-bold text-xs flex items-center gap-1.5 transition-colors" title="Kosongkan seluruh riwayat log aktivitas">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                <span>Clear All Logs</span>
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- FILTER BAR LOG AKTIVITAS (BY DATE, BY TINDAKAN, BY JURUSAN, BY NAME) -->
                    <div class="mb-5 p-4 bg-sage-50 dark:bg-[#171717] border border-sage-200 dark:border-[#262626] rounded-2xl space-y-3 transition-colors">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-extrabold text-slate-700 dark:text-[#a3a3a3] uppercase tracking-wider flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-sage-600 dark:text-sage-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                                Filter Log Aktivitas
                            </h4>
                            <button type="button" onclick="resetLogFilters()" class="text-xs font-bold text-sage-600 dark:text-sage-400 hover:text-sage-800 dark:hover:text-sage-300 transition-colors flex items-center gap-1" title="Reset Semua Filter">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                <span>Reset Filter</span>
                            </button>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                            <!-- 1. BY DATE -->
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 mb-1">Tanggal</label>
                                <input type="date" id="filterLogDate" onchange="filterTableLogAktivitas()" class="w-full px-3 py-1.5 bg-white dark:bg-[#0a0a0a] border border-slate-200 dark:border-[#262626] rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-200 focus:outline-none focus:border-sage-600 dark:focus:border-sage-500 transition-all shadow-sm dark:[color-scheme:dark]">
                            </div>
                            <!-- 2. BY TINDAKAN -->
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 mb-1">Tindakan / Aksi</label>
                                <select id="filterLogTindakan" onchange="filterTableLogAktivitas()" class="w-full px-3 py-1.5 bg-white dark:bg-[#0a0a0a] border border-slate-200 dark:border-[#262626] rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-200 focus:outline-none focus:border-sage-600 dark:focus:border-sage-500 transition-all shadow-sm">
                                    <option value="" class="bg-white dark:bg-[#171717] text-slate-800 dark:text-slate-200">Semua Tindakan</option>
                                    <?php
                                        $uniqueTindakan = array_unique(array_filter(array_column($dbLogAktivitas, 'tindakan')));
                                        sort($uniqueTindakan);
                                        foreach ($uniqueTindakan as $act):
                                    ?>
                                        <option value="<?= htmlspecialchars(strtoupper($act)); ?>" class="bg-white dark:bg-[#171717] text-slate-800 dark:text-slate-200"><?= htmlspecialchars(strtoupper($act)); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <!-- 3. BY JURUSAN -->
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 mb-1">Jurusan</label>
                                <select id="filterLogJurusan" onchange="filterTableLogAktivitas()" class="w-full px-3 py-1.5 bg-white dark:bg-[#0a0a0a] border border-slate-200 dark:border-[#262626] rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-200 focus:outline-none focus:border-sage-600 dark:focus:border-sage-500 transition-all shadow-sm">
                                    <option value="" class="bg-white dark:bg-[#171717] text-slate-800 dark:text-slate-200">Semua Jurusan</option>
                                    <?php foreach ($dbJurusan as $j): ?>
                                        <option value="<?= htmlspecialchars(strtolower($j['nama_jurusan'])); ?>" class="bg-white dark:bg-[#171717] text-slate-800 dark:text-slate-200"><?= htmlspecialchars($j['nama_jurusan']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <!-- 4. BY NAME -->
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 mb-1">Nama Pengguna</label>
                                <div class="relative">
                                    <input type="text" id="filterLogName" oninput="filterTableLogAktivitas()" placeholder="Cari nama..." class="w-full pl-8 pr-3 py-1.5 bg-white dark:bg-[#0a0a0a] border border-slate-200 dark:border-[#262626] rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-sage-600 dark:focus:border-sage-500 transition-all shadow-sm">
                                    <svg class="w-3.5 h-3.5 text-slate-400 dark:text-slate-500 absolute left-2.5 top-2.5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto max-w-full w-full block align-middle rounded-xl border border-sage-100">
                        <table id="tableLogAktivitas" class="w-full text-left text-xs text-slate-600">
                            <thead class="bg-sage-50 text-slate-700 font-bold border-b border-sage-200">
                                <tr>
                                    <th class="py-3 px-4 w-12 text-center">No</th>
                                    <th class="py-3 px-4">Pengguna</th>
                                    <th class="py-3 px-4">Tindakan</th>
                                    <?php if (!empty($user['peran']) && $user['peran'] === 'admin_sekolah'): ?><th class="py-3 px-4">Jurusan</th><?php endif; ?>
                                    <th class="py-3 px-4">Deskripsi Rincian</th>
                                    <th class="py-3 px-4">Waktu</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php $no = 1; foreach ($dbLogAktivitas as $log): ?>
                                    <tr class="hover:bg-sage-50/50" 
                                        data-date="<?= date('Y-m-d', strtotime($log['created_at'])); ?>" 
                                        data-tindakan="<?= htmlspecialchars(strtoupper($log['tindakan'] ?? '')); ?>" 
                                        data-jurusan="<?= htmlspecialchars(strtolower($log['nama_jurusan'] ?? '-')); ?>" 
                                        data-pengguna="<?= htmlspecialchars(strtolower($log['nama_pengguna'] ?? $log['nama_lengkap'] ?? 'Sistem')); ?>">
                                        <td class="py-3.5 px-4 text-center font-bold text-slate-500 row-number-cell"><?= $no++; ?></td>
                                        <td class="py-3.5 px-4 font-bold text-slate-800"><?= htmlspecialchars($log['nama_pengguna'] ?? $log['nama_lengkap'] ?? 'Sistem'); ?></td>
                                        <td class="py-3.5 px-4 font-bold text-slate-800">
                                            <?php 
                                                $tindakan = strtoupper($log['tindakan'] ?? '');
                                                $badgeStyle = 'bg-sage-100 text-sage-800 border-sage-200';
                                                $dotColor = 'bg-sage-600';

                                                if ($tindakan === 'LOGIN') {
                                                    $badgeStyle = 'bg-emerald-100 text-emerald-800 border-emerald-200';
                                                    $dotColor = 'bg-emerald-600';
                                                } elseif ($tindakan === 'LOGOUT') {
                                                    $badgeStyle = 'bg-rose-100 text-rose-800 border-rose-200';
                                                    $dotColor = 'bg-rose-600';
                                                } elseif (str_contains($tindakan, 'TAMBAH') || str_contains($tindakan, 'CREATE')) {
                                                    $badgeStyle = 'bg-blue-100 text-blue-800 border-blue-200';
                                                    $dotColor = 'bg-blue-600';
                                                } elseif (str_contains($tindakan, 'HAPUS') || str_contains($tindakan, 'DELETE')) {
                                                    $badgeStyle = 'bg-red-100 text-red-800 border-red-200';
                                                    $dotColor = 'bg-red-600';
                                                } elseif (str_contains($tindakan, 'IMPERSONATE')) {
                                                    $badgeStyle = 'bg-amber-100 text-amber-800 border-amber-200';
                                                    $dotColor = 'bg-amber-600';
                                                }
                                            ?>
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold <?= $badgeStyle; ?> border">
                                                <span class="w-1.5 h-1.5 rounded-full <?= $dotColor; ?> shrink-0"></span>
                                                <span><?= htmlspecialchars($log['tindakan']); ?></span>
                                            </span>
                                        </td>
                                        <?php if (!empty($user['peran']) && $user['peran'] === 'admin_sekolah'): ?><td class="py-3.5 px-4 font-bold text-sage-700"><?= htmlspecialchars($log['nama_jurusan'] ?? '-'); ?></td><?php endif; ?>
                                        <td class="py-3.5 px-4 text-slate-600"><?= htmlspecialchars($log['deskripsi'] ?? '-'); ?></td>
                                        <td class="py-3.5 px-4 font-mono text-[11px] text-slate-500"><?= date('d M Y H:i', strtotime($log['created_at'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- SECTION 9: TAB PENGATURAN PROFIL -->
            <div id="tab-pengaturan-profil" class="tab-content hidden space-y-6">
                <div class="max-w-4xl mx-auto space-y-6">
                    <form action="update_profile.php" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl border border-sage-200/80 shadow-sm p-6 space-y-6">
                        <input type="hidden" name="csrf_token" value="<?= getCsrfToken(); ?>">
                        
                        <!-- Header Title Profil -->
                        <div class="border-b border-sage-100 pb-4 mb-2 flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-slate-800">Pengaturan Akun & Profil</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Perbarui informasi data diri dan kata sandi akun Anda</p>
                            </div>
                            <span class="text-xs font-bold text-slate-600 dark:text-slate-400 capitalize">
                                <?= htmlspecialchars($user['peran'] ?? 'admin'); ?>
                            </span>
                        </div>

                        <div>
                            <h4 class="text-sm font-bold text-slate-800 pb-3 mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4 text-sage-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Data Informasi Akun
                            </h4>

                            <!-- Foto Profil Avatar Row -->
                            <div class="flex items-center gap-4 p-4 bg-sage-50/50 rounded-2xl border border-sage-100 mb-5">
                                <div class="w-16 h-16 rounded-full bg-sage-600 text-white font-bold flex items-center justify-center text-xl shrink-0 shadow-md overflow-hidden border-2 border-white">
                                    <?php if (!empty($user['foto_url']) && file_exists(__DIR__ . '/../../../' . $user['foto_url'])): ?>
                                        <img id="profileAvatarPreview" src="<?= htmlspecialchars($user['foto_url']);  ?>" class="w-full h-full object-cover" alt="Avatar">
                                    <?php else: ?>
                                        <span id="profileAvatarInitial"><?= strtoupper(substr($user['nama_lengkap'] ?? $user['nama_pengguna'] ?? 'U', 0, 1)); ?></span>
                                        <img id="profileAvatarPreview" class="w-full h-full object-cover hidden" alt="Avatar" loading="lazy" decoding="async">
                                    <?php endif; ?>
                                </div>
                                <div class="flex-1">
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Unggah Foto Profil <span class="text-slate-400 font-normal">(Opsional)</span></label>
                                    <div class="flex items-center gap-3 flex-wrap">
                                        <input type="file" name="foto" accept="image/png, image/jpeg, image/jpg, image/webp" onchange="previewProfilePhoto(event)" class="text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-sage-600 file:text-white hover:file:bg-sage-700 transition-all cursor-pointer">
                                        <?php if (!empty($user['foto_url'])): ?>
                                            <button type="button" onclick="hapusFotoProfil()" class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl transition-all flex items-center gap-1.5 shadow-sm" title="Hapus Foto Profil">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                <span>Hapus Foto</span>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-1">*Format: JPG, PNG, WEBP (Maksimal 2MB)</p>
                                </div>
                            </div>

                            <?php 
                            $profEmailParts = explode('@', $user['email'] ?? '', 2);
                            $profEmailPrefix = $profEmailParts[0] ?? '';
                            $profEmailDomain = !empty($profEmailParts[1]) ? $profEmailParts[1] : 'smk2pangkalpinang.sch.id';
                            ?>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Nama Pengguna (Username)</label>
                                    <input type="text" value="<?= htmlspecialchars($user['nama_pengguna'] ?? 'admin'); ?>" disabled class="w-full px-3.5 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-xs font-semibold text-slate-500 cursor-not-allowed">
                                    <p class="text-[10px] text-slate-400 mt-1">*Nama pengguna tidak dapat diubah</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                                    <input type="text" name="nama_lengkap" required value="<?= htmlspecialchars($user['nama_lengkap'] ?? ''); ?>" class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:border-sage-600 focus:bg-white transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Email</label>
                                    <div class="flex items-center gap-2">
                                        <input type="text" name="email_prefix" value="<?= htmlspecialchars($profEmailPrefix); ?>" placeholder="nama_email" class="w-1/2 px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:border-sage-600 focus:bg-white transition-all">
                                        <span class="text-slate-400 font-bold text-sm">@</span>
                                        <input type="text" name="email_domain" value="<?= htmlspecialchars($profEmailDomain); ?>" placeholder="smk2pangkalpinang.sch.id" class="w-1/2 px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:border-sage-600 focus:bg-white transition-all">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Nomor Telepon / WhatsApp</label>
                                    <input type="text" name="nomor_telepon" value="<?= htmlspecialchars($user['nomor_telepon'] ?? ''); ?>" placeholder="081234567890" class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:border-sage-600 focus:bg-white transition-all">
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-sage-100">
                            <h4 class="text-sm font-bold text-slate-800 pb-3 mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4 text-sage-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                Ubah Kata Sandi <span class="text-xs font-normal text-slate-400">(Biarkan kosong jika tidak ingin diubah)</span>
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Kata Sandi Lama</label>
                                    <input type="password" name="password_lama" placeholder="••••••••" class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl text-xs focus:outline-none focus:border-sage-600 focus:bg-white transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Kata Sandi Baru</label>
                                    <input type="password" name="password_baru" placeholder="••••••••" class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl text-xs focus:outline-none focus:border-sage-600 focus:bg-white transition-all">
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 flex justify-end">
                            <button type="submit" class="px-6 py-2.5 bg-sage-600 hover:bg-sage-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-sage-600/25 transition-all flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Simpan Perubahan Profil
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        <!-- 3.12. TAB SWAGGER API DOCS (KHUSUS ADMIN SEKOLAH) -->
        <?php if ($isSuperAdmin): ?>
        <div id="tab-swagger" class="tab-content hidden space-y-6">
            <!-- Header Section -->
            <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl text-slate-800 dark:text-white shadow-sm border border-slate-200 dark:border-slate-800 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div class="space-y-1">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-100 dark:bg-emerald-500/20 border border-emerald-300 dark:border-emerald-500/30 text-emerald-800 dark:text-emerald-400 rounded-full text-xs font-mono font-bold">
                        <span>OPENAPI 3.0.3 SPECIFICATION</span>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-800 dark:text-white flex items-center gap-2">
                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                        Swagger REST API Route Explorer
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Dokumentasi & Pengujian Endpoint RESTful API (Tampilan Official Default Swagger UI)</p>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <a href="swagger_ui.php" target="_blank" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition-colors shadow-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        <span>Buka Fullscreen Swagger UI</span>
                    </a>
                </div>
            </div>

            <!-- Swagger UI Isolated Iframe Container -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl overflow-hidden shadow-sm">
                <iframe src="swagger_ui.php" class="w-full h-[85vh] border-0" style="min-height: 750px;"></iframe>
            </div>
        </div>
        <?php endif; ?>

        </main>

        </main>

    </div>

</div>

<!-- Include Modal Dialogs -->
<?php require_once __DIR__ . '/../layouts/modals.php'; ?>

<!-- Interactive JavaScript Tab Switcher, Chart.js Column Chart & Pie Chart -->
<script>
window.currentUser = <?= json_encode($user); ?>;
window.dbJurusan = <?= json_encode($dbJurusan ?: []); ?>;
window.dbPengguna = <?= json_encode($dbPengguna ?: []); ?>;
window.dbKategori = <?= json_encode($dbKategori ?: []); ?>;
window.dbRak = <?= json_encode($dbRak ?: []); ?>;
window.dbBarang = <?= json_encode($dbBarang ?: []); ?>;
window.dbBarangMasuk = <?= json_encode($dbBarangMasuk ?: []); ?>;
window.dbBarangKeluar = <?= json_encode($dbBarangKeluar ?: []); ?>;
window.dbPeminjaman = <?= json_encode($dbPeminjaman ?: []); ?>;
window.dbLogAktivitas = <?= json_encode($dbLogAktivitas ?: []); ?>;
window.nextCodes = {
    barcode: <?= json_encode(Barang::generateNextBarcode()); ?>,
    rakBarcode: <?= json_encode(Rak::generateNextBarcode()); ?>
};

function setUrlParam(key, val) {
    const url = new URL(window.location.href);
    if (val) {
        url.searchParams.set(key, val);
    } else {
        url.searchParams.delete(key);
    }
    history.pushState(null, '', url.pathname + (url.searchParams.toString() ? '?' + url.searchParams.toString() : ''));
}

function exportTableToCSV(tableId, filename) {
    let dataset = null;
    let tableSchema = null;

    if (tableId === 'tablePengguna') {
        dataset = window.dbPengguna;
        tableSchema = [
            'id', 'jurusan_id', 'nama_pengguna', 'nama_lengkap', 'email', 'peran', 
            'nomor_telepon', 'foto_url', 'nama_jurusan', 'created_at', 'updated_at'
        ];
    } else if (tableId === 'tableJurusan') {
        dataset = window.dbJurusan;
        tableSchema = [
            'id', 'kode_jurusan', 'nama_jurusan', 'keterangan', 'deskripsi', 
            'warna_tema', 'theme_color', 'created_at', 'updated_at'
        ];
    } else if (tableId === 'tableKategori') {
        dataset = window.dbKategori;
        tableSchema = [
            'id', 'jurusan_id', 'kode_kategori', 'nama_kategori', 'deskripsi', 
            'nama_jurusan', 'created_at', 'updated_at'
        ];
    } else if (tableId === 'tableRak') {
        dataset = window.dbRak;
        tableSchema = [
            'id', 'jurusan_id', 'kode_rak', 'barcode_rak', 'nama_rak', 'kategori_rak', 
            'keterangan', 'nama_jurusan', 'total_barang', 'total_stok_tersedia', 
            'created_at', 'updated_at'
        ];
    } else if (tableId === 'tableBarang') {
        dataset = window.dbBarang;
        tableSchema = [
            'id', 'kategori_id', 'rak_id', 'jurusan_id', 'kode_barang', 'nama_barang', 
            'merek', 'barcode', 'stok_total', 'stok_tersedia', 'stok_minimum', 'jumlah', 
            'deskripsi', 'nama_kategori', 'nama_rak', 'nama_jurusan', 
            'barcode_path', 'created_at', 'updated_at'
        ];
    } else if (tableId === 'tableBarangMasuk') {
        dataset = window.dbBarangMasuk;
        tableSchema = [
            'id', 'barang_id', 'pengguna_id', 'jurusan_id', 'kode_barang', 'nama_barang', 
            'nama_pemasok', 'jumlah', 'tanggal_masuk', 'catatan', 'nama_petugas', 
            'nama_jurusan', 'created_at', 'updated_at'
        ];
    } else if (tableId === 'tableBarangKeluar') {
        dataset = window.dbBarangKeluar;
        tableSchema = [
            'id', 'barang_id', 'pengguna_id', 'jurusan_id', 'kode_barang', 'nama_barang', 
            'nama_penerima', 'jumlah', 'tanggal_keluar', 'catatan', 'nama_petugas', 
            'nama_jurusan', 'created_at', 'updated_at'
        ];
    } else if (tableId === 'tablePeminjaman' || tableId === 'tableLogPeminjaman') {
        dataset = window.dbPeminjaman;
        tableSchema = [
            'id', 'kode_peminjaman', 'barang_id', 'pengguna_id', 'jurusan_id', 
            'nama_barang', 'nama_peminjam', 'jumlah', 'tanggal_pinjam', 'tenggat_kembali', 
            'tanggal_kembali', 'status', 'kondisi_sebelum', 'kondisi_sesudah', 'catatan', 
            'bukti_foto_url', 'nama_petugas', 'nama_jurusan', 'created_at', 'updated_at'
        ];
    } else if (tableId === 'tableLogAktivitas') {
        dataset = window.dbLogAktivitas;
        tableSchema = [
            'id', 'pengguna_id', 'jurusan_id', 'nama_pengguna', 'nama_lengkap', 
            'tindakan', 'deskripsi', 'ip_address', 'user_agent', 'nama_jurusan', 
            'created_at', 'updated_at'
        ];
    }

    // Jika dataset JavaScript tersedia, export SELURUH FIELD SESUAI SKEMA DATABASE & JOIN
    if (Array.isArray(dataset) && dataset.length > 0) {
        let items = dataset;

        // Ambil baris terfilter jika pengguna sedang memfilter/mencari data
        const paginator = window.tablePaginators && window.tablePaginators[tableId];
        if (paginator && Array.isArray(paginator.filteredRows) && paginator.filteredRows.length > 0) {
            const allowedIds = new Set();
            paginator.filteredRows.forEach(tr => {
                const id = tr.getAttribute('data-id');
                if (id) allowedIds.add(String(id));
            });
            if (allowedIds.size > 0) {
                items = dataset.filter(item => allowedIds.has(String(item.id)));
            }
        }

        if (!items || items.length === 0) items = dataset;

        // Gabungkan schema eksplisit + sisa properti tambahan yang ada di dataset
        const finalKeysSet = new Set();
        if (tableSchema) {
            tableSchema.forEach(k => finalKeysSet.add(k));
        }

        items.forEach(item => {
            Object.keys(item).forEach(k => {
                if (k !== 'kata_sandi_hash') {
                    finalKeysSet.add(k);
                }
            });
        });

        const headers = Array.from(finalKeysSet);
        let csv = [];

        // Header CSV (Field persis seperti di database)
        csv.push(headers.map(h => '"' + String(h).toUpperCase().replace(/"/g, '""') + '"').join(","));

        // Data Rows
        items.forEach(item => {
            let row = headers.map(key => {
                let val = item[key];
                if (val === null || val === undefined || val === '') {
                    if (key === 'created_at') {
                        val = item['tanggal_masuk'] || item['tanggal_keluar'] || item['tanggal_pinjam'] || item['updated_at'] || '';
                    } else if (key === 'updated_at') {
                        val = item['created_at'] || item['tanggal_masuk'] || item['tanggal_keluar'] || item['tanggal_pinjam'] || '';
                    } else if (key === 'jumlah' && tableId === 'tableBarang') {
                        val = item['stok_total'] || item['stok_tersedia'] || '0';
                    } else {
                        val = '';
                    }
                } else if (typeof val === 'object') {
                    val = JSON.stringify(val);
                }
                return '"' + String(val).replace(/"/g, '""').trim() + '"';
            });
            csv.push(row.join(","));
        });

        const csvFile = new Blob(["\uFEFF" + csv.join("\n")], { type: "text/csv;charset=utf-8;" });
        const downloadLink = document.createElement("a");
        downloadLink.download = filename || (tableId + '.csv');
        downloadLink.href = window.URL.createObjectURL(csvFile);
        downloadLink.style.display = "none";
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
        return;
    }

    // Fallback: Export elemen tabel HTML DOM
    const table = document.getElementById(tableId);
    if (!table) return;

    let csv = [];
    const rows = table.querySelectorAll("tr");
    
    for (let i = 0; i < rows.length; i++) {
        let row = [], cols = rows[i].querySelectorAll("td, th");
        for (let j = 0; j < cols.length; j++) {
            if (cols[j].innerText.trim() === 'Aksi' || cols[j].querySelector('button')) {
                continue;
            }
            let text = cols[j].innerText.replace(/"/g, '""').trim();
            row.push('"' + text + '"');
        }
        if (row.length > 0) {
            csv.push(row.join(","));
        }
    }

    const csvFile = new Blob(["\uFEFF" + csv.join("\n")], { type: "text/csv;charset=utf-8;" });
    const downloadLink = document.createElement("a");
    downloadLink.download = filename || 'export.csv';
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = "none";
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}

async function handleDirectCsvImport(event) {
    const file = event.target.files[0];
    if (!file) return;

    if (!file.name.toLowerCase().endsWith('.csv')) {
        showToast('File harus berformat .csv!', 'error');
        event.target.value = '';
        return;
    }

    showToast('Mengimpor data CSV...', 'success');

    const formData = new FormData();
    formData.append('action', 'import_barang_csv');
    formData.append('csv_file', file);

    const csrfInput = document.querySelector('input[name="csrf_token"]');
    if (csrfInput) formData.append('csrf_token', csrfInput.value);

    try {
        const res = await fetch('api.php', { method: 'POST', body: formData });
        const data = await res.json();
        if (data.success) {
            showToast(data.message || 'Berhasil mengimpor data barang!', 'success');
            setTimeout(() => location.reload(), 800);
        } else {
            showToast(data.message || 'Gagal mengimpor file CSV.', 'error');
        }
    } catch (err) {
        console.error(err);
        showToast('Terjadi kesalahan saat mengimpor CSV.', 'error');
    } finally {
        event.target.value = '';
    }
}

function editJurusan(id) {
    const data = (window.dbJurusan || []).find(item => String(item.id) === String(id));
    if (data) {
        setUrlParam('tab', 'jurusan');
        setUrlParam('id', id);
        openModal('modalJurusan', 'Edit Data Jurusan', data);
    }
}

function editPengguna(id) {
    const data = (window.dbPengguna || []).find(item => String(item.id) === String(id));
    if (data) {
        setUrlParam('tab', 'pengguna');
        setUrlParam('id', id);
        openModal('modalPengguna', 'Edit Data Pengguna', data);
    }
}

function editKategori(id) {
    const data = (window.dbKategori || []).find(item => String(item.id) === String(id));
    if (data) {
        setUrlParam('tab', 'kategori');
        setUrlParam('id', id);
        openModal('modalKategori', 'Edit Kategori Barang', data);
    }
}

function editBarang(id) {
    const data = (window.dbBarang || []).find(item => String(item.id) === String(id));
    if (data) {
        setUrlParam('tab', 'barang');
        setUrlParam('id', id);
        openModal('modalBarang', 'Edit Data Barang', data);
    }
}

function executeDelete(action, id, entityName) {
    showDeleteConfirm(entityName, async () => {
        const formData = new FormData();
        formData.append('action', action);
        formData.append('id', id);
        const csrfInput = document.querySelector('input[name="csrf_token"]');
        if (csrfInput) formData.append('csrf_token', csrfInput.value);

        try {
            const res = await fetch('api.php', { method: 'POST', body: formData });
            const data = await res.json();
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 800);
            } else {
                showToast(data.message || 'Gagal menghapus data.', 'error');
            }
        } catch (e) {
            console.error(e);
            showToast('Terjadi kesalahan koneksi server.', 'error');
        }
    });
}

function deleteJurusan(id, name) {
    executeDelete('delete_jurusan', id, 'jurusan "' + name + '"');
}

function deletePengguna(id, name) {
    executeDelete('delete_pengguna', id, 'pengguna "' + name + '"');
}

function deleteKategori(id, name) {
    executeDelete('delete_kategori', id, 'kategori "' + name + '"');
}

function deleteBarang(id, name) {
    executeDelete('delete_barang', id, 'barang "' + name + '"');
}

function deleteBarangMasuk(id, name) {
    executeDelete('delete_barang_masuk', id, 'catatan barang masuk "' + name + '"');
}

function deleteBarangKeluar(id, name) {
    executeDelete('delete_barang_keluar', id, 'catatan barang keluar "' + name + '"');
}

function deletePeminjaman(id, name) {
    executeDelete('delete_peminjaman', id, 'transaksi peminjaman "' + name + '"');
}

function kembalikanPeminjaman(id) {
    const item = (window.dbPeminjaman || []).find(x => String(x.id) === String(id));
    const idInput = document.getElementById('confirm_kembali_id');
    const fileInput = document.getElementById('confirm_kembali_bukti_foto');
    const msgElem = document.getElementById('confirmKembaliMessage');

    if (idInput) idInput.value = id;
    if (fileInput) fileInput.value = '';

    if (item && msgElem) {
        msgElem.innerText = `Apakah Anda yakin barang/alat "${item.nama_barang || 'ini'}" (${item.jumlah || 1} ${item.satuan || 'Unit'}) telah dikembalikan oleh ${item.nama_peminjam || 'peminjam'}?`;
    }

    openModal('modalConfirmPengembalian');
}

function previewProfilePhoto(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('profileAvatarPreview');
            const initial = document.getElementById('profileAvatarInitial');
            if (preview) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            }
            if (initial) {
                initial.classList.add('hidden');
            }
        };
        reader.readAsDataURL(file);
    }
}

function hapusFotoProfil() {
    showActionConfirm('Hapus Foto Profil', 'Apakah Anda yakin ingin menghapus foto profil Anda?', 'Ya, Hapus Foto', 'px-4 py-2 bg-red-600 text-white font-bold text-xs rounded-xl hover:bg-red-700 transition-colors w-1/2', async () => {
        const formData = new FormData();
        formData.append('action', 'delete_profile_photo');
        const csrfInput = document.querySelector('input[name="csrf_token"]');
        if (csrfInput) formData.append('csrf_token', csrfInput.value);

        try {
            const res = await fetch('api.php', { method: 'POST', body: formData });
            const data = await res.json();
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 800);
            } else {
                showToast(data.message || 'Gagal menghapus foto profil.', 'error');
            }
        } catch (e) {
            console.error(e);
            showToast('Terjadi kesalahan koneksi server.', 'error');
        }
    });
}

function getJurusanColor(label) {
    if (!label) return '#2e7d32';
    if (label === 'Belum Ada Data' || label === 'Tanpa Stok' || label === 'Belum Ada Activity') {
        return '#94a3b8';
    }
    
    const normLabel = label.trim().toLowerCase();
    
    if (window.dbJurusan && Array.isArray(window.dbJurusan)) {
        const found = window.dbJurusan.find(j => {
            const full = (j.nama_jurusan || '').trim().toLowerCase();
            const match = full.match(/\(([^)]+)\)/);
            const short = match ? match[1].trim().toLowerCase() : full;
            const code = (j.kode_jurusan || '').trim().toLowerCase();
            
            return normLabel === full || 
                   normLabel === short || 
                   normLabel === code ||
                   full.includes(normLabel) || 
                   normLabel.includes(short);
        });

        if (found && found.warna_tema) {
            let color = found.warna_tema.trim();
            const presets = {
                'kuning': '#EAB308',
                'orange': '#EA580C',
                'hijau': '#2E7D32',
                'merah': '#DC2626',
                'biru': '#2563EB',
                'ungu': '#7C3AED',
                'pink': '#E11D48',
                'cyan': '#0891B2'
            };
            if (presets[color.toLowerCase()]) {
                return presets[color.toLowerCase()];
            }
            if (!color.startsWith('#')) {
                color = '#' + color;
            }
            return color;
        }
    }

    const fallbackPalette = ['#2e7d32', '#eab308', '#dc2626', '#ea580c', '#0284c7', '#7c3aed', '#db2777', '#059669'];
    let hash = 0;
    for (let i = 0; i < label.length; i++) hash = label.charCodeAt(i) + ((hash << 5) - hash);
    return fallbackPalette[Math.abs(hash) % fallbackPalette.length];
}

function getColorsForLabels(labels) {
    return (labels || []).map(lbl => getJurusanColor(lbl));
}

let inventoryChart = null;
let categoryChart = null;

function initInventoryChart() {
    const isDark = document.documentElement.classList.contains('dark');
    const labelColor = isDark ? '#94a3b8' : '#64748b';
    const gridColor = isDark ? '#1e293b' : '#f1f5f9';

    const isSuperAdmin = window.currentUser && window.currentUser.peran === 'admin_sekolah';

    // 1. Column Chart (Left Side)
    const ctxCol = document.getElementById('inventoryColumnChart');
    if (ctxCol) {
        if (inventoryChart) inventoryChart.destroy();

        if (isSuperAdmin && window.dbJurusan && window.dbJurusan.length > 0) {
            // Per Jurusan Mode for Admin Sekolah
            const jurLabels = window.dbJurusan.map(j => {
                let name = j.nama_jurusan || j.kode_jurusan || 'Jurusan';
                const match = name.match(/\(([^)]+)\)/);
                return match ? match[1] : name;
            });

            const masukPerJur = window.dbJurusan.map(j => {
                return (window.dbBarangMasuk || [])
                    .filter(bm => String(bm.jurusan_id) === String(j.id) || bm.nama_jurusan === j.nama_jurusan)
                    .reduce((acc, bm) => acc + parseInt(bm.jumlah || 1), 0);
            });

            const keluarPerJur = window.dbJurusan.map(j => {
                return (window.dbBarangKeluar || [])
                    .filter(bk => String(bk.jurusan_id) === String(j.id) || bk.nama_jurusan === j.nama_jurusan)
                    .reduce((acc, bk) => acc + parseInt(bk.jumlah || 1), 0);
            });

            const pinjamPerJur = window.dbJurusan.map(j => {
                return (window.dbPeminjaman || [])
                    .filter(pm => String(pm.jurusan_id) === String(j.id) || pm.nama_jurusan === j.nama_jurusan)
                    .reduce((acc, pm) => acc + parseInt(pm.jumlah || 1), 0);
            });

            inventoryChart = new Chart(ctxCol, {
                type: 'bar',
                data: {
                    labels: jurLabels,
                    datasets: [
                        {
                            label: 'Barang Masuk',
                            data: masukPerJur,
                            backgroundColor: 'rgba(46, 125, 50, 0.85)',
                            hoverBackgroundColor: '#2e7d32',
                            borderRadius: 6,
                        },
                        {
                            label: 'Barang Keluar',
                            data: keluarPerJur,
                            backgroundColor: 'rgba(245, 158, 11, 0.85)',
                            hoverBackgroundColor: '#f59e0b',
                            borderRadius: 6,
                        },
                        {
                            label: 'Peminjaman',
                            data: pinjamPerJur,
                            backgroundColor: 'rgba(14, 165, 233, 0.85)',
                            hoverBackgroundColor: '#0ea5e9',
                            borderRadius: 6,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: { duration: 1000, easing: 'easeOutQuart' },
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { size: 11, weight: '600' }, color: labelColor } },
                        y: { grid: { color: gridColor }, ticks: { font: { size: 11 }, color: labelColor, precision: 0 } }
                    }
                }
            });
        } else {
            // Monthly Mode for Single Jurusan
            const masukMonthly = new Array(12).fill(0);
            const keluarMonthly = new Array(12).fill(0);
            const pinjamMonthly = new Array(12).fill(0);

            const parseMonth = (dateStr) => {
                if (!dateStr) return -1;
                const d = new Date(dateStr);
                return isNaN(d.getTime()) ? -1 : d.getMonth();
            };

            (window.dbBarangMasuk || []).forEach(bm => {
                const m = parseMonth(bm.created_at || bm.tanggal_masuk);
                if (m >= 0 && m < 12) masukMonthly[m] += parseInt(bm.jumlah || 1);
            });

            (window.dbBarangKeluar || []).forEach(bk => {
                const m = parseMonth(bk.created_at || bk.tanggal_keluar);
                if (m >= 0 && m < 12) keluarMonthly[m] += parseInt(bk.jumlah || 1);
            });

            (window.dbPeminjaman || []).forEach(pm => {
                const m = parseMonth(pm.created_at || pm.tanggal_pinjam);
                if (m >= 0 && m < 12) pinjamMonthly[m] += parseInt(pm.jumlah || 1);
            });

            inventoryChart = new Chart(ctxCol, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [
                        { label: 'Barang Masuk', data: masukMonthly, backgroundColor: 'rgba(46, 125, 50, 0.85)', borderRadius: 6 },
                        { label: 'Barang Keluar', data: keluarMonthly, backgroundColor: 'rgba(245, 158, 11, 0.85)', borderRadius: 6 },
                        { label: 'Peminjaman', data: pinjamMonthly, backgroundColor: 'rgba(14, 165, 233, 0.85)', borderRadius: 6 }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: { duration: 1000, easing: 'easeOutQuart' },
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { size: 11, weight: '600' }, color: labelColor } },
                        y: { grid: { color: gridColor }, ticks: { font: { size: 11 }, color: labelColor, precision: 0 } }
                    }
                }
            });
        }
    }

    // 2. Pie Chart (Right Side)
    const ctxPie = document.getElementById('categoryPieChart');
    if (ctxPie) {
        if (categoryChart) categoryChart.destroy();
        const catColors = ['#2e7d32', '#f59e0b', '#0ea5e9', '#6366f1', '#ec4899', '#8b5cf6', '#14b8a6', '#f97316'];

        let pieLabels = [];
        let pieData = [];

        if (isSuperAdmin && window.dbJurusan && window.dbJurusan.length > 0) {
            // Per Jurusan Stock Pie Chart for Admin Sekolah
            window.dbJurusan.forEach(j => {
                let name = j.nama_jurusan || j.kode_jurusan || 'Jurusan';
                const match = name.match(/\(([^)]+)\)/);
                pieLabels.push(match ? match[1] : name);

                const totalStokJur = (window.dbBarang || [])
                    .filter(b => String(b.jurusan_id) === String(j.id) || b.nama_jurusan === j.nama_jurusan)
                    .reduce((acc, b) => acc + parseInt(b.stok_total || 0), 0);
                pieData.push(totalStokJur);
            });
        } else {
            // Per Category Stock Pie Chart for Single Jurusan
            if (window.dbKategori && window.dbKategori.length > 0) {
                window.dbKategori.forEach(c => {
                    pieLabels.push(c.nama_kategori);
                    const totalStokCat = (window.dbBarang || [])
                        .filter(b => String(b.kategori_id) === String(c.id))
                        .reduce((acc, b) => acc + parseInt(b.stok_total || 0), 0);
                    pieData.push(totalStokCat);
                });
            }
        }

        if (pieLabels.length === 0 || pieData.every(v => v === 0)) {
            pieLabels.push('Tanpa Stok');
            pieData.push(1);
        }

        const pieBgColors = (isSuperAdmin && window.dbJurusan && window.dbJurusan.length > 0) ? getColorsForLabels(pieLabels) : catColors.slice(0, pieLabels.length);

        categoryChart = new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: pieLabels,
                datasets: [{
                    data: pieData,
                    backgroundColor: pieBgColors,
                    hoverBackgroundColor: pieBgColors,
                    borderWidth: 2,
                    borderColor: isDark ? '#0f172a' : '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: { duration: 1000, easing: 'easeOutQuart' },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { font: { size: 10, weight: '600' }, boxWidth: 12, padding: 12, color: labelColor }
                    }
                }
            }
        });
    }
}

let tabAnalyticsCharts = {};

function initTabAnalytics(tabId) {
    const isSuperAdmin = window.currentUser && window.currentUser.peran === 'admin_sekolah';

    const destroyChart = (key) => {
        if (tabAnalyticsCharts[key]) {
            tabAnalyticsCharts[key].destroy();
            tabAnalyticsCharts[key] = null;
        }
    };

    const isDark = document.documentElement.classList.contains('dark');
    const labelColor = isDark ? '#94a3b8' : '#64748b';
    const gridColor = isDark ? '#1e293b' : '#f1f5f9';

    const countByJurusan = (items, keyField = 'nama_jurusan') => {
        const counts = {};
        (items || []).forEach(item => {
            const jName = item[keyField] || 'Sekolah';
            const match = jName.match(/\(([^)]+)\)/);
            const shortName = match ? match[1] : jName;
            counts[shortName] = (counts[shortName] || 0) + 1;
        });
        return counts;
    };

    const groupByJurusan = (items, keyField = 'nama_jurusan') => {
        const counts = {};
        (items || []).forEach(item => {
            const jName = item[keyField] || 'Sekolah';
            const match = jName.match(/\(([^)]+)\)/);
            const shortName = match ? match[1] : jName;
            counts[shortName] = (counts[shortName] || 0) + (parseInt(item.jumlah || 1));
        });
        return counts;
    };

    // 1. PENGGUNA TAB ANALYTICS
    if (tabId === 'pengguna') {
        const roleLabels = isSuperAdmin 
            ? ['Admin Sekolah', 'Admin Jurusan', 'Petugas Gudang', 'Siswa'] 
            : ['Admin Jurusan', 'Petugas Gudang', 'Siswa'];

        const roleData = isSuperAdmin
            ? [
                (window.dbPengguna || []).filter(u => u.peran === 'admin_sekolah').length,
                (window.dbPengguna || []).filter(u => u.peran === 'admin_jurusan').length,
                (window.dbPengguna || []).filter(u => u.peran === 'petugas').length,
                (window.dbPengguna || []).filter(u => u.peran === 'siswa').length
              ]
            : [
                (window.dbPengguna || []).filter(u => u.peran === 'admin_jurusan').length,
                (window.dbPengguna || []).filter(u => u.peran === 'petugas').length,
                (window.dbPengguna || []).filter(u => u.peran === 'siswa').length
              ];

        const roleColors = isSuperAdmin
            ? ['#2563eb', '#eab308', '#059669', '#7c3aed']
            : ['#eab308', '#059669', '#7c3aed'];

        const ctxRole = document.getElementById('userRoleChart');
        if (ctxRole) {
            destroyChart('userRole');
            tabAnalyticsCharts['userRole'] = new Chart(ctxRole, {
                type: 'bar',
                data: {
                    labels: roleLabels,
                    datasets: [{
                        label: 'Jumlah Pengguna',
                        data: roleData,
                        backgroundColor: roleColors,
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { x: { grid: { display: false }, ticks: { color: labelColor } }, y: { grid: { color: gridColor }, ticks: { color: labelColor, precision: 0 } } }
                }
            });
        }

        const ctxUserJur = document.getElementById('userJurusanChart');
        if (ctxUserJur) {
            destroyChart('userJurusan');
            let labels, data, bgColors;

            if (isSuperAdmin) {
                const jCounts = countByJurusan(window.dbPengguna);
                labels = Object.keys(jCounts);
                data = Object.values(jCounts);
                bgColors = getColorsForLabels(labels);
            } else {
                labels = ['Admin Jurusan', 'Petugas Gudang', 'Siswa'];
                data = [
                    (window.dbPengguna || []).filter(u => u.peran === 'admin_jurusan').length,
                    (window.dbPengguna || []).filter(u => u.peran === 'petugas').length,
                    (window.dbPengguna || []).filter(u => u.peran === 'siswa').length
                ];
                bgColors = ['#d97706', '#059669', '#7c3aed'];
            }

            tabAnalyticsCharts['userJurusan'] = new Chart(ctxUserJur, {
                type: 'pie',
                data: { labels: labels, datasets: [{ data: data, backgroundColor: bgColors }] },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, color: labelColor } } } }
            });
        }
    }

    // 7. LOG AKTIVITAS TAB ANALYTICS
    if (tabId === 'log-aktivitas') {
        const ctxCol = document.getElementById('logColumnChart');
        const ctxPie = document.getElementById('logPieChart');
        const jCounts = countByJurusan(window.dbLogAktivitas);
        const labels = Object.keys(jCounts).length > 0 ? Object.keys(jCounts) : ['Belum Ada Activity'];
        const data = Object.values(jCounts).length > 0 ? Object.values(jCounts) : [0];
        const bgColors = getColorsForLabels(labels);

        if (ctxCol) {
            destroyChart('logCol');
            tabAnalyticsCharts['logCol'] = new Chart(ctxCol, {
                type: 'bar',
                data: { labels: labels, datasets: [{ label: 'Catatan Log', data: data, backgroundColor: bgColors, borderRadius: 6 }] },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { ticks: { color: labelColor } }, y: { ticks: { color: labelColor, precision: 0 } } } }
            });
        }
        if (ctxPie) {
            destroyChart('logPie');
            tabAnalyticsCharts['logPie'] = new Chart(ctxPie, {
                type: 'pie',
                data: { labels: labels, datasets: [{ data: data, backgroundColor: bgColors }] },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { color: labelColor } } } }
            });
        }
    }

    // CHARTS BELOW ONLY FOR ADMIN SEKOLAH
    if (!isSuperAdmin) return;

    // 2. KATEGORI TAB ANALYTICS
    if (tabId === 'kategori') {
        const ctxCol = document.getElementById('kategoriColumnChart');
        const ctxPie = document.getElementById('kategoriPieChart');
        const jCounts = countByJurusan(window.dbKategori);
        const labels = Object.keys(jCounts).length > 0 ? Object.keys(jCounts) : ['Belum Ada Data'];
        const data = Object.values(jCounts).length > 0 ? Object.values(jCounts) : [0];
        const bgColors = getColorsForLabels(labels);

        if (ctxCol) {
            destroyChart('katCol');
            tabAnalyticsCharts['katCol'] = new Chart(ctxCol, {
                type: 'bar',
                data: { labels: labels, datasets: [{ label: 'Kategori', data: data, backgroundColor: bgColors, borderRadius: 6 }] },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { ticks: { color: labelColor } }, y: { ticks: { color: labelColor, precision: 0 } } } }
            });
        }
        if (ctxPie) {
            destroyChart('katPie');
            tabAnalyticsCharts['katPie'] = new Chart(ctxPie, {
                type: 'pie',
                data: { labels: labels, datasets: [{ data: data, backgroundColor: bgColors }] },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { color: labelColor } } } }
            });
        }
    }

    // 3. BARANG TAB ANALYTICS
    if (tabId === 'barang') {
        const ctxCol = document.getElementById('barangColumnChart');
        const ctxPie = document.getElementById('barangPieChart');
        const jCounts = {};
        (window.dbBarang || []).forEach(b => {
            const rawName = b.nama_jurusan || 'Semua Jurusan';
            const match = rawName.match(/\(([^)]+)\)/);
            const jName = match ? match[1] : rawName;
            jCounts[jName] = (jCounts[jName] || 0) + (parseInt(b.stok_total || 0));
        });
        const labels = Object.keys(jCounts).length > 0 ? Object.keys(jCounts) : ['Belum Ada Data'];
        const data = Object.values(jCounts).length > 0 ? Object.values(jCounts) : [0];
        const bgColors = getColorsForLabels(labels);

        if (ctxCol) {
            destroyChart('brgCol');
            tabAnalyticsCharts['brgCol'] = new Chart(ctxCol, {
                type: 'bar',
                data: { labels: labels, datasets: [{ label: 'Total Stok', data: data, backgroundColor: bgColors, borderRadius: 6 }] },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { ticks: { color: labelColor } }, y: { ticks: { color: labelColor, precision: 0 } } } }
            });
        }
        if (ctxPie) {
            destroyChart('brgPie');
            tabAnalyticsCharts['brgPie'] = new Chart(ctxPie, {
                type: 'pie',
                data: { labels: labels, datasets: [{ data: data, backgroundColor: bgColors }] },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { color: labelColor } } } }
            });
        }
    }

    // 4. BARANG MASUK TAB ANALYTICS
    if (tabId === 'barang-masuk') {
        const ctxCol = document.getElementById('masukColumnChart');
        const ctxPie = document.getElementById('masukPieChart');
        const jCounts = groupByJurusan(window.dbBarangMasuk);
        const labels = Object.keys(jCounts).length > 0 ? Object.keys(jCounts) : ['Belum Ada Data'];
        const data = Object.values(jCounts).length > 0 ? Object.values(jCounts) : [0];
        const bgColors = getColorsForLabels(labels);

        if (ctxCol) {
            destroyChart('masukCol');
            tabAnalyticsCharts['masukCol'] = new Chart(ctxCol, {
                type: 'bar',
                data: { labels: labels, datasets: [{ label: 'Unit Masuk', data: data, backgroundColor: bgColors, borderRadius: 6 }] },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { ticks: { color: labelColor } }, y: { ticks: { color: labelColor, precision: 0 } } } }
            });
        }
        if (ctxPie) {
            destroyChart('masukPie');
            tabAnalyticsCharts['masukPie'] = new Chart(ctxPie, {
                type: 'pie',
                data: { labels: labels, datasets: [{ data: data, backgroundColor: bgColors }] },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { color: labelColor } } } }
            });
        }
    }

    // 5. BARANG KELUAR TAB ANALYTICS
    if (tabId === 'barang-keluar') {
        const ctxCol = document.getElementById('keluarColumnChart');
        const ctxPie = document.getElementById('keluarPieChart');
        const jCounts = groupByJurusan(window.dbBarangKeluar);
        const labels = Object.keys(jCounts).length > 0 ? Object.keys(jCounts) : ['Belum Ada Data'];
        const data = Object.values(jCounts).length > 0 ? Object.values(jCounts) : [0];
        const bgColors = getColorsForLabels(labels);

        if (ctxCol) {
            destroyChart('keluarCol');
            tabAnalyticsCharts['keluarCol'] = new Chart(ctxCol, {
                type: 'bar',
                data: { labels: labels, datasets: [{ label: 'Unit Keluar', data: data, backgroundColor: bgColors, borderRadius: 6 }] },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { ticks: { color: labelColor } }, y: { ticks: { color: labelColor, precision: 0 } } } }
            });
        }
        if (ctxPie) {
            destroyChart('keluarPie');
            tabAnalyticsCharts['keluarPie'] = new Chart(ctxPie, {
                type: 'pie',
                data: { labels: labels, datasets: [{ data: data, backgroundColor: bgColors }] },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { color: labelColor } } } }
            });
        }
    }

    // 6. PEMINJAMAN ALAT TAB ANALYTICS
    if (tabId === 'peminjaman') {
        const ctxCol = document.getElementById('pinjamColumnChart');
        const ctxPie = document.getElementById('pinjamPieChart');
        const jCounts = groupByJurusan(window.dbPeminjaman);
        const labels = Object.keys(jCounts).length > 0 ? Object.keys(jCounts) : ['Belum Ada Data'];
        const data = Object.values(jCounts).length > 0 ? Object.values(jCounts) : [0];
        const bgColors = getColorsForLabels(labels);

        if (ctxCol) {
            destroyChart('pinjamCol');
            tabAnalyticsCharts['pinjamCol'] = new Chart(ctxCol, {
                type: 'bar',
                data: { labels: labels, datasets: [{ label: 'Total Peminjaman', data: data, backgroundColor: bgColors, borderRadius: 6 }] },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { ticks: { color: labelColor } }, y: { ticks: { color: labelColor, precision: 0 } } } }
            });
        }
        if (ctxPie) {
            destroyChart('pinjamPie');
            tabAnalyticsCharts['pinjamPie'] = new Chart(ctxPie, {
                type: 'pie',
                data: { labels: labels, datasets: [{ data: data, backgroundColor: bgColors }] },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { color: labelColor } } } }
            });
        }
    }
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function escapeJsStr(str) {
    if (!str) return '';
    return String(str).replace(/\\/g, '\\\\').replace(/'/g, "\\'");
}

function updateStatCardsData() {
    const totalBarangItems = (window.dbBarang || []).length;
    const totalStokTotal = (window.dbBarang || []).reduce((sum, b) => sum + parseInt(b.stok_total || 0), 0);
    const totalStokTersedia = (window.dbBarang || []).reduce((sum, b) => sum + parseInt(b.stok_tersedia || 0), 0);
    const totalKategori = (window.dbKategori || []).length;
    const totalRak = (window.dbRak || []).length;
    const totalJurusan = (window.dbJurusan || []).length;
    const totalPengguna = (window.dbPengguna || []).length;

    const dipinjamCount = (window.dbPeminjaman || []).filter(p => p.status === 'dipinjam' || p.status === 'pending' || p.status === 'diajukan_pengembalian').length;
    const dikembalikanCount = (window.dbPeminjaman || []).filter(p => p.status === 'dikembalikan').length;
    const totalPeminjaman = (window.dbPeminjaman || []).length;
    const totalLogs = (window.dbLogAktivitas || []).length;

    const setTxt = (id, val) => {
        const el = document.getElementById(id);
        if (el) el.innerText = val;
    };

    setTxt('statSuperTotalJurusan', totalJurusan + ' Jurusan');
    setTxt('statSuperTotalKategori', totalKategori + ' Kategori');
    setTxt('statSuperTotalBarang', totalBarangItems + ' Item');
    setTxt('statSuperStokTersedia', totalStokTersedia + ' Unit');
    setTxt('statSuperBelumKembali', dipinjamCount + ' Transaksi');
    setTxt('statSuperSudahKembali', dikembalikanCount + ' Transaksi');
    setTxt('statSuperTotalLogs', totalLogs + ' Catatan');

    setTxt('statTotalBarang', totalBarangItems + ' Item');
    setTxt('statStokTersedia', totalStokTersedia + ' Unit');
    setTxt('statTotalKategori', totalKategori + ' Kategori');
    setTxt('statBelumKembali', dipinjamCount + ' Transaksi');
    setTxt('statSudahKembali', dikembalikanCount + ' Transaksi');
    setTxt('statTotalLogs', totalLogs + ' Catatan');

    setTxt('statTabJurusanTotal', totalJurusan + ' Jurusan');
    setTxt('statTabPenggunaTotal', totalPengguna + ' Pengguna');
    setTxt('statTabKategoriTotal', totalKategori + ' Kategori');
    setTxt('statTabRakTotal', totalRak + ' Rak');

    setTxt('statTabBarangTotalItem', totalBarangItems + ' Item');
    setTxt('statTabBarangTotalStok', totalStokTersedia + ' Unit');

    const totalMasukJumlah = (window.dbBarangMasuk || []).reduce((sum, m) => sum + parseInt(m.jumlah || 0), 0);
    setTxt('statTabMasukTotal', (window.dbBarangMasuk || []).length + ' Transaksi');
    setTxt('statTabMasukTotalItem', '+' + totalMasukJumlah + ' Unit');

    const totalKeluarJumlah = (window.dbBarangKeluar || []).reduce((sum, k) => sum + parseInt(k.jumlah || 0), 0);
    setTxt('statTabKeluarTotal', (window.dbBarangKeluar || []).length + ' Transaksi');
    setTxt('statTabKeluarTotalItem', '-' + totalKeluarJumlah + ' Unit');

    setTxt('statTabPinjamTotal', totalPeminjaman + ' Transaksi');
    setTxt('statTabPinjamBelumKembali', dipinjamCount + ' Transaksi');
    setTxt('statTabPinjamSudahKembali', dikembalikanCount + ' Transaksi');

    setTxt('statTabLogAktivitasTotal', totalLogs + ' Catatan');
}

function renderTablePengguna() {
    const tbody = document.querySelector('#tablePengguna tbody');
    if (!tbody || !window.dbPengguna) return;
    const isSuperAdmin = window.currentUser && window.currentUser.peran === 'admin_sekolah';
    const currentUserId = window.currentUser ? window.currentUser.id : null;

    tbody.innerHTML = window.dbPengguna.map((p, idx) => {
        const initial = (p.nama_lengkap || p.nama_pengguna || 'U').charAt(0).toUpperCase();
        const avatarHtml = p.foto_url
            ? `<img src="${p.foto_url}" class="w-7 h-7 rounded-full object-cover border border-sage-200 shrink-0 shadow-sm" alt="Avatar">`
            : `<div class="w-7 h-7 rounded-full bg-sage-600 text-white font-bold flex items-center justify-center text-[10px] shrink-0 shadow-sm">${initial}</div>`;
        const peranText = p.peran === 'admin_sekolah' ? 'Admin Sekolah' : (p.peran === 'admin_jurusan' ? 'Admin Jurusan' : (p.peran === 'petugas' ? 'Petugas Gudang' : 'Siswa'));
        const jurusanText = p.peran === 'admin_sekolah' ? '<span class="text-slate-400 font-normal">-</span>' : (p.nama_jurusan || '-');
        const loginBtnHtml = (isSuperAdmin && p.id !== currentUserId)
            ? `<button type="button" onclick="loginAsUser('${p.id}', '${escapeJsStr(p.nama_pengguna)}')" class="px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] shadow-sm transition-all flex items-center gap-1" title="Login Sebagai Akun Ini"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg><span>Login Akun</span></button>`
            : '';

        return `<tr class="hover:bg-sage-50/50">
            <td class="py-3.5 px-3 text-center"><input type="checkbox" class="row-checkbox rounded accent-sage-600 cursor-pointer" value="${p.id}" onchange="updateBatchDeleteBar()"></td>
            <td class="py-3.5 px-4 text-center font-bold text-slate-500 row-number-cell">${idx + 1}</td>
            <td class="py-3.5 px-4 font-bold text-slate-800 flex items-center gap-2.5">${avatarHtml}<span>${escapeHtml(p.nama_pengguna)}</span></td>
            <td class="py-3.5 px-4">${escapeHtml(p.nama_lengkap || '')}</td>
            <td class="py-3.5 px-4">${escapeHtml(p.email || '-')}</td>
            <td class="py-3.5 px-4 font-bold text-sage-700">${jurusanText}</td>
            <td class="py-3.5 px-4 font-semibold">${peranText}</td>
            <td class="py-3.5 px-4">${escapeHtml(p.nomor_telepon || '-')}</td>
            <td class="py-3.5 px-4">
                <div class="flex items-center gap-1.5">
                    ${loginBtnHtml}
                    <button type="button" onclick="editPengguna('${p.id}')" class="p-1.5 rounded-lg bg-sage-600 hover:bg-sage-700 text-white shadow-sm transition-all" title="Edit Data Pengguna"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 01-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                    <button type="button" onclick="deletePengguna('${p.id}', '${escapeJsStr(p.nama_pengguna)}')" class="p-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white shadow-sm transition-all" title="Hapus Data Pengguna"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                </div>
            </td>
        </tr>`;
    }).join('');
}

function renderTableJurusan() {
    const tbody = document.querySelector('#tableJurusan tbody');
    if (!tbody || !window.dbJurusan) return;
    const presets = {'kuning':'#EAB308','orange':'#EA580C','hijau':'#2E7D32','merah':'#DC2626','biru':'#2563EB','ungu':'#7C3AED','pink':'#E11D48','cyan':'#0891B2'};

    tbody.innerHTML = window.dbJurusan.map((j, idx) => {
        let colorVal = j.warna_tema || '#EAB308';
        let hexDisplay = presets[colorVal.toLowerCase()] || ((colorVal.startsWith('#') ? '' : '#') + colorVal.toUpperCase());
        const dateStr = j.created_at ? new Date(j.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) : '-';

        return `<tr class="hover:bg-sage-50/50">
            <td class="py-3.5 px-3 text-center"><input type="checkbox" class="row-checkbox rounded accent-sage-600 cursor-pointer" value="${j.id}" onchange="updateBatchDeleteBar()"></td>
            <td class="py-3.5 px-4 text-center font-bold text-slate-500 row-number-cell">${idx + 1}</td>
            <td class="py-3.5 px-4 font-bold text-sage-700">${escapeHtml(j.nama_jurusan)}</td>
            <td class="py-3.5 px-4 font-bold">
                <span class="inline-flex items-center gap-2 px-2.5 py-1 rounded-lg text-xs font-mono font-bold bg-slate-100 text-slate-700 border border-slate-200">
                    <span class="w-3.5 h-3.5 rounded-md shrink-0 shadow-sm border border-black/10" style="background-color: ${hexDisplay};"></span>
                    <span>${hexDisplay}</span>
                </span>
            </td>
            <td class="py-3.5 px-4">${escapeHtml(j.deskripsi || '-')}</td>
            <td class="py-3.5 px-4">${dateStr}</td>
            <td class="py-3.5 px-4">
                <div class="flex items-center gap-1.5">
                    <button type="button" onclick="editJurusan('${j.id}')" class="p-1.5 rounded-lg bg-sage-600 hover:bg-sage-700 text-white shadow-sm transition-all" title="Edit Data Jurusan"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 01-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                    <button type="button" onclick="deleteJurusan('${j.id}', '${escapeJsStr(j.nama_jurusan)}')" class="p-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white shadow-sm transition-all" title="Hapus Data Jurusan"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                </div>
            </td>
        </tr>`;
    }).join('');
}

function renderTableKategori() {
    const tbody = document.querySelector('#tableKategori tbody');
    if (!tbody || !window.dbKategori) return;
    const isSuperAdmin = window.currentUser && window.currentUser.peran === 'admin_sekolah';

    tbody.innerHTML = window.dbKategori.map((kat, idx) => {
        const jurTd = isSuperAdmin ? `<td class="py-3.5 px-4 font-bold text-sage-700">${escapeHtml(kat.nama_jurusan || 'Semua Jurusan')}</td>` : '';
        return `<tr class="hover:bg-sage-50/50">
            <td class="py-3.5 px-3 text-center"><input type="checkbox" class="row-checkbox rounded accent-sage-600 cursor-pointer" value="${kat.id}" onchange="updateBatchDeleteBar()"></td>
            <td class="py-3.5 px-4 text-center font-bold text-slate-500 row-number-cell">${idx + 1}</td>
            <td class="py-3.5 px-4 font-bold text-slate-800">${escapeHtml(kat.nama_kategori)}</td>
            ${jurTd}
            <td class="py-3.5 px-4">${escapeHtml(kat.deskripsi || '-')}</td>
            <td class="py-3.5 px-4">
                <div class="flex items-center gap-1.5">
                    <button type="button" onclick="editKategori('${kat.id}')" class="p-1.5 rounded-lg bg-sage-600 hover:bg-sage-700 text-white shadow-sm transition-all" title="Edit Kategori"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 01-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                    <button type="button" onclick="deleteKategori('${kat.id}', '${escapeJsStr(kat.nama_kategori)}')" class="p-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white shadow-sm transition-all" title="Hapus Kategori"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                </div>
            </td>
        </tr>`;
    }).join('');
}

function renderTableRak() {
    const tbody = document.querySelector('#tableRak tbody');
    if (!tbody || !window.dbRak) return;
    const isSuperAdmin = window.currentUser && window.currentUser.peran === 'admin_sekolah';

    tbody.innerHTML = window.dbRak.map((rk, idx) => {
        const jurTd = isSuperAdmin ? `<td class="py-3.5 px-4 font-bold text-sage-700">${escapeHtml(rk.nama_jurusan || 'Semua Jurusan')}</td>` : '';
        return `<tr class="hover:bg-sage-50/50">
            <td class="py-3.5 px-3 text-center"><input type="checkbox" class="row-checkbox rounded accent-sage-600 cursor-pointer" value="${rk.id}" onchange="updateBatchDeleteBar()"></td>
            <td class="py-3.5 px-4 text-center font-bold text-slate-500 row-number-cell">${idx + 1}</td>
            <td class="py-3.5 px-4 font-bold text-slate-800">${escapeHtml(rk.nama_rak)}</td>
            <td class="py-3.5 px-4 font-mono text-sage-700">
                <button type="button" onclick="showRakBarcodeModal('${escapeHtml(rk.barcode || '')}', '${escapeJsStr(rk.nama_rak)}', '${rk.id}')" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-sage-50 hover:bg-sage-100 text-sage-800 border border-sage-200 font-bold transition-all group" title="Klik untuk preview / scan QR Code Rak">
                    <svg class="w-3.5 h-3.5 text-sage-600 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    <span>${escapeHtml(rk.barcode || '-')}</span>
                </button>
            </td>
            <td class="py-3.5 px-4 font-semibold text-slate-700">${escapeHtml(rk.kategori_rak || '-')}</td>
            ${jurTd}
            <td class="py-3.5 px-4">${escapeHtml(rk.keterangan || '-')}</td>
            <td class="py-3.5 px-4 text-center font-bold text-slate-800">${parseInt(rk.total_barang || 0)} Jenis</td>
            <td class="py-3.5 px-4 text-center font-extrabold text-sage-600">${parseInt(rk.total_stok_tersedia || 0)} Unit</td>
            <td class="py-3.5 px-4">
                <div class="flex items-center gap-1.5">
                    <button type="button" onclick="showBarangInRakModal('${rk.id}', '${escapeJsStr(rk.nama_rak)}')" class="px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] shadow-sm transition-all flex items-center gap-1" title="Lihat Daftar Barang di Rak"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg><span>Lihat Barang</span></button>
                    <button type="button" onclick="editRak('${rk.id}')" class="p-1.5 rounded-lg bg-sage-600 hover:bg-sage-700 text-white shadow-sm transition-all" title="Edit Data Rak"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 01-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                    <button type="button" onclick="deleteRak('${rk.id}', '${escapeJsStr(rk.nama_rak)}')" class="p-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white shadow-sm transition-all" title="Hapus Data Rak"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                </div>
            </td>
        </tr>`;
    }).join('');
}

function renderTableBarang() {
    const tbody = document.querySelector('#tableBarang tbody');
    if (!tbody || !window.dbBarang) return;
    const isSuperAdmin = window.currentUser && window.currentUser.peran === 'admin_sekolah';
    const isNotSiswa = window.currentUser && window.currentUser.peran !== 'siswa';

    tbody.innerHTML = window.dbBarang.map((b, idx) => {
        const jurTd = isSuperAdmin ? `<td class="py-3.5 px-4 font-bold text-sage-700">${escapeHtml(b.nama_jurusan || 'Semua Jurusan')}</td>` : '';
        const barcodeHtml = b.barcode
            ? `<button type="button" onclick="showBarcodeModal('${escapeHtml(b.barcode)}', '${escapeJsStr(b.nama_barang)}')" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-sage-50 hover:bg-sage-100 text-sage-800 border border-sage-200 font-bold transition-all group" title="Klik untuk preview / simpan barcode"><svg class="w-4 h-4 text-sage-600 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg><span>${escapeHtml(b.barcode)}</span></button>`
            : '<span class="text-slate-400">-</span>';

        const actionBtns = isNotSiswa
            ? `<div class="flex items-center gap-1.5"><button type="button" onclick="editBarang('${b.id}')" class="p-1.5 rounded-lg bg-sage-600 hover:bg-sage-700 text-white shadow-sm transition-all" title="Edit Master Barang"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 01-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button><button type="button" onclick="deleteBarang('${b.id}', '${escapeJsStr(b.nama_barang)}')" class="p-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white shadow-sm transition-all" title="Hapus Data Barang"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button></div>`
            : '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-sage-50 text-sage-700 border border-sage-200">Read Only</span>';

        return `<tr id="row-barang-${b.id}" class="hover:bg-sage-50/50 transition-all duration-300">
            <td class="py-3.5 px-3 text-center"><input type="checkbox" class="row-checkbox rounded accent-sage-600 cursor-pointer" value="${b.id}" onchange="updateBatchDeleteBar()"></td>
            <td class="py-3.5 px-4 text-center font-bold text-slate-500 row-number-cell">${idx + 1}</td>
            <td class="py-3.5 px-4 font-extrabold text-xs nama-barang-cell text-sage-700">${escapeHtml(b.nama_barang)}</td>
            <td class="py-3.5 px-4 font-semibold text-slate-700">${escapeHtml(b.nama_kategori || '-')}</td>
            <td class="py-3.5 px-4 font-semibold text-slate-700">${escapeHtml(b.nama_rak || '-')}</td>
            ${jurTd}
            <td class="py-3.5 px-4">${escapeHtml(b.merek || '-')}</td>
            <td class="py-3.5 px-4 font-mono text-sage-700">${barcodeHtml}</td>
            <td class="py-3.5 px-4 font-bold">${escapeHtml(b.stok_total)} ${escapeHtml(b.satuan || 'Unit')}</td>
            <td class="py-3.5 px-4 font-bold text-sage-600">${escapeHtml(b.stok_tersedia)} ${escapeHtml(b.satuan || 'Unit')}</td>
            <td class="py-3.5 px-4">${actionBtns}</td>
        </tr>`;
    }).join('');
}

function renderTableBarangMasuk() {
    const tbody = document.querySelector('#tableBarangMasuk tbody');
    if (!tbody || !window.dbBarangMasuk) return;
    const isSuperAdmin = window.currentUser && window.currentUser.peran === 'admin_sekolah';

    tbody.innerHTML = window.dbBarangMasuk.map((bm, idx) => {
        const jurTd = isSuperAdmin ? `<td class="py-3.5 px-4 font-bold text-sage-700">${escapeHtml(bm.nama_jurusan || '-')}</td>` : '';
        const dateStr = bm.created_at ? new Date(bm.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) : '-';
        return `<tr class="hover:bg-sage-50/50">
            <td class="py-3.5 px-3 text-center"><input type="checkbox" class="row-checkbox rounded accent-sage-600 cursor-pointer" value="${bm.id}" onchange="updateBatchDeleteBar()"></td>
            <td class="py-3.5 px-4 text-center font-bold text-slate-500 row-number-cell">${idx + 1}</td>
            <td class="py-3.5 px-4 font-bold text-slate-800">${escapeHtml(bm.nama_barang)}</td>
            ${jurTd}
            <td class="py-3.5 px-4">${escapeHtml(bm.nama_pemasok || '-')}</td>
            <td class="py-3.5 px-4 font-bold text-emerald-600">+${escapeHtml(bm.jumlah)} ${escapeHtml(bm.satuan || 'Unit')}</td>
            <td class="py-3.5 px-4">${escapeHtml(bm.nama_petugas || 'Petugas')}</td>
            <td class="py-3.5 px-4">${dateStr}</td>
            <td class="py-3.5 px-4">
                <div class="flex items-center gap-1.5">
                    <button type="button" onclick="editBarangMasuk('${bm.id}')" class="p-1.5 rounded-lg bg-sage-600 hover:bg-sage-700 text-white shadow-sm transition-all" title="Edit Transaksi Barang Masuk"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 01-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                    <button type="button" onclick="deleteBarangMasuk('${bm.id}', '${escapeJsStr(bm.nama_barang)}')" class="p-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white shadow-sm transition-all" title="Hapus Transaksi Barang Masuk"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                </div>
            </td>
        </tr>`;
    }).join('');
}

function renderTableBarangKeluar() {
    const tbody = document.querySelector('#tableBarangKeluar tbody');
    if (!tbody || !window.dbBarangKeluar) return;
    const isSuperAdmin = window.currentUser && window.currentUser.peran === 'admin_sekolah';

    tbody.innerHTML = window.dbBarangKeluar.map((bk, idx) => {
        const jurTd = isSuperAdmin ? `<td class="py-3.5 px-4 font-bold text-sage-700">${escapeHtml(bk.nama_jurusan || '-')}</td>` : '';
        const dateStr = bk.created_at ? new Date(bk.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) : '-';
        return `<tr class="hover:bg-sage-50/50">
            <td class="py-3.5 px-3 text-center"><input type="checkbox" class="row-checkbox rounded accent-sage-600 cursor-pointer" value="${bk.id}" onchange="updateBatchDeleteBar()"></td>
            <td class="py-3.5 px-4 text-center font-bold text-slate-500 row-number-cell">${idx + 1}</td>
            <td class="py-3.5 px-4 font-bold text-slate-800">${escapeHtml(bk.nama_barang)}</td>
            ${jurTd}
            <td class="py-3.5 px-4">${escapeHtml(bk.nama_penerima || '-')}</td>
            <td class="py-3.5 px-4 font-bold text-amber-600">-${escapeHtml(bk.jumlah)} ${escapeHtml(bk.satuan || 'Unit')}</td>
            <td class="py-3.5 px-4">${escapeHtml(bk.nama_petugas || 'Petugas')}</td>
            <td class="py-3.5 px-4">${dateStr}</td>
            <td class="py-3.5 px-4">
                <div class="flex items-center gap-1.5">
                    <button type="button" onclick="editBarangKeluar('${bk.id}')" class="p-1.5 rounded-lg bg-sage-600 hover:bg-sage-700 text-white shadow-sm transition-all" title="Edit Transaksi Barang Keluar"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 01-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                    <button type="button" onclick="deleteBarangKeluar('${bk.id}', '${escapeJsStr(bk.nama_barang)}')" class="p-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white shadow-sm transition-all" title="Hapus Transaksi Barang Keluar"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                </div>
            </td>
        </tr>`;
    }).join('');
}

function renderTablePeminjaman() {
    const tbody = document.querySelector('#tablePeminjaman tbody');
    if (!tbody || !window.dbPeminjaman) return;
    const isSuperAdmin = window.currentUser && window.currentUser.peran === 'admin_sekolah';

    tbody.innerHTML = window.dbPeminjaman.map((pm, idx) => {
        const jurTd = isSuperAdmin ? `<td class="py-3.5 px-4 font-bold text-sage-700">${escapeHtml(pm.nama_jurusan || '-')}</td>` : '';
        const tglPinjam = pm.tanggal_pinjam ? new Date(pm.tanggal_pinjam).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) : '-';
        const tglKembali = (pm.tanggal_kembali && pm.status === 'dikembalikan') ? new Date(pm.tanggal_kembali).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) : '<span class="text-slate-400 font-normal">-</span>';
        
        let statusHtml = '';
        if (pm.status === 'dipinjam') statusHtml = `<span class="text-amber-500 font-extrabold">Dipinjam</span>`;
        else if (pm.status === 'pending') statusHtml = `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-amber-100 text-amber-800 border border-amber-300"><svg class="w-3 h-3 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>Pending</span>`;
        else if (pm.status === 'ditolak') statusHtml = `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-red-100 text-red-800 border border-red-300">Ditolak</span>`;
        else statusHtml = `<span class="font-extrabold text-sage-600">Dikembalikan</span>`;

        let actionBtns = '';
        if (pm.status === 'dipinjam') {
            actionBtns = `<button type="button" onclick="kembalikanPeminjaman('${pm.id}')" class="px-2.5 py-1 rounded-lg bg-sage-600 hover:bg-sage-700 text-white font-bold text-[11px] shadow-sm transition-all" title="Kembalikan Alat">Kembalikan</button>`;
        } else if (pm.status === 'pending') {
            actionBtns = `<button type="button" onclick="approvePeminjaman('${pm.id}')" class="px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] shadow-sm transition-all flex items-center gap-1" title="Setujui Pengembalian"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Setujui</button>
            <button type="button" onclick="rejectPeminjaman('${pm.id}')" class="px-2.5 py-1 rounded-lg bg-red-600 hover:bg-red-700 text-white font-bold text-[11px] shadow-sm transition-all flex items-center gap-1" title="Tolak Pengembalian"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>Tolak</button>`;
            if (pm.bukti_foto) {
                actionBtns += `<button type="button" onclick="showFotoPreview('${pm.bukti_foto}', 'Bukti Foto Pengembalian Alat', 'Peminjam: ${escapeJsStr(pm.nama_peminjam)} | Alat: ${escapeJsStr(pm.nama_barang)}')" class="p-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow-sm transition-all" title="Lihat Bukti Foto"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></button>`;
            }
        } else if (pm.status === 'ditolak') {
            actionBtns = `<button type="button" onclick="kembalikanPeminjaman('${pm.id}')" class="px-2.5 py-1 rounded-lg bg-amber-600 hover:bg-amber-700 text-white font-bold text-[11px] shadow-sm transition-all flex items-center gap-1" title="Upload Ulang Bukti Foto Pengembalian">Upload Ulang</button>`;
            if (pm.bukti_foto) {
                actionBtns += `<button type="button" onclick="showFotoPreview('${pm.bukti_foto}', 'Bukti Foto Pengembalian Alat (Ditolak)', 'Peminjam: ${escapeJsStr(pm.nama_peminjam)} | Alat: ${escapeJsStr(pm.nama_barang)}')" class="p-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow-sm transition-all" title="Lihat Bukti Foto Ditolak"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></button>`;
            }
        }

        if (pm.status !== 'dikembalikan') {
            actionBtns += `<button type="button" onclick="editPeminjaman('${pm.id}')" class="p-1.5 rounded-lg bg-sage-600 hover:bg-sage-700 text-white shadow-sm transition-all" title="Edit Transaksi Peminjaman"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 01-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
            <button type="button" onclick="deletePeminjaman('${pm.id}', '${escapeJsStr(pm.nama_peminjam)}')" class="p-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white shadow-sm transition-all" title="Hapus Transaksi Peminjaman"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>`;
        } else if (pm.bukti_foto) {
            actionBtns += `<button type="button" onclick="showFotoPreview('${pm.bukti_foto}', 'Bukti Foto Pengembalian Alat', 'Peminjam: ${escapeJsStr(pm.nama_peminjam)} | Alat: ${escapeJsStr(pm.nama_barang)}')" class="p-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow-sm transition-all" title="Lihat Bukti Foto"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></button>`;
        }

        return `<tr class="hover:bg-sage-50/50">
            <td class="py-3.5 px-3 text-center"><input type="checkbox" class="row-checkbox rounded accent-sage-600 cursor-pointer" value="${pm.id}" onchange="updateBatchDeleteBar()"></td>
            <td class="py-3.5 px-4 text-center font-bold text-slate-500 row-number-cell">${idx + 1}</td>
            <td class="py-3.5 px-4 font-bold text-slate-800">${escapeHtml(pm.nama_barang)}</td>
            ${jurTd}
            <td class="py-3.5 px-4">${escapeHtml(pm.nama_peminjam)}</td>
            <td class="py-3.5 px-4 font-semibold text-slate-700">${escapeHtml(pm.nama_petugas || '-')}</td>
            <td class="py-3.5 px-4 font-semibold">${escapeHtml(pm.jumlah)} Unit</td>
            <td class="py-3.5 px-4 font-semibold text-slate-700">${escapeHtml(pm.tugas || '-')}</td>
            <td class="py-3.5 px-4 font-semibold text-sage-700">${escapeHtml(pm.tahun_ajaran || '2025/2026')}</td>
            <td class="py-3.5 px-4">${tglPinjam}</td>
            <td class="py-3.5 px-4 font-mono">${tglKembali}</td>
            <td class="py-3.5 px-4 text-center">${statusHtml}</td>
            <td class="py-3.5 px-4"><div class="flex items-center gap-1.5">${actionBtns}</div></td>
        </tr>`;
    }).join('');
}

function renderTableLogPeminjaman() {
    const tbody = document.querySelector('#tableLogPeminjaman tbody');
    if (!tbody || !window.dbPeminjaman) return;
    const isSuperAdmin = window.currentUser && window.currentUser.peran === 'admin_sekolah';

    tbody.innerHTML = window.dbPeminjaman.map((logPm, idx) => {
        const jurTd = isSuperAdmin ? `<td class="py-3 px-4 font-bold text-sage-700">${escapeHtml(logPm.nama_jurusan || 'Semua Jurusan')}</td>` : '';
        const tglPinjam = logPm.tanggal_pinjam ? new Date(logPm.tanggal_pinjam).toLocaleString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }) : '-';
        const tglKembali = logPm.tanggal_kembali ? new Date(logPm.tanggal_kembali).toLocaleString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }) : '-';

        let statusHtml = '';
        if (logPm.status === 'dipinjam') statusHtml = `<span class="text-amber-500 font-extrabold">Dipinjam</span>`;
        else if (logPm.status === 'pending') statusHtml = `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-amber-100 text-amber-800 border border-amber-300"><svg class="w-3 h-3 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>Pending</span>`;
        else if (logPm.status === 'ditolak') statusHtml = `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-red-100 text-red-800 border border-red-300">Ditolak</span>`;
        else statusHtml = `<span class="font-extrabold text-sage-600">Dikembalikan</span>`;

        const fotoBtn = logPm.bukti_foto
            ? `<button type="button" onclick="showFotoPreview('${logPm.bukti_foto}', 'Bukti Foto Pengembalian Alat', 'Peminjam: ${escapeJsStr(logPm.nama_peminjam)} | Alat: ${escapeJsStr(logPm.nama_barang)}')" class="px-3 py-1 rounded-xl bg-sage-600 hover:bg-sage-700 text-white font-bold text-xs shadow-md shadow-sage-600/20 transition-all inline-flex items-center gap-1.5" title="Lihat Foto Bukti Pengembalian Alat"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg><span>View</span></button>`
            : '<span class="text-slate-400 font-normal">-</span>';

        const actionBtn = (logPm.status === 'dikembalikan' && window.currentUser && window.currentUser.peran !== 'siswa')
            ? `<button type="button" onclick="editPeminjaman('${logPm.id}')" class="p-1.5 rounded-lg bg-sage-600 hover:bg-sage-700 text-white shadow-sm transition-all flex items-center gap-1 text-xs font-bold px-2.5" title="Edit Status Peminjaman"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 01-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg><span>Edit Status</span></button>`
            : '<span class="text-slate-400 font-normal">-</span>';

        return `<tr class="hover:bg-sage-50/50">
            <td class="py-3 px-4 text-center font-bold text-slate-500 row-number-cell">${idx + 1}</td>
            <td class="py-3 px-4 font-bold text-slate-800">${escapeHtml(logPm.nama_peminjam || '-')}</td>
            <td class="py-3 px-4 font-semibold text-slate-700">${escapeHtml(logPm.nama_barang || '-')}</td>
            ${jurTd}
            <td class="py-3 px-4 font-semibold text-slate-700">${escapeHtml(logPm.nama_petugas || '-')}</td>
            <td class="py-3 px-4 text-center font-bold text-slate-800">${parseInt(logPm.jumlah || 1)} ${escapeHtml(logPm.satuan || 'Unit')}</td>
            <td class="py-3 px-4 font-mono text-[11px] text-slate-600">${tglPinjam}</td>
            <td class="py-3 px-4 font-mono text-[11px] text-slate-600">${tglKembali}</td>
            <td class="py-3 px-4 text-center font-bold">${statusHtml}</td>
            <td class="py-3 px-4 text-slate-600">${escapeHtml(logPm.tugas || logPm.catatan || '-')}</td>
            <td class="py-3 px-4">${fotoBtn}</td>
            <td class="py-3 px-4">${actionBtn}</td>
        </tr>`;
    }).join('');
}

function renderTableLogAktivitas() {
    const tbody = document.querySelector('#tableLogAktivitas tbody');
    if (!tbody || !window.dbLogAktivitas) return;
    const isSuperAdmin = window.currentUser && window.currentUser.peran === 'admin_sekolah';

    tbody.innerHTML = window.dbLogAktivitas.map((log, idx) => {
        const dateAttr = log.created_at ? log.created_at.substring(0, 10) : '';
        const tindakan = (log.tindakan || '').toUpperCase();
        const jurAttr = (log.nama_jurusan || '-').toLowerCase();
        const pengAttr = (log.nama_pengguna || log.nama_lengkap || 'Sistem').toLowerCase();

        let badgeStyle = 'bg-sage-100 text-sage-800 border-sage-200';
        let dotColor = 'bg-sage-600';

        if (tindakan === 'LOGIN') {
            badgeStyle = 'bg-emerald-100 text-emerald-800 border-emerald-200';
            dotColor = 'bg-emerald-600';
        } else if (tindakan === 'LOGOUT') {
            badgeStyle = 'bg-rose-100 text-rose-800 border-rose-200';
            dotColor = 'bg-rose-600';
        } else if (tindakan.includes('TAMBAH') || tindakan.includes('CREATE')) {
            badgeStyle = 'bg-blue-100 text-blue-800 border-blue-200';
            dotColor = 'bg-blue-600';
        } else if (tindakan.includes('HAPUS') || tindakan.includes('DELETE')) {
            badgeStyle = 'bg-red-100 text-red-800 border-red-200';
            dotColor = 'bg-red-600';
        } else if (tindakan.includes('IMPERSONATE')) {
            badgeStyle = 'bg-amber-100 text-amber-800 border-amber-200';
            dotColor = 'bg-amber-600';
        }

        const jurTd = isSuperAdmin ? `<td class="py-3.5 px-4 font-bold text-sage-700">${escapeHtml(log.nama_jurusan || '-')}</td>` : '';
        const dateStr = log.created_at ? new Date(log.created_at).toLocaleString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }) : '-';

        return `<tr class="hover:bg-sage-50/50" data-date="${dateAttr}" data-tindakan="${escapeHtml(tindakan)}" data-jurusan="${escapeHtml(jurAttr)}" data-pengguna="${escapeHtml(pengAttr)}">
            <td class="py-3.5 px-4 text-center font-bold text-slate-500 row-number-cell">${idx + 1}</td>
            <td class="py-3.5 px-4 font-bold text-slate-800">${escapeHtml(log.nama_pengguna || log.nama_lengkap || 'Sistem')}</td>
            <td class="py-3.5 px-4 font-bold text-slate-800">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold ${badgeStyle} border">
                    <span class="w-1.5 h-1.5 rounded-full ${dotColor} shrink-0"></span>
                    <span>${escapeHtml(log.tindakan)}</span>
                </span>
            </td>
            ${jurTd}
            <td class="py-3.5 px-4 text-slate-600">${escapeHtml(log.deskripsi || '-')}</td>
            <td class="py-3.5 px-4 font-mono text-[11px] text-slate-500">${dateStr}</td>
        </tr>`;
    }).join('');
}

function renderAllTableBodies() {
    renderTablePengguna();
    renderTableJurusan();
    renderTableKategori();
    renderTableRak();
    renderTableBarang();
    renderTableBarangMasuk();
    renderTableBarangKeluar();
    renderTablePeminjaman();
    renderTableLogPeminjaman();
    renderTableLogAktivitas();
}

let isFetchingFreshData = false;
async function fetchFreshDataAndRefreshUI(tabId = null) {
    if (isFetchingFreshData) return;
    isFetchingFreshData = true;

    try {
        const res = await fetch('api.php?action=get_fresh_data');
        if (!res.ok) throw new Error('Network response error');
        const data = await res.json();

        if (data && data.success) {
            window.dbJurusan = data.dbJurusan || [];
            window.dbPengguna = data.dbPengguna || [];
            window.dbKategori = data.dbKategori || [];
            window.dbRak = data.dbRak || [];
            window.dbBarang = data.dbBarang || [];
            window.dbBarangMasuk = data.dbBarangMasuk || [];
            window.dbBarangKeluar = data.dbBarangKeluar || [];
            window.dbPeminjaman = data.dbPeminjaman || [];
            window.dbLogAktivitas = data.dbLogAktivitas || [];

            updateStatCardsData();
            renderAllTableBodies();

            if (window.tablePaginators) {
                Object.keys(window.tablePaginators).forEach(id => {
                    if (window.tablePaginators[id] && typeof window.tablePaginators[id].reinit === 'function') {
                        window.tablePaginators[id].reinit();
                    }
                });
            }

            const currentTab = tabId || (new URLSearchParams(window.location.search).get('tab') || 'dashboard');
            if (currentTab === 'dashboard') {
                initInventoryChart();
            }
            initTabAnalytics(currentTab);
        }
    } catch (err) {
        console.error('Error fetching fresh data on navbar tab switch:', err);
    } finally {
        isFetchingFreshData = false;
    }
}

function switchTab(tabId) {
    setUrlParam('tab', tabId);
    if (typeof clearBatchSelection === 'function') clearBatchSelection();

    document.querySelectorAll('.tab-content').forEach(section => {
        section.classList.add('hidden');
        section.classList.remove('animate-fade-in-up');
    });

    document.querySelectorAll('.nav-tab-btn').forEach(btn => {
        btn.classList.remove('active');
        btn.classList.add('text-slate-600', 'hover:text-sage-700', 'hover:bg-sage-50');
    });

    const targetSection = document.getElementById('tab-' + tabId);
    if (targetSection) {
        targetSection.classList.remove('hidden');
        void targetSection.offsetWidth; // Force reflow
        targetSection.classList.add('animate-fade-in-up');
    }

    const targetBtn = document.querySelector(`.nav-tab-btn[data-tab="${tabId}"]`);
    if (targetBtn) {
        targetBtn.classList.add('active');
        targetBtn.classList.remove('text-slate-600', 'hover:text-sage-700', 'hover:bg-sage-50');
    }

    const pageTitle = document.getElementById('pageTitle');
    if (pageTitle && targetBtn) {
        const titleText = targetBtn.querySelector('span').innerText;
        pageTitle.innerText = titleText === 'Dashboard' ? 'Dashboard Overview' : titleText;
    }

    if (tabId === 'swagger') {
        setTimeout(initSwaggerUi, 100);
    }

    // Always fetch fresh data from database when navbar tab switches
    fetchFreshDataAndRefreshUI(tabId);
}

let swaggerUiInitialized = false;
function initSwaggerUi() {
    if (swaggerUiInitialized) return;
    const container = document.getElementById('swagger-ui-container');
    if (!container) return;

    if (typeof SwaggerUIBundle !== 'undefined') {
        window.ui = SwaggerUIBundle({
            url: 'api/openapi.json',
            dom_id: '#swagger-ui-container',
            deepLinking: true,
            presets: [
                SwaggerUIBundle.presets.apis,
                SwaggerUIBundle.SwaggerUIStandalonePreset
            ],
            layout: "BaseLayout"
        });
        swaggerUiInitialized = true;
    } else {
        setTimeout(initSwaggerUi, 300);
    }
}

class TablePaginationManager {
    constructor(tableId, pageSize = 5) {
        this.table = document.getElementById(tableId);
        if (!this.table) return;
        this.pageSize = pageSize;
        this.currentPage = 1;
        this.searchQuery = '';
        this.selectedJurusanFilter = '';
        this.tbody = this.table.querySelector('tbody');
        if (!this.tbody) return;
        this.allRows = Array.from(this.tbody.querySelectorAll('tr'));
        this.filteredRows = [...this.allRows];
        this.renderControls();
        this.update();
    }

    reinit() {
        this.tbody = this.table.querySelector('tbody');
        if (!this.tbody) return;
        this.allRows = Array.from(this.tbody.querySelectorAll('tr'));
        this.update();
    }

    renderControls() {
        if (this.table.id !== 'tableLogAktivitas') {
            const cardHeader = this.table.closest('[class*="bg-white"]')?.querySelector('.flex.items-center.justify-between');
            if (cardHeader && !cardHeader.querySelector('.table-search-input')) {
                const thList = Array.from(this.table.querySelectorAll('thead th'));
                const jurusanColIndex = thList.findIndex(th => th.innerText.trim().toLowerCase() === 'jurusan');

                const searchDiv = document.createElement('div');
                searchDiv.className = 'relative flex items-center gap-2 shrink-0 flex-wrap';

                let jurusanFilterHtml = '';
                const isSuperAdmin = window.currentUser && window.currentUser.peran === 'admin_sekolah';
                if (isSuperAdmin && jurusanColIndex !== -1 && window.dbJurusan && window.dbJurusan.length > 0) {
                    jurusanFilterHtml = `
                        <select class="jurusan-filter-select px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none focus:border-sage-600 transition-all">
                            <option value="">Semua Jurusan</option>
                            ${window.dbJurusan.map(j => `<option value="${j.nama_jurusan.toLowerCase()}">${j.nama_jurusan}</option>`).join('')}
                        </select>
                    `;
                }

                searchDiv.innerHTML = `
                    ${jurusanFilterHtml}
                    <div class="relative flex items-center shrink-0">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" placeholder="Cari data..." class="table-search-input pl-9 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none focus:border-sage-600 focus:bg-white transition-all w-44 focus:w-56">
                    </div>
                `;

                const filterSelect = searchDiv.querySelector('.jurusan-filter-select');
                if (filterSelect) {
                    filterSelect.addEventListener('change', (e) => {
                        this.selectedJurusanFilter = e.target.value;
                        this.currentPage = 1;
                        this.update();
                    });
                }

                const searchInput = searchDiv.querySelector('input');
                let searchTimeout = null;
                searchInput.addEventListener('input', (e) => {
                    clearTimeout(searchTimeout);
                    const query = e.target.value.toLowerCase().trim();
                    searchTimeout = setTimeout(() => {
                        this.searchQuery = query;
                        this.currentPage = 1;
                        this.update();
                    }, 150);
                });

                const actionContainer = cardHeader.querySelector('.flex.items-center.gap-3') || cardHeader;
                if (actionContainer !== cardHeader) {
                    actionContainer.prepend(searchDiv);
                } else {
                    cardHeader.appendChild(searchDiv);
                }
            }
        }

        const overflowDiv = this.table.closest('.overflow-x-auto');
        if (overflowDiv && !overflowDiv.parentNode.querySelector(`.pagination-footer-${this.table.id}`)) {
            const footer = document.createElement('div');
            footer.className = `pagination-footer-${this.table.id} mt-4 flex items-center justify-between text-xs text-slate-500 pt-3 border-t border-slate-100 flex-wrap gap-3`;
            footer.innerHTML = `
                <div class="flex items-center gap-2">
                    <span class="font-medium info-text">Menampilkan 0 data</span>
                    <select class="page-size-select bg-slate-50 border border-slate-200 rounded-lg px-2 py-1 text-xs font-semibold text-slate-700 focus:outline-none">
                        <option value="5" ${this.pageSize === 5 ? 'selected' : ''}>5 per hal</option>
                        <option value="10" ${this.pageSize === 10 ? 'selected' : ''}>10 per hal</option>
                        <option value="25" ${this.pageSize === 25 ? 'selected' : ''}>25 per hal</option>
                    </select>
                </div>
                <div class="flex items-center gap-1.5 pagination-btns"></div>
            `;

            footer.querySelector('.page-size-select').addEventListener('change', (e) => {
                this.pageSize = parseInt(e.target.value);
                this.currentPage = 1;
                this.update();
            });

            overflowDiv.after(footer);
            this.footer = footer;
        }
    }

    update() {
        const thList = Array.from(this.table.querySelectorAll('thead th'));
        const jurusanColIndex = thList.findIndex(th => th.innerText.trim().toLowerCase() === 'jurusan');

        this.filteredRows = this.allRows.filter(row => {
            if (this.selectedJurusanFilter && jurusanColIndex !== -1) {
                const cell = row.cells[jurusanColIndex];
                if (cell) {
                    const cellText = cell.innerText.toLowerCase();
                    if (!cellText.includes(this.selectedJurusanFilter)) {
                        return false;
                    }
                }
            }

            if (this.customDateFilter) {
                const rowDate = row.getAttribute('data-date') || '';
                if (rowDate !== this.customDateFilter) return false;
            }

            if (this.customTindakanFilter) {
                const rowTindakan = (row.getAttribute('data-tindakan') || '').toUpperCase();
                if (rowTindakan !== this.customTindakanFilter) return false;
            }

            if (this.customJurusanFilter) {
                const rowJurusan = (row.getAttribute('data-jurusan') || '').toLowerCase();
                if (!rowJurusan.includes(this.customJurusanFilter)) return false;
            }

            if (this.customNameFilter) {
                const rowPengguna = (row.getAttribute('data-pengguna') || '').toLowerCase();
                if (!rowPengguna.includes(this.customNameFilter)) return false;
            }

            if (!this.searchQuery) return true;
            return row.innerText.toLowerCase().includes(this.searchQuery);
        });

        this.allRows.forEach(row => row.style.display = 'none');

        const totalFiltered = this.filteredRows.length;
        const totalPages = Math.ceil(totalFiltered / this.pageSize) || 1;

        if (this.currentPage > totalPages) this.currentPage = totalPages;

        const startIdx = (this.currentPage - 1) * this.pageSize;
        const endIdx = Math.min(startIdx + this.pageSize, totalFiltered);

        for (let i = startIdx; i < endIdx; i++) {
            if (this.filteredRows[i]) {
                this.filteredRows[i].style.display = '';
                const numCell = this.filteredRows[i].querySelector('.row-number-cell');
                if (numCell) {
                    numCell.innerText = i + 1;
                }
            }
        }

        if (this.footer) {
            const infoText = this.footer.querySelector('.info-text');
            if (infoText) {
                if (totalFiltered === 0) {
                    infoText.innerText = 'Data tidak ditemukan';
                } else {
                    infoText.innerText = `Menampilkan ${startIdx + 1} - ${endIdx} dari ${totalFiltered} data`;
                }
            }

            const btnsContainer = this.footer.querySelector('.pagination-btns');
            if (btnsContainer) {
                btnsContainer.innerHTML = '';

                const prevBtn = document.createElement('button');
                prevBtn.type = 'button';
                prevBtn.className = `px-2.5 py-1 rounded-lg border text-xs font-bold transition-colors ${this.currentPage > 1 ? 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50' : 'bg-slate-50 border-slate-100 text-slate-300 cursor-not-allowed'}`;
                prevBtn.innerText = '« Prev';
                prevBtn.disabled = this.currentPage <= 1;
                prevBtn.onclick = () => { if (this.currentPage > 1) { this.currentPage--; this.update(); } };
                btnsContainer.appendChild(prevBtn);

                for (let p = 1; p <= totalPages; p++) {
                    const pageBtn = document.createElement('button');
                    pageBtn.type = 'button';
                    pageBtn.className = `px-2.5 py-1 rounded-lg text-xs font-bold transition-colors ${p === this.currentPage ? 'bg-sage-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50'}`;
                    pageBtn.innerText = p;
                    pageBtn.onclick = () => { this.currentPage = p; this.update(); };
                    btnsContainer.appendChild(pageBtn);
                }

                const nextBtn = document.createElement('button');
                nextBtn.type = 'button';
                nextBtn.className = `px-2.5 py-1 rounded-lg border text-xs font-bold transition-colors ${this.currentPage < totalPages ? 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50' : 'bg-slate-50 border-slate-100 text-slate-300 cursor-not-allowed'}`;
                nextBtn.innerText = 'Next »';
                nextBtn.disabled = this.currentPage >= totalPages;
                nextBtn.onclick = () => { if (this.currentPage < totalPages) { this.currentPage++; this.update(); } };
                btnsContainer.appendChild(nextBtn);
            }
        }
        if (typeof updateBatchDeleteBar === 'function') {
            updateBatchDeleteBar();
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const navButtons = document.querySelectorAll('.nav-tab-btn');
    navButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const tabId = btn.getAttribute('data-tab');
            switchTab(tabId);
        });
    });

    const urlParams = new URLSearchParams(window.location.search);
    const initialTab = urlParams.get('tab') || 'dashboard';
    const initialId = urlParams.get('id');

    if (initialId) {
        const userMatch = (window.dbPengguna || []).find(u => String(u.id) === String(initialId));
        const katMatch = (window.dbKategori || []).find(k => String(k.id) === String(initialId));
        const rakMatch = (window.dbRak || []).find(r => String(r.id) === String(initialId));
        const brgMatch = (window.dbBarang || []).find(b => String(b.id) === String(initialId));
        const masukMatch = (window.dbBarangMasuk || []).find(m => String(m.id) === String(initialId));
        const keluarMatch = (window.dbBarangKeluar || []).find(k => String(k.id) === String(initialId));
        const pinjamMatch = (window.dbPeminjaman || []).find(p => String(p.id) === String(initialId));

        if (userMatch) {
            switchTab('pengguna');
            openModal('modalPengguna', 'Edit Data Pengguna', userMatch);
        } else if (katMatch) {
            switchTab('kategori');
            openModal('modalKategori', 'Edit Kategori Barang', katMatch);
        } else if (rakMatch) {
            switchTab('rak');
            openModal('modalRak', 'Edit Data Rak Penyimpanan', rakMatch);
        } else if (brgMatch) {
            switchTab('barang');
            openModal('modalBarang', 'Edit Data Barang', brgMatch);
        } else if (masukMatch) {
            switchTab('barang-masuk');
            openModal('modalBarangMasuk', 'Edit Transaksi Barang Masuk', masukMatch);
        } else if (keluarMatch) {
            switchTab('barang-keluar');
            openModal('modalBarangKeluar', 'Edit Transaksi Barang Keluar', keluarMatch);
        } else if (pinjamMatch) {
            switchTab('peminjaman');
            openModal('modalPeminjaman', 'Edit Transaksi Peminjaman', pinjamMatch);
        } else {
            switchTab(initialTab);
        }
    } else {
        switchTab(initialTab);
    }

    // Inisialisasi Pagination dan Live Search untuk seluruh tabel
    window.tablePaginators = window.tablePaginators || {};
    ['tablePengguna', 'tableJurusan', 'tableKategori', 'tableRak', 'tableBarang', 'tableBarangMasuk', 'tableBarangKeluar', 'tablePeminjaman', 'tableLogPeminjaman', 'tableLogAktivitas'].forEach(id => {
        window.tablePaginators[id] = new TablePaginationManager(id, 5);
    });
});

function filterTableLogAktivitas() {
    const paginator = window.tablePaginators && window.tablePaginators['tableLogAktivitas'];
    if (!paginator) return;

    paginator.customDateFilter = document.getElementById('filterLogDate')?.value || '';
    paginator.customTindakanFilter = (document.getElementById('filterLogTindakan')?.value || '').toUpperCase();
    paginator.customJurusanFilter = (document.getElementById('filterLogJurusan')?.value || '').toLowerCase();
    paginator.customNameFilter = (document.getElementById('filterLogName')?.value || '').toLowerCase().trim();

    paginator.currentPage = 1;
    paginator.update();
}

function resetLogFilters() {
    const elDate = document.getElementById('filterLogDate');
    const elTindakan = document.getElementById('filterLogTindakan');
    const elJurusan = document.getElementById('filterLogJurusan');
    const elName = document.getElementById('filterLogName');

    if (elDate) elDate.value = '';
    if (elTindakan) elTindakan.value = '';
    if (elJurusan) elJurusan.value = '';
    if (elName) elName.value = '';

    filterTableLogAktivitas();
}

function clearAllLogs() {
    showDeleteConfirm('seluruh riwayat log aktivitas', async () => {
        const formData = new FormData();
        formData.append('action', 'clear_log_aktivitas');
        const csrfInput = document.querySelector('input[name="csrf_token"]');
        if (csrfInput) formData.append('csrf_token', csrfInput.value);

        try {
            const res = await fetch('api.php', { method: 'POST', body: formData });
            const data = await res.json();
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 800);
            } else {
                showToast(data.message || 'Gagal menghapus log aktivitas.', 'error');
            }
        } catch (e) {
            showToast('Terjadi kesalahan server saat menghapus log.', 'error');
        }
    });
}
</script>

</body>
</html>
