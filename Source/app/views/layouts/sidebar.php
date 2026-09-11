<?php 
require_once __DIR__ . '/../../models/Pengguna.php';
$user = currentUser(); 
$roleName = $user['peran'] ?? 'siswa';
$userAccessibleJurusans = Pengguna::getAccessibleJurusans($user['id'] ?? '');
$activeJurusanId = $user['jurusan_id'] ?? '';

$brandShort = 'Sekolah';
if (!empty($user['peran']) && $user['peran'] === 'admin_sekolah') {
    $brandShort = 'Sekolah';
} elseif (!empty($user['nama_jurusan'])) {
    if (preg_match('/\(([^)]+)\)/', $user['nama_jurusan'], $m)) {
        $brandShort = $m[1];
    } else {
        $brandShort = $user['nama_jurusan'];
    }
}

if ($roleName === 'admin_sekolah') {
    $peranText = 'Admin Sekolah';
} elseif ($roleName === 'kabeng' || $roleName === 'admin_jurusan') {
    $peranText = 'Kabeng (' . $brandShort . ')';
} elseif ($roleName === 'guru_jurusan' || $roleName === 'petugas') {
    $peranText = 'Guru Jurusan';
} elseif ($roleName === 'guru_umum') {
    $peranText = 'Guru Umum';
} else {
    $peranText = 'Siswa';
}
?>
<!-- Mobile Sidebar Backdrop Overlay -->
<div id="sidebarBackdrop" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-30 hidden lg:hidden transition-opacity duration-300 opacity-0 pointer-events-none"></div>

<!-- Sidebar Navigation - Collapsible & Responsive Mobile Drawer -->
<aside id="mainSidebar" class="relative w-[265px] bg-white dark:bg-[#121212] text-slate-700 dark:text-slate-200 h-full flex flex-col flex-shrink-0 border-r border-slate-100 dark:border-[#202020] shadow-xl lg:shadow-none z-40 transition-all duration-300 fixed lg:static inset-y-0 left-0 -translate-x-full lg:translate-x-0">
    
    <!-- Brand Logo Area & Toggle Button -->
    <div class="sidebar-brand-header px-4 py-3.5 border-b border-slate-100 dark:border-[#202020] flex items-center justify-between gap-2 flex-shrink-0 h-16 transition-all duration-300">
        <!-- Expanded Logo View -->
        <div class="brand-header-full flex items-center gap-3 overflow-hidden min-w-0">
            <div class="w-10 h-10 rounded-2xl shrink-0 flex items-center justify-center text-white shadow-sm transition-all duration-300" style="background-color: <?= $activeThemePalette['600'] ?? '#16a34a'; ?>;">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div class="sidebar-text truncate flex flex-col justify-center min-w-0">
                <h1 class="text-sm font-bold text-slate-900 dark:text-white tracking-tight leading-tight truncate">Gudang <?= htmlspecialchars($brandShort); ?></h1>
                <p class="text-[11px] font-medium text-slate-400 dark:text-slate-400 leading-tight mt-0.5 truncate">Sistem Inventaris</p>
            </div>
        </div>

        <!-- Collapsed Mini Logo View -->
        <div class="brand-header-mini hidden items-center justify-center mx-auto cursor-pointer group" title="Perluas Sidebar">
            <div class="w-9 h-9 rounded-xl shrink-0 flex items-center justify-center text-white shadow-sm group-hover:scale-105 active:scale-95 transition-transform" style="background-color: <?= $activeThemePalette['600'] ?? '#16a34a'; ?>;">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
        </div>

        <!-- Header Action Buttons -->
        <div class="flex items-center gap-1.5 shrink-0 ml-auto">
            <!-- Switch Jurusan Dropdown Trigger (Hanya jika bukan admin_sekolah dan memiliki > 1 Jurusan yang dapat diakses) -->
            <?php if ($roleName !== 'admin_sekolah' && !empty($userAccessibleJurusans) && count($userAccessibleJurusans) > 1): ?>
            <button type="button" id="jurusanSwitcherBtn" onclick="toggleJurusanSwitcherPopover(event)" class="w-8 h-8 rounded-xl bg-slate-100/80 hover:bg-slate-200/80 dark:bg-[#1e1e1e] dark:hover:bg-[#282828] text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 flex items-center justify-center transition-all cursor-pointer border border-slate-200/60 dark:border-[#2a2a2a]" title="Ganti Gudang Jurusan Aktif">
                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
            </button>
            <?php endif; ?>

            <!-- Toggle Button for Desktop Mini Mode -->
            <button type="button" id="sidebarToggleBtn" class="w-8 h-8 rounded-xl bg-slate-100/80 hover:bg-slate-200/80 dark:bg-[#1e1e1e] dark:hover:bg-[#282828] text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 flex items-center justify-center transition-all shrink-0 cursor-pointer" title="Kecilkan Sidebar">
                <svg class="w-4 h-4 transition-transform duration-300" id="toggleIcon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>

            <!-- Mobile Close Button -->
            <button type="button" onclick="closeMobileSidebar()" class="lg:hidden w-8 h-8 rounded-full bg-slate-100 hover:bg-red-50 dark:bg-[#1e1e1e] dark:hover:bg-red-950/40 text-slate-400 hover:text-red-500 flex items-center justify-center transition-colors shrink-0" title="Tutup Menu">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Dropdown Popover List of Jurusans (Clean design, no badge, no emoji) -->
    <?php if ($roleName !== 'admin_sekolah' && !empty($userAccessibleJurusans) && count($userAccessibleJurusans) > 1): ?>
    <div id="jurusanSwitcherPopover" class="hidden absolute left-3 right-3 top-[68px] bg-white dark:bg-[#181818] border border-slate-200 dark:border-[#282828] rounded-2xl shadow-2xl z-50 p-1.5 text-slate-700 dark:text-slate-200 text-left">
        <div class="px-2.5 py-1.5 border-b border-slate-100 dark:border-[#242424] mb-1">
            <span class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Pilih Gudang Jurusan</span>
        </div>
        <div class="space-y-0.5 max-h-60 overflow-y-auto" style="scrollbar-width: thin;">
            <?php if ($roleName === 'admin_sekolah'): 
                $isAllActive = empty($_SESSION['active_jurusan_id']);
            ?>
            <button type="button" onclick="switchActiveJurusan('')" class="w-full flex items-center justify-between px-2.5 py-2 rounded-xl text-xs transition-all cursor-pointer <?= $isAllActive ? 'bg-slate-100 dark:bg-[#252525] font-semibold text-slate-900 dark:text-white' : 'hover:bg-slate-50 dark:hover:bg-[#1e1e1e] text-slate-600 dark:text-slate-300 font-normal' ?>">
                <div class="flex items-center gap-2.5 min-w-0 truncate">
                    <span class="w-2.5 h-2.5 rounded-full shrink-0 bg-slate-400 dark:bg-slate-500"></span>
                    <span class="truncate">Semua Gudang Jurusan</span>
                </div>
                <?php if ($isAllActive): ?>
                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0 ml-1.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                <?php endif; ?>
            </button>
            <?php endif; ?>

            <?php foreach ($userAccessibleJurusans as $aj): 
                $isActive = ($aj['id'] === $activeJurusanId);
                $ajColor = resolveJurusanThemeColor($aj['warna_tema'] ?? '#2e7d32');
            ?>
            <button type="button" onclick="switchActiveJurusan('<?= $aj['id']; ?>')" class="w-full flex items-center justify-between px-2.5 py-2 rounded-xl text-xs transition-all cursor-pointer <?= $isActive ? 'bg-slate-100 dark:bg-[#252525] font-semibold text-slate-900 dark:text-white' : 'hover:bg-slate-50 dark:hover:bg-[#1e1e1e] text-slate-600 dark:text-slate-300 font-normal' ?>">
                <div class="flex items-center gap-2.5 min-w-0 truncate">
                    <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: <?= htmlspecialchars($ajColor); ?>;"></span>
                    <span class="truncate <?= $isActive ? 'font-semibold' : '' ?>"><?= htmlspecialchars($aj['nama_jurusan']); ?></span>
                </div>
                <?php if ($isActive): ?>
                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0 ml-1.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                <?php endif; ?>
            </button>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Navigation Menu Scrollable -->
    <nav class="flex-1 px-3 py-2 space-y-1 overflow-y-auto overflow-x-hidden text-sm" id="sidebarNav">
        
        <!-- MAIN MENU SECTION -->
        <div class="sidebar-section-container px-3.5 pt-2 pb-1.5">
            <span class="sidebar-section-label text-[11px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">MAIN MENU</span>
        </div>

        <!-- Dashboard (Default Active) -->
        <button type="button" data-tab="dashboard" class="nav-tab-btn active w-full flex items-center gap-3.5 px-3.5 py-2.5 rounded-2xl text-[13.5px] font-semibold text-left transition-all" title="Dashboard">
            <svg class="w-5 h-5 shrink-0 transition-colors" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="sidebar-text whitespace-nowrap text-left leading-none">Dashboard</span>
        </button>

        <!-- Data Jurusan (Admin Sekolah Only) -->
        <?php if (!empty($user['peran']) && $user['peran'] === 'admin_sekolah'): ?>
        <button type="button" data-tab="jurusan" class="nav-tab-btn w-full flex items-center gap-3.5 px-3.5 py-2.5 rounded-2xl text-[13.5px] font-medium text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100 hover:bg-slate-50/80 dark:hover:bg-[#1a1a1a] text-left transition-all" title="Data Jurusan">
            <svg class="w-5 h-5 shrink-0 transition-colors" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 21h8M12 21v-4a2 2 0 012-2h0a2 2 0 012 2v4M5 21V7a2 2 0 012-2h10a2 2 0 012 2v14M5 21h14"/>
            </svg>
            <span class="sidebar-text whitespace-nowrap text-left leading-none">Data Jurusan</span>
        </button>
        <?php endif; ?>

        <!-- Data Pengguna (Admin Sekolah & Kabeng) -->
        <?php if (in_array($roleName, ['admin_sekolah', 'kabeng', 'admin_jurusan'])): ?>
        <button type="button" data-tab="pengguna" class="nav-tab-btn w-full flex items-center gap-3.5 px-3.5 py-2.5 rounded-2xl text-[13.5px] font-medium text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100 hover:bg-slate-50/80 dark:hover:bg-[#1a1a1a] text-left transition-all" title="Data Pengguna">
            <svg class="w-5 h-5 shrink-0 transition-colors" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <span class="sidebar-text whitespace-nowrap text-left leading-none">Data Pengguna</span>
        </button>

        <!-- Data Guru (Admin Sekolah & Kabeng) -->
        <button type="button" data-tab="guru" class="nav-tab-btn w-full flex items-center gap-3.5 px-3.5 py-2.5 rounded-2xl text-[13.5px] font-medium text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100 hover:bg-slate-50/80 dark:hover:bg-[#1a1a1a] text-left transition-all" title="Data Guru">
            <svg class="w-5 h-5 shrink-0 transition-colors" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14v4.5"/>
            </svg>
            <span class="sidebar-text whitespace-nowrap text-left leading-none">Data Guru</span>
        </button>

        <!-- Data Siswa (Admin Sekolah & Kabeng) -->
        <button type="button" data-tab="siswa" class="nav-tab-btn w-full flex items-center gap-3.5 px-3.5 py-2.5 rounded-2xl text-[13.5px] font-medium text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100 hover:bg-slate-50/80 dark:hover:bg-[#1a1a1a] text-left transition-all" title="Data Siswa">
            <svg class="w-5 h-5 shrink-0 transition-colors" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <span class="sidebar-text whitespace-nowrap text-left leading-none">Data Siswa</span>
        </button>
        <?php endif; ?>

        <!-- Data Kategori (Admin Sekolah, Kabeng, Guru Jurusan) -->
        <?php if (in_array($roleName, ['admin_sekolah', 'kabeng', 'admin_jurusan', 'guru_jurusan', 'petugas'])): ?>
        <button type="button" data-tab="kategori" class="nav-tab-btn w-full flex items-center gap-3.5 px-3.5 py-2.5 rounded-2xl text-[13.5px] font-medium text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100 hover:bg-slate-50/80 dark:hover:bg-[#1a1a1a] text-left transition-all" title="Kategori Barang">
            <svg class="w-5 h-5 shrink-0 transition-colors" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h.01M4 12h.01M4 18h.01M8 6h12M8 12h12M8 18h12"/>
            </svg>
            <span class="sidebar-text whitespace-nowrap text-left leading-none">Kategori Barang</span>
        </button>
        <?php endif; ?>

        <!-- Rak & Lemari (Admin Sekolah, Kabeng, Guru Jurusan) -->
        <?php if (in_array($roleName, ['admin_sekolah', 'kabeng', 'admin_jurusan', 'guru_jurusan', 'petugas'])): ?>
        <button type="button" data-tab="rak" class="nav-tab-btn w-full flex items-center gap-3.5 px-3.5 py-2.5 rounded-2xl text-[13.5px] font-medium text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100 hover:bg-slate-50/80 dark:hover:bg-[#1a1a1a] text-left transition-all" title="Rak & Lemari">
            <svg class="w-5 h-5 shrink-0 transition-colors" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v1a2 2 0 01-2 2M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
            </svg>
            <span class="sidebar-text whitespace-nowrap text-left leading-none">Rak & Lemari</span>
        </button>
        <?php endif; ?>

        <!-- Master Barang & Barcode (Semua Peran) -->
        <button type="button" data-tab="barang" class="nav-tab-btn w-full flex items-center gap-3.5 px-3.5 py-2.5 rounded-2xl text-[13.5px] font-medium text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100 hover:bg-slate-50/80 dark:hover:bg-[#1a1a1a] text-left transition-all" title="Alat & Bahan">
            <svg class="w-5 h-5 shrink-0 transition-colors" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <span class="sidebar-text whitespace-nowrap text-left leading-none">Alat & Bahan</span>
        </button>

        <!-- TRANSAKSI & OPERASIONAL SECTION -->
        <div class="sidebar-section-container pt-3 pb-1.5 px-3">
            <div class="sidebar-divider h-px bg-slate-100 dark:bg-[#202020] mb-3"></div>
            <span class="sidebar-section-label px-0.5 text-[11px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">TRANSAKSI & OPERASIONAL</span>
        </div>

        <!-- Barang Masuk (Admin Sekolah, Kabeng, Guru Jurusan) -->
        <?php if (in_array($roleName, ['admin_sekolah', 'kabeng', 'admin_jurusan', 'guru_jurusan', 'petugas'])): ?>
        <button type="button" data-tab="barang-masuk" class="nav-tab-btn w-full flex items-center gap-3.5 px-3.5 py-2.5 rounded-2xl text-[13.5px] font-medium text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100 hover:bg-slate-50/80 dark:hover:bg-[#1a1a1a] text-left transition-all" title="Alat & Bahan Masuk">
            <svg class="w-5 h-5 shrink-0 transition-colors" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            <span class="sidebar-text whitespace-nowrap text-left leading-none">Alat & Bahan Masuk</span>
        </button>

        <!-- Barang Keluar (Admin Sekolah, Kabeng, Guru Jurusan) -->
        <button type="button" data-tab="barang-keluar" class="nav-tab-btn w-full flex items-center gap-3.5 px-3.5 py-2.5 rounded-2xl text-[13.5px] font-medium text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100 hover:bg-slate-50/80 dark:hover:bg-[#1a1a1a] text-left transition-all" title="Bahan Keluar">
            <svg class="w-5 h-5 shrink-0 transition-colors" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
            <span class="sidebar-text whitespace-nowrap text-left leading-none">Bahan Keluar</span>
        </button>
        <?php endif; ?>

        <!-- Sirkulasi Peminjaman (Semua Peran) -->
        <button type="button" data-tab="peminjaman" class="nav-tab-btn w-full flex items-center gap-3.5 px-3.5 py-2.5 rounded-2xl text-[13.5px] font-medium text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100 hover:bg-slate-50/80 dark:hover:bg-[#1a1a1a] text-left transition-all" title="Peminjaman Alat">
            <svg class="w-5 h-5 shrink-0 transition-colors" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
            </svg>
            <span class="sidebar-text whitespace-nowrap text-left leading-none">Peminjaman Alat</span>
        </button>

        <!-- Log Aktivitas (Admin Sekolah & Kabeng) -->
        <?php if (in_array($roleName, ['admin_sekolah', 'kabeng', 'admin_jurusan'])): ?>
        <button type="button" data-tab="log-aktivitas" class="nav-tab-btn w-full flex items-center gap-3.5 px-3.5 py-2.5 rounded-2xl text-[13.5px] font-medium text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100 hover:bg-slate-50/80 dark:hover:bg-[#1a1a1a] text-left transition-all" title="Log Aktivitas">
            <svg class="w-5 h-5 shrink-0 transition-colors" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span class="sidebar-text whitespace-nowrap text-left leading-none">Log Aktivitas</span>
        </button>
        <?php endif; ?>

        <!-- PENGATURAN SECTION -->
        <div class="sidebar-section-container pt-3 pb-1.5 px-3">
            <div class="sidebar-divider h-px bg-slate-100 dark:bg-[#202020] mb-3"></div>
            <span class="sidebar-section-label px-0.5 text-[11px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">PENGATURAN</span>
        </div>

        <!-- Pengaturan Profil -->
        <button type="button" data-tab="pengaturan-profil" class="nav-tab-btn w-full flex items-center gap-3.5 px-3.5 py-2.5 rounded-2xl text-[13.5px] font-medium text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100 hover:bg-slate-50/80 dark:hover:bg-[#1a1a1a] text-left transition-all" title="Pengaturan Profil">
            <svg class="w-5 h-5 shrink-0 transition-colors" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="sidebar-text whitespace-nowrap text-left leading-none">Pengaturan Profil</span>
        </button>

        <!-- Swagger API Routes (Khusus Admin Sekolah) -->
        <?php if (!empty($roleName) && $roleName === 'admin_sekolah'): ?>
        <button type="button" data-tab="swagger" class="nav-tab-btn w-full flex items-center gap-3.5 px-3.5 py-2.5 rounded-2xl text-[13.5px] font-medium text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100 hover:bg-slate-50/80 dark:hover:bg-[#1a1a1a] text-left transition-all" title="Dokumentasi Swagger API">
            <svg class="w-5 h-5 shrink-0 transition-colors text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
            </svg>
            <span class="sidebar-text whitespace-nowrap text-left leading-none font-bold text-emerald-700 dark:text-emerald-400">Swagger API Docs</span>
        </button>
        <?php endif; ?>

    </nav>

    <?php if (!empty($_SESSION['admin_sekolah_original_id'])): ?>
    <div class="p-2.5 bg-amber-500/10 border-t border-amber-500/20 text-center flex-shrink-0">
        <button type="button" onclick="revertImpersonation()" class="w-full py-2 px-3 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl text-xs shadow-none border-0 outline-none focus:outline-none ring-0 transition-colors flex items-center justify-center gap-1.5" style="box-shadow: none !important; filter: none !important;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z"/></svg>
            <span class="sidebar-text truncate">Kembali ke Admin Sekolah</span>
        </button>
    </div>
    <?php endif; ?>

    <!-- User Profile Floating Pill Card Footer -->
    <div class="p-3 pt-2 flex-shrink-0">
        <div class="sidebar-user-pill flex items-center justify-between w-full p-2 px-2.5 rounded-2xl bg-[#f8fafc] dark:bg-[#181818] border border-slate-200/60 dark:border-[#262626] transition-all">
            <div class="flex items-center gap-2.5 overflow-hidden">
                <div class="w-9 h-9 rounded-full shrink-0 flex items-center justify-center text-white font-bold text-sm shadow-sm overflow-hidden" style="background-color: <?= $activeThemePalette['600'] ?? '#16a34a'; ?>;">
                    <?php if (!empty($user['foto_url']) && file_exists(__DIR__ . '/../../../' . $user['foto_url'])): ?>
                        <img src="<?= htmlspecialchars($user['foto_url']);  ?>" class="w-full h-full object-cover" alt="Avatar">
                    <?php else: ?>
                        <?= strtoupper(substr($user['nama_lengkap'] ?? $user['nama_pengguna'] ?? 'U', 0, 1)); ?>
                    <?php endif; ?>
                </div>
                <div class="sidebar-text truncate flex flex-col justify-center">
                    <p class="sidebar-user-name text-xs font-bold text-slate-900 dark:text-white truncate leading-tight"><?= htmlspecialchars($user['nama_lengkap'] ?? $user['nama_pengguna'] ?? 'User'); ?></p>
                    <p class="sidebar-user-role text-[11px] font-medium leading-tight mt-0.5 truncate" style="color: <?= $activeThemePalette['600'] ?? '#16a34a'; ?>;"><?= htmlspecialchars($peranText); ?></p>
                </div>
            </div>
            <div class="sidebar-actions flex items-center gap-1 shrink-0">
                <button type="button" onclick="toggleTheme()" class="p-1 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors" title="Beralih Mode Gelap / Terang">
                    <svg class="themeSunIcon w-4 h-4 hidden text-slate-400 hover:text-amber-500 transition-colors" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <svg class="themeMoonIcon w-4 h-4 text-slate-400 hover:text-indigo-400 transition-colors" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>
                <div class="h-4 w-px bg-slate-200 dark:bg-[#303030] mx-0.5"></div>
                <a href="logout.php" class="p-1 text-slate-400 hover:text-red-500 dark:hover:text-red-400 transition-colors" title="Keluar">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 01-3-3H6a3 3 0 01-3 3v1"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</aside>

<style>
/* Sidebar Nav Buttons Styling */
.nav-tab-btn {
    color: #4b5563;
    transition: background-color 0.1s ease, color 0.1s ease;
}
.nav-tab-btn svg {
    color: #64748b;
    transition: color 0.1s ease;
}
.nav-tab-btn:hover {
    background-color: #f8fafc;
    color: #0f172a;
}
.nav-tab-btn:hover svg {
    color: #0f172a;
}

/* Active tab button - Light Mode: soft green pill with dark green icon & text (Instant Switch No Delay) */
.nav-tab-btn.active {
    background-color: <?= $activeThemePalette['50'] ?? '#ecf7ed'; ?> !important;
    color: <?= $activeThemePalette['700'] ?? '#15803d'; ?> !important;
    font-weight: 600 !important;
    transition: none !important;
}
.nav-tab-btn.active svg {
    color: <?= $activeThemePalette['700'] ?? '#15803d'; ?> !important;
    stroke-width: 2 !important;
    transition: none !important;
}
.nav-tab-btn.active .sidebar-text {
    color: <?= $activeThemePalette['700'] ?? '#15803d'; ?> !important;
    font-weight: 600 !important;
    transition: none !important;
}

/* Dark Mode Menu Styling */
html.dark .nav-tab-btn {
    color: #94a3b8 !important;
    transition: background-color 0.1s ease, color 0.1s ease;
}
html.dark .nav-tab-btn svg {
    color: #94a3b8 !important;
    transition: color 0.1s ease;
}
html.dark .nav-tab-btn:hover {
    background-color: #1a1a1a !important;
    color: #f8fafc !important;
}
html.dark .nav-tab-btn:hover svg {
    color: #f8fafc !important;
}

/* Dark Mode Active Pill: soft translucent emerald pill (Instant Switch No Delay) */
html.dark .nav-tab-btn.active {
    background-color: rgba(<?= implode(',', sscanf($activeThemePalette['500'] ?? '#10b981', "#%02x%02x%02x")); ?>, 0.18) !important;
    color: <?= $activeThemePalette['400'] ?? '#34d399'; ?> !important;
    font-weight: 600 !important;
    transition: none !important;
}
html.dark .nav-tab-btn.active svg {
    color: <?= $activeThemePalette['400'] ?? '#34d399'; ?> !important;
    stroke-width: 2 !important;
    transition: none !important;
}
html.dark .nav-tab-btn.active .sidebar-text {
    color: <?= $activeThemePalette['400'] ?? '#34d399'; ?> !important;
    font-weight: 600 !important;
    transition: none !important;
}

/* Section labels & dividers */
.sidebar-section-label {
    letter-spacing: 0.05em;
}

/* Mini Collapsed Sidebar (76px mode) */
#mainSidebar.collapsed {
    width: 76px !important;
}
#mainSidebar.collapsed .sidebar-text,
#mainSidebar.collapsed .sidebar-section-container,
#mainSidebar.collapsed .brand-header-full,
#mainSidebar.collapsed .sidebar-actions,
#mainSidebar.collapsed .sidebar-divider {
    display: none !important;
}
#mainSidebar.collapsed .sidebar-brand-header {
    flex-direction: column !important;
    height: auto !important;
    min-height: 94px !important;
    padding: 12px 8px !important;
    gap: 8px !important;
    justify-content: center !important;
    align-items: center !important;
}
#mainSidebar.collapsed .brand-header-mini {
    display: flex !important;
    margin: 0 auto !important;
}
#mainSidebar.collapsed #sidebarToggleBtn {
    display: flex !important;
    margin: 0 auto !important;
    width: 32px !important;
    height: 32px !important;
    border-radius: 10px !important;
}
#mainSidebar.collapsed #jurusanSwitcherBtn {
    display: flex !important;
    margin: 0 auto !important;
    width: 32px !important;
    height: 32px !important;
    border-radius: 10px !important;
}
#mainSidebar.collapsed #jurusanSwitcherPopover {
    left: 80px !important;
    right: auto !important;
    top: 12px !important;
    width: 230px !important;
}
#mainSidebar.collapsed #toggleIcon {
    transform: rotate(180deg) !important;
}
#mainSidebar.collapsed .sidebar-user-pill {
    padding: 4px !important;
    justify-content: center !important;
    background: transparent !important;
    border-color: transparent !important;
}
#mainSidebar.collapsed .nav-tab-btn {
    justify-content: center !important;
    padding-left: 0 !important;
    padding-right: 0 !important;
    width: 44px !important;
    height: 44px !important;
    margin: 2px auto !important;
    border-radius: 12px !important;
}

/* Hide horizontal scrollbar completely and stylize slim vertical scrollbar */
#sidebarNav {
    overflow-x: hidden !important;
    scrollbar-width: thin;
    scrollbar-color: rgba(148, 163, 184, 0.25) transparent;
}
#sidebarNav::-webkit-scrollbar {
    width: 4px;
    height: 0px !important;
}
#sidebarNav::-webkit-scrollbar-track {
    background: transparent;
}
#sidebarNav::-webkit-scrollbar-thumb {
    background-color: rgba(148, 163, 184, 0.25);
    border-radius: 9999px;
}
#sidebarNav::-webkit-scrollbar-thumb:hover {
    background-color: rgba(148, 163, 184, 0.5);
}
</style>

<script>
function openMobileSidebar() {
    const sidebar = document.getElementById('mainSidebar');
    const backdrop = document.getElementById('sidebarBackdrop');
    if (sidebar && backdrop) {
        sidebar.classList.remove('-translate-x-full');
        sidebar.classList.add('translate-x-0');
        backdrop.classList.remove('hidden', 'opacity-0', 'pointer-events-none');
        backdrop.classList.add('opacity-100', 'pointer-events-auto');
        document.body.style.overflow = 'hidden';
    }
}

function closeMobileSidebar() {
    const sidebar = document.getElementById('mainSidebar');
    const backdrop = document.getElementById('sidebarBackdrop');
    if (sidebar && backdrop) {
        sidebar.classList.remove('translate-x-0');
        sidebar.classList.add('-translate-x-full');
        backdrop.classList.remove('opacity-100', 'pointer-events-auto');
        backdrop.classList.add('opacity-0', 'pointer-events-none');
        setTimeout(() => backdrop.classList.add('hidden'), 300);
        document.body.style.overflow = '';
    }
}

function toggleMobileSidebar() {
    const sidebar = document.getElementById('mainSidebar');
    if (sidebar && sidebar.classList.contains('-translate-x-full')) {
        openMobileSidebar();
    } else {
        closeMobileSidebar();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('mainSidebar');
    const toggleBtn = document.getElementById('sidebarToggleBtn');
    const miniLogo = document.querySelector('.brand-header-mini');

    function toggleSidebarCollapse() {
        if (window.innerWidth >= 1024) {
            sidebar.classList.toggle('collapsed');
            if (toggleBtn) {
                if (sidebar.classList.contains('collapsed')) {
                    toggleBtn.setAttribute('title', 'Perluas Sidebar');
                } else {
                    toggleBtn.setAttribute('title', 'Kecilkan Sidebar');
                }
            }
        } else {
            closeMobileSidebar();
        }
    }

    if (sidebar && toggleBtn) {
        toggleBtn.addEventListener('click', toggleSidebarCollapse);
    }
    if (sidebar && miniLogo) {
        miniLogo.addEventListener('click', toggleSidebarCollapse);
    }

    // Auto close mobile menu when tab item clicked
    document.querySelectorAll('.nav-tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            if (window.innerWidth < 1024) {
                closeMobileSidebar();
            }
        });
    });
});

function toggleJurusanSwitcherPopover(e) {
    if (e) {
        e.preventDefault();
        e.stopPropagation();
    }
    const pop = document.getElementById('jurusanSwitcherPopover');
    if (!pop) return;
    pop.classList.toggle('hidden');
}

document.addEventListener('click', function(e) {
    const pop = document.getElementById('jurusanSwitcherPopover');
    const btn = document.getElementById('jurusanSwitcherBtn');
    if (pop && !pop.classList.contains('hidden')) {
        if (!pop.contains(e.target) && !btn?.contains(e.target)) {
            pop.classList.add('hidden');
        }
    }
});

async function switchActiveJurusan(jurusanId) {
    try {
        const res = await fetch(`api.php?action=switch_active_jurusan&jurusan_id=${encodeURIComponent(jurusanId)}`);
        const data = await res.json();
        if (data && data.success) {
            window.location.href = 'dashboard.php';
        } else {
            alert(data.message || 'Gagal berpindah ke jurusan terpilih.');
        }
    } catch (err) {
        console.error(err);
        alert('Terjadi kesalahan koneksi saat beralih jurusan.');
    }
}
</script>
