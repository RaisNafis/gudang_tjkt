<?php 
$user = currentUser(); 
?>
<!-- Standard Modern Header Navigation Bar -->
<header class="bg-white dark:bg-slate-900 border-b border-sage-200/80 dark:border-slate-800 px-4 sm:px-6 py-3.5 flex items-center justify-between shadow-sm sticky top-0 z-30">
    <!-- Brand Logo & Title -->
    <div class="flex items-center gap-3 min-w-0">
        <div class="w-9 h-9 rounded-xl text-white flex items-center justify-center shrink-0 shadow-md transition-all duration-300" style="background: linear-gradient(135deg, <?= $activeThemePalette['600'] ?? '#eab308'; ?>, <?= $activeThemePalette['700'] ?? '#ca8a04'; ?>); box-shadow: 0 4px 14px 0 rgba(<?= implode(',', sscanf($activeThemePalette['600'] ?? '#eab308', "#%02x%02x%02x")); ?>, 0.35);">
            <img src="assets/img/belmoti.svg" alt="Belmoti Logo" class="w-6 h-6 object-contain filter drop-shadow-sm" onerror="this.style.display='none'; document.getElementById('fallbackNavLogo').style.display='block';">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none;" id="fallbackNavLogo">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
        </div>
        <div class="min-w-0">
            <h1 class="text-base font-bold text-slate-800 dark:text-white truncate">Gudang Sekolah TKJ</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 truncate hidden xs:block">Sistem Informasi Inventaris & Peminjaman</p>
        </div>
    </div>

    <?php if ($user): ?>
        <!-- User Info & Controls -->
        <div class="flex items-center gap-3 shrink-0">
            <button type="button" onclick="toggleTheme()" class="p-2 rounded-xl text-black dark:text-white hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700 transition-colors" title="Beralih Mode Gelap / Terang">
                <svg class="themeSunIcon w-5 h-5 hidden text-black dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <svg class="themeMoonIcon w-5 h-5 text-black dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
            </button>

            <div class="hidden md:block text-right">
                <span class="block text-xs font-bold text-slate-800 dark:text-white leading-tight truncate">
                    <?= htmlspecialchars($user['nama_lengkap'] ?? $user['nama_pengguna'] ?? 'User'); ?>
                </span>
                <span class="block text-[10px] font-semibold text-sage-600 dark:text-sage-400 capitalize leading-tight mt-0.5">
                    <?= htmlspecialchars($user['peran'] ?? 'Siswa'); ?>
                </span>
            </div>

            <a href="logout.php" class="p-2 rounded-xl text-slate-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-950/40 border border-slate-200 dark:border-slate-700 transition-colors" title="Keluar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
            </a>
        </div>
    <?php endif; ?>
</header>
