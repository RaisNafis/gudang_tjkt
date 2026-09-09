<?php 
$user = currentUser(); 
$roleName = $user['peran'] ?? 'siswa';

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
} elseif ($roleName === 'admin_jurusan') {
    $peranText = 'Admin Jurusan (' . $brandShort . ')';
} elseif ($roleName === 'petugas') {
    $peranText = 'Petugas Gudang';
} elseif ($roleName === 'guru_umum') {
    $peranText = 'Guru Umum';
} else {
    $peranText = 'Siswa';
}
?>
<!-- Mobile Sidebar Backdrop Overlay -->
<div id="sidebarBackdrop" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-30 hidden lg:hidden transition-opacity duration-300 opacity-0 pointer-events-none"></div>

<!-- Sidebar Navigation - Collapsible & Responsive Mobile Drawer -->
<aside id="mainSidebar" class="w-[270px] bg-white dark:bg-[#121212] text-slate-700 dark:text-slate-200 h-full flex flex-col flex-shrink-0 border-r border-sage-200 shadow-xl lg:shadow-sm z-40 transition-all duration-300 fixed lg:static inset-y-0 left-0 -translate-x-full lg:translate-x-0 overflow-x-hidden">
    
    <!-- Brand Logo Area & Toggle Button -->
    <div class="p-4 border-b border-sage-100 dark:border-[#262626] flex items-center justify-between gap-2 flex-shrink-0 h-16 overflow-hidden">
        <!-- Expanded Logo View -->
        <div class="brand-header-full flex items-center gap-3 overflow-hidden">
            <div class="w-9 h-9 rounded-xl overflow-hidden shadow-md shrink-0 flex items-center justify-center transition-all duration-300" style="background: linear-gradient(135deg, <?= $activeThemePalette['600'] ?? '#eab308'; ?>, <?= $activeThemePalette['700'] ?? '#ca8a04'; ?>); box-shadow: 0 4px 14px 0 rgba(<?= implode(',', sscanf($activeThemePalette['600'] ?? '#eab308', "#%02x%02x%02x")); ?>, 0.35);">
                <img src="assets/img/belmoti.svg" alt="Belmoti Logo" class="w-6 h-6 object-contain p-0.5 filter drop-shadow-sm" onerror="this.style.display='none'" loading="lazy" decoding="async">
            </div>
            <div class="sidebar-text truncate">
                <h1 class="text-sm font-extrabold text-slate-800 dark:text-white tracking-wide truncate">Gudang <?= htmlspecialchars($brandShort); ?></h1>
                <p class="text-[11px] font-medium truncate" style="color: <?= $activeThemePalette['600'] ?? '#eab308'; ?>;">Sistem Inventaris</p>
            </div>
        </div>

        <!-- Toggle Button for Desktop Mini Mode / Mobile Close -->
        <button type="button" id="sidebarToggleBtn" class="p-1.5 rounded-lg text-slate-400 hover:text-sage-600 hover:bg-sage-50 dark:hover:bg-[#202020] transition-colors shrink-0 mx-auto" title="Kecilkan / Perluas Sidebar">
            <svg class="w-5 h-5 transition-transform duration-300" id="toggleIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
            </svg>
        </button>
        <!-- Mobile Close Cross Button -->
        <button type="button" onclick="closeMobileSidebar()" class="lg:hidden p-1.5 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-950/40 transition-colors shrink-0" title="Tutup Menu">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Navigation Menu Scrollable -->
    <nav class="flex-1 p-3 space-y-1.5 overflow-y-auto overflow-x-hidden text-sm" id="sidebarNav">
        
        <div class="sidebar-section-label px-3 pb-1 pt-2 text-[10px] font-bold text-sage-600 uppercase tracking-wider">Main Menu</div>

        <!-- Dashboard (Default Active) -->
        <button type="button" data-tab="dashboard" class="nav-tab-btn active w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-left transition-all" title="Dashboard">
            <span class="sidebar-icon-mask w-5 h-5 shrink-0 transition-all inline-block" style="-webkit-mask-image: url('assets/img/icon-dashboard.svg'); mask-image: url('assets/img/icon-dashboard.svg'); -webkit-mask-size: contain; mask-size: contain; -webkit-mask-repeat: no-repeat; mask-repeat: no-repeat; -webkit-mask-position: center; mask-position: center;"></span>
            <span class="sidebar-text whitespace-nowrap text-left leading-none">Dashboard</span>
        </button>

        <!-- Data Jurusan (Admin Sekolah Only) -->
        <?php if (!empty($user['peran']) && $user['peran'] === 'admin_sekolah'): ?>
        <button type="button" data-tab="jurusan" class="nav-tab-btn w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-slate-600 hover:text-sage-700 hover:bg-sage-50 text-left transition-all" title="Data Jurusan">
            <span class="sidebar-icon-mask w-5 h-5 shrink-0 transition-all inline-block" style="-webkit-mask-image: url('assets/img/icon-jurusan.svg'); mask-image: url('assets/img/icon-jurusan.svg'); -webkit-mask-size: contain; mask-size: contain; -webkit-mask-repeat: no-repeat; mask-repeat: no-repeat; -webkit-mask-position: center; mask-position: center;"></span>
            <span class="sidebar-text whitespace-nowrap text-left leading-none">Data Jurusan</span>
        </button>
        <?php endif; ?>

        <!-- Data Pengguna (Admin Sekolah & Admin Jurusan) -->
        <?php if (in_array($roleName, ['admin_sekolah', 'admin_jurusan'])): ?>
        <button type="button" data-tab="pengguna" class="nav-tab-btn w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-slate-600 hover:text-sage-700 hover:bg-sage-50 text-left transition-all" title="Data Pengguna">
            <span class="sidebar-icon-mask w-5 h-5 shrink-0 transition-all inline-block" style="-webkit-mask-image: url('assets/img/icon-pengguna.svg'); mask-image: url('assets/img/icon-pengguna.svg'); -webkit-mask-size: contain; mask-size: contain; -webkit-mask-repeat: no-repeat; mask-repeat: no-repeat; -webkit-mask-position: center; mask-position: center;"></span>
            <span class="sidebar-text whitespace-nowrap text-left leading-none">Data Pengguna</span>
        </button>

        <!-- Data Guru (Admin Sekolah & Admin Jurusan) -->
        <button type="button" data-tab="guru" class="nav-tab-btn w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-slate-600 hover:text-sage-700 hover:bg-sage-50 text-left transition-all" title="Data Guru">
            <svg class="w-5 h-5 shrink-0 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
            <span class="sidebar-text whitespace-nowrap text-left leading-none">Data Guru</span>
        </button>

        <!-- Data Siswa (Admin Sekolah & Admin Jurusan) -->
        <button type="button" data-tab="siswa" class="nav-tab-btn w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-slate-600 hover:text-sage-700 hover:bg-sage-50 text-left transition-all" title="Data Siswa">
            <svg class="w-5 h-5 shrink-0 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            <span class="sidebar-text whitespace-nowrap text-left leading-none">Data Siswa</span>
        </button>
        <?php endif; ?>

        <!-- Data Kategori (Admin Sekolah, Admin Jurusan, Petugas) -->
        <?php if (in_array($roleName, ['admin_sekolah', 'admin_jurusan', 'petugas'])): ?>
        <button type="button" data-tab="kategori" class="nav-tab-btn w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-slate-600 hover:text-sage-700 hover:bg-sage-50 text-left transition-all" title="Kategori Barang">
            <span class="sidebar-icon-mask w-5 h-5 shrink-0 transition-all inline-block" style="-webkit-mask-image: url('assets/img/icon-kategori.svg'); mask-image: url('assets/img/icon-kategori.svg'); -webkit-mask-size: contain; mask-size: contain; -webkit-mask-repeat: no-repeat; mask-repeat: no-repeat; -webkit-mask-position: center; mask-position: center;"></span>
            <span class="sidebar-text whitespace-nowrap text-left leading-none">Kategori Barang</span>
        </button>
        <?php endif; ?>

        <!-- Data Rak (Admin Sekolah, Admin Jurusan, Petugas) -->
        <?php if (in_array($roleName, ['admin_sekolah', 'admin_jurusan', 'petugas'])): ?>
        <button type="button" data-tab="rak" class="nav-tab-btn w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-slate-600 hover:text-sage-700 hover:bg-sage-50 text-left transition-all" title="Data Rak Penyimpanan">
            <span class="sidebar-icon-mask w-5 h-5 shrink-0 transition-all inline-block" style="-webkit-mask-image: url('assets/img/icon-rak.svg'); mask-image: url('assets/img/icon-rak.svg'); -webkit-mask-size: contain; mask-size: contain; -webkit-mask-repeat: no-repeat; mask-repeat: no-repeat; -webkit-mask-position: center; mask-position: center;"></span>
            <span class="sidebar-text whitespace-nowrap text-left leading-none">Data Rak</span>
        </button>
        <?php endif; ?>

        <!-- Master Barang & Barcode (Semua Peran) -->
        <button type="button" data-tab="barang" class="nav-tab-btn w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-slate-600 hover:text-sage-700 hover:bg-sage-50 text-left transition-all" title="Alat & Bahan">
            <span class="sidebar-icon-mask w-5 h-5 shrink-0 transition-all inline-block" style="-webkit-mask-image: url('assets/img/icon-barang.svg'); mask-image: url('assets/img/icon-barang.svg'); -webkit-mask-size: contain; mask-size: contain; -webkit-mask-repeat: no-repeat; mask-repeat: no-repeat; -webkit-mask-position: center; mask-position: center;"></span>
            <span class="sidebar-text whitespace-nowrap text-left leading-none">Alat & Bahan</span>
        </button>

        <div class="sidebar-section-label px-3 pb-1 pt-3 text-[10px] font-bold text-sage-600 uppercase tracking-wider">Transaksi & Operasional</div>

        <!-- Barang Masuk (Admin Sekolah, Admin Jurusan, Petugas) -->
        <?php if (in_array($roleName, ['admin_sekolah', 'admin_jurusan', 'petugas'])): ?>
        <button type="button" data-tab="barang-masuk" class="nav-tab-btn w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-slate-600 hover:text-sage-700 hover:bg-sage-50 text-left transition-all" title="Alat & Bahan Masuk">
            <span class="sidebar-icon-mask w-5 h-5 shrink-0 transition-all inline-block" style="-webkit-mask-image: url('assets/img/icon-masuk.svg'); mask-image: url('assets/img/icon-masuk.svg'); -webkit-mask-size: contain; mask-size: contain; -webkit-mask-repeat: no-repeat; mask-repeat: no-repeat; -webkit-mask-position: center; mask-position: center;"></span>
            <span class="sidebar-text whitespace-nowrap text-left leading-none">Alat & Bahan Masuk</span>
        </button>

        <!-- Barang Keluar (Admin Sekolah, Admin Jurusan, Petugas) -->
        <button type="button" data-tab="barang-keluar" class="nav-tab-btn w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-slate-600 hover:text-sage-700 hover:bg-sage-50 text-left transition-all" title="Bahan Keluar">
            <span class="sidebar-icon-mask w-5 h-5 shrink-0 transition-all inline-block" style="-webkit-mask-image: url('assets/img/icon-keluar.svg'); mask-image: url('assets/img/icon-keluar.svg'); -webkit-mask-size: contain; mask-size: contain; -webkit-mask-repeat: no-repeat; mask-repeat: no-repeat; -webkit-mask-position: center; mask-position: center;"></span>
            <span class="sidebar-text whitespace-nowrap text-left leading-none">Bahan Keluar</span>
        </button>
        <?php endif; ?>

        <!-- Sirkulasi Peminjaman (Semua Peran) -->
        <button type="button" data-tab="peminjaman" class="nav-tab-btn w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-slate-600 hover:text-sage-700 hover:bg-sage-50 text-left transition-all" title="Peminjaman Alat">
            <span class="sidebar-icon-mask w-5 h-5 shrink-0 transition-all inline-block" style="-webkit-mask-image: url('assets/img/icon-peminjaman.svg'); mask-image: url('assets/img/icon-peminjaman.svg'); -webkit-mask-size: contain; mask-size: contain; -webkit-mask-repeat: no-repeat; mask-repeat: no-repeat; -webkit-mask-position: center; mask-position: center;"></span>
            <span class="sidebar-text whitespace-nowrap text-left leading-none">Peminjaman Alat</span>
        </button>

        <!-- Log Aktivitas (Admin Sekolah & Admin Jurusan) -->
        <?php if (in_array($roleName, ['admin_sekolah', 'admin_jurusan'])): ?>
        <button type="button" data-tab="log-aktivitas" class="nav-tab-btn w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-slate-600 hover:text-sage-700 hover:bg-sage-50 text-left transition-all" title="Log Aktivitas">
            <span class="sidebar-icon-mask w-5 h-5 shrink-0 transition-all inline-block" style="-webkit-mask-image: url('assets/img/icon-log.svg'); mask-image: url('assets/img/icon-log.svg'); -webkit-mask-size: contain; mask-size: contain; -webkit-mask-repeat: no-repeat; mask-repeat: no-repeat; -webkit-mask-position: center; mask-position: center;"></span>
            <span class="sidebar-text whitespace-nowrap text-left leading-none">Log Aktivitas</span>
        </button>
        <?php endif; ?>

        <!-- Kategori Pengaturan -->
        <div class="sidebar-section-label px-3 pb-1 pt-3 text-[10px] font-bold text-sage-600 uppercase tracking-wider">Pengaturan</div>

        <!-- Pengaturan Profil -->
        <button type="button" data-tab="pengaturan-profil" class="nav-tab-btn w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-slate-600 hover:text-sage-700 hover:bg-sage-50 text-left transition-all" title="Pengaturan Profil">
            <span class="sidebar-icon-mask w-5 h-5 shrink-0 transition-all inline-block" style="-webkit-mask-image: url('assets/img/icon-profil.svg'); mask-image: url('assets/img/icon-profil.svg'); -webkit-mask-size: contain; mask-size: contain; -webkit-mask-repeat: no-repeat; mask-repeat: no-repeat; -webkit-mask-position: center; mask-position: center;"></span>
            <span class="sidebar-text whitespace-nowrap text-left leading-none">Pengaturan Profil</span>
        </button>

        <!-- Swagger API Routes (Khusus Admin Sekolah) -->
        <?php if (!empty($roleName) && $roleName === 'admin_sekolah'): ?>
        <button type="button" data-tab="swagger" class="nav-tab-btn w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-slate-600 hover:text-sage-700 hover:bg-sage-50 text-left transition-all" title="Dokumentasi Swagger API">
            <svg class="w-5 h-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
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

    <!-- User Profile Footer -->
    <div class="p-3.5 border-t border-sage-100 bg-sage-50/60 flex-shrink-0">
        <div class="flex items-center justify-between w-full gap-2">
            <div class="flex items-center gap-3 overflow-hidden">
                <div class="w-9 h-9 rounded-full bg-sage-600 text-white font-bold flex items-center justify-center shrink-0 text-sm shadow-sm overflow-hidden">
                    <?php if (!empty($user['foto_url']) && file_exists(__DIR__ . '/../../../' . $user['foto_url'])): ?>
                        <img src="<?= htmlspecialchars($user['foto_url']);  ?>" class="w-full h-full object-cover" alt="Avatar">
                    <?php else: ?>
                        <?= strtoupper(substr($user['nama_lengkap'] ?? $user['nama_pengguna'] ?? 'U', 0, 1)); ?>
                    <?php endif; ?>
                </div>
                <div class="sidebar-text truncate flex flex-col justify-center">
                    <p class="sidebar-user-name text-xs font-extrabold text-slate-900 dark:text-white truncate leading-tight"><?= htmlspecialchars($user['nama_lengkap'] ?? $user['nama_pengguna'] ?? 'User'); ?></p>
                    <p class="sidebar-user-role text-[11px] font-bold text-sage-700 capitalize leading-tight mt-0.5"><?= htmlspecialchars($peranText); ?></p>
                </div>
            </div>
            <div class="flex items-center gap-1 shrink-0">
                <button type="button" onclick="toggleTheme()" class="sidebar-text p-1.5 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors" title="Beralih Mode Gelap / Terang">
                    <svg class="themeSunIcon w-4.5 h-4.5 hidden text-slate-700 dark:text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <svg class="themeMoonIcon w-4.5 h-4.5 text-slate-600 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>
                <a href="logout.php" class="sidebar-text p-1.5 text-slate-400 hover:text-red-600 transition-colors" title="Keluar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 01-3-3H6a3 3 0 01-3 3v1"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</aside>

<style>
/* Dynamic Active Menu Styling & White Mode Theme Text */
html:not(.dark) .nav-tab-btn {
    color: <?= $activeThemePalette['800'] ?? '#1b5e20'; ?> !important;
}
html:not(.dark) .nav-tab-btn span {
    color: <?= $activeThemePalette['800'] ?? '#1b5e20'; ?> !important;
    font-weight: 600 !important;
}
html:not(.dark) .nav-tab-btn:hover {
    background-color: <?= $activeThemePalette['50'] ?? '#f0fdf4'; ?> !important;
    color: <?= $activeThemePalette['700'] ?? '#15803d'; ?> !important;
}
html:not(.dark) .nav-tab-btn:hover span {
    color: <?= $activeThemePalette['700'] ?? '#15803d'; ?> !important;
}
html:not(.dark) .nav-tab-btn.active {
    background-color: <?= $activeThemePalette['600'] ?? '#2e7d32'; ?> !important;
    color: #ffffff !important;
    font-weight: 700 !important;
    box-shadow: none !important;
}
html:not(.dark) .nav-tab-btn.active span,
html:not(.dark) .nav-tab-btn.active svg {
    color: #ffffff !important;
}
html:not(.dark) .sidebar-section-label {
    color: <?= $activeThemePalette['700'] ?? '#15803d'; ?> !important;
}
html:not(.dark) #mainSidebar .sidebar-user-name {
    color: #0f172a !important;
}
html.dark #mainSidebar .sidebar-user-name {
    color: #f8fafc !important;
}
html:not(.dark) #mainSidebar .sidebar-user-role {
    color: <?= $activeThemePalette['600'] ?? '#2e7d32'; ?> !important;
}

/* Dynamic Department Theme Colored Sidebar Icons */
html:not(.dark) .nav-tab-btn:not(.active) .sidebar-icon-mask {
    background-color: <?= $activeThemePalette['600'] ?? '#2e7d32'; ?> !important;
}
html:not(.dark) .nav-tab-btn:not(.active):hover .sidebar-icon-mask {
    background-color: <?= $activeThemePalette['700'] ?? '#15803d'; ?> !important;
}
html:not(.dark) .nav-tab-btn.active .sidebar-icon-mask {
    background-color: #ffffff !important;
}

html.dark .nav-tab-btn:not(.active) .sidebar-icon-mask {
    background-color: #a3a3a3 !important;
}
html.dark .nav-tab-btn.active .sidebar-icon-mask {
    background-color: #ffffff !important;
}

/* Collapsed Mini Sidebar Styles (76px mode) */
#mainSidebar.collapsed {
    width: 76px !important;
    overflow-x: hidden !important;
}
#mainSidebar.collapsed .sidebar-text,
#mainSidebar.collapsed .sidebar-section-label,
#mainSidebar.collapsed .brand-header-full {
    display: none !important;
}
#mainSidebar.collapsed .nav-tab-btn {
    justify-content: center !important;
    padding-left: 0 !important;
    padding-right: 0 !important;
    width: 44px !important;
    height: 44px !important;
    margin: 0 auto !important;
}
#mainSidebar.collapsed #toggleIcon {
    transform: rotate(180deg);
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

    if (sidebar && toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            if (window.innerWidth >= 1024) {
                sidebar.classList.toggle('collapsed');
            } else {
                closeMobileSidebar();
            }
        });
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
</script>
