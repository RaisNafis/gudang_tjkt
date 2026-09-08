<?php
$title = "Masuk - Gudang Sekolah TKJ";
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="min-h-screen flex items-center justify-center p-4 sm:p-6 md:p-8">
    <div class="w-full max-w-4xl bg-white rounded-3xl shadow-xl border border-sage-100 grid grid-cols-1 md:grid-cols-12 overflow-hidden animate-fade-in-up">
        
        <!-- Left Banner -->
        <div class="md:col-span-5 p-8 sm:p-10 text-white flex flex-col items-center justify-between text-center relative overflow-hidden" style="background: linear-gradient(135deg, <?= $activeThemePalette['700'] ?? '#ca8a04'; ?>, <?= $activeThemePalette['800'] ?? '#a16207'; ?>);">
            <div></div>

            <!-- Centered Belmoti Icon & Title -->
            <div class="relative z-10 flex flex-col items-center justify-center my-auto py-8">
                <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-3xl border border-white/30 p-4 shadow-2xl mb-5 flex items-center justify-center group hover:scale-105 transition-transform duration-300" style="background: linear-gradient(135deg, <?= $activeThemePalette['600'] ?? '#eab308'; ?>, <?= $activeThemePalette['700'] ?? '#ca8a04'; ?>); box-shadow: 0 12px 30px -5px rgba(<?= implode(',', sscanf($activeThemePalette['600'] ?? '#eab308', "#%02x%02x%02x")); ?>, 0.45);">
                    <img src="assets/img/belmoti.svg" alt="Belmoti Logo" class="w-full h-full object-contain filter drop-shadow-md" loading="lazy" decoding="async">
                </div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-wide text-white drop-shadow-sm">
                    Gudang Sekolah
                </h1>
            </div>

            <!-- Footer -->
            <div class="relative z-10 text-xs text-white/80 font-medium">
                © 2026 Belmoti Tech All rights reserved.
            </div>
        </div>

        <!-- Form Login -->
        <div class="md:col-span-7 p-8 sm:p-10 md:p-12 flex flex-col justify-center">
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-sage-100 text-sage-700 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-slate-800 tracking-tight">Selamat Datang</h2>
                </div>
                <p class="text-sm text-slate-500">Silakan masuk ke akun Gudang Sekolah Anda</p>
            </div>

            <?php if (!empty($flash)): ?>
                <div class="mb-6 p-4 rounded-xl bg-sage-100 border border-sage-300 text-sage-800 text-sm flex items-center gap-3">
                    <svg class="w-5 h-5 text-sage-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span><?= htmlspecialchars($flash['message']); ?></span>
                </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm flex items-center gap-3">
                    <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span><?= htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST" class="space-y-5" autocomplete="off">
                <input type="hidden" name="csrf_token" value="<?= getCsrfToken(); ?>">
                <!-- Nama Pengguna -->
                <div>
                    <label for="nama_pengguna" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Pengguna</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <input type="text" id="nama_pengguna" name="nama_pengguna" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-sage-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-sage-500 focus:bg-white focus:ring-4 focus:ring-sage-500/15 transition-all" placeholder="Masukkan nama pengguna" value="<?= htmlspecialchars($_POST['nama_pengguna'] ?? '') ?>" required autofocus>
                    </div>
                </div>

                <!-- Kata Sandi dengan Tombol Toggle Hide/Unhide -->
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kata Sandi</label>
                    <div class="relative flex items-center">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <input type="password" id="password" name="password" class="w-full pl-11 pr-12 py-3 bg-slate-50 border border-sage-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-sage-500 focus:bg-white focus:ring-4 focus:ring-sage-500/15 transition-all" placeholder="••••••••" required>
                        <button type="button" id="togglePasswordBtn" class="absolute right-3 p-1.5 text-slate-400 hover:text-sage-600 focus:outline-none transition-colors" title="Tampilkan/Sembunyikan Kata Sandi">
                            <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full py-3.5 px-6 bg-gradient-to-r from-sage-600 to-sage-700 hover:from-sage-700 hover:to-sage-800 text-white font-bold rounded-xl shadow-lg shadow-sage-600/25 hover:shadow-sage-600/40 transform hover:-translate-y-0.5 active:translate-y-0 transition-all flex items-center justify-center gap-2 mt-2">
                    <span>Masuk ke Akun</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>

            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('togglePasswordBtn');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    if (toggleBtn && passwordInput && eyeIcon) {
        toggleBtn.addEventListener('click', function() {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');

            if (isPassword) {
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.04 10.04 0 012.122-.063c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m-6.892-6.892a3 3 0 014.242 4.242M9.88 9.88l4.24 4.24M3 3l18 18"/>
                `;
            } else {
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                `;
            }
        });
    }
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
