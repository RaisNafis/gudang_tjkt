<?php
require_once __DIR__ . '/../../models/Pengguna.php';
require_once __DIR__ . '/../../models/Guru.php';
require_once __DIR__ . '/../../models/Siswa.php';
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
$dbGuru = Guru::getAll($currentJurusanId);
$dbSiswa = Siswa::getAll($currentJurusanId);
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
$totalGuruCount = count($dbGuru);
$totalSiswaCount = count($dbSiswa);
$totalJurusanCount = count($dbJurusan);
$totalKategoriCount = count($dbKategori);
$totalBarangCount = $stats['total_items'] ?? 0;
$totalStokTersedia = $stats['total_available'] ?? 0;

$cntAdminSekolah = 0;
$cntAdminJurusan = 0;
$cntPetugas = 0;
$cntGuruUmum = 0;
$cntSiswa = 0;
foreach ($dbPengguna as $pUser) {
    $r = $pUser['peran'] ?? 'siswa';
    if ($r === 'admin_sekolah') $cntAdminSekolah++;
    elseif ($r === 'admin_jurusan') $cntAdminJurusan++;
    elseif ($r === 'petugas') $cntPetugas++;
    elseif ($r === 'guru_umum') $cntGuruUmum++;
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

$daysIndo = ['Minggu', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
$monthsIndo = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
$todayFormatted = $daysIndo[(int)date('w')] . ', ' . (int)date('j') . ' ' . $monthsIndo[(int)date('n') - 1] . ' ' . date('Y');
?>

<div class="flex h-screen w-screen bg-slate-50/70 dark:bg-[#0a0a0a] font-sans overflow-hidden">
    
    <!-- Sidebar Navigation -->
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col h-full min-w-0 overflow-hidden">
        
        <!-- Top Bar Header: Mobile Hamburger, Page Title, User Profile & Controls -->
        <header class="bg-white dark:bg-[#121212] border-b border-slate-100 dark:border-[#262626] px-4 sm:px-6 py-2.5 sm:py-3 flex items-center justify-between shadow-xs flex-shrink-0 z-20 gap-3">
            <!-- UJUNG KIRI: Hamburger Menu Button (Mobile) + Text Title Halaman -->
            <div class="flex items-center gap-3 min-w-0 shrink-0">
                <button type="button" onclick="openMobileSidebar()" class="lg:hidden p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:text-sage-700 hover:bg-sage-100 dark:hover:bg-[#222222] transition-colors border border-sage-200 dark:border-[#262626] shrink-0" title="Buka Menu Sidebar">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <h2 class="text-base sm:text-xl font-bold text-slate-800 dark:text-white truncate" id="pageTitle">Dashboard Overview</h2>
            </div>

            <!-- UJUNG KANAN: Date, Theme Toggle, Sekolah Text & Simple Profile Dropdown -->
            <div class="flex items-center gap-3 sm:gap-4 shrink-0">
                <!-- Date in Navbar (Hanya Teks Saja) -->
                <span class="hidden xl:inline-block text-xs font-semibold text-slate-400 dark:text-slate-500">
                    <?= $todayFormatted; ?>
                </span>

                <!-- Theme Toggle (Fixed Icon Display) -->
                <button type="button" onclick="toggleTheme()" class="p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors flex items-center justify-center border border-slate-200/80 dark:border-[#262626]" title="Beralih Mode Gelap / Terang">
                    <svg class="themeSunIcon w-4 h-4 hidden text-amber-400 dark:text-amber-300" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <svg class="themeMoonIcon w-4 h-4 text-slate-600 dark:text-slate-300" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>

                <!-- School Text (Hanya Teks Saja) -->
                <span class="hidden 2xl:inline-block text-xs font-semibold text-slate-400 dark:text-slate-500">
                    SMK NEGERI 2 PANGKALPINANG
                </span>

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
                } elseif ($topRole === 'guru_umum') {
                    $topRoleText = 'Guru Umum';
                } else {
                    $topRoleText = 'Siswa';
                }
                ?>
                <!-- Top Header Profile Trigger (Hanya Teks Saja Tanpa Badge) -->
                <div class="relative" id="topHeaderProfileContainer">
                    <button type="button" id="topHeaderProfileBtn" onclick="toggleProfilePopover('topHeaderProfilePopover', 'topHeaderProfileArrow', event)" class="inline-flex items-center gap-1.5 py-1 px-2 text-left hover:bg-slate-100/80 dark:hover:bg-[#1a1a1a] rounded-xl transition-colors cursor-pointer group" title="Menu Profil">
                        <div class="text-left leading-tight">
                            <div class="text-xs font-bold text-slate-800 dark:text-white flex items-center gap-1">
                                <span><?= htmlspecialchars($user['nama_pengguna'] ?? 'admin'); ?></span>
                                <svg id="topHeaderProfileArrow" class="w-3 h-3 text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-200 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                            <div class="text-[10px] text-slate-400 dark:text-slate-500 font-medium"><?= htmlspecialchars($topRoleText); ?></div>
                        </div>
                    </button>

                    <!-- Simple Dropdown Popover (Linear / Gambar 4 Style) -->
                    <div id="topHeaderProfilePopover" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-[#141414] border border-slate-200 dark:border-[#262626] rounded-xl shadow-2xl z-50 p-1 text-slate-700 dark:text-slate-200 transition-all origin-top-right text-left">
                        <div class="space-y-0.5">
                            <button type="button" onclick="goToSettingsProfile()" class="w-full flex items-center justify-between px-3 py-2 text-xs font-medium text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-[#222222] rounded-lg transition-all text-left cursor-pointer group">
                                <span>Pengaturan Profil</span>
                                <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-slate-200 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            </button>
                            <button type="button" onclick="toggleTheme()" class="w-full flex items-center justify-between px-3 py-2 text-xs font-medium text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-[#222222] rounded-lg transition-all text-left cursor-pointer group">
                                <span>Beralih Tema</span>
                                <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-slate-200 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            </button>
                            <div class="border-t border-slate-100 dark:border-[#222222] my-0.5"></div>
                            <a href="logout.php" class="w-full flex items-center justify-between px-3 py-2 text-xs font-medium text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-lg transition-all text-left cursor-pointer group">
                                <span>Keluar (Logout)</span>
                                <svg class="w-3.5 h-3.5 text-rose-500/70 group-hover:text-rose-400 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            </a>
                        </div>
                    </div>
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
                
                <!-- TOP GREETING HEADER -->
                <div class="mb-1">
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-800 dark:text-white tracking-tight">Dashboard</h1>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5">Selamat datang, <?= htmlspecialchars($user['nama_lengkap'] ?? $user['nama_pengguna'] ?? 'Admin'); ?>! Berikut ringkasan data inventaris SMK Negeri 2 Pangkalpinang.</p>
                </div>

                <!-- Statistics Cards Row Live from MySQL Database -->
                <?php if ($isSuperAdmin): ?>
                    <div class="space-y-5">
                        <!-- ROW 1: Siswa, Guru, Jurusan, Users (4 Kolom) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                            <!-- TOTAL SISWA -->
                            <div class="bg-white dark:bg-[#161616] p-5 rounded-2xl border border-slate-100/90 dark:border-[#262626] shadow-[0_2px_12px_-4px_rgba(0,0,0,0.04)] dark:shadow-none hover:shadow-md transition-all flex items-center justify-between">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <div class="w-12 h-12 rounded-2xl bg-sky-50 dark:bg-sky-950/50 text-sky-500 dark:text-sky-400 flex items-center justify-center shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">TOTAL SISWA</span>
                                        <h3 class="text-2xl font-black text-slate-800 dark:text-white leading-tight mt-0.5">
                                            <span id="statSuperTotalSiswa"><?= number_format($totalSiswaCount); ?></span>
                                        </h3>
                                        <span class="block text-xs font-medium text-slate-400 mt-0.5">Siswa</span>
                                    </div>
                                </div>
                                <div class="shrink-0 ml-2 hidden sm:block">
                                    <svg class="w-14 h-8 text-sky-400/80 dark:text-sky-500/50" viewBox="0 0 64 32" fill="none">
                                        <path d="M2 24 C14 28, 22 10, 34 20 C46 30, 52 8, 62 14" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>

                            <!-- TOTAL GURU -->
                            <div class="bg-white dark:bg-[#161616] p-5 rounded-2xl border border-slate-100/90 dark:border-[#262626] shadow-[0_2px_12px_-4px_rgba(0,0,0,0.04)] dark:shadow-none hover:shadow-md transition-all flex items-center justify-between">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-500 dark:text-emerald-400 flex items-center justify-center shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">TOTAL GURU</span>
                                        <h3 class="text-2xl font-black text-slate-800 dark:text-white leading-tight mt-0.5">
                                            <span id="statSuperTotalGuru"><?= number_format($totalGuruCount); ?></span>
                                        </h3>
                                        <span class="block text-xs font-medium text-slate-400 mt-0.5">Guru</span>
                                    </div>
                                </div>
                                <div class="shrink-0 ml-2 hidden sm:block">
                                    <svg class="w-14 h-8 text-emerald-400/80 dark:text-emerald-500/50" viewBox="0 0 64 32" fill="none">
                                        <path d="M2 22 C12 26, 24 12, 34 18 C44 24, 52 6, 62 12" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>

                            <!-- TOTAL JURUSAN -->
                            <div class="bg-white dark:bg-[#161616] p-5 rounded-2xl border border-slate-100/90 dark:border-[#262626] shadow-[0_2px_12px_-4px_rgba(0,0,0,0.04)] dark:shadow-none hover:shadow-md transition-all flex items-center justify-between">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <div class="w-12 h-12 rounded-2xl bg-purple-50 dark:bg-purple-950/50 text-purple-500 dark:text-purple-400 flex items-center justify-center shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">TOTAL JURUSAN</span>
                                        <h3 class="text-2xl font-black text-slate-800 dark:text-white leading-tight mt-0.5">
                                            <span id="statSuperTotalJurusan"><?= number_format($totalJurusanCount); ?></span>
                                        </h3>
                                        <span class="block text-xs font-medium text-slate-400 mt-0.5">Jurusan</span>
                                    </div>
                                </div>
                                <div class="shrink-0 ml-2 hidden sm:block">
                                    <svg class="w-14 h-8 text-purple-400/80 dark:text-purple-500/50" viewBox="0 0 64 32" fill="none">
                                        <path d="M2 25 C14 27, 24 16, 36 22 C46 26, 52 10, 62 16" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>

                            <!-- TOTAL USERS -->
                            <div class="bg-white dark:bg-[#161616] p-5 rounded-2xl border border-slate-100/90 dark:border-[#262626] shadow-[0_2px_12px_-4px_rgba(0,0,0,0.04)] dark:shadow-none hover:shadow-md transition-all flex items-center justify-between">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <div class="w-12 h-12 rounded-2xl bg-orange-50 dark:bg-orange-950/50 text-orange-500 dark:text-orange-400 flex items-center justify-center shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">TOTAL USERS</span>
                                        <h3 class="text-2xl font-black text-slate-800 dark:text-white leading-tight mt-0.5">
                                            <span id="statSuperTotalUsers"><?= number_format($totalUsersCount); ?></span>
                                        </h3>
                                        <span class="block text-xs font-medium text-slate-400 mt-0.5">User</span>
                                    </div>
                                </div>
                                <div class="shrink-0 ml-2 hidden sm:block">
                                    <svg class="w-14 h-8 text-orange-400/80 dark:text-orange-500/50" viewBox="0 0 64 32" fill="none">
                                        <path d="M2 26 C12 28, 22 18, 34 24 C44 28, 54 10, 62 14" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- ROW 2: Kategori Barang, Total Alat & Bahan, Log Aktivitas, Stok Tersedia (4 Kolom) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                            <!-- KATEGORI BARANG -->
                            <div class="bg-white dark:bg-[#161616] p-5 rounded-2xl border border-slate-100/90 dark:border-[#262626] shadow-[0_2px_12px_-4px_rgba(0,0,0,0.04)] dark:shadow-none hover:shadow-md transition-all flex items-center justify-between">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-950/50 text-rose-500 dark:text-rose-400 flex items-center justify-center shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 11h.01M7 15h.01M11 7h8M11 11h8M11 15h8"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">KATEGORI BARANG</span>
                                        <h3 class="text-2xl font-black text-slate-800 dark:text-white leading-tight mt-0.5">
                                            <span id="statSuperTotalKategori"><?= number_format($totalKategoriCount); ?></span>
                                        </h3>
                                        <span class="block text-xs font-medium text-slate-400 mt-0.5">Kategori</span>
                                    </div>
                                </div>
                                <div class="shrink-0 ml-2 hidden sm:block">
                                    <svg class="w-14 h-8 text-rose-400/80 dark:text-rose-500/50" viewBox="0 0 64 32" fill="none">
                                        <path d="M2 24 C14 26, 26 14, 36 20 C48 26, 54 12, 62 16" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>

                            <!-- TOTAL ALAT & BAHAN -->
                            <div class="bg-white dark:bg-[#161616] p-5 rounded-2xl border border-slate-100/90 dark:border-[#262626] shadow-[0_2px_12px_-4px_rgba(0,0,0,0.04)] dark:shadow-none hover:shadow-md transition-all flex items-center justify-between">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <div class="w-12 h-12 rounded-2xl bg-teal-50 dark:bg-teal-950/50 text-teal-500 dark:text-teal-400 flex items-center justify-center shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">TOTAL ALAT & BAHAN</span>
                                        <h3 class="text-2xl font-black text-slate-800 dark:text-white leading-tight mt-0.5">
                                            <span id="statSuperTotalBarang"><?= number_format($totalBarangCount); ?></span>
                                        </h3>
                                        <span class="block text-xs font-medium text-slate-400 mt-0.5">Item</span>
                                    </div>
                                </div>
                                <div class="shrink-0 ml-2 hidden sm:block">
                                    <svg class="w-14 h-8 text-teal-400/80 dark:text-teal-500/50" viewBox="0 0 64 32" fill="none">
                                        <path d="M2 23 C12 26, 24 16, 36 21 C46 25, 54 8, 62 14" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>

                            <!-- LOG AKTIVITAS -->
                            <div class="bg-white dark:bg-[#161616] p-5 rounded-2xl border border-slate-100/90 dark:border-[#262626] shadow-[0_2px_12px_-4px_rgba(0,0,0,0.04)] dark:shadow-none hover:shadow-md transition-all flex items-center justify-between">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/50 text-amber-500 dark:text-amber-400 flex items-center justify-center shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">LOG AKTIVITAS</span>
                                        <h3 class="text-2xl font-black text-slate-800 dark:text-white leading-tight mt-0.5">
                                            <span id="statSuperTotalLogs"><?= number_format($totalLogsCount); ?></span>
                                        </h3>
                                        <span class="block text-xs font-medium text-slate-400 mt-0.5">Catatan</span>
                                    </div>
                                </div>
                                <div class="shrink-0 ml-2 hidden sm:block">
                                    <svg class="w-14 h-8 text-amber-400/80 dark:text-amber-500/50" viewBox="0 0 64 32" fill="none">
                                        <path d="M2 25 C14 28, 26 18, 38 22 C48 25, 54 14, 62 18" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>

                            <!-- STOK TERSEDIA -->
                            <div class="bg-white dark:bg-[#161616] p-5 rounded-2xl border border-slate-100/90 dark:border-[#262626] shadow-[0_2px_12px_-4px_rgba(0,0,0,0.04)] dark:shadow-none hover:shadow-md transition-all flex items-center justify-between">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <div class="w-12 h-12 rounded-2xl bg-sky-50 dark:bg-sky-950/50 text-sky-500 dark:text-sky-400 flex items-center justify-center shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">STOK TERSEDIA</span>
                                        <h3 class="text-2xl font-black text-slate-800 dark:text-white leading-tight mt-0.5">
                                            <span id="statSuperStokTersedia"><?= number_format($totalStokTersedia); ?></span>
                                        </h3>
                                        <span class="block text-xs font-medium text-slate-400 mt-0.5">Unit</span>
                                    </div>
                                </div>
                                <div class="shrink-0 ml-2 hidden sm:block">
                                    <svg class="w-14 h-8 text-sky-400/80 dark:text-sky-500/50" viewBox="0 0 64 32" fill="none">
                                        <path d="M2 26 C12 28, 22 16, 34 22 C46 28, 52 10, 62 15" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- ROW 3: Belum Dikembalikan, Sudah Dikembalikan (2 Kolom) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <!-- BELUM DIKEMBALIKAN -->
                            <div class="bg-white dark:bg-[#161616] p-5 rounded-2xl border border-slate-100/90 dark:border-[#262626] shadow-[0_2px_12px_-4px_rgba(0,0,0,0.04)] dark:shadow-none hover:shadow-md transition-all flex items-center justify-between">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <div class="w-12 h-12 rounded-2xl bg-violet-50 dark:bg-violet-950/50 text-violet-500 dark:text-violet-400 flex items-center justify-center shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">BELUM DIKEMBALIKAN</span>
                                        <h3 class="text-2xl font-black text-slate-800 dark:text-white leading-tight mt-0.5">
                                            <span id="statSuperBelumKembali"><?= number_format($pinjamBelumKembali); ?></span>
                                        </h3>
                                        <span class="block text-xs font-medium text-slate-400 mt-0.5">Transaksi</span>
                                    </div>
                                </div>
                                <div class="shrink-0 ml-2 hidden sm:block">
                                    <svg class="w-14 h-8 text-violet-400/80 dark:text-violet-500/50" viewBox="0 0 64 32" fill="none">
                                        <path d="M2 24 C14 27, 24 15, 36 21 C48 27, 54 12, 62 16" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>

                            <!-- SUDAH DIKEMBALIKAN -->
                            <div class="bg-white dark:bg-[#161616] p-5 rounded-2xl border border-slate-100/90 dark:border-[#262626] shadow-[0_2px_12px_-4px_rgba(0,0,0,0.04)] dark:shadow-none hover:shadow-md transition-all flex items-center justify-between">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-500 dark:text-emerald-400 flex items-center justify-center shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">SUDAH DIKEMBALIKAN</span>
                                        <h3 class="text-2xl font-black text-slate-800 dark:text-white leading-tight mt-0.5">
                                            <span id="statSuperSudahKembali"><?= number_format($pinjamSudahKembali); ?></span>
                                        </h3>
                                        <span class="block text-xs font-medium text-slate-400 mt-0.5">Transaksi</span>
                                    </div>
                                </div>
                                <div class="shrink-0 ml-2 hidden sm:block">
                                    <svg class="w-14 h-8 text-emerald-400/80 dark:text-emerald-500/50" viewBox="0 0 64 32" fill="none">
                                        <path d="M2 23 C12 26, 24 14, 36 20 C46 25, 54 9, 62 14" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php elseif (!empty($user['peran']) && $user['peran'] === 'siswa'): ?>
                    <div class="space-y-5">
                        <!-- Stat Cards Siswa: Belum Dikembalikan, Sudah Dikembalikan (2 Kolom) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="bg-white dark:bg-[#161616] p-5 rounded-2xl border border-slate-100/90 dark:border-[#262626] shadow-[0_2px_12px_-4px_rgba(0,0,0,0.04)] dark:shadow-none hover:shadow-md transition-all flex items-center justify-between">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <div class="w-12 h-12 rounded-2xl bg-violet-50 dark:bg-violet-950/50 text-violet-500 dark:text-violet-400 flex items-center justify-center shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">BELUM DIKEMBALIKAN</span>
                                        <h3 class="text-2xl font-black text-slate-800 dark:text-white leading-tight mt-0.5">
                                            <span id="statBelumKembali"><?= number_format($pinjamBelumKembali); ?></span>
                                        </h3>
                                        <span class="block text-xs font-medium text-slate-400 mt-0.5">Transaksi</span>
                                    </div>
                                </div>
                                <div class="shrink-0 ml-2 hidden sm:block">
                                    <svg class="w-14 h-8 text-violet-400/80 dark:text-violet-500/50" viewBox="0 0 64 32" fill="none">
                                        <path d="M2 24 C14 27, 24 15, 36 21 C48 27, 54 12, 62 16" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>

                            <div class="bg-white dark:bg-[#161616] p-5 rounded-2xl border border-slate-100/90 dark:border-[#262626] shadow-[0_2px_12px_-4px_rgba(0,0,0,0.04)] dark:shadow-none hover:shadow-md transition-all flex items-center justify-between">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-500 dark:text-emerald-400 flex items-center justify-center shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">SUDAH DIKEMBALIKAN</span>
                                        <h3 class="text-2xl font-black text-slate-800 dark:text-white leading-tight mt-0.5">
                                            <span id="statSudahKembali"><?= number_format($pinjamSudahKembali); ?></span>
                                        </h3>
                                        <span class="block text-xs font-medium text-slate-400 mt-0.5">Transaksi</span>
                                    </div>
                                </div>
                                <div class="shrink-0 ml-2 hidden sm:block">
                                    <svg class="w-14 h-8 text-emerald-400/80 dark:text-emerald-500/50" viewBox="0 0 64 32" fill="none">
                                        <path d="M2 23 C12 26, 24 14, 36 20 C46 25, 54 9, 62 14" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="space-y-5">
                        <!-- ROW 1: Siswa, Guru (2 Kolom) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="bg-white dark:bg-[#161616] p-5 rounded-2xl border border-slate-100/90 dark:border-[#262626] shadow-[0_2px_12px_-4px_rgba(0,0,0,0.04)] dark:shadow-none hover:shadow-md transition-all flex items-center justify-between">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <div class="w-12 h-12 rounded-2xl bg-sky-50 dark:bg-sky-950/50 text-sky-500 dark:text-sky-400 flex items-center justify-center shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">TOTAL SISWA</span>
                                        <h3 class="text-2xl font-black text-slate-800 dark:text-white leading-tight mt-0.5">
                                            <span id="statJurusanTotalSiswa"><?= number_format($totalSiswaCount); ?></span>
                                        </h3>
                                        <span class="block text-xs font-medium text-slate-400 mt-0.5">Siswa</span>
                                    </div>
                                </div>
                                <div class="shrink-0 ml-2 hidden sm:block">
                                    <svg class="w-14 h-8 text-sky-400/80 dark:text-sky-500/50" viewBox="0 0 64 32" fill="none">
                                        <path d="M2 24 C14 28, 22 10, 34 20 C46 30, 52 8, 62 14" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>

                            <div class="bg-white dark:bg-[#161616] p-5 rounded-2xl border border-slate-100/90 dark:border-[#262626] shadow-[0_2px_12px_-4px_rgba(0,0,0,0.04)] dark:shadow-none hover:shadow-md transition-all flex items-center justify-between">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-500 dark:text-emerald-400 flex items-center justify-center shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">TOTAL GURU</span>
                                        <h3 class="text-2xl font-black text-slate-800 dark:text-white leading-tight mt-0.5">
                                            <span id="statJurusanTotalGuru"><?= number_format($totalGuruCount); ?></span>
                                        </h3>
                                        <span class="block text-xs font-medium text-slate-400 mt-0.5">Guru</span>
                                    </div>
                                </div>
                                <div class="shrink-0 ml-2 hidden sm:block">
                                    <svg class="w-14 h-8 text-emerald-400/80 dark:text-emerald-500/50" viewBox="0 0 64 32" fill="none">
                                        <path d="M2 22 C12 26, 24 12, 34 18 C44 24, 52 6, 62 12" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- ROW 2: Kategori Barang, Total Barang, Log Aktivitas (3 Kolom) -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                            <div class="bg-white dark:bg-[#161616] p-5 rounded-2xl border border-slate-100/90 dark:border-[#262626] shadow-[0_2px_12px_-4px_rgba(0,0,0,0.04)] dark:shadow-none hover:shadow-md transition-all flex items-center justify-between">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-950/50 text-rose-500 dark:text-rose-400 flex items-center justify-center shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 11h.01M7 15h.01M11 7h8M11 11h8M11 15h8"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">KATEGORI BARANG</span>
                                        <h3 class="text-2xl font-black text-slate-800 dark:text-white leading-tight mt-0.5">
                                            <span id="statTotalKategori"><?= number_format($totalKategoriCount); ?></span>
                                        </h3>
                                        <span class="block text-xs font-medium text-slate-400 mt-0.5">Kategori</span>
                                    </div>
                                </div>
                                <div class="shrink-0 ml-2 hidden sm:block">
                                    <svg class="w-14 h-8 text-rose-400/80 dark:text-rose-500/50" viewBox="0 0 64 32" fill="none">
                                        <path d="M2 24 C14 26, 26 14, 36 20 C48 26, 54 12, 62 16" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>

                            <div class="bg-white dark:bg-[#161616] p-5 rounded-2xl border border-slate-100/90 dark:border-[#262626] shadow-[0_2px_12px_-4px_rgba(0,0,0,0.04)] dark:shadow-none hover:shadow-md transition-all flex items-center justify-between">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <div class="w-12 h-12 rounded-2xl bg-teal-50 dark:bg-teal-950/50 text-teal-500 dark:text-teal-400 flex items-center justify-center shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">TOTAL ALAT & BAHAN</span>
                                        <h3 class="text-2xl font-black text-slate-800 dark:text-white leading-tight mt-0.5">
                                            <span id="statTotalBarang"><?= number_format($totalBarangCount); ?></span>
                                        </h3>
                                        <span class="block text-xs font-medium text-slate-400 mt-0.5">Item</span>
                                    </div>
                                </div>
                                <div class="shrink-0 ml-2 hidden sm:block">
                                    <svg class="w-14 h-8 text-teal-400/80 dark:text-teal-500/50" viewBox="0 0 64 32" fill="none">
                                        <path d="M2 23 C12 26, 24 16, 36 21 C46 25, 54 8, 62 14" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>

                            <div class="bg-white dark:bg-[#161616] p-5 rounded-2xl border border-slate-100/90 dark:border-[#262626] shadow-[0_2px_12px_-4px_rgba(0,0,0,0.04)] dark:shadow-none hover:shadow-md transition-all flex items-center justify-between">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/50 text-amber-500 dark:text-amber-400 flex items-center justify-center shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">LOG AKTIVITAS</span>
                                        <h3 class="text-2xl font-black text-slate-800 dark:text-white leading-tight mt-0.5">
                                            <span id="statTotalLogs"><?= number_format($totalLogsCount); ?></span>
                                        </h3>
                                        <span class="block text-xs font-medium text-slate-400 mt-0.5">Catatan</span>
                                    </div>
                                </div>
                                <div class="shrink-0 ml-2 hidden sm:block">
                                    <svg class="w-14 h-8 text-amber-400/80 dark:text-amber-500/50" viewBox="0 0 64 32" fill="none">
                                        <path d="M2 25 C14 28, 26 18, 38 22 C48 25, 54 14, 62 18" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- ROW 3: Belum Dikembalikan, Sudah Dikembalikan (2 Kolom) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="bg-white dark:bg-[#161616] p-5 rounded-2xl border border-slate-100/90 dark:border-[#262626] shadow-[0_2px_12px_-4px_rgba(0,0,0,0.04)] dark:shadow-none hover:shadow-md transition-all flex items-center justify-between">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <div class="w-12 h-12 rounded-2xl bg-violet-50 dark:bg-violet-950/50 text-violet-500 dark:text-violet-400 flex items-center justify-center shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">BELUM DIKEMBALIKAN</span>
                                        <h3 class="text-2xl font-black text-slate-800 dark:text-white leading-tight mt-0.5">
                                            <span id="statBelumKembali"><?= number_format($pinjamBelumKembali); ?></span>
                                        </h3>
                                        <span class="block text-xs font-medium text-slate-400 mt-0.5">Transaksi</span>
                                    </div>
                                </div>
                                <div class="shrink-0 ml-2 hidden sm:block">
                                    <svg class="w-14 h-8 text-violet-400/80 dark:text-violet-500/50" viewBox="0 0 64 32" fill="none">
                                        <path d="M2 24 C14 27, 24 15, 36 21 C48 27, 54 12, 62 16" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>

                            <div class="bg-white dark:bg-[#161616] p-5 rounded-2xl border border-slate-100/90 dark:border-[#262626] shadow-[0_2px_12px_-4px_rgba(0,0,0,0.04)] dark:shadow-none hover:shadow-md transition-all flex items-center justify-between">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-500 dark:text-emerald-400 flex items-center justify-center shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">SUDAH DIKEMBALIKAN</span>
                                        <h3 class="text-2xl font-black text-slate-800 dark:text-white leading-tight mt-0.5">
                                            <span id="statSudahKembali"><?= number_format($pinjamSudahKembali); ?></span>
                                        </h3>
                                        <span class="block text-xs font-medium text-slate-400 mt-0.5">Transaksi</span>
                                    </div>
                                </div>
                                <div class="shrink-0 ml-2 hidden sm:block">
                                    <svg class="w-14 h-8 text-emerald-400/80 dark:text-emerald-500/50" viewBox="0 0 64 32" fill="none">
                                        <path d="M2 23 C12 26, 24 14, 36 20 C46 25, 54 9, 62 14" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Middle Row: 3 Columns Grid (Matches Image 1) -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
                    
                    <!-- Col 1 (5 Cols on xl): Grafik Sirkulasi & Stok Inventaris per Jurusan -->
                    <div class="lg:col-span-12 xl:col-span-5 bg-white dark:bg-[#161616] p-5 sm:p-6 rounded-3xl border border-slate-100/90 dark:border-[#262626] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] dark:shadow-none flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-4">
                                <div>
                                    <h3 class="text-sm sm:text-base font-bold text-slate-800 dark:text-white leading-snug">
                                        <?= $isSuperAdmin ? 'Grafik Sirkulasi & Stok Inventaris per Jurusan' : 'Grafik Sirkulasi Inventaris & Peminjaman'; ?>
                                    </h3>
                                    <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5">
                                        <?= $isSuperAdmin ? 'Perbandingan Alat & Bahan Masuk, Bahan Keluar, dan Peminjaman Alat per Jurusan' : 'Perbandingan Alat & Bahan Masuk, Bahan Keluar, dan Peminjaman Alat per Bulan'; ?>
                                    </p>
                                </div>
                                <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 shrink-0">Tahun <?= date('Y'); ?></span>
                            </div>

                            <div class="relative h-64 w-full mt-2">
                                <canvas id="inventoryColumnChart"></canvas>
                            </div>
                        </div>

                        <!-- Custom Legend below Bar Chart -->
                        <div class="flex items-center justify-center gap-3 sm:gap-4 pt-3 mt-3 border-t border-slate-100 dark:border-[#262626] text-xs font-semibold text-slate-600 dark:text-slate-300 flex-wrap">
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-xl bg-slate-50/80 dark:bg-[#1a1a1a] border border-slate-100 dark:border-[#262626]">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block shadow-2xs"></span> Alat & Bahan Masuk
                            </span>
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-xl bg-slate-50/80 dark:bg-[#1a1a1a] border border-slate-100 dark:border-[#262626]">
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-500 inline-block shadow-2xs"></span> Bahan Keluar
                            </span>
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-xl bg-slate-50/80 dark:bg-[#1a1a1a] border border-slate-100 dark:border-[#262626]">
                                <span class="w-2.5 h-2.5 rounded-full bg-sky-500 inline-block shadow-2xs"></span> Peminjaman
                            </span>
                        </div>
                    </div>

                    <!-- Col 2 (3 Cols on xl): Sebaran Total Stok per Jurusan (Donut Chart) -->
                    <div class="lg:col-span-6 xl:col-span-3 bg-white dark:bg-[#161616] p-5 sm:p-6 rounded-3xl border border-slate-100/90 dark:border-[#262626] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] dark:shadow-none flex flex-col justify-between">
                        <div>
                            <div class="mb-2">
                                <h3 class="text-sm sm:text-base font-bold text-slate-800 dark:text-white leading-snug truncate">
                                    <?= $isSuperAdmin ? 'Sebaran Total Stok per Jurusan' : 'Komposisi Kategori Barang'; ?>
                                </h3>
                                <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5 truncate">
                                    <?= $isSuperAdmin ? 'Persentase total stok inventaris' : 'Persentase jumlah stok per kategori'; ?>
                                </p>
                            </div>

                            <!-- Donut Canvas with Center Unit Overlay -->
                            <div class="relative h-48 w-full flex items-center justify-center my-3">
                                <canvas id="categoryPieChart"></canvas>
                                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none text-center select-none">
                                    <span id="donutTotalStokCenter" class="text-3xl font-black text-slate-900 dark:text-white tracking-tight leading-none">
                                        <?= number_format($totalStokTersedia); ?>
                                    </span>
                                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500 mt-1.5">Total Stok</span>
                                    <span class="text-[10px] font-bold text-sky-600 dark:text-sky-400 bg-sky-50 dark:bg-sky-950/60 px-2.5 py-0.5 rounded-full border border-sky-200/60 dark:border-sky-800/40 mt-1 shadow-2xs">Unit</span>
                                </div>
                            </div>
                        </div>

                        <!-- Dynamic Sleek Legend Container -->
                        <div id="donutLegendContainer" class="pt-3 border-t border-slate-100 dark:border-[#262626] text-xs">
                            <!-- Populated dynamically by initInventoryChart() -->
                        </div>
                    </div>

                    <!-- Col 3 (4 Cols on xl): Aktivitas Terbaru -->
                    <div class="lg:col-span-6 xl:col-span-4 bg-white dark:bg-[#161616] p-5 sm:p-6 rounded-3xl border border-slate-100/90 dark:border-[#262626] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] dark:shadow-none flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-sm sm:text-base font-bold text-slate-800 dark:text-white leading-snug">Aktivitas Terbaru</h3>
                                    <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5">5 aktivitas terakhir di sistem</p>
                                </div>
                                <button type="button" onclick="switchTab('log-aktivitas')" class="text-xs font-semibold text-sky-600 hover:text-sky-700 dark:text-sky-400 dark:hover:text-sky-300 transition-colors inline-flex items-center gap-1 shrink-0">
                                    <span>Lihat Semua</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </button>
                            </div>

                            <!-- List of 5 Recent Activities -->
                            <div id="recentActivitiesListContainer" class="divide-y divide-slate-100 dark:divide-slate-800/80">
                                <?php
                                $recentLogs = array_slice($dbLogAktivitas ?: [], 0, 5);
                                if (empty($recentLogs)):
                                ?>
                                    <div class="py-6 text-center text-xs text-slate-400 dark:text-slate-500 italic">
                                        Belum ada aktivitas yang tercatat di sistem
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($recentLogs as $act): ?>
                                        <?php
                                        $actTime = strtotime($act['created_at'] ?? 'now');
                                        $timeStr = date('H:i', $actTime);
                                        $mIdx = (int)date('n', $actTime) - 1;
                                        $dateStr = (int)date('j', $actTime) . ' ' . $monthsIndo[$mIdx] . ' ' . date('Y', $actTime);
                                        
                                        $rawTindakan = strtoupper(trim($act['tindakan'] ?? 'AKTIVITAS'));
                                        $actTitle = ucwords(strtolower(str_replace('_', ' ', $rawTindakan)));
                                        $actPengguna = $act['nama_pengguna'] ?? ($act['username'] ?? 'User');
                                        $actJurusan = $act['nama_jurusan'] ?? '';
                                        
                                        $shortJurusan = '';
                                        if (!empty($actJurusan) && $actJurusan !== '-') {
                                            $shortJurusan = (preg_match('/\(([^)]+)\)/', $actJurusan, $matches)) ? $matches[1] : $actJurusan;
                                        }
                                        
                                        $actDesc = $act['deskripsi'] ?? 'Aktivitas sistem';
                                        ?>
                                        <div class="py-2.5 first:pt-1 last:pb-0 flex items-center justify-between gap-3">
                                            <div class="min-w-0">
                                                <div class="flex items-center gap-1.5 flex-wrap min-w-0">
                                                    <h4 class="text-xs font-bold text-slate-800 dark:text-white truncate"><?= htmlspecialchars($actTitle); ?></h4>
                                                    <span class="text-[10px] text-slate-400 dark:text-slate-600">•</span>
                                                    <span class="text-[11px] font-semibold text-emerald-600 dark:text-emerald-400 truncate"><?= htmlspecialchars($actPengguna); ?></span>
                                                    <?php if (!empty($shortJurusan)): ?>
                                                        <span class="text-[10px] text-slate-400 dark:text-slate-600">•</span>
                                                        <span class="text-[11px] font-medium text-slate-400 dark:text-slate-500 truncate"><?= htmlspecialchars($shortJurusan); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 truncate" title="<?= htmlspecialchars($actDesc); ?>"><?= htmlspecialchars($actDesc); ?></p>
                                            </div>
                                            <div class="text-right shrink-0">
                                                <span class="block text-xs font-semibold text-slate-700 dark:text-slate-300"><?= $timeStr; ?></span>
                                                <span class="block text-[10px] text-slate-400 dark:text-slate-500"><?= $dateStr; ?></span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Sirkulasi Peminjaman Alat Terbaru & Aktivitas Masuk Keluar (MySQL Live) -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <div class="lg:col-span-8 bg-white dark:bg-[#161616] rounded-3xl border border-slate-100/90 dark:border-[#262626] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] dark:shadow-none p-5 sm:p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3.5 mb-5">
                            <div>
                                <h3 class="text-base font-bold text-slate-800 dark:text-white">Sirkulasi Peminjaman Alat Terbaru</h3>
                                <p class="text-xs text-slate-400">Daftar transaksi peminjaman barang oleh siswa (MySQL Live)</p>
                            </div>
                            <button onclick="switchTab('peminjaman')" class="inline-flex items-center gap-1 text-xs font-bold text-sky-600 dark:text-sky-400 hover:underline">
                                <span>Lihat Semua</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        </div>
                        <div class="overflow-x-auto max-w-full w-full block align-middle rounded-xl border border-slate-100 dark:border-[#262626]">
                            <table id="tableDashboardRecentPeminjaman" class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                                <thead class="bg-slate-50 dark:bg-[#202020] text-slate-700 dark:text-slate-200 font-bold border-b border-slate-100 dark:border-[#262626]">
                                    <tr>
                                        <th class="py-3 px-4">Nama Barang</th>
                                        <th class="py-3 px-4">Jenis Barang</th>
                                        <th class="py-3 px-4">Siswa Peminjam</th>
                                        <th class="py-3 px-4">Guru Peminjam</th>
                                        <th class="py-3 px-4">Petugas</th>
                                        <th class="py-3 px-4">Jumlah</th>
                                        <th class="py-3 px-4">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                    <?php if (!empty($dbPeminjaman)): ?>
                                        <?php foreach (array_slice($dbPeminjaman, 0, 5) as $pm): ?>
                                            <tr class="hover:bg-slate-50/60 dark:hover:bg-[#222222]/40 transition-colors">
                                                <td class="py-3.5 px-4 font-bold text-slate-800 dark:text-white"><?= htmlspecialchars($pm['nama_barang']); ?></td>
                                                <td class="py-3.5 px-4 font-semibold capitalize text-slate-700 dark:text-slate-300"><?= htmlspecialchars(ucfirst($pm['jenis'] ?? 'Alat')); ?></td>
                                                <td class="py-3.5 px-4">
                                                    <?php if (!empty($pm['nama_peminjam'])): ?>
                                                        <div class="font-bold text-slate-800 dark:text-white"><?= htmlspecialchars($pm['nama_peminjam']); ?></div>
                                                        <?php if (!empty($pm['nisn'])): ?>
                                                            <span class="inline-flex items-center gap-1 font-mono text-[10px] text-slate-400"><span class="font-bold">NISN:</span> <?= htmlspecialchars($pm['nisn']); ?></span>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <span class="text-slate-400 font-normal">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="py-3.5 px-4 font-bold text-slate-800 dark:text-white"><?= htmlspecialchars($pm['guru_peminjam'] ?? '-'); ?></td>
                                                <td class="py-3.5 px-4 font-semibold text-slate-700 dark:text-slate-300"><?= htmlspecialchars($pm['nama_petugas'] ?? '-'); ?></td>
                                                <td class="py-3.5 px-4 font-semibold text-slate-800 dark:text-slate-200"><?= htmlspecialchars($pm['jumlah']); ?> <?= htmlspecialchars($pm['satuan'] ?? 'Unit'); ?></td>
                                                <td class="py-3.5 px-4">
                                                    <?php if ($pm['status'] === 'dipinjam'): ?>
                                                        <span class="font-extrabold text-amber-500 dark:text-amber-400">
                                                            Dipinjam
                                                        </span>
                                                    <?php elseif ($pm['status'] === 'pending'): ?>
                                                        <span class="font-extrabold text-sky-500 dark:text-sky-400 inline-flex items-center gap-1">
                                                            <svg class="w-3 h-3 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                            Pending
                                                        </span>
                                                    <?php elseif ($pm['status'] === 'ditolak'): ?>
                                                        <span class="font-extrabold text-rose-500 dark:text-rose-400">
                                                            Ditolak
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="font-extrabold text-emerald-500 dark:text-emerald-400">
                                                            Dikembalikan
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="7" class="py-4 text-center text-slate-400">Belum ada data peminjaman</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="lg:col-span-4 bg-white dark:bg-[#161616] rounded-3xl border border-slate-100/90 dark:border-[#262626] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] dark:shadow-none p-5 sm:p-6 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-base font-bold text-slate-800 dark:text-white">Aktivitas Masuk & Keluar</h3>
                                    <p class="text-xs text-slate-400">Catatan transaksi alat & bahan terbaru</p>
                                </div>
                            </div>
                            <?php 
                            $recentLogMasukKeluar = [];
                            foreach ($dbBarangMasuk as $m) {
                                $recentLogMasukKeluar[] = [
                                    'type' => 'masuk',
                                    'nama_barang' => $m['nama_barang'] ?? 'Barang',
                                    'jurusan_id' => $m['jurusan_id'] ?? null,
                                    'pihak' => $m['nama_petugas'] ?? 'Petugas Gudang',
                                    'jumlah' => $m['jumlah'] ?? 1,
                                    'satuan' => $m['satuan'] ?? 'Unit',
                                    'tanggal' => $m['tanggal_masuk'] ?? $m['created_at'] ?? date('Y-m-d H:i')
                                ];
                            }
                            foreach ($dbBarangKeluar as $k) {
                                $recentLogMasukKeluar[] = [
                                    'type' => 'keluar',
                                    'nama_barang' => $k['nama_barang'] ?? 'Barang',
                                    'jurusan_id' => $k['jurusan_id'] ?? null,
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
                            <div class="space-y-3" id="recentLogMasukKeluarContainer">
                                <?php if (!empty($recentLogMasukKeluar)): ?>
                                    <?php foreach ($recentLogMasukKeluar as $log): ?>
                                        <?php 
                                            $isMasuk = ($log['type'] === 'masuk');
                                        ?>
                                        <div class="p-3 sm:p-3.5 rounded-2xl bg-transparent border border-slate-200/80 dark:border-[#262626] hover:border-slate-300 dark:hover:border-[#383838] hover:bg-slate-50/40 dark:hover:bg-white/[0.02] flex items-center justify-between transition-all duration-200">
                                            <div class="flex items-center gap-3 min-w-0">
                                                <div class="w-9 h-9 rounded-xl <?= $isMasuk ? 'bg-emerald-500 text-white' : 'bg-amber-500 text-white'; ?> flex items-center justify-center text-xs font-bold shrink-0 shadow-xs">
                                                    <?php if ($isMasuk): ?>
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                                                    <?php else: ?>
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-xs font-bold line-clamp-1 text-slate-800 dark:text-slate-100"><?= htmlspecialchars($log['nama_barang']); ?></p>
                                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium truncate mt-0.5"><?= $isMasuk ? 'Petugas: ' : 'Untuk: '; ?><span class="text-slate-700 dark:text-slate-200 font-semibold"><?= htmlspecialchars($log['pihak']); ?></span></p>
                                                </div>
                                            </div>
                                            <div class="text-right shrink-0 ml-3">
                                                <span class="text-xs font-black <?= $isMasuk ? 'text-emerald-500 dark:text-emerald-400' : 'text-amber-500 dark:text-amber-400'; ?>">
                                                    <?= $isMasuk ? '+' : '-'; ?><?= htmlspecialchars($log['jumlah']); ?> <?= htmlspecialchars($log['satuan']); ?>
                                                </span>
                                                <p class="text-[10px] text-slate-400 dark:text-slate-500 font-medium mt-0.5"><?= date('d/m/H:i', strtotime($log['tanggal'])); ?></p>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="p-6 text-center text-slate-400 dark:text-slate-500 text-xs italic">
                                        Belum ada aktivitas alat & bahan masuk / keluar
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
                <!-- Stat Cards Row 1: Total User, Total Siswa, Total Guru (3 Kolom) -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-sage-200/80 dark:border-[#262626] shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total User</span>
                            <div class="w-10 h-10 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            </div>
                        </div>
                        <h3 id="statTabPenggunaTotal" class="text-2xl font-extrabold text-slate-800 dark:text-white"><?= number_format($totalUsersCount); ?> User</h3>
                    </div>

                    <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-sage-200/80 dark:border-[#262626] shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Siswa</span>
                            <div class="w-10 h-10 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                        </div>
                        <h3 id="statTabPenggunaTotalSiswa" class="text-2xl font-extrabold text-slate-800 dark:text-white"><?= number_format($totalSiswaCount); ?> Siswa</h3>
                    </div>

                    <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-sage-200/80 dark:border-[#262626] shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Guru</span>
                            <div class="w-10 h-10 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                            </div>
                        </div>
                        <h3 id="statTabPenggunaTotalGuru" class="text-2xl font-extrabold text-slate-800 dark:text-white"><?= number_format($totalGuruCount); ?> Guru</h3>
                    </div>
                </div>

                <!-- Stat Cards Row 2: Admin Sekolah, Admin Jurusan, Petugas Gudang, Guru Umum -->
                <div class="grid grid-cols-1 sm:grid-cols-2 <?= $isSuperAdmin ? 'lg:grid-cols-4' : 'lg:grid-cols-2'; ?> gap-5">
                    <?php if ($isSuperAdmin): ?>
                    <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-sage-200/80 dark:border-[#262626] shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Admin Sekolah</span>
                            <div class="w-10 h-10 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                        </div>
                        <h3 id="statPenggunaAdminSekolah" class="text-2xl font-extrabold text-slate-800 dark:text-white"><?= number_format($cntAdminSekolah); ?> Admin</h3>
                    </div>
                    <?php endif; ?>

                    <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-sage-200/80 dark:border-[#262626] shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Admin Jurusan</span>
                            <div class="w-10 h-10 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                        </div>
                        <h3 id="statPenggunaAdminJurusan" class="text-2xl font-extrabold text-slate-800 dark:text-white"><?= number_format($cntAdminJurusan); ?> Admin</h3>
                    </div>

                    <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-sage-200/80 dark:border-[#262626] shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Petugas Gudang</span>
                            <div class="w-10 h-10 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                        </div>
                        <h3 id="statPenggunaPetugas" class="text-2xl font-extrabold text-slate-800 dark:text-white"><?= number_format($cntPetugas); ?> Petugas</h3>
                    </div>

                    <?php if ($isSuperAdmin): ?>
                    <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-sage-200/80 dark:border-[#262626] shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Guru Umum</span>
                            <div class="w-10 h-10 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                        </div>
                        <h3 id="statPenggunaGuruUmum" class="text-2xl font-extrabold text-slate-800 dark:text-white"><?= number_format($cntGuruUmum); ?> Guru</h3>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Charts Row for Data Pengguna -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <div class="lg:col-span-6 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-sage-200/80 dark:border-[#262626] shadow-sm">
                        <h3 class="text-base font-bold text-slate-800 dark:text-white mb-1">Distribusi Pengguna per Peran (Role)</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">Jumlah Admin Sekolah, Admin Jurusan, Petugas, Guru Umum, dan Siswa</p>
                        <div class="relative h-64 w-full"><canvas id="userRoleChart"></canvas></div>
                    </div>
                    <div class="lg:col-span-6 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-sage-200/80 dark:border-[#262626] shadow-sm flex flex-col justify-between">
                        <div>
                            <h3 class="text-base font-bold text-slate-800 dark:text-white mb-1"><?= $isSuperAdmin ? 'Sebaran Pengguna per Jurusan' : 'Persentase Peran Pengguna'; ?></h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mb-3"><?= $isSuperAdmin ? 'Persentase dan jumlah anggota di setiap jurusan sekolah' : 'Proporsi Admin Jurusan, Petugas Gudang, dan Siswa'; ?></p>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-center flex-1">
                            <!-- Left: Pie Chart (5 cols) -->
                            <div class="sm:col-span-5 relative h-56 w-full flex items-center justify-center">
                                <canvas id="userJurusanChart"></canvas>
                            </div>
                            <!-- Right: Legend List (7 cols) -->
                            <div class="sm:col-span-7 overflow-y-auto pr-1 space-y-1" style="scrollbar-width: thin; max-height: 224px;" id="userJurusanLegendList">
                                <!-- Populated dynamically by JavaScript -->
                            </div>
                        </div>
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

                    <!-- Filter Controls Data Pengguna -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
                        <div class="relative">
                            <input type="text" id="filter_pengguna_search" oninput="debouncedRenderTablePengguna()" placeholder="Cari nama, email, kelas..." class="w-full pl-9 pr-3 py-2 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-[#2a2a2a] rounded-xl text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:border-sage-600 font-medium">
                            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <div>
                            <select id="filter_pengguna_tingkat_kelas" onchange="currentPenggunaPage=1; renderTablePengguna()" class="w-full px-3 py-2 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-[#2a2a2a] rounded-xl text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:border-sage-600 font-semibold">
                                <option value="">Semua Tingkat Kelas</option>
                                <option value="10">Kelas 10</option>
                                <option value="11">Kelas 11</option>
                                <option value="12">Kelas 12</option>
                            </select>
                        </div>
                        <div>
                            <select id="filter_pengguna_peran" onchange="currentPenggunaPage=1; renderTablePengguna()" class="w-full px-3 py-2 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-[#2a2a2a] rounded-xl text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:border-sage-600 font-semibold">
                                <option value="">Semua Peran</option>
                                <?php if ($isSuperAdmin): ?><option value="admin_sekolah">Admin Sekolah</option><?php endif; ?>
                                <option value="admin_jurusan">Admin Jurusan</option>
                                <option value="petugas">Petugas Gudang</option>
                                <option value="guru_umum">Guru Umum</option>
                                <option value="siswa">Siswa</option>
                            </select>
                        </div>
                        <div>
                            <?php if ($isSuperAdmin): ?>
                                <select id="filter_pengguna_jurusan" onchange="currentPenggunaPage=1; renderTablePengguna()" class="w-full px-3 py-2 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-[#2a2a2a] rounded-xl text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:border-sage-600 font-semibold">
                                    <option value="">Semua Jurusan</option>
                                    <option value="none">Tanpa Jurusan (Umum)</option>
                                    <?php foreach ($dbJurusan as $j): ?>
                                        <option value="<?= htmlspecialchars($j['id']); ?>"><?= htmlspecialchars($j['nama_jurusan']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else: ?>
                                <select id="filter_pengguna_jurusan" disabled class="w-full px-3 py-2 bg-slate-100 dark:bg-[#202020] border border-slate-200 dark:border-[#2a2a2a] rounded-xl text-xs text-slate-500 dark:text-slate-400 font-semibold cursor-not-allowed">
                                    <option value="<?= htmlspecialchars($user['jurusan_id'] ?? ''); ?>" selected><?= htmlspecialchars($user['nama_jurusan'] ?? 'Jurusan Saya'); ?></option>
                                </select>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="overflow-x-auto max-w-full w-full block align-middle rounded-xl border border-sage-100 dark:border-[#262626]">
                        <table id="tablePengguna" class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                            <thead class="bg-sage-50 dark:bg-slate-800/80 text-slate-700 dark:text-slate-300 font-bold border-b border-sage-200 dark:border-[#2a2a2a]">
                                <tr>
                                    <th class="py-3 px-3 w-10 text-center"><input type="checkbox" class="select-all-checkbox rounded accent-sage-600 cursor-pointer" onchange="toggleSelectAll(this)"></th>
                                    <th class="py-3 px-4 w-12 text-center">No</th>
                                    <th class="py-3 px-4">Nama Pengguna</th>
                                    <th class="py-3 px-4">Nama Lengkap</th>
                                    <th class="py-3 px-4">Email</th>
                                    <th class="py-3 px-4">Jurusan</th>
                                    <th class="py-3 px-4">Peran</th>
                                    <th class="py-3 px-4">Kelas</th>
                                    <th class="py-3 px-4">Nomor Telepon</th>
                                    <th class="py-3 px-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Controls Data Pengguna -->
                    <div id="pagination_pengguna" class="mt-4 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 dark:text-slate-400 pt-3 border-t border-slate-100 dark:border-[#262626] gap-3">
                        <div class="flex items-center gap-2.5">
                            <span>Tampilkan</span>
                            <select id="pengguna_per_page" onchange="changePenggunaPerPage(this.value)" class="px-2 py-1 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-[#2a2a2a] rounded-lg text-xs font-bold text-slate-700 dark:text-slate-300 focus:outline-none focus:border-sage-600">
                                <option value="10" selected>10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <span>data per halaman</span>
                        </div>
                        <div id="pengguna_pagination_info" class="font-medium text-slate-600 dark:text-slate-300 text-center sm:text-left">
                            Menampilkan 0 data
                        </div>
                        <div id="pengguna_pagination_btns" class="flex items-center gap-1 flex-wrap justify-center sm:justify-end">
                            <!-- Populated dynamically by renderPenggunaPaginationControls -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 2.1: TAB DATA GURU -->
            <div id="tab-guru" class="tab-content hidden space-y-6">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-sage-200/80 dark:border-[#262626] shadow-sm p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3.5 mb-5">
                        <div>
                            <h3 class="text-base font-bold text-slate-800 dark:text-white">Master Data Guru</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Kelola data guru pengajar (Bengkel / Umum)</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                            <button onclick="openModal('modalImportGuruCSV')" class="px-3.5 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-200 dark:border-[#2a2a2a] rounded-xl font-bold text-xs flex items-center gap-1.5 transition-colors" title="Import data dari berkas CSV">
                                <svg class="w-4 h-4 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12"/></svg>
                                <span>Import CSV</span>
                            </button>
                            <button onclick="exportTableToCSV('tableGuru', 'data_guru.csv')" class="px-3.5 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-200 dark:border-[#2a2a2a] rounded-xl font-bold text-xs flex items-center gap-1.5 transition-colors" title="Download data sebagai CSV">
                                <svg class="w-4 h-4 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Export CSV</span>
                            </button>
                            <button onclick="openModal('modalGuru', 'Tambah Data Guru Baru')" class="px-4 py-2 bg-sage-600 text-white rounded-xl font-bold text-xs shadow-md shadow-sage-600/20 hover:bg-sage-700 transition-colors">+ Tambah Guru</button>
                        </div>
                    </div>

                    <!-- Filter Controls Data Guru -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-4">
                        <div class="relative">
                            <input type="text" id="filter_guru_search" oninput="debouncedRenderTableGuru()" placeholder="Cari nama guru atau token..." class="w-full pl-9 pr-3 py-2 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-[#2a2a2a] rounded-xl text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:border-sage-600 font-medium">
                            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <div>
                            <?php if ($isSuperAdmin): ?>
                                <select id="filter_guru_mengajar" onchange="currentGuruPage=1; renderTableGuru()" class="w-full px-3 py-2 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-[#2a2a2a] rounded-xl text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:border-sage-600 font-semibold">
                                    <option value="">Semua Kategori Mengajar</option>
                                    <option value="bengkel">Guru Bengkel</option>
                                    <option value="umum">Guru Umum</option>
                                </select>
                            <?php else: ?>
                                <select id="filter_guru_mengajar" disabled class="w-full px-3 py-2 bg-slate-100 dark:bg-[#202020] border border-slate-200 dark:border-[#2a2a2a] rounded-xl text-xs text-slate-500 dark:text-slate-400 font-semibold cursor-not-allowed">
                                    <option value="bengkel" selected>Guru Bengkel (Kejuruan)</option>
                                </select>
                            <?php endif; ?>
                        </div>
                        <div>
                            <?php if ($isSuperAdmin): ?>
                                <select id="filter_guru_jurusan" onchange="currentGuruPage=1; renderTableGuru()" class="w-full px-3 py-2 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-[#2a2a2a] rounded-xl text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:border-sage-600 font-semibold">
                                    <option value="">Semua Jurusan</option>
                                    <option value="none">Guru Umum (Tidak Ada Jurusan)</option>
                                    <?php foreach ($dbJurusan as $j): ?>
                                        <option value="<?= htmlspecialchars($j['id']); ?>"><?= htmlspecialchars($j['nama_jurusan']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else: ?>
                                <select id="filter_guru_jurusan" disabled class="w-full px-3 py-2 bg-slate-100 dark:bg-[#202020] border border-slate-200 dark:border-[#2a2a2a] rounded-xl text-xs text-slate-500 dark:text-slate-400 font-semibold cursor-not-allowed">
                                    <option value="<?= htmlspecialchars($user['jurusan_id'] ?? ''); ?>" selected><?= htmlspecialchars($user['nama_jurusan'] ?? 'Jurusan Saya'); ?></option>
                                </select>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="overflow-x-auto max-w-full w-full block align-middle rounded-xl border border-sage-100 dark:border-[#262626]">
                        <table id="tableGuru" class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                            <thead class="bg-sage-50 dark:bg-slate-800/80 text-slate-700 dark:text-slate-300 font-bold border-b border-sage-200 dark:border-[#2a2a2a]">
                                <tr>
                                    <th class="py-3 px-3 w-10 text-center"><input type="checkbox" class="select-all-checkbox rounded accent-sage-600 cursor-pointer" onchange="toggleSelectAll(this)"></th>
                                    <th class="py-3 px-4 w-12 text-center">No</th>
                                    <th class="py-3 px-4">Nama Guru</th>
                                    <th class="py-3 px-4">Nama Pengguna</th>
                                    <th class="py-3 px-4">Token</th>
                                    <th class="py-3 px-4">Kategori Mengajar</th>
                                    <th class="py-3 px-4">Jurusan</th>
                                    <th class="py-3 px-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Controls Data Guru -->
                    <div id="pagination_guru" class="mt-4 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 dark:text-slate-400 pt-3 border-t border-slate-100 dark:border-[#262626] gap-3">
                        <div class="flex items-center gap-2.5">
                            <span id="guru_pagination_info" class="font-medium text-slate-600 dark:text-slate-300">Menampilkan 0 data</span>
                            <div class="flex items-center gap-1.5 ml-2">
                                <span class="text-[11px] text-slate-400">Tampilkan:</span>
                                <select id="guru_per_page" onchange="changeGuruPerPage(this.value)" class="bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-[#2a2a2a] rounded-lg px-2 py-1 text-xs font-semibold text-slate-700 dark:text-slate-200 focus:outline-none focus:border-sage-600 cursor-pointer">
                                    <option value="10" selected>10 / hal</option>
                                    <option value="25">25 / hal</option>
                                    <option value="50">50 / hal</option>
                                </select>
                            </div>
                        </div>
                        <div id="guru_pagination_btns" class="flex items-center gap-1 flex-wrap"></div>
                    </div>
                </div>
            </div>

            <!-- SECTION 2.2: TAB DATA SISWA -->
            <div id="tab-siswa" class="tab-content hidden space-y-6">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-sage-200/80 dark:border-[#262626] shadow-sm p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3.5 mb-5">
                        <div>
                            <h3 class="text-base font-bold text-slate-800 dark:text-white">Master Data Siswa</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Kelola data siswa</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                            <button onclick="openModal('modalImportSiswaCSV')" class="px-3.5 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-200 dark:border-[#2a2a2a] rounded-xl font-bold text-xs flex items-center gap-1.5 transition-colors" title="Import data dari berkas CSV">
                                <svg class="w-4 h-4 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12"/></svg>
                                <span>Import CSV</span>
                            </button>
                            <button onclick="exportTableToCSV('tableSiswa', 'data_siswa.csv')" class="px-3.5 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-200 dark:border-[#2a2a2a] rounded-xl font-bold text-xs flex items-center gap-1.5 transition-colors" title="Download data sebagai CSV">
                                <svg class="w-4 h-4 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Export CSV</span>
                            </button>
                            <button onclick="openModal('modalSiswa', 'Tambah Data Siswa Baru')" class="px-4 py-2 bg-sage-600 text-white rounded-xl font-bold text-xs shadow-md shadow-sage-600/20 hover:bg-sage-700 transition-colors">+ Tambah Siswa</button>
                        </div>
                    </div>

                    <!-- Filter Controls Data Siswa -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
                        <div class="relative">
                            <input type="text" id="filter_siswa_search" oninput="debouncedRenderTableSiswa()" placeholder="Cari nama, NISN, token, atau kelas..." class="w-full pl-9 pr-3 py-2 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-[#2a2a2a] rounded-xl text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:border-sage-600 font-medium">
                            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <div>
                            <select id="filter_siswa_tingkat_kelas" onchange="currentSiswaPage=1; renderTableSiswa()" class="w-full px-3 py-2 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-[#2a2a2a] rounded-xl text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:border-sage-600 font-semibold">
                                <option value="">Semua Tingkat Kelas</option>
                                <option value="10">Kelas 10</option>
                                <option value="11">Kelas 11</option>
                                <option value="12">Kelas 12</option>
                            </select>
                        </div>
                        <div>
                            <?php if ($isSuperAdmin): ?>
                                <select id="filter_siswa_jurusan" onchange="currentSiswaPage=1; renderTableSiswa()" class="w-full px-3 py-2 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-[#2a2a2a] rounded-xl text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:border-sage-600 font-semibold">
                                    <option value="">Semua Jurusan</option>
                                    <option value="none">Tidak Ada Jurusan (Umum)</option>
                                    <?php foreach ($dbJurusan as $j): ?>
                                        <option value="<?= htmlspecialchars($j['id']); ?>"><?= htmlspecialchars($j['nama_jurusan']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else: ?>
                                <select id="filter_siswa_jurusan" disabled class="w-full px-3 py-2 bg-slate-100 dark:bg-[#202020] border border-slate-200 dark:border-[#2a2a2a] rounded-xl text-xs text-slate-500 dark:text-slate-400 font-semibold cursor-not-allowed">
                                    <option value="<?= htmlspecialchars($user['jurusan_id'] ?? ''); ?>" selected><?= htmlspecialchars($user['nama_jurusan'] ?? 'Jurusan Saya'); ?></option>
                                </select>
                            <?php endif; ?>
                        </div>
                        <div>
                            <select id="filter_siswa_tahun" onchange="currentSiswaPage=1; renderTableSiswa()" class="w-full px-3 py-2 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-[#2a2a2a] rounded-xl text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:border-sage-600 font-semibold">
                                <option value="">Semua Tahun Ajaran</option>
                                <?php 
                                    $taList = array_values(array_unique(array_filter(array_map(fn($s) => $s['tahun_ajaran'] ?? '', $dbSiswa))));
                                    if (empty($taList)) $taList = ['2026/2027'];
                                    foreach ($taList as $ta): 
                                ?>
                                    <option value="<?= htmlspecialchars($ta); ?>"><?= htmlspecialchars($ta); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="overflow-x-auto max-w-full w-full block align-middle rounded-xl border border-sage-100 dark:border-[#262626]">
                        <table id="tableSiswa" class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                            <thead class="bg-sage-50 dark:bg-slate-800/80 text-slate-700 dark:text-slate-300 font-bold border-b border-sage-200 dark:border-[#2a2a2a]">
                                <tr>
                                    <th class="py-3 px-3 w-10 text-center"><input type="checkbox" class="select-all-checkbox rounded accent-sage-600 cursor-pointer" onchange="toggleSelectAll(this)"></th>
                                    <th class="py-3 px-4 w-12 text-center">No</th>
                                    <th class="py-3 px-4">NISN</th>
                                    <th class="py-3 px-4">Nama Siswa</th>
                                    <th class="py-3 px-4">Token</th>
                                    <th class="py-3 px-4">Kelas</th>
                                    <th class="py-3 px-4">Jurusan</th>
                                    <th class="py-3 px-4">Tahun Ajaran</th>
                                    <th class="py-3 px-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Controls Data Siswa -->
                    <div id="pagination_siswa" class="mt-4 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 dark:text-slate-400 pt-3 border-t border-slate-100 dark:border-[#262626] gap-3">
                        <div class="flex items-center gap-2.5">
                            <span id="siswa_pagination_info" class="font-medium text-slate-600 dark:text-slate-300">Menampilkan 0 data</span>
                            <div class="flex items-center gap-1.5 ml-2">
                                <span class="text-[11px] text-slate-400">Tampilkan:</span>
                                <select id="siswa_per_page" onchange="changeSiswaPerPage(this.value)" class="bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-[#2a2a2a] rounded-lg px-2 py-1 text-xs font-semibold text-slate-700 dark:text-slate-200 focus:outline-none focus:border-sage-600 cursor-pointer">
                                    <option value="10">10 / hal</option>
                                    <option value="25" selected>25 / hal</option>
                                    <option value="50">50 / hal</option>
                                    <option value="100">100 / hal</option>
                                </select>
                            </div>
                        </div>
                        <div id="siswa_pagination_btns" class="flex items-center gap-1 flex-wrap"></div>
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
                        <h3 id="statTabKategoriTotal" class="text-2xl font-extrabold text-slate-800"><?= number_format($totalKategoriCount); ?> Kategori</h3>
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
                        <h3 id="statTabRakTotal" class="text-2xl font-extrabold text-slate-800"><?= number_format(count($dbRak)); ?> Rak</h3>
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

            <!-- SECTION 4: TAB MASTER ALAT & BAHAN & BARCODE (MySQL Live) -->
            <div id="tab-barang" class="tab-content hidden space-y-6">
                <!-- Stat Cards Row for Barang -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-sage-200/80 dark:border-[#262626] shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Alat & Bahan</span>
                            <div class="w-10 h-10 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </div>
                        </div>
                        <h3 id="statTabBarangTotalItem" class="text-2xl font-extrabold text-slate-800 dark:text-white"><?= number_format($totalBarangCount); ?> Item</h3>
                    </div>
                </div>

                <?php if ($isSuperAdmin): ?>
                <!-- Charts Row for Barang -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <div class="lg:col-span-7 bg-white p-6 rounded-2xl border border-sage-200/80 shadow-sm">
                        <h3 class="text-base font-bold text-slate-800 mb-1">Total Stok Alat & Bahan per Jurusan</h3>
                        <p class="text-xs text-slate-500 mb-4">Total stok seluruh alat & bahan inventaris di masing-masing jurusan</p>
                        <div class="relative h-64 w-full"><canvas id="barangColumnChart"></canvas></div>
                    </div>
                    <div class="lg:col-span-5 bg-white p-6 rounded-2xl border border-sage-200/80 shadow-sm flex flex-col justify-between">
                        <div>
                            <h3 class="text-base font-bold text-slate-800 mb-1">Persentase Stok Alat & Bahan per Jurusan</h3>
                            <p class="text-xs text-slate-500 mb-4">Proporsi stok alat & bahan di seluruh jurusan</p>
                        </div>
                        <div class="relative h-56 w-full flex items-center justify-center"><canvas id="barangPieChart"></canvas></div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="bg-white rounded-2xl border border-sage-200/80 shadow-sm p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3.5 mb-5">
                        <div>
                            <h3 class="text-base font-bold text-slate-800">Alat & Bahan</h3>
                            <p class="text-xs text-slate-500">Kelola data alat & bahan, stok total/tersedia, dan kode barcode</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                            <button onclick="exportTableToCSV('tableBarang', 'master_alat_dan_bahan.csv')" class="px-3.5 py-2 bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200 rounded-xl font-bold text-xs flex items-center gap-1.5 transition-colors" title="Download data sebagai CSV">
                                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Export CSV</span>
                            </button>
                            <?php if (!empty($user['peran']) && $user['peran'] !== 'siswa'): ?>
                            <input type="file" id="directCsvFileInput" accept=".csv" class="hidden" onchange="handleDirectCsvImport(event)">
                            <button onclick="document.getElementById('directCsvFileInput').click()" class="px-3.5 py-2 bg-sage-50 text-sage-700 hover:bg-sage-100 border border-sage-200 rounded-xl font-bold text-xs flex items-center gap-1.5 transition-colors" title="Import data dari file CSV">+ Import CSV</button>
                            <button onclick="openModal('modalBarang', 'Tambah Alat & Bahan Baru')" class="px-4 py-2 bg-sage-600 text-white rounded-xl font-bold text-xs shadow-md shadow-sage-600/20 hover:bg-sage-700 transition-colors">+ Tambah Alat & Bahan Baru</button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Filter Controls Alat & Bahan -->
                    <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3 mb-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 <?= $isSuperAdmin ? 'xl:grid-cols-5' : ''; ?> gap-3 flex-1">
                            <div class="relative">
                                <input type="text" id="filter_barang_search" oninput="debouncedFilterTableBarang()" placeholder="Cari nama, merek, barcode..." class="w-full pl-9 pr-3 py-2 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-[#2a2a2a] rounded-xl text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:border-sage-600 font-medium">
                                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <div>
                                <select id="filter_barang_jenis" onchange="filterTableBarang()" class="w-full px-3 py-2 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-[#2a2a2a] rounded-xl text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:border-sage-600 font-semibold cursor-pointer">
                                    <option value="">Semua Jenis (Alat & Bahan)</option>
                                    <option value="alat">Alat</option>
                                    <option value="bahan">Bahan</option>
                                </select>
                            </div>
                            <div>
                                <select id="filter_barang_kategori" onchange="filterTableBarang()" class="w-full px-3 py-2 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-[#2a2a2a] rounded-xl text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:border-sage-600 font-semibold cursor-pointer">
                                    <option value="">Semua Kategori</option>
                                    <?php foreach ($dbKategori as $kat): ?>
                                        <option value="<?= htmlspecialchars(strval($kat['id'])); ?>"><?= htmlspecialchars($kat['nama_kategori']); ?></option>
                                    <?php endforeach; ?>
                                    <option value="__none__">Tanpa Kategori</option>
                                </select>
                            </div>
                            <div>
                                <select id="filter_barang_rak" onchange="filterTableBarang()" class="w-full px-3 py-2 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-[#2a2a2a] rounded-xl text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:border-sage-600 font-semibold cursor-pointer">
                                    <option value="">Semua Rak</option>
                                    <?php foreach ($dbRak as $rk): ?>
                                        <option value="<?= htmlspecialchars(strval($rk['id'])); ?>"><?= htmlspecialchars($rk['nama_rak'] . (!empty($rk['kategori_rak']) ? ' (' . $rk['kategori_rak'] . ')' : '')); ?></option>
                                    <?php endforeach; ?>
                                    <option value="__none__">Tanpa Rak</option>
                                </select>
                            </div>
                            <?php if ($isSuperAdmin): ?>
                            <div>
                                <select id="filter_barang_jurusan" onchange="onBarangJurusanFilterChange()" class="w-full px-3 py-2 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-[#2a2a2a] rounded-xl text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:border-sage-600 font-semibold cursor-pointer">
                                    <option value="">Semua Jurusan</option>
                                    <?php foreach ($dbJurusan as $j): ?>
                                        <option value="<?= htmlspecialchars(strval($j['id'])); ?>"><?= htmlspecialchars($j['nama_jurusan']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="flex items-center shrink-0">
                            <button type="button" onclick="resetBarangFilters()" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 border border-slate-200 dark:border-[#2a2a2a] rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 transition-colors flex items-center gap-1.5" title="Reset Semua Filter">
                                <svg class="w-3.5 h-3.5 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                <span>Reset Filter</span>
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto max-w-full w-full block align-middle rounded-xl border border-sage-100">
                        <table id="tableBarang" class="w-full text-left text-xs text-slate-600">
                            <thead class="bg-sage-50 text-slate-700 font-bold border-b border-sage-200">
                                <tr>
                                    <th class="py-3 px-3 w-10 text-center"><input type="checkbox" class="select-all-checkbox rounded accent-sage-600 cursor-pointer" onchange="toggleSelectAll(this)"></th>
                                    <th class="py-3 px-4 w-12 text-center">No</th>
                                    <th class="py-3 px-4">Nama Alat / Bahan</th>
                                    <th class="py-3 px-4">Jenis</th>
                                    <th class="py-3 px-4">Kategori</th>
                                    <th class="py-3 px-4">Rak</th>
                                    <?php if (!empty($user['peran']) && $user['peran'] === 'admin_sekolah'): ?><th class="py-3 px-4">Jurusan</th><?php endif; ?>
                                    <th class="py-3 px-4">Merek</th>
                                    <th class="py-3 px-4">Barcode</th>
                                    <th class="py-3 px-4">Stock Awal</th>
                                    <th class="py-3 px-4">Stock Tersedia</th>
                                    <th class="py-3 px-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php $no = 1; foreach ($dbBarang as $b): ?>
                                    <?php 
                                        $jColor = (!empty($b['jurusan_id']) && isset($jurusanColors[$b['jurusan_id']])) ? $jurusanColors[$b['jurusan_id']] : '#2E7D32';
                                    ?>
                                    <tr id="row-barang-<?= htmlspecialchars($b['id']); ?>" 
                                        data-jenis="<?= htmlspecialchars(strtolower($b['jenis'] ?? 'alat')); ?>"
                                        data-kategori-id="<?= htmlspecialchars(strval($b['kategori_id'] ?? '')); ?>"
                                        data-rak-id="<?= htmlspecialchars(strval($b['rak_id'] ?? '')); ?>"
                                        data-jurusan-id="<?= htmlspecialchars(strval($b['jurusan_id'] ?? '')); ?>"
                                        class="hover:bg-sage-50/50 transition-all duration-300">
                                        <td class="py-3.5 px-3 text-center"><input type="checkbox" class="row-checkbox rounded accent-sage-600 cursor-pointer" value="<?= htmlspecialchars($b['id']); ?>" onchange="updateBatchDeleteBar()"></td>
                                        <td class="py-3.5 px-4 text-center font-bold text-slate-500 row-number-cell"><?= $no++; ?></td>
                                        <td class="py-3.5 px-4 font-extrabold text-xs nama-barang-cell" style="color: <?= htmlspecialchars($jColor); ?>;"><?= htmlspecialchars($b['nama_barang']); ?></td>
                                        <td class="py-3.5 px-4 font-semibold text-slate-700"><?= ucfirst(htmlspecialchars($b['jenis'] ?? 'alat')); ?></td>
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
                                        <td class="py-3.5 px-4 font-bold"><?= htmlspecialchars($b['stok_awal'] ?? $b['stok_total']); ?> <?= htmlspecialchars($b['satuan'] ?? 'Unit'); ?></td>
                                        <td class="py-3.5 px-4 font-bold text-sage-600 whitespace-nowrap">
                                            <div class="whitespace-nowrap"><?= intval($b['stok_tersedia'] ?? 0); ?> <?= htmlspecialchars($b['satuan'] ?? 'Unit'); ?></div>
                                            <?php 
                                            $dipinjamCount = !empty($b['total_dipinjam']) ? intval($b['total_dipinjam']) : 0;
                                            $keluarCount = !empty($b['total_keluar']) ? intval($b['total_keluar']) : 0;
                                            if ($dipinjamCount > 0): 
                                            ?>
                                                <span class="block text-[9.5px] font-semibold text-amber-500 mt-0.5 whitespace-nowrap leading-tight"><?= $dipinjamCount; ?> <?= htmlspecialchars($b['satuan'] ?? 'Unit'); ?> dipinjam</span>
                                            <?php endif; ?>
                                            <?php if ($keluarCount > 0): ?>
                                                <span class="block text-[9.5px] font-semibold text-rose-500 dark:text-rose-400 mt-0.5 whitespace-nowrap leading-tight"><?= $keluarCount; ?> <?= htmlspecialchars($b['satuan'] ?? 'Unit'); ?> dikeluarkan</span>
                                            <?php endif; ?>
                                        </td>
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
                        <h3 id="statTabMasukTotal" class="text-2xl font-extrabold text-slate-800"><?= number_format(count($dbBarangMasuk)); ?> Transaksi</h3>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-sage-200/80 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Unit Masuk</span>
                            <div class="w-10 h-10 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                        </div>
                        <h3 id="statTabMasukTotalItem" class="text-2xl font-extrabold text-slate-800">+<?= number_format($totalUnitMasuk); ?> Unit</h3>
                    </div>
                </div>

                <?php if ($isSuperAdmin): ?>
                <!-- Charts Row for Barang Masuk -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <div class="lg:col-span-7 bg-white p-6 rounded-2xl border border-sage-200/80 shadow-sm">
                        <h3 class="text-base font-bold text-slate-800 mb-1">Alat & Bahan Masuk per Jurusan</h3>
                        <p class="text-xs text-slate-500 mb-4">Total unit alat & bahan yang diterima di masing-masing jurusan</p>
                        <div class="relative h-64 w-full"><canvas id="masukColumnChart"></canvas></div>
                    </div>
                    <div class="lg:col-span-5 bg-white p-6 rounded-2xl border border-sage-200/80 shadow-sm flex flex-col justify-between">
                        <div>
                            <h3 class="text-base font-bold text-slate-800 mb-1">Persentase Alat & Bahan Masuk per Jurusan</h3>
                            <p class="text-xs text-slate-500 mb-4">Proporsi penerimaan alat & bahan per jurusan</p>
                        </div>
                        <div class="relative h-56 w-full flex items-center justify-center"><canvas id="masukPieChart"></canvas></div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="bg-white rounded-2xl border border-sage-200/80 shadow-sm p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3.5 mb-5">
                        <div>
                            <h3 class="text-base font-bold text-slate-800">Transaksi Alat & Bahan Masuk</h3>
                            <p class="text-xs text-slate-500">Catatan pengadaan dan penerimaan alat & bahan</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                            <button onclick="exportTableToCSV('tableBarangMasuk', 'alat_bahan_masuk.csv')" class="px-3.5 py-2 bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200 rounded-xl font-bold text-xs flex items-center gap-1.5 transition-colors" title="Download data sebagai CSV">
                                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Export CSV</span>
                            </button>
                            <button onclick="openModal('modalBarangMasuk')" class="px-4 py-2 bg-sage-600 text-white rounded-xl font-bold text-xs shadow-md shadow-sage-600/20 hover:bg-sage-700 transition-colors">+ Catat Alat & Bahan Masuk</button>
                        </div>
                    </div>
                    <div class="overflow-x-auto max-w-full w-full block align-middle rounded-xl border border-sage-100">
                        <table id="tableBarangMasuk" class="w-full text-left text-xs text-slate-600">
                            <thead class="bg-sage-50 text-slate-700 font-bold border-b border-sage-200">
                                <tr>
                                    <th class="py-3 px-3 w-10 text-center"><input type="checkbox" class="select-all-checkbox rounded accent-sage-600 cursor-pointer" onchange="toggleSelectAll(this)"></th>
                                    <th class="py-3 px-4 w-12 text-center">No</th>
                                    <th class="py-3 px-4">Alat / Bahan</th>
                                    <?php if (!empty($user['peran']) && $user['peran'] === 'admin_sekolah'): ?><th class="py-3 px-4">Jurusan</th><?php endif; ?>
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
                        <h3 id="statTabKeluarTotal" class="text-2xl font-extrabold text-slate-800"><?= number_format(count($dbBarangKeluar)); ?> Transaksi</h3>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-sage-200/80 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Unit Keluar</span>
                            <div class="w-10 h-10 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7"/></svg>
                            </div>
                        </div>
                        <h3 id="statTabKeluarTotalItem" class="text-2xl font-extrabold text-slate-800">-<?= number_format($totalUnitKeluar); ?> Unit</h3>
                    </div>
                </div>

                <?php if ($isSuperAdmin): ?>
                <!-- Charts Row for Barang Keluar -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <div class="lg:col-span-7 bg-white p-6 rounded-2xl border border-sage-200/80 shadow-sm">
                        <h3 class="text-base font-bold text-slate-800 mb-1">Bahan Keluar per Jurusan</h3>
                        <p class="text-xs text-slate-500 mb-4">Total unit bahan yang dikeluarkan di masing-masing jurusan</p>
                        <div class="relative h-64 w-full"><canvas id="keluarColumnChart"></canvas></div>
                    </div>
                    <div class="lg:col-span-5 bg-white p-6 rounded-2xl border border-sage-200/80 shadow-sm flex flex-col justify-between">
                        <div>
                            <h3 class="text-base font-bold text-slate-800 mb-1">Persentase Bahan Keluar per Jurusan</h3>
                            <p class="text-xs text-slate-500 mb-4">Proporsi pengeluaran bahan per jurusan</p>
                        </div>
                        <div class="relative h-56 w-full flex items-center justify-center"><canvas id="keluarPieChart"></canvas></div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="bg-white rounded-2xl border border-sage-200/80 shadow-sm p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3.5 mb-5">
                        <div>
                            <h3 class="text-base font-bold text-slate-800">Transaksi Bahan Keluar</h3>
                            <p class="text-xs text-slate-500">Catatan pengeluaran bahan inventaris</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                            <button onclick="exportTableToCSV('tableBarangKeluar', 'bahan_keluar.csv')" class="px-3.5 py-2 bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200 rounded-xl font-bold text-xs flex items-center gap-1.5 transition-colors" title="Download data sebagai CSV">
                                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Export CSV</span>
                            </button>
                            <button onclick="openModal('modalBarangKeluar')" class="px-4 py-2 bg-sage-600 text-white rounded-xl font-bold text-xs shadow-md shadow-sage-600/20 hover:bg-sage-700 transition-colors">+ Catat Bahan Keluar</button>
                        </div>
                    </div>
                    <div class="overflow-x-auto max-w-full w-full block align-middle rounded-xl border border-sage-100">
                        <table id="tableBarangKeluar" class="w-full text-left text-xs text-slate-600">
                            <thead class="bg-sage-50 text-slate-700 font-bold border-b border-sage-200">
                                <tr>
                                    <th class="py-3 px-3 w-10 text-center"><input type="checkbox" class="select-all-checkbox rounded accent-sage-600 cursor-pointer" onchange="toggleSelectAll(this)"></th>
                                    <th class="py-3 px-4 w-12 text-center">No</th>
                                    <th class="py-3 px-4">Bahan</th>
                                    <?php if (!empty($user['peran']) && $user['peran'] === 'admin_sekolah'): ?><th class="py-3 px-4">Jurusan</th><?php endif; ?>
                                    <th class="py-3 px-4">Nama Penerima</th>
                                    <th class="py-3 px-4">Jumlah</th>
                                    <th class="py-3 px-4">Keterangan / Alasan</th>
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
                                        <td class="py-3.5 px-4"><?= htmlspecialchars($bk['catatan'] ?? '-'); ?></td>
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
                        <h3 id="statTabPinjamTotal" class="text-2xl font-extrabold text-slate-800"><?= number_format(count($dbPeminjaman)); ?> Transaksi</h3>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-sage-200/80 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Belum Dikembalikan</span>
                            <div class="w-10 h-10 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                        </div>
                        <h3 id="statTabPinjamBelumKembali" class="text-2xl font-extrabold text-slate-800"><?= number_format($pinjamBelumKembali); ?> Transaksi</h3>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-sage-200/80 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Sudah Dikembalikan</span>
                            <div class="w-10 h-10 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                        </div>
                        <h3 id="statTabPinjamSudahKembali" class="text-2xl font-extrabold text-slate-800"><?= number_format($pinjamSudahKembali); ?> Transaksi</h3>
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
                            <button onclick="openModal('modalPeminjaman', 'Tambah Transaksi Peminjaman')" class="px-4 py-2 bg-sage-600 text-white rounded-xl font-bold text-xs shadow-md shadow-sage-600/20 hover:bg-sage-700 transition-colors">+ Peminjaman</button>
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
                                    <th class="py-3 px-4">Guru Peminjam</th>
                                    <th class="py-3 px-4">Siswa Peminjam</th>
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
                                        <td class="py-3.5 px-4 font-bold text-slate-800 dark:text-white">
                                            <?= htmlspecialchars($pm['guru_peminjam'] ?? '-'); ?>
                                        </td>
                                        <td class="py-3.5 px-4">
                                            <?php if (!empty($pm['nama_peminjam'])): ?>
                                                <div class="font-bold text-slate-800 dark:text-white"><?= htmlspecialchars($pm['nama_peminjam']); ?></div>
                                                <?php if (!empty($pm['nisn'])): ?>
                                                    <span class="inline-flex items-center gap-1 font-mono text-[10px] text-slate-500"><span class="font-bold">NISN:</span> <?= htmlspecialchars($pm['nisn']); ?></span>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-slate-400 font-normal">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-3.5 px-4 font-semibold text-slate-700"><?= htmlspecialchars($pm['nama_petugas'] ?? '-'); ?></td>
                                        <?php
                                        $brgMatch = null;
                                        foreach ($dbBarang as $bItem) {
                                            if (strval($bItem['id']) === strval($pm['barang_id'])) {
                                                $brgMatch = $bItem;
                                                break;
                                            }
                                        }
                                        $satuanDisplay = !empty($brgMatch['satuan']) ? $brgMatch['satuan'] : (!empty($pm['satuan']) ? $pm['satuan'] : 'Unit');
                                        ?>
                                        <td class="py-3.5 px-4 font-semibold"><?= htmlspecialchars($pm['jumlah']); ?> <?= htmlspecialchars($satuanDisplay); ?></td>
                                        <td class="py-3.5 px-4 font-semibold text-slate-700"><?= htmlspecialchars($pm['tugas'] ?? '-'); ?></td>
                                        <td class="py-3.5 px-4 font-semibold text-sage-700"><?= !empty($pm['nama_peminjam']) ? htmlspecialchars($pm['tahun_ajaran'] ?? '2026/2027') : '-'; ?></td>
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
                            <h3 class="text-base font-bold text-slate-800">
                                Logs Riwayat Peminjaman Alat
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
                                    <th class="py-3 px-4">Guru Peminjam</th>
                                    <th class="py-3 px-4">Siswa Peminjam</th>
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
                                        <td class="py-3 px-4 font-bold text-slate-800 dark:text-white"><?= htmlspecialchars($logPm['guru_peminjam'] ?? '-'); ?></td>
                                        <td class="py-3 px-4">
                                            <?php if (!empty($logPm['nama_peminjam'])): ?>
                                                <span class="font-bold text-slate-800 dark:text-white"><?= htmlspecialchars($logPm['nama_peminjam']); ?></span>
                                                <?php if (!empty($logPm['nisn'])): ?>
                                                    <div class="text-[10px] text-slate-500 font-mono">NISN: <?= htmlspecialchars($logPm['nisn']); ?></div>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-slate-400 font-normal">-</span>
                                            <?php endif; ?>
                                        </td>
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
                        <h3 id="statTabLogAktivitasTotal" class="text-2xl font-extrabold text-slate-800"><?= number_format($totalLogsCount); ?> Catatan</h3>
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
                                        <td class="py-3.5 px-4 font-bold text-slate-800 dark:text-white"><?= htmlspecialchars($log['tindakan']); ?></td>
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
                    <form action="update_profile.php" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-[#161616] rounded-2xl border border-sage-200/80 dark:border-[#262626] shadow-sm p-6 space-y-6">
                        <input type="hidden" name="csrf_token" value="<?= getCsrfToken(); ?>">
                        
                        <!-- Header Title Profil -->
                        <div class="border-b border-sage-100 dark:border-[#262626] pb-4 mb-2 flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-slate-800 dark:text-white">Pengaturan Akun & Profil</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Perbarui informasi data diri dan kata sandi akun Anda</p>
                            </div>
                            <span class="text-xs font-bold text-slate-600 dark:text-slate-400 capitalize px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800">
                                <?= htmlspecialchars($user['peran'] ?? 'admin'); ?>
                            </span>
                        </div>

                        <div>
                            <h4 class="text-sm font-bold text-slate-800 dark:text-white pb-3 mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4 text-sage-600 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Data Informasi Akun
                            </h4>

                            <!-- Foto Profil Avatar Row -->
                            <div class="flex items-center gap-4 p-4 bg-sage-50/50 dark:bg-slate-900/50 rounded-2xl border border-sage-100 dark:border-[#262626] mb-5">
                                <div class="w-16 h-16 rounded-full bg-sage-600 text-white font-bold flex items-center justify-center text-xl shrink-0 shadow-md overflow-hidden border-2 border-white dark:border-[#2a2a2a]">
                                    <?php if (!empty($user['foto_url']) && file_exists(__DIR__ . '/../../../' . $user['foto_url'])): ?>
                                        <img id="profileAvatarPreview" src="<?= htmlspecialchars($user['foto_url']);  ?>" class="w-full h-full object-cover" alt="Avatar">
                                    <?php else: ?>
                                        <span id="profileAvatarInitial"><?= strtoupper(substr($user['nama_lengkap'] ?? $user['nama_pengguna'] ?? 'U', 0, 1)); ?></span>
                                        <img id="profileAvatarPreview" class="w-full h-full object-cover hidden" alt="Avatar" loading="lazy" decoding="async">
                                    <?php endif; ?>
                                </div>
                                <div class="flex-1">
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Unggah Foto Profil <span class="text-slate-400 font-normal">(Opsional)</span></label>
                                    <div class="flex items-center gap-3 flex-wrap">
                                        <input type="file" name="foto" accept="image/png, image/jpeg, image/jpg, image/webp" onchange="previewProfilePhoto(event)" class="text-xs text-slate-600 dark:text-slate-300 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-sage-600 file:text-white hover:file:bg-sage-700 transition-all cursor-pointer">
                                        <?php if (!empty($user['foto_url'])): ?>
                                            <button type="button" onclick="hapusFotoProfil()" class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl transition-all flex items-center gap-1.5 shadow-sm cursor-pointer" title="Hapus Foto Profil">
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
                                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1.5">Nama Pengguna (Username)</label>
                                    <input type="text" value="<?= htmlspecialchars($user['nama_pengguna'] ?? 'admin'); ?>" disabled class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-[#202020] border border-slate-200 dark:border-[#2a2a2a] rounded-xl text-xs font-semibold text-slate-500 dark:text-slate-400 cursor-not-allowed">
                                    <p class="text-[10px] text-slate-400 mt-1">*Nama pengguna tidak dapat diubah</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                                    <input type="text" name="nama_lengkap" required value="<?= htmlspecialchars($user['nama_lengkap'] ?? ''); ?>" class="w-full px-3.5 py-2.5 bg-sage-50/50 dark:bg-[#121212] border border-sage-200 dark:border-[#2a2a2a] rounded-xl text-xs font-semibold text-slate-800 dark:text-white focus:outline-none focus:border-sky-500 focus:bg-white dark:focus:bg-[#161616] transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Alamat Email</label>
                                    <div class="flex items-center gap-2">
                                        <input type="text" name="email_prefix" value="<?= htmlspecialchars($profEmailPrefix); ?>" placeholder="nama_email" class="w-1/2 px-3.5 py-2.5 bg-sage-50/50 dark:bg-[#121212] border border-sage-200 dark:border-[#2a2a2a] rounded-xl text-xs font-semibold text-slate-800 dark:text-white focus:outline-none focus:border-sky-500 focus:bg-white dark:focus:bg-[#161616] transition-all">
                                        <span class="text-slate-400 font-bold text-sm">@</span>
                                        <input type="text" name="email_domain" value="<?= htmlspecialchars($profEmailDomain); ?>" placeholder="smk2pangkalpinang.sch.id" class="w-1/2 px-3.5 py-2.5 bg-sage-50/50 dark:bg-[#121212] border border-sage-200 dark:border-[#2a2a2a] rounded-xl text-xs font-semibold text-slate-800 dark:text-white focus:outline-none focus:border-sky-500 focus:bg-white dark:focus:bg-[#161616] transition-all">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Nomor Telepon / WhatsApp</label>
                                    <input type="text" name="nomor_telepon" value="<?= htmlspecialchars($user['nomor_telepon'] ?? ''); ?>" placeholder="081234567890" class="w-full px-3.5 py-2.5 bg-sage-50/50 dark:bg-[#121212] border border-sage-200 dark:border-[#2a2a2a] rounded-xl text-xs font-semibold text-slate-800 dark:text-white focus:outline-none focus:border-sky-500 focus:bg-white dark:focus:bg-[#161616] transition-all">
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-sage-100 dark:border-[#262626]">
                            <h4 class="text-sm font-bold text-slate-800 dark:text-white pb-3 mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4 text-sage-600 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                Ubah Kata Sandi <span class="text-xs font-normal text-slate-400">(Biarkan kosong jika tidak ingin diubah)</span>
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Kata Sandi Lama</label>
                                    <input type="password" name="password_lama" placeholder="••••••••" class="w-full px-3.5 py-2.5 bg-sage-50/50 dark:bg-[#121212] border border-sage-200 dark:border-[#2a2a2a] rounded-xl text-xs text-slate-800 dark:text-white focus:outline-none focus:border-sky-500 focus:bg-white dark:focus:bg-[#161616] transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Kata Sandi Baru</label>
                                    <input type="password" name="password_baru" placeholder="••••••••" class="w-full px-3.5 py-2.5 bg-sage-50/50 dark:bg-[#121212] border border-sage-200 dark:border-[#2a2a2a] rounded-xl text-xs text-slate-800 dark:text-white focus:outline-none focus:border-sky-500 focus:bg-white dark:focus:bg-[#161616] transition-all">
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 flex justify-end">
                            <button type="submit" class="px-6 py-2.5 bg-sage-600 hover:bg-sage-700 dark:bg-sky-600 dark:hover:bg-sky-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-sage-600/25 dark:shadow-sky-600/20 transition-all flex items-center gap-2 cursor-pointer">
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
            <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl text-slate-800 dark:text-white shadow-sm border border-slate-200 dark:border-[#262626] flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
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
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-[#262626] rounded-3xl overflow-hidden shadow-sm">
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
window.dbGuru = <?= json_encode($dbGuru ?: []); ?>;
window.dbSiswa = <?= json_encode($dbSiswa ?: []); ?>;
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
window.jurusanColors = <?= json_encode($jurusanColors ?: []); ?>;
window.themePrimaryColor = <?= json_encode($activeThemePalette['600'] ?? '#eab308'); ?>;

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

    if (tableId === 'tableGuru') {
        dataset = window.dbGuru;
        tableSchema = ['id', 'nama_guru', 'token', 'mengajar', 'jurusan_id', 'nama_jurusan', 'created_at', 'updated_at'];
    } else if (tableId === 'tableSiswa') {
        dataset = window.dbSiswa;
        tableSchema = ['id', 'nisn', 'nama_siswa', 'nama_lengkap', 'token', 'kelas', 'jurusan_id', 'nama_jurusan', 'tahun_ajaran', 'created_at', 'updated_at'];
    } else if (tableId === 'tablePengguna') {
        dataset = window.dbPengguna;
        tableSchema = [
            'id', 'jurusan_id', 'nama_pengguna', 'nama_lengkap', 'email', 'peran', 'kelas', 'nisn', 'status_pengguna', 'token',
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
            'jumlah', 'tanggal_masuk', 'catatan', 'nama_petugas', 
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
            'nama_barang', 'guru_peminjam', 'nama_peminjam', 'nisn', 'jumlah', 'tanggal_pinjam', 'tenggat_kembali', 
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
        if (tableId === 'tablePengguna' && Array.isArray(window.filteredPengguna)) {
            items = window.filteredPengguna;
        } else {
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
    const gridColor = isDark ? 'rgba(148, 163, 184, 0.08)' : '#f1f5f9';

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
                            label: 'Alat & Bahan Masuk',
                            data: masukPerJur,
                            backgroundColor: '#10b981',
                            hoverBackgroundColor: '#059669',
                            borderRadius: 8,
                            maxBarThickness: 18,
                        },
                        {
                            label: 'Bahan Keluar',
                            data: keluarPerJur,
                            backgroundColor: '#f59e0b',
                            hoverBackgroundColor: '#d97706',
                            borderRadius: 8,
                            maxBarThickness: 18,
                        },
                        {
                            label: 'Peminjaman',
                            data: pinjamPerJur,
                            backgroundColor: '#3b82f6',
                            hoverBackgroundColor: '#2563eb',
                            borderRadius: 8,
                            maxBarThickness: 18,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: { duration: 1000, easing: 'easeOutQuart' },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: isDark ? '#1a1a1a' : '#ffffff',
                            titleColor: isDark ? '#ffffff' : '#0f172a',
                            bodyColor: isDark ? '#cbd5e1' : '#334155',
                            borderColor: isDark ? '#333333' : '#e2e8f0',
                            borderWidth: 1,
                            padding: 10,
                            cornerRadius: 12,
                            boxPadding: 4
                        }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { size: 10, weight: '600' }, color: labelColor } },
                        y: { grid: { color: gridColor }, ticks: { font: { size: 10 }, color: labelColor, precision: 0 }, beginAtZero: true }
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
                        { label: 'Alat & Bahan Masuk', data: masukMonthly, backgroundColor: '#10b981', hoverBackgroundColor: '#059669', borderRadius: 8, maxBarThickness: 16 },
                        { label: 'Bahan Keluar', data: keluarMonthly, backgroundColor: '#f59e0b', hoverBackgroundColor: '#d97706', borderRadius: 8, maxBarThickness: 16 },
                        { label: 'Peminjaman', data: pinjamMonthly, backgroundColor: '#3b82f6', hoverBackgroundColor: '#2563eb', borderRadius: 8, maxBarThickness: 16 }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: { duration: 1000, easing: 'easeOutQuart' },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: isDark ? '#1a1a1a' : '#ffffff',
                            titleColor: isDark ? '#ffffff' : '#0f172a',
                            bodyColor: isDark ? '#cbd5e1' : '#334155',
                            borderColor: isDark ? '#333333' : '#e2e8f0',
                            borderWidth: 1,
                            padding: 10,
                            cornerRadius: 12,
                            boxPadding: 4
                        }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { size: 10, weight: '600' }, color: labelColor } },
                        y: { grid: { color: gridColor }, ticks: { font: { size: 10 }, color: labelColor, precision: 0 }, beginAtZero: true }
                    }
                }
            });
        }
    }

    // 2. Donut Chart (Right Side) - Matches Image 1
    const ctxPie = document.getElementById('categoryPieChart');
    if (ctxPie) {
        if (categoryChart) categoryChart.destroy();
        const donutPalette = [
            '#3b82f6', // Electric Blue
            '#10b981', // Emerald
            '#8b5cf6', // Violet
            '#f59e0b', // Amber
            '#06b6d4', // Cyan
            '#ec4899', // Pink
            '#6366f1', // Indigo
            '#14b8a6', // Teal
            '#f97316', // Orange
            '#a855f7', // Purple
            '#e11d48', // Rose
            '#84cc16'  // Lime
        ];

        let rawList = [];

        if (isSuperAdmin && window.dbJurusan && window.dbJurusan.length > 0) {
            // Per Jurusan Stock Pie Chart for Admin Sekolah
            window.dbJurusan.forEach((j, idx) => {
                let name = j.nama_jurusan || j.kode_jurusan || 'Jurusan';
                const match = name.match(/\(([^)]+)\)/);
                const shortName = match ? match[1] : name;

                const totalStokJur = (window.dbBarang || [])
                    .filter(b => String(b.jurusan_id) === String(j.id) || b.nama_jurusan === j.nama_jurusan)
                    .reduce((acc, b) => acc + parseInt(b.stok_tersedia !== undefined ? b.stok_tersedia : (b.stok_total || 0)), 0);

                rawList.push({
                    label: shortName,
                    fullName: name,
                    value: totalStokJur,
                    color: (typeof getJurusanColor === 'function' ? getJurusanColor(name) : donutPalette[idx % donutPalette.length])
                });
            });
        } else {
            // Per Category Stock Pie Chart for Single Jurusan (Admin Jurusan, Petugas, Guru, Siswa)
            const categoryStockMap = {};

            // 1. Inisialisasi dari kategori yang telah terdaftar di jurusan ini
            (window.dbKategori || []).forEach(c => {
                const catName = (c.nama_kategori || '').trim();
                if (catName) {
                    categoryStockMap[catName] = 0;
                }
            });

            // 2. Agregasi stok barang riil milik jurusan ini berdasarkan kategori
            (window.dbBarang || []).forEach(b => {
                const stock = parseInt(b.stok_tersedia !== undefined ? b.stok_tersedia : (b.stok_total || 0)) || 0;
                let catName = (b.nama_kategori || '').trim();
                if (!catName && b.kategori_id) {
                    const foundCat = (window.dbKategori || []).find(c => String(c.id) === String(b.kategori_id));
                    if (foundCat && foundCat.nama_kategori) {
                        catName = foundCat.nama_kategori.trim();
                    }
                }
                if (!catName) {
                    catName = 'Umum';
                }
                categoryStockMap[catName] = (categoryStockMap[catName] || 0) + stock;
            });

            Object.entries(categoryStockMap).forEach(([lbl, val], idx) => {
                rawList.push({
                    label: lbl,
                    fullName: lbl,
                    value: val,
                    color: donutPalette[idx % donutPalette.length]
                });
            });
        }

        // Urutkan data dari stok terbanyak
        rawList.sort((a, b) => b.value - a.value);

        const totalStokSum = rawList.reduce((acc, item) => acc + item.value, 0);
        const activeItems = rawList.filter(item => item.value > 0);
        const zeroItems = rawList.filter(item => item.value === 0);
        const isEmptyData = activeItems.length === 0;

        let chartLabels = [];
        let chartData = [];
        let chartColors = [];

        if (isEmptyData) {
            chartLabels = ['Belum Ada Barang'];
            chartData = [1];
            chartColors = [isDark ? '#262626' : '#e2e8f0'];
        } else {
            // Hanya masukkan activeItems ke doughnut agar setiap slice memiliki bentuk yang proporsional & elegan
            activeItems.forEach((item, idx) => {
                chartLabels.push(item.label);
                chartData.push(item.value);
                const assignedColor = item.color && item.color !== '#2e7d32' ? item.color : donutPalette[idx % donutPalette.length];
                item.color = assignedColor;
                chartColors.push(assignedColor);
            });
        }

        categoryChart = new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: chartLabels,
                datasets: [{
                    data: chartData,
                    backgroundColor: chartColors,
                    hoverBackgroundColor: chartColors,
                    borderWidth: 2,
                    borderColor: isDark ? '#161616' : '#ffffff',
                    borderRadius: isEmptyData ? 0 : 8, // Rounded ends on each donut slice!
                    spacing: chartData.length > 1 && !isEmptyData ? 3 : 0,
                    hoverOffset: isEmptyData ? 0 : 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '74%',
                animation: {
                    animateScale: true,
                    animateRotate: true,
                    duration: 1100,
                    easing: 'easeOutQuart'
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        enabled: !isEmptyData,
                        backgroundColor: isDark ? '#1a1a1a' : '#ffffff',
                        titleColor: isDark ? '#ffffff' : '#0f172a',
                        bodyColor: isDark ? '#cbd5e1' : '#334155',
                        borderColor: isDark ? '#333333' : '#e2e8f0',
                        borderWidth: 1,
                        padding: 10,
                        cornerRadius: 12,
                        boxPadding: 4,
                        usePointStyle: true,
                        callbacks: {
                            label: function(context) {
                                const val = context.raw || 0;
                                const sum = totalStokSum || 1;
                                const pct = Math.round((val / sum) * 100);
                                return ` ${context.label}: ${val.toLocaleString()} Unit (${pct}%)`;
                            }
                        }
                    }
                }
            }
        });

        // Update Center Total Text
        const centerTotalEl = document.getElementById('donutTotalStokCenter');
        if (centerTotalEl) {
            centerTotalEl.innerText = Number(totalStokSum).toLocaleString();
        }

        // Render Modern Card-Based Legend with Mini Progress Bars
        const legendContainer = document.getElementById('donutLegendContainer');
        if (legendContainer) {
            if (isEmptyData) {
                legendContainer.innerHTML = `
                    <div class="text-center py-3.5 px-3 rounded-2xl bg-slate-50/60 dark:bg-[#1a1a1a]/60 border border-slate-100 dark:border-[#262626] text-xs text-slate-400 dark:text-slate-500">
                        <p class="font-bold text-slate-600 dark:text-slate-300">Belum ada barang</p>
                        <p class="text-[11px] mt-0.5">Stok inventaris belum tercatat</p>
                    </div>
                `;
            } else {
                let html = '<div class="space-y-2">';

                // 1. Render Active Items (dengan indikator warna, unit, persentase badge, dan progress bar halus)
                activeItems.forEach(item => {
                    const pct = totalStokSum > 0 ? Math.round((item.value / totalStokSum) * 100) : 0;
                    html += `
                        <div class="group p-2.5 rounded-2xl bg-slate-50/70 dark:bg-[#181818] border border-slate-100/90 dark:border-[#262626] hover:border-slate-200 dark:hover:border-[#333333] transition-all">
                            <div class="flex items-center justify-between gap-2 mb-1.5">
                                <div class="flex items-center gap-2 min-w-0">
                                    <span class="w-2.5 h-2.5 rounded-full shrink-0 shadow-2xs" style="background-color: ${item.color};"></span>
                                    <span class="font-bold text-xs text-slate-700 dark:text-slate-200 truncate" title="${item.fullName || item.label}">${item.label}</span>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <span class="text-[11px] font-semibold text-slate-400 dark:text-slate-400">${item.value.toLocaleString()} Unit</span>
                                    <span class="px-2 py-0.5 rounded-lg text-[10px] font-extrabold bg-white dark:bg-[#242424] text-slate-800 dark:text-slate-200 border border-slate-200/80 dark:border-[#333333] shadow-2xs">${pct}%</span>
                                </div>
                            </div>
                            <!-- Mini Sleek Progress Bar -->
                            <div class="w-full bg-slate-200/80 dark:bg-[#262626] h-1.5 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-700 ease-out" style="width: ${Math.max(pct, 2)}%; background-color: ${item.color};"></div>
                            </div>
                        </div>
                    `;
                });

                // 2. Render Inactive/Zero Items sebagai dropdown summary yang ringkas & rapi
                if (zeroItems.length > 0) {
                    html += `
                        <div class="pt-1">
                            <details class="group/zero">
                                <summary class="flex items-center justify-between text-[11px] font-semibold text-slate-400 dark:text-slate-500 cursor-pointer hover:text-slate-600 dark:hover:text-slate-300 transition-colors list-none py-1.5 px-2 rounded-xl hover:bg-slate-50 dark:hover:bg-[#1c1c1c]">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 transition-transform group-open/zero:rotate-90 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        <span>${zeroItems.length} Jurusan Lainnya</span>
                                    </span>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 dark:bg-[#222222] text-slate-400 dark:text-slate-500">0 Unit</span>
                                </summary>
                                <div class="grid grid-cols-2 gap-1.5 pt-2 pb-1 px-1">
                                    ${zeroItems.map(z => `
                                        <div class="flex items-center justify-between text-[11px] py-1 px-2 rounded-lg bg-slate-50/50 dark:bg-[#181818]/50 text-slate-400 dark:text-slate-500 border border-slate-100/50 dark:border-[#222222]/50">
                                            <span class="truncate" title="${z.fullName || z.label}">${z.label}</span>
                                            <span class="font-mono text-[10px]">0%</span>
                                        </div>
                                    `).join('')}
                                </div>
                            </details>
                        </div>
                    `;
                }

                html += '</div>';
                legendContainer.innerHTML = html;
            }
        }
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
            ? ['Admin Sekolah', 'Admin Jurusan', 'Petugas Gudang', 'Guru Umum', 'Siswa'] 
            : ['Admin Jurusan', 'Petugas Gudang', 'Siswa'];

        const roleData = isSuperAdmin
            ? [
                (window.dbPengguna || []).filter(u => u.peran === 'admin_sekolah').length,
                (window.dbPengguna || []).filter(u => u.peran === 'admin_jurusan').length,
                (window.dbPengguna || []).filter(u => u.peran === 'petugas').length,
                (window.dbPengguna || []).filter(u => u.peran === 'guru_umum').length,
                (window.dbPengguna || []).filter(u => u.peran === 'siswa').length
              ]
            : [
                (window.dbPengguna || []).filter(u => u.peran === 'admin_jurusan').length,
                (window.dbPengguna || []).filter(u => u.peran === 'petugas').length,
                (window.dbPengguna || []).filter(u => u.peran === 'siswa').length
              ];

        const roleColors = isSuperAdmin
            ? ['#2563eb', '#eab308', '#059669', '#f97316', '#7c3aed']
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
                        borderRadius: 8,
                        maxBarThickness: 48
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
            let labels, data, bgColors, legendItems = [];

            if (isSuperAdmin) {
                // Hitung pengguna per jurusan dengan nama lengkap
                const countsPerJur = {};
                (window.dbPengguna || []).forEach(u => {
                    const fullName = u.nama_jurusan || 'Admin Sekolah';
                    countsPerJur[fullName] = (countsPerJur[fullName] || 0) + 1;
                });

                // Urutkan dari jumlah user terbanyak
                const sortedNames = Object.keys(countsPerJur).sort((a, b) => countsPerJur[b] - countsPerJur[a]);

                labels = sortedNames.map(name => {
                    const match = name.match(/\(([^)]+)\)/);
                    return match ? match[1] : name;
                });
                data = sortedNames.map(name => countsPerJur[name]);
                bgColors = getColorsForLabels(labels);

                legendItems = sortedNames.map((name, idx) => ({
                    fullName: name,
                    count: countsPerJur[name],
                    color: bgColors[idx]
                }));
            } else {
                labels = ['Admin Jurusan', 'Petugas Gudang', 'Siswa'];
                data = [
                    (window.dbPengguna || []).filter(u => u.peran === 'admin_jurusan').length,
                    (window.dbPengguna || []).filter(u => u.peran === 'petugas').length,
                    (window.dbPengguna || []).filter(u => u.peran === 'siswa').length
                ];
                bgColors = ['#d97706', '#059669', '#7c3aed'];

                legendItems = labels.map((name, idx) => ({
                    fullName: name,
                    count: data[idx],
                    color: bgColors[idx]
                }));
            }

            // Render list data di samping kanan pie chart
            const legendContainer = document.getElementById('userJurusanLegendList');
            if (legendContainer) {
                legendContainer.innerHTML = legendItems.map(item => `
                    <div class="flex items-center justify-between py-1.5 px-2.5 rounded-xl hover:bg-slate-100/70 dark:hover:bg-[#222222] transition-colors text-xs border border-transparent hover:border-slate-200 dark:hover:border-[#333333]">
                        <div class="flex items-center gap-2 min-w-0 pr-2">
                            <span class="w-2.5 h-2.5 rounded-full shrink-0 shadow-sm" style="background-color: ${item.color}"></span>
                            <span class="font-medium text-slate-700 dark:text-slate-200 truncate" title="${escapeHtml(item.fullName)}">
                                ${escapeHtml(item.fullName)}
                            </span>
                        </div>
                        <span class="font-semibold text-slate-800 dark:text-slate-200 shrink-0 ml-1">
                            ${item.count.toLocaleString('en-US')}
                        </span>
                    </div>
                `).join('');
            }

            tabAnalyticsCharts['userJurusan'] = new Chart(ctxUserJur, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: bgColors,
                        hoverBackgroundColor: bgColors,
                        borderWidth: 2,
                        borderColor: isDark ? '#0f172a' : '#ffffff',
                        borderRadius: 8,
                        spacing: 3,
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    transitions: { active: { animation: { duration: 300, easing: 'easeOutCubic' } } },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                title: function() {
                                    return '';
                                },
                                label: function(context) {
                                    const shortLabel = context.label || '';
                                    const val = Number(context.raw || 0).toLocaleString('en-US');
                                    const total = data.reduce((a, b) => a + b, 0);
                                    const pct = total > 0 ? Math.round(((context.raw || 0) / total) * 100) : 0;
                                    return ` ${shortLabel}: ${val} (${pct}%)`;
                                }
                            }
                        }
                    }
                }
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
                data: { labels: labels, datasets: [{ label: 'Catatan Log', data: data, backgroundColor: bgColors, borderRadius: 8, maxBarThickness: 48 }] },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { ticks: { color: labelColor } }, y: { ticks: { color: labelColor, precision: 0 } } } }
            });
        }
        if (ctxPie) {
            destroyChart('logPie');
            tabAnalyticsCharts['logPie'] = new Chart(ctxPie, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: bgColors,
                        hoverBackgroundColor: bgColors,
                        borderWidth: 2,
                        borderColor: isDark ? '#0f172a' : '#ffffff',
                        borderRadius: 8,
                        spacing: 3,
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    transitions: { active: { animation: { duration: 300, easing: 'easeOutCubic' } } },
                    plugins: { legend: { position: 'bottom', labels: { color: labelColor } } }
                }
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
                data: { labels: labels, datasets: [{ label: 'Kategori', data: data, backgroundColor: bgColors, borderRadius: 8, maxBarThickness: 48 }] },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { ticks: { color: labelColor } }, y: { ticks: { color: labelColor, precision: 0 } } } }
            });
        }
        if (ctxPie) {
            destroyChart('katPie');
            tabAnalyticsCharts['katPie'] = new Chart(ctxPie, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: bgColors,
                        hoverBackgroundColor: bgColors,
                        borderWidth: 2,
                        borderColor: isDark ? '#0f172a' : '#ffffff',
                        borderRadius: 8,
                        spacing: 3,
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    transitions: { active: { animation: { duration: 300, easing: 'easeOutCubic' } } },
                    plugins: { legend: { position: 'bottom', labels: { color: labelColor } } }
                }
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
                data: { labels: labels, datasets: [{ label: 'Total Stok', data: data, backgroundColor: bgColors, borderRadius: 8, maxBarThickness: 48 }] },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { ticks: { color: labelColor } }, y: { ticks: { color: labelColor, precision: 0 } } } }
            });
        }
        if (ctxPie) {
            destroyChart('brgPie');
            tabAnalyticsCharts['brgPie'] = new Chart(ctxPie, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: bgColors,
                        hoverBackgroundColor: bgColors,
                        borderWidth: 2,
                        borderColor: isDark ? '#0f172a' : '#ffffff',
                        borderRadius: 8,
                        spacing: 3,
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    transitions: { active: { animation: { duration: 300, easing: 'easeOutCubic' } } },
                    plugins: { legend: { position: 'bottom', labels: { color: labelColor } } }
                }
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
                data: { labels: labels, datasets: [{ label: 'Unit Masuk', data: data, backgroundColor: bgColors, borderRadius: 8, maxBarThickness: 48 }] },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { ticks: { color: labelColor } }, y: { ticks: { color: labelColor, precision: 0 } } } }
            });
        }
        if (ctxPie) {
            destroyChart('masukPie');
            tabAnalyticsCharts['masukPie'] = new Chart(ctxPie, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: bgColors,
                        hoverBackgroundColor: bgColors,
                        borderWidth: 2,
                        borderColor: isDark ? '#0f172a' : '#ffffff',
                        borderRadius: 8,
                        spacing: 3,
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    transitions: { active: { animation: { duration: 300, easing: 'easeOutCubic' } } },
                    plugins: { legend: { position: 'bottom', labels: { color: labelColor } } }
                }
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
                data: { labels: labels, datasets: [{ label: 'Unit Keluar', data: data, backgroundColor: bgColors, borderRadius: 8, maxBarThickness: 48 }] },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { ticks: { color: labelColor } }, y: { ticks: { color: labelColor, precision: 0 } } } }
            });
        }
        if (ctxPie) {
            destroyChart('keluarPie');
            tabAnalyticsCharts['keluarPie'] = new Chart(ctxPie, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: bgColors,
                        hoverBackgroundColor: bgColors,
                        borderWidth: 2,
                        borderColor: isDark ? '#0f172a' : '#ffffff',
                        borderRadius: 8,
                        spacing: 3,
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    transitions: { active: { animation: { duration: 300, easing: 'easeOutCubic' } } },
                    plugins: { legend: { position: 'bottom', labels: { color: labelColor } } }
                }
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
                data: { labels: labels, datasets: [{ label: 'Total Peminjaman', data: data, backgroundColor: bgColors, borderRadius: 8, maxBarThickness: 48 }] },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { ticks: { color: labelColor } }, y: { ticks: { color: labelColor, precision: 0 } } } }
            });
        }
        if (ctxPie) {
            destroyChart('pinjamPie');
            tabAnalyticsCharts['pinjamPie'] = new Chart(ctxPie, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: bgColors,
                        hoverBackgroundColor: bgColors,
                        borderWidth: 2,
                        borderColor: isDark ? '#0f172a' : '#ffffff',
                        borderRadius: 8,
                        spacing: 3,
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    transitions: { active: { animation: { duration: 300, easing: 'easeOutCubic' } } },
                    plugins: { legend: { position: 'bottom', labels: { color: labelColor } } }
                }
            });
        }
    }
}

function escapeHtml(str) {
    if (str === null || str === undefined || str === '') return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function escapeJsStr(str) {
    if (str === null || str === undefined || str === '') return '';
    return String(str).replace(/\\/g, '\\\\').replace(/'/g, "\\'");
}

function handleDashboardQuickSearch(query) {
    const q = (query || '').toLowerCase().trim();
    const rows = document.querySelectorAll('#tableDashboardRecentPeminjaman tbody tr');
    rows.forEach(r => {
        if (!q) {
            r.style.display = '';
            return;
        }
        const text = r.innerText.toLowerCase();
        r.style.display = text.includes(q) ? '' : 'none';
    });
}

function updateStatCardsData() {
    const totalBarangItems = (window.dbBarang || []).length;
    const totalStokTotal = (window.dbBarang || []).reduce((sum, b) => sum + parseInt(b.stok_total || 0), 0);
    const totalStokTersedia = (window.dbBarang || []).reduce((sum, b) => sum + parseInt(b.stok_tersedia || 0), 0);
    const totalKategori = (window.dbKategori || []).length;
    const totalRak = (window.dbRak || []).length;
    const totalJurusan = (window.dbJurusan || []).length;
    const totalPengguna = (window.dbPengguna || []).length;
    const totalSiswa = (window.dbSiswa || []).length;
    const totalGuru = (window.dbGuru || []).length;

    const dipinjamCount = (window.dbPeminjaman || []).filter(p => p.status === 'dipinjam' || p.status === 'pending' || p.status === 'diajukan_pengembalian').length;
    const dikembalikanCount = (window.dbPeminjaman || []).filter(p => p.status === 'dikembalikan').length;
    const totalPeminjaman = (window.dbPeminjaman || []).length;
    const totalLogs = (window.dbLogAktivitas || []).length;

    const fmt = (num) => Number(num || 0).toLocaleString('en-US');

    const setTxt = (id, val) => {
        const el = document.getElementById(id);
        if (el) el.innerText = val;
    };

    // SuperAdmin Dashboard Stat Cards
    setTxt('statSuperTotalSiswa', fmt(totalSiswa));
    setTxt('statSuperTotalGuru', fmt(totalGuru));
    setTxt('statSuperTotalJurusan', fmt(totalJurusan));
    setTxt('statSuperTotalUsers', fmt(totalPengguna));
    setTxt('statSuperTotalKategori', fmt(totalKategori));
    setTxt('statSuperTotalBarang', fmt(totalBarangItems));
    setTxt('statSuperStokTersedia', fmt(totalStokTersedia));
    setTxt('statSuperBelumKembali', fmt(dipinjamCount));
    setTxt('statSuperSudahKembali', fmt(dikembalikanCount));
    setTxt('statSuperTotalLogs', fmt(totalLogs));
    setTxt('donutTotalStokCenter', fmt(totalStokTersedia));

    // Jurusan & Siswa Dashboard Stat Cards
    setTxt('statJurusanTotalSiswa', fmt(totalSiswa));
    setTxt('statJurusanTotalGuru', fmt(totalGuru));
    setTxt('statTotalBarang', fmt(totalBarangItems));
    setTxt('statStokTersedia', fmt(totalStokTersedia));
    setTxt('statTotalKategori', fmt(totalKategori));
    setTxt('statBelumKembali', fmt(dipinjamCount));
    setTxt('statSudahKembali', fmt(dikembalikanCount));
    setTxt('statTotalLogs', fmt(totalLogs));

    // Tab Data Pengguna Stat Cards
    // Row 1: Total User, Total Siswa, Total Guru
    setTxt('statTabPenggunaTotal', fmt(totalPengguna) + ' User');
    setTxt('statTabPenggunaTotalSiswa', fmt(totalSiswa) + ' Siswa');
    setTxt('statTabPenggunaTotalGuru', fmt(totalGuru) + ' Guru');

    // Row 2: Admin Sekolah, Admin Jurusan, Petugas Gudang, Guru Umum
    const cntAdminSekolah = (window.dbPengguna || []).filter(u => u.peran === 'admin_sekolah').length;
    const cntAdminJurusan = (window.dbPengguna || []).filter(u => u.peran === 'admin_jurusan').length;
    const cntPetugas = (window.dbPengguna || []).filter(u => u.peran === 'petugas').length;
    const cntGuruUmum = (window.dbPengguna || []).filter(u => u.peran === 'guru_umum').length;
    setTxt('statPenggunaAdminSekolah', fmt(cntAdminSekolah) + ' Admin');
    setTxt('statPenggunaAdminJurusan', fmt(cntAdminJurusan) + ' Admin');
    setTxt('statPenggunaPetugas', fmt(cntPetugas) + ' Petugas');
    setTxt('statPenggunaGuruUmum', fmt(cntGuruUmum) + ' Guru');

    // Tab-specific Stat Cards
    setTxt('statTabJurusanTotal', fmt(totalJurusan) + ' Jurusan');
    setTxt('statTabKategoriTotal', fmt(totalKategori) + ' Kategori');
    setTxt('statTabRakTotal', fmt(totalRak) + ' Rak');

    setTxt('statTabBarangTotalItem', fmt(totalBarangItems) + ' Item');
    setTxt('statTabBarangTotalStok', fmt(totalStokTersedia) + ' Unit');

    const totalMasukJumlah = (window.dbBarangMasuk || []).reduce((sum, m) => sum + parseInt(m.jumlah || 0), 0);
    setTxt('statTabMasukTotal', fmt((window.dbBarangMasuk || []).length) + ' Transaksi');
    setTxt('statTabMasukTotalItem', '+' + fmt(totalMasukJumlah) + ' Unit');

    const totalKeluarJumlah = (window.dbBarangKeluar || []).reduce((sum, k) => sum + parseInt(k.jumlah || 0), 0);
    setTxt('statTabKeluarTotal', fmt((window.dbBarangKeluar || []).length) + ' Transaksi');
    setTxt('statTabKeluarTotalItem', '-' + fmt(totalKeluarJumlah) + ' Unit');

    setTxt('statTabPinjamTotal', fmt(totalPeminjaman) + ' Transaksi');
    setTxt('statTabPinjamBelumKembali', fmt(dipinjamCount) + ' Transaksi');
    setTxt('statTabPinjamSudahKembali', fmt(dikembalikanCount) + ' Transaksi');

    setTxt('statTabLogAktivitasTotal', fmt(totalLogs) + ' Catatan');
}

let currentPenggunaPage = 1;
let currentPenggunaPerPage = 10;
let penggunaSearchDebounceTimer = null;

function matchTingkatKelas(kelasStr, tingkat) {
    if (!tingkat) return true;
    if (!kelasStr) return false;
    const k = String(kelasStr).trim().toUpperCase();
    if (tingkat === '10') {
        return k.startsWith('10') || k.startsWith('X-') || k.startsWith('X ') || k === 'X';
    } else if (tingkat === '11') {
        return k.startsWith('11') || k.startsWith('XI-') || k.startsWith('XI ') || k === 'XI';
    } else if (tingkat === '12') {
        return k.startsWith('12') || k.startsWith('XII-') || k.startsWith('XII ') || k === 'XII';
    }
    return k.includes(tingkat);
}

function debouncedRenderTablePengguna() {
    clearTimeout(penggunaSearchDebounceTimer);
    penggunaSearchDebounceTimer = setTimeout(() => {
        currentPenggunaPage = 1;
        renderTablePengguna();
    }, 150);
}

function changePenggunaPerPage(val) {
    currentPenggunaPerPage = parseInt(val) || 10;
    currentPenggunaPage = 1;
    renderTablePengguna();
}

function setPenggunaPage(p) {
    currentPenggunaPage = p;
    renderTablePengguna();
}

function renderPenggunaPaginationControls(totalItems, totalPages, startIdx, endIdx) {
    const infoElem = document.getElementById('pengguna_pagination_info');
    const btnsElem = document.getElementById('pengguna_pagination_btns');
    if (!infoElem || !btnsElem) return;

    if (totalItems === 0) {
        infoElem.innerHTML = `Menampilkan <strong class="text-slate-800 dark:text-white">0</strong> data`;
        btnsElem.innerHTML = '';
        return;
    }

    infoElem.innerHTML = `Menampilkan <strong class="text-slate-800 dark:text-white">${startIdx + 1} - ${endIdx}</strong> dari <strong class="text-slate-800 dark:text-white">${totalItems.toLocaleString('id-ID')}</strong> pengguna (Hal <strong class="text-slate-800 dark:text-white">${currentPenggunaPage}</strong> / ${totalPages})`;

    if (totalPages <= 1) {
        btnsElem.innerHTML = '';
        return;
    }

    let html = '';
    const prevDisabled = currentPenggunaPage <= 1;
    const nextDisabled = currentPenggunaPage >= totalPages;

    html += `<button type="button" onclick="setPenggunaPage(1)" ${prevDisabled ? 'disabled' : ''} class="px-2 py-1 rounded-lg border border-slate-200 dark:border-[#2a2a2a] font-bold text-xs ${prevDisabled ? 'opacity-40 cursor-not-allowed text-slate-400' : 'hover:bg-sage-100 dark:hover:bg-[#222222] text-slate-700 dark:text-slate-200'}" title="Halaman Pertama">&laquo;</button>`;
    html += `<button type="button" onclick="setPenggunaPage(${currentPenggunaPage - 1})" ${prevDisabled ? 'disabled' : ''} class="px-2.5 py-1 rounded-lg border border-slate-200 dark:border-[#2a2a2a] font-bold text-xs ${prevDisabled ? 'opacity-40 cursor-not-allowed text-slate-400' : 'hover:bg-sage-100 dark:hover:bg-[#222222] text-slate-700 dark:text-slate-200'}" title="Sebelumnya">&lsaquo;</button>`;

    let startPage = Math.max(1, currentPenggunaPage - 2);
    let endPage = Math.min(totalPages, currentPenggunaPage + 2);

    if (startPage > 1) {
        html += `<button type="button" onclick="setPenggunaPage(1)" class="px-2.5 py-1 rounded-lg border border-slate-200 dark:border-[#2a2a2a] text-xs font-semibold hover:bg-sage-100 dark:hover:bg-[#222222] text-slate-700 dark:text-slate-200">1</button>`;
        if (startPage > 2) {
            html += `<span class="px-1 text-slate-400">...</span>`;
        }
    }

    for (let p = startPage; p <= endPage; p++) {
        if (p === currentPenggunaPage) {
            html += `<button type="button" class="px-2.5 py-1 rounded-lg bg-sage-600 text-white font-bold text-xs shadow-sm">${p}</button>`;
        } else {
            html += `<button type="button" onclick="setPenggunaPage(${p})" class="px-2.5 py-1 rounded-lg border border-slate-200 dark:border-[#2a2a2a] text-xs font-semibold hover:bg-sage-100 dark:hover:bg-[#222222] text-slate-700 dark:text-slate-200">${p}</button>`;
        }
    }

    if (endPage < totalPages) {
        if (endPage < totalPages - 1) {
            html += `<span class="px-1 text-slate-400">...</span>`;
        }
        html += `<button type="button" onclick="setPenggunaPage(${totalPages})" class="px-2.5 py-1 rounded-lg border border-slate-200 dark:border-[#2a2a2a] text-xs font-semibold hover:bg-sage-100 dark:hover:bg-[#222222] text-slate-700 dark:text-slate-200">${totalPages}</button>`;
    }

    html += `<button type="button" onclick="setPenggunaPage(${currentPenggunaPage + 1})" ${nextDisabled ? 'disabled' : ''} class="px-2.5 py-1 rounded-lg border border-slate-200 dark:border-[#2a2a2a] font-bold text-xs ${nextDisabled ? 'opacity-40 cursor-not-allowed text-slate-400' : 'hover:bg-sage-100 dark:hover:bg-[#222222] text-slate-700 dark:text-slate-200'}" title="Selanjutnya">&rsaquo;</button>`;
    html += `<button type="button" onclick="setPenggunaPage(${totalPages})" ${nextDisabled ? 'disabled' : ''} class="px-2 py-1 rounded-lg border border-slate-200 dark:border-[#2a2a2a] font-bold text-xs ${nextDisabled ? 'opacity-40 cursor-not-allowed text-slate-400' : 'hover:bg-sage-100 dark:hover:bg-[#222222] text-slate-700 dark:text-slate-200'}" title="Halaman Terakhir">&raquo;</button>`;

    btnsElem.innerHTML = html;
}

function renderTablePengguna() {
    const tbody = document.querySelector('#tablePengguna tbody');
    if (!tbody || !window.dbPengguna) return;
    const isSuperAdmin = window.currentUser && window.currentUser.peran === 'admin_sekolah';
    const currentUserId = window.currentUser ? window.currentUser.id : null;

    const searchVal = (document.getElementById('filter_pengguna_search')?.value || '').trim().toLowerCase();
    const kelasVal = document.getElementById('filter_pengguna_tingkat_kelas')?.value || '';
    const peranVal = document.getElementById('filter_pengguna_peran')?.value || '';
    const jurusanVal = document.getElementById('filter_pengguna_jurusan')?.value || '';

    const filtered = window.dbPengguna.filter(p => {
        const matchSearch = !searchVal ||
            (p.nama_pengguna && p.nama_pengguna.toLowerCase().includes(searchVal)) ||
            (p.nama_lengkap && p.nama_lengkap.toLowerCase().includes(searchVal)) ||
            (p.email && p.email.toLowerCase().includes(searchVal)) ||
            (p.nomor_telepon && p.nomor_telepon.toLowerCase().includes(searchVal)) ||
            (p.peran && p.peran.toLowerCase().includes(searchVal)) ||
            (p.kelas && p.kelas.toLowerCase().includes(searchVal));

        const matchKelas = matchTingkatKelas(p.kelas, kelasVal);

        const matchPeran = !peranVal || p.peran === peranVal;

        let matchJurusan = true;
        if (jurusanVal === 'none') {
            matchJurusan = !p.jurusan_id;
        } else if (jurusanVal) {
            matchJurusan = String(p.jurusan_id) === String(jurusanVal);
        }

        return matchSearch && matchKelas && matchPeran && matchJurusan;
    });

    window.filteredPengguna = filtered;

    const totalItems = filtered.length;
    const totalPages = Math.max(1, Math.ceil(totalItems / currentPenggunaPerPage));
    if (currentPenggunaPage > totalPages) currentPenggunaPage = totalPages;
    if (currentPenggunaPage < 1) currentPenggunaPage = 1;

    const startIdx = (currentPenggunaPage - 1) * currentPenggunaPerPage;
    const endIdx = Math.min(startIdx + currentPenggunaPerPage, totalItems);
    const pageItems = filtered.slice(startIdx, endIdx);

    renderPenggunaPaginationControls(totalItems, totalPages, startIdx, endIdx);

    if (totalItems === 0) {
        tbody.innerHTML = `<tr><td colspan="10" class="py-8 text-center text-slate-400 font-semibold">Tidak ada data pengguna yang cocok dengan filter.</td></tr>`;
        return;
    }

    tbody.innerHTML = pageItems.map((p, idx) => {
        const rowNo = startIdx + idx + 1;
        const initial = (p.nama_lengkap || p.nama_pengguna || 'U').charAt(0).toUpperCase();
        const avatarHtml = p.foto_url
            ? `<img src="${p.foto_url}" class="w-7 h-7 rounded-full object-cover border border-sage-200 shrink-0 shadow-sm" alt="Avatar">`
            : `<div class="w-7 h-7 rounded-full bg-sage-600 text-white font-bold flex items-center justify-center text-[10px] shrink-0 shadow-sm">${initial}</div>`;
        const peranText = p.peran === 'admin_sekolah' ? 'Admin Sekolah' : (p.peran === 'admin_jurusan' ? 'Admin Jurusan' : (p.peran === 'petugas' ? 'Petugas Gudang' : (p.peran === 'guru_umum' ? 'Guru Umum' : 'Siswa')));
        const jurusanText = p.peran === 'admin_sekolah' ? '<span class="text-slate-400 font-normal">-</span>' : (p.nama_jurusan || '-');
        const isSiswa = p.peran === 'siswa' || p.status_pengguna === 'siswa';
        const loginBtnHtml = (isSuperAdmin && p.id !== currentUserId && !isSiswa)
            ? `<button type="button" onclick="loginAsUser('${p.id}', '${escapeJsStr(p.nama_pengguna)}')" class="px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] shadow-sm transition-all flex items-center gap-1" title="Login Sebagai Akun Ini"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg><span>Login Akun</span></button>`
            : '';
        const kelasText = (p.kelas && String(p.kelas).trim() !== '')
            ? `<span class="font-bold text-slate-700 dark:text-slate-300 whitespace-nowrap">${escapeHtml(p.kelas)}</span>`
            : '<span class="text-slate-400 font-normal">-</span>';

        return `<tr class="hover:bg-sage-50/50 dark:hover:bg-[#222222]/40">
            <td class="py-3.5 px-3 text-center"><input type="checkbox" class="row-checkbox rounded accent-sage-600 cursor-pointer" value="${p.id}" onchange="updateBatchDeleteBar()"></td>
            <td class="py-3.5 px-4 text-center font-bold text-slate-500 row-number-cell">${rowNo}</td>
            <td class="py-3.5 px-4 font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2.5">${avatarHtml}<span>${escapeHtml(p.nama_pengguna)}</span></td>
            <td class="py-3.5 px-4 font-semibold text-slate-800 dark:text-slate-200">${escapeHtml(p.nama_lengkap || '')}</td>
            <td class="py-3.5 px-4">${escapeHtml(p.email || '-')}</td>
            <td class="py-3.5 px-4 font-bold text-sage-700">${jurusanText}</td>
            <td class="py-3.5 px-4 font-semibold">${peranText}</td>
            <td class="py-3.5 px-4">${kelasText}</td>
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

let currentSiswaPage = 1;
let currentSiswaPerPage = 25;
let currentGuruPage = 1;
let currentGuruPerPage = 10;
let siswaSearchDebounceTimer = null;
let guruSearchDebounceTimer = null;

function debouncedRenderTableSiswa() {
    clearTimeout(siswaSearchDebounceTimer);
    siswaSearchDebounceTimer = setTimeout(() => {
        currentSiswaPage = 1;
        renderTableSiswa();
    }, 150);
}

function debouncedRenderTableGuru() {
    clearTimeout(guruSearchDebounceTimer);
    guruSearchDebounceTimer = setTimeout(() => {
        currentGuruPage = 1;
        renderTableGuru();
    }, 150);
}

function changeSiswaPerPage(val) {
    currentSiswaPerPage = parseInt(val) || 25;
    currentSiswaPage = 1;
    renderTableSiswa();
}

function changeGuruPerPage(val) {
    currentGuruPerPage = parseInt(val) || 10;
    currentGuruPage = 1;
    renderTableGuru();
}

function setSiswaPage(p) {
    currentSiswaPage = p;
    renderTableSiswa();
}

function setGuruPage(p) {
    currentGuruPage = p;
    renderTableGuru();
}

function renderGuruPaginationControls(totalItems, totalPages, startIdx, endIdx) {
    const infoElem = document.getElementById('guru_pagination_info');
    const btnsElem = document.getElementById('guru_pagination_btns');
    if (!infoElem || !btnsElem) return;

    if (totalItems === 0) {
        infoElem.innerHTML = `Menampilkan <strong class="text-slate-800 dark:text-white">0</strong> data`;
        btnsElem.innerHTML = '';
        return;
    }

    infoElem.innerHTML = `Menampilkan <strong class="text-slate-800 dark:text-white">${startIdx + 1} - ${endIdx}</strong> dari <strong class="text-slate-800 dark:text-white">${totalItems.toLocaleString('id-ID')}</strong> guru`;

    if (totalPages <= 1) {
        btnsElem.innerHTML = '';
        return;
    }

    let html = '';
    const prevDisabled = currentGuruPage <= 1;
    const nextDisabled = currentGuruPage >= totalPages;

    html += `<button type="button" onclick="setGuruPage(${currentGuruPage - 1})" ${prevDisabled ? 'disabled' : ''} class="px-2.5 py-1 rounded-lg border border-slate-200 dark:border-[#2a2a2a] font-bold text-xs ${prevDisabled ? 'opacity-40 cursor-not-allowed text-slate-400' : 'hover:bg-sage-100 dark:hover:bg-[#222222] text-slate-700 dark:text-slate-200'}" title="Sebelumnya">&lsaquo;</button>`;

    let startPage = Math.max(1, currentGuruPage - 2);
    let endPage = Math.min(totalPages, currentGuruPage + 2);

    for (let p = startPage; p <= endPage; p++) {
        if (p === currentGuruPage) {
            html += `<button type="button" class="px-2.5 py-1 rounded-lg bg-sage-600 text-white font-bold text-xs shadow-sm">${p}</button>`;
        } else {
            html += `<button type="button" onclick="setGuruPage(${p})" class="px-2.5 py-1 rounded-lg border border-slate-200 dark:border-[#2a2a2a] text-xs font-semibold hover:bg-sage-100 dark:hover:bg-[#222222] text-slate-700 dark:text-slate-200">${p}</button>`;
        }
    }

    html += `<button type="button" onclick="setGuruPage(${currentGuruPage + 1})" ${nextDisabled ? 'disabled' : ''} class="px-2.5 py-1 rounded-lg border border-slate-200 dark:border-[#2a2a2a] font-bold text-xs ${nextDisabled ? 'opacity-40 cursor-not-allowed text-slate-400' : 'hover:bg-sage-100 dark:hover:bg-[#222222] text-slate-700 dark:text-slate-200'}" title="Selanjutnya">&rsaquo;</button>`;

    btnsElem.innerHTML = html;
}

function renderTableGuru() {
    const tbody = document.querySelector('#tableGuru tbody');
    if (!tbody || !window.dbGuru) return;

    const searchVal = (document.getElementById('filter_guru_search')?.value || '').trim().toLowerCase();
    const mengajarVal = document.getElementById('filter_guru_mengajar')?.value || '';
    const jurusanVal = document.getElementById('filter_guru_jurusan')?.value || '';

    const filtered = window.dbGuru.filter(g => {
        const matchSearch = !searchVal || 
            (g.nama_guru && g.nama_guru.toLowerCase().includes(searchVal)) || 
            (g.nama_pengguna && g.nama_pengguna.toLowerCase().includes(searchVal)) || 
            (g.token && g.token.toLowerCase().includes(searchVal));
        const matchMengajar = !mengajarVal || String(g.mengajar || '') === String(mengajarVal);
        let matchJurusan = true;
        if (jurusanVal === 'none') {
            matchJurusan = !g.jurusan_id || g.mengajar === 'umum';
        } else if (jurusanVal) {
            matchJurusan = String(g.jurusan_id) === String(jurusanVal);
        }

        return matchSearch && matchMengajar && matchJurusan;
    });

    const totalItems = filtered.length;
    const totalPages = Math.max(1, Math.ceil(totalItems / currentGuruPerPage));
    if (currentGuruPage > totalPages) currentGuruPage = totalPages;
    if (currentGuruPage < 1) currentGuruPage = 1;

    const startIdx = (currentGuruPage - 1) * currentGuruPerPage;
    const endIdx = Math.min(startIdx + currentGuruPerPage, totalItems);
    const pageItems = filtered.slice(startIdx, endIdx);

    renderGuruPaginationControls(totalItems, totalPages, startIdx, endIdx);

    if (totalItems === 0) {
        tbody.innerHTML = `<tr><td colspan="8" class="py-8 text-center text-slate-400 font-semibold">Tidak ada data guru yang cocok dengan filter.</td></tr>`;
        return;
    }

    tbody.innerHTML = pageItems.map((g, idx) => {
        const rowNo = startIdx + idx + 1;
        const mengajarText = g.mengajar === 'umum' ? 'Guru Umum' : 'Guru Bengkel';
        const jurusanText = g.mengajar === 'umum'
            ? `<span class="text-slate-400 font-normal">Tidak ada jurusan</span>`
            : escapeHtml(g.nama_jurusan || '-');
        const tokenText = `<span class="font-mono font-bold text-slate-700 dark:text-slate-200">${escapeHtml(g.token || '-')}</span>`;
        const usernameClean = escapeHtml(g.nama_pengguna || g.nama_guru.toLowerCase().replace(/[^a-z0-9]/g, ''));
        const usernameText = `<span class="font-mono font-bold text-sage-600 dark:text-amber-400">${usernameClean}</span>`;

        return `<tr class="hover:bg-sage-50/50 dark:hover:bg-[#222222]/40">
            <td class="py-3.5 px-3 text-center"><input type="checkbox" class="row-checkbox rounded accent-sage-600 cursor-pointer" value="${g.id}" onchange="updateBatchDeleteBar()"></td>
            <td class="py-3.5 px-4 text-center font-bold text-slate-500 row-number-cell">${rowNo}</td>
            <td class="py-3.5 px-4 font-bold text-slate-800 dark:text-slate-200">${escapeHtml(g.nama_guru)}</td>
            <td class="py-3.5 px-4">${usernameText}</td>
            <td class="py-3.5 px-4">${tokenText}</td>
            <td class="py-3.5 px-4 font-bold text-slate-700 dark:text-slate-300">${mengajarText}</td>
            <td class="py-3.5 px-4 font-bold text-sage-700">${jurusanText}</td>
            <td class="py-3.5 px-4">
                <div class="flex items-center gap-1.5">
                    <button type="button" onclick="editGuru('${g.id}')" class="p-1.5 rounded-lg bg-sage-600 hover:bg-sage-700 text-white shadow-sm transition-all" title="Edit Data Guru"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 01-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                    <button type="button" onclick="deleteGuru('${g.id}', '${escapeJsStr(g.nama_guru)}')" class="p-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white shadow-sm transition-all" title="Hapus Data Guru"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                </div>
            </td>
        </tr>`;
    }).join('');
}

function renderSiswaPaginationControls(totalItems, totalPages, startIdx, endIdx) {
    const infoElem = document.getElementById('siswa_pagination_info');
    const btnsElem = document.getElementById('siswa_pagination_btns');
    if (!infoElem || !btnsElem) return;

    if (totalItems === 0) {
        infoElem.innerHTML = `Menampilkan <strong class="text-slate-800 dark:text-white">0</strong> data`;
        btnsElem.innerHTML = '';
        return;
    }

    infoElem.innerHTML = `Menampilkan <strong class="text-slate-800 dark:text-white">${startIdx + 1} - ${endIdx}</strong> dari <strong class="text-slate-800 dark:text-white">${totalItems.toLocaleString('id-ID')}</strong> siswa (Hal <strong class="text-slate-800 dark:text-white">${currentSiswaPage}</strong> / ${totalPages})`;

    if (totalPages <= 1) {
        btnsElem.innerHTML = '';
        return;
    }

    let html = '';
    const prevDisabled = currentSiswaPage <= 1;
    const nextDisabled = currentSiswaPage >= totalPages;

    html += `<button type="button" onclick="setSiswaPage(1)" ${prevDisabled ? 'disabled' : ''} class="px-2 py-1 rounded-lg border border-slate-200 dark:border-[#2a2a2a] font-bold text-xs ${prevDisabled ? 'opacity-40 cursor-not-allowed text-slate-400' : 'hover:bg-sage-100 dark:hover:bg-[#222222] text-slate-700 dark:text-slate-200'}" title="Halaman Pertama">&laquo;</button>`;
    html += `<button type="button" onclick="setSiswaPage(${currentSiswaPage - 1})" ${prevDisabled ? 'disabled' : ''} class="px-2.5 py-1 rounded-lg border border-slate-200 dark:border-[#2a2a2a] font-bold text-xs ${prevDisabled ? 'opacity-40 cursor-not-allowed text-slate-400' : 'hover:bg-sage-100 dark:hover:bg-[#222222] text-slate-700 dark:text-slate-200'}" title="Sebelumnya">&lsaquo;</button>`;

    let startPage = Math.max(1, currentSiswaPage - 2);
    let endPage = Math.min(totalPages, currentSiswaPage + 2);

    if (startPage > 1) {
        html += `<button type="button" onclick="setSiswaPage(1)" class="px-2.5 py-1 rounded-lg border border-slate-200 dark:border-[#2a2a2a] text-xs font-semibold hover:bg-sage-100 dark:hover:bg-[#222222] text-slate-700 dark:text-slate-200">1</button>`;
        if (startPage > 2) {
            html += `<span class="px-1 text-slate-400">...</span>`;
        }
    }

    for (let p = startPage; p <= endPage; p++) {
        if (p === currentSiswaPage) {
            html += `<button type="button" class="px-2.5 py-1 rounded-lg bg-sage-600 text-white font-bold text-xs shadow-sm">${p}</button>`;
        } else {
            html += `<button type="button" onclick="setSiswaPage(${p})" class="px-2.5 py-1 rounded-lg border border-slate-200 dark:border-[#2a2a2a] text-xs font-semibold hover:bg-sage-100 dark:hover:bg-[#222222] text-slate-700 dark:text-slate-200">${p}</button>`;
        }
    }

    if (endPage < totalPages) {
        if (endPage < totalPages - 1) {
            html += `<span class="px-1 text-slate-400">...</span>`;
        }
        html += `<button type="button" onclick="setSiswaPage(${totalPages})" class="px-2.5 py-1 rounded-lg border border-slate-200 dark:border-[#2a2a2a] text-xs font-semibold hover:bg-sage-100 dark:hover:bg-[#222222] text-slate-700 dark:text-slate-200">${totalPages}</button>`;
    }

    html += `<button type="button" onclick="setSiswaPage(${currentSiswaPage + 1})" ${nextDisabled ? 'disabled' : ''} class="px-2.5 py-1 rounded-lg border border-slate-200 dark:border-[#2a2a2a] font-bold text-xs ${nextDisabled ? 'opacity-40 cursor-not-allowed text-slate-400' : 'hover:bg-sage-100 dark:hover:bg-[#222222] text-slate-700 dark:text-slate-200'}" title="Selanjutnya">&rsaquo;</button>`;
    html += `<button type="button" onclick="setSiswaPage(${totalPages})" ${nextDisabled ? 'disabled' : ''} class="px-2 py-1 rounded-lg border border-slate-200 dark:border-[#2a2a2a] font-bold text-xs ${nextDisabled ? 'opacity-40 cursor-not-allowed text-slate-400' : 'hover:bg-sage-100 dark:hover:bg-[#222222] text-slate-700 dark:text-slate-200'}" title="Halaman Terakhir">&raquo;</button>`;

    btnsElem.innerHTML = html;
}

function renderTableSiswa() {
    const tbody = document.querySelector('#tableSiswa tbody');
    if (!tbody || !window.dbSiswa) return;

    const searchVal = (document.getElementById('filter_siswa_search')?.value || '').trim().toLowerCase();
    const kelasVal = document.getElementById('filter_siswa_tingkat_kelas')?.value || '';
    const jurusanVal = document.getElementById('filter_siswa_jurusan')?.value || '';
    const tahunVal = document.getElementById('filter_siswa_tahun')?.value || '';

    const filtered = window.dbSiswa.filter(s => {
        const matchSearch = !searchVal ||
            (s.nama_lengkap && s.nama_lengkap.toLowerCase().includes(searchVal)) ||
            (s.nama_siswa && s.nama_siswa.toLowerCase().includes(searchVal)) ||
            (s.nisn && s.nisn.toLowerCase().includes(searchVal)) ||
            (s.token && s.token.toLowerCase().includes(searchVal)) ||
            (s.kelas && s.kelas.toLowerCase().includes(searchVal));

        const matchKelas = matchTingkatKelas(s.kelas, kelasVal);

        let matchJurusan = true;
        if (jurusanVal === 'none') {
            matchJurusan = !s.jurusan_id;
        } else if (jurusanVal) {
            matchJurusan = String(s.jurusan_id) === String(jurusanVal);
        }

        const matchTahun = !tahunVal || String(s.tahun_ajaran || '') === String(tahunVal);

        return matchSearch && matchKelas && matchJurusan && matchTahun;
    });

    const totalItems = filtered.length;
    const totalPages = Math.max(1, Math.ceil(totalItems / currentSiswaPerPage));
    if (currentSiswaPage > totalPages) currentSiswaPage = totalPages;
    if (currentSiswaPage < 1) currentSiswaPage = 1;

    const startIdx = (currentSiswaPage - 1) * currentSiswaPerPage;
    const endIdx = Math.min(startIdx + currentSiswaPerPage, totalItems);
    const pageItems = filtered.slice(startIdx, endIdx);

    renderSiswaPaginationControls(totalItems, totalPages, startIdx, endIdx);

    if (totalItems === 0) {
        tbody.innerHTML = `<tr><td colspan="9" class="py-8 text-center text-slate-400 font-semibold">Tidak ada data siswa yang cocok dengan filter.</td></tr>`;
        return;
    }

    tbody.innerHTML = pageItems.map((s, idx) => {
        const rowNo = startIdx + idx + 1;
        const tokenHtml = `<span class="font-mono font-bold text-slate-700 dark:text-slate-200">${escapeHtml(s.token || '-')}</span>`;
        const nisnHtml = s.nisn
            ? `<span class="font-mono font-bold text-slate-700 dark:text-slate-300 tracking-wider">${escapeHtml(s.nisn)}</span>`
            : `<span class="text-slate-400 italic text-[11px]">-</span>`;

        return `<tr class="hover:bg-sage-50/50 dark:hover:bg-[#222222]/40">
            <td class="py-3.5 px-3 text-center"><input type="checkbox" class="row-checkbox rounded accent-sage-600 cursor-pointer" value="${s.id}" onchange="updateBatchDeleteBar()"></td>
            <td class="py-3.5 px-4 text-center font-bold text-slate-500 row-number-cell">${rowNo}</td>
            <td class="py-3.5 px-4">${nisnHtml}</td>
            <td class="py-3.5 px-4 font-bold text-slate-800 dark:text-slate-200">${escapeHtml(s.nama_lengkap || s.nama_siswa)}</td>
            <td class="py-3.5 px-4">${tokenHtml}</td>
            <td class="py-3.5 px-4 font-bold text-slate-700 dark:text-slate-300">${escapeHtml(s.kelas || '-')}</td>
            <td class="py-3.5 px-4 font-bold text-sage-700 dark:text-sage-400">${escapeHtml(s.nama_jurusan || '-')}</td>
            <td class="py-3.5 px-4 dark:text-slate-400">${escapeHtml(s.tahun_ajaran || '2026/2027')}</td>
            <td class="py-3.5 px-4">
                <div class="flex items-center gap-1.5">
                    <button type="button" onclick="editSiswa('${s.id}')" class="p-1.5 rounded-lg bg-sage-600 hover:bg-sage-700 text-white shadow-sm transition-all" title="Edit Data Siswa"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 01-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                    <button type="button" onclick="deleteSiswa('${s.id}', '${escapeJsStr(s.nama_lengkap || s.nama_siswa)}')" class="p-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white shadow-sm transition-all" title="Hapus Data Siswa"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
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
        const jenisText = String(b.jenis || 'alat').toLowerCase() === 'bahan' ? 'Bahan' : 'Alat';
        const barcodeHtml = b.barcode
            ? `<button type="button" onclick="showBarcodeModal('${escapeHtml(b.barcode)}', '${escapeJsStr(b.nama_barang)}')" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-sage-50 hover:bg-sage-100 text-sage-800 border border-sage-200 font-bold transition-all group" title="Klik untuk preview / simpan barcode"><svg class="w-4 h-4 text-sage-600 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg><span>${escapeHtml(b.barcode)}</span></button>`
            : '<span class="text-slate-400">-</span>';

        const actionBtns = isNotSiswa
            ? `<div class="flex items-center gap-1.5"><button type="button" onclick="editBarang('${b.id}')" class="p-1.5 rounded-lg bg-sage-600 hover:bg-sage-700 text-white shadow-sm transition-all" title="Edit Master Barang"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 01-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button><button type="button" onclick="deleteBarang('${b.id}', '${escapeJsStr(b.nama_barang)}')" class="p-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white shadow-sm transition-all" title="Hapus Data Barang"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button></div>`
            : '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-sage-50 text-sage-700 border border-sage-200">Read Only</span>';

            let totalDipinjam = Number(b.total_dipinjam || 0);
            if (window.dbPeminjaman && window.dbPeminjaman.length > 0) {
                totalDipinjam = window.dbPeminjaman
                    .filter(p => String(p.barang_id) === String(b.id) && p.status !== 'dikembalikan')
                    .reduce((sum, p) => sum + parseInt(p.jumlah || 0), 0);
            }

            let totalKeluar = Number(b.total_keluar || 0);
            if (window.dbBarangKeluar && window.dbBarangKeluar.length > 0) {
                totalKeluar = window.dbBarangKeluar
                    .filter(k => String(k.barang_id) === String(b.id))
                    .reduce((sum, k) => sum + parseInt(k.jumlah || 0), 0);
            }

            const dipinjamInfo = (totalDipinjam > 0)
                ? `<span class="block text-[9.5px] font-semibold text-amber-500 mt-0.5 whitespace-nowrap leading-tight">${totalDipinjam} ${escapeHtml(b.satuan || 'Unit')} dipinjam</span>`
                : '';

            const keluarInfo = (totalKeluar > 0)
                ? `<span class="block text-[9.5px] font-semibold text-rose-500 dark:text-rose-400 mt-0.5 whitespace-nowrap leading-tight">${totalKeluar} ${escapeHtml(b.satuan || 'Unit')} dikeluarkan</span>`
                : '';

            const stokTersediaVal = parseInt(b.stok_tersedia !== undefined && b.stok_tersedia !== null ? b.stok_tersedia : 0);
            const stokAwalVal = (b.stok_awal !== undefined && b.stok_awal !== null) ? b.stok_awal : (b.stok_total || 0);

            return `<tr id="row-barang-${b.id}"
            data-jenis="${escapeHtml(String(b.jenis || 'alat').toLowerCase())}"
            data-kategori-id="${escapeHtml(String(b.kategori_id || ''))}"
            data-rak-id="${escapeHtml(String(b.rak_id || ''))}"
            data-jurusan-id="${escapeHtml(String(b.jurusan_id || ''))}"
            class="hover:bg-sage-50/50 transition-all duration-300">
            <td class="py-3.5 px-3 text-center"><input type="checkbox" class="row-checkbox rounded accent-sage-600 cursor-pointer" value="${b.id}" onchange="updateBatchDeleteBar()"></td>
            <td class="py-3.5 px-4 text-center font-bold text-slate-500 row-number-cell">${idx + 1}</td>
            <td class="py-3.5 px-4 font-extrabold text-xs nama-barang-cell text-sage-700">${escapeHtml(b.nama_barang)}</td>
            <td class="py-3.5 px-4 font-semibold text-slate-700">${escapeHtml(jenisText)}</td>
            <td class="py-3.5 px-4 font-semibold text-slate-700">${escapeHtml(b.nama_kategori || '-')}</td>
            <td class="py-3.5 px-4 font-semibold text-slate-700">${escapeHtml(b.nama_rak || '-')}</td>
            ${jurTd}
            <td class="py-3.5 px-4">${escapeHtml(b.merek || '-')}</td>
            <td class="py-3.5 px-4 font-mono text-sage-700">${barcodeHtml}</td>
            <td class="py-3.5 px-4 font-bold">${escapeHtml(String(stokAwalVal))} ${escapeHtml(b.satuan || 'Unit')}</td>
            <td class="py-3.5 px-4 font-bold text-sage-600 whitespace-nowrap">
                <div class="whitespace-nowrap">${stokTersediaVal} ${escapeHtml(b.satuan || 'Unit')}</div>
                ${dipinjamInfo}
                ${keluarInfo}
            </td>
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
            <td class="py-3.5 px-4">${escapeHtml(bk.catatan || '-')}</td>
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
        
        const guruHtml = `<td class="py-3.5 px-4 font-bold text-slate-800 dark:text-white">${escapeHtml(pm.guru_peminjam || '-')}</td>`;
        let siswaHtml = '<td class="py-3.5 px-4"><span class="text-slate-400 font-normal">-</span></td>';
        if (pm.nama_peminjam) {
            let sContent = `<div class="font-bold text-slate-800 dark:text-white">${escapeHtml(pm.nama_peminjam)}</div>`;
            if (pm.nisn) {
                sContent += `<span class="inline-flex items-center gap-1 font-mono text-[10px] text-slate-500"><span class="font-bold">NISN:</span> ${escapeHtml(pm.nisn)}</span>`;
            }
            siswaHtml = `<td class="py-3.5 px-4">${sContent}</td>`;
        }

        const thnAjaranDisplay = pm.nama_peminjam ? escapeHtml(pm.tahun_ajaran || '2026/2027') : '-';

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
                actionBtns += `<button type="button" onclick="showFotoPreview('${pm.bukti_foto}', 'Bukti Foto Pengembalian Alat', 'Peminjam: ${escapeJsStr(pm.nama_peminjam || pm.guru_peminjam)} | Alat: ${escapeJsStr(pm.nama_barang)}')" class="p-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow-sm transition-all" title="Lihat Bukti Foto"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></button>`;
            }
        } else if (pm.status === 'ditolak') {
            actionBtns = `<button type="button" onclick="kembalikanPeminjaman('${pm.id}')" class="px-2.5 py-1 rounded-lg bg-amber-600 hover:bg-amber-700 text-white font-bold text-[11px] shadow-sm transition-all flex items-center gap-1" title="Upload Ulang Bukti Foto Pengembalian">Upload Ulang</button>`;
            if (pm.bukti_foto) {
                actionBtns += `<button type="button" onclick="showFotoPreview('${pm.bukti_foto}', 'Bukti Foto Pengembalian Alat (Ditolak)', 'Peminjam: ${escapeJsStr(pm.nama_peminjam || pm.guru_peminjam)} | Alat: ${escapeJsStr(pm.nama_barang)}')" class="p-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow-sm transition-all" title="Lihat Bukti Foto Ditolak"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></button>`;
            }
        }

        if (pm.status !== 'dikembalikan') {
            actionBtns += `<button type="button" onclick="editPeminjaman('${pm.id}')" class="p-1.5 rounded-lg bg-sage-600 hover:bg-sage-700 text-white shadow-sm transition-all" title="Edit Transaksi Peminjaman"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 01-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
            <button type="button" onclick="deletePeminjaman('${pm.id}', '${escapeJsStr(pm.nama_peminjam || pm.guru_peminjam)}')" class="p-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white shadow-sm transition-all" title="Hapus Transaksi Peminjaman"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>`;
        } else if (pm.bukti_foto) {
            actionBtns += `<button type="button" onclick="showFotoPreview('${pm.bukti_foto}', 'Bukti Foto Pengembalian Alat', 'Peminjam: ${escapeJsStr(pm.nama_peminjam || pm.guru_peminjam)} | Alat: ${escapeJsStr(pm.nama_barang)}')" class="p-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow-sm transition-all" title="Lihat Bukti Foto"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></button>`;
        }

        const brgMatch = (window.dbBarang || []).find(b => String(b.id) === String(pm.barang_id));
        const satuanDisplay = (brgMatch && brgMatch.satuan) ? brgMatch.satuan : (pm.satuan || 'Unit');

        return `<tr class="hover:bg-sage-50/50">
            <td class="py-3.5 px-3 text-center"><input type="checkbox" class="row-checkbox rounded accent-sage-600 cursor-pointer" value="${pm.id}" onchange="updateBatchDeleteBar()"></td>
            <td class="py-3.5 px-4 text-center font-bold text-slate-500 row-number-cell">${idx + 1}</td>
            <td class="py-3.5 px-4 font-bold text-slate-800">${escapeHtml(pm.nama_barang)}</td>
            ${jurTd}
            ${guruHtml}
            ${siswaHtml}
            <td class="py-3.5 px-4 font-semibold text-slate-700">${escapeHtml(pm.nama_petugas || '-')}</td>
            <td class="py-3.5 px-4 font-semibold">${parseInt(pm.jumlah || 1)} ${escapeHtml(satuanDisplay)}</td>
            <td class="py-3.5 px-4 font-semibold text-slate-700">${escapeHtml(pm.tugas || '-')}</td>
            <td class="py-3.5 px-4 font-semibold text-sage-700">${thnAjaranDisplay}</td>
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

        const guruHtml = `<td class="py-3 px-4 font-bold text-slate-800 dark:text-white">${escapeHtml(logPm.guru_peminjam || '-')}</td>`;
        let siswaHtml = '<td class="py-3 px-4"><span class="text-slate-400 font-normal">-</span></td>';
        if (logPm.nama_peminjam) {
            let sContent = `<span class="font-bold text-slate-800 dark:text-white">${escapeHtml(logPm.nama_peminjam)}</span>`;
            if (logPm.nisn) {
                sContent += `<div class="text-[10px] text-slate-500 font-mono">NISN: ${escapeHtml(logPm.nisn)}</div>`;
            }
            siswaHtml = `<td class="py-3 px-4">${sContent}</td>`;
        }

        let statusHtml = '';
        if (logPm.status === 'dipinjam') statusHtml = `<span class="text-amber-500 font-extrabold">Dipinjam</span>`;
        else if (logPm.status === 'pending') statusHtml = `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-amber-100 text-amber-800 border border-amber-300"><svg class="w-3 h-3 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>Pending</span>`;
        else if (logPm.status === 'ditolak') statusHtml = `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-red-100 text-red-800 border border-red-300">Ditolak</span>`;
        else statusHtml = `<span class="font-extrabold text-sage-600">Dikembalikan</span>`;

        const fotoBtn = logPm.bukti_foto
            ? `<button type="button" onclick="showFotoPreview('${logPm.bukti_foto}', 'Bukti Foto Pengembalian Alat', 'Peminjam: ${escapeJsStr(logPm.nama_peminjam || logPm.guru_peminjam)} | Alat: ${escapeJsStr(logPm.nama_barang)}')" class="px-3 py-1 rounded-xl bg-sage-600 hover:bg-sage-700 text-white font-bold text-xs shadow-md shadow-sage-600/20 transition-all inline-flex items-center gap-1.5" title="Lihat Foto Bukti Pengembalian Alat"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg><span>View</span></button>`
            : '<span class="text-slate-400 font-normal">-</span>';

        const actionBtn = (logPm.status === 'dikembalikan' && window.currentUser && window.currentUser.peran !== 'siswa')
            ? `<button type="button" onclick="editPeminjaman('${logPm.id}')" class="p-1.5 rounded-lg bg-sage-600 hover:bg-sage-700 text-white shadow-sm transition-all flex items-center gap-1 text-xs font-bold px-2.5" title="Edit Status Peminjaman"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 01-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg><span>Edit Status</span></button>`
            : '<span class="text-slate-400 font-normal">-</span>';

        const brgMatch = (window.dbBarang || []).find(b => String(b.id) === String(logPm.barang_id));
        const satuanDisplay = (brgMatch && brgMatch.satuan) ? brgMatch.satuan : (logPm.satuan || 'Unit');

        return `<tr class="hover:bg-sage-50/50">
            <td class="py-3 px-4 text-center font-bold text-slate-500 row-number-cell">${idx + 1}</td>
            ${guruHtml}
            ${siswaHtml}
            <td class="py-3 px-4 font-semibold text-slate-700">${escapeHtml(logPm.nama_barang || '-')}</td>
            ${jurTd}
            <td class="py-3 px-4 font-semibold text-slate-700">${escapeHtml(logPm.nama_petugas || '-')}</td>
            <td class="py-3 px-4 text-center font-bold text-slate-800">${parseInt(logPm.jumlah || 1)} ${escapeHtml(satuanDisplay)}</td>
            <td class="py-3 px-4 font-mono text-[11px] text-slate-600">${tglPinjam}</td>
            <td class="py-3 px-4 font-mono text-[11px] text-slate-600">${tglKembali}</td>
            <td class="py-3 px-4 text-center font-bold">${statusHtml}</td>
            <td class="py-3 px-4 text-slate-600">${escapeHtml(logPm.tugas || logPm.catatan || '-')}</td>
            <td class="py-3 px-4">${fotoBtn}</td>
            <td class="py-3 px-4"><div class="flex items-center gap-1.5">${actionBtn}</div></td>
        </tr>`;
    }).join('');
}

function renderDashboardRecentPeminjaman() {
    const tbody = document.querySelector('#tableDashboardRecentPeminjaman tbody');
    if (!tbody || !window.dbPeminjaman) return;

    if (window.dbPeminjaman.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="py-4 text-center text-slate-400">Belum ada data peminjaman</td></tr>';
        return;
    }

    tbody.innerHTML = window.dbPeminjaman.slice(0, 5).map(pm => {
        let statusHtml = '';
        if (pm.status === 'dipinjam') statusHtml = '<span class="text-amber-500 font-extrabold">Dipinjam</span>';
        else if (pm.status === 'pending') statusHtml = '<span class="text-sky-500 font-extrabold inline-flex items-center gap-1"><svg class="w-3 h-3 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>Pending</span>';
        else if (pm.status === 'ditolak') statusHtml = '<span class="font-extrabold text-rose-500 dark:text-rose-400">Ditolak</span>';
        else statusHtml = '<span class="font-extrabold text-emerald-500 dark:text-emerald-400">Dikembalikan</span>';

        const brgMatch = (window.dbBarang || []).find(b => String(b.id) === String(pm.barang_id));
        const jenisDisplay = (pm.jenis || (brgMatch ? brgMatch.jenis : 'alat')) || 'alat';
        const satuanDisplay = (brgMatch && brgMatch.satuan) ? brgMatch.satuan : (pm.satuan || 'Unit');

        let siswaHtml = '<span class="text-slate-400 font-normal">-</span>';
        if (pm.nama_peminjam) {
            let sContent = `<div class="font-bold text-slate-800 dark:text-white">${escapeHtml(pm.nama_peminjam)}</div>`;
            if (pm.nisn) {
                sContent += `<span class="inline-flex items-center gap-1 font-mono text-[10px] text-slate-500"><span class="font-bold">NISN:</span> ${escapeHtml(pm.nisn)}</span>`;
            }
            siswaHtml = sContent;
        }

        return `<tr class="hover:bg-sage-50/50">
            <td class="py-3.5 px-4 font-bold text-slate-800 dark:text-white">${escapeHtml(pm.nama_barang || '-')}</td>
            <td class="py-3.5 px-4 font-semibold capitalize text-slate-700 dark:text-slate-300">${escapeHtml(jenisDisplay)}</td>
            <td class="py-3.5 px-4">${siswaHtml}</td>
            <td class="py-3.5 px-4 font-bold text-slate-800 dark:text-white">${escapeHtml(pm.guru_peminjam || '-')}</td>
            <td class="py-3.5 px-4 font-semibold text-slate-700 dark:text-slate-300">${escapeHtml(pm.nama_petugas || '-')}</td>
            <td class="py-3.5 px-4 font-semibold text-slate-800 dark:text-slate-200">${parseInt(pm.jumlah || 1)} ${escapeHtml(satuanDisplay)}</td>
            <td class="py-3.5 px-4">${statusHtml}</td>
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

        const jurTd = isSuperAdmin ? `<td class="py-3.5 px-4 font-bold text-sage-700">${escapeHtml(log.nama_jurusan || '-')}</td>` : '';
        const dateStr = log.created_at ? new Date(log.created_at).toLocaleString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }) : '-';

        return `<tr class="hover:bg-sage-50/50" data-date="${dateAttr}" data-tindakan="${escapeHtml(tindakan)}" data-jurusan="${escapeHtml(jurAttr)}" data-pengguna="${escapeHtml(pengAttr)}">
            <td class="py-3.5 px-4 text-center font-bold text-slate-500 row-number-cell">${idx + 1}</td>
            <td class="py-3.5 px-4 font-bold text-slate-800 dark:text-white">${escapeHtml(log.nama_pengguna || log.nama_lengkap || 'Sistem')}</td>
            <td class="py-3.5 px-4 font-bold text-slate-800 dark:text-white">${escapeHtml(log.tindakan || '-')}</td>
            ${jurTd}
            <td class="py-3.5 px-4 text-slate-600 dark:text-slate-300">${escapeHtml(log.deskripsi || '-')}</td>
            <td class="py-3.5 px-4 font-mono text-[11px] text-slate-500">${dateStr}</td>
        </tr>`;
    }).join('');
}

function renderRecentLogMasukKeluar() {
    const container = document.getElementById('recentLogMasukKeluarContainer');
    if (!container || !window.dbBarangMasuk || !window.dbBarangKeluar) return;

    const list = [];
    (window.dbBarangMasuk || []).forEach(m => {
        list.push({
            type: 'masuk',
            nama_barang: m.nama_barang || 'Barang',
            jurusan_id: m.jurusan_id || null,
            pihak: m.nama_petugas || 'Petugas Gudang',
            jumlah: m.jumlah || 1,
            satuan: m.satuan || 'Unit',
            tanggal: m.tanggal_masuk || m.created_at || new Date().toISOString()
        });
    });
    (window.dbBarangKeluar || []).forEach(k => {
        list.push({
            type: 'keluar',
            nama_barang: k.nama_barang || 'Barang',
            jurusan_id: k.jurusan_id || null,
            pihak: k.nama_penerima || 'Penerima',
            jumlah: k.jumlah || 1,
            satuan: k.satuan || 'Unit',
            tanggal: k.tanggal_keluar || k.created_at || new Date().toISOString()
        });
    });

    list.sort((a, b) => new Date(b.tanggal).getTime() - new Date(a.tanggal).getTime());
    const topLogs = list.slice(0, 4);

    if (topLogs.length === 0) {
        container.innerHTML = '<div class="p-6 text-center text-slate-400 dark:text-slate-500 text-xs italic">Belum ada aktivitas alat & bahan masuk / keluar</div>';
        return;
    }

    container.innerHTML = topLogs.map(log => {
        const isMasuk = log.type === 'masuk';
        const dateObj = new Date(log.tanggal);
        const d = String(dateObj.getDate()).padStart(2, '0');
        const m = String(dateObj.getMonth() + 1).padStart(2, '0');
        const h = String(dateObj.getHours()).padStart(2, '0');
        const min = String(dateObj.getMinutes()).padStart(2, '0');
        const dateFormatted = `${d}/${m}/${h}:${min}`;

        const jurColor = (window.jurusanColors && log.jurusan_id && window.jurusanColors[log.jurusan_id])
            ? window.jurusanColors[log.jurusan_id]
            : (window.themePrimaryColor || '#eab308');

        const boxClass = 'bg-transparent border border-slate-200/80 dark:border-[#262626] hover:border-slate-300 dark:hover:border-[#383838] hover:bg-slate-50/40 dark:hover:bg-white/[0.02]';

        const iconBg = isMasuk ? 'bg-emerald-500 text-white' : 'bg-amber-500 text-white';
        const iconSvg = isMasuk
            ? '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>'
            : '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>';

        const amountClass = isMasuk ? 'text-emerald-500 dark:text-emerald-400' : 'text-amber-500 dark:text-amber-400';
        const sign = isMasuk ? '+' : '-';
        const pihakLabel = isMasuk ? 'Petugas: ' : 'Untuk: ';

        return `<div class="p-3 sm:p-3.5 rounded-2xl ${boxClass} flex items-center justify-between transition-all duration-200">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-9 h-9 rounded-xl ${iconBg} flex items-center justify-center text-xs font-bold shrink-0 shadow-xs">
                    ${iconSvg}
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-bold line-clamp-1 text-slate-800 dark:text-slate-100">${escapeHtml(log.nama_barang)}</p>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium truncate mt-0.5">${pihakLabel}<span class="text-slate-700 dark:text-slate-200 font-semibold">${escapeHtml(log.pihak)}</span></p>
                </div>
            </div>
            <div class="text-right shrink-0 ml-3">
                <span class="text-xs font-black ${amountClass}">${sign}${escapeHtml(String(log.jumlah))} ${escapeHtml(log.satuan)}</span>
                <p class="text-[10px] text-slate-400 dark:text-slate-500 font-medium mt-0.5">${dateFormatted}</p>
            </div>
        </div>`;
    }).join('');
}

function renderDashboardRecentActivities() {
    const container = document.getElementById('recentActivitiesListContainer');
    if (!container || !window.dbLogAktivitas) return;

    const list = (window.dbLogAktivitas || []).slice(0, 5);
    if (list.length === 0) {
        container.innerHTML = '<div class="py-6 text-center text-xs text-slate-400 dark:text-slate-500 italic">Belum ada aktivitas yang tercatat di sistem</div>';
        return;
    }

    const monthsIndo = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    const actionLabels = {
        'LOGIN': 'Login',
        'LOGOUT': 'Logout',
        'EDIT_PENGGUNA': 'Edit Pengguna',
        'TAMBAH_PENGGUNA': 'Tambah Pengguna',
        'HAPUS_PENGGUNA': 'Hapus Pengguna',
        'EDIT_BARANG': 'Edit Barang',
        'TAMBAH_BARANG': 'Tambah Barang',
        'HAPUS_BARANG': 'Hapus Barang',
        'TAMBAH_BARANG_MASUK': 'Barang Masuk',
        'TAMBAH_BARANG_KELUAR': 'Barang Keluar',
        'PINJAM': 'Peminjaman Alat',
        'KEMBALI': 'Pengembalian Alat',
        'EDIT_JURUSAN': 'Edit Jurusan',
        'TAMBAH_JURUSAN': 'Tambah Jurusan',
        'HAPUS_JURUSAN': 'Hapus Jurusan',
        'EDIT_KATEGORI': 'Edit Kategori',
        'TAMBAH_KATEGORI': 'Tambah Kategori',
        'HAPUS_KATEGORI': 'Hapus Kategori',
        'EDIT_RAK': 'Edit Rak',
        'TAMBAH_RAK': 'Tambah Rak',
        'HAPUS_RAK': 'Hapus Rak'
    };

    container.innerHTML = list.map(act => {
        const d = act.created_at ? new Date(act.created_at) : new Date();
        const timeStr = String(d.getHours()).padStart(2, '0') + ':' + String(d.getMinutes()).padStart(2, '0');
        const dateStr = d.getDate() + ' ' + (monthsIndo[d.getMonth()] || '') + ' ' + d.getFullYear();

        const rawTindakan = (act.tindakan || 'AKTIVITAS').toUpperCase();
        let actTitle = actionLabels[rawTindakan];
        if (!actTitle) {
            actTitle = rawTindakan.toLowerCase().replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
        }

        const actPengguna = act.nama_pengguna || act.nama_lengkap || 'Sistem';
        let shortJurusan = '';
        if (act.nama_jurusan && act.nama_jurusan !== '-') {
            const match = act.nama_jurusan.match(/\(([^)]+)\)/);
            shortJurusan = match ? match[1] : act.nama_jurusan;
        }

        const actDesc = act.deskripsi || 'Aktivitas sistem';

        let iconBg = '';
        let iconSvg = '';
        if (rawTindakan.includes('TAMBAH') || rawTindakan.includes('MASUK')) {
            iconBg = 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-500 dark:text-emerald-400';
            iconSvg = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>';
        } else if (rawTindakan.includes('PINJAM')) {
            iconBg = 'bg-sky-50 dark:bg-sky-950/50 text-sky-500 dark:text-sky-400';
            iconSvg = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>';
        } else if (rawTindakan.includes('KEMBALI')) {
            iconBg = 'bg-amber-50 dark:bg-amber-950/50 text-amber-500 dark:text-amber-400';
            iconSvg = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
        } else if (rawTindakan.includes('EDIT') || rawTindakan.includes('UPDATE')) {
            iconBg = 'bg-purple-50 dark:bg-purple-950/50 text-purple-500 dark:text-purple-400';
            iconSvg = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>';
        } else if (rawTindakan.includes('HAPUS') || rawTindakan.includes('DELETE')) {
            iconBg = 'bg-rose-50 dark:bg-rose-950/50 text-rose-500 dark:text-rose-400';
            iconSvg = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>';
        } else if (rawTindakan.includes('LOGIN')) {
            iconBg = 'bg-sky-50 dark:bg-sky-950/50 text-sky-500 dark:text-sky-400';
            iconSvg = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>';
        } else if (rawTindakan.includes('LOGOUT')) {
            iconBg = 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400';
            iconSvg = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>';
        } else {
            iconBg = 'bg-teal-50 dark:bg-teal-950/50 text-teal-500 dark:text-teal-400';
            iconSvg = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
        }

        const jurBadgeHtml = shortJurusan
            ? `<span class="text-[10px] text-slate-400 dark:text-slate-600">•</span>
               <span class="text-[11px] font-medium text-slate-400 dark:text-slate-500 truncate">${escapeHtml(shortJurusan)}</span>`
            : '';

        return `<div class="py-2.5 first:pt-1 last:pb-0 flex items-center justify-between gap-3">
            <div class="min-w-0">
                <div class="flex items-center gap-1.5 flex-wrap min-w-0">
                    <h4 class="text-xs font-bold text-slate-800 dark:text-white truncate">${escapeHtml(actTitle)}</h4>
                    <span class="text-[10px] text-slate-400 dark:text-slate-600">•</span>
                    <span class="text-[11px] font-semibold text-emerald-600 dark:text-emerald-400 truncate">${escapeHtml(actPengguna)}</span>
                    ${jurBadgeHtml}
                </div>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 truncate" title="${escapeHtml(actDesc)}">${escapeHtml(actDesc)}</p>
            </div>
            <div class="text-right shrink-0">
                <span class="block text-xs font-semibold text-slate-700 dark:text-slate-300">${timeStr}</span>
                <span class="block text-[10px] text-slate-400 dark:text-slate-500">${dateStr}</span>
            </div>
        </div>`;
    }).join('');
}

function renderActiveTabTable(tabId) {
    if (!tabId) tabId = (new URLSearchParams(window.location.search).get('tab') || 'dashboard');
    if (tabId === 'siswa') {
        renderTableSiswa();
    } else if (tabId === 'guru') {
        renderTableGuru();
    } else if (tabId === 'barang') {
        renderTableBarang();
        refreshBarangFilterDropdowns();
        const p = window.tablePaginators && window.tablePaginators['tableBarang'];
        if (p) p.reinit();
    } else if (tabId === 'peminjaman') {
        renderTablePeminjaman();
        renderTableLogPeminjaman();
        const p1 = window.tablePaginators && window.tablePaginators['tablePeminjaman'];
        if (p1) p1.reinit();
        const p2 = window.tablePaginators && window.tablePaginators['tableLogPeminjaman'];
        if (p2) p2.reinit();
    } else if (tabId === 'barang-masuk') {
        renderTableBarangMasuk();
        const p = window.tablePaginators && window.tablePaginators['tableBarangMasuk'];
        if (p) p.reinit();
    } else if (tabId === 'barang-keluar') {
        renderTableBarangKeluar();
        const p = window.tablePaginators && window.tablePaginators['tableBarangKeluar'];
        if (p) p.reinit();
    } else if (tabId === 'pengguna') {
        renderTablePengguna();
        if (typeof initTabAnalytics === 'function') initTabAnalytics('pengguna');
    } else if (tabId === 'jurusan') {
        renderTableJurusan();
        const p = window.tablePaginators && window.tablePaginators['tableJurusan'];
        if (p) p.reinit();
    } else if (tabId === 'kategori') {
        renderTableKategori();
        const p = window.tablePaginators && window.tablePaginators['tableKategori'];
        if (p) p.reinit();
    } else if (tabId === 'rak') {
        renderTableRak();
        const p = window.tablePaginators && window.tablePaginators['tableRak'];
        if (p) p.reinit();
    } else if (tabId === 'log-aktivitas') {
        renderTableLogAktivitas();
        const p = window.tablePaginators && window.tablePaginators['tableLogAktivitas'];
        if (p) p.reinit();
    } else if (tabId === 'dashboard') {
        if (typeof initInventoryChart === 'function') initInventoryChart();
        if (typeof renderRecentLogMasukKeluar === 'function') renderRecentLogMasukKeluar();
        if (typeof renderDashboardRecentPeminjaman === 'function') renderDashboardRecentPeminjaman();
        if (typeof renderDashboardRecentActivities === 'function') renderDashboardRecentActivities();
    }
}

function renderAllTableBodies() {
    const currentTab = (new URLSearchParams(window.location.search).get('tab') || 'dashboard');
    renderActiveTabTable(currentTab);
    if (typeof renderRecentLogMasukKeluar === 'function') renderRecentLogMasukKeluar();
    if (typeof renderDashboardRecentPeminjaman === 'function') renderDashboardRecentPeminjaman();
    if (typeof renderDashboardRecentActivities === 'function') renderDashboardRecentActivities();
}

let isFetchingFreshData = false;
let freshDataDebounceTimer = null;

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
            window.dbGuru = data.dbGuru || window.dbGuru || [];
            window.dbSiswa = data.dbSiswa || window.dbSiswa || [];
            window.dbKategori = data.dbKategori || [];
            window.dbRak = data.dbRak || [];
            window.dbBarang = data.dbBarang || [];
            window.dbBarangMasuk = data.dbBarangMasuk || [];
            window.dbBarangKeluar = data.dbBarangKeluar || [];
            window.dbPeminjaman = data.dbPeminjaman || [];
            window.dbLogAktivitas = data.dbLogAktivitas || [];

            updateStatCardsData();
            const currentTab = tabId || (new URLSearchParams(window.location.search).get('tab') || 'dashboard');
            renderActiveTabTable(currentTab);
            if (typeof initTabAnalytics === 'function') initTabAnalytics(currentTab);
        }
    } catch (err) {
        console.error('Error fetching fresh data on navbar tab switch:', err);
    } finally {
        isFetchingFreshData = false;
    }
}

function toggleProfilePopover(popoverId, arrowId, event) {
    if (event) {
        event.stopPropagation();
    }
    const popover = document.getElementById(popoverId);
    const arrow = document.getElementById(arrowId);
    if (!popover) return;

    const isHidden = popover.classList.contains('hidden');

    // Close all profile dropdown popovers first
    closeAllProfileDropdowns();

    if (isHidden) {
        popover.classList.remove('hidden');
        if (arrow) arrow.classList.add('rotate-180');
    }
}

function closeAllProfileDropdowns() {
    ['dashboardProfilePopover', 'topHeaderProfilePopover'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.classList.add('hidden');
    });
    ['dashboardProfileArrow', 'topHeaderProfileArrow'].forEach(id => {
        const arrow = document.getElementById(id);
        if (arrow) arrow.classList.remove('rotate-180');
    });
}

function goToSettingsProfile() {
    closeAllProfileDropdowns();
    if (typeof switchTab === 'function') {
        switchTab('pengaturan-profil');
    }
    const mainContainer = document.querySelector('main');
    if (mainContainer) {
        mainContainer.scrollTo({ top: 0, behavior: 'smooth' });
    }
}

// Global outside-click listener & ESC key listener for profile popovers
window.addEventListener('click', function(e) {
    const dContainer = document.getElementById('dashboardProfileContainer');
    const tContainer = document.getElementById('topHeaderProfileContainer');
    const clickedInsideD = dContainer && dContainer.contains(e.target);
    const clickedInsideT = tContainer && tContainer.contains(e.target);

    if (!clickedInsideD && !clickedInsideT) {
        closeAllProfileDropdowns();
    }
});

window.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAllProfileDropdowns();
    }
});

function switchTab(tabId) {
    if (!tabId) tabId = 'dashboard';
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

    // Render immediately from in-memory data
    renderActiveTabTable(tabId);

    // Debounce background sync so rapid navigation does not block browser
    clearTimeout(freshDataDebounceTimer);
    freshDataDebounceTimer = setTimeout(() => {
        fetchFreshDataAndRefreshUI(tabId);
    }, 500);
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
        this.allRows = Array.from(this.tbody.querySelectorAll('tr:not(.empty-filter-row)'));
        this.update();
    }

    renderControls() {
        if (this.table.id !== 'tableLogAktivitas' && this.table.id !== 'tableBarang') {
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
            footer.className = `pagination-footer-${this.table.id} mt-4 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 dark:text-slate-400 pt-3 border-t border-slate-100 dark:border-[#262626] gap-3`;
            footer.innerHTML = `
                <div class="flex items-center gap-2.5">
                    <span class="font-medium info-text text-slate-600 dark:text-slate-300">Menampilkan 0 data</span>
                    <div class="flex items-center gap-1.5 ml-2">
                        <span class="text-[11px] text-slate-400">Tampilkan:</span>
                        <select class="page-size-select bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-[#2a2a2a] rounded-lg px-2.5 py-1 text-xs font-semibold text-slate-700 dark:text-slate-200 focus:outline-none focus:border-sage-600 cursor-pointer">
                            <option value="5" ${this.pageSize === 5 ? 'selected' : ''}>5 / hal</option>
                            <option value="10" ${this.pageSize === 10 ? 'selected' : ''}>10 / hal</option>
                            <option value="25" ${this.pageSize === 25 ? 'selected' : ''}>25 / hal</option>
                            <option value="50" ${this.pageSize === 50 ? 'selected' : ''}>50 / hal</option>
                            <option value="100" ${this.pageSize === 100 ? 'selected' : ''}>100 / hal</option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center gap-1 pagination-btns flex-wrap justify-center"></div>
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

            // Custom Filters for tableBarang
            if (this.customJenisFilter) {
                const rowJenis = (row.getAttribute('data-jenis') || '').toLowerCase();
                if (rowJenis !== this.customJenisFilter) return false;
            }

            if (this.customKategoriFilter) {
                const rowKategori = row.getAttribute('data-kategori-id') || '';
                if (this.customKategoriFilter === '__none__') {
                    if (rowKategori !== '' && rowKategori !== '0' && rowKategori !== 'null') return false;
                } else if (String(rowKategori) !== String(this.customKategoriFilter)) {
                    return false;
                }
            }

            if (this.customRakFilter) {
                const rowRak = row.getAttribute('data-rak-id') || '';
                if (this.customRakFilter === '__none__') {
                    if (rowRak !== '' && rowRak !== '0' && rowRak !== 'null') return false;
                } else if (String(rowRak) !== String(this.customRakFilter)) {
                    return false;
                }
            }

            if (this.customBarangJurusanFilter) {
                const rowJur = row.getAttribute('data-jurusan-id') || '';
                if (String(rowJur) !== String(this.customBarangJurusanFilter)) return false;
            }

            if (!this.searchQuery) return true;
            return row.innerText.toLowerCase().includes(this.searchQuery);
        });

        this.allRows.forEach(row => row.style.display = 'none');

        const totalFiltered = this.filteredRows.length;
        const totalPages = Math.ceil(totalFiltered / this.pageSize) || 1;

        if (this.currentPage > totalPages) this.currentPage = totalPages;
        if (this.currentPage < 1) this.currentPage = 1;

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

        let emptyRow = this.tbody.querySelector('.empty-filter-row');
        if (totalFiltered === 0) {
            if (!emptyRow) {
                const cols = this.table.querySelectorAll('thead th').length || 10;
                emptyRow = document.createElement('tr');
                emptyRow.className = 'empty-filter-row';
                emptyRow.innerHTML = `<td colspan="${cols}" class="py-8 text-center text-slate-400 font-semibold">Tidak ada data alat & bahan yang cocok dengan filter atau pencarian.</td>`;
                this.tbody.appendChild(emptyRow);
            } else {
                emptyRow.style.display = '';
            }
        } else if (emptyRow) {
            emptyRow.style.display = 'none';
        }

        if (this.footer) {
            const infoText = this.footer.querySelector('.info-text');
            if (infoText) {
                if (totalFiltered === 0) {
                    infoText.innerHTML = 'Data tidak ditemukan';
                } else {
                    infoText.innerHTML = `Menampilkan <strong class="text-slate-800 dark:text-white">${startIdx + 1} - ${endIdx}</strong> dari <strong class="text-slate-800 dark:text-white">${totalFiltered.toLocaleString('id-ID')}</strong> data (Hal <strong class="text-slate-800 dark:text-white">${this.currentPage}</strong> / ${totalPages})`;
                }
            }

            const btnsContainer = this.footer.querySelector('.pagination-btns');
            if (btnsContainer) {
                btnsContainer.innerHTML = '';
                if (totalPages <= 1) {
                    // Only 1 page or none, no buttons needed
                } else {
                    const createBtn = (page, label = null, isActive = false, isDisabled = false) => {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.innerText = label || page;
                        btn.disabled = isDisabled;

                        if (isActive) {
                            btn.className = 'px-2.5 py-1 rounded-lg bg-sage-600 text-white font-bold text-xs shadow-sm';
                        } else if (isDisabled) {
                            btn.className = 'px-2.5 py-1 rounded-lg border border-slate-200 dark:border-[#262626] text-xs font-bold text-slate-300 dark:text-slate-600 opacity-40 cursor-not-allowed';
                        } else {
                            btn.className = 'px-2.5 py-1 rounded-lg border border-slate-200 dark:border-[#2a2a2a] text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-sage-100 dark:hover:bg-[#222222] transition-colors cursor-pointer';
                            btn.onclick = () => {
                                this.currentPage = page;
                                this.update();
                            };
                        }
                        return btn;
                    };

                    const createEllipsis = () => {
                        const span = document.createElement('span');
                        span.className = 'px-1 text-slate-400 dark:text-slate-500 font-bold select-none text-xs';
                        span.innerText = '...';
                        return span;
                    };

                    // Previous Button
                    btnsContainer.appendChild(createBtn(this.currentPage - 1, '« Prev', false, this.currentPage <= 1));

                    // Smart windowing: 1 ... 100
                    if (totalPages <= 7) {
                        for (let p = 1; p <= totalPages; p++) {
                            btnsContainer.appendChild(createBtn(p, null, p === this.currentPage));
                        }
                    } else {
                        // Always show Page 1
                        btnsContainer.appendChild(createBtn(1, null, this.currentPage === 1));

                        if (this.currentPage <= 4) {
                            for (let p = 2; p <= 5; p++) {
                                btnsContainer.appendChild(createBtn(p, null, p === this.currentPage));
                            }
                            btnsContainer.appendChild(createEllipsis());
                        } else if (this.currentPage >= totalPages - 3) {
                            btnsContainer.appendChild(createEllipsis());
                            for (let p = totalPages - 4; p <= totalPages - 1; p++) {
                                btnsContainer.appendChild(createBtn(p, null, p === this.currentPage));
                            }
                        } else {
                            btnsContainer.appendChild(createEllipsis());
                            for (let p = this.currentPage - 1; p <= this.currentPage + 1; p++) {
                                btnsContainer.appendChild(createBtn(p, null, p === this.currentPage));
                            }
                            btnsContainer.appendChild(createEllipsis());
                        }

                        // Always show last page
                        btnsContainer.appendChild(createBtn(totalPages, null, this.currentPage === totalPages));
                    }

                    // Next Button
                    btnsContainer.appendChild(createBtn(this.currentPage + 1, 'Next »', false, this.currentPage >= totalPages));
                }
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
    ['tableJurusan', 'tableKategori', 'tableRak', 'tableBarang', 'tableBarangMasuk', 'tableBarangKeluar', 'tablePeminjaman', 'tableLogPeminjaman', 'tableLogAktivitas'].forEach(id => {
        const defaultSize = (id === 'tableBarang') ? 10 : 5;
        window.tablePaginators[id] = new TablePaginationManager(id, defaultSize);
    });

    filterTableBarang();
    filterTableLogAktivitas();
});

let searchBarangDebounceTimer = null;
function debouncedFilterTableBarang() {
    clearTimeout(searchBarangDebounceTimer);
    searchBarangDebounceTimer = setTimeout(filterTableBarang, 150);
}

function filterTableBarang() {
    const paginator = window.tablePaginators && window.tablePaginators['tableBarang'];
    if (!paginator) return;

    paginator.customJenisFilter = (document.getElementById('filter_barang_jenis')?.value || '').toLowerCase().trim();
    paginator.customKategoriFilter = (document.getElementById('filter_barang_kategori')?.value || '').trim();
    paginator.customRakFilter = (document.getElementById('filter_barang_rak')?.value || '').trim();
    paginator.customBarangJurusanFilter = (document.getElementById('filter_barang_jurusan')?.value || '').trim();
    paginator.searchQuery = (document.getElementById('filter_barang_search')?.value || '').toLowerCase().trim();

    paginator.currentPage = 1;
    paginator.update();
}

function resetBarangFilters() {
    const elSearch = document.getElementById('filter_barang_search');
    const elJenis = document.getElementById('filter_barang_jenis');
    const elKat = document.getElementById('filter_barang_kategori');
    const elRak = document.getElementById('filter_barang_rak');
    const elJur = document.getElementById('filter_barang_jurusan');

    if (elSearch) elSearch.value = '';
    if (elJenis) elJenis.value = '';
    if (elKat) elKat.value = '';
    if (elRak) elRak.value = '';
    if (elJur) elJur.value = '';

    filterTableBarang();
}

function refreshBarangFilterDropdowns() {
    const selKat = document.getElementById('filter_barang_kategori');
    const jurVal = document.getElementById('filter_barang_jurusan')?.value || '';

    if (selKat && window.dbKategori) {
        const curVal = selKat.value;
        let filteredKat = window.dbKategori;
        if (jurVal) {
            filteredKat = filteredKat.filter(k => String(k.jurusan_id) === String(jurVal));
        }
        let katHtml = '<option value="">Semua Kategori</option>';
        filteredKat.forEach(k => {
            katHtml += `<option value="${k.id}">${escapeHtml(k.nama_kategori)}</option>`;
        });
        katHtml += '<option value="__none__">Tanpa Kategori</option>';
        selKat.innerHTML = katHtml;
        selKat.value = curVal || '';
    }

    const selRak = document.getElementById('filter_barang_rak');
    if (selRak && window.dbRak) {
        const curVal = selRak.value;
        let filteredRak = window.dbRak;
        if (jurVal) {
            filteredRak = filteredRak.filter(r => String(r.jurusan_id) === String(jurVal));
        }
        let rakHtml = '<option value="">Semua Rak</option>';
        filteredRak.forEach(r => {
            const extra = r.kategori_rak ? ` (${r.kategori_rak})` : '';
            rakHtml += `<option value="${r.id}">${escapeHtml(r.nama_rak + extra)}</option>`;
        });
        rakHtml += '<option value="__none__">Tanpa Rak</option>';
        selRak.innerHTML = rakHtml;
        selRak.value = curVal || '';
    }
}

function onBarangJurusanFilterChange() {
    refreshBarangFilterDropdowns();
    const selKat = document.getElementById('filter_barang_kategori');
    const selRak = document.getElementById('filter_barang_rak');
    if (selKat) selKat.value = '';
    if (selRak) selRak.value = '';
    filterTableBarang();
}

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
