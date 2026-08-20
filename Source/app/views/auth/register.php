<?php
// Page Register Disabled by Admin Request
header('Location: login.php');
exit;

/*
$title = "Daftar Akun - Gudang Sekolah TKJ";
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="min-h-screen flex items-center justify-center p-4 sm:p-6 md:p-8">
    <div class="w-full max-w-5xl bg-white rounded-3xl shadow-xl border border-sage-100 grid grid-cols-1 lg:grid-cols-12 overflow-hidden">
        
        <!-- Left Side Banner -->
        <div class="lg:col-span-5 bg-sage-700 p-8 sm:p-10 text-white flex flex-col items-center justify-between text-center relative overflow-hidden">
            <div></div>

            <!-- Centered Belmoti Icon & Title -->
            <div class="relative z-10 flex flex-col items-center justify-center my-auto py-8">
                <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-3xl bg-white/10 backdrop-blur-md border border-white/20 p-3 shadow-2xl shadow-sage-900/40 mb-5 flex items-center justify-center group hover:scale-105 transition-transform duration-300">
                    <img src="assets/img/belmoti.png" alt="Belmoti Logo" class="w-full h-full object-contain filter drop-shadow-md" loading="lazy" decoding="async">
                </div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-wide text-white drop-shadow-sm">
                    Gudang Sekolah
                </h1>
            </div>

            <!-- Footer -->
            <div class="relative z-10 text-xs text-sage-200 font-medium">
                © 2026 Belmoti Tech All rights reserved.
            </div>
        </div>

        <!-- Form Register -->
        <div class="lg:col-span-7 p-8 sm:p-10 md:p-12 flex flex-col justify-center">
            <div class="mb-8">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 rounded-lg bg-sage-100 text-sage-700 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-slate-800 tracking-tight">Buat Akun Baru</h2>
                </div>
                <p class="text-sm text-slate-500">Lengkapi formulir di bawah ini untuk pendaftaran akun</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm flex items-center gap-3">
                    <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span><?= htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>

            <form action="register.php" method="POST" class="space-y-5" autocomplete="off">
                <input type="hidden" name="csrf_token" value="<?= getCsrfToken(); ?>">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="nama_pengguna" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Pengguna (Username) *</label>
                        <input type="text" id="nama_pengguna" name="nama_pengguna" class="w-full px-4 py-3 bg-slate-50 border border-sage-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-sage-500 focus:bg-white focus:ring-4 focus:ring-sage-500/15 transition-all" placeholder="ahmad_tkj" value="<?= htmlspecialchars($_POST['nama_pengguna'] ?? '') ?>" required>
                    </div>
                    <div>
                        <label for="nama_lengkap" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Lengkap *</label>
                        <input type="text" id="nama_lengkap" name="nama_lengkap" class="w-full px-4 py-3 bg-slate-50 border border-sage-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-sage-500 focus:bg-white focus:ring-4 focus:ring-sage-500/15 transition-all" placeholder="Ahmad Rizki" value="<?= htmlspecialchars($_POST['nama_lengkap'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Email (Opsional)</label>
                        <input type="email" id="email" name="email" class="w-full px-4 py-3 bg-slate-50 border border-sage-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-sage-500 focus:bg-white focus:ring-4 focus:ring-sage-500/15 transition-all" placeholder="ahmad@siswa.sch.id" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Peran Akun</label>
                        <div class="w-full px-4 py-3 bg-sage-50 border border-sage-200 rounded-xl text-sage-800 font-bold text-sm flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-sage-600"></span>
                            <span>Siswa (Peminjam Alat)</span>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="nomor_telepon" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nomor Telepon / WhatsApp (Opsional)</label>
                    <input type="text" id="nomor_telepon" name="nomor_telepon" class="w-full px-4 py-3 bg-slate-50 border border-sage-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-sage-500 focus:bg-white focus:ring-4 focus:ring-sage-500/15 transition-all" placeholder="081234567890" value="<?= htmlspecialchars($_POST['nomor_telepon'] ?? '') ?>">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kata Sandi *</label>
                        <input type="password" id="password" name="password" class="w-full px-4 py-3 bg-slate-50 border border-sage-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-sage-500 focus:bg-white focus:ring-4 focus:ring-sage-500/15 transition-all" placeholder="••••••••" required>
                    </div>
                    <div>
                        <label for="confirm_password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Konfirmasi Kata Sandi *</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="w-full px-4 py-3 bg-slate-50 border border-sage-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-sage-500 focus:bg-white focus:ring-4 focus:ring-sage-500/15 transition-all" placeholder="••••••••" required>
                    </div>
                </div>

                <button type="submit" class="w-full py-3.5 px-6 bg-gradient-to-r from-sage-600 to-sage-700 hover:from-sage-700 hover:to-sage-800 text-white font-bold rounded-xl shadow-lg shadow-sage-600/25 hover:shadow-sage-600/40 transform hover:-translate-y-0.5 active:translate-y-0 transition-all flex items-center justify-center gap-2 mt-4">
                    <span>Daftar Akun Sekarang</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>

                <p class="text-center text-xs text-slate-500 mt-6">
                    Sudah memiliki akun? <a href="login.php" class="font-bold text-sage-600 hover:text-sage-700 hover:underline">Masuk di sini</a>
                </p>
            </form>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
*/
