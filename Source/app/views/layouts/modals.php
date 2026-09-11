<!-- Modal Container Component - Soft Green & Soft White Theme -->

<!-- 0.5. MODAL DATA JURUSAN (TAMBAH / EDIT) -->
<div id="modalJurusan" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-4 overflow-y-auto bg-slate-900/40 backdrop-blur-sm animate-fade-in-up">
    <div class="bg-white rounded-3xl border border-sage-200 shadow-2xl w-full max-w-lg overflow-hidden">
        <div class="p-6 bg-sage-50/80 border-b border-sage-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0v-4c0-.883.39-1.683 1.018-2.227M13 17v-4c0-.883-.39-1.683-1.018-2.227"/></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800" id="modalJurusanTitle">Tambah Data Jurusan</h3>
            </div>
            <button onclick="closeModal('modalJurusan')" class="text-slate-400 hover:text-red-600 p-1.5 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form onsubmit="handleFormSubmit(event, 'Jurusan')" class="p-6 space-y-4 text-xs">
            <input type="hidden" name="csrf_token" value="<?= getCsrfToken(); ?>">
            <input type="hidden" id="jurusan_edit_id" value="">
            <div>
                <label class="block font-bold text-slate-700 mb-1">Nama Jurusan <span class="text-red-500">*</span></label>
                <input type="text" id="jurusan_nama" required class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600" placeholder="contoh: Teknik Komputer dan Jaringan (TKJ)">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Deskripsi Jurusan</label>
                <textarea id="jurusan_deskripsi" rows="2" class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-medium text-slate-800 focus:outline-none focus:border-sage-600" placeholder="Deskripsi mengenai keahlian atau lab jurusan ini..."></textarea>
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Warna Tema Dashboard <span class="text-red-500">*</span></label>
                <div class="flex items-center gap-3">
                    <input type="color" id="jurusan_warna_tema_picker" value="#EAB308" oninput="syncThemeColorInput(this.value)" class="w-12 h-10 rounded-xl border border-sage-200 cursor-pointer bg-white p-1 shrink-0">
                    <input type="text" id="jurusan_warna_tema" required value="#EAB308" oninput="syncThemeColorPicker(this.value)" placeholder="#EAB308" class="flex-1 px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-mono font-semibold text-slate-800 focus:outline-none focus:border-sage-600 uppercase">
                </div>
            </div>
            <div class="pt-3 flex justify-end gap-3 border-t border-sage-100">
                <button type="button" onclick="closeModal('modalJurusan')" class="px-4 py-2 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2 bg-sage-600 text-white font-bold rounded-xl shadow-md shadow-sage-600/20 hover:bg-sage-700">Simpan Jurusan</button>
            </div>
        </form>
    </div>
</div>

<!-- 1. MODAL DATA PENGGUNA (TAMBAH / EDIT) -->
<!-- 1. MODAL DATA PENGGUNA (TAMBAH / EDIT) -->
<div id="modalPengguna" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-4 overflow-y-auto bg-slate-900/50 backdrop-blur-sm animate-fade-in-up">
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-sage-200 dark:border-slate-800 shadow-2xl w-full max-w-lg max-h-[90vh] flex flex-col overflow-hidden">
        <div class="p-6 bg-sage-50/80 dark:bg-slate-800/80 border-b border-sage-100 dark:border-slate-700 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800 dark:text-white" id="modalPenggunaTitle">Tambah Data Pengguna</h3>
            </div>
            <button onclick="closeModal('modalPengguna')" class="text-slate-400 hover:text-red-600 p-1.5 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form onsubmit="handleFormSubmit(event, 'Pengguna')" class="p-6 space-y-4 text-xs overflow-y-auto flex-1">
            <input type="hidden" name="csrf_token" value="<?= getCsrfToken(); ?>">
            <input type="hidden" id="pengguna_edit_id" value="">
            
            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-200 mb-1">Status Pengguna (Profil Profesi)</label>
                <select id="pengguna_status_pengguna" onchange="handleStatusPenggunaChange(this.value)" class="w-full px-3.5 py-2.5 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-slate-200 focus:outline-none focus:border-sage-600">
                    <option value="tidak_ada">-- Tidak Ada --</option>
                    <option value="guru">Guru</option>
                    <option value="siswa">Siswa</option>
                </select>
            </div>

            <!-- DROPDOWN PILIH DATA GURU (Tampil saat status = guru) -->
            <div id="field_group_pilih_guru" class="hidden">
                <label class="block font-bold text-slate-700 dark:text-slate-200 mb-1">Pilih Data Guru</label>
                <select id="pengguna_guru_id" onchange="onGuruSelectedInUserForm(this.value)" class="w-full px-3.5 py-2.5 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-slate-200 focus:outline-none focus:border-sage-600">
                    <option value="">-- Pilih Guru --</option>
                </select>
            </div>

            <!-- DROPDOWN PILIH DATA SISWA (Tampil saat status = siswa) -->
            <div id="field_group_pilih_siswa" class="hidden">
                <label class="block font-bold text-slate-700 dark:text-slate-200 mb-1">Pilih Data Siswa</label>
                <select id="pengguna_siswa_id" onchange="onSiswaSelectedInUserForm(this.value)" class="w-full px-3.5 py-2.5 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-slate-200 focus:outline-none focus:border-sage-600">
                    <option value="">-- Pilih Siswa --</option>
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-200 mb-1">Nama Pengguna (Username) <span class="text-red-500">*</span></label>
                <input type="text" id="pengguna_nama_pengguna" required class="w-full px-3.5 py-2.5 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-slate-200 focus:outline-none focus:border-sage-600" placeholder="contoh: budiprasetyo">
            </div>

            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-200 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" id="pengguna_nama_lengkap" required class="w-full px-3.5 py-2.5 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-slate-200 focus:outline-none focus:border-sage-600" placeholder="contoh: Budi Prasetyo, S.Pd.">
            </div>

            <div id="field_group_token_siswa" class="hidden">
                <label class="block font-bold text-slate-700 dark:text-slate-200 mb-1" id="pengguna_token_label">Token Login (Password)</label>
                <input type="text" id="pengguna_token" readonly class="w-full px-3.5 py-2.5 bg-sage-100/70 dark:bg-slate-900 border border-sage-200 dark:border-slate-700 rounded-xl font-mono font-bold text-sage-900 dark:text-sage-300 text-sm tracking-widest focus:outline-none cursor-not-allowed select-all" placeholder="ABCDE">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Email <span class="text-slate-400 font-normal">(Opsional)</span></label>
                <div class="flex items-center gap-2">
                    <input type="text" id="pengguna_email_prefix" class="w-1/2 px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600" placeholder="nama_email">
                    <span class="text-slate-400 font-bold text-sm">@</span>
                    <input type="text" id="pengguna_email_domain" value="smk2pangkalpinang.sch.id" class="w-1/2 px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600" placeholder="smk2pangkalpinang.sch.id">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Nomor Telepon / HP <span class="text-slate-400 font-normal">(Opsional)</span></label>
                <input type="text" id="pengguna_telepon" class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600" placeholder="contoh: 081234567890">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Jurusan / Departemen</label>
                <select id="pengguna_jurusan_id" class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600">
                    <option value="">-- Tidak Ada Jurusan --</option>
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Peran Akses <span class="text-red-500">*</span></label>
                <select id="pengguna_peran" required class="w-full px-3.5 py-2.5 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-slate-200 focus:outline-none focus:border-sage-600">
                    <option value="siswa">Siswa</option>
                    <?php if (!empty($user['peran']) && $user['peran'] === 'admin_sekolah'): ?>
                        <option value="guru_umum">Guru Umum</option>
                    <?php endif; ?>
                    <option value="guru_jurusan">Guru Jurusan</option>
                    <option value="kabeng">Kabeng (Kepala Bengkel)</option>
                    <?php if (!empty($user['peran']) && $user['peran'] === 'admin_sekolah'): ?>
                        <option value="admin_sekolah">Admin Sekolah</option>
                    <?php endif; ?>
                </select>
            </div>

            <div id="field_group_password">
                <label class="block font-bold text-slate-700 dark:text-slate-200 mb-1" id="pengguna_password_label">Kata Sandi</label>
                <div class="relative flex items-center">
                    <input type="password" id="pengguna_password" class="w-full px-3.5 py-2.5 pr-10 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-slate-200 focus:outline-none focus:border-sage-600" placeholder="••••••••">
                    <button type="button" onclick="togglePasswordVisibility('pengguna_password', this)" class="absolute right-3 text-slate-400 hover:text-sage-600 transition-colors p-1" title="Tampilkan / Sembunyikan Kata Sandi">
                        <svg class="eyeOpenIcon w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg class="eyeCloseIcon w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.025 10.025 0 014.122-.963c4.478 0 8.268 2.943 9.542 7a9.97 9.97 0 01-2.52 4.148M9.88 9.88a3 3 0 104.243 4.243M3 3l18 18"/>
                        </svg>
                    </button>
                </div>
                <p class="text-[10px] text-slate-400 mt-1 hidden" id="pengguna_password_hint">*Biarkan kosong jika tidak ingin mengubah kata sandi</p>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Foto Profil <span class="text-slate-400 font-normal">(Opsional)</span></label>
                <input type="file" id="pengguna_foto" accept="image/png, image/jpeg, image/jpg, image/webp" class="w-full px-3.5 py-2 bg-sage-50/50 border border-sage-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:border-sage-600 file:mr-3 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-sage-600 file:text-white hover:file:bg-sage-700">
            </div>

            <div class="pt-3 flex justify-end gap-3 border-t border-sage-100">
                <button type="button" onclick="closeModal('modalPengguna')" class="px-4 py-2 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2 bg-sage-600 text-white font-bold rounded-xl shadow-md shadow-sage-600/20 hover:bg-sage-700">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<!-- 1.1 MODAL DATA GURU (TAMBAH / EDIT) -->
<div id="modalGuru" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-4 overflow-y-auto bg-slate-900/50 backdrop-blur-sm animate-fade-in-up">
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-sage-200 dark:border-slate-800 shadow-2xl w-full max-w-lg max-h-[90vh] flex flex-col overflow-hidden">
        <div class="p-6 bg-sage-50/80 dark:bg-slate-800/80 border-b border-sage-100 dark:border-slate-700 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800 dark:text-white" id="modalGuruTitle">Tambah Data Guru</h3>
            </div>
            <button onclick="closeModal('modalGuru')" class="text-slate-400 hover:text-red-600 p-1.5 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form onsubmit="handleFormSubmit(event, 'Guru')" class="p-6 space-y-4 text-xs overflow-y-auto flex-1">
            <input type="hidden" name="csrf_token" value="<?= getCsrfToken(); ?>">
            <input type="hidden" id="guru_edit_id" value="">
            <input type="hidden" id="guru_token" value="">

            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-200 mb-1">Nama Guru <span class="text-red-500">*</span></label>
                <input type="text" id="guru_nama_guru" required oninput="autoFillGuruUsername(this.value)" class="w-full px-3.5 py-2.5 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-white focus:outline-none focus:border-sage-600" placeholder="contoh: Pak wahyu">
            </div>

            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-200 mb-1">Nama Pengguna (Username) <span class="text-red-500">*</span></label>
                <input type="text" id="guru_nama_pengguna" required class="w-full px-3.5 py-2.5 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-white focus:outline-none focus:border-sage-600" placeholder="contoh: pakwahyu">
                <span class="text-[10px] text-slate-400 mt-1 block">*Digunakan untuk login akun (huruf kecil & tanpa spasi)</span>
            </div>

            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-200 mb-1">Kategori Mengajar <span class="text-red-500">*</span></label>
                <select id="guru_mengajar" required onchange="handleGuruMengajarChange(this.value)" class="w-full px-3.5 py-2.5 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-white focus:outline-none focus:border-sage-600">
                    <option value="bengkel">Bengkel (Jurusan Spesifik)</option>
                    <option value="umum">Umum (Tidak Ada Jurusan)</option>
                </select>
            </div>

            <div id="group_guru_jurusan">
                <label class="block font-bold text-slate-700 mb-1">Jurusan / Departemen <span class="text-red-500">*</span></label>
                <select id="guru_jurusan_id" class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600">
                    <option value="">-- Pilih Jurusan --</option>
                </select>
            </div>

            <div class="pt-3 flex justify-end gap-3 border-t border-sage-100">
                <button type="button" onclick="closeModal('modalGuru')" class="px-4 py-2 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2 bg-sage-600 text-white font-bold rounded-xl shadow-md shadow-sage-600/20 hover:bg-sage-700">Simpan Guru</button>
            </div>
        </form>
    </div>
</div>

<!-- 1.2 MODAL DATA SISWA (TAMBAH / EDIT) -->
<div id="modalSiswa" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-4 overflow-y-auto bg-slate-900/50 backdrop-blur-sm animate-fade-in-up">
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-sage-200 dark:border-slate-800 shadow-2xl w-full max-w-lg max-h-[90vh] flex flex-col overflow-hidden">
        <div class="p-6 bg-sage-50/80 dark:bg-slate-800/80 border-b border-sage-100 dark:border-slate-700 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800 dark:text-white" id="modalSiswaTitle">Tambah Data Siswa</h3>
            </div>
            <button onclick="closeModal('modalSiswa')" class="text-slate-400 hover:text-red-600 p-1.5 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form onsubmit="handleFormSubmit(event, 'Siswa')" class="p-6 space-y-4 text-xs overflow-y-auto flex-1">
            <input type="hidden" name="csrf_token" value="<?= getCsrfToken(); ?>">
            <input type="hidden" id="siswa_edit_id" value="">
            <input type="hidden" id="siswa_token" value="">

            <div>
                <label class="block font-bold text-slate-700 mb-1">NISN (Nomor Induk Siswa Nasional)</label>
                <input type="text" id="siswa_nisn" class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600" placeholder="contoh: 0112586332">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Nama Lengkap Siswa <span class="text-red-500">*</span></label>
                <input type="text" id="siswa_nama_lengkap" required class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600" placeholder="contoh: Rais Nafis">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Nama Siswa (Huruf Kecil & Tanpa Spasi) <span class="text-red-500">*</span></label>
                <input type="text" id="siswa_nama_siswa" required class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600" placeholder="contoh: raisnafis">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Kelas <span class="text-red-500">*</span></label>
                    <input type="text" id="siswa_kelas" required class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600" placeholder="X TKJ 1">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Tahun Ajaran</label>
                    <input type="text" id="siswa_tahun_ajaran" value="2025/2026" class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600" placeholder="2025/2026">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Jurusan / Departemen</label>
                <select id="siswa_jurusan_id" class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600">
                    <option value="">-- Tidak Ada Jurusan (Umum / Staf) --</option>
                </select>
            </div>

            <div class="pt-3 flex justify-end gap-3 border-t border-sage-100">
                <button type="button" onclick="closeModal('modalSiswa')" class="px-4 py-2 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2 bg-sage-600 text-white font-bold rounded-xl shadow-md shadow-sage-600/20 hover:bg-sage-700">Simpan Siswa</button>
            </div>
        </form>
    </div>
</div>

<!-- 1.3 MODAL IMPORT GURU CSV -->
<div id="modalImportGuruCSV" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-4 overflow-y-auto bg-slate-900/40 backdrop-blur-sm animate-fade-in-up">
    <div class="bg-white rounded-3xl border border-sage-200 shadow-2xl w-full max-w-md overflow-hidden">
        <div class="p-6 bg-sage-50/80 border-b border-sage-100 flex items-center justify-between">
            <h3 class="text-base font-bold text-slate-800">Import Data Guru dari CSV</h3>
            <button onclick="closeModal('modalImportGuruCSV')" class="text-slate-400 hover:text-red-600 p-1.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form onsubmit="handleImportCSVSubmit(event, 'guru')" class="p-6 space-y-4 text-xs">
            <div class="p-3 bg-sage-50 rounded-xl border border-sage-200 text-slate-600">
                <p class="font-bold text-sage-900 mb-1">Format Kolom CSV:</p>
                <p class="font-mono text-[11px]">nama_guru, mengajar, nama_jurusan</p>
                <p class="mt-1 text-[10px] text-slate-500">*mengajar berisi 'bengkel' atau 'umum'</p>
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Pilih Berkas CSV</label>
                <input type="file" id="import_guru_file" accept=".csv" required class="w-full px-3 py-2 border border-sage-200 rounded-xl text-xs">
            </div>
            <div class="pt-3 flex justify-end gap-3 border-t border-sage-100">
                <button type="button" onclick="closeModal('modalImportGuruCSV')" class="px-4 py-2 bg-red-600 text-white font-bold rounded-xl">Batal</button>
                <button type="submit" class="px-5 py-2 bg-sage-600 text-white font-bold rounded-xl shadow-md">Upload & Import</button>
            </div>
        </form>
    </div>
</div>

<!-- 1.4 MODAL IMPORT SISWA EXCEL & CSV -->
<div id="modalImportSiswaCSV" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-4 overflow-y-auto bg-slate-900/40 backdrop-blur-sm animate-fade-in-up">
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-amber-200 dark:border-slate-800 shadow-2xl w-full max-w-md overflow-hidden">
        <div class="p-6 bg-amber-50/80 dark:bg-slate-800/80 border-b border-amber-100 dark:border-slate-700 flex items-center justify-between">
            <h3 class="text-base font-bold text-slate-800 dark:text-white">Import Data Siswa (Excel / CSV)</h3>
            <button onclick="closeModal('modalImportSiswaCSV')" class="text-slate-400 hover:text-red-600 p-1.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form onsubmit="handleImportCSVSubmit(event, 'siswa')" class="p-6 space-y-4 text-xs">
            <div class="p-3 bg-amber-50 dark:bg-amber-950/30 rounded-xl border border-amber-200 dark:border-amber-800 text-slate-600 dark:text-slate-300">
                <p class="font-bold text-amber-900 dark:text-amber-400 mb-1">Format Berkas yang Didukung:</p>
                <p class="text-[11px] font-semibold text-slate-700 dark:text-slate-200 mb-1">• <strong>Berkas Excel (.xlsx)</strong>: Format Dapodik (No, NISN, Nama, Kelas, Tahun Ajaran)</p>
                <p class="text-[11px] font-semibold text-slate-700 dark:text-slate-200">• <strong>Berkas CSV (.csv)</strong>: nama_siswa, token, kelas, jurusan, tahun_ajaran</p>
                <p class="mt-1 text-[10px] text-slate-500 dark:text-slate-400">*Jurusan & token login pengguna akan otomatis dihubungkan</p>
            </div>
            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Pilih Berkas Excel (.xlsx) atau CSV (.csv)</label>
                <input type="file" id="import_siswa_file" accept=".xlsx, .xls, .csv" required class="w-full px-3 py-2 border border-amber-200 dark:border-slate-700 dark:bg-slate-800 rounded-xl text-xs">
            </div>
            <div class="pt-3 flex justify-end gap-3 border-t border-amber-100 dark:border-slate-800">
                <button type="button" onclick="closeModal('modalImportSiswaCSV')" class="px-4 py-2 bg-red-600 text-white font-bold rounded-xl">Batal</button>
                <button type="submit" class="px-5 py-2 bg-amber-600 text-white font-bold rounded-xl shadow-md">Upload & Import</button>
            </div>
        </form>
    </div>
</div>

<?php if (!empty($isSuperAdmin)): ?>
<!-- 1.5 MODAL MIGRASI KENAIKAN KELAS SISWA -->
<div id="modalMigrasiSiswa" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-4 overflow-y-auto bg-slate-900/60 backdrop-blur-sm animate-fade-in-up">
    <div class="bg-white dark:bg-[#1a1a1a] rounded-3xl border border-slate-200 dark:border-[#262626] shadow-2xl w-full max-w-xl p-6 relative transition-all">
        <!-- Close Button (X) -->
        <button type="button" onclick="closeModal('modalMigrasiSiswa')" class="absolute top-5 right-5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 rounded-xl hover:bg-slate-100 dark:hover:bg-[#252525] transition-colors" title="Tutup">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <!-- Title & Subtitle (Langsung di Konten Tanpa Header Terpisah) -->
        <div class="mb-4 pr-8">
            <h3 class="text-base font-bold text-slate-900 dark:text-white">Migrasi Kenaikan Kelas Siswa</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                Pembaruan massal tingkatan kelas untuk tahun ajaran baru
            </p>
        </div>

        <!-- Body Form -->
        <form id="formMigrasiSiswa" onsubmit="handleMigrasiSiswaSubmit(event)" class="space-y-4 text-xs">
            <input type="hidden" name="csrf_token" value="<?= getCsrfToken(); ?>">

            <!-- Penjelasan Teks Bersih (Tanpa Card Box & Badge) -->
            <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                Sistem akan memproses kenaikan kelas seluruh siswa aktif secara serentak untuk tahun ajaran baru:
            </p>

            <!-- Teks Alur Kenaikan Kelas (Hanya Teks) -->
            <div class="space-y-2 py-0.5 text-xs text-slate-700 dark:text-slate-300">
                <div class="flex items-center justify-between py-1.5 border-b border-slate-100 dark:border-slate-800/60">
                    <span>Kelas 10 &rarr; Naik ke Kelas 11</span>
                    <span id="migrasiCountKelas10" class="font-bold text-slate-800 dark:text-slate-200">0 Siswa</span>
                </div>
                <div class="flex items-center justify-between py-1.5 border-b border-slate-100 dark:border-slate-800/60">
                    <span>Kelas 11 &rarr; Naik ke Kelas 12</span>
                    <span id="migrasiCountKelas11" class="font-bold text-slate-800 dark:text-slate-200">0 Siswa</span>
                </div>
                <div class="flex items-center justify-between py-1.5">
                    <span>Kelas 12 &rarr; Status LULUS</span>
                    <span id="migrasiCountKelas12" class="font-bold text-slate-800 dark:text-slate-200">0 Siswa</span>
                </div>
            </div>

            <!-- Scope & Target Settings -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1 text-xs">Jurusan / Sasaran Migrasi</label>
                    <select id="migrasi_jurusan_id" onchange="updateMigrasiCounts()" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-200 font-semibold focus:outline-none focus:border-slate-800 dark:focus:border-white">
                        <!-- Populated based on currentUser -->
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1 text-xs">Target Tahun Ajaran Baru</label>
                    <input type="text" id="migrasi_tahun_ajaran" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-200 font-semibold focus:outline-none focus:border-slate-800 dark:focus:border-white" placeholder="contoh: 2027/2028">
                </div>
            </div>

            <!-- Catatan Konfirmasi (Hanya Teks) -->
            <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed pt-0.5">
                Pastikan Anda telah melakukan <strong class="text-slate-700 dark:text-slate-300">Export CSV</strong> terlebih dahulu sebagai arsip cadangan sebelum memproses migrasi.
            </p>

            <!-- Buttons -->
            <div class="pt-3 flex items-center justify-between border-t border-slate-200 dark:border-slate-800">
                <span class="text-[11px] text-slate-500 dark:text-slate-400">Total terpengaruh: <strong id="migrasiCountTotal" class="text-slate-900 dark:text-white font-bold">0</strong> siswa</span>
                <div class="flex items-center gap-2.5">
                    <button type="button" onclick="closeModal('modalMigrasiSiswa')" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors border border-slate-200 dark:border-slate-700">Batal</button>
                    <button type="submit" id="btnSubmitMigrasiSiswa" class="px-5 py-2 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold rounded-xl hover:bg-slate-800 dark:hover:bg-slate-100 shadow-sm transition-all flex items-center gap-2">
                        <span>Ya, Jalankan Migrasi</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- MODAL ROLLBACK MIGRASI KELAS SISWA -->
<div id="modalRollbackMigrasiSiswa" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-4 overflow-y-auto bg-slate-900/60 backdrop-blur-sm animate-fade-in-up">
    <div class="bg-white dark:bg-[#1a1a1a] rounded-3xl border border-slate-200 dark:border-[#262626] shadow-2xl w-full max-w-xl p-6 relative transition-all">
        <!-- Close Button (X) -->
        <button type="button" onclick="closeModal('modalRollbackMigrasiSiswa')" class="absolute top-5 right-5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 rounded-xl hover:bg-slate-100 dark:hover:bg-[#252525] transition-colors" title="Tutup">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <!-- Title & Subtitle (Langsung di Konten Tanpa Header Terpisah) -->
        <div class="mb-4 pr-8">
            <h3 class="text-base font-bold text-slate-900 dark:text-white">Rollback Migrasi Kelas Siswa</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                Batalkan migrasi dan kembalikan siswa ke kelas & tahun ajaran semula
            </p>
        </div>

        <!-- Body -->
        <form id="formRollbackMigrasiSiswa" onsubmit="handleRollbackMigrasiSubmit(event)" class="space-y-4 text-xs">
            <input type="hidden" name="csrf_token" value="<?= getCsrfToken(); ?>">
            <input type="hidden" id="rollback_batch_id" name="batch_id" value="">

            <!-- Empty State (No migration to rollback - Tanpa Card Box) -->
            <div id="rollbackEmptyState" class="hidden py-8 text-center space-y-2">
                <h4 class="font-bold text-slate-700 dark:text-slate-200 text-sm">Tidak Ada Riwayat Migrasi Aktif</h4>
                <p class="text-slate-500 dark:text-slate-400 text-xs leading-relaxed">Belum ada batch migrasi yang tercatat, atau migrasi terakhir sudah pernah di-rollback sebelumnya.</p>
            </div>

            <!-- Data Card (When migration exists - Hanya Teks Tanpa Card Box) -->
            <div id="rollbackDataCard" class="hidden space-y-4">
                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                    Sistem akan mengembalikan seluruh data siswa pada migrasi ini ke tingkatan kelas dan tahun ajaran aslinya.
                </p>

                <!-- Info Grid (Clean Text) -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 py-1 text-xs">
                    <div class="py-1 border-b border-slate-100 dark:border-slate-800/60">
                        <span class="block text-[10px] text-slate-400 font-bold uppercase">Waktu Migrasi</span>
                        <span id="rollbackInfoWaktu" class="font-bold text-slate-800 dark:text-slate-200 text-xs mt-0.5 block">-</span>
                    </div>
                    <div class="py-1 border-b border-slate-100 dark:border-slate-800/60">
                        <span class="block text-[10px] text-slate-400 font-bold uppercase">Tahun Ajaran Asal</span>
                        <span id="rollbackInfoTaAsal" class="font-bold text-slate-900 dark:text-white text-xs mt-0.5 block">-</span>
                    </div>
                    <div class="py-1 border-b border-slate-100 dark:border-slate-800/60">
                        <span class="block text-[10px] text-slate-400 font-bold uppercase">Jurusan / Lingkup</span>
                        <span id="rollbackInfoJurusan" class="font-bold text-slate-800 dark:text-slate-200 text-xs mt-0.5 block">Semua Jurusan</span>
                    </div>
                </div>

                <!-- Detail Alur Rollback (Hanya Teks, Tanpa Badges & Box) -->
                <div class="space-y-2 py-0.5 text-xs text-slate-700 dark:text-slate-300">
                    <div class="flex items-center justify-between py-1.5 border-b border-slate-100 dark:border-slate-800/60">
                        <span>Siswa LULUS &rarr; Dikembalikan ke Kelas 12</span>
                        <span id="rollbackCount12" class="font-bold text-slate-800 dark:text-slate-200">0 Siswa</span>
                    </div>
                    <div class="flex items-center justify-between py-1.5 border-b border-slate-100 dark:border-slate-800/60">
                        <span>Kelas 12 &rarr; Dikembalikan ke Kelas 11</span>
                        <span id="rollbackCount11" class="font-bold text-slate-800 dark:text-slate-200">0 Siswa</span>
                    </div>
                    <div class="flex items-center justify-between py-1.5">
                        <span>Kelas 11 &rarr; Dikembalikan ke Kelas 10</span>
                        <span id="rollbackCount10" class="font-bold text-slate-800 dark:text-slate-200">0 Siswa</span>
                    </div>
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="pt-3 flex items-center justify-between border-t border-slate-200 dark:border-slate-800">
                <span class="text-[11px] text-slate-500 dark:text-slate-400">Total: <strong id="rollbackCountTotal" class="text-slate-900 dark:text-white font-bold">0</strong> siswa</span>
                <div class="flex items-center gap-2.5">
                    <button type="button" onclick="closeModal('modalRollbackMigrasiSiswa')" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors border border-slate-200 dark:border-slate-700">Tutup</button>
                    <button type="submit" id="btnSubmitRollbackMigrasi" class="hidden px-5 py-2 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold rounded-xl hover:bg-slate-800 dark:hover:bg-slate-100 shadow-sm transition-all flex items-center gap-2">
                        <span>Ya, Rollback Migrasi</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<!-- MODAL KELOLA AKSES MULTI-JURUSAN KEPALA BENGKEL -->
<div id="modalMultiJurusanKabeng" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-4 overflow-y-auto bg-slate-900/50 backdrop-blur-sm animate-fade-in-up">
    <div class="relative w-full max-w-xl bg-white dark:bg-[#1e1e1e] p-5 sm:p-6 rounded-3xl shadow-2xl overflow-hidden border border-slate-200 dark:border-slate-800">
        <!-- Header -->
        <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100 dark:border-slate-800">
            <div>
                <h3 class="font-extrabold text-slate-800 dark:text-white text-base">Akses Multi-Jurusan Kepala Bengkel</h3>
                <p class="text-xs text-slate-400 dark:text-slate-500">Atur hak akses beberapa jurusan/gudang untuk Kepala Bengkel</p>
            </div>
            <button type="button" onclick="closeModal('modalMultiJurusanKabeng')" class="text-slate-400 hover:text-red-600 p-1.5 rounded-lg transition-colors cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Form -->
        <form id="formMultiJurusanKabeng" onsubmit="handleMultiJurusanSubmit(event)" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?= getCsrfToken(); ?>">
            
            <!-- Pilih Pengguna (Kabeng) -->
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Pilih Kepala Bengkel <span class="text-red-500">*</span></label>
                <select id="multi_kabeng_pengguna_id" name="pengguna_id" required onchange="onKabengSelectedForMulti(this.value)" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-xl font-bold text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:border-indigo-500">
                    <option value="">-- Pilih Kepala Bengkel --</option>
                </select>
            </div>

            <!-- Info Kabeng Terpilih -->
            <div id="multi_kabeng_info_card" class="hidden py-1">
                <h4 id="multi_kabeng_nama" class="font-extrabold text-xs text-slate-800 dark:text-white truncate">-</h4>
                <div class="text-[11px] text-slate-500 dark:text-slate-400 flex flex-wrap items-center gap-x-4 gap-y-0.5 mt-0.5">
                    <span>Username: <strong id="multi_kabeng_username" class="font-semibold text-slate-700 dark:text-slate-300">-</strong></span>
                    <span>Jurusan Utama: <strong id="multi_kabeng_jurusan_asal" class="font-semibold text-slate-700 dark:text-slate-300">-</strong></span>
                </div>
            </div>

            <!-- Checkbox List Jurusan -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">Daftar Jurusan yang Diberi Akses</label>
                    <span class="text-[11px] text-slate-400">Centang jurusan yang diizinkan</span>
                </div>
                
                <!-- Input Search untuk Jurusan List -->
                <div class="relative mb-2">
                    <input type="text" id="multi_jurusan_search_input" oninput="filterMultiJurusanList(this.value)" placeholder="Cari nama atau kode jurusan..." class="w-full pl-8 pr-8 py-2 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:border-indigo-500 font-medium transition-colors">
                    <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <button type="button" id="btn_clear_multi_jurusan_search" onclick="clearMultiJurusanSearch()" class="hidden absolute right-2.5 top-2 text-slate-400 hover:text-slate-600 dark:hover:text-white cursor-pointer" title="Hapus pencarian">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div id="multi_jurusan_checkbox_container" class="space-y-2 max-h-56 overflow-y-auto pr-1">
                    <div class="p-4 text-center text-xs text-slate-400">Pilih Kepala Bengkel terlebih dahulu...</div>
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="pt-3 flex items-center justify-end gap-2.5 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onclick="closeModal('modalMultiJurusanKabeng')" class="px-4 py-2 bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold rounded-xl hover:bg-slate-300 dark:hover:bg-slate-700 transition-colors text-xs">Batal</button>
                <button type="submit" id="btnSubmitMultiJurusan" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-md shadow-indigo-600/20 transition-all flex items-center gap-2 text-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Simpan Akses Multi-Jurusan</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL PREVIEW IMAGE BESAR -->
<div id="modalPreviewImage" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-4 overflow-y-auto bg-slate-900/70 backdrop-blur-sm animate-fade-in-up" onclick="closeModal('modalPreviewImage')">
    <div class="relative max-w-2xl max-h-[85vh] bg-white dark:bg-slate-900 p-2.5 rounded-3xl shadow-2xl overflow-hidden border border-slate-200 dark:border-slate-700" onclick="event.stopPropagation()">
        <button type="button" onclick="closeModal('modalPreviewImage')" class="absolute top-4 right-4 z-10 w-8 h-8 rounded-full bg-slate-900/70 hover:bg-slate-900 text-white flex items-center justify-center transition-colors shadow-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <img id="previewModalImg" src="" alt="Preview" class="w-full max-h-[75vh] object-contain rounded-2xl">
        <div id="previewModalCaption" class="p-3 text-center text-xs font-bold text-slate-700 dark:text-slate-200"></div>
    </div>
</div>

<!-- 2. MODAL KATEGORI BARANG (TAMBAH / EDIT) -->
<div id="modalKategori" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-4 overflow-y-auto bg-slate-900/40 backdrop-blur-sm animate-fade-in-up">
    <div class="bg-white rounded-3xl border border-sage-200 shadow-2xl w-full max-w-lg overflow-hidden">
        <div class="p-6 bg-sage-50/80 border-b border-sage-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 11h.01M7 15h.01M11 7h8M11 11h8M11 15h8"/></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800" id="modalKategoriTitle">Tambah Kategori Barang</h3>
            </div>
            <button onclick="closeModal('modalKategori')" class="text-slate-400 hover:text-red-600 p-1.5 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form onsubmit="handleFormSubmit(event, 'Kategori')" class="p-6 space-y-4 text-xs">
            <input type="hidden" name="csrf_token" value="<?= getCsrfToken(); ?>">
            <input type="hidden" id="kategori_edit_id" value="">
            <?php if (!empty($user['peran']) && $user['peran'] === 'admin_sekolah'): ?>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Jurusan / Departemen</label>
                <select id="kategori_jurusan_id" class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600">
                    <option value="">-- Semua Jurusan / Sekolah --</option>
                </select>
            </div>
            <?php endif; ?>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Nama Kategori <span class="text-red-500">*</span></label>
                <input type="text" id="kategori_nama" required class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600" placeholder="contoh: Jaringan & Networking">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Deskripsi Kategori</label>
                <textarea id="kategori_deskripsi" rows="3" class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-medium text-slate-800 focus:outline-none focus:border-sage-600" placeholder="Keterangan singkat mengenai kategori barang ini..."></textarea>
            </div>
            <div class="pt-3 flex justify-end gap-3 border-t border-sage-100">
                <button type="button" onclick="closeModal('modalKategori')" class="px-4 py-2 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2 bg-sage-600 text-white font-bold rounded-xl shadow-md shadow-sage-600/20 hover:bg-sage-700">Simpan Kategori</button>
            </div>
        </form>
    </div>
</div>

<!-- 2.5. MODAL DATA RAK PENYIMPANAN (TAMBAH / EDIT) -->
<div id="modalRak" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-4 overflow-y-auto bg-slate-900/40 backdrop-blur-sm animate-fade-in-up">
    <div class="bg-white rounded-3xl border border-sage-200 shadow-2xl w-full max-w-lg overflow-hidden">
        <div class="p-6 bg-sage-50/80 border-b border-sage-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800" id="modalRakTitle">Tambah Rak Penyimpanan</h3>
            </div>
            <button onclick="closeModal('modalRak')" class="text-slate-400 hover:text-red-600 p-1.5 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form onsubmit="handleFormSubmit(event, 'Rak')" class="p-6 space-y-4 text-xs">
            <input type="hidden" name="csrf_token" value="<?= getCsrfToken(); ?>">
            <input type="hidden" id="rak_edit_id" value="">
            <?php if (!empty($user['peran']) && $user['peran'] === 'admin_sekolah'): ?>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Jurusan / Departemen</label>
                <select id="rak_jurusan_id" class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600">
                    <option value="">-- Semua Jurusan / Sekolah --</option>
                </select>
            </div>
            <?php endif; ?>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Kode Barcode Rak</label>
                <input type="text" id="rak_barcode" readonly class="w-full px-3.5 py-2.5 bg-slate-100/70 dark:bg-neutral-900/80 border border-sage-200 rounded-xl font-mono font-bold text-slate-700 dark:text-slate-300 cursor-not-allowed focus:outline-none" placeholder="899300100001">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Nama Rak Penyimpanan <span class="text-red-500">*</span></label>
                <input type="text" id="rak_nama" required class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600" placeholder="contoh: Rak A1 - Jaringan & Alat Utilitas">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Kategori / Peruntukan Rak</label>
                <input type="text" id="rak_kategori" class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600" placeholder="contoh: Toolset, Kabel & Connector, Komponen PC...">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Keterangan / Deskripsi Lokasi</label>
                <textarea id="rak_keterangan" rows="2" class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-medium text-slate-800 focus:outline-none focus:border-sage-600" placeholder="Keterangan letak fisik rak di dalam lab gudang..."></textarea>
            </div>
            <div class="pt-3 flex justify-end gap-3 border-t border-sage-100">
                <button type="button" onclick="closeModal('modalRak')" class="px-4 py-2 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2 bg-sage-600 text-white font-bold rounded-xl shadow-md shadow-sage-600/20 hover:bg-sage-700">Simpan Data Rak</button>
            </div>
        </form>
    </div>
</div>

<!-- 2.6. MODAL LIHAT DAFTAR BARANG DI RAK -->
<div id="modalLihatBarangRak" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-4 overflow-y-auto bg-slate-900/40 backdrop-blur-sm animate-fade-in-up">
    <div class="bg-white rounded-3xl border border-sage-200 shadow-2xl w-full max-w-3xl overflow-hidden">
        <div class="p-6 bg-sage-50/80 border-b border-sage-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800" id="lihatRakTitle">Daftar Barang di Rak</h3>
                    <p class="text-xs text-slate-500" id="lihatRakSubtitle">Barang inventaris yang tersimpan dalam rak ini</p>
                </div>
            </div>
            <button onclick="closeModal('modalLihatBarangRak')" class="text-slate-400 hover:text-red-600 p-1.5 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
            <div class="overflow-x-auto border border-sage-200 rounded-2xl">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-sage-50 text-slate-700 font-bold border-b border-sage-200">
                        <tr>
                            <th class="py-3 px-4">No</th>
                            <th class="py-3 px-4">Nama Barang</th>
                            <th class="py-3 px-4">Kategori</th>
                            <th class="py-3 px-4">Barcode</th>
                            <th class="py-3 px-4 text-center">Stok Tersedia</th>
                            <th class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="lihatRakBarangTbody" class="divide-y divide-slate-100">
                        <tr><td colspan="6" class="py-4 text-center text-slate-400">Memuat data barang...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="p-6 bg-slate-50 border-t border-sage-100 flex justify-end">
            <button type="button" onclick="closeModal('modalLihatBarangRak')" class="px-5 py-2.5 bg-slate-700 text-white font-bold rounded-xl hover:bg-slate-800 transition-colors text-xs">Tutup</button>
        </div>
    </div>
</div>

<!-- 3. MODAL MASTER BARANG & BARCODE (TAMBAH / EDIT) -->
<div id="modalBarang" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-4 overflow-y-auto bg-slate-900/50 backdrop-blur-sm animate-fade-in-up">
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-sage-200 dark:border-slate-800 shadow-2xl w-full max-w-lg max-h-[90vh] flex flex-col overflow-hidden">
        <div class="p-6 bg-sage-50/80 dark:bg-slate-800/80 border-b border-sage-100 dark:border-slate-700 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800 dark:text-white" id="modalBarangTitle">Tambah Alat / Bahan & Barcode</h3>
            </div>
            <button onclick="closeModal('modalBarang')" class="text-slate-400 hover:text-red-600 p-1.5 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form onsubmit="handleFormSubmit(event, 'Barang')" class="p-6 space-y-4 text-xs overflow-y-auto flex-1">
            <input type="hidden" name="csrf_token" value="<?= getCsrfToken(); ?>">
            <input type="hidden" id="barang_edit_id" value="">
            <input type="hidden" id="barang_remove_image" value="0">
            <?php if (!empty($user['peran']) && $user['peran'] === 'admin_sekolah'): ?>
            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Jurusan / Departemen <span class="text-red-500">*</span></label>
                <select id="barang_jurusan_id" onchange="filterKategoriAndRakByJurusan(this.value)" class="w-full px-3.5 py-2.5 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-slate-200 focus:outline-none focus:border-sage-600">
                    <option value="">-- Pilih Jurusan --</option>
                </select>
            </div>
            <?php endif; ?>
            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Kode Barcode</label>
                <input type="text" id="barang_barcode" readonly class="w-full px-3.5 py-2.5 bg-slate-100/70 dark:bg-slate-800/80 border border-sage-200 dark:border-slate-700 rounded-xl font-mono font-bold text-slate-700 dark:text-slate-300 cursor-not-allowed focus:outline-none" placeholder="899100100004">
            </div>
            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Alat / Bahan <span class="text-red-500">*</span></label>
                <input type="text" id="barang_nama" required class="w-full px-3.5 py-2.5 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-slate-200 focus:outline-none focus:border-sage-600" placeholder="contoh: Router Mikrotik Hex Gr3 / Kabel UTP">
            </div>
            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Jenis Inventaris <span class="text-red-500">*</span></label>
                <select id="barang_jenis" required class="w-full px-3.5 py-2.5 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-slate-200 focus:outline-none focus:border-sage-600">
                    <option value="alat">Alat (Dapat dipinjam & dikembalikan)</option>
                    <option value="bahan">Bahan (Material habis pakai)</option>
                </select>
            </div>
            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Kategori</label>
                <select id="barang_kategori_id" class="w-full px-3.5 py-2.5 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-slate-200 focus:outline-none focus:border-sage-600">
                    <option value="">-- Pilih Kategori --</option>
                </select>
            </div>
            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Rak Penyimpanan</label>
                <select id="barang_rak_id" class="w-full px-3.5 py-2.5 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-slate-200 focus:outline-none focus:border-sage-600">
                    <option value="">-- Pilih Rak Penyimpanan --</option>
                </select>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Merek / Brand</label>
                    <input type="text" id="barang_merek" class="w-full px-3.5 py-2.5 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-slate-200 focus:outline-none focus:border-sage-600" placeholder="Mikrotik">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Stok Awal</label>
                    <input type="number" id="barang_stok" min="0" value="10" class="w-full px-3.5 py-2.5 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-xl font-bold text-slate-800 dark:text-slate-200 focus:outline-none focus:border-sage-600">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Satuan <span class="text-red-500">*</span></label>
                    <input type="text" id="barang_satuan" required value="Unit" class="w-full px-3.5 py-2.5 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-slate-200 focus:outline-none focus:border-sage-600" placeholder="Unit, Meter, Pcs...">
                </div>
            </div>

            <!-- FOTO / GAMBAR BARANG (MAKSIMAL 1 FOTO) -->
            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Foto / Gambar Barang <span class="text-slate-400 font-normal">(Maksimal 1 Foto - Opsional)</span></label>
                <div class="flex items-center gap-3">
                    <label for="barang_image_input" class="cursor-pointer px-4 py-2 bg-sage-600 hover:bg-sage-700 active:bg-sage-800 text-white rounded-xl font-bold text-xs inline-flex items-center gap-2 transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>Pilih Gambar</span>
                    </label>
                    <input type="file" id="barang_image_input" accept="image/png,image/jpeg,image/webp,image/jpg" class="hidden" onchange="previewBarangImage(this)">
                    <div id="barang_image_preview_box" class="hidden items-center gap-2">
                        <img id="barang_image_preview" src="" alt="Preview" class="w-8 h-8 rounded-lg object-cover border border-sage-200 dark:border-slate-700 shadow-xs cursor-pointer" onclick="if(this.src) showImageModal(this.src)" title="Klik untuk memperbesar">
                        <button type="button" id="btn_remove_barang_image" onclick="removeBarangImage()" class="px-2.5 py-1 text-xs text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/40 rounded-lg font-bold transition-colors">Hapus</button>
                    </div>
                </div>
            </div>

            <div class="pt-3 flex justify-end gap-3 border-t border-sage-100 dark:border-slate-800">
                <button type="button" onclick="closeModal('modalBarang')" class="px-4 py-2 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2 bg-sage-600 text-white font-bold rounded-xl shadow-md shadow-sage-600/20 hover:bg-sage-700">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<!-- 4. MODAL BARANG MASUK -->
<div id="modalBarangMasuk" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-4 overflow-y-auto bg-slate-900/50 backdrop-blur-sm animate-fade-in-up">
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-sage-200 dark:border-slate-800 shadow-2xl w-full max-w-lg max-h-[90vh] flex flex-col overflow-hidden">
        <div class="p-6 bg-sage-50/80 dark:bg-slate-800/80 border-b border-sage-100 dark:border-slate-700 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800 dark:text-white" id="modalBarangMasukTitle">Catat Transaksi Alat & Bahan Masuk</h3>
            </div>
            <button onclick="closeModal('modalBarangMasuk')" class="text-slate-400 hover:text-red-600 p-1.5 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form onsubmit="handleFormSubmit(event, 'Barang Masuk')" class="p-6 space-y-4 text-xs overflow-y-auto flex-1">
            <input type="hidden" name="csrf_token" value="<?= getCsrfToken(); ?>">
            <input type="hidden" id="masuk_edit_id" value="">

            <!-- Checklist: Apakah ingin mencatat masuk menggunakan scan barcode/QR code data alat & bahannya? -->
            <div class="p-3 bg-sage-50/60 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-2xl flex items-center justify-between">
                <label class="flex items-center gap-2.5 cursor-pointer select-none">
                    <input type="checkbox" id="masuk_use_barcode" onchange="toggleMasukBarcodeScanner(this.checked)" class="w-4 h-4 rounded accent-sage-600 cursor-pointer">
                    <span class="font-bold text-slate-800 dark:text-slate-200 text-xs">
                        Catat Masuk Menggunakan Scan Barcode / QR Code Barang
                    </span>
                </label>
            </div>

            <!-- CONTAINER SCANNER BARCODE (TAMPIL JIKA CHECKBOX DICENTANG) -->
            <div id="section_scan_barcode_masuk" class="hidden space-y-3 py-1 px-0 bg-transparent rounded-2xl animate-fade-in-up">
                <!-- Tab Pilihan Metode Scan -->
                <div class="flex items-center justify-between pb-1">
                    <span class="font-bold text-slate-700 dark:text-slate-200 text-[11px] uppercase tracking-wider">
                        Metode Scan Barcode
                    </span>
                    <div class="flex items-center bg-slate-100/40 dark:bg-slate-800/40 rounded-xl p-1 border border-slate-200 dark:border-slate-700 text-xs">
                        <button type="button" id="btn_mode_kamera_masuk" onclick="switchMasukScanMode('kamera')" class="px-3 py-1 rounded-lg font-bold transition-all bg-sage-600 text-white shadow-sm">Kamera</button>
                        <button type="button" id="btn_mode_file_masuk" onclick="switchMasukScanMode('file')" class="px-3 py-1 rounded-lg font-semibold text-slate-600 dark:text-slate-300 hover:text-sage-600 dark:hover:text-sage-400 transition-all">Pilih Gambar</button>
                    </div>
                </div>

                <!-- 1. MODE KAMERA LIVE -->
                <div id="masuk_scan_camera_pane" class="space-y-3">
                    <div id="masuk_camera_select_wrap" class="hidden">
                        <select id="masuk_camera_select" onchange="changeMasukCamera(this.value)" class="w-full px-3 py-1.5 text-xs bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl font-medium text-slate-700 dark:text-slate-300 focus:outline-none focus:border-sage-600">
                            <option value="">Pilih Kamera...</option>
                        </select>
                    </div>

                    <div id="masuk_camera_view_wrap" class="hidden relative w-full max-w-sm mx-auto overflow-hidden rounded-2xl bg-transparent min-h-[220px] flex items-center justify-center border border-slate-200 dark:border-slate-700">
                        <div id="masuk_barcode_reader" class="w-full h-full min-h-[220px] bg-transparent"></div>
                    </div>
                    <div id="masuk_camera_placeholder" class="hidden"></div>

                    <div class="flex items-center justify-start py-2">
                        <button type="button" id="btn_toggle_camera_masuk" onclick="toggleMasukCameraStream()" class="px-5 py-2.5 bg-sage-600 hover:bg-sage-700 text-white font-bold rounded-xl text-xs flex items-center gap-2 shadow-none hover:shadow-none transition-colors cursor-pointer" style="box-shadow: none !important;">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Nyalakan Kamera</span>
                        </button>
                    </div>
                </div>

                <!-- 2. MODE PILIH GAMBAR (CHOOSE FILE) -->
                <div id="masuk_scan_file_pane" class="hidden space-y-3">
                    <input type="file" id="masuk_barcode_file_input" accept="image/*" class="hidden" onchange="handleMasukBarcodeFileUpload(this)">
                    <div class="flex items-center justify-start py-2">
                        <button type="button" onclick="document.getElementById('masuk_barcode_file_input').click()" class="px-5 py-2.5 bg-sage-600 hover:bg-sage-700 text-white font-bold rounded-xl text-xs flex items-center gap-2 shadow-none hover:shadow-none transition-colors cursor-pointer" style="box-shadow: none !important;">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>Pilih Berkas Gambar</span>
                        </button>
                    </div>
                    <div id="masuk_file_scan_status" class="hidden text-left py-1.5">
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-sage-600 dark:text-sage-400">
                            <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                            Menganalisis dan memindai barcode pada gambar...
                        </span>
                    </div>
                </div>

                <!-- 3. KOTAK FEEDBACK HASIL SCAN & SINKRONISASI -->
                <div id="masuk_scan_feedback" class="hidden p-3 rounded-2xl text-xs transition-all"></div>
            </div>

            <!-- BAGIAN INPUT MANUAL (JURUSAN, JENIS, INVENTARIS) - OTOMATIS DISEMBUNYIKAN SAAT SCAN BARCODE AKTIF -->
            <?php if (!empty($user['peran']) && $user['peran'] === 'admin_sekolah'): ?>
            <div id="wrap_masuk_jurusan">
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Jurusan / Departemen</label>
                <select id="masuk_jurusan_id" onchange="filterBarangMasukOptions()" class="w-full px-3.5 py-2.5 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-white focus:outline-none focus:border-sage-600">
                    <option value="">-- Semua Jurusan --</option>
                </select>
            </div>
            <?php endif; ?>
            <div id="wrap_masuk_jenis">
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Pilih Jenis Barang <span class="text-red-500">*</span></label>
                <select id="masuk_jenis" onchange="filterBarangMasukOptions()" class="w-full px-3.5 py-2.5 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-white focus:outline-none focus:border-sage-600">
                    <option value="">-- Semua Jenis (Alat dan Bahan) --</option>
                    <option value="alat">Alat</option>
                    <option value="bahan">Bahan</option>
                </select>
            </div>
            <div id="wrap_masuk_barang" class="relative">
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1" id="masuk_barang_label">Pilih Alat dan Bahan <span class="text-red-500">*</span></label>
                <select id="masuk_barang_id" class="hidden">
                    <option value="">-- Pilih Alat dan Bahan --</option>
                </select>
                <div class="relative">
                    <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none flex items-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" id="masuk_barang_search" autocomplete="off" placeholder="Ketik untuk mencari Alat dan Bahan masuk..." class="w-full pl-9 pr-16 py-2.5 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-xl font-semibold text-xs text-slate-800 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-sage-600 focus:ring-1 focus:ring-sage-600 transition-all cursor-pointer">
                    <div class="absolute right-2.5 top-1/2 -translate-y-1/2 flex items-center gap-1">
                        <button type="button" id="masuk_barang_clear" tabindex="-1" class="hidden p-1 text-slate-400 hover:text-red-500 rounded-lg transition-colors" title="Hapus pilihan">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                        <button type="button" id="masuk_barang_chevron" tabindex="-1" class="p-1 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 rounded-lg transition-transform">
                            <svg class="w-4 h-4 transform transition-transform duration-200" id="masuk_barang_chevron_icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </div>
                    <div id="masuk_barang_menu" class="hidden absolute z-50 left-0 right-0 mt-1 max-h-56 overflow-y-auto bg-white dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-2xl shadow-xl divide-y divide-slate-100 dark:divide-slate-700/50 text-xs"></div>
                </div>
            </div>
            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Jumlah Masuk <span class="text-red-500">*</span></label>
                <input type="number" id="masuk_jumlah" min="1" value="5" required class="w-full px-3.5 py-2.5 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-xl font-bold text-slate-800 dark:text-white focus:outline-none focus:border-sage-600">
            </div>
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block font-bold text-slate-700 dark:text-slate-300">Tanggal Masuk <span class="text-red-500">*</span></label>
                    <label class="flex items-center gap-1.5 text-[11px] font-semibold text-slate-600 dark:text-slate-400 cursor-pointer select-none">
                        <input type="checkbox" id="masuk_tgl_auto" checked onchange="toggleAutoDateMasuk(this.checked)" class="w-3.5 h-3.5 rounded accent-sage-600 cursor-pointer">
                        <span>Sinkron Otomatis (Hari Ini)</span>
                    </label>
                </div>
                <div class="relative flex items-center">
                    <input type="date" id="masuk_tanggal" value="<?= date('Y-m-d'); ?>" required onclick="openNativeDatePicker(this)" class="w-full pl-3.5 pr-10 py-2.5 bg-slate-100 dark:bg-slate-800/60 border border-sage-200 dark:border-slate-700 rounded-xl font-semibold text-xs text-slate-800 dark:text-white focus:outline-none focus:border-sage-600 transition-colors cursor-not-allowed opacity-75 dark:[color-scheme:dark]" disabled>
                    <button type="button" id="masuk_tgl_picker_btn" onclick="triggerDatePick('masuk_tanggal')" class="absolute right-2.5 p-1.5 text-slate-400 hover:text-sage-600 dark:hover:text-white rounded-lg transition-colors cursor-pointer" title="Pilih Tanggal dari Kalender">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </button>
                </div>
            </div>
            <div class="pt-3 flex justify-end gap-3 border-t border-sage-100 dark:border-slate-700">
                <button type="button" onclick="closeModal('modalBarangMasuk')" class="px-4 py-2 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2 bg-sage-600 text-white font-bold rounded-xl shadow-md shadow-sage-600/20 hover:bg-sage-700">Simpan Alat & Bahan Masuk</button>
            </div>
        </form>
    </div>
</div>

<!-- 4.5. MODAL BARANG KELUAR -->
<div id="modalBarangKeluar" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-4 overflow-y-auto bg-slate-900/50 backdrop-blur-sm animate-fade-in-up">
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-sage-200 dark:border-slate-800 shadow-2xl w-full max-w-lg max-h-[90vh] flex flex-col overflow-hidden">
        <div class="p-6 bg-sage-50/80 dark:bg-slate-800/80 border-b border-sage-100 dark:border-slate-700 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12"/></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800 dark:text-white" id="modalBarangKeluarTitle">Catat Transaksi Bahan Keluar</h3>
            </div>
            <button onclick="closeModal('modalBarangKeluar')" class="text-slate-400 hover:text-red-600 p-1.5 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form onsubmit="handleFormSubmit(event, 'Barang Keluar')" class="p-6 space-y-4 text-xs overflow-y-auto flex-1">
            <input type="hidden" name="csrf_token" value="<?= getCsrfToken(); ?>">
            <input type="hidden" id="keluar_edit_id" value="">

            <!-- Checklist: Apakah ingin mencatat keluar menggunakan scan barcode/QR code data bahan? -->
            <div class="p-3 bg-sage-50/60 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-2xl flex items-center justify-between">
                <label class="flex items-center gap-2.5 cursor-pointer select-none">
                    <input type="checkbox" id="keluar_use_barcode" onchange="toggleKeluarBarcodeScanner(this.checked)" class="w-4 h-4 rounded accent-sage-600 cursor-pointer">
                    <span class="font-bold text-slate-800 dark:text-slate-200 text-xs">
                        Catat Keluar Menggunakan Scan Barcode / QR Code Bahan
                    </span>
                </label>
            </div>

            <!-- CONTAINER SCANNER BARCODE (TAMPIL JIKA CHECKBOX DICENTANG) -->
            <div id="section_scan_barcode_keluar" class="hidden space-y-3 py-1 px-0 bg-transparent rounded-2xl animate-fade-in-up">
                <!-- Tab Pilihan Metode Scan -->
                <div class="flex items-center justify-between pb-1">
                    <span class="font-bold text-slate-700 dark:text-slate-200 text-[11px] uppercase tracking-wider">
                        Metode Scan Barcode
                    </span>
                    <div class="flex items-center bg-slate-100/40 dark:bg-slate-800/40 rounded-xl p-1 border border-slate-200 dark:border-slate-700 text-xs">
                        <button type="button" id="btn_mode_kamera_keluar" onclick="switchKeluarScanMode('kamera')" class="px-3 py-1 rounded-lg font-bold transition-all bg-sage-600 text-white shadow-sm">Kamera</button>
                        <button type="button" id="btn_mode_file_keluar" onclick="switchKeluarScanMode('file')" class="px-3 py-1 rounded-lg font-semibold text-slate-600 dark:text-slate-300 hover:text-sage-600 dark:hover:text-sage-400 transition-all">Pilih Gambar</button>
                    </div>
                </div>

                <!-- 1. MODE KAMERA LIVE -->
                <div id="keluar_scan_camera_pane" class="space-y-3">
                    <div id="keluar_camera_select_wrap" class="hidden">
                        <select id="keluar_camera_select" onchange="changeKeluarCamera(this.value)" class="w-full px-3 py-1.5 text-xs bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl font-medium text-slate-700 dark:text-slate-300 focus:outline-none focus:border-sage-600">
                            <option value="">Pilih Kamera...</option>
                        </select>
                    </div>

                    <div id="keluar_camera_view_wrap" class="hidden relative w-full max-w-sm mx-auto overflow-hidden rounded-2xl bg-transparent min-h-[220px] flex items-center justify-center border border-slate-200 dark:border-slate-700">
                        <div id="keluar_barcode_reader" class="w-full h-full min-h-[220px] bg-transparent"></div>
                    </div>
                    <div id="keluar_camera_placeholder" class="hidden"></div>

                    <div class="flex items-center justify-start py-2">
                        <button type="button" id="btn_toggle_camera_keluar" onclick="toggleKeluarCameraStream()" class="px-5 py-2.5 bg-sage-600 hover:bg-sage-700 text-white font-bold rounded-xl text-xs flex items-center gap-2 shadow-none hover:shadow-none transition-colors cursor-pointer" style="box-shadow: none !important;">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Nyalakan Kamera</span>
                        </button>
                    </div>
                </div>

                <!-- 2. MODE PILIH GAMBAR (CHOOSE FILE) -->
                <div id="keluar_scan_file_pane" class="hidden space-y-3">
                    <input type="file" id="keluar_barcode_file_input" accept="image/*" class="hidden" onchange="handleKeluarBarcodeFileUpload(this)">
                    <div class="flex items-center justify-start py-2">
                        <button type="button" onclick="document.getElementById('keluar_barcode_file_input').click()" class="px-5 py-2.5 bg-sage-600 hover:bg-sage-700 text-white font-bold rounded-xl text-xs flex items-center gap-2 shadow-none hover:shadow-none transition-colors cursor-pointer" style="box-shadow: none !important;">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>Pilih Berkas Gambar</span>
                        </button>
                    </div>
                    <div id="keluar_file_scan_status" class="hidden text-left py-1.5">
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-sage-600 dark:text-sage-400">
                            <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                            Menganalisis dan memindai barcode pada gambar...
                        </span>
                    </div>
                </div>

                <!-- 3. KOTAK FEEDBACK HASIL SCAN & SINKRONISASI -->
                <div id="keluar_scan_feedback" class="hidden p-3 rounded-2xl text-xs transition-all"></div>
            </div>

            <!-- BAGIAN INPUT MANUAL (JURUSAN, BAHAN) - OTOMATIS DISEMBUNYIKAN SAAT SCAN BARCODE AKTIF -->
            <?php if (!empty($user['peran']) && $user['peran'] === 'admin_sekolah'): ?>
            <div id="wrap_keluar_jurusan">
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Jurusan / Departemen</label>
                <select id="keluar_jurusan_id" onchange="filterBarangKeluarOptions()" class="w-full px-3.5 py-2.5 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-white focus:outline-none focus:border-sage-600">
                    <option value="">-- Semua Jurusan --</option>
                </select>
            </div>
            <?php endif; ?>
            <div id="wrap_keluar_barang" class="relative">
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1" id="keluar_barang_label">Pilih Bahan <span class="text-red-500">*</span></label>
                <select id="keluar_barang_id" class="hidden">
                    <option value="">-- Pilih Bahan --</option>
                    <?php if (!empty($dbBarang)): ?>
                        <?php foreach ($dbBarang as $b): ?>
                            <?php if (strtolower($b['jenis'] ?? 'alat') === 'bahan'): ?>
                                <option value="<?= htmlspecialchars($b['id']); ?>"><?= htmlspecialchars($b['nama_barang']); ?> (Tersedia: <?= htmlspecialchars($b['stok_tersedia']); ?> <?= htmlspecialchars($b['satuan'] ?? 'Unit'); ?>)</option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <div class="relative">
                    <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none flex items-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" id="keluar_barang_search" autocomplete="off" placeholder="Ketik untuk mencari Bahan keluar..." class="w-full pl-9 pr-16 py-2.5 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-xl font-semibold text-xs text-slate-800 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-sage-600 focus:ring-1 focus:ring-sage-600 transition-all cursor-pointer">
                    <div class="absolute right-2.5 top-1/2 -translate-y-1/2 flex items-center gap-1">
                        <button type="button" id="keluar_barang_clear" tabindex="-1" class="hidden p-1 text-slate-400 hover:text-red-500 rounded-lg transition-colors" title="Hapus pilihan">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                        <button type="button" id="keluar_barang_chevron" tabindex="-1" class="p-1 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 rounded-lg transition-transform">
                            <svg class="w-4 h-4 transform transition-transform duration-200" id="keluar_barang_chevron_icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </div>
                    <div id="keluar_barang_menu" class="hidden absolute z-50 left-0 right-0 mt-1 max-h-56 overflow-y-auto bg-white dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-2xl shadow-xl divide-y divide-slate-100 dark:divide-slate-700/50 text-xs"></div>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Penerima / Peruntukan <span class="text-red-500">*</span></label>
                    <input type="text" id="keluar_penerima" required class="w-full px-3.5 py-2.5 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-white focus:outline-none focus:border-sage-600" placeholder="contoh: Lab Komputer 2 / Ahmad">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Jumlah Keluar <span class="text-red-500">*</span></label>
                    <input type="number" id="keluar_jumlah" min="1" value="1" required class="w-full px-3.5 py-2.5 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-xl font-bold text-slate-800 dark:text-white focus:outline-none focus:border-sage-600">
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block font-bold text-slate-700 dark:text-slate-300">Tanggal Keluar <span class="text-red-500">*</span></label>
                    <label class="flex items-center gap-1.5 text-[11px] font-semibold text-slate-600 dark:text-slate-400 cursor-pointer select-none">
                        <input type="checkbox" id="keluar_tgl_auto" checked onchange="toggleAutoDateKeluar(this.checked)" class="w-3.5 h-3.5 rounded accent-sage-600 cursor-pointer">
                        <span>Sinkron Otomatis (Hari Ini)</span>
                    </label>
                </div>
                <div class="relative flex items-center">
                    <input type="date" id="keluar_tanggal" value="<?= date('Y-m-d'); ?>" required onclick="openNativeDatePicker(this)" class="w-full pl-3.5 pr-10 py-2.5 bg-slate-100 dark:bg-slate-800/60 border border-sage-200 dark:border-slate-700 rounded-xl font-semibold text-xs text-slate-800 dark:text-white focus:outline-none focus:border-sage-600 transition-colors cursor-not-allowed opacity-75 dark:[color-scheme:dark]" disabled>
                    <button type="button" id="keluar_tgl_picker_btn" onclick="triggerDatePick('keluar_tanggal')" class="absolute right-2.5 p-1.5 text-slate-400 hover:text-sage-600 dark:hover:text-white rounded-lg transition-colors cursor-pointer" title="Pilih Tanggal dari Kalender">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </button>
                </div>
            </div>
            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Keterangan / Alasan</label>
                <textarea id="keluar_keterangan" rows="2" class="w-full px-3.5 py-2.5 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-white focus:outline-none focus:border-sage-600" placeholder="contoh: Pemakaian bahan praktik jaringan"></textarea>
            </div>
            <div class="pt-3 flex justify-end gap-3 border-t border-sage-100 dark:border-slate-700">
                <button type="button" onclick="closeModal('modalBarangKeluar')" class="px-4 py-2 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2 bg-sage-600 text-white font-bold rounded-xl shadow-md shadow-sage-600/20 hover:bg-sage-700">Simpan Bahan Keluar</button>
            </div>
        </form>
    </div>
</div>

<!-- 5. MODAL PEMINJAMAN ALAT -->
<div id="modalPeminjaman" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-4 overflow-y-auto bg-slate-900/50 backdrop-blur-sm animate-fade-in-up">
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-sage-200 dark:border-slate-800 shadow-2xl w-full max-w-lg max-h-[90vh] flex flex-col overflow-hidden">
        <div class="p-6 bg-sage-50/80 dark:bg-slate-800/80 border-b border-sage-100 dark:border-slate-700 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800 dark:text-white" id="modalPeminjamanTitle">Tambah Transaksi Peminjaman</h3>
            </div>
            <button onclick="closeModal('modalPeminjaman')" class="text-slate-400 hover:text-red-600 p-1.5 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form onsubmit="handleFormSubmit(event, 'Peminjaman Alat')" class="p-6 space-y-4 text-xs overflow-y-auto flex-1">
            <input type="hidden" name="csrf_token" value="<?= getCsrfToken(); ?>">
            <input type="hidden" id="pinjam_edit_id" value="">
            <!-- Checklist: Apakah ingin meminjam menggunakan scan barcode/QR code data alat & bahannya? -->
            <div class="p-3 bg-sage-50/60 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-2xl flex items-center justify-between">
                <label class="flex items-center gap-2.5 cursor-pointer select-none">
                    <input type="checkbox" id="pinjam_use_barcode" onchange="togglePinjamBarcodeScanner(this.checked)" class="w-4 h-4 rounded accent-sage-600 cursor-pointer">
                    <span class="font-bold text-slate-800 dark:text-slate-200 text-xs">
                        Pinjam Menggunakan Scan Barcode / QR Code Barang
                    </span>
                </label>
            </div>

            <!-- CONTAINER SCANNER BARCODE (TAMPIL JIKA CHECKBOX DICENTANG) -->
            <div id="section_scan_barcode_peminjaman" class="hidden space-y-3 py-1 px-0 bg-transparent rounded-2xl animate-fade-in-up">
                <!-- Tab Pilihan Metode Scan -->
                <div class="flex items-center justify-between pb-1">
                    <span class="font-bold text-slate-700 dark:text-slate-200 text-[11px] uppercase tracking-wider">
                        Metode Scan Barcode
                    </span>
                    <div class="flex items-center bg-slate-100/40 dark:bg-slate-800/40 rounded-xl p-1 border border-slate-200 dark:border-slate-700 text-xs">
                        <button type="button" id="btn_mode_kamera" onclick="switchPinjamScanMode('kamera')" class="px-3 py-1 rounded-lg font-bold transition-all bg-sage-600 text-white shadow-sm">Kamera</button>
                        <button type="button" id="btn_mode_file" onclick="switchPinjamScanMode('file')" class="px-3 py-1 rounded-lg font-semibold text-slate-600 dark:text-slate-300 hover:text-sage-600 dark:hover:text-sage-400 transition-all">Pilih Gambar</button>
                    </div>
                </div>

                <!-- 1. MODE KAMERA LIVE -->
                <div id="pinjam_scan_camera_pane" class="space-y-3">
                    <div id="pinjam_camera_select_wrap" class="hidden">
                        <select id="pinjam_camera_select" onchange="changePinjamCamera(this.value)" class="w-full px-3 py-1.5 text-xs bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl font-medium text-slate-700 dark:text-slate-300 focus:outline-none focus:border-sage-600">
                            <option value="">Pilih Kamera...</option>
                        </select>
                    </div>

                    <div id="pinjam_camera_view_wrap" class="hidden relative w-full max-w-sm mx-auto overflow-hidden rounded-2xl bg-transparent min-h-[220px] flex items-center justify-center border border-slate-200 dark:border-slate-700">
                        <div id="pinjam_barcode_reader" class="w-full h-full min-h-[220px] bg-transparent"></div>
                    </div>
                    <div id="pinjam_camera_placeholder" class="hidden"></div>

                    <div class="flex items-center justify-start py-2">
                        <button type="button" id="btn_toggle_camera" onclick="togglePinjamCameraStream()" class="px-5 py-2.5 bg-sage-600 hover:bg-sage-700 text-white font-bold rounded-xl text-xs flex items-center gap-2 shadow-none hover:shadow-none transition-colors cursor-pointer" style="box-shadow: none !important;">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Nyalakan Kamera</span>
                        </button>
                    </div>
                </div>

                <!-- 2. MODE PILIH GAMBAR (CHOOSE FILE) -->
                <div id="pinjam_scan_file_pane" class="hidden space-y-3">
                    <input type="file" id="pinjam_barcode_file_input" accept="image/*" class="hidden" onchange="handlePinjamBarcodeFileUpload(this)">
                    <div class="flex items-center justify-start py-2">
                        <button type="button" onclick="document.getElementById('pinjam_barcode_file_input').click()" class="px-5 py-2.5 bg-sage-600 hover:bg-sage-700 text-white font-bold rounded-xl text-xs flex items-center gap-2 shadow-none hover:shadow-none transition-colors cursor-pointer" style="box-shadow: none !important;">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>Pilih Berkas Gambar</span>
                        </button>
                    </div>
                    <div id="pinjam_file_scan_status" class="hidden text-left py-1.5">
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-sage-600 dark:text-sage-400">
                            <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                            Menganalisis dan memindai barcode pada gambar...
                        </span>
                    </div>
                </div>

                <!-- 3. KOTAK FEEDBACK HASIL SCAN & SINKRONISASI -->
                <div id="pinjam_scan_feedback" class="hidden p-3 rounded-2xl text-xs transition-all"></div>
            </div>

            <!-- BAGIAN INPUT MANUAL (JURUSAN, JENIS, INVENTARIS) - OTOMATIS DISEMBUNYIKAN SAAT SCAN BARCODE AKTIF -->
            <?php if (!empty($user['peran']) && $user['peran'] === 'admin_sekolah'): ?>
            <div id="wrap_pinjam_jurusan">
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Jurusan / Departemen</label>
                <select id="pinjam_jurusan_id" onchange="filterBarangPinjamOptions(); filterGuruPinjamOptions(); filterSiswaPinjamOptions();" class="w-full px-3.5 py-2.5 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-white focus:outline-none focus:border-sage-600">
                    <option value="">-- Semua Jurusan --</option>
                </select>
            </div>
            <?php endif; ?>
            <div id="wrap_pinjam_jenis">
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Pilih Jenis Barang <span class="text-red-500">*</span></label>
                <select id="pinjam_jenis" disabled class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800/70 border border-slate-200 dark:border-slate-700 rounded-xl font-semibold text-slate-600 dark:text-slate-400 cursor-not-allowed">
                    <option value="alat" selected>Alat</option>
                </select>
            </div>
            <div id="wrap_pinjam_barang" class="relative">
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1" id="pinjam_barang_label">Pilih Alat <span class="text-red-500">*</span></label>
                <select id="pinjam_barang_id" class="hidden">
                    <option value="">-- Pilih Alat --</option>
                </select>
                <div class="relative">
                    <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none flex items-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" id="pinjam_barang_search" autocomplete="off" placeholder="Ketik untuk mencari Alat..." class="w-full pl-9 pr-16 py-2.5 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-xl font-semibold text-xs text-slate-800 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-sage-600 focus:ring-1 focus:ring-sage-600 transition-all cursor-pointer">
                    <div class="absolute right-2.5 top-1/2 -translate-y-1/2 flex items-center gap-1">
                        <button type="button" id="pinjam_barang_clear" tabindex="-1" class="hidden p-1 text-slate-400 hover:text-red-500 rounded-lg transition-colors" title="Hapus pilihan">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                        <button type="button" id="pinjam_barang_chevron" tabindex="-1" class="p-1 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 rounded-lg transition-transform">
                            <svg class="w-4 h-4 transform transition-transform duration-200" id="pinjam_barang_chevron_icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </div>
                    <div id="pinjam_barang_menu" class="hidden absolute z-50 left-0 right-0 mt-1 max-h-56 overflow-y-auto bg-white dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-2xl shadow-xl divide-y divide-slate-100 dark:divide-slate-700/50 text-xs"></div>
                </div>
            </div>
            <div id="wrap_pinjam_guru" class="relative">
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1" id="pinjam_guru_label">Guru Peminjam</label>
                <select id="pinjam_guru_peminjam_select" onchange="toggleGuruPeminjamMode(this.value)" class="hidden">
                    <option value="">-- Pilih Guru Peminjam --</option>
                </select>
                <div class="relative">
                    <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none flex items-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" id="pinjam_guru_search" autocomplete="off" placeholder="Ketik untuk mencari Guru Peminjam..." class="w-full pl-9 pr-16 py-2.5 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-xl font-semibold text-xs text-slate-800 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-sage-600 focus:ring-1 focus:ring-sage-600 transition-all cursor-pointer">
                    <div class="absolute right-2.5 top-1/2 -translate-y-1/2 flex items-center gap-1">
                        <button type="button" id="pinjam_guru_clear" tabindex="-1" class="hidden p-1 text-slate-400 hover:text-red-500 rounded-lg transition-colors" title="Hapus pilihan">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                        <button type="button" id="pinjam_guru_chevron" tabindex="-1" class="p-1 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 rounded-lg transition-transform">
                            <svg class="w-4 h-4 transform transition-transform duration-200" id="pinjam_guru_chevron_icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </div>
                    <div id="pinjam_guru_menu" class="hidden absolute z-50 left-0 right-0 mt-1 max-h-56 overflow-y-auto bg-white dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-2xl shadow-xl divide-y divide-slate-100 dark:divide-slate-700/50 text-xs"></div>
                </div>
                <input type="text" id="pinjam_guru_peminjam_custom" placeholder="Ketik nama guru peminjam manual..." class="mt-2 hidden w-full px-3.5 py-2.5 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-white focus:outline-none focus:border-sage-600">
            </div>

            <!-- Checklist: Apakah dipinjamkan untuk siswa? -->
            <div class="p-3 bg-sage-50/60 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-2xl flex items-center justify-between">
                <label class="flex items-center gap-2.5 cursor-pointer select-none">
                    <input type="checkbox" id="pinjam_untuk_siswa" onchange="togglePinjamUntukSiswa(this.checked)" class="w-4 h-4 rounded accent-sage-600 cursor-pointer">
                    <span class="font-bold text-slate-800 dark:text-slate-200 text-xs">Apakah dipinjamkan untuk siswa?</span>
                </label>
                <span id="pinjam_untuk_siswa_badge" class="text-xs font-medium text-slate-400 dark:text-slate-400">Tidak (Guru Langsung)</span>
            </div>

            <!-- Bagian Data Siswa (Hanya tampil jika checklist dicentang) -->
            <div id="section_siswa_peminjam" class="hidden space-y-3">
                <div id="wrap_pinjam_siswa" class="relative">
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1" id="pinjam_siswa_label">Siswa Peminjam <span class="text-red-500">*</span></label>
                    <select id="pinjam_peminjam_select" onchange="onSiswaSelectedInPeminjamanForm(this.value)" class="hidden">
                        <option value="">-- Pilih Siswa Peminjam --</option>
                    </select>
                    <div class="relative">
                        <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none flex items-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" id="pinjam_siswa_search" autocomplete="off" placeholder="Ketik untuk mencari Siswa Peminjam..." class="w-full pl-9 pr-16 py-2.5 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-xl font-semibold text-xs text-slate-800 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-sage-600 focus:ring-1 focus:ring-sage-600 transition-all cursor-pointer">
                        <div class="absolute right-2.5 top-1/2 -translate-y-1/2 flex items-center gap-1">
                            <button type="button" id="pinjam_siswa_clear" tabindex="-1" class="hidden p-1 text-slate-400 hover:text-red-500 rounded-lg transition-colors" title="Hapus pilihan">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                            <button type="button" id="pinjam_siswa_chevron" tabindex="-1" class="p-1 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 rounded-lg transition-transform">
                                <svg class="w-4 h-4 transform transition-transform duration-200" id="pinjam_siswa_chevron_icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                        </div>
                        <div id="pinjam_siswa_menu" class="hidden absolute z-50 left-0 right-0 mt-1 max-h-56 overflow-y-auto bg-white dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-2xl shadow-xl divide-y divide-slate-100 dark:divide-slate-700/50 text-xs"></div>
                    </div>
                    <input type="text" id="pinjam_peminjam_custom" placeholder="Ketik nama siswa peminjam..." class="mt-2 hidden w-full px-3.5 py-2.5 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-white focus:outline-none focus:border-sage-600">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">NISN Siswa</label>
                        <input type="text" id="pinjam_nisn" readonly class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800/80 border border-sage-200 dark:border-slate-700 rounded-xl font-mono font-bold text-slate-600 dark:text-slate-300 cursor-not-allowed" placeholder="Nisn">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Tahun Ajaran (Siswa) <span class="text-red-500">*</span></label>
                        <input type="text" id="pinjam_tahun_ajaran" value="2026/2027" class="w-full px-3.5 py-2.5 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-white focus:outline-none focus:border-sage-600" placeholder="2026/2027">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Jumlah Pinjam <span class="text-red-500">*</span></label>
                    <input type="number" id="pinjam_jumlah" min="1" value="1" required class="w-full px-3.5 py-2.5 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-xl font-bold text-slate-800 dark:text-white focus:outline-none focus:border-sage-600">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Tugas / Keperluan Praktik</label>
                    <input type="text" id="pinjam_tugas" class="w-full px-3.5 py-2.5 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-white focus:outline-none focus:border-sage-600" placeholder="contoh: Praktik Jaringan Komputer">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Tanggal Meminjam</label>
                    <input type="datetime-local" id="pinjam_tanggal_pinjam" value="<?= date('Y-m-d\TH:i'); ?>" onclick="try{this.showPicker()}catch(e){}" class="w-full px-3.5 py-2.5 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-white focus:outline-none focus:border-sage-600 cursor-pointer">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Tanggal Pengembalian (Balik)</label>
                    <input type="datetime-local" id="pinjam_tanggal_kembali" onclick="try{this.showPicker()}catch(e){}" class="w-full px-3.5 py-2.5 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-white focus:outline-none focus:border-sage-600 cursor-pointer">
                </div>
            </div>
            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Status Peminjaman</label>
                <select id="pinjam_status" class="w-full px-3.5 py-2.5 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-xl font-bold text-slate-800 dark:text-white focus:outline-none focus:border-sage-600">
                    <option value="dipinjam">Dipinjam (Masih Dipinjam)</option>
                    <option value="dikembalikan">Dikembalikan (Sudah Kembali)</option>
                </select>
            </div>
            <div class="pt-3 flex justify-end gap-3 border-t border-sage-100 dark:border-slate-800">
                <button type="button" onclick="closeModal('modalPeminjaman')" class="px-4 py-2 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-colors">Batal</button>
                <button type="submit" id="modalPeminjamanSubmitBtn" class="px-5 py-2 bg-sage-600 text-white font-bold rounded-xl shadow-md shadow-sage-600/20 hover:bg-sage-700">Kirim Pengajuan</button>
            </div>
        </form>
    </div>
</div>

<!-- 6. MODAL PREVIEW & CETAK BARCODE -->
<div id="modalBarcodePreview" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-4 overflow-y-auto bg-slate-900/40 backdrop-blur-sm animate-fade-in-up">
    <div class="bg-white rounded-3xl border border-sage-200 shadow-2xl w-full max-w-lg overflow-hidden">
        <div class="p-6 bg-sage-50/80 border-b border-sage-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800" id="previewBarcodeTitle">Hasil Scan Barcode Barang</h3>
                    <p class="text-xs text-slate-500">Label Barcode & Barcode Images</p>
                </div>
            </div>
            <button onclick="closeModal('modalBarcodePreview')" class="text-slate-400 hover:text-red-600 p-1.5 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-6 flex flex-col items-center justify-center space-y-4 max-h-[75vh] overflow-y-auto">
            <div class="p-6 bg-white border-2 border-dashed border-sage-300 rounded-none flex flex-col items-center justify-center shadow-inner w-full">
                <canvas id="barcodeCanvas" class="w-64 h-64 max-w-full rounded-none"></canvas>
                <div class="mt-4 text-center">
                    <p class="font-mono font-extrabold text-slate-800 text-lg tracking-widest" id="previewBarcodeNumber">899100100001</p>
                    <p class="text-xs font-bold text-sage-600 mt-1" id="previewItemName">Router Mikrotik RB951Ui-2HnD</p>
                </div>
            </div>
        </div>
        <div class="p-6 bg-slate-50 border-t border-sage-100 flex items-center justify-between gap-3">
            <button type="button" onclick="closeModal('modalBarcodePreview')" class="px-4 py-2.5 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-colors text-xs">Tutup</button>
            <div class="flex items-center gap-2">
                <button type="button" onclick="downloadBarcodeImage()" class="px-4 py-2.5 bg-sage-600 text-white font-bold rounded-xl shadow-md shadow-sage-600/20 hover:bg-sage-700 transition-colors text-xs flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Simpan Gambar</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- 7. MODAL IMPORT CSV BARANG -->
<div id="modalImportBarang" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-4 overflow-y-auto bg-slate-900/40 backdrop-blur-sm animate-fade-in-up">
    <div class="bg-white rounded-3xl border border-sage-200 shadow-2xl w-full max-w-lg overflow-hidden">
        <div class="p-6 bg-sage-50/50 border-b border-sage-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800">Import Data Barang via CSV</h3>
                    <p class="text-[11px] text-slate-500">Unggah file CSV untuk menambahkan data barang secara massal</p>
                </div>
            </div>
            <button onclick="closeModal('modalImportBarang')" class="text-slate-400 hover:text-red-600 p-1.5 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form onsubmit="handleImportCSVSubmit(event)" class="p-6 space-y-4 text-xs">
            <input type="hidden" name="csrf_token" value="<?= getCsrfToken(); ?>">
            
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 flex items-center justify-between gap-3">
                <div>
                    <p class="font-bold text-slate-700">Belum punya format file?</p>
                    <p class="text-[11px] text-slate-500">Unduh contoh template CSV yang siap diisi</p>
                </div>
                <button type="button" onclick="downloadTemplateBarangCSV()" class="px-3 py-1.5 bg-white border border-slate-300 text-slate-700 hover:bg-slate-100 rounded-xl font-bold text-xs shrink-0 flex items-center gap-1.5 transition-colors">
                    <svg class="w-4 h-4 text-sage-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Unduh Template</span>
                </button>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Pilih File CSV (.csv) <span class="text-red-500">*</span></label>
                <input type="file" id="import_csv_file" accept=".csv" required class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-sage-600 file:text-white hover:file:bg-sage-700">
            </div>

            <div class="pt-3 flex justify-end gap-3 border-t border-sage-100">
                <button type="button" onclick="closeModal('modalImportBarang')" class="px-4 py-2 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2 bg-sage-600 text-white font-bold rounded-xl hover:bg-sage-700">Mulai Import Data</button>
            </div>
        </form>
    </div>
</div>

<!-- 8. MODAL KONFIRMASI CUSTOM (HAPUS / AKSI / PENGEMBALIAN) -->
<div id="modalConfirmDelete" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-4 overflow-y-auto bg-slate-900/40 backdrop-blur-sm animate-fade-in-up">
    <div class="bg-white rounded-3xl border border-red-100 shadow-2xl w-full max-w-sm overflow-hidden">
        <form id="formModalConfirmCustom" onsubmit="handleConfirmSubmit(event)" class="p-6 text-center">
            <div id="confirmIconContainer" class="w-14 h-14 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-3.5 shadow-sm">
                <svg id="confirmIconSvg" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-slate-800 mb-1" id="confirmModalTitle">Konfirmasi Hapus Data</h3>
            <p class="text-xs text-slate-500 mb-4 leading-relaxed" id="confirmModalMessage">
                Apakah Anda yakin ingin menghapus <strong id="deleteEntityName" class="text-slate-800 font-bold">data ini</strong>? Tindakan ini tidak dapat dibatalkan.
            </p>

            <!-- EXTRA FIELD UPLOAD FOTO BUKTI PENGEMBALIAN (OPSIONAL / NULL) -->
            <div id="confirmModalFotoContainer" class="text-left mb-4 hidden">
                <label class="block font-bold text-slate-700 text-xs mb-1">Bukti Foto Pengembalian <span class="text-slate-400 font-normal">(Opsional / Boleh Kosong)</span></label>
                <input type="file" id="confirm_modal_bukti_foto" accept="image/*" class="w-full px-3 py-2 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600 text-xs cursor-pointer file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700">
                <p class="text-[10px] text-slate-400 mt-1">*Jika diunggah, foto dikompresi menjadi format file <strong>.dat</strong></p>
            </div>

            <div class="flex items-center justify-center gap-2.5">
                <button type="button" onclick="closeModal('modalConfirmDelete')" class="px-4 py-2 bg-red-600 text-white font-bold text-xs rounded-xl hover:bg-red-700 transition-colors w-1/2">
                    Batal
                </button>
                <button type="submit" id="confirmModalSubmitBtn" class="px-4 py-2 bg-sage-600 text-white font-bold text-xs rounded-xl shadow-md shadow-sage-600/20 hover:bg-sage-700 transition-colors w-1/2">
                    Ya, Hapus Data
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL KONFIRMASI PENGEMBALIAN ALAT (MINIMALIS & SINGKAT) -->
<div id="modalConfirmPengembalian" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-4 overflow-y-auto bg-slate-900/50 backdrop-blur-sm animate-fade-in-up">
    <div class="bg-white dark:bg-[#141414] rounded-2xl border border-slate-200/80 dark:border-[#222222] shadow-xl w-full max-w-sm overflow-hidden">
        <form id="formConfirmPengembalian" onsubmit="executeKembalikanPeminjaman(event)" class="p-5 text-center">
            <input type="hidden" name="csrf_token" value="<?= getCsrfToken(); ?>">
            <input type="hidden" id="confirm_kembali_id" value="">

            <div class="w-10 h-10 rounded-full bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mx-auto mb-2.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </div>

            <h3 class="text-sm font-bold text-slate-900 dark:text-white">Pengembalian Alat</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 mb-4" id="confirmKembaliMessage">
                Konfirmasi pengembalian alat?
            </p>

            <div class="text-left mb-4">
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Foto Bukti <span class="text-slate-400 dark:text-slate-500 font-normal">(Opsional)</span></label>
                <input type="file" id="confirm_kembali_bukti_foto" accept="image/*" class="w-full text-xs text-slate-500 dark:text-slate-400 file:mr-2.5 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-slate-100 dark:file:bg-[#202020] file:text-slate-700 dark:file:text-slate-300 hover:file:bg-slate-200 dark:hover:file:bg-[#282828] cursor-pointer">
            </div>

            <div class="flex items-center gap-2 pt-1">
                <button type="button" onclick="closeModal('modalConfirmPengembalian')" class="w-1/2 py-2 px-3.5 bg-slate-100 hover:bg-slate-200/80 dark:bg-[#202020] dark:hover:bg-[#282828] text-slate-600 dark:text-slate-300 font-semibold text-xs rounded-xl transition-colors cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="w-1/2 py-2 px-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl shadow-none transition-colors cursor-pointer">
                    Kembalikan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- FULL PREVIEW IMAGE ONLY (TANPA MODAL CARD, FLOATING CLOSE & DOWNLOAD ICONS, NO BORDER RADIUS, EXTRA LARGE DISPLAY) -->
<div id="modalFotoPreview" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-4 overflow-y-auto md:p-8 bg-slate-950/90 cursor-pointer" onclick="if(event.target === this) closeModal('modalFotoPreview')">
    <div class="relative flex items-center justify-center cursor-default">
        <!-- TOMBOL AKSI & NAMA BARANG DISAMPING KANAN ATAS -->
        <div class="absolute -top-12 -right-2 md:-top-14 md:-right-2 flex items-center gap-2.5 z-20 max-w-[92vw]">
            <!-- TOMBOL DOWNLOAD IMAGE -->
            <button type="button" onclick="downloadFotoPreview()" class="text-slate-300 hover:text-white p-2.5 rounded-full bg-slate-800/90 hover:bg-slate-700 transition-colors shadow-2xl border border-slate-700/80 flex items-center justify-center shrink-0 cursor-pointer" title="Unduh / Download Foto">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            </button>
            <!-- TOMBOL CLOSE X -->
            <button type="button" onclick="closeModal('modalFotoPreview')" class="text-slate-300 hover:text-white p-2.5 rounded-full bg-slate-800/90 hover:bg-slate-700 transition-colors shadow-2xl border border-slate-700/80 flex items-center justify-center shrink-0 cursor-pointer" title="Tutup Preview (ESC)">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <!-- NAMA BARANG / JUDUL (DI UJUNG KANAN, HANYA TEKS TANPA CARD) -->
            <div id="previewFotoTitleBox" class="hidden items-center pl-1 text-white max-w-[200px] sm:max-w-[340px] md:max-w-[480px]">
                <span id="previewFotoTitle" class="text-xs sm:text-sm font-bold text-white drop-shadow-md truncate block"></span>
            </div>
        </div>
        <!-- GAMBAR PREVIEW UKURAN BESAR MURNI TANPA BORDER RADIUS -->
        <img id="previewFotoImg" src="" alt="Bukti Foto" class="max-h-[92vh] max-w-[95vw] min-w-[320px] sm:min-w-[480px] md:min-w-[600px] w-auto h-auto object-contain rounded-none shadow-2xl block" loading="lazy" decoding="async">
    </div>
</div>

<!-- MODAL UBAH PASSWORD DARI TOKEN -->
<div id="modalUbahPasswordToken" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-4 overflow-y-auto bg-slate-900/60 backdrop-blur-sm animate-fade-in-up">
    <div class="bg-white dark:bg-[#1a1a1a] rounded-2xl max-w-md w-full p-6 shadow-2xl border border-sage-200/80 dark:border-[#262626] relative transition-all">
        <!-- Close Button (X) -->
        <button type="button" onclick="closeModalUbahPasswordToken(true)" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 rounded-xl hover:bg-slate-100 dark:hover:bg-[#252525] transition-colors" title="Tutup">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <!-- Title & Text (Hanya Teks Saja, Tanpa Emoji & Tanpa Badge Card) -->
        <div class="mb-5 pr-6">
            <h3 class="text-base font-bold text-slate-800 dark:text-white">Ubah Password Akun</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 leading-relaxed">
                Saat ini akun Anda masih menggunakan token bawaan sebagai kata sandi login. Silakan ubah ke kata sandi baru pribadi Anda yang aman dan mudah diingat.
            </p>
        </div>

        <!-- Form -->
        <form id="formUbahPasswordToken" onsubmit="submitUbahPasswordToken(event)" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?= getCsrfToken(); ?>">

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1.5">Kata Sandi Baru <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="password" id="inputTokenNewPassword" required minlength="6" placeholder="Minimal 6 karakter" class="w-full pl-3.5 pr-10 py-2.5 bg-slate-50 dark:bg-[#222222] border border-slate-200 dark:border-[#2d2d2d] rounded-xl text-xs font-semibold text-slate-800 dark:text-white focus:outline-none focus:border-sage-600 focus:bg-white dark:focus:bg-[#1a1a1a] transition-all">
                    <button type="button" onclick="togglePasswordVisibility('inputTokenNewPassword', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                        <svg class="w-4 h-4 eyeOpenIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <svg class="w-4 h-4 eyeCloseIcon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                    </button>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1.5">Konfirmasi Kata Sandi Baru <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="password" id="inputTokenConfirmPassword" required minlength="6" placeholder="Ketik ulang kata sandi baru" class="w-full pl-3.5 pr-10 py-2.5 bg-slate-50 dark:bg-[#222222] border border-slate-200 dark:border-[#2d2d2d] rounded-xl text-xs font-semibold text-slate-800 dark:text-white focus:outline-none focus:border-sage-600 focus:bg-white dark:focus:bg-[#1a1a1a] transition-all">
                    <button type="button" onclick="togglePasswordVisibility('inputTokenConfirmPassword', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                        <svg class="w-4 h-4 eyeOpenIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <svg class="w-4 h-4 eyeCloseIcon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                    </button>
                </div>
                <p id="tokenPasswordErrorText" class="text-[11px] text-red-500 mt-1 hidden"></p>
            </div>

            <!-- Footer Actions -->
            <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100 dark:border-[#262626]">
                <button type="button" onclick="closeModalUbahPasswordToken(true)" class="px-4 py-2 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-[#252525] rounded-xl transition-colors">
                    Nanti Saja
                </button>
                <button type="submit" id="btnSubmitUbahPasswordToken" class="px-5 py-2 bg-sage-600 hover:bg-sage-700 text-white font-bold text-xs rounded-xl shadow-md shadow-sage-600/20 transition-all">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Helper Functions in JavaScript -->
<script>
function parseJakartaDate(str) {
    if (!str) return null;
    if (str instanceof Date) return str;
    if (typeof str === 'string') {
        const trimmed = str.trim();
        if (/^\d{4}-\d{2}-\d{2}[ T]\d{2}:\d{2}(:\d{2})?$/.test(trimmed)) {
            return new Date(trimmed.replace(' ', 'T') + (trimmed.length === 16 ? ':00+07:00' : '+07:00'));
        }
        return new Date(trimmed);
    }
    return new Date(str);
}

function formatForDateTimeLocal(dateStr) {
    if (!dateStr) return '';
    const d = parseJakartaDate(dateStr) || new Date();
    if (isNaN(d.getTime())) return '';
    const parts = new Intl.DateTimeFormat('en-CA', {
        timeZone: 'Asia/Jakarta',
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        hour12: false
    }).formatToParts(d);
    const getPart = type => (parts.find(p => p.type === type) || {}).value || '00';
    return `${getPart('year')}-${getPart('month')}-${getPart('day')}T${getPart('hour')}:${getPart('minute')}`;
}

// Fungsi render barcode pada HTML5 Canvas
function drawBarcodeFallback(code, canvas) {
    const ctx = canvas.getContext('2d');
    canvas.width = 320;
    canvas.height = 100;

    ctx.fillStyle = '#FFFFFF';
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    ctx.fillStyle = '#000000';
    let x = 20;
    const str = String(code);
    
    // Start bar
    ctx.fillRect(x, 15, 3, 70); x += 5;
    ctx.fillRect(x, 15, 1, 70); x += 3;
    ctx.fillRect(x, 15, 2, 70); x += 4;

    for (let i = 0; i < str.length; i++) {
        let charCode = str.charCodeAt(i);
        let pattern = [
            (charCode % 3) + 1,
            ((charCode * 2) % 4) + 1,
            (charCode % 2) + 1,
            ((charCode + 3) % 3) + 1
        ];
        pattern.forEach((w, idx) => {
            if (idx % 2 === 0) {
                ctx.fillRect(x, 15, w * 1.8, 70);
            }
            x += w * 2.2;
        });
    }

    // Stop bar
    ctx.fillRect(x, 15, 3, 70); x += 5;
    ctx.fillRect(x, 15, 1, 70); x += 3;
    ctx.fillRect(x, 15, 4, 70);
}

function encodeBase64(str) {
    try {
        return btoa(unescape(encodeURIComponent(str)));
    } catch(e) {
        return btoa(str);
    }
}
window.encodeBase64 = encodeBase64;

function getSingkatanJurusan(jurusanName) {
    if (!jurusanName || jurusanName === 'Semua Jurusan' || jurusanName === '-') {
        const fallback = (window.userJurusanNama && window.userJurusanNama !== 'Sekolah') ? window.userJurusanNama : 'TKJ';
        return getSingkatanJurusan(fallback);
    }
    const match = String(jurusanName).match(/\(([^)]+)\)/);
    if (match && match[1]) {
        return match[1].toUpperCase().trim();
    }
    const words = String(jurusanName).replace(/[^a-zA-Z0-9\s]/g, '').trim().split(/\s+/);
    if (words.length > 1) {
        return words.map(w => w[0]).join('').toUpperCase();
    }
    return String(jurusanName).substring(0, 4).toUpperCase();
}

function drawCenterLabelOnCanvas(canvas, labelText) {
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    const w = canvas.width || 280;
    const h = canvas.height || 280;
    const centerX = w / 2;
    const centerY = h / 2;

    const text = String(labelText || 'TKJ').toUpperCase();

    // Font setup (proporsional & jelas terbaca oleh QR scanner)
    ctx.font = '900 16px system-ui, -apple-system, sans-serif, Arial';
    const textMetrics = ctx.measureText(text);
    const textWidth = textMetrics.width;
    const badgePaddingX = 10;
    const badgeHeight = 26;
    const badgeWidth = Math.max(textWidth + (badgePaddingX * 2), 46);
    const rectX = centerX - (badgeWidth / 2);
    const rectY = centerY - (badgeHeight / 2);

    ctx.save();
    
    // Latar belakang putih bersih
    ctx.beginPath();
    if (typeof ctx.roundRect === 'function') {
        ctx.roundRect(rectX, rectY, badgeWidth, badgeHeight, 5);
    } else {
        ctx.rect(rectX, rectY, badgeWidth, badgeHeight);
    }
    ctx.fillStyle = '#FFFFFF';
    ctx.fill();

    // Border merah khas aplikasi
    ctx.lineWidth = 2.5;
    ctx.strokeStyle = '#DC2626';
    ctx.stroke();

    // Tulis teks singkatan jurusan tepat di tengah
    ctx.fillStyle = '#0F172A';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    ctx.fillText(text, centerX, centerY + 1);

    ctx.restore();
}

function showBarcodeModal(code, itemName) {
    document.getElementById('previewBarcodeNumber').innerText = code;
    document.getElementById('previewItemName').innerText = itemName;

    // Cari data lengkap item dari window.dbBarang (seluruh data kolom di tabel barang)
    let fullItem = (window.dbBarang || []).find(b => String(b.barcode) === String(code) || String(b.nama_barang) === String(itemName));

    if (!fullItem) {
        fullItem = {
            id: "auto-gen",
            jurusan_id: null,
            kategori_id: null,
            rak_id: null,
            kode_barang: "BRG-001",
            nama_barang: itemName,
            merek: "-",
            barcode: code,
            stok_total: 1,
            stok_tersedia: 1,
            satuan: "Unit",
            nama_kategori: "Hardware",
            nama_jurusan: "Semua Jurusan",
            nama_rak: "Rak A1",
            created_at: new Date().toISOString()
        };
    }

    // Format Data Lengkap Barang
    const cleanPayload = {
        type: "BARANG_INVENTORY",
        id: fullItem.id,
        jurusan_id: fullItem.jurusan_id || null,
        kategori_id: fullItem.kategori_id || null,
        rak_id: fullItem.rak_id || null,
        kode_barang: fullItem.kode_barang || "",
        nama_barang: fullItem.nama_barang || "",
        merek: fullItem.merek || "-",
        barcode: fullItem.barcode || code,
        stok_total: parseInt(fullItem.stok_total || 0),
        stok_tersedia: parseInt(fullItem.stok_tersedia || 0),
        satuan: fullItem.satuan || "Unit",
        nama_kategori: fullItem.nama_kategori || "-",
        nama_jurusan: fullItem.nama_jurusan || "-",
        nama_rak: fullItem.nama_rak || "-"
    };

    // Encode API URL Endpoint di dalam QR Code Barang (menggunakan parameter barang_id)
    const baseUrl = window.location.origin || 'http://localhost:8000';
    const qrApiUrl = baseUrl + '/api/scan.php?barang_id=' + encodeURIComponent(fullItem.id || code);
    const canvas = document.getElementById('barcodeCanvas');
    const labelText = getSingkatanJurusan(fullItem.nama_jurusan);

    if (typeof QRCode !== 'undefined' && typeof QRCode.toCanvas === 'function') {
        QRCode.toCanvas(canvas, qrApiUrl, { width: 280, margin: 2, errorCorrectionLevel: 'H' }, function (err) {
            if (err) console.error(err);
            drawCenterLabelOnCanvas(canvas, labelText);
        });
    } else {
        const ctx = canvas.getContext('2d');
        const img = new Image();
        img.crossOrigin = "Anonymous";
        img.src = `https://api.qrserver.com/v1/create-qr-code/?size=280x280&ecc=H&margin=2&data=${encodeURIComponent(qrApiUrl)}`;
        img.onload = function () {
            canvas.width = 280;
            canvas.height = 280;
            ctx.drawImage(img, 0, 0, 280, 280);
            drawCenterLabelOnCanvas(canvas, labelText);
        };
        img.onerror = function() {
            canvas.width = 280;
            canvas.height = 280;
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(0,0,280,280);
            ctx.fillStyle = '#000000';
            ctx.font = 'bold 12px monospace';
            ctx.fillText(code, 20, 140);
            drawCenterLabelOnCanvas(canvas, labelText);
        };
    }

    openModal('modalBarcodePreview', 'Scan QR Code Barang');
}

function showRakBarcodeModal(code, rakName, rakId) {
    const rak = (window.dbRak || []).find(r => String(r.id) === String(rakId) || String(r.barcode) === String(code)) || {
        id: rakId || "rak-auto",
        nama_rak: rakName,
        barcode: code,
        kategori_rak: "-",
        nama_jurusan: "Semua Jurusan"
    };

    // Encode API URL Endpoint di dalam QR Code Rak (menggunakan parameter rak_id)
    const baseUrl = window.location.origin || 'http://localhost:8000';
    const qrApiUrl = baseUrl + '/api/scan.php?rak_id=' + encodeURIComponent(rak.id || code);

    const previewNum = document.getElementById('previewBarcodeNumber');
    const previewName = document.getElementById('previewItemName');
    if (previewNum) previewNum.innerText = rak.barcode || code;
    if (previewName) previewName.innerText = rak.nama_rak || rakName;

    const canvas = document.getElementById('barcodeCanvas');
    const labelText = getSingkatanJurusan(rak.nama_jurusan);

    if (typeof QRCode !== 'undefined' && typeof QRCode.toCanvas === 'function') {
        QRCode.toCanvas(canvas, qrApiUrl, { width: 280, margin: 2, errorCorrectionLevel: 'H' }, function (err) {
            if (err) console.error(err);
            drawCenterLabelOnCanvas(canvas, labelText);
        });
    } else {
        const ctx = canvas.getContext('2d');
        const img = new Image();
        img.crossOrigin = "Anonymous";
        img.src = `https://api.qrserver.com/v1/create-qr-code/?size=280x280&ecc=H&margin=2&data=${encodeURIComponent(qrApiUrl)}`;
        img.onload = function () {
            canvas.width = 280;
            canvas.height = 280;
            ctx.drawImage(img, 0, 0, 280, 280);
            drawCenterLabelOnCanvas(canvas, labelText);
        };
    }

    openModal('modalBarcodePreview', 'Hasil Scan QR Code Rak & Barang (' + (rak.nama_rak || rakName) + ')');
}

function downloadBarcodeImage() {
    const canvas = document.getElementById('barcodeCanvas');
    const code = document.getElementById('previewBarcodeNumber').innerText || 'barcode';
    const link = document.createElement('a');
    link.download = 'barcode_' + code + '.png';
    link.href = canvas.toDataURL('image/png');
    link.click();
}

// Helper notifikasi standar
function showNotification(message, type = 'warning') {
    if (typeof showToast === 'function') {
        showToast(message, type === 'warning' ? 'error' : type);
    } else {
        alert(message);
    }
}

// --- LIGHTWEIGHT SEARCHABLE COMBOBOX COMPONENT ---
class SearchableSelect {
    constructor(config) {
        this.wrapper = typeof config.wrapper === 'string' ? document.getElementById(config.wrapper) : config.wrapper;
        this.selectEl = typeof config.select === 'string' ? document.getElementById(config.select) : config.select;
        this.inputEl = typeof config.input === 'string' ? document.getElementById(config.input) : config.input;
        this.menuEl = typeof config.menu === 'string' ? document.getElementById(config.menu) : config.menu;
        this.clearBtn = typeof config.clearBtn === 'string' ? document.getElementById(config.clearBtn) : config.clearBtn;
        this.toggleBtn = typeof config.toggleBtn === 'string' ? document.getElementById(config.toggleBtn) : config.toggleBtn;
        this.chevronIcon = typeof config.chevronIcon === 'string' ? document.getElementById(config.chevronIcon) : config.chevronIcon;
        this.emptyText = config.emptyText || 'Barang tidak ditemukan';
        this.placeholder = config.placeholder || 'Ketik untuk mencari...';
        this.onSelect = config.onSelect || null;

        this.items = [];
        this.filteredItems = [];
        this.highlightedIndex = -1;
        this.selectedValue = '';
        this.selectedText = '';

        this.init();
    }

    init() {
        if (!this.inputEl || !this.menuEl) return;

        // Fokus atau klik pada input -> buka dropdown
        this.inputEl.addEventListener('focus', () => {
            if (this.selectedValue) {
                this.inputEl.select();
            }
            this.open();
        });

        this.inputEl.addEventListener('click', () => {
            this.open();
        });

        // Ketik pencarian realtime
        this.inputEl.addEventListener('input', () => {
            this.open();
            this.filter(this.inputEl.value);
        });

        // Navigasi Keyboard (Panah Atas/Bawah, Enter, Escape)
        this.inputEl.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                if (!this.isOpen()) {
                    this.open();
                } else {
                    this.moveHighlight(1);
                }
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                if (this.isOpen()) {
                    this.moveHighlight(-1);
                }
            } else if (e.key === 'Enter') {
                if (this.isOpen() && this.highlightedIndex >= 0 && this.filteredItems[this.highlightedIndex]) {
                    e.preventDefault();
                    this.select(this.filteredItems[this.highlightedIndex]);
                }
            } else if (e.key === 'Escape') {
                this.close();
            }
        });

        // Tombol Chevron Toggle
        if (this.toggleBtn) {
            this.toggleBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                if (this.isOpen()) {
                    this.close();
                } else {
                    this.inputEl.focus();
                    this.open();
                }
            });
        }

        // Tombol Clear (X)
        if (this.clearBtn) {
            this.clearBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.clear();
                this.inputEl.focus();
                this.open();
            });
        }

        // Tutup jika klik di luar
        document.addEventListener('click', (e) => {
            if (this.wrapper && !this.wrapper.contains(e.target)) {
                this.close();
            }
        });
    }

    isOpen() {
        return this.menuEl && !this.menuEl.classList.contains('hidden');
    }

    open() {
        if (!this.menuEl) return;
        this.menuEl.classList.remove('hidden');
        if (this.chevronIcon) this.chevronIcon.classList.add('rotate-180');
        if (this.inputEl.value && this.inputEl.value !== this.selectedText) {
            this.filter(this.inputEl.value);
        } else {
            this.filter('');
        }
        this.scrollToSelected();
    }

    close() {
        if (!this.menuEl) return;
        this.menuEl.classList.add('hidden');
        if (this.chevronIcon) this.chevronIcon.classList.remove('rotate-180');
        this.highlightedIndex = -1;
        this.inputEl.value = this.selectedText || '';
    }

    setItems(items, preselectedValue = null) {
        this.items = (items || []).map(b => {
            if (b.name !== undefined && b.id !== undefined && b.details !== undefined) {
                return {
                    id: String(b.id),
                    name: String(b.name),
                    details: b.details || [],
                    rightText: b.rightText || '',
                    isOutOfStock: !!b.isOutOfStock,
                    searchStr: (b.searchStr || `${b.name} ${(b.details || []).join(' ')}`).toLowerCase()
                };
            }
            if (b.nama_barang) {
                const isAlat = String(b.jenis || 'alat').toLowerCase() === 'alat';
                let details = [isAlat ? 'Alat' : 'Bahan'];
                if (b.merek && b.merek !== '-') details.push(b.merek);
                if (b.barcode) details.push('#' + b.barcode);
                const stokVal = Number(b.stok_tersedia ?? b.stok_total ?? 0);
                return {
                    id: String(b.id),
                    name: b.nama_barang,
                    details: details,
                    rightText: stokVal > 0 ? `${stokVal.toLocaleString('en-US')} ${b.satuan || 'Unit'}` : 'Habis',
                    isOutOfStock: stokVal <= 0,
                    searchStr: `${b.nama_barang} ${b.merek || ''} ${b.barcode || ''} ${b.jenis || ''}`.toLowerCase()
                };
            }
            if (b.nama_guru) {
                let details = [];
                if (b.nama_jurusan) details.push(b.nama_jurusan);
                else if (b.mengajar) details.push(b.mengajar === 'umum' ? 'Guru Umum' : b.mengajar);
                return {
                    id: String(b.nama_guru),
                    name: b.nama_guru,
                    details: details,
                    rightText: '',
                    isOutOfStock: false,
                    searchStr: `${b.nama_guru} ${b.nama_jurusan || ''} ${b.mengajar || ''}`.toLowerCase()
                };
            }
            if (b.nama_siswa || b.nama_lengkap) {
                const sName = b.nama_lengkap || b.nama_siswa;
                let details = [];
                if (b.nisn) details.push('NISN: ' + b.nisn);
                if (b.kelas) details.push(b.kelas);
                if (b.nama_jurusan) details.push(b.nama_jurusan);
                return {
                    id: String(sName),
                    name: sName,
                    details: details,
                    rightText: b.kelas || '',
                    isOutOfStock: false,
                    searchStr: `${sName} ${b.nisn || ''} ${b.kelas || ''} ${b.nama_jurusan || ''}`.toLowerCase()
                };
            }
            return {
                id: String(b.id || b.value || ''),
                name: String(b.name || b.label || b.id || ''),
                details: b.details || [],
                rightText: b.rightText || '',
                isOutOfStock: false,
                searchStr: String(b.name || b.label || b.id || '').toLowerCase()
            };
        });

        let target = preselectedValue !== null ? String(preselectedValue) : this.selectedValue;
        const found = this.items.find(i => i.id === target || (target && i.name.toLowerCase() === target.toLowerCase()));
        if (found) {
            this.select(found, false);
        } else if (target) {
            this.selectedValue = target;
            this.selectedText = target;
            this.inputEl.value = target;
            if (this.clearBtn) this.clearBtn.classList.remove('hidden');
        } else {
            this.clear(false);
        }
    }

    filter(query) {
        const q = (query || '').trim().toLowerCase();
        if (!q) {
            this.filteredItems = [...this.items];
        } else {
            this.filteredItems = this.items.filter(item => {
                return (item.searchStr || item.name.toLowerCase()).includes(q);
            });
        }
        this.highlightedIndex = -1;
        this.renderMenu();
    }

    renderMenu() {
        if (!this.menuEl) return;
        if (this.filteredItems.length === 0) {
            this.menuEl.innerHTML = `
                <div class="py-5 px-3 text-center text-slate-400 dark:text-slate-500">
                    <svg class="w-5 h-5 mx-auto mb-1.5 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <p class="font-medium text-xs">${escapeHtml(this.emptyText)}</p>
                </div>
            `;
            return;
        }

        let html = '';
        this.filteredItems.forEach((item, idx) => {
            const isSelected = item.id === this.selectedValue || (this.selectedValue && item.name === this.selectedValue);
            const isHighlighted = idx === this.highlightedIndex;

            let metaDetails = (item.details || []).map(d => {
                if (typeof d === 'string' && d.startsWith('#')) {
                    return `<span class="font-mono text-[10.5px] text-slate-400">${escapeHtml(d)}</span>`;
                }
                return `<span class="font-medium text-slate-500 dark:text-slate-400">${escapeHtml(d)}</span>`;
            });

            html += `
                <div data-id="${escapeHtml(item.id)}" data-idx="${idx}" class="combobox-item px-3.5 py-2 cursor-pointer flex items-center justify-between gap-3 transition-colors ${
                    isSelected ? 'bg-sage-50/90 dark:bg-slate-700/80' : (isHighlighted ? 'bg-slate-100/80 dark:bg-slate-700/40' : 'hover:bg-slate-50 dark:hover:bg-slate-700/40')
                }">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-4 h-4 shrink-0 flex items-center justify-center">
                            ${isSelected ? '<svg class="w-4 h-4 text-sage-600 dark:text-sage-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>' : ''}
                        </div>
                        <div class="min-w-0">
                            <div class="font-semibold text-xs text-slate-800 dark:text-slate-100 truncate ${isSelected ? 'font-bold text-sage-700 dark:text-sage-300' : ''}">
                                ${escapeHtml(item.name)}
                            </div>
                            ${metaDetails.length > 0 ? `<div class="flex items-center gap-1.5 mt-0.5 text-[11px] text-slate-400 dark:text-slate-400 truncate">${metaDetails.join('<span class="text-slate-300 dark:text-slate-600">•</span>')}</div>` : ''}
                        </div>
                    </div>
                    ${item.rightText ? `
                    <div class="shrink-0 text-right">
                        <span class="text-xs ${item.isOutOfStock ? 'text-rose-500 dark:text-rose-400 font-semibold' : 'text-slate-500 dark:text-slate-400 font-medium'}">${escapeHtml(item.rightText)}</span>
                    </div>` : ''}
                </div>
            `;
        });

        this.menuEl.innerHTML = html;

        this.menuEl.querySelectorAll('.combobox-item').forEach(el => {
            el.addEventListener('click', (e) => {
                e.stopPropagation();
                const id = el.getAttribute('data-id');
                const item = this.items.find(i => String(i.id) === String(id));
                if (item) {
                    this.select(item);
                }
            });
        });
    }

    moveHighlight(direction) {
        if (this.filteredItems.length === 0) return;
        this.highlightedIndex += direction;
        if (this.highlightedIndex < 0) this.highlightedIndex = this.filteredItems.length - 1;
        if (this.highlightedIndex >= this.filteredItems.length) this.highlightedIndex = 0;
        this.renderMenu();
        const highlightedEl = this.menuEl.querySelector(`[data-idx="${this.highlightedIndex}"]`);
        if (highlightedEl) {
            highlightedEl.scrollIntoView({ block: 'nearest' });
        }
    }

    scrollToSelected() {
        if (!this.selectedValue) return;
        const selectedEl = this.menuEl.querySelector(`[data-id="${this.selectedValue}"]`);
        if (selectedEl) {
            selectedEl.scrollIntoView({ block: 'nearest' });
        }
    }

    select(item, triggerEvent = true) {
        if (!item) {
            this.clear(triggerEvent);
            return;
        }
        this.selectedValue = item.id;
        this.selectedText = item.name;
        this.inputEl.value = item.name;
        if (this.selectEl) {
            this.selectEl.value = item.id;
            if (triggerEvent) {
                this.selectEl.dispatchEvent(new Event('change', { bubbles: true }));
            }
        }
        if (this.clearBtn) this.clearBtn.classList.remove('hidden');
        this.close();
        if (this.onSelect) this.onSelect(item);
    }

    setValue(id) {
        if (!id) {
            this.clear(false);
            return;
        }
        const item = this.items.find(i => String(i.id) === String(id));
        if (item) {
            this.select(item, false);
        } else {
            this.selectedValue = String(id);
            this.selectedText = String(id);
            this.inputEl.value = String(id);
            if (this.clearBtn) this.clearBtn.classList.remove('hidden');
        }
    }

    syncFromSelect() {
        if (!this.selectEl) return;
        this.setValue(this.selectEl.value);
    }

    clear(triggerEvent = true) {
        this.selectedValue = '';
        this.selectedText = '';
        this.inputEl.value = '';
        if (this.selectEl) {
            this.selectEl.value = '';
            if (triggerEvent) {
                this.selectEl.dispatchEvent(new Event('change', { bubbles: true }));
            }
        }
        if (this.clearBtn) this.clearBtn.classList.add('hidden');
        this.close();
        if (this.onSelect) this.onSelect(null);
    }
}

let pinjamCombobox = null;
let pinjamGuruCombobox = null;
let pinjamSiswaCombobox = null;
let masukCombobox = null;
let keluarCombobox = null;

function initSearchableComboboxes() {
    if (!pinjamCombobox && document.getElementById('pinjam_barang_search')) {
        pinjamCombobox = new SearchableSelect({
            wrapper: 'wrap_pinjam_barang',
            select: 'pinjam_barang_id',
            input: 'pinjam_barang_search',
            menu: 'pinjam_barang_menu',
            clearBtn: 'pinjam_barang_clear',
            toggleBtn: 'pinjam_barang_chevron',
            chevronIcon: 'pinjam_barang_chevron_icon',
            emptyText: 'Alat tidak ditemukan',
            placeholder: 'Ketik untuk mencari Alat...'
        });
        window.pinjamCombobox = pinjamCombobox;
    }

    if (!pinjamGuruCombobox && document.getElementById('pinjam_guru_search')) {
        pinjamGuruCombobox = new SearchableSelect({
            wrapper: 'wrap_pinjam_guru',
            select: 'pinjam_guru_peminjam_select',
            input: 'pinjam_guru_search',
            menu: 'pinjam_guru_menu',
            clearBtn: 'pinjam_guru_clear',
            toggleBtn: 'pinjam_guru_chevron',
            chevronIcon: 'pinjam_guru_chevron_icon',
            emptyText: 'Guru tidak ditemukan',
            placeholder: 'Ketik untuk mencari Guru Peminjam...'
        });
        window.pinjamGuruCombobox = pinjamGuruCombobox;
    }

    if (!pinjamSiswaCombobox && document.getElementById('pinjam_siswa_search')) {
        pinjamSiswaCombobox = new SearchableSelect({
            wrapper: 'wrap_pinjam_siswa',
            select: 'pinjam_peminjam_select',
            input: 'pinjam_siswa_search',
            menu: 'pinjam_siswa_menu',
            clearBtn: 'pinjam_siswa_clear',
            toggleBtn: 'pinjam_siswa_chevron',
            chevronIcon: 'pinjam_siswa_chevron_icon',
            emptyText: 'Siswa tidak ditemukan',
            placeholder: 'Ketik untuk mencari Siswa Peminjam...'
        });
        window.pinjamSiswaCombobox = pinjamSiswaCombobox;
    }

    if (!masukCombobox && document.getElementById('masuk_barang_search')) {
        masukCombobox = new SearchableSelect({
            wrapper: 'wrap_masuk_barang',
            select: 'masuk_barang_id',
            input: 'masuk_barang_search',
            menu: 'masuk_barang_menu',
            clearBtn: 'masuk_barang_clear',
            toggleBtn: 'masuk_barang_chevron',
            chevronIcon: 'masuk_barang_chevron_icon',
            emptyText: 'Alat dan Bahan tidak ditemukan',
            placeholder: 'Ketik untuk mencari Alat dan Bahan masuk...'
        });
        window.masukCombobox = masukCombobox;
    }

    if (!keluarCombobox && document.getElementById('keluar_barang_search')) {
        keluarCombobox = new SearchableSelect({
            wrapper: 'wrap_keluar_barang',
            select: 'keluar_barang_id',
            input: 'keluar_barang_search',
            menu: 'keluar_barang_menu',
            clearBtn: 'keluar_barang_clear',
            toggleBtn: 'keluar_barang_chevron',
            chevronIcon: 'keluar_barang_chevron_icon',
            emptyText: 'Bahan tidak ditemukan',
            placeholder: 'Ketik untuk mencari Bahan keluar...'
        });
        window.keluarCombobox = keluarCombobox;
    }
}

// Inisialisasi awal saat dokumen siap
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSearchableComboboxes);
} else {
    initSearchableComboboxes();
}

// Fungsi membuka modal, mendukung pengisian data otomatis untuk mode Edit

function filterBarangSelectByJurusan(targetSelectId, selectedJurusanId) {
    initSearchableComboboxes();
    const el = document.getElementById(targetSelectId);
    if (!el || !window.dbBarang) return;
    let filtered = window.dbBarang;
    if (selectedJurusanId) {
        filtered = window.dbBarang.filter(b => String(b.jurusan_id) === String(selectedJurusanId));
    }
    if (targetSelectId === 'keluar_barang_id') {
        filtered = filtered.filter(b => String(b.jenis || 'alat').toLowerCase() === 'bahan');
    } else if (targetSelectId === 'pinjam_barang_id') {
        filtered = filtered.filter(b => String(b.jenis || 'alat').toLowerCase() === 'alat');
    }
    const defaultLabel = (targetSelectId === 'keluar_barang_id') ? '-- Pilih Bahan --' : (targetSelectId === 'pinjam_barang_id' ? '-- Pilih Alat --' : '-- Pilih Barang --');
    el.innerHTML = `<option value="">${defaultLabel}</option>` +
        filtered.map(b => `<option value="${b.id}">${b.nama_barang} (Tersedia: ${b.stok_tersedia} ${b.satuan || 'Unit'})</option>`).join('');

    if (targetSelectId === 'pinjam_barang_id' && window.pinjamCombobox) {
        window.pinjamCombobox.setItems(filtered, el.value);
    } else if (targetSelectId === 'masuk_barang_id' && window.masukCombobox) {
        window.masukCombobox.setItems(filtered, el.value);
    } else if (targetSelectId === 'keluar_barang_id' && window.keluarCombobox) {
        window.keluarCombobox.setItems(filtered, el.value);
    }
}

function filterBarangMasukOptions(preselectedBarangId = null) {
    initSearchableComboboxes();
    const selectBarang = document.getElementById('masuk_barang_id');
    const selectJenis = document.getElementById('masuk_jenis');
    const selectJur = document.getElementById('masuk_jurusan_id');
    const labelBarang = document.getElementById('masuk_barang_label');

    if (!selectBarang || !window.dbBarang) return;

    const chosenJenis = selectJenis ? selectJenis.value.toLowerCase() : '';
    const isAdminSekolah = window.currentUser && window.currentUser.peran === 'admin_sekolah';
    const chosenJur = (isAdminSekolah && selectJur && selectJur.value) ? selectJur.value : (window.currentUser?.jurusan_id || null);

    let filtered = window.dbBarang;

    if (chosenJur) {
        filtered = filtered.filter(b => String(b.jurusan_id) === String(chosenJur));
    }

    if (chosenJenis === 'alat') {
        filtered = filtered.filter(b => String(b.jenis || 'alat').toLowerCase() === 'alat');
        if (labelBarang) labelBarang.innerHTML = 'Pilih Alat <span class="text-red-500">*</span>';
    } else if (chosenJenis === 'bahan') {
        filtered = filtered.filter(b => String(b.jenis || 'alat').toLowerCase() === 'bahan');
        if (labelBarang) labelBarang.innerHTML = 'Pilih Bahan <span class="text-red-500">*</span>';
    } else {
        if (labelBarang) labelBarang.innerHTML = 'Pilih Alat / Bahan <span class="text-red-500">*</span>';
    }

    const defaultPrompt = chosenJenis === 'alat' ? '-- Pilih Alat --' : (chosenJenis === 'bahan' ? '-- Pilih Bahan --' : '-- Pilih Alat / Bahan --');

    selectBarang.innerHTML = `<option value="">${defaultPrompt}</option>` +
        filtered.map(b => `<option value="${b.id}">${b.nama_barang} (Tersedia: ${b.stok_tersedia} ${b.satuan || 'Unit'})</option>`).join('');

    const targetVal = preselectedBarangId !== null ? preselectedBarangId : selectBarang.value;
    if (targetVal && filtered.some(b => String(b.id) === String(targetVal))) {
        selectBarang.value = targetVal;
    } else {
        selectBarang.value = '';
    }

    if (window.masukCombobox) {
        window.masukCombobox.setItems(filtered, targetVal);
    }
}

// --- HELPER SINKRONISASI TANGGAL OTOMATIS & MANUAL ---
function getLocalDateString(d = new Date()) {
    const year = d.getFullYear();
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

function openNativeDatePicker(el) {
    if (typeof el === 'string') el = document.getElementById(el);
    if (!el || el.disabled) return;
    if (typeof el.showPicker === 'function') {
        try {
            el.showPicker();
            return;
        } catch (e) {
            console.warn('showPicker error:', e);
        }
    }
    el.focus();
}

function triggerDatePick(inputId) {
    const input = document.getElementById(inputId);
    if (!input) return;
    const isMasuk = inputId.includes('masuk');
    const autoChk = document.getElementById(isMasuk ? 'masuk_tgl_auto' : 'keluar_tgl_auto');
    if (autoChk && autoChk.checked) {
        if (isMasuk) toggleAutoDateMasuk(false);
        else toggleAutoDateKeluar(false);
    } else {
        openNativeDatePicker(input);
    }
}

function toggleAutoDateMasuk(isAuto) {
    const chk = document.getElementById('masuk_tgl_auto');
    const input = document.getElementById('masuk_tanggal');
    if (!input) return;
    if (chk) chk.checked = isAuto;
    if (isAuto) {
        input.value = getLocalDateString();
        input.disabled = true;
        input.classList.add('bg-slate-100', 'dark:bg-slate-800/60', 'cursor-not-allowed', 'opacity-75');
        input.classList.remove('bg-sage-50/50', 'dark:bg-slate-800', 'cursor-pointer', 'opacity-100');
    } else {
        input.disabled = false;
        input.classList.remove('bg-slate-100', 'dark:bg-slate-800/60', 'cursor-not-allowed', 'opacity-75');
        input.classList.add('bg-sage-50/50', 'dark:bg-slate-800', 'cursor-pointer', 'opacity-100');
        setTimeout(() => {
            openNativeDatePicker(input);
        }, 50);
    }
}

function toggleAutoDateKeluar(isAuto) {
    const chk = document.getElementById('keluar_tgl_auto');
    const input = document.getElementById('keluar_tanggal');
    if (!input) return;
    if (chk) chk.checked = isAuto;
    if (isAuto) {
        input.value = getLocalDateString();
        input.disabled = true;
        input.classList.add('bg-slate-100', 'dark:bg-slate-800/60', 'cursor-not-allowed', 'opacity-75');
        input.classList.remove('bg-sage-50/50', 'dark:bg-slate-800', 'cursor-pointer', 'opacity-100');
    } else {
        input.disabled = false;
        input.classList.remove('bg-slate-100', 'dark:bg-slate-800/60', 'cursor-not-allowed', 'opacity-75');
        input.classList.add('bg-sage-50/50', 'dark:bg-slate-800', 'cursor-pointer', 'opacity-100');
        setTimeout(() => {
            openNativeDatePicker(input);
        }, 50);
    }
}

// --- SCANNER BARCODE & QR CODE UNTUK ALAT & BAHAN MASUK ---
let masukHtml5QrCode = null;
let masukIsCameraRunning = false;
let masukActiveScanMode = 'kamera';

function toggleMasukBarcodeScanner(checked) {
    const section = document.getElementById('section_scan_barcode_masuk');
    const wrapJurusan = document.getElementById('wrap_masuk_jurusan');
    const wrapJenis = document.getElementById('wrap_masuk_jenis');
    const wrapBarang = document.getElementById('wrap_masuk_barang');
    const selectBarang = document.getElementById('masuk_barang_id');

    if (wrapJurusan) wrapJurusan.classList.toggle('hidden', checked);
    if (wrapJenis) wrapJenis.classList.toggle('hidden', checked);
    if (wrapBarang) wrapBarang.classList.toggle('hidden', checked);

    if (selectBarang) {
        if (checked) {
            selectBarang.removeAttribute('required');
        } else {
            selectBarang.setAttribute('required', 'required');
        }
    }

    if (checked) {
        if (section) section.classList.remove('hidden');
        switchMasukScanMode(masukActiveScanMode || 'kamera');
    } else {
        if (section) section.classList.add('hidden');
        stopMasukCameraStream();
        clearMasukScanFeedback();
    }
}

function switchMasukScanMode(mode) {
    masukActiveScanMode = mode;
    const btnKamera = document.getElementById('btn_mode_kamera_masuk');
    const btnFile = document.getElementById('btn_mode_file_masuk');
    const paneKamera = document.getElementById('masuk_scan_camera_pane');
    const paneFile = document.getElementById('masuk_scan_file_pane');

    if (mode === 'kamera') {
        if (btnKamera) {
            btnKamera.className = 'px-3 py-1 rounded-lg font-bold transition-all bg-sage-600 text-white shadow-sm';
        }
        if (btnFile) {
            btnFile.className = 'px-3 py-1 rounded-lg font-semibold text-slate-600 dark:text-slate-300 hover:text-sage-600 dark:hover:text-sage-400 transition-all';
        }
        if (paneKamera) paneKamera.classList.remove('hidden');
        if (paneFile) paneFile.classList.add('hidden');
    } else {
        if (btnFile) {
            btnFile.className = 'px-3 py-1 rounded-lg font-bold transition-all bg-sage-600 text-white shadow-sm';
        }
        if (btnKamera) {
            btnKamera.className = 'px-3 py-1 rounded-lg font-semibold text-slate-600 dark:text-slate-300 hover:text-sage-600 dark:hover:text-sage-400 transition-all';
        }
        if (paneFile) paneFile.classList.remove('hidden');
        if (paneKamera) paneKamera.classList.add('hidden');
        stopMasukCameraStream();
    }
}

async function toggleMasukCameraStream() {
    if (masukIsCameraRunning) {
        await stopMasukCameraStream();
    } else {
        await startMasukCameraStream();
    }
}

async function startMasukCameraStream(preferDeviceId = null) {
    if (typeof Html5Qrcode === 'undefined') {
        showToast('Library pemindai barcode sedang disiapkan, silakan coba sesaat lagi.', 'warning');
        return;
    }

    try {
        if (!masukHtml5QrCode) {
            masukHtml5QrCode = new Html5Qrcode("masuk_barcode_reader");
        }

        const placeholder = document.getElementById('masuk_camera_placeholder');
        const btnToggle = document.getElementById('btn_toggle_camera_masuk');
        const cameraSelectWrap = document.getElementById('masuk_camera_select_wrap');
        const cameraSelect = document.getElementById('masuk_camera_select');

        // Deteksi daftar kamera yang tersedia
        try {
            const cameras = await Html5Qrcode.getCameras();
            if (cameras && cameras.length > 1 && cameraSelect && cameraSelectWrap) {
                cameraSelectWrap.classList.remove('hidden');
                cameraSelect.innerHTML = cameras.map((c, i) => `<option value="${c.id}">${c.label || 'Kamera ' + (i+1)}</option>`).join('');
                if (preferDeviceId) cameraSelect.value = preferDeviceId;
            }
        } catch (e) {
            console.warn('Gagal membaca daftar kamera:', e);
        }

        const selectedDeviceId = (cameraSelect && cameraSelect.value) ? cameraSelect.value : preferDeviceId;
        const cameraConfig = selectedDeviceId ? { exact: selectedDeviceId } : { facingMode: "environment" };

        const config = {
            fps: 15,
            qrbox: (w, h) => ({
                width: Math.min(Math.floor(w * 0.8), 280),
                height: Math.min(Math.floor(h * 0.8), 280)
            }),
            aspectRatio: 1.333
        };

        await masukHtml5QrCode.start(
            cameraConfig,
            config,
            (decodedText, decodedResult) => {
                onMasukBarcodeScanned(decodedText);
            },
            (errorMessage) => {
                // scanning frame error ignored
            }
        );

        masukIsCameraRunning = true;
        const cameraWrap = document.getElementById('masuk_camera_view_wrap');
        if (cameraWrap) cameraWrap.classList.remove('hidden');
        if (placeholder) placeholder.classList.add('hidden');
        if (btnToggle) {
            btnToggle.className = 'px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl text-xs flex items-center gap-2 shadow-none hover:shadow-none transition-colors cursor-pointer';
            btnToggle.style.boxShadow = 'none';
            btnToggle.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg><span>Hentikan Kamera</span>`;
        }
    } catch (err) {
        console.error('Camera start error:', err);
        masukIsCameraRunning = false;
        showToast('Tidak dapat mengakses kamera: ' + (err.message || err), 'error');
    }
}

async function stopMasukCameraStream() {
    if (masukHtml5QrCode && masukIsCameraRunning) {
        try {
            await masukHtml5QrCode.stop();
        } catch (e) {
            console.warn('Error saat menghentikan kamera:', e);
        }
        masukIsCameraRunning = false;
    }
    const placeholder = document.getElementById('masuk_camera_placeholder');
    const cameraWrap = document.getElementById('masuk_camera_view_wrap');
    const btnToggle = document.getElementById('btn_toggle_camera_masuk');
    if (cameraWrap) cameraWrap.classList.add('hidden');
    if (placeholder) placeholder.classList.remove('hidden');
    if (btnToggle) {
        btnToggle.className = 'px-5 py-2.5 bg-sage-600 hover:bg-sage-700 text-white font-bold rounded-xl text-xs flex items-center gap-2 shadow-none hover:shadow-none transition-colors cursor-pointer';
        btnToggle.style.boxShadow = 'none';
        btnToggle.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span>Nyalakan Kamera</span>`;
    }
}

async function changeMasukCamera(deviceId) {
    if (masukIsCameraRunning) {
        await stopMasukCameraStream();
        await startMasukCameraStream(deviceId);
    }
}

async function handleMasukBarcodeFileUpload(input) {
    if (!input || !input.files || input.files.length === 0) return;
    const file = input.files[0];
    const statusEl = document.getElementById('masuk_file_scan_status');
    if (statusEl) statusEl.classList.remove('hidden');

    try {
        if (typeof Html5Qrcode === 'undefined') {
            throw new Error('Library pemindai belum siap.');
        }

        const fileScanner = new Html5Qrcode("masuk_barcode_reader");
        const decodedText = await fileScanner.scanFile(file, true);
        if (statusEl) statusEl.classList.add('hidden');
        await onMasukBarcodeScanned(decodedText);
    } catch (err) {
        if (statusEl) statusEl.classList.add('hidden');
        console.warn('File scan error:', err);
        showMasukScanFeedback(false, null, 'Tidak ditemukan barcode atau QR code pada gambar ini. Pastikan gambar jelas, tajam, dan tidak buram.');
    } finally {
        input.value = '';
    }
}

async function onMasukBarcodeScanned(code) {
    if (!code) return;
    const cleanCode = String(code).trim();
    if (!cleanCode) return;
    const extractedCode = extractBarcodeValue(cleanCode);

    let found = (window.dbBarang || []).find(b => {
        const matchExtracted = (
            (b.id && String(b.id).trim().toLowerCase() === extractedCode.toLowerCase()) ||
            (b.barcode && String(b.barcode).trim().toLowerCase() === extractedCode.toLowerCase()) ||
            (b.kode_barang && String(b.kode_barang).trim().toLowerCase() === extractedCode.toLowerCase())
        );
        const matchClean = (
            (b.id && String(b.id).trim().toLowerCase() === cleanCode.toLowerCase()) ||
            (b.barcode && String(b.barcode).trim().toLowerCase() === cleanCode.toLowerCase()) ||
            (b.kode_barang && String(b.kode_barang).trim().toLowerCase() === cleanCode.toLowerCase())
        );
        return matchExtracted || matchClean;
    });

    if (!found) {
        try {
            const queryParam = encodeURIComponent(extractedCode || cleanCode);
            const resp = await fetch(`api/scan.php?code=${queryParam}&barang_id=${queryParam}`);
            const json = await resp.json();
            if (json && json.success && json.data) {
                found = json.data;
            }
        } catch (e) {
            console.warn('Gagal memanggil fallback scan API:', e);
        }
    }

    if (found) {
        const userJurusanId = (window.currentUser && window.currentUser.jurusan_id) ? String(window.currentUser.jurusan_id).trim() : '';
        const userPeran = (window.currentUser && window.currentUser.peran) ? String(window.currentUser.peran).toLowerCase().trim() : '';
        const itemJurusanId = (found.jurusan_id) ? String(found.jurusan_id).trim() : '';

        let itemJurusanName = found.nama_jurusan || '';
        if (!itemJurusanName && window.dbJurusan && itemJurusanId) {
            const jMatch = window.dbJurusan.find(j => String(j.id).trim() === itemJurusanId);
            if (jMatch) itemJurusanName = jMatch.nama_jurusan;
        }
        if (!itemJurusanName) itemJurusanName = 'Jurusan Lain';

        let userJurusanName = (window.currentUser && (window.currentUser.nama_jurusan || window.currentUser.kode_jurusan)) ? (window.currentUser.nama_jurusan || window.currentUser.kode_jurusan) : 'Jurusan Anda';

        if (userPeran !== 'admin_sekolah' && userJurusanId && itemJurusanId && userJurusanId !== itemJurusanId) {
            await stopMasukCameraStream();
            const selectBarang = document.getElementById('masuk_barang_id');
            if (selectBarang) selectBarang.value = '';
            if (window.masukCombobox) window.masukCombobox.clear(false);

            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.type = 'sawtooth';
                osc.frequency.value = 180;
                gain.gain.value = 0.35;
                osc.start();
                setTimeout(() => { osc.stop(); audioCtx.close(); }, 350);
            } catch (e) {}

            showMasukScanFeedback(false, null, `Pemindaian Ditolak! Barang "${found.nama_barang || 'Barang'}" terdaftar pada ${itemJurusanName}. Akun Anda terdaftar di ${userJurusanName}, sehingga hanya dapat memindai alat & bahan milik ${userJurusanName}.`, true);
            showToast(`Pemindaian Ditolak! Barang ini milik ${itemJurusanName}`, 'error');
            return;
        }

        try {
            const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.frequency.value = 880;
            gain.gain.value = 0.2;
            osc.start();
            setTimeout(() => { osc.stop(); audioCtx.close(); }, 120);
        } catch (e) {}

        const selectJur = document.getElementById('masuk_jurusan_id');
        const selectJenis = document.getElementById('masuk_jenis');
        const selectBarang = document.getElementById('masuk_barang_id');

        if (selectJur && found.jurusan_id) {
            selectJur.value = found.jurusan_id;
        }

        if (selectJenis && found.jenis) {
            selectJenis.value = found.jenis.toLowerCase();
        }

        filterBarangMasukOptions(found.id);
        if (selectBarang) {
            let optExists = Array.from(selectBarang.options).some(o => String(o.value) === String(found.id));
            if (!optExists) {
                const newOpt = document.createElement('option');
                newOpt.value = found.id;
                newOpt.textContent = `${found.nama_barang} (Tersedia: ${found.stok_tersedia ?? 0} ${found.satuan || 'Unit'})`;
                selectBarang.appendChild(newOpt);
            }
            selectBarang.value = found.id;
            if (window.masukCombobox) {
                window.masukCombobox.setValue(found.id);
            }
            const searchInp = document.getElementById('masuk_barang_search');
            if (searchInp) {
                searchInp.classList.add('ring-2', 'ring-emerald-500');
                setTimeout(() => searchInp.classList.remove('ring-2', 'ring-emerald-500'), 2500);
            }
        }

        await stopMasukCameraStream();
        showMasukScanFeedback(true, found, extractedCode || cleanCode);
        showToast('Barang berhasil disinkronkan: ' + (found.nama_barang || 'Item'), 'success');
    } else {
        showMasukScanFeedback(false, null, 'Barcode "' + (extractedCode || cleanCode) + '" terdeteksi, namun data barang tidak ditemukan dalam inventaris.');
        showToast('Barang tidak ditemukan untuk barcode: ' + (extractedCode || cleanCode), 'warning');
    }
}

function showMasukScanFeedback(isSuccess, item, messageOrCode, isReject = false) {
    const box = document.getElementById('masuk_scan_feedback');
    if (!box) return;
    box.classList.remove('hidden');

    const safeEsc = (str) => {
        if (typeof escapeHtml === 'function') return escapeHtml(str);
        if (str === null || str === undefined) return '';
        const d = document.createElement('div');
        d.textContent = String(str);
        return d.innerHTML;
    };

    if (isSuccess && item) {
        const jenisLabel = (item.jenis || 'alat').toLowerCase() === 'alat' ? 'Alat' : 'Bahan';
        box.className = 'p-3.5 bg-sage-50/80 dark:bg-slate-900 border border-sage-300 dark:border-slate-700 rounded-2xl text-xs space-y-1.5 animate-fade-in-up';
        box.innerHTML = `
            <div class="flex items-center justify-between text-sage-800 dark:text-sage-300 font-bold">
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-sage-600 dark:text-sage-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Barcode Berhasil Terdeteksi & Tersinkronkan!
                </span>
            </div>
            <div class="text-slate-700 dark:text-slate-200">
                <div class="font-extrabold text-sm text-slate-800 dark:text-white">${safeEsc(item.nama_barang || '')}</div>
                <div class="flex flex-wrap gap-x-3 gap-y-1 text-[11px] text-slate-600 dark:text-slate-400 mt-1">
                    <span>Jenis: <b class="capitalize text-sage-700 dark:text-sage-300">${safeEsc(jenisLabel)}</b></span>
                    ${item.nama_jurusan ? `<span>Jurusan: <b>${safeEsc(item.nama_jurusan)}</b></span>` : ''}
                    <span>Kode / Barcode: <code class="font-mono bg-white dark:bg-slate-800 px-1.5 py-0.5 rounded border border-sage-200 dark:border-slate-700 font-bold text-sage-700 dark:text-sage-300">${safeEsc(item.barcode || item.kode_barang || messageOrCode)}</code></span>
                    <span>Stok Saat Ini: <b class="text-sage-700 dark:text-sage-400">${item.stok_tersedia ?? 0} ${safeEsc(item.satuan || 'Unit')}</b></span>
                </div>
            </div>
        `;
    } else if (isReject) {
        box.className = 'p-3.5 bg-red-50/90 dark:bg-red-950/40 border border-red-300 dark:border-red-900/60 rounded-2xl text-xs space-y-1.5 animate-fade-in-up';
        box.innerHTML = `
            <div class="flex items-center justify-between text-red-700 dark:text-red-400 font-bold">
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-red-600 dark:text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                    Pemindaian Ditolak (Bukan Jurusan Anda)
                </span>
            </div>
            <p class="text-slate-700 dark:text-slate-200 text-xs leading-relaxed mt-1">${safeEsc(messageOrCode)}</p>
        `;
    } else {
        box.className = 'p-3.5 bg-amber-50/80 dark:bg-slate-900 border border-amber-300 dark:border-slate-700 rounded-2xl text-xs space-y-1 animate-fade-in-up';
        box.innerHTML = `
            <div class="flex items-center justify-between text-amber-800 dark:text-amber-300 font-bold">
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Peringatan Barcode
                </span>
                <button type="button" onclick="clearMasukScanFeedback(); startMasukCameraStream();" class="text-[11px] px-2 py-0.5 bg-amber-600 text-white rounded-lg hover:bg-amber-700 font-semibold shadow-xs">Coba Lagi</button>
            </div>
            <p class="text-slate-700 dark:text-slate-300">${safeEsc(messageOrCode)}</p>
        `;
    }
}

function clearMasukScanFeedback() {
    const box = document.getElementById('masuk_scan_feedback');
    if (box) {
        box.classList.add('hidden');
        box.innerHTML = '';
    }
}

// --- SCANNER BARCODE & QR CODE UNTUK PEMINJAMAN ---
let pinjamHtml5QrCode = null;
let pinjamIsCameraRunning = false;
let pinjamActiveScanMode = 'kamera';

function togglePinjamBarcodeScanner(checked) {
    const section = document.getElementById('section_scan_barcode_peminjaman');
    const wrapJurusan = document.getElementById('wrap_pinjam_jurusan');
    const wrapJenis = document.getElementById('wrap_pinjam_jenis');
    const wrapBarang = document.getElementById('wrap_pinjam_barang');
    const selectBarang = document.getElementById('pinjam_barang_id');

    if (wrapJurusan) wrapJurusan.classList.toggle('hidden', checked);
    if (wrapJenis) wrapJenis.classList.toggle('hidden', checked);
    if (wrapBarang) wrapBarang.classList.toggle('hidden', checked);

    if (selectBarang) {
        selectBarang.removeAttribute('required');
    }

    if (checked) {
        if (section) section.classList.remove('hidden');
        switchPinjamScanMode(pinjamActiveScanMode || 'kamera');
    } else {
        if (section) section.classList.add('hidden');
        stopPinjamCameraStream();
        clearPinjamScanFeedback();
    }
}

function switchPinjamScanMode(mode) {
    pinjamActiveScanMode = mode;
    const btnKamera = document.getElementById('btn_mode_kamera');
    const btnFile = document.getElementById('btn_mode_file');
    const paneKamera = document.getElementById('pinjam_scan_camera_pane');
    const paneFile = document.getElementById('pinjam_scan_file_pane');

    if (mode === 'kamera') {
        if (btnKamera) {
            btnKamera.className = 'px-3 py-1 rounded-lg font-bold transition-all bg-sage-600 text-white shadow-sm';
        }
        if (btnFile) {
            btnFile.className = 'px-3 py-1 rounded-lg font-semibold text-slate-600 dark:text-slate-300 hover:text-sage-600 dark:hover:text-sage-400 transition-all';
        }
        if (paneKamera) paneKamera.classList.remove('hidden');
        if (paneFile) paneFile.classList.add('hidden');
    } else {
        if (btnFile) {
            btnFile.className = 'px-3 py-1 rounded-lg font-bold transition-all bg-sage-600 text-white shadow-sm';
        }
        if (btnKamera) {
            btnKamera.className = 'px-3 py-1 rounded-lg font-semibold text-slate-600 dark:text-slate-300 hover:text-sage-600 dark:hover:text-sage-400 transition-all';
        }
        if (paneFile) paneFile.classList.remove('hidden');
        if (paneKamera) paneKamera.classList.add('hidden');
        stopPinjamCameraStream();
    }
}

async function togglePinjamCameraStream() {
    if (pinjamIsCameraRunning) {
        await stopPinjamCameraStream();
    } else {
        await startPinjamCameraStream();
    }
}

async function startPinjamCameraStream(preferDeviceId = null) {
    if (typeof Html5Qrcode === 'undefined') {
        showToast('Library pemindai barcode sedang disiapkan, silakan coba sesaat lagi.', 'warning');
        return;
    }

    try {
        if (!pinjamHtml5QrCode) {
            pinjamHtml5QrCode = new Html5Qrcode("pinjam_barcode_reader");
        }

        const placeholder = document.getElementById('pinjam_camera_placeholder');
        const btnToggle = document.getElementById('btn_toggle_camera');
        const cameraSelectWrap = document.getElementById('pinjam_camera_select_wrap');
        const cameraSelect = document.getElementById('pinjam_camera_select');

        // Deteksi daftar kamera yang tersedia
        try {
            const cameras = await Html5Qrcode.getCameras();
            if (cameras && cameras.length > 1 && cameraSelect && cameraSelectWrap) {
                cameraSelectWrap.classList.remove('hidden');
                cameraSelect.innerHTML = cameras.map((c, i) => `<option value="${c.id}">${c.label || 'Kamera ' + (i+1)}</option>`).join('');
                if (preferDeviceId) cameraSelect.value = preferDeviceId;
            }
        } catch (e) {
            console.warn('Gagal membaca daftar kamera:', e);
        }

        const selectedDeviceId = (cameraSelect && cameraSelect.value) ? cameraSelect.value : preferDeviceId;
        const cameraConfig = selectedDeviceId ? { exact: selectedDeviceId } : { facingMode: "environment" };

        const config = {
            fps: 15,
            qrbox: (w, h) => ({
                width: Math.min(Math.floor(w * 0.8), 280),
                height: Math.min(Math.floor(h * 0.8), 280)
            }),
            aspectRatio: 1.333
        };

        await pinjamHtml5QrCode.start(
            cameraConfig,
            config,
            (decodedText, decodedResult) => {
                onPinjamBarcodeScanned(decodedText);
            },
            (errorMessage) => {
                // scanning frame error ignored
            }
        );

        pinjamIsCameraRunning = true;
        const cameraWrap = document.getElementById('pinjam_camera_view_wrap');
        if (cameraWrap) cameraWrap.classList.remove('hidden');
        if (placeholder) placeholder.classList.add('hidden');
        if (btnToggle) {
            btnToggle.className = 'px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl text-xs flex items-center gap-2 shadow-none hover:shadow-none transition-colors cursor-pointer';
            btnToggle.style.boxShadow = 'none';
            btnToggle.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg><span>Hentikan Kamera</span>`;
        }
    } catch (err) {
        console.error('Camera start error:', err);
        pinjamIsCameraRunning = false;
        showToast('Tidak dapat mengakses kamera: ' + (err.message || err), 'error');
    }
}

async function stopPinjamCameraStream() {
    if (pinjamHtml5QrCode && pinjamIsCameraRunning) {
        try {
            await pinjamHtml5QrCode.stop();
        } catch (e) {
            console.warn('Error saat menghentikan kamera:', e);
        }
        pinjamIsCameraRunning = false;
    }
    const placeholder = document.getElementById('pinjam_camera_placeholder');
    const cameraWrap = document.getElementById('pinjam_camera_view_wrap');
    const btnToggle = document.getElementById('btn_toggle_camera');
    if (cameraWrap) cameraWrap.classList.add('hidden');
    if (placeholder) placeholder.classList.remove('hidden');
    if (btnToggle) {
        btnToggle.className = 'px-5 py-2.5 bg-sage-600 hover:bg-sage-700 text-white font-bold rounded-xl text-xs flex items-center gap-2 shadow-none hover:shadow-none transition-colors cursor-pointer';
        btnToggle.style.boxShadow = 'none';
        btnToggle.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span>Nyalakan Kamera</span>`;
    }
}

async function changePinjamCamera(deviceId) {
    if (pinjamIsCameraRunning) {
        await stopPinjamCameraStream();
        await startPinjamCameraStream(deviceId);
    }
}

async function handlePinjamBarcodeFileUpload(input) {
    if (!input || !input.files || input.files.length === 0) return;
    const file = input.files[0];
    const statusEl = document.getElementById('pinjam_file_scan_status');
    if (statusEl) statusEl.classList.remove('hidden');

    try {
        if (typeof Html5Qrcode === 'undefined') {
            throw new Error('Library pemindai belum siap.');
        }

        const fileScanner = new Html5Qrcode("pinjam_barcode_reader");
        const decodedText = await fileScanner.scanFile(file, true);
        if (statusEl) statusEl.classList.add('hidden');
        await onPinjamBarcodeScanned(decodedText);
    } catch (err) {
        if (statusEl) statusEl.classList.add('hidden');
        console.warn('File scan error:', err);
        showPinjamScanFeedback(false, null, 'Tidak ditemukan barcode atau QR code pada gambar ini. Pastikan gambar jelas, tajam, dan tidak buram.');
    } finally {
        input.value = '';
    }
}

function extractBarcodeValue(code) {
    if (!code) return '';
    let raw = String(code).trim();
    if (!raw) return '';

    // Jika raw berupa format URL atau query string (contoh: http://.../api/scan.php?barang_id=XYZ)
    if (raw.startsWith('http://') || raw.startsWith('https://') || raw.includes('?') || raw.includes('&')) {
        try {
            const parsed = new URL(raw, window.location.origin);
            const pId = parsed.searchParams.get('barang_id') ||
                        parsed.searchParams.get('id') ||
                        parsed.searchParams.get('code') ||
                        parsed.searchParams.get('barcode');
            if (pId) return pId.trim();
        } catch (e) {
            const match = raw.match(/[?&](?:barang_id|id|code|barcode)=([^&#\s]+)/i);
            if (match && match[1]) {
                return decodeURIComponent(match[1]).trim();
            }
        }
    }
    return raw;
}

async function onPinjamBarcodeScanned(code) {
    if (!code) return;
    const cleanCode = String(code).trim();
    if (!cleanCode) return;
    const extractedCode = extractBarcodeValue(cleanCode);

    // Cari di window.dbBarang (cocokkan ID, barcode, maupun kode_barang)
    let found = (window.dbBarang || []).find(b => {
        const matchExtracted = (
            (b.id && String(b.id).trim().toLowerCase() === extractedCode.toLowerCase()) ||
            (b.barcode && String(b.barcode).trim().toLowerCase() === extractedCode.toLowerCase()) ||
            (b.kode_barang && String(b.kode_barang).trim().toLowerCase() === extractedCode.toLowerCase())
        );
        const matchClean = (
            (b.id && String(b.id).trim().toLowerCase() === cleanCode.toLowerCase()) ||
            (b.barcode && String(b.barcode).trim().toLowerCase() === cleanCode.toLowerCase()) ||
            (b.kode_barang && String(b.kode_barang).trim().toLowerCase() === cleanCode.toLowerCase())
        );
        return matchExtracted || matchClean;
    });

    // Jika tidak ditemukan di lokal, cari lewat API scan.php
    if (!found) {
        try {
            const queryParam = encodeURIComponent(extractedCode || cleanCode);
            const resp = await fetch(`api/scan.php?code=${queryParam}&barang_id=${queryParam}`);
            const json = await resp.json();
            if (json && json.success && json.data) {
                found = json.data;
            }
        } catch (e) {
            console.warn('Gagal memanggil fallback scan API:', e);
        }
    }

    if (found) {
        // =========================================================================
        // VALIDASI JURUSAN PENGGUNA:
        // Jika akun login memiliki jurusan tertentu (misal TKJ),
        // maka hanya barcode alat & bahan milik jurusan tersebut yang dapat dipindai.
        // Jika scan barang milik jurusan lain -> DITOLAK!
        // =========================================================================
        const userJurusanId = (window.currentUser && window.currentUser.jurusan_id) ? String(window.currentUser.jurusan_id).trim() : '';
        const userPeran = (window.currentUser && window.currentUser.peran) ? String(window.currentUser.peran).toLowerCase().trim() : '';
        const itemJurusanId = (found.jurusan_id) ? String(found.jurusan_id).trim() : '';

        // Dapatkan nama jurusan barang
        let itemJurusanName = found.nama_jurusan || '';
        if (!itemJurusanName && window.dbJurusan && itemJurusanId) {
            const jMatch = window.dbJurusan.find(j => String(j.id).trim() === itemJurusanId);
            if (jMatch) itemJurusanName = jMatch.nama_jurusan;
        }
        if (!itemJurusanName) itemJurusanName = 'Jurusan Lain';

        // Dapatkan nama jurusan akun pengguna
        let userJurusanName = (window.currentUser && (window.currentUser.nama_jurusan || window.currentUser.kode_jurusan)) ? (window.currentUser.nama_jurusan || window.currentUser.kode_jurusan) : 'Jurusan Anda';

        // Validasi: Jika bukan admin_sekolah dan memiliki jurusan_id, lalu scan barang jurusan lain:
        if (userPeran !== 'admin_sekolah' && userJurusanId && itemJurusanId && userJurusanId !== itemJurusanId) {
            // Hentikan streaming kamera jika aktif
            await stopPinjamCameraStream();

            // Reset pilihan barang pada form
            const selectBarang = document.getElementById('pinjam_barang_id');
            if (selectBarang) selectBarang.value = '';
            if (window.pinjamCombobox) window.pinjamCombobox.clear(false);

            // Mainkan nada peringatan/penolakan
            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.type = 'sawtooth';
                osc.frequency.value = 180;
                gain.gain.value = 0.35;
                osc.start();
                setTimeout(() => { osc.stop(); audioCtx.close(); }, 350);
            } catch (e) {}

            showPinjamScanFeedback(false, null, `Pemindaian Ditolak! Barang "${found.nama_barang || 'Barang'}" terdaftar pada ${itemJurusanName}. Akun Anda terdaftar di ${userJurusanName}, sehingga hanya dapat memindai alat & bahan milik ${userJurusanName}.`, true);
            showToast(`Pemindaian Ditolak! Barang ini milik ${itemJurusanName}`, 'error');
            return;
        }

        // Validasi Jenis Barang: Form peminjaman HANYA untuk kategori ALAT (bukan bahan)
        const itemJenis = String(found.jenis || 'alat').toLowerCase();
        if (itemJenis === 'bahan') {
            await stopPinjamCameraStream();
            const selectBarang = document.getElementById('pinjam_barang_id');
            if (selectBarang) selectBarang.value = '';
            if (window.pinjamCombobox) window.pinjamCombobox.clear(false);

            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.type = 'sawtooth';
                osc.frequency.value = 180;
                gain.gain.value = 0.35;
                osc.start();
                setTimeout(() => { osc.stop(); audioCtx.close(); }, 350);
            } catch (e) {}

            showPinjamScanFeedback(false, null, `Pemindaian Ditolak! Barang "${found.nama_barang || 'Barang'}" berjenis BAHAN. Form peminjaman ini khusus untuk transaksi peminjaman ALAT. Untuk pengeluaran bahan habis pakai, silakan gunakan menu Bahan Keluar.`, true);
            showToast('Pemindaian Ditolak! Barang ini berjenis BAHAN.', 'error');
            return;
        }

        // Bunyikan nada beep indikator sukses HANYA jika valid & diterima
        try {
            const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.frequency.value = 880;
            gain.gain.value = 0.2;
            osc.start();
            setTimeout(() => { osc.stop(); audioCtx.close(); }, 120);
        } catch (e) {}

        // Sinkronisasi otomatis ke form peminjaman!
        const selectJur = document.getElementById('pinjam_jurusan_id');
        const selectJenis = document.getElementById('pinjam_jenis');
        const selectBarang = document.getElementById('pinjam_barang_id');

        if (selectJur && found.jurusan_id) {
            selectJur.value = found.jurusan_id;
        }

        if (selectJenis) {
            selectJenis.value = 'alat';
        }

        filterBarangPinjamOptions(found.id);
        if (selectBarang) {
            let optExists = Array.from(selectBarang.options).some(o => String(o.value) === String(found.id));
            if (!optExists) {
                const newOpt = document.createElement('option');
                newOpt.value = found.id;
                newOpt.textContent = `${found.nama_barang} (Tersedia: ${found.stok_tersedia ?? 0} ${found.satuan || 'Unit'})`;
                selectBarang.appendChild(newOpt);
            }
            selectBarang.value = found.id;
            if (window.pinjamCombobox) {
                window.pinjamCombobox.setValue(found.id);
            }
            const searchInp = document.getElementById('pinjam_barang_search');
            if (searchInp) {
                searchInp.classList.add('ring-2', 'ring-emerald-500');
                setTimeout(() => searchInp.classList.remove('ring-2', 'ring-emerald-500'), 2500);
            }
        }

        // Hentikan streaming kamera setelah barcode berhasil dideteksi
        await stopPinjamCameraStream();

        showPinjamScanFeedback(true, found, extractedCode || cleanCode);
        showToast('Barang berhasil disinkronkan: ' + (found.nama_barang || 'Item'), 'success');
    } else {
        showPinjamScanFeedback(false, null, 'Barcode "' + (extractedCode || cleanCode) + '" terdeteksi, namun data barang tidak ditemukan dalam inventaris.');
        showToast('Barang tidak ditemukan untuk barcode: ' + (extractedCode || cleanCode), 'warning');
    }
}

function showPinjamScanFeedback(isSuccess, item, messageOrCode, isReject = false) {
    const box = document.getElementById('pinjam_scan_feedback');
    if (!box) return;
    box.classList.remove('hidden');

    const safeEsc = (str) => {
        if (typeof escapeHtml === 'function') return escapeHtml(str);
        if (str === null || str === undefined) return '';
        const d = document.createElement('div');
        d.textContent = String(str);
        return d.innerHTML;
    };

    if (isSuccess && item) {
        const jenisLabel = (item.jenis || 'alat').toLowerCase() === 'alat' ? 'Alat' : 'Bahan';
        box.className = 'p-3.5 bg-sage-50/80 dark:bg-slate-900 border border-sage-300 dark:border-slate-700 rounded-2xl text-xs space-y-1.5 animate-fade-in-up';
        box.innerHTML = `
            <div class="flex items-center justify-between text-sage-800 dark:text-sage-300 font-bold">
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-sage-600 dark:text-sage-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Barcode Berhasil Terdeteksi & Tersinkronkan!
                </span>
            </div>
            <div class="text-slate-700 dark:text-slate-200">
                <div class="font-extrabold text-sm text-slate-800 dark:text-white">${safeEsc(item.nama_barang || '')}</div>
                <div class="flex flex-wrap gap-x-3 gap-y-1 text-[11px] text-slate-600 dark:text-slate-400 mt-1">
                    <span>Jenis: <b class="capitalize text-sage-700 dark:text-sage-300">${safeEsc(jenisLabel)}</b></span>
                    ${item.nama_jurusan ? `<span>Jurusan: <b>${safeEsc(item.nama_jurusan)}</b></span>` : ''}
                    <span>Kode / Barcode: <code class="font-mono bg-white dark:bg-slate-800 px-1.5 py-0.5 rounded border border-sage-200 dark:border-slate-700 font-bold text-sage-700 dark:text-sage-300">${safeEsc(item.barcode || item.kode_barang || messageOrCode)}</code></span>
                    <span>Stok Tersedia: <b class="text-sage-700 dark:text-sage-400">${item.stok_tersedia ?? 0} ${safeEsc(item.satuan || 'Unit')}</b></span>
                </div>
            </div>
        `;
    } else if (isReject) {
        box.className = 'p-3.5 bg-red-50/90 dark:bg-red-950/40 border border-red-300 dark:border-red-900/60 rounded-2xl text-xs space-y-1.5 animate-fade-in-up';
        box.innerHTML = `
            <div class="flex items-center justify-between text-red-700 dark:text-red-400 font-bold">
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-red-600 dark:text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                    Pemindaian Ditolak (Bukan Jurusan Anda)
                </span>
            </div>
            <p class="text-slate-700 dark:text-slate-200 text-xs leading-relaxed mt-1">${safeEsc(messageOrCode)}</p>
        `;
    } else {
        box.className = 'p-3.5 bg-amber-50/80 dark:bg-slate-900 border border-amber-300 dark:border-slate-700 rounded-2xl text-xs space-y-1 animate-fade-in-up';
        box.innerHTML = `
            <div class="flex items-center justify-between text-amber-800 dark:text-amber-300 font-bold">
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Peringatan Barcode
                </span>
                <button type="button" onclick="clearPinjamScanFeedback(); startPinjamCameraStream();" class="text-[11px] px-2 py-0.5 bg-amber-600 text-white rounded-lg hover:bg-amber-700 font-semibold shadow-xs">Coba Lagi</button>
            </div>
            <p class="text-slate-700 dark:text-slate-300">${safeEsc(messageOrCode)}</p>
        `;
    }
}

function clearPinjamScanFeedback() {
    const box = document.getElementById('pinjam_scan_feedback');
    if (box) {
        box.classList.add('hidden');
        box.innerHTML = '';
    }
}

function filterBarangKeluarOptions(preselectedBarangId = null) {
    initSearchableComboboxes();
    const el = document.getElementById('keluar_barang_id');
    const selectJur = document.getElementById('keluar_jurusan_id');
    if (!el || !window.dbBarang) return;

    const isAdminSekolah = window.currentUser && window.currentUser.peran === 'admin_sekolah';
    const chosenJur = (isAdminSekolah && selectJur && selectJur.value) ? selectJur.value : (window.currentUser?.jurusan_id || null);

    let filtered = window.dbBarang;
    if (chosenJur) {
        filtered = filtered.filter(b => String(b.jurusan_id) === String(chosenJur));
    }

    // Hanya tampilkan data dengan jenis barang "bahan"
    filtered = filtered.filter(b => String(b.jenis || 'alat').toLowerCase() === 'bahan');

    el.innerHTML = '<option value="">-- Pilih Bahan --</option>' +
        filtered.map(b => `<option value="${b.id}">${b.nama_barang} (Tersedia: ${b.stok_tersedia} ${b.satuan || 'Unit'})</option>`).join('');

    const targetVal = preselectedBarangId !== null ? preselectedBarangId : el.value;
    if (targetVal && filtered.some(b => String(b.id) === String(targetVal))) {
        el.value = targetVal;
    } else {
        el.value = '';
    }

    if (window.keluarCombobox) {
        window.keluarCombobox.setItems(filtered, targetVal);
    }
}

// --- SCANNER BARCODE & QR CODE UNTUK BAHAN KELUAR ---
let keluarHtml5QrCode = null;
let keluarIsCameraRunning = false;
let keluarActiveScanMode = 'kamera';

function toggleKeluarBarcodeScanner(checked) {
    const section = document.getElementById('section_scan_barcode_keluar');
    const wrapJurusan = document.getElementById('wrap_keluar_jurusan');
    const wrapBarang = document.getElementById('wrap_keluar_barang');
    const selectBarang = document.getElementById('keluar_barang_id');

    if (wrapJurusan) wrapJurusan.classList.toggle('hidden', checked);
    if (wrapBarang) wrapBarang.classList.toggle('hidden', checked);

    if (selectBarang) {
        if (checked) {
            selectBarang.removeAttribute('required');
        } else {
            selectBarang.setAttribute('required', 'required');
        }
    }

    if (checked) {
        if (section) section.classList.remove('hidden');
        switchKeluarScanMode(keluarActiveScanMode || 'kamera');
    } else {
        if (section) section.classList.add('hidden');
        stopKeluarCameraStream();
        clearKeluarScanFeedback();
    }
}

function switchKeluarScanMode(mode) {
    keluarActiveScanMode = mode;
    const btnKamera = document.getElementById('btn_mode_kamera_keluar');
    const btnFile = document.getElementById('btn_mode_file_keluar');
    const paneKamera = document.getElementById('keluar_scan_camera_pane');
    const paneFile = document.getElementById('keluar_scan_file_pane');

    if (mode === 'kamera') {
        if (btnKamera) {
            btnKamera.className = 'px-3 py-1 rounded-lg font-bold transition-all bg-sage-600 text-white shadow-sm';
        }
        if (btnFile) {
            btnFile.className = 'px-3 py-1 rounded-lg font-semibold text-slate-600 dark:text-slate-300 hover:text-sage-600 dark:hover:text-sage-400 transition-all';
        }
        if (paneKamera) paneKamera.classList.remove('hidden');
        if (paneFile) paneFile.classList.add('hidden');
    } else {
        if (btnFile) {
            btnFile.className = 'px-3 py-1 rounded-lg font-bold transition-all bg-sage-600 text-white shadow-sm';
        }
        if (btnKamera) {
            btnKamera.className = 'px-3 py-1 rounded-lg font-semibold text-slate-600 dark:text-slate-300 hover:text-sage-600 dark:hover:text-sage-400 transition-all';
        }
        if (paneFile) paneFile.classList.remove('hidden');
        if (paneKamera) paneKamera.classList.add('hidden');
        stopKeluarCameraStream();
    }
}

async function toggleKeluarCameraStream() {
    if (keluarIsCameraRunning) {
        await stopKeluarCameraStream();
    } else {
        await startKeluarCameraStream();
    }
}

async function startKeluarCameraStream(preferDeviceId = null) {
    if (typeof Html5Qrcode === 'undefined') {
        showToast('Library pemindai barcode sedang disiapkan, silakan coba sesaat lagi.', 'warning');
        return;
    }

    try {
        if (!keluarHtml5QrCode) {
            keluarHtml5QrCode = new Html5Qrcode("keluar_barcode_reader");
        }

        const placeholder = document.getElementById('keluar_camera_placeholder');
        const btnToggle = document.getElementById('btn_toggle_camera_keluar');
        const cameraSelectWrap = document.getElementById('keluar_camera_select_wrap');
        const cameraSelect = document.getElementById('keluar_camera_select');

        // Deteksi daftar kamera yang tersedia
        try {
            const cameras = await Html5Qrcode.getCameras();
            if (cameras && cameras.length > 1 && cameraSelect && cameraSelectWrap) {
                cameraSelectWrap.classList.remove('hidden');
                cameraSelect.innerHTML = cameras.map((c, i) => `<option value="${c.id}">${c.label || 'Kamera ' + (i+1)}</option>`).join('');
                if (preferDeviceId) cameraSelect.value = preferDeviceId;
            }
        } catch (e) {
            console.warn('Gagal membaca daftar kamera:', e);
        }

        const selectedDeviceId = (cameraSelect && cameraSelect.value) ? cameraSelect.value : preferDeviceId;
        const cameraConfig = selectedDeviceId ? { exact: selectedDeviceId } : { facingMode: "environment" };

        const config = {
            fps: 15,
            qrbox: (w, h) => ({
                width: Math.min(Math.floor(w * 0.8), 280),
                height: Math.min(Math.floor(h * 0.8), 280)
            }),
            aspectRatio: 1.333
        };

        await keluarHtml5QrCode.start(
            cameraConfig,
            config,
            (decodedText, decodedResult) => {
                onKeluarBarcodeScanned(decodedText);
            },
            (errorMessage) => {
                // scanning frame error ignored
            }
        );

        keluarIsCameraRunning = true;
        const cameraWrap = document.getElementById('keluar_camera_view_wrap');
        if (cameraWrap) cameraWrap.classList.remove('hidden');
        if (placeholder) placeholder.classList.add('hidden');
        if (btnToggle) {
            btnToggle.className = 'px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl text-xs flex items-center gap-2 shadow-none hover:shadow-none transition-colors cursor-pointer';
            btnToggle.style.boxShadow = 'none';
            btnToggle.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg><span>Hentikan Kamera</span>`;
        }
    } catch (err) {
        console.error('Camera start error:', err);
        keluarIsCameraRunning = false;
        showToast('Tidak dapat mengakses kamera: ' + (err.message || err), 'error');
    }
}

async function stopKeluarCameraStream() {
    if (keluarHtml5QrCode && keluarIsCameraRunning) {
        try {
            await keluarHtml5QrCode.stop();
        } catch (e) {
            console.warn('Error saat menghentikan kamera:', e);
        }
        keluarIsCameraRunning = false;
    }
    const placeholder = document.getElementById('keluar_camera_placeholder');
    const cameraWrap = document.getElementById('keluar_camera_view_wrap');
    const btnToggle = document.getElementById('btn_toggle_camera_keluar');
    if (cameraWrap) cameraWrap.classList.add('hidden');
    if (placeholder) placeholder.classList.remove('hidden');
    if (btnToggle) {
        btnToggle.className = 'px-5 py-2.5 bg-sage-600 hover:bg-sage-700 text-white font-bold rounded-xl text-xs flex items-center gap-2 shadow-none hover:shadow-none transition-colors cursor-pointer';
        btnToggle.style.boxShadow = 'none';
        btnToggle.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span>Nyalakan Kamera</span>`;
    }
}

async function changeKeluarCamera(deviceId) {
    if (keluarIsCameraRunning) {
        await stopKeluarCameraStream();
        await startKeluarCameraStream(deviceId);
    }
}

async function handleKeluarBarcodeFileUpload(input) {
    if (!input || !input.files || input.files.length === 0) return;
    const file = input.files[0];
    const statusEl = document.getElementById('keluar_file_scan_status');
    if (statusEl) statusEl.classList.remove('hidden');

    try {
        if (typeof Html5Qrcode === 'undefined') {
            throw new Error('Library pemindai belum siap.');
        }

        const fileScanner = new Html5Qrcode("keluar_barcode_reader");
        const decodedText = await fileScanner.scanFile(file, true);
        if (statusEl) statusEl.classList.add('hidden');
        await onKeluarBarcodeScanned(decodedText);
    } catch (err) {
        if (statusEl) statusEl.classList.add('hidden');
        console.warn('File scan error:', err);
        showKeluarScanFeedback(false, null, 'Tidak ditemukan barcode atau QR code pada gambar ini. Pastikan gambar jelas, tajam, dan tidak buram.');
    } finally {
        input.value = '';
    }
}

async function onKeluarBarcodeScanned(code) {
    if (!code) return;
    const cleanCode = String(code).trim();
    if (!cleanCode) return;
    const extractedCode = extractBarcodeValue(cleanCode);

    let found = (window.dbBarang || []).find(b => {
        const matchExtracted = (
            (b.id && String(b.id).trim().toLowerCase() === extractedCode.toLowerCase()) ||
            (b.barcode && String(b.barcode).trim().toLowerCase() === extractedCode.toLowerCase()) ||
            (b.kode_barang && String(b.kode_barang).trim().toLowerCase() === extractedCode.toLowerCase())
        );
        const matchClean = (
            (b.id && String(b.id).trim().toLowerCase() === cleanCode.toLowerCase()) ||
            (b.barcode && String(b.barcode).trim().toLowerCase() === cleanCode.toLowerCase()) ||
            (b.kode_barang && String(b.kode_barang).trim().toLowerCase() === cleanCode.toLowerCase())
        );
        return matchExtracted || matchClean;
    });

    if (!found) {
        try {
            const queryParam = encodeURIComponent(extractedCode || cleanCode);
            const resp = await fetch(`api/scan.php?code=${queryParam}&barang_id=${queryParam}`);
            const json = await resp.json();
            if (json && json.success && json.data) {
                found = json.data;
            }
        } catch (e) {
            console.warn('Gagal memanggil fallback scan API:', e);
        }
    }

    if (found) {
        const userJurusanId = (window.currentUser && window.currentUser.jurusan_id) ? String(window.currentUser.jurusan_id).trim() : '';
        const userPeran = (window.currentUser && window.currentUser.peran) ? String(window.currentUser.peran).toLowerCase().trim() : '';
        const itemJurusanId = (found.jurusan_id) ? String(found.jurusan_id).trim() : '';

        let itemJurusanName = found.nama_jurusan || '';
        if (!itemJurusanName && window.dbJurusan && itemJurusanId) {
            const jMatch = window.dbJurusan.find(j => String(j.id).trim() === itemJurusanId);
            if (jMatch) itemJurusanName = jMatch.nama_jurusan;
        }
        if (!itemJurusanName) itemJurusanName = 'Jurusan Lain';

        let userJurusanName = (window.currentUser && (window.currentUser.nama_jurusan || window.currentUser.kode_jurusan)) ? (window.currentUser.nama_jurusan || window.currentUser.kode_jurusan) : 'Jurusan Anda';

        if (userPeran !== 'admin_sekolah' && userJurusanId && itemJurusanId && userJurusanId !== itemJurusanId) {
            await stopKeluarCameraStream();
            const selectBarang = document.getElementById('keluar_barang_id');
            if (selectBarang) selectBarang.value = '';
            if (window.keluarCombobox) window.keluarCombobox.clear(false);

            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.type = 'sawtooth';
                osc.frequency.value = 180;
                gain.gain.value = 0.35;
                osc.start();
                setTimeout(() => { osc.stop(); audioCtx.close(); }, 350);
            } catch (e) {}

            showKeluarScanFeedback(false, null, `Pemindaian Ditolak! Barang "${found.nama_barang || 'Barang'}" terdaftar pada ${itemJurusanName}. Akun Anda terdaftar di ${userJurusanName}, sehingga hanya dapat memindai bahan milik ${userJurusanName}.`, true);
            showToast(`Pemindaian Ditolak! Barang ini milik ${itemJurusanName}`, 'error');
            return;
        }

        // VALIDASI KHUSUS BAHAN KELUAR: Jenis barang WAJIB 'bahan'!
        const itemJenis = String(found.jenis || 'alat').toLowerCase();
        if (itemJenis !== 'bahan') {
            await stopKeluarCameraStream();
            const selectBarang = document.getElementById('keluar_barang_id');
            if (selectBarang) selectBarang.value = '';
            if (window.keluarCombobox) window.keluarCombobox.clear(false);

            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.type = 'sawtooth';
                osc.frequency.value = 180;
                gain.gain.value = 0.35;
                osc.start();
                setTimeout(() => { osc.stop(); audioCtx.close(); }, 350);
            } catch (e) {}

            showKeluarScanFeedback(false, null, `Pemindaian Ditolak! Barang "${found.nama_barang || 'Barang'}" berjenis ALAT. Form ini khusus untuk transaksi pengeluaran BAHAN habis pakai. Jika ingin meminjamkan alat ini, gunakan menu Peminjaman Alat.`, true);
            showToast(`Pemindaian Ditolak! "${found.nama_barang}" adalah Alat, bukan Bahan habis pakai.`, 'error');
            return;
        }

        try {
            const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.frequency.value = 880;
            gain.gain.value = 0.2;
            osc.start();
            setTimeout(() => { osc.stop(); audioCtx.close(); }, 120);
        } catch (e) {}

        const selectJur = document.getElementById('keluar_jurusan_id');
        const selectBarang = document.getElementById('keluar_barang_id');

        if (selectJur && found.jurusan_id) {
            selectJur.value = found.jurusan_id;
        }

        filterBarangKeluarOptions(found.id);
        if (selectBarang) {
            let optExists = Array.from(selectBarang.options).some(o => String(o.value) === String(found.id));
            if (!optExists) {
                const newOpt = document.createElement('option');
                newOpt.value = found.id;
                newOpt.textContent = `${found.nama_barang} (Tersedia: ${found.stok_tersedia ?? 0} ${found.satuan || 'Unit'})`;
                selectBarang.appendChild(newOpt);
            }
            selectBarang.value = found.id;
            if (window.keluarCombobox) {
                window.keluarCombobox.setValue(found.id);
            }
            const searchInp = document.getElementById('keluar_barang_search');
            if (searchInp) {
                searchInp.classList.add('ring-2', 'ring-emerald-500');
                setTimeout(() => searchInp.classList.remove('ring-2', 'ring-emerald-500'), 2500);
            }
        }

        await stopKeluarCameraStream();
        showKeluarScanFeedback(true, found, extractedCode || cleanCode);
        showToast('Bahan berhasil disinkronkan: ' + (found.nama_barang || 'Item'), 'success');
    } else {
        showKeluarScanFeedback(false, null, 'Barcode "' + (extractedCode || cleanCode) + '" terdeteksi, namun data barang tidak ditemukan dalam inventaris.');
        showToast('Barang tidak ditemukan untuk barcode: ' + (extractedCode || cleanCode), 'warning');
    }
}

function showKeluarScanFeedback(isSuccess, item, messageOrCode, isReject = false) {
    const box = document.getElementById('keluar_scan_feedback');
    if (!box) return;
    box.classList.remove('hidden');

    const safeEsc = (str) => {
        if (typeof escapeHtml === 'function') return escapeHtml(str);
        if (str === null || str === undefined) return '';
        const d = document.createElement('div');
        d.textContent = String(str);
        return d.innerHTML;
    };

    if (isSuccess && item) {
        box.className = 'p-3.5 bg-sage-50/80 dark:bg-slate-900 border border-sage-300 dark:border-slate-700 rounded-2xl text-xs space-y-1.5 animate-fade-in-up';
        box.innerHTML = `
            <div class="flex items-center justify-between text-sage-800 dark:text-sage-300 font-bold">
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-sage-600 dark:text-sage-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Barcode Berhasil Terdeteksi & Tersinkronkan!
                </span>
            </div>
            <div class="text-slate-700 dark:text-slate-200">
                <div class="font-extrabold text-sm text-slate-800 dark:text-white">${safeEsc(item.nama_barang || '')}</div>
                <div class="flex flex-wrap gap-x-3 gap-y-1 text-[11px] text-slate-600 dark:text-slate-400 mt-1">
                    <span>Jenis: <b class="capitalize text-sage-700 dark:text-sage-300">Bahan</b></span>
                    ${item.nama_jurusan ? `<span>Jurusan: <b>${safeEsc(item.nama_jurusan)}</b></span>` : ''}
                    <span>Kode / Barcode: <code class="font-mono bg-white dark:bg-slate-800 px-1.5 py-0.5 rounded border border-sage-200 dark:border-slate-700 font-bold text-sage-700 dark:text-sage-300">${safeEsc(item.barcode || item.kode_barang || messageOrCode)}</code></span>
                    <span>Stok Tersedia: <b class="text-sage-700 dark:text-sage-400">${item.stok_tersedia ?? 0} ${safeEsc(item.satuan || 'Unit')}</b></span>
                </div>
            </div>
        `;
    } else if (isReject) {
        box.className = 'p-3.5 bg-red-50/90 dark:bg-red-950/40 border border-red-300 dark:border-red-900/60 rounded-2xl text-xs space-y-1.5 animate-fade-in-up';
        box.innerHTML = `
            <div class="flex items-center justify-between text-red-700 dark:text-red-400 font-bold">
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-red-600 dark:text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                    Pemindaian Ditolak
                </span>
            </div>
            <p class="text-slate-700 dark:text-slate-200 text-xs leading-relaxed mt-1">${safeEsc(messageOrCode)}</p>
        `;
    } else {
        box.className = 'p-3.5 bg-amber-50/80 dark:bg-slate-900 border border-amber-300 dark:border-slate-700 rounded-2xl text-xs space-y-1 animate-fade-in-up';
        box.innerHTML = `
            <div class="flex items-center justify-between text-amber-800 dark:text-amber-300 font-bold">
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Peringatan Barcode
                </span>
                <button type="button" onclick="clearKeluarScanFeedback(); startKeluarCameraStream();" class="text-[11px] px-2 py-0.5 bg-amber-600 text-white rounded-lg hover:bg-amber-700 font-semibold shadow-xs">Coba Lagi</button>
            </div>
            <p class="text-slate-700 dark:text-slate-300">${safeEsc(messageOrCode)}</p>
        `;
    }
}

function clearKeluarScanFeedback() {
    const box = document.getElementById('keluar_scan_feedback');
    if (box) {
        box.classList.add('hidden');
        box.innerHTML = '';
    }
}

function filterBarangPinjamOptions(preselectedBarangId = null) {
    initSearchableComboboxes();
    const selectBarang = document.getElementById('pinjam_barang_id');
    const selectJur = document.getElementById('pinjam_jurusan_id');
    const labelBarang = document.getElementById('pinjam_barang_label');

    if (!selectBarang || !window.dbBarang) return;

    const isAdminSekolah = window.currentUser && window.currentUser.peran === 'admin_sekolah';
    const chosenJur = (isAdminSekolah && selectJur && selectJur.value) ? selectJur.value : (window.currentUser?.jurusan_id || null);

    // Peminjaman HANYA diperuntukkan bagi kategori Alat (sinkron otomatis)
    let filtered = window.dbBarang.filter(b => String(b.jenis || 'alat').toLowerCase() === 'alat');

    if (chosenJur) {
        filtered = filtered.filter(b => String(b.jurusan_id) === String(chosenJur));
    }

    if (labelBarang) labelBarang.innerHTML = 'Pilih Alat <span class="text-red-500">*</span>';

    selectBarang.innerHTML = `<option value="">-- Pilih Alat --</option>` +
        filtered.map(b => `<option value="${b.id}">${b.nama_barang} (Tersedia: ${b.stok_tersedia} ${b.satuan || 'Unit'})</option>`).join('');

    const targetVal = preselectedBarangId !== null ? preselectedBarangId : selectBarang.value;
    if (targetVal && filtered.some(b => String(b.id) === String(targetVal))) {
        selectBarang.value = targetVal;
    } else {
        selectBarang.value = '';
    }

    if (window.pinjamCombobox) {
        window.pinjamCombobox.setItems(filtered, targetVal);
    }
}

function filterGuruPinjamOptions(preselectedGuru = null) {
    initSearchableComboboxes();
    const selectGuru = document.getElementById('pinjam_guru_peminjam_select');
    if (!selectGuru || !window.dbGuru) return;

    const selectJur = document.getElementById('pinjam_jurusan_id');
    const isAdminSekolah = window.currentUser && window.currentUser.peran === 'admin_sekolah';
    const chosenJur = (isAdminSekolah && selectJur && selectJur.value) ? selectJur.value : (window.currentUser?.jurusan_id || null);

    let filtered = window.dbGuru;
    if (chosenJur) {
        filtered = filtered.filter(g => String(g.jurusan_id) === String(chosenJur));
        if (filtered.length === 0) {
            filtered = window.dbGuru.filter(g => String(g.jurusan_id) === String(chosenJur) || g.mengajar === 'umum');
        }
    }

    const currentTeacherName = (window.currentUser && (window.currentUser.nama_lengkap || window.currentUser.nama_pengguna)) || '';
    const defaultLabel = currentTeacherName ? `-- Pilih Guru Peminjam (Otomatis: ${escapeHtml(currentTeacherName)}) --` : '-- Pilih Guru Peminjam (Otomatis Guru Login) --';

    let optionsHtml = `<option value="">${defaultLabel}</option>`;
    filtered.forEach(g => {
        optionsHtml += `<option value="${escapeHtml(g.nama_guru)}">${escapeHtml(g.nama_guru)}</option>`;
    });
    optionsHtml += '<option value="__custom__">-- + Input Nama Guru Manual --</option>';
    selectGuru.innerHTML = optionsHtml;

    let targetVal = preselectedGuru !== null ? preselectedGuru : selectGuru.value;

    if (targetVal) {
        let matched = false;
        for (let opt of selectGuru.options) {
            if (opt.value && opt.value.trim().toLowerCase() === targetVal.trim().toLowerCase()) {
                selectGuru.value = opt.value;
                matched = true;
                break;
            }
        }
        const customGuru = document.getElementById('pinjam_guru_peminjam_custom');
        if (matched) {
            if (customGuru) { customGuru.classList.add('hidden'); customGuru.value = ''; }
        } else if (targetVal !== '__custom__') {
            selectGuru.value = '__custom__';
            if (customGuru) { customGuru.classList.remove('hidden'); customGuru.value = targetVal; }
        }
    } else if (currentTeacherName && preselectedGuru === null) {
        for (let opt of selectGuru.options) {
            if (opt.value && (opt.value.trim().toLowerCase() === currentTeacherName.trim().toLowerCase() ||
                opt.value.trim().toLowerCase().includes(currentTeacherName.trim().toLowerCase()) ||
                currentTeacherName.trim().toLowerCase().includes(opt.value.trim().toLowerCase()))) {
                selectGuru.value = opt.value;
                targetVal = opt.value;
                break;
            }
        }
    }

    if (window.pinjamGuruCombobox) {
        const guruItems = filtered.map(g => {
            let details = [];
            if (g.nama_jurusan) details.push(g.nama_jurusan);
            else if (g.mengajar) details.push(g.mengajar === 'umum' ? 'Guru Umum' : g.mengajar);
            return {
                id: g.nama_guru,
                name: g.nama_guru,
                details: details,
                rightText: ''
            };
        });
        guruItems.push({
            id: '__custom__',
            name: '+ Input Nama Guru Manual',
            details: ['Ketik nama guru manual jika belum terdaftar'],
            rightText: ''
        });
        window.pinjamGuruCombobox.setItems(guruItems, selectGuru.value || null);
    }
}

function filterSiswaPinjamOptions(preselectedSiswa = null) {
    initSearchableComboboxes();
    const selectSiswa = document.getElementById('pinjam_peminjam_select');
    if (!selectSiswa || !window.dbSiswa) return;

    const jurSelect = document.getElementById('pinjam_jurusan_id');
    const isAdminSekolah = window.currentUser && window.currentUser.peran === 'admin_sekolah';
    const chosenJur = (isAdminSekolah && jurSelect && jurSelect.value) ? jurSelect.value : (window.currentUser?.jurusan_id || null);

    let filteredSiswa = window.dbSiswa || [];
    if (chosenJur) {
        filteredSiswa = filteredSiswa.filter(s => String(s.jurusan_id) === String(chosenJur));
    }

    let optionsHtml = '<option value="">-- Pilih Siswa Peminjam --</option>';
    filteredSiswa.forEach(s => {
        const displayName = s.nama_lengkap || s.nama_siswa;
        const nisnPart = s.nisn ? s.nisn + ' - ' : '';
        optionsHtml += `<option value="${escapeHtml(displayName)}" data-nisn="${escapeHtml(s.nisn || '')}" data-ta="${escapeHtml(s.tahun_ajaran || '2026/2027')}">${escapeHtml(displayName)} (${escapeHtml(nisnPart)}${escapeHtml(s.kelas || '-')})</option>`;
    });
    optionsHtml += '<option value="__custom__">-- + Input Nama Siswa Manual --</option>';
    selectSiswa.innerHTML = optionsHtml;

    let targetVal = preselectedSiswa !== null ? preselectedSiswa : selectSiswa.value;
    if (targetVal) {
        let matched = false;
        for (let opt of selectSiswa.options) {
            if (opt.value && opt.value.trim().toLowerCase() === targetVal.trim().toLowerCase()) {
                selectSiswa.value = opt.value;
                matched = true;
                break;
            }
        }
        const customSiswa = document.getElementById('pinjam_peminjam_custom');
        if (matched) {
            if (customSiswa) { customSiswa.classList.add('hidden'); customSiswa.required = false; customSiswa.value = ''; }
        } else if (targetVal !== '__custom__') {
            selectSiswa.value = '__custom__';
            if (customSiswa) { customSiswa.classList.remove('hidden'); customSiswa.required = true; customSiswa.value = targetVal; }
        }
    }

    if (window.pinjamSiswaCombobox) {
        const siswaItems = filteredSiswa.map(s => {
            const displayName = s.nama_lengkap || s.nama_siswa;
            let details = [];
            if (s.nisn) details.push('NISN: ' + s.nisn);
            if (s.kelas) details.push(s.kelas);
            if (s.nama_jurusan) details.push(s.nama_jurusan);
            return {
                id: displayName,
                name: displayName,
                details: details,
                rightText: s.kelas || ''
            };
        });
        siswaItems.push({
            id: '__custom__',
            name: '+ Input Nama Siswa Manual',
            details: ['Ketik nama siswa manual jika belum terdaftar'],
            rightText: ''
        });
        window.pinjamSiswaCombobox.setItems(siswaItems, selectSiswa.value || null);
    }
}

function filterKategoriAndRakByJurusan(selectedJurusanId, selectedKategoriId = null, selectedRakId = null) {
    let jurId = selectedJurusanId;
    if (!jurId && window.currentUser && window.currentUser.jurusan_id) {
        jurId = window.currentUser.jurusan_id;
    }

    const selectKat = document.getElementById('barang_kategori_id');
    const selectRak = document.getElementById('barang_rak_id');

    if (selectKat) {
        let filteredKategori = window.dbKategori || [];
        if (jurId) {
            filteredKategori = filteredKategori.filter(k => String(k.jurusan_id) === String(jurId));
        }
        selectKat.innerHTML = '<option value="">-- Pilih Kategori --</option>' +
            filteredKategori.map(k => `<option value="${k.id}">${k.nama_kategori}</option>`).join('');
        if (selectedKategoriId) {
            selectKat.value = selectedKategoriId;
        } else {
            selectKat.value = '';
        }
    }

    if (selectRak) {
        let filteredRak = window.dbRak || [];
        if (jurId) {
            filteredRak = filteredRak.filter(r => String(r.jurusan_id) === String(jurId));
        }
        selectRak.innerHTML = '<option value="">-- Pilih Rak Penyimpanan --</option>' +
            filteredRak.map(r => `<option value="${r.id}">${r.nama_rak}${r.kategori_rak ? ' (' + r.kategori_rak + ')' : ''}</option>`).join('');
        if (selectedRakId) {
            selectRak.value = selectedRakId;
        } else {
            selectRak.value = '';
        }
    }
}

function openModal(modalId, customTitle = null, editData = null) {
    const modal = document.getElementById(modalId);
    if (!modal) return;

    if (modalId === 'modalBarangMasuk') {
        const chkBarcode = document.getElementById('masuk_use_barcode');
        if (chkBarcode) {
            chkBarcode.checked = false;
            toggleMasukBarcodeScanner(false);
        }
        filterBarangMasukOptions(editData ? editData.barang_id : null);
    } else if (modalId === 'modalBarangKeluar') {
        const chkBarcode = document.getElementById('keluar_use_barcode');
        if (chkBarcode) {
            chkBarcode.checked = false;
            toggleKeluarBarcodeScanner(false);
        }
        filterBarangKeluarOptions(editData ? editData.barang_id : null);
    } else if (modalId === 'modalPeminjaman') {
        const chkBarcode = document.getElementById('pinjam_use_barcode');
        if (chkBarcode) {
            chkBarcode.checked = false;
            togglePinjamBarcodeScanner(false);
        }
        filterBarangPinjamOptions(editData ? editData.barang_id : null);
        filterGuruPinjamOptions(editData ? editData.guru_peminjam : null);
        filterSiswaPinjamOptions(editData ? editData.nama_peminjam : null);
    }

    if (window.dbJurusan) {
        const isAdminSekolah = window.currentUser && window.currentUser.peran === 'admin_sekolah';
        const userJurusanId = window.currentUser ? window.currentUser.jurusan_id : null;

        const allJurSelects = modal.querySelectorAll('select[id$="_jurusan_id"]');
        allJurSelects.forEach(el => {
            const selectId = el.id;
            const defaultLabel = (selectId === 'pengguna_jurusan_id' || selectId === 'siswa_jurusan_id') ? '-- Tidak Ada Jurusan (Umum) --' : '-- Pilih Jurusan --';
            if (isAdminSekolah) {
                el.removeAttribute('disabled');
                el.innerHTML = `<option value="">${defaultLabel}</option>` +
                    window.dbJurusan.map(j => `<option value="${j.id}">${j.nama_jurusan}</option>`).join('');
            } else {
                el.innerHTML = window.dbJurusan
                    .filter(j => String(j.id) === String(userJurusanId))
                    .map(j => `<option value="${j.id}" selected>${j.nama_jurusan}</option>`).join('');
                if (!el.innerHTML && userJurusanId) {
                    el.innerHTML = `<option value="${userJurusanId}" selected>Jurusan Saya</option>`;
                }
                el.setAttribute('disabled', 'disabled');
            }
        });
    }

    if (modalId === 'modalPengguna') {
        const selectGuru = document.getElementById('pengguna_guru_id');
        if (selectGuru && window.dbGuru) {
            selectGuru.innerHTML = '<option value="">-- Pilih Guru --</option>' +
                window.dbGuru.map(g => `<option value="${g.id}">${g.nama_guru} (${g.mengajar === 'umum' ? 'Umum' : (g.nama_jurusan || 'Bengkel')})</option>`).join('');
        }
        const selectSiswa = document.getElementById('pengguna_siswa_id');
        if (selectSiswa && window.dbSiswa) {
            selectSiswa.innerHTML = '<option value="">-- Pilih Siswa --</option>' +
                window.dbSiswa.map(s => `<option value="${s.id}">${s.nama_lengkap || s.nama_siswa} (${s.kelas} - ${s.nama_jurusan || 'Semua'})</option>`).join('');
        }
    }

    if (modalId === 'modalPeminjaman') {
        filterGuruPinjamOptions(editData ? editData.guru_peminjam : null);
        filterSiswaPinjamOptions(editData ? editData.nama_peminjam : null);
    }

    if (modalId === 'modalBarang') {
        const selectJur = document.getElementById('barang_jurusan_id');
        const initialJurId = editData ? editData.jurusan_id : (selectJur ? selectJur.value : null);
        filterKategoriAndRakByJurusan(initialJurId, editData ? editData.kategori_id : null, editData ? editData.rak_id : null);
    }

    // Set judul modal & tombol submit
    const defaultModalTitles = {
        'modalPeminjaman': editData ? 'Edit Transaksi Peminjaman' : 'Tambah Transaksi Peminjaman',
        'modalBarangMasuk': editData ? 'Edit Transaksi Alat & Bahan Masuk' : 'Catat Transaksi Alat & Bahan Masuk',
        'modalBarangKeluar': editData ? 'Edit Transaksi Bahan Keluar' : 'Catat Transaksi Bahan Keluar',
        'modalBarang': editData ? 'Edit Alat & Bahan' : 'Tambah / Edit Alat & Bahan',
        'modalGuru': editData ? 'Edit Data Guru' : 'Tambah Guru Baru',
        'modalSiswa': editData ? 'Edit Data Siswa' : 'Tambah Siswa Baru',
        'modalPengguna': editData ? 'Edit Data Pengguna' : 'Tambah Pengguna Baru',
        'modalJurusan': editData ? 'Edit Jurusan' : 'Tambah Jurusan Baru',
        'modalKategori': editData ? 'Edit Kategori' : 'Tambah Kategori Baru',
        'modalRak': editData ? 'Edit Rak' : 'Tambah Rak Baru'
    };

    const titleElem = modal.querySelector('h3');
    if (titleElem) {
        if (customTitle) {
            titleElem.innerText = customTitle;
        } else if (defaultModalTitles[modalId]) {
            titleElem.innerText = defaultModalTitles[modalId];
        }
    }

    if (modalId === 'modalPeminjaman') {
        const submitBtn = document.getElementById('modalPeminjamanSubmitBtn');
        if (submitBtn) {
            submitBtn.innerText = editData ? 'Simpan Perubahan' : 'Kirim Pengajuan';
        }
    }

    // Reset semua field terlebih dahulu
    const form = modal.querySelector('form');
    if (form) form.reset();

    const populateFields = () => {
        if (editData) {
            if (modalId === 'modalGuru') {
                const elId = document.getElementById('guru_edit_id');
                const elNama = document.getElementById('guru_nama_guru');
                const elUsername = document.getElementById('guru_nama_pengguna');
                const elMengajar = document.getElementById('guru_mengajar');
                const elJurusan = document.getElementById('guru_jurusan_id');
                const elToken = document.getElementById('guru_token');

                if (elId) elId.value = editData.id || '';
                if (elNama) elNama.value = editData.nama_guru || '';
                if (elUsername) elUsername.value = editData.nama_pengguna || (editData.nama_guru ? editData.nama_guru.toLowerCase().replace(/[^a-z0-9]/g, '') : '');
                if (elToken) elToken.value = editData.token || '';
                if (elMengajar) {
                    elMengajar.value = editData.mengajar || 'bengkel';
                    handleGuruMengajarChange(editData.mengajar || 'bengkel');
                }
                if (elJurusan) elJurusan.value = editData.jurusan_id || '';
            }
            else if (modalId === 'modalSiswa') {
                const elId = document.getElementById('siswa_edit_id');
                const elNisn = document.getElementById('siswa_nisn');
                const elNama = document.getElementById('siswa_nama_siswa');
                const elLengkap = document.getElementById('siswa_nama_lengkap');
                const elToken = document.getElementById('siswa_token');
                const elKelas = document.getElementById('siswa_kelas');
                const elJurusan = document.getElementById('siswa_jurusan_id');
                const elTA = document.getElementById('siswa_tahun_ajaran');

                if (elId) elId.value = editData.id || '';
                if (elNisn) elNisn.value = editData.nisn || '';
                if (elNama) elNama.value = editData.nama_siswa || '';
                if (elLengkap) elLengkap.value = editData.nama_lengkap || editData.nama_siswa || '';
                if (elToken) elToken.value = editData.token || '';
                if (elKelas) elKelas.value = editData.kelas || '';
                if (elJurusan) elJurusan.value = editData.jurusan_id || '';
                if (elTA) elTA.value = editData.tahun_ajaran || '2026/2027';
            }
            else if (modalId === 'modalJurusan') {
                const elId = document.getElementById('jurusan_edit_id');
                const elNama = document.getElementById('jurusan_nama');
                const elDesk = document.getElementById('jurusan_deskripsi');
                const elWarna = document.getElementById('jurusan_warna_tema');
                const elPicker = document.getElementById('jurusan_warna_tema_picker');

                if (elId) elId.value = editData.id || '';
                if (elNama) elNama.value = editData.nama_jurusan || '';
                if (elDesk) elDesk.value = editData.deskripsi || '';
                
                let colorVal = editData.warna_tema || '#EAB308';
                const presets = {'kuning':'#EAB308','orange':'#EA580C','hijau':'#2E7D32','merah':'#DC2626','biru':'#2563EB','ungu':'#7C3AED','pink':'#E11D48','cyan':'#0891B2'};
                if (presets[colorVal.toLowerCase()]) colorVal = presets[colorVal.toLowerCase()];
                if (!colorVal.startsWith('#')) colorVal = '#' + colorVal;

                if (elWarna) elWarna.value = colorVal.toUpperCase();
                if (elPicker) elPicker.value = colorVal;
            }
            else if (modalId === 'modalPengguna') {
                const elStatus = document.getElementById('pengguna_status_pengguna');
                const elGuru = document.getElementById('pengguna_guru_id');
                const elSiswa = document.getElementById('pengguna_siswa_id');
                const elToken = document.getElementById('pengguna_token');

                const statusVal = editData.status_pengguna || 'tidak_ada';
                if (elStatus) elStatus.value = statusVal;
                if (elGuru) elGuru.value = editData.guru_id || '';
                if (elSiswa) elSiswa.value = editData.siswa_id || '';
                if (elToken) elToken.value = editData.token || '';
                handleStatusPenggunaChange(statusVal);

                const elId = document.getElementById('pengguna_edit_id');
                const elJurusan = document.getElementById('pengguna_jurusan_id');
                const elNama = document.getElementById('pengguna_nama_pengguna');
                const elLengkap = document.getElementById('pengguna_nama_lengkap');
                const elPrefix = document.getElementById('pengguna_email_prefix');
                const elDomain = document.getElementById('pengguna_email_domain');
                const elPeran = document.getElementById('pengguna_peran');
                const elPass = document.getElementById('pengguna_password');
                const elHint = document.getElementById('pengguna_password_hint');
                const elTel = document.getElementById('pengguna_telepon');
                const elFoto = document.getElementById('pengguna_foto');

                if (elId) elId.value = editData.id || '';
                if (elJurusan) elJurusan.value = editData.jurusan_id || '';
                if (elNama) elNama.value = editData.nama_pengguna || '';
                if (elLengkap) elLengkap.value = editData.nama_lengkap || '';

                const rawEmail = editData.email || '';
                if (rawEmail.includes('@')) {
                    const parts = rawEmail.split('@');
                    if (elPrefix) elPrefix.value = parts[0] || '';
                    if (elDomain) elDomain.value = parts[1] || 'smk2pangkalpinang.sch.id';
                } else {
                    if (elPrefix) elPrefix.value = rawEmail;
                    if (elDomain) elDomain.value = 'smk2pangkalpinang.sch.id';
                }

                let pVal = editData.peran || 'siswa';
                if (pVal === 'admin_jurusan') pVal = 'kabeng';
                if (pVal === 'petugas') pVal = 'guru_jurusan';
                if (elPeran) elPeran.value = pVal;
                if (elPass) elPass.value = '';
                if (elTel) elTel.value = editData.nomor_telepon || '';
                if (elFoto) elFoto.value = '';
                if (elHint) elHint.classList.remove('hidden');
            }
            else if (modalId === 'modalKategori') {
                const elId = document.getElementById('kategori_edit_id');
                const elJurusan = document.getElementById('kategori_jurusan_id');
                const elNama = document.getElementById('kategori_nama');
                const elDesk = document.getElementById('kategori_deskripsi');

                if (elId) elId.value = editData.id || '';
                if (elJurusan) elJurusan.value = editData.jurusan_id || '';
                if (elNama) elNama.value = editData.nama_kategori || '';
                if (elDesk) elDesk.value = editData.deskripsi || '';
            }
            else if (modalId === 'modalRak') {
                const elId = document.getElementById('rak_edit_id');
                const elJurusan = document.getElementById('rak_jurusan_id');
                const elBarcode = document.getElementById('rak_barcode');
                const elNama = document.getElementById('rak_nama');
                const elKat = document.getElementById('rak_kategori');
                const elKet = document.getElementById('rak_keterangan');

                if (elId) elId.value = editData.id || '';
                if (elJurusan) elJurusan.value = editData.jurusan_id || '';
                if (elBarcode) elBarcode.value = editData.barcode || '';
                if (elNama) elNama.value = editData.nama_rak || '';
                if (elKat) elKat.value = editData.kategori_rak || '';
                if (elKet) elKet.value = editData.keterangan || '';
            }
            else if (modalId === 'modalBarang') {
                const elId = document.getElementById('barang_edit_id');
                const elJurusan = document.getElementById('barang_jurusan_id');
                const elBarcode = document.getElementById('barang_barcode');
                const elNama = document.getElementById('barang_nama');
                const elJenis = document.getElementById('barang_jenis');
                const elKategori = document.getElementById('barang_kategori_id');
                const elRak = document.getElementById('barang_rak_id');
                const elMerek = document.getElementById('barang_merek');
                const elStok = document.getElementById('barang_stok');
                const elSatuan = document.getElementById('barang_satuan');

                if (elId) elId.value = editData.id || '';
                if (elJurusan) elJurusan.value = editData.jurusan_id || '';
                if (elBarcode) elBarcode.value = editData.barcode || '';
                if (elNama) elNama.value = editData.nama_barang || '';
                if (elJenis) elJenis.value = editData.jenis || 'alat';
                filterKategoriAndRakByJurusan(editData.jurusan_id || '', editData.kategori_id || '', editData.rak_id || '');
                if (elMerek) elMerek.value = editData.merek || '';
                if (elStok) elStok.value = editData.stok_awal !== undefined ? editData.stok_awal : (editData.stok_total || 0);
                if (elSatuan) elSatuan.value = editData.satuan || 'Unit';

                const imgInput = document.getElementById('barang_image_input');
                const imgPreview = document.getElementById('barang_image_preview');
                const previewBox = document.getElementById('barang_image_preview_box');
                const imgIcon = document.getElementById('barang_image_placeholder_icon');
                const btnRemove = document.getElementById('btn_remove_barang_image');
                const remInp = document.getElementById('barang_remove_image');

                if (imgInput) imgInput.value = '';
                if (remInp) remInp.value = '0';

                if (editData.image) {
                    if (imgPreview) {
                        imgPreview.src = editData.image;
                        imgPreview.classList.remove('hidden');
                    }
                    if (previewBox) {
                        previewBox.classList.remove('hidden');
                        previewBox.classList.add('flex');
                    }
                    if (imgIcon) imgIcon.classList.add('hidden');
                    if (btnRemove) btnRemove.classList.remove('hidden');
                } else {
                    if (imgPreview) {
                        imgPreview.src = '';
                        imgPreview.classList.add('hidden');
                    }
                    if (previewBox) {
                        previewBox.classList.add('hidden');
                        previewBox.classList.remove('flex');
                    }
                    if (imgIcon) imgIcon.classList.remove('hidden');
                    if (btnRemove) btnRemove.classList.add('hidden');
                }
            }
            else if (modalId === 'modalBarangMasuk') {
                const elId = document.getElementById('masuk_edit_id');
                const elJurusan = document.getElementById('masuk_jurusan_id');
                const elJenis = document.getElementById('masuk_jenis');
                const elBarang = document.getElementById('masuk_barang_id');
                const elJumlah = document.getElementById('masuk_jumlah');
                const elTgl = document.getElementById('masuk_tanggal');

                if (elId) elId.value = editData.id || '';
                if (elJurusan) elJurusan.value = editData.jurusan_id || '';
                const foundB = (window.dbBarang || []).find(b => String(b.id) === String(editData.barang_id));
                if (elJenis) elJenis.value = foundB ? (foundB.jenis || '') : '';
                filterBarangMasukOptions(editData.barang_id || '');
                if (elJumlah) elJumlah.value = editData.jumlah || 1;

                const rawDate = editData.tanggal_masuk || editData.created_at || '';
                const dateOnly = rawDate ? rawDate.substring(0, 10) : getLocalDateString();
                if (elTgl) elTgl.value = dateOnly;
                toggleAutoDateMasuk(dateOnly === getLocalDateString());
            }
            else if (modalId === 'modalBarangKeluar') {
                const elId = document.getElementById('keluar_edit_id');
                const elJurusan = document.getElementById('keluar_jurusan_id');
                const elPenerima = document.getElementById('keluar_penerima');
                const elJumlah = document.getElementById('keluar_jumlah');
                const elKet = document.getElementById('keluar_keterangan');
                const elTgl = document.getElementById('keluar_tanggal');

                if (elId) elId.value = editData.id || '';
                if (elJurusan) elJurusan.value = editData.jurusan_id || '';
                filterBarangKeluarOptions(editData.barang_id || '');
                if (elPenerima) elPenerima.value = editData.nama_penerima || '';
                if (elJumlah) elJumlah.value = editData.jumlah || 1;
                if (elKet) elKet.value = editData.catatan || '';

                const rawDate = editData.tanggal_keluar || editData.created_at || '';
                const dateOnly = rawDate ? rawDate.substring(0, 10) : getLocalDateString();
                if (elTgl) elTgl.value = dateOnly;
                toggleAutoDateKeluar(dateOnly === getLocalDateString());
            }
            else if (modalId === 'modalPeminjaman') {
                const elId = document.getElementById('pinjam_edit_id');
                const elJurusan = document.getElementById('pinjam_jurusan_id');
                const elJenis = document.getElementById('pinjam_jenis');
                const elBarang = document.getElementById('pinjam_barang_id');
                const elJumlah = document.getElementById('pinjam_jumlah');
                const elTugas = document.getElementById('pinjam_tugas');
                const elTahun = document.getElementById('pinjam_tahun_ajaran');
                const elTglPinjam = document.getElementById('pinjam_tanggal_pinjam');
                const elTglKembali = document.getElementById('pinjam_tanggal_kembali');
                const elStatus = document.getElementById('pinjam_status');
                const elNisn = document.getElementById('pinjam_nisn');
                const elCheckSiswa = document.getElementById('pinjam_untuk_siswa');

                const selectGuru = document.getElementById('pinjam_guru_peminjam_select');
                const customGuru = document.getElementById('pinjam_guru_peminjam_custom');
                const selectSiswa = document.getElementById('pinjam_peminjam_select');
                const customSiswa = document.getElementById('pinjam_peminjam_custom');

                if (elId) elId.value = editData.id || '';
                if (elJurusan) elJurusan.value = editData.jurusan_id || '';

                filterGuruPinjamOptions(editData.guru_peminjam || null);
                filterSiswaPinjamOptions(editData.nama_peminjam || null);

                if (elJenis) elJenis.value = 'alat';
                filterBarangPinjamOptions(editData.barang_id || '');
                if (elBarang) elBarang.value = editData.barang_id || '';

                if (elJumlah) elJumlah.value = editData.jumlah || 1;
                if (elTugas) elTugas.value = editData.tugas || editData.keperluan_tugas || '';
                if (elTglPinjam) elTglPinjam.value = formatForDateTimeLocal(editData.tanggal_pinjam);
                if (elTglKembali) elTglKembali.value = formatForDateTimeLocal(editData.tanggal_kembali);
                if (elStatus) elStatus.value = editData.status || 'dipinjam';

                // 1. Sinkronisasi Guru Penanggung Jawab
                if (selectGuru && editData.guru_peminjam) {
                    let matchedGuruOpt = false;
                    for (let opt of selectGuru.options) {
                        if (opt.value && opt.value.trim().toLowerCase() === editData.guru_peminjam.trim().toLowerCase()) {
                            selectGuru.value = opt.value;
                            matchedGuruOpt = true;
                            break;
                        }
                    }
                    if (matchedGuruOpt) {
                        if (customGuru) { customGuru.classList.add('hidden'); customGuru.value = ''; }
                    } else {
                        selectGuru.value = '__custom__';
                        if (customGuru) { customGuru.classList.remove('hidden'); customGuru.value = editData.guru_peminjam; }
                    }
                    if (window.pinjamGuruCombobox) {
                        window.pinjamGuruCombobox.setValue(selectGuru.value);
                    }
                }

                // 2. Sinkronisasi Apakah Peminjam Siswa
                const isUntukSiswa = Boolean(
                    (editData.nisn && String(editData.nisn).trim() !== '') ||
                    (editData.nama_peminjam && (!editData.guru_peminjam || editData.nama_peminjam.trim().toLowerCase() !== editData.guru_peminjam.trim().toLowerCase()))
                );

                if (elCheckSiswa) {
                    elCheckSiswa.checked = isUntukSiswa;
                }
                togglePinjamUntukSiswa(isUntukSiswa);

                // 3. Sinkronisasi Data Siswa
                if (isUntukSiswa) {
                    const rawPeminjam = (editData.nama_peminjam || '').trim();
                    const rawNisn = (editData.nisn || '').trim();

                    const matchedSiswa = (window.dbSiswa || []).find(s =>
                        (rawNisn && s.nisn && String(s.nisn).trim() === rawNisn) ||
                        (rawPeminjam && s.nama_lengkap && s.nama_lengkap.trim().toLowerCase() === rawPeminjam.toLowerCase()) ||
                        (rawPeminjam && s.nama_siswa && s.nama_siswa.trim().toLowerCase() === rawPeminjam.toLowerCase())
                    );

                    let matchedOpt = false;
                    if (selectSiswa) {
                        for (let opt of selectSiswa.options) {
                            const optNisn = (opt.getAttribute('data-nisn') || '').trim();
                            const optVal = opt.value.trim().toLowerCase();
                            if (
                                (rawNisn && optNisn && optNisn === rawNisn) ||
                                (rawPeminjam && optVal === rawPeminjam.toLowerCase()) ||
                                (matchedSiswa && (optVal === (matchedSiswa.nama_lengkap || '').toLowerCase() || optVal === (matchedSiswa.nama_siswa || '').toLowerCase()))
                            ) {
                                selectSiswa.value = opt.value;
                                matchedOpt = true;
                                break;
                            }
                        }

                        if (matchedOpt) {
                            if (customSiswa) { customSiswa.classList.add('hidden'); customSiswa.required = false; customSiswa.value = ''; }
                        } else if (rawPeminjam) {
                            selectSiswa.value = '__custom__';
                            if (customSiswa) { customSiswa.classList.remove('hidden'); customSiswa.required = true; customSiswa.value = rawPeminjam; }
                        }
                        if (window.pinjamSiswaCombobox) {
                            window.pinjamSiswaCombobox.setValue(selectSiswa.value);
                        }
                    }

                    if (elNisn) elNisn.value = rawNisn || (matchedSiswa ? matchedSiswa.nisn : '');
                    if (elTahun) elTahun.value = editData.tahun_ajaran || (matchedSiswa ? matchedSiswa.tahun_ajaran : '2026/2027');
                } else {
                    if (selectSiswa) selectSiswa.value = '';
                    if (customSiswa) { customSiswa.classList.add('hidden'); customSiswa.required = false; customSiswa.value = ''; }
                    if (elNisn) elNisn.value = '';
                    if (elTahun) elTahun.value = '';
                    if (window.pinjamSiswaCombobox) {
                        window.pinjamSiswaCombobox.clear(false);
                    }
                }

                // Jika status sudah 'dikembalikan', KUNCI (disable) semua field kecuali Tanggal dan Status!
                const isReturned = (editData.status === 'dikembalikan');
                const searchPinjam = document.getElementById('pinjam_barang_search');
                const clearPinjam = document.getElementById('pinjam_barang_clear');
                const chevronPinjam = document.getElementById('pinjam_barang_chevron');
                const searchGuru = document.getElementById('pinjam_guru_search');
                const clearGuru = document.getElementById('pinjam_guru_clear');
                const chevronGuru = document.getElementById('pinjam_guru_chevron');
                const searchSiswa = document.getElementById('pinjam_siswa_search');
                const clearSiswa = document.getElementById('pinjam_siswa_clear');
                const chevronSiswa = document.getElementById('pinjam_siswa_chevron');
                [elJurusan, elJenis, elBarang, selectSiswa, customSiswa, elJumlah, elTugas, elTahun, elCheckSiswa, selectGuru, customGuru, searchPinjam, searchGuru, searchSiswa].forEach(el => {
                    if (el) {
                        el.disabled = isReturned;
                        if (isReturned) {
                            el.classList.add('bg-slate-100/80', 'cursor-not-allowed');
                        } else {
                            el.classList.remove('bg-slate-100/80', 'cursor-not-allowed');
                        }
                    }
                });
                if (clearPinjam && isReturned) clearPinjam.classList.add('hidden');
                if (chevronPinjam) chevronPinjam.disabled = isReturned;
                if (clearGuru && isReturned) clearGuru.classList.add('hidden');
                if (chevronGuru) chevronGuru.disabled = isReturned;
                if (clearSiswa && isReturned) clearSiswa.classList.add('hidden');
                if (chevronSiswa) chevronSiswa.disabled = isReturned;
            }
        } else {
            ['pinjam_barang_search', 'pinjam_guru_search', 'pinjam_siswa_search'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.disabled = false;
                    el.classList.remove('bg-slate-100/80', 'cursor-not-allowed');
                }
            });
            ['pinjam_barang_chevron', 'pinjam_guru_chevron', 'pinjam_siswa_chevron'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.disabled = false;
            });
            // Reset semua hidden ID dan input saat mode Tambah Data Baru
            ['guru_edit_id', 'siswa_edit_id', 'jurusan_edit_id', 'pengguna_edit_id', 'kategori_edit_id', 'rak_edit_id', 'barang_edit_id', 'masuk_edit_id', 'keluar_edit_id', 'pinjam_edit_id'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.value = '';
            });

            if (modalId === 'modalGuru') {
                const elNama = document.getElementById('guru_nama_guru');
                const elUsername = document.getElementById('guru_nama_pengguna');
                const elMengajar = document.getElementById('guru_mengajar');
                const elJur = document.getElementById('guru_jurusan_id');
                const elToken = document.getElementById('guru_token');
                if (elNama) elNama.value = '';
                if (elUsername) elUsername.value = '';
                if (elMengajar) {
                    elMengajar.value = 'bengkel';
                    handleGuruMengajarChange('bengkel');
                }
                if (elJur) elJur.value = '';
                if (elToken) elToken.value = '';
                generateTokenForGuruForm();
            }

            if (modalId === 'modalSiswa') {
                const elNisn = document.getElementById('siswa_nisn');
                const elNama = document.getElementById('siswa_nama_siswa');
                const elLengkap = document.getElementById('siswa_nama_lengkap');
                const elKelas = document.getElementById('siswa_kelas');
                const elTA = document.getElementById('siswa_tahun_ajaran');
                const elJur = document.getElementById('siswa_jurusan_id');
                if (elNisn) elNisn.value = '';
                if (elNama) elNama.value = '';
                if (elLengkap) elLengkap.value = '';
                if (elKelas) elKelas.value = '';
                if (elTA) elTA.value = '2026/2027';
                if (elJur) elJur.value = '';
                generateTokenForSiswaForm();
            }

            if (modalId === 'modalJurusan') {
                const elNama = document.getElementById('jurusan_nama');
                const elDesk = document.getElementById('jurusan_deskripsi');
                const elWarna = document.getElementById('jurusan_warna_tema');
                const elPicker = document.getElementById('jurusan_warna_tema_picker');
                if (elNama) elNama.value = '';
                if (elDesk) elDesk.value = '';
                if (elWarna) elWarna.value = '#EAB308';
                if (elPicker) elPicker.value = '#EAB308';
            } else if (modalId === 'modalRak') {
                const elBarcode = document.getElementById('rak_barcode');
                const elNama = document.getElementById('rak_nama');
                const elKat = document.getElementById('rak_kategori');
                const elKet = document.getElementById('rak_keterangan');
                if (elBarcode) elBarcode.value = (window.nextCodes && window.nextCodes.rakBarcode) ? window.nextCodes.rakBarcode : '';
                if (elNama) elNama.value = '';
                if (elKat) elKat.value = '';
                if (elKet) elKet.value = '';
            } else if (modalId === 'modalPengguna') {
                const elStatus = document.getElementById('pengguna_status_pengguna');
                const elGuru = document.getElementById('pengguna_guru_id');
                const elSiswa = document.getElementById('pengguna_siswa_id');
                const elToken = document.getElementById('pengguna_token');
                const elNama = document.getElementById('pengguna_nama_pengguna');
                const elLengkap = document.getElementById('pengguna_nama_lengkap');
                const elPrefix = document.getElementById('pengguna_email_prefix');
                const elDomain = document.getElementById('pengguna_email_domain');
                const elPeran = document.getElementById('pengguna_peran');
                const elPass = document.getElementById('pengguna_password');
                const elTel = document.getElementById('pengguna_telepon');
                const elFoto = document.getElementById('pengguna_foto');
                const pwHint = document.getElementById('pengguna_password_hint');

                if (elStatus) elStatus.value = 'tidak_ada';
                if (elGuru) elGuru.value = '';
                if (elSiswa) elSiswa.value = '';
                if (elToken) elToken.value = '';
                handleStatusPenggunaChange('tidak_ada');

                if (elNama) elNama.value = '';
                if (elLengkap) elLengkap.value = '';
                if (elPrefix) elPrefix.value = '';
                if (elDomain) elDomain.value = 'smk2pangkalpinang.sch.id';
                if (elPeran) elPeran.value = 'siswa';
                if (elPass) elPass.value = '';
                if (elTel) elTel.value = '';
                if (elFoto) elFoto.value = '';
                if (pwHint) pwHint.classList.add('hidden');
            } else if (modalId === 'modalBarang') {
                const elBarcode = document.getElementById('barang_barcode');
                const elKategori = document.getElementById('barang_kategori_id');
                const elRak = document.getElementById('barang_rak_id');
                const elSatuan = document.getElementById('barang_satuan');
                const elJurusan = document.getElementById('barang_jurusan_id');

                const initialJurId = elJurusan ? elJurusan.value : null;
                filterKategoriAndRakByJurusan(initialJurId);

                const elJenis = document.getElementById('barang_jenis');
                if (elJenis) elJenis.value = 'alat';
                if (elKategori) elKategori.value = '';
                if (elRak) elRak.value = '';
                if (elSatuan) elSatuan.value = 'Unit';
                if (elBarcode) elBarcode.value = window.nextCodes?.barcode || '';

                const imgInput = document.getElementById('barang_image_input');
                const imgPreview = document.getElementById('barang_image_preview');
                const previewBox = document.getElementById('barang_image_preview_box');
                const imgIcon = document.getElementById('barang_image_placeholder_icon');
                const btnRemove = document.getElementById('btn_remove_barang_image');
                const remInp = document.getElementById('barang_remove_image');
                if (imgInput) imgInput.value = '';
                if (remInp) remInp.value = '0';
                if (imgPreview) {
                    imgPreview.src = '';
                    imgPreview.classList.add('hidden');
                }
                if (previewBox) {
                    previewBox.classList.add('hidden');
                    previewBox.classList.remove('flex');
                }
                if (imgIcon) imgIcon.classList.remove('hidden');
                if (btnRemove) btnRemove.classList.add('hidden');
            } else if (modalId === 'modalBarangMasuk') {
                const elJenis = document.getElementById('masuk_jenis');
                if (elJenis) elJenis.value = '';
                filterBarangMasukOptions();
                const elJumlah = document.getElementById('masuk_jumlah');
                if (elJumlah) elJumlah.value = 5;
                toggleAutoDateMasuk(true);
            } else if (modalId === 'modalBarangKeluar') {
                filterBarangKeluarOptions();
                const elPenerima = document.getElementById('keluar_penerima');
                const elJumlah = document.getElementById('keluar_jumlah');
                const elKet = document.getElementById('keluar_keterangan');
                if (elPenerima) elPenerima.value = '';
                if (elJumlah) elJumlah.value = 1;
                if (elKet) elKet.value = '';
                toggleAutoDateKeluar(true);
            } else if (modalId === 'modalPeminjaman') {
                const elJenis = document.getElementById('pinjam_jenis');
                if (elJenis) elJenis.value = 'alat';
                filterBarangPinjamOptions();
                filterGuruPinjamOptions();
                filterSiswaPinjamOptions();

                const elJurusan = document.getElementById('pinjam_jurusan_id');
                const elBarang = document.getElementById('pinjam_barang_id');
                const elJumlah = document.getElementById('pinjam_jumlah');
                const elTugas = document.getElementById('pinjam_tugas');
                const elTglPinjam = document.getElementById('pinjam_tanggal_pinjam');
                const elTglKembali = document.getElementById('pinjam_tanggal_kembali');
                const elStatus = document.getElementById('pinjam_status');

                const checkUntukSiswa = document.getElementById('pinjam_untuk_siswa');
                const selectSiswa = document.getElementById('pinjam_peminjam_select');
                const customSiswa = document.getElementById('pinjam_peminjam_custom');
                const inputNisn = document.getElementById('pinjam_nisn');
                const inputTA = document.getElementById('pinjam_tahun_ajaran');

                const selectGuru = document.getElementById('pinjam_guru_peminjam_select');
                const customGuru = document.getElementById('pinjam_guru_peminjam_custom');

                const searchPinjam = document.getElementById('pinjam_barang_search');
                const searchGuru = document.getElementById('pinjam_guru_search');
                const searchSiswa = document.getElementById('pinjam_siswa_search');
                [elJurusan, elJenis, elBarang, selectSiswa, customSiswa, elJumlah, elTugas, elTglPinjam, elTglKembali, elStatus, checkUntukSiswa, selectGuru, customGuru, inputNisn, inputTA, searchPinjam, searchGuru, searchSiswa].forEach(el => {
                    if (el) {
                        el.disabled = false;
                        el.classList.remove('bg-slate-100/80', 'cursor-not-allowed');
                    }
                });

                if (checkUntukSiswa) {
                    checkUntukSiswa.checked = false;
                }
                togglePinjamUntukSiswa(false);

                if (selectSiswa) selectSiswa.value = '';
                if (customSiswa) { customSiswa.classList.add('hidden'); customSiswa.required = false; customSiswa.value = ''; }
                if (inputNisn) inputNisn.value = '';
                if (inputTA) inputTA.value = '2026/2027';

                if (selectGuru) {
                    const currentUserFullName = (window.currentUser && (window.currentUser.nama_lengkap || window.currentUser.nama_pengguna)) || '';
                    const matchedGuru = (window.dbGuru || []).find(g =>
                        g.nama_guru.toLowerCase().includes(currentUserFullName.toLowerCase()) ||
                        currentUserFullName.toLowerCase().includes(g.nama_guru.toLowerCase())
                    );
                    if (matchedGuru) {
                        selectGuru.value = matchedGuru.nama_guru;
                        if (customGuru) { customGuru.classList.add('hidden'); customGuru.value = ''; }
                    } else {
                        selectGuru.value = '';
                        if (customGuru) { customGuru.classList.add('hidden'); customGuru.value = ''; }
                    }
                }

                if (window.pinjamCombobox) window.pinjamCombobox.clear(false);
                if (window.pinjamSiswaCombobox) window.pinjamSiswaCombobox.clear(false);
                if (window.pinjamGuruCombobox) window.pinjamGuruCombobox.setValue(selectGuru ? selectGuru.value : '');

                if (elJumlah) elJumlah.value = 1;
                if (elTugas) elTugas.value = '';
                if (elTglPinjam) elTglPinjam.value = formatForDateTimeLocal(new Date());
                if (elTglKembali) elTglKembali.value = '';
                if (elStatus) elStatus.value = 'dipinjam';
            }
        }
    };

    // Jalankan langsung dan set delay kecil agar nilai tidak ter-reset browser
    populateFields();
    setTimeout(populateFields, 20);

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.classList.add('modal-open');
}

function syncThemeColorInput(val) {
    const elText = document.getElementById('jurusan_warna_tema');
    if (elText) elText.value = val.toUpperCase();
}

function handlePeranChange(peranVal) {
    const elJurusan = document.getElementById('pengguna_jurusan_id');
    if (elJurusan) {
        if (peranVal === 'admin_sekolah') {
            elJurusan.value = '';
            elJurusan.disabled = true;
        } else {
            elJurusan.disabled = false;
        }
    }
}

function togglePasswordVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    if (!input) return;
    const eyeOpen = btn.querySelector('.eyeOpenIcon');
    const eyeClose = btn.querySelector('.eyeCloseIcon');
    if (input.type === 'password') {
        input.type = 'text';
        if (eyeOpen) eyeOpen.classList.remove('hidden');
        if (eyeClose) eyeClose.classList.add('hidden');
    } else {
        input.type = 'password';
        if (eyeOpen) eyeOpen.classList.add('hidden');
        if (eyeClose) eyeClose.classList.remove('hidden');
    }
}

function syncThemeColorPicker(val) {
    const elPicker = document.getElementById('jurusan_warna_tema_picker');
    let hex = val.trim();
    if (!hex.startsWith('#')) hex = '#' + hex;
    if (elPicker && (hex.length === 4 || hex.length === 7)) {
        elPicker.value = hex;
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        const form = modal.querySelector('form');
        if (form) form.reset();
        modal.querySelectorAll('input[type="hidden"]').forEach(el => {
            if (el.id && el.id.includes('_edit_id')) el.value = '';
        });

        if (modalId === 'modalPengguna') {
            const elStatus = document.getElementById('pengguna_status_pengguna');
            const elGuru = document.getElementById('pengguna_guru_id');
            const elSiswa = document.getElementById('pengguna_siswa_id');
            if (elStatus) elStatus.value = 'tidak_ada';
            if (elGuru) elGuru.value = '';
            if (elSiswa) elSiswa.value = '';
            handleStatusPenggunaChange('tidak_ada');
        } else if (modalId === 'modalPeminjaman') {
            stopPinjamCameraStream();
            const chkBarcode = document.getElementById('pinjam_use_barcode');
            if (chkBarcode) {
                chkBarcode.checked = false;
                togglePinjamBarcodeScanner(false);
            }
        } else if (modalId === 'modalBarangMasuk') {
            stopMasukCameraStream();
            const chkBarcode = document.getElementById('masuk_use_barcode');
            if (chkBarcode) {
                chkBarcode.checked = false;
                toggleMasukBarcodeScanner(false);
            }
            toggleAutoDateMasuk(true);
        } else if (modalId === 'modalBarangKeluar') {
            stopKeluarCameraStream();
            const chkBarcode = document.getElementById('keluar_use_barcode');
            if (chkBarcode) {
                chkBarcode.checked = false;
                toggleKeluarBarcodeScanner(false);
            }
            toggleAutoDateKeluar(true);
        } else if (modalId === 'modalBarang') {
            const imgInput = document.getElementById('barang_image_input');
            const imgPreview = document.getElementById('barang_image_preview');
            const previewBox = document.getElementById('barang_image_preview_box');
            const imgIcon = document.getElementById('barang_image_placeholder_icon');
            const btnRemove = document.getElementById('btn_remove_barang_image');
            const remInp = document.getElementById('barang_remove_image');
            if (imgInput) imgInput.value = '';
            if (remInp) remInp.value = '0';
            if (imgPreview) {
                imgPreview.src = '';
                imgPreview.classList.add('hidden');
            }
            if (previewBox) {
                previewBox.classList.add('hidden');
                previewBox.classList.remove('flex');
            }
            if (imgIcon) imgIcon.classList.remove('hidden');
            if (btnRemove) btnRemove.classList.add('hidden');
        } else if (modalId === 'modalMigrasiSiswa') {
            const btn = document.getElementById('btnSubmitMigrasiSiswa');
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> <span>Ya, Jalankan Migrasi</span>`;
            }
        } else if (modalId === 'modalRollbackMigrasiSiswa') {
            const btn = document.getElementById('btnSubmitRollbackMigrasi');
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a5 5 0 015 5v2m0 0l-4-4m4 4l4-4M3 10l4-4m-4 4l4 4"/></svg> <span>Ya, Rollback Migrasi</span>`;
            }
        } else if (modalId === 'modalFotoPreview') {
            const titleBox = document.getElementById('previewFotoTitleBox');
            if (titleBox) {
                titleBox.classList.add('hidden');
                titleBox.classList.remove('flex');
            }
            const titleElem = document.getElementById('previewFotoTitle');
            if (titleElem) titleElem.textContent = '';
            const imgElem = document.getElementById('previewFotoImg');
            if (imgElem) imgElem.src = '';
        }
    }

    const remainingModals = document.querySelectorAll('.fixed.inset-0.z-50:not(.hidden)');
    if (remainingModals.length === 0) {
        document.body.classList.remove('modal-open');
    }

    // Bersihkan parameter ID dari URL saat modal ditutup, tetapkan tab aktif
    const url = new URL(window.location.href);
    if (url.searchParams.has('id')) {
        url.searchParams.delete('id');
    }
    const activeBtn = document.querySelector('.nav-tab-btn.active');
    if (activeBtn) {
        const activeTab = activeBtn.getAttribute('data-tab');
        if (activeTab) {
            url.searchParams.set('tab', activeTab);
        }
    }
    const newSearch = url.searchParams.toString();
    history.pushState(null, '', url.pathname + (newSearch ? '?' + newSearch : ''));
}

let isFormSubmitting = false;
async function handleFormSubmit(event, actionName) {
    event.preventDefault();
    if (isFormSubmitting) return;

    const form = event.target;
    const modal = form.closest('.fixed.inset-0');
    const formData = new FormData(form);

    let apiAction = '';
    if (actionName === 'Jurusan') {
        apiAction = 'save_jurusan';
        formData.append('id', document.getElementById('jurusan_edit_id')?.value || '');
        formData.append('nama_jurusan', document.getElementById('jurusan_nama')?.value || '');
        formData.append('deskripsi', document.getElementById('jurusan_deskripsi')?.value || '');
        formData.append('warna_tema', document.getElementById('jurusan_warna_tema')?.value || 'kuning');
    } else if (actionName === 'Pengguna') {
        apiAction = 'save_pengguna';
        formData.append('id', document.getElementById('pengguna_edit_id')?.value || '');
        formData.append('jurusan_id', document.getElementById('pengguna_jurusan_id')?.value || '');
        formData.append('nama_pengguna', document.getElementById('pengguna_nama_pengguna')?.value || '');
        formData.append('nama_lengkap', document.getElementById('pengguna_nama_lengkap')?.value || '');

        const emailPrefix = document.getElementById('pengguna_email_prefix')?.value.trim() || '';
        let emailDomain = document.getElementById('pengguna_email_domain')?.value.trim() || 'smk2pangkalpinang.sch.id';
        if (emailDomain.startsWith('@')) emailDomain = emailDomain.substring(1);
        const combinedEmail = emailPrefix ? (emailPrefix + '@' + emailDomain) : '';
        formData.append('email', combinedEmail);

        formData.append('peran', document.getElementById('pengguna_peran')?.value || 'siswa');
        formData.append('status_pengguna', document.getElementById('pengguna_status_pengguna')?.value || 'tidak_ada');
        formData.append('guru_id', document.getElementById('pengguna_guru_id')?.value || '');
        formData.append('siswa_id', document.getElementById('pengguna_siswa_id')?.value || '');
        formData.append('token', document.getElementById('pengguna_token')?.value || '');
        formData.append('password', document.getElementById('pengguna_password')?.value || '');
        formData.append('nomor_telepon', document.getElementById('pengguna_telepon')?.value || '');

        const fotoFileInput = document.getElementById('pengguna_foto');
        if (fotoFileInput && fotoFileInput.files && fotoFileInput.files.length > 0) {
            formData.append('foto', fotoFileInput.files[0]);
        }
    } else if (actionName === 'Kategori') {
        apiAction = 'save_kategori';
        formData.append('id', document.getElementById('kategori_edit_id')?.value || '');
        formData.append('jurusan_id', document.getElementById('kategori_jurusan_id')?.value || '');
        formData.append('nama_kategori', document.getElementById('kategori_nama')?.value || '');
        formData.append('deskripsi', document.getElementById('kategori_deskripsi')?.value || '');
    } else if (actionName === 'Rak') {
        apiAction = 'save_rak';
        formData.append('id', document.getElementById('rak_edit_id')?.value || '');
        formData.append('jurusan_id', document.getElementById('rak_jurusan_id')?.value || '');
        formData.append('nama_rak', document.getElementById('rak_nama')?.value || '');
        formData.append('barcode', document.getElementById('rak_barcode')?.value || '');
        formData.append('kategori_rak', document.getElementById('rak_kategori')?.value || '');
        formData.append('keterangan', document.getElementById('rak_keterangan')?.value || '');
    } else if (actionName === 'Barang') {
        apiAction = 'save_barang';
        formData.append('id', document.getElementById('barang_edit_id')?.value || '');
        formData.append('jurusan_id', document.getElementById('barang_jurusan_id')?.value || '');
        formData.append('nama_barang', document.getElementById('barang_nama')?.value || '');
        formData.append('jenis', document.getElementById('barang_jenis')?.value || 'alat');
        formData.append('kategori_id', document.getElementById('barang_kategori_id')?.value || '');
        formData.append('rak_id', document.getElementById('barang_rak_id')?.value || '');
        formData.append('merek', document.getElementById('barang_merek')?.value || '');
        formData.append('barcode', document.getElementById('barang_barcode')?.value || '');
        formData.append('stok_awal', document.getElementById('barang_stok')?.value || '0');
        formData.append('stok_total', document.getElementById('barang_stok')?.value || '0');
        formData.append('satuan', document.getElementById('barang_satuan')?.value || 'Unit');
        const imgInput = document.getElementById('barang_image_input');
        if (imgInput && imgInput.files && imgInput.files[0]) {
            formData.append('image', imgInput.files[0]);
        }
        formData.append('remove_image', document.getElementById('barang_remove_image')?.value || '0');
    } else if (actionName === 'Barang Keluar') {
        apiAction = 'save_barang_keluar';
        formData.append('id', document.getElementById('keluar_edit_id')?.value || '');
        formData.append('jurusan_id', document.getElementById('keluar_jurusan_id')?.value || '');
        const barangId = document.getElementById('keluar_barang_id')?.value || '';
        if (!barangId) {
            const isUsingBarcode = document.getElementById('keluar_use_barcode')?.checked;
            if (isUsingBarcode) {
                showNotification('Silakan scan barcode atau QR code bahan terlebih dahulu!', 'warning');
            } else {
                showNotification('Pilih bahan yang akan dicatat keluar terlebih dahulu!', 'warning');
            }
            return;
        }
        formData.append('barang_id', barangId);
        formData.append('nama_penerima', document.getElementById('keluar_penerima')?.value || '');
        formData.append('jumlah', document.getElementById('keluar_jumlah')?.value || '1');
        formData.append('catatan', document.getElementById('keluar_keterangan')?.value || '');
        const isAutoKeluar = document.getElementById('keluar_tgl_auto')?.checked;
        const keluarTgl = isAutoKeluar ? getLocalDateString() : (document.getElementById('keluar_tanggal')?.value || getLocalDateString());
        formData.append('tanggal', keluarTgl);
    } else if (actionName === 'Barang Masuk') {
        apiAction = 'save_barang_masuk';
        formData.append('id', document.getElementById('masuk_edit_id')?.value || '');
        formData.append('jurusan_id', document.getElementById('masuk_jurusan_id')?.value || '');
        const barangId = document.getElementById('masuk_barang_id')?.value || '';
        if (!barangId) {
            const isUsingBarcode = document.getElementById('masuk_use_barcode')?.checked;
            if (isUsingBarcode) {
                showNotification('Silakan scan barcode atau QR code barang terlebih dahulu!', 'warning');
            } else {
                showNotification('Pilih alat atau bahan yang akan dicatat masuk terlebih dahulu!', 'warning');
            }
            return;
        }
        formData.append('barang_id', barangId);
        formData.append('jumlah', document.getElementById('masuk_jumlah')?.value || '1');
        const isAutoMasuk = document.getElementById('masuk_tgl_auto')?.checked;
        const masukTgl = isAutoMasuk ? getLocalDateString() : (document.getElementById('masuk_tanggal')?.value || getLocalDateString());
        formData.append('tanggal', masukTgl);
    } else if (actionName === 'Peminjaman Alat' || actionName === 'Peminjaman') {
        apiAction = 'save_peminjaman';
        formData.append('id', document.getElementById('pinjam_edit_id')?.value || '');
        formData.append('jurusan_id', document.getElementById('pinjam_jurusan_id')?.value || '');

        const barangId = document.getElementById('pinjam_barang_id')?.value || '';
        if (!barangId) {
            const isUsingBarcode = document.getElementById('pinjam_use_barcode')?.checked;
            if (isUsingBarcode) {
                showNotification('Silakan scan barcode atau QR code barang terlebih dahulu!', 'warning');
            } else {
                showNotification('Pilih alat yang akan dipinjam!', 'warning');
            }
            return;
        }
        formData.append('barang_id', barangId);

        const elGuruSel = document.getElementById('pinjam_guru_peminjam_select');
        const elGuruCust = document.getElementById('pinjam_guru_peminjam_custom');
        const elGuruAuto = document.getElementById('pinjam_guru_peminjam_auto');
        let guruVal = '';
        if (elGuruSel) {
            guruVal = (elGuruSel.value === '__custom__') ? (elGuruCust ? elGuruCust.value : '') : elGuruSel.value;
        } else if (elGuruAuto) {
            guruVal = elGuruAuto.value;
        }

        // Jika input kosong, otomatis gunakan guru yang sedang login di akun tersebut
        if (!guruVal || !guruVal.trim()) {
            guruVal = (window.currentUser && (window.currentUser.nama_lengkap || window.currentUser.nama_pengguna)) || 'Guru';
        }

        const isUntukSiswa = document.getElementById('pinjam_untuk_siswa')?.checked;
        let siswaVal = '';
        let nisnVal = '';
        let taVal = '';

        if (isUntukSiswa) {
            const elSiswaSel = document.getElementById('pinjam_peminjam_select');
            const elSiswaCust = document.getElementById('pinjam_peminjam_custom');
            siswaVal = (elSiswaSel && elSiswaSel.value === '__custom__') ? (elSiswaCust ? elSiswaCust.value : '') : (elSiswaSel ? elSiswaSel.value : '');
            nisnVal = document.getElementById('pinjam_nisn')?.value || '';
            taVal = document.getElementById('pinjam_tahun_ajaran')?.value || '2026/2027';

            if (!siswaVal || !siswaVal.trim()) {
                showNotification('Pilih atau masukkan nama Siswa Peminjam!', 'warning');
                return;
            }
        }

        formData.append('guru_peminjam', guruVal);
        formData.append('untuk_siswa', isUntukSiswa ? '1' : '0');
        formData.append('peminjam', siswaVal);
        formData.append('nisn', nisnVal);
        formData.append('tahun_ajaran', taVal);
        formData.append('jumlah', document.getElementById('pinjam_jumlah')?.value || '1');
        formData.append('tugas', document.getElementById('pinjam_tugas')?.value || '');
        formData.append('tanggal_pinjam', document.getElementById('pinjam_tanggal_pinjam')?.value || '');
        formData.append('tanggal_kembali', document.getElementById('pinjam_tanggal_kembali')?.value || '');
        formData.append('status', document.getElementById('pinjam_status')?.value || 'dipinjam');
    } else if (actionName === 'Guru') {
        apiAction = 'save_guru';
        formData.append('id', document.getElementById('guru_edit_id')?.value || '');
        formData.append('nama_guru', document.getElementById('guru_nama_guru')?.value || '');
        formData.append('nama_pengguna', document.getElementById('guru_nama_pengguna')?.value || '');
        formData.append('token', document.getElementById('guru_token')?.value || '');
        formData.append('mengajar', document.getElementById('guru_mengajar')?.value || 'bengkel');
        formData.append('jurusan_id', document.getElementById('guru_jurusan_id')?.value || '');
    } else if (actionName === 'Siswa') {
        apiAction = 'save_siswa';
        formData.append('id', document.getElementById('siswa_edit_id')?.value || '');
        formData.append('nisn', document.getElementById('siswa_nisn')?.value || '');
        formData.append('nama_siswa', document.getElementById('siswa_nama_siswa')?.value || '');
        formData.append('nama_lengkap', document.getElementById('siswa_nama_lengkap')?.value || '');
        formData.append('token', document.getElementById('siswa_token')?.value || '');
        formData.append('kelas', document.getElementById('siswa_kelas')?.value || '');
        formData.append('jurusan_id', document.getElementById('siswa_jurusan_id')?.value || '');
        formData.append('tahun_ajaran', document.getElementById('siswa_tahun_ajaran')?.value || '2026/2027');
    }

    if (apiAction) {
        formData.append('action', apiAction);
        isFormSubmitting = true;
        try {
            const res = await fetch('api.php', {
                method: 'POST',
                body: formData
            });
            const data = await res.json();
            if (data.success) {
                showToast(data.message, 'success');
                const activeBtn = document.querySelector('.nav-tab-btn.active');
                if (activeBtn) {
                    const activeTab = activeBtn.getAttribute('data-tab');
                    if (activeTab) {
                        const url = new URL(window.location.href);
                        url.searchParams.set('tab', activeTab);
                        url.searchParams.delete('id');
                        history.replaceState(null, '', url.pathname + '?' + url.searchParams.toString());
                    }
                }
                if (modal) closeModal(modal.id);
                setTimeout(() => location.reload(), 800);
            } else {
                showToast(data.message || 'Gagal menyimpan data.', 'error');
                isFormSubmitting = false;
            }
        } catch (e) {
            console.error(e);
            showToast('Terjadi kesalahan koneksi server.', 'error');
            isFormSubmitting = false;
        }
    } else {
        showToast('Data ' + actionName + ' berhasil disimpan!', 'success');
        if (modal) closeModal(modal.id);
    }
}

// Fungsi khusus untuk tombol Edit: membaca data dari atribut data-edit
function openEditModal(btn) {
    const modalId = btn.getAttribute('data-modal');
    const title = btn.getAttribute('data-title');
    const editJson = btn.getAttribute('data-edit');
    let editData = null;
    try {
        editData = JSON.parse(editJson);
    } catch (e) {
        console.error('Gagal parse data edit:', e);
    }
    openModal(modalId, title, editData);
}

// Unduh Template CSV Contoh
function downloadTemplateBarangCSV() {
    const templateContent = "kode_barang,nama_barang,merek,barcode,stok_total\n" +
        "BRG-101,Switch TP-Link 16 Port,TP-Link,899200100101,15\n" +
        "BRG-102,Kabel UTP Cat6 305m,Belden,899200100102,5\n" +
        "BRG-103,Crimping Tool RJ45,Krisbow,899200100103,10";
    
    const blob = new Blob(["\uFEFF" + templateContent], { type: "text/csv;charset=utf-8;" });
    const link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = "template_import_barang.csv";
    link.click();
}

// Proses submit file CSV
async function handleImportCSVSubmit(event) {
    event.preventDefault();
    const fileInput = document.getElementById('import_csv_file');
    if (!fileInput || !fileInput.files.length) {
        showToast('Pilih file CSV terlebih dahulu!', 'error');
        return;
    }

    const file = fileInput.files[0];
    const formData = new FormData();
    formData.append('action', 'import_barang_csv');
    formData.append('csv_file', file);

    const csrfInput = document.querySelector('input[name="csrf_token"]');
    if (csrfInput) formData.append('csrf_token', csrfInput.value);

    try {
        const res = await fetch('api.php', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();
        if (data.success) {
            showToast(data.message, 'success');
            closeModal('modalImportBarang');
            setTimeout(() => location.reload(), 800);
        } else {
            showToast(data.message || 'Gagal mengimpor data.', 'error');
        }
    } catch (e) {
        console.error(e);
        showToast('Terjadi kesalahan koneksi server saat impor.', 'error');
    }
}

// Tutup modal ketika mengklik area luar form (backdrop click)
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.fixed.inset-0').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal(this.id);
            }
        });
    });
});
// Helper fungsi konfirmasi custom modal (ganti confirm default browser)
let pendingConfirmCallback = null;

function handleConfirmSubmit(event) {
    if (event) event.preventDefault();
    if (typeof pendingConfirmCallback === 'function') {
        const cb = pendingConfirmCallback;
        pendingConfirmCallback = null;
        closeModal('modalConfirmDelete');
        cb();
    }
}

function confirmDeleteAction() {
    handleConfirmSubmit();
}

function showDeleteConfirm(entityName, onConfirm) {
    const titleElem = document.getElementById('confirmModalTitle');
    const msgElem = document.getElementById('confirmModalMessage');
    const iconBox = document.getElementById('confirmIconContainer');
    const submitBtn = document.getElementById('confirmModalSubmitBtn');
    const fotoContainer = document.getElementById('confirmModalFotoContainer');

    if (fotoContainer) fotoContainer.classList.add('hidden');

    if (titleElem) titleElem.innerText = 'Konfirmasi Hapus Data';
    if (msgElem) msgElem.innerHTML = `Apakah Anda yakin ingin menghapus <strong class="text-slate-800 font-bold">${entityName}</strong>? Tindakan ini tidak dapat dibatalkan.`;
    
    if (iconBox) {
        iconBox.className = 'w-14 h-14 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-3.5 shadow-sm';
        iconBox.innerHTML = '<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>';
    }
    
    if (submitBtn) {
        submitBtn.innerText = 'Ya, Hapus Data';
        submitBtn.className = 'px-4 py-2 bg-red-600 text-white font-bold text-xs rounded-xl shadow-md shadow-red-600/20 hover:bg-red-700 transition-colors w-1/2';
    }

    pendingConfirmCallback = onConfirm;
    openModal('modalConfirmDelete');
}

function showLoginConfirm(username, onConfirm) {
    const titleElem = document.getElementById('confirmModalTitle');
    const msgElem = document.getElementById('confirmModalMessage');
    const iconBox = document.getElementById('confirmIconContainer');
    const submitBtn = document.getElementById('confirmModalSubmitBtn');
    const fotoContainer = document.getElementById('confirmModalFotoContainer');

    if (fotoContainer) fotoContainer.classList.add('hidden');

    if (titleElem) titleElem.innerText = 'Konfirmasi Beralih Akun';
    if (msgElem) msgElem.innerHTML = `Apakah Anda yakin ingin beralih dan login ke akun <strong class="text-slate-800 font-bold">${username}</strong>?`;

    if (iconBox) {
        iconBox.className = 'w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto mb-3.5 shadow-sm';
        iconBox.innerHTML = '<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>';
    }

    if (submitBtn) {
        submitBtn.innerText = 'Ya, Login Akun';
        submitBtn.className = 'px-4 py-2 bg-emerald-600 text-white font-bold text-xs rounded-xl shadow-md shadow-emerald-600/20 hover:bg-emerald-700 transition-colors w-1/2';
    }

    pendingConfirmCallback = onConfirm;
    openModal('modalConfirmDelete');
}

function showActionConfirm(title, message, btnText, btnColorClass, onConfirm) {
    const titleElem = document.getElementById('confirmModalTitle');
    const msgElem = document.getElementById('confirmModalMessage');
    const iconBox = document.getElementById('confirmIconContainer');
    const submitBtn = document.getElementById('confirmModalSubmitBtn');
    const fotoContainer = document.getElementById('confirmModalFotoContainer');

    if (fotoContainer) fotoContainer.classList.add('hidden');

    if (titleElem) titleElem.innerText = title;
    if (msgElem) msgElem.innerHTML = message;

    if (iconBox) {
        iconBox.className = 'w-14 h-14 rounded-2xl bg-sage-600 text-white flex items-center justify-center mx-auto mb-3.5';
    }

    if (submitBtn) {
        submitBtn.innerText = btnText || 'Ya, Lanjutkan';
        submitBtn.className = btnColorClass || 'px-4 py-2 bg-sage-600 text-white font-bold text-xs rounded-xl hover:bg-sage-700 transition-colors w-1/2';
    }

    pendingConfirmCallback = onConfirm;
    openModal('modalConfirmDelete');
}

function confirmDeleteAction() {
    if (typeof pendingConfirmCallback === 'function') {
        const cb = pendingConfirmCallback;
        pendingConfirmCallback = null;
        closeModal('modalConfirmDelete');
        cb();
    }
}

function editBarangMasuk(id) {
    const item = (window.dbBarangMasuk || []).find(x => String(x.id) === String(id));
    if (item) {
        setUrlParam('tab', 'barang-masuk');
        setUrlParam('id', id);
        openModal('modalBarangMasuk', 'Edit Transaksi Barang Masuk', item);
    }
}

function deleteBarangMasuk(id, name) {
    showDeleteConfirm('transaksi barang masuk ' + name, async () => {
        const formData = new FormData();
        formData.append('action', 'delete_barang_masuk');
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
            showToast('Kesalahan server saat menghapus data.', 'error');
        }
    });
}

function editPengguna(id) {
    const item = (window.dbPengguna || []).find(x => String(x.id) === String(id));
    if (item) {
        setUrlParam('tab', 'pengguna');
        setUrlParam('id', id);
        openModal('modalPengguna', 'Edit Data Pengguna', item);
    }
}

function loginAsUser(userId, username) {
    showLoginConfirm(username, async () => {
        const formData = new FormData();
        formData.append('action', 'login_as_user');
        formData.append('user_id', userId);
        const csrfInput = document.querySelector('input[name="csrf_token"]');
        if (csrfInput) formData.append('csrf_token', csrfInput.value);

        try {
            const res = await fetch('api.php', { method: 'POST', body: formData });
            const data = await res.json();
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => window.location.href = data.redirect || 'index.php', 600);
            } else {
                showToast(data.message || 'Gagal beralih ke akun tersebut.', 'error');
            }
        } catch (e) {
            showToast('Kesalahan server saat beralih akun.', 'error');
        }
    });
}

function revertImpersonation() {
    showLoginConfirm('Admin Sekolah', async () => {
        const formData = new FormData();
        formData.append('action', 'revert_impersonation');
        const csrfInput = document.querySelector('input[name="csrf_token"]');
        if (csrfInput) formData.append('csrf_token', csrfInput.value);

        try {
            const res = await fetch('api.php', { method: 'POST', body: formData });
            const data = await res.json();
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => window.location.href = data.redirect || 'index.php', 600);
            } else {
                showToast(data.message || 'Gagal kembali ke Admin Sekolah.', 'error');
            }
        } catch (e) {
            showToast('Kesalahan server saat kembali ke Admin Sekolah.', 'error');
        }
    });
}

function deletePengguna(id, name) {
    showDeleteConfirm('pengguna ' + name, async () => {
        const formData = new FormData();
        formData.append('action', 'delete_pengguna');
        formData.append('id', id);
        const csrfInput = document.querySelector('input[name="csrf_token"]');
        if (csrfInput) formData.append('csrf_token', csrfInput.value);
        try {
            const res = await fetch('api.php', { method: 'POST', body: formData });
            const data = await res.json();
            if (data.success) {
                showToast(data.message, 'success');
                if (data.is_self_deleted || data.redirect) {
                    setTimeout(() => window.location.href = data.redirect || 'login.php', 800);
                } else {
                    setTimeout(() => location.reload(), 800);
                }
            } else {
                showToast(data.message || 'Gagal menghapus pengguna.', 'error');
            }
        } catch (e) {
            showToast('Kesalahan server saat menghapus data pengguna.', 'error');
        }
    });
}

function editBarangKeluar(id) {
    const item = (window.dbBarangKeluar || []).find(x => String(x.id) === String(id));
    if (item) {
        setUrlParam('tab', 'barang-keluar');
        setUrlParam('id', id);
        openModal('modalBarangKeluar', 'Edit Transaksi Barang Keluar', item);
    }
}

function deleteBarangKeluar(id, name) {
    showDeleteConfirm('transaksi barang keluar ' + name, async () => {
        const formData = new FormData();
        formData.append('action', 'delete_barang_keluar');
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
            showToast('Kesalahan server saat menghapus data.', 'error');
        }
    });
}

function editPeminjaman(id) {
    const item = (window.dbPeminjaman || []).find(x => String(x.id) === String(id));
    if (item) {
        setUrlParam('tab', 'peminjaman');
        setUrlParam('id', id);
        openModal('modalPeminjaman', 'Edit Transaksi Peminjaman', item);
    }
}

function deletePeminjaman(id, name) {
    showDeleteConfirm('transaksi peminjaman oleh ' + name, async () => {
        const formData = new FormData();
        formData.append('action', 'delete_peminjaman');
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
            showToast('Kesalahan server saat menghapus data.', 'error');
        }
    });
}

function kembalikanPeminjaman(id) {
    const item = (window.dbPeminjaman || []).find(x => String(x.id) === String(id));
    const idInput = document.getElementById('confirm_kembali_id');
    const fileInput = document.getElementById('confirm_kembali_bukti_foto');
    const msgElem = document.getElementById('confirmKembaliMessage');

    if (idInput) idInput.value = id;
    if (fileInput) fileInput.value = '';

    if (item && msgElem) {
        const jml = (item.jumlah || 1) + ' ' + (item.satuan || 'Unit');
        const peminjam = item.nama_peminjam || item.guru_peminjam || 'Peminjam';
        const esc = typeof escapeHtml === 'function' ? escapeHtml : (s => s);
        msgElem.innerHTML = `<span class="font-bold text-slate-800 dark:text-slate-100">${esc(item.nama_barang || 'Alat')}</span> (${jml}) &bull; ${esc(peminjam)}`;
    }

    openModal('modalConfirmPengembalian');
}

async function executeKembalikanPeminjaman(event) {
    event.preventDefault();
    const id = document.getElementById('confirm_kembali_id')?.value;
    const fileInput = document.getElementById('confirm_kembali_bukti_foto');
    if (!id) return;

    const formData = new FormData();
    formData.append('action', 'return_peminjaman');
    formData.append('id', id);
    const csrfInput = document.querySelector('input[name="csrf_token"]');
    if (csrfInput) formData.append('csrf_token', csrfInput.value);

    // Bukti foto opsional (bisa diisi atau NULL)
    if (fileInput && fileInput.files && fileInput.files.length > 0) {
        formData.append('bukti_foto', fileInput.files[0]);
    }

    try {
        const res = await fetch('api.php', { method: 'POST', body: formData });
        const data = await res.json();
        if (data.success) {
            showToast(data.message, 'success');
            closeModal('modalConfirmPengembalian');
            setTimeout(() => location.reload(), 800);
        } else {
            showToast(data.message || 'Gagal mengonfirmasi pengembalian.', 'error');
        }
    } catch (e) {
        showToast('Kesalahan server saat memproses pengembalian.', 'error');
    }
}

function showFotoPreview(srcUrl, title = '', caption = '') {
    const imgElem = document.getElementById('previewFotoImg');
    const titleBox = document.getElementById('previewFotoTitleBox');
    const titleElem = document.getElementById('previewFotoTitle');
    const captionElem = document.getElementById('previewFotoCaption');

    if (imgElem) imgElem.src = srcUrl;
    if (titleElem) {
        titleElem.textContent = title || '';
        if (titleBox) {
            if (title && String(title).trim()) {
                titleBox.classList.remove('hidden');
                titleBox.classList.add('flex');
            } else {
                titleBox.classList.add('hidden');
                titleBox.classList.remove('flex');
            }
        }
    }
    if (captionElem) captionElem.textContent = caption;

    openModal('modalFotoPreview');
}

function downloadFotoPreview() {
    const imgElem = document.getElementById('previewFotoImg');
    if (!imgElem || !imgElem.src) {
        showToast('Foto belum tersedia untuk diunduh.', 'error');
        return;
    }

    const titleElem = document.getElementById('previewFotoTitle');
    let prefix = 'foto';
    if (titleElem && titleElem.textContent && titleElem.textContent.trim()) {
        prefix = titleElem.textContent.trim().toLowerCase().replace(/[^a-z0-9]+/g, '_');
    }
    const filename = prefix + '_' + Date.now() + '.png';

    const src = imgElem.src;
    if (src.startsWith('data:')) {
        const a = document.createElement('a');
        a.href = src;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        showToast('Mengunduh foto...', 'success');
    } else {
        fetch(src)
            .then(res => res.blob())
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);
                showToast('Mengunduh foto...', 'success');
            })
            .catch(err => {
                console.error(err);
                const a = document.createElement('a');
                a.href = src;
                a.target = '_blank';
                a.download = filename;
                a.click();
            });
    }
}

function updateBatchDeleteBar() {
    const activeTabContent = document.querySelector('.tab-content:not(.hidden)');
    if (!activeTabContent) return;
    
    const activeTable = activeTabContent.querySelector('table');
    if (!activeTable) return;

    const checkedBoxes = activeTable.querySelectorAll('.row-checkbox:checked');
    const toast = document.getElementById('batchDeleteToast');
    const countText = document.getElementById('batchDeleteCountText');

    if (checkedBoxes.length > 0) {
        if (countText) countText.innerText = `${checkedBoxes.length} data dipilih`;
        if (toast) {
            toast.classList.remove('hidden');
            toast.classList.add('flex');
        }
    } else {
        if (toast) {
            toast.classList.add('hidden');
            toast.classList.remove('flex');
        }
    }

    const selectAllBox = activeTable.querySelector('.select-all-checkbox');
    const visibleRowCheckboxes = Array.from(activeTable.querySelectorAll('tbody tr'))
        .filter(tr => tr.style.display !== 'none')
        .map(tr => tr.querySelector('.row-checkbox'))
        .filter(Boolean);

    if (selectAllBox && visibleRowCheckboxes.length > 0) {
        const visibleCheckedCount = visibleRowCheckboxes.filter(cb => cb.checked).length;
        selectAllBox.checked = (visibleCheckedCount === visibleRowCheckboxes.length);
    } else if (selectAllBox) {
        selectAllBox.checked = false;
    }
}

function toggleSelectAll(selectAllCheckbox) {
    const table = selectAllCheckbox.closest('table');
    if (!table) return;

    const rowCheckboxes = table.querySelectorAll('.row-checkbox');
    if (!selectAllCheckbox.checked) {
        rowCheckboxes.forEach(cb => {
            cb.checked = false;
        });
    } else {
        rowCheckboxes.forEach(cb => {
            const tr = cb.closest('tr');
            if (tr && tr.style.display !== 'none') {
                cb.checked = true;
            }
        });
    }
    updateBatchDeleteBar();
}

function clearBatchSelection() {
    document.querySelectorAll('.row-checkbox, .select-all-checkbox').forEach(cb => {
        cb.checked = false;
    });
    updateBatchDeleteBar();
}

async function executeBatchDelete() {
    const activeTabContent = document.querySelector('.tab-content:not(.hidden)');
    if (!activeTabContent) return;

    const activeTable = activeTabContent.querySelector('table');
    if (!activeTable) return;

    const checkedBoxes = Array.from(activeTable.querySelectorAll('.row-checkbox:checked'));
    if (checkedBoxes.length === 0) return;

    const selectedIds = checkedBoxes.map(cb => cb.value);
    const tableId = activeTable.id;

    let action = '';
    let entityName = '';

    if (tableId === 'tableJurusan') {
        action = 'bulk_delete_jurusan';
        entityName = selectedIds.length + ' data jurusan';
    } else if (tableId === 'tableGuru') {
        action = 'bulk_delete_guru';
        entityName = selectedIds.length + ' data guru';
    } else if (tableId === 'tableSiswa') {
        action = 'bulk_delete_siswa';
        entityName = selectedIds.length + ' data siswa';
    } else if (tableId === 'tablePengguna') {
        action = 'bulk_delete_pengguna';
        entityName = selectedIds.length + ' data pengguna';
    } else if (tableId === 'tableKategori') {
        action = 'bulk_delete_kategori';
        entityName = selectedIds.length + ' kategori barang';
    } else if (tableId === 'tableRak') {
        action = 'bulk_delete_rak';
        entityName = selectedIds.length + ' data rak';
    } else if (tableId === 'tableBarang') {
        action = 'bulk_delete_barang';
        entityName = selectedIds.length + ' data barang';
    } else if (tableId === 'tableBarangMasuk') {
        action = 'bulk_delete_barang_masuk';
        entityName = selectedIds.length + ' transaksi barang masuk';
    } else if (tableId === 'tableBarangKeluar') {
        action = 'bulk_delete_barang_keluar';
        entityName = selectedIds.length + ' transaksi barang keluar';
    } else if (tableId === 'tablePeminjaman') {
        action = 'bulk_delete_peminjaman';
        entityName = selectedIds.length + ' transaksi peminjaman';
    }

    showDeleteConfirm(entityName, async () => {
        const formData = new FormData();
        formData.append('action', action);
        formData.append('ids', JSON.stringify(selectedIds));
        const csrfInput = document.querySelector('input[name="csrf_token"]');
        if (csrfInput) formData.append('csrf_token', csrfInput.value);

        try {
            const res = await fetch('api.php', { method: 'POST', body: formData });
            const data = await res.json();
            if (data.success) {
                clearBatchSelection();
                showToast(data.message, 'success');
                if (data.is_self_deleted || data.redirect) {
                    setTimeout(() => window.location.href = data.redirect || 'login.php', 800);
                } else {
                    setTimeout(() => location.reload(), 800);
                }
            } else {
                showToast(data.message || 'Gagal menghapus data.', 'error');
            }
        } catch (e) {
            showToast('Kesalahan server saat menghapus data secara massal.', 'error');
        }
    });
}

function editRak(id) {
    const item = (window.dbRak || []).find(x => String(x.id) === String(id));
    if (item) {
        setUrlParam('tab', 'rak');
        setUrlParam('id', id);
        openModal('modalRak', 'Edit Data Rak Penyimpanan', item);
    }
}

function deleteRak(id, name) {
    showDeleteConfirm('rak penyimpanan ' + name, async () => {
        const formData = new FormData();
        formData.append('action', 'delete_rak');
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
                showToast(data.message || 'Gagal menghapus data rak.', 'error');
            }
        } catch (e) {
            showToast('Kesalahan server saat menghapus data rak.', 'error');
        }
    });
}

async function showBarangInRakModal(rakId, rakName) {
    const titleElem = document.getElementById('lihatRakTitle');
    const subtitleElem = document.getElementById('lihatRakSubtitle');
    const tbody = document.getElementById('lihatRakBarangTbody');

    if (titleElem) titleElem.innerText = 'Daftar Barang di ' + rakName;
    if (subtitleElem) subtitleElem.innerText = 'Barang inventaris yang tersimpan di dalam ' + rakName;
    if (tbody) tbody.innerHTML = '<tr><td colspan="6" class="py-4 text-center text-slate-400 font-semibold">Memuat data barang...</td></tr>';

    openModal('modalLihatBarangRak');

    try {
        const res = await fetch(`api.php?action=get_items_in_rak&rak_id=${encodeURIComponent(rakId)}`);
        const data = await res.json();
        if (data.success && data.items) {
            if (data.items.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="py-6 text-center text-slate-400 font-medium italic">Belum ada barang yang disimpan di rak ini.</td></tr>';
            } else {
                tbody.innerHTML = data.items.map((b, idx) => `
                    <tr class="hover:bg-sage-50/50">
                        <td class="py-3 px-4 text-center font-bold text-slate-500">${idx + 1}</td>
                        <td class="py-3 px-4 font-bold text-slate-800">${b.nama_barang}</td>
                        <td class="py-3 px-4 font-semibold text-slate-700">${b.nama_kategori || '-'}</td>
                        <td class="py-3 px-4 font-mono text-sage-700">${b.barcode || '-'}</td>
                        <td class="py-3 px-4 text-center font-extrabold text-sage-600">${b.stok_tersedia} / ${b.stok_total} ${b.satuan || 'Unit'}</td>
                        <td class="py-3 px-4 text-center">
                            <button type="button" onclick="navigateToBarangRow('${b.id}')" class="px-3 py-1.5 rounded-xl bg-sage-600 hover:bg-sage-700 text-white font-bold text-xs shadow-md shadow-sage-600/20 transition-all flex items-center justify-center gap-1.5 mx-auto">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <span>View</span>
                            </button>
                        </td>
                    </tr>
                `).join('');
            }
        } else {
            tbody.innerHTML = '<tr><td colspan="6" class="py-4 text-center text-red-500 font-semibold">Gagal memuat data barang rak.</td></tr>';
        }
    } catch (e) {
        tbody.innerHTML = '<tr><td colspan="6" class="py-4 text-center text-red-500 font-semibold">Terjadi kesalahan koneksi server.</td></tr>';
    }
}

function navigateToBarangRow(barangId) {
    closeModal('modalLihatBarangRak');
    switchTab('barang');

    setTimeout(() => {
        const table = document.getElementById('tableBarang');
        if (!table) return;

        const paginator = window.tablePaginators ? window.tablePaginators['tableBarang'] : null;
        if (paginator) {
            paginator.searchQuery = '';
            const searchInput = paginator.cardHeader ? paginator.cardHeader.querySelector('input[type="text"]') : null;
            if (searchInput) searchInput.value = '';
            paginator.update();
        }

        let targetRow = document.getElementById('row-barang-' + barangId) || table.querySelector(`input[value="${barangId}"]`)?.closest('tr');

        if (paginator && targetRow) {
            const allRows = Array.from(table.querySelectorAll('tbody tr'));
            const rowIndex = allRows.indexOf(targetRow);
            if (rowIndex !== -1) {
                const targetPage = Math.floor(rowIndex / paginator.pageSize) + 1;
                paginator.currentPage = targetPage;
                paginator.update();
            }
        }

        targetRow = document.getElementById('row-barang-' + barangId) || table.querySelector(`input[value="${barangId}"]`)?.closest('tr');
        if (targetRow) {
            targetRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
            targetRow.classList.add('bg-emerald-500/20', 'dark:bg-emerald-500/30', 'transition-all', 'duration-500');
            setTimeout(() => {
                targetRow.classList.remove('bg-emerald-500/20', 'dark:bg-emerald-500/30');
            }, 3000);
        }
    }, 150);
}

function showApproveConfirm(itemName, onConfirm) {
    const titleElem = document.getElementById('confirmModalTitle');
    const msgElem = document.getElementById('confirmModalMessage');
    const iconBox = document.getElementById('confirmIconContainer');
    const submitBtn = document.getElementById('confirmModalSubmitBtn');
    const fotoContainer = document.getElementById('confirmModalFotoContainer');

    if (fotoContainer) fotoContainer.classList.add('hidden');

    if (titleElem) titleElem.innerText = 'Konfirmasi Setujui Pengembalian';
    if (msgElem) msgElem.innerHTML = `Apakah Anda yakin ingin menyetujui pengembalian <strong class="text-slate-800 font-bold">${itemName}</strong>? Stok barang akan otomatis dikembalikan ke inventaris gudang.`;

    if (iconBox) {
        iconBox.className = 'w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto mb-3.5 shadow-sm';
        iconBox.innerHTML = '<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
    }

    if (submitBtn) {
        submitBtn.innerText = 'Ya, Setujui';
        submitBtn.className = 'px-4 py-2 bg-emerald-600 text-white font-bold text-xs rounded-xl shadow-md shadow-emerald-600/20 hover:bg-emerald-700 transition-colors w-1/2';
    }

    pendingConfirmCallback = onConfirm;
    openModal('modalConfirmDelete');
}

function approvePeminjaman(id) {
    const item = (window.dbPeminjaman || []).find(x => String(x.id) === String(id));
    const itemName = item ? `barang/alat "${item.nama_barang || 'ini'}" (${item.jumlah || 1} ${item.satuan || 'Unit'}) oleh ${item.nama_peminjam || 'peminjam'}` : 'pengembalian barang ini';

    showApproveConfirm(itemName, async () => {
        const formData = new FormData();
        formData.append('action', 'approve_peminjaman');
        formData.append('id', id);
        const csrfInput = document.querySelector('input[name="csrf_token"]');
        if (csrfInput) formData.append('csrf_token', csrfInput.value);

        try {
            const res = await fetch('api.php', { method: 'POST', body: formData });
            const data = await res.json();
            if (data.success) {
                showToast(data.message || 'Pengembalian barang berhasil disetujui!', 'success');
                setTimeout(() => location.reload(), 800);
            } else {
                showToast(data.message || 'Gagal menyetujui pengembalian.', 'error');
            }
        } catch (err) {
            console.error(err);
            showToast('Terjadi kesalahan koneksi server.', 'error');
        }
    });
}

function showRejectConfirm(itemName, onConfirm) {
    const titleElem = document.getElementById('confirmModalTitle');
    const msgElem = document.getElementById('confirmModalMessage');
    const iconBox = document.getElementById('confirmIconContainer');
    const submitBtn = document.getElementById('confirmModalSubmitBtn');
    const fotoContainer = document.getElementById('confirmModalFotoContainer');

    if (fotoContainer) fotoContainer.classList.add('hidden');

    if (titleElem) titleElem.innerText = 'Konfirmasi Tolak Pengembalian';
    if (msgElem) msgElem.innerHTML = `Apakah Anda yakin ingin menolak pengembalian <strong class="text-slate-800 font-bold">${itemName}</strong>? Status akan menjadi Ditolak dan peminjam diminta mengunggah foto bukti ulang.`;

    if (iconBox) {
        iconBox.className = 'w-14 h-14 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-3.5 shadow-sm';
        iconBox.innerHTML = '<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>';
    }

    if (submitBtn) {
        submitBtn.innerText = 'Ya, Tolak';
        submitBtn.className = 'px-4 py-2 bg-red-600 text-white font-bold text-xs rounded-xl shadow-md shadow-red-600/20 hover:bg-red-700 transition-colors w-1/2';
    }

    pendingConfirmCallback = onConfirm;
    openModal('modalConfirmDelete');
}

function rejectPeminjaman(id) {
    const item = (window.dbPeminjaman || []).find(x => String(x.id) === String(id));
    const itemName = item ? `barang/alat "${item.nama_barang || 'ini'}" (${item.jumlah || 1} ${item.satuan || 'Unit'}) oleh ${item.nama_peminjam || 'peminjam'}` : 'pengembalian barang ini';

    showRejectConfirm(itemName, async () => {
        const formData = new FormData();
        formData.append('action', 'reject_peminjaman');
        formData.append('id', id);
        const csrfInput = document.querySelector('input[name="csrf_token"]');
        if (csrfInput) formData.append('csrf_token', csrfInput.value);

        try {
            const res = await fetch('api.php', { method: 'POST', body: formData });
            const data = await res.json();
            if (data.success) {
                showToast(data.message || 'Pengembalian barang ditolak.', 'success');
                setTimeout(() => location.reload(), 800);
            } else {
                showToast(data.message || 'Gagal menolak pengembalian.', 'error');
            }
        } catch (err) {
            console.error(err);
            showToast('Terjadi kesalahan koneksi server.', 'error');
        }
    });
}

function handleStatusPenggunaChange(val) {
    const groupGuru = document.getElementById('field_group_pilih_guru');
    const groupSiswa = document.getElementById('field_group_pilih_siswa');
    const groupToken = document.getElementById('field_group_token_siswa');
    const tokenLabel = document.getElementById('pengguna_token_label');
    const groupPassword = document.getElementById('field_group_password');
    const elPeran = document.getElementById('pengguna_peran');

    if (groupGuru) groupGuru.classList.toggle('hidden', val !== 'guru');
    if (groupSiswa) groupSiswa.classList.toggle('hidden', val !== 'siswa');
    if (groupToken) groupToken.classList.toggle('hidden', val !== 'siswa' && val !== 'guru');

    if (tokenLabel) {
        tokenLabel.innerText = val === 'guru' ? 'Token Login Guru (Password)' : 'Token Login Siswa (Password)';
    }

    // Sembunyikan field kata sandi jika status adalah siswa atau guru
    if (groupPassword) {
        groupPassword.classList.toggle('hidden', val === 'siswa' || val === 'guru');
    }

    if (val === 'siswa') {
        if (elPeran) elPeran.value = 'siswa';
        const elSiswaId = document.getElementById('pengguna_siswa_id');
        if (elSiswaId && elSiswaId.value) onSiswaSelectedInUserForm(elSiswaId.value);
    } else if (val === 'guru') {
        const elGuruId = document.getElementById('pengguna_guru_id');
        if (elGuruId && elGuruId.value) onGuruSelectedInUserForm(elGuruId.value);
    }
}

function onGuruSelectedInUserForm(guruId) {
    if (!guruId || !window.dbGuru) return;
    const guru = window.dbGuru.find(g => String(g.id) === String(guruId));
    if (guru) {
        const elUsername = document.getElementById('pengguna_nama_pengguna');
        const elNamaLengkap = document.getElementById('pengguna_nama_lengkap');
        const elJurusan = document.getElementById('pengguna_jurusan_id');
        const elPeran = document.getElementById('pengguna_peran');
        const elToken = document.getElementById('pengguna_token');

        if (elNamaLengkap) elNamaLengkap.value = guru.nama_guru;
        if (elUsername) {
            const cleanUser = guru.nama_guru.toLowerCase().replace(/[^a-z0-9]/g, '');
            elUsername.value = cleanUser;
        }
        if (elToken) elToken.value = guru.token || '';

        // Aturan Peran & Jurusan Guru:
        // Guru Bengkel -> Peran: Kabeng (Kepala Bengkel) ('kabeng'), Jurusan: jurusan_id guru
        // Guru Umum -> Peran: Guru Umum ('guru_umum'), Jurusan: "" (Tidak Ada)
        if (guru.mengajar === 'bengkel') {
            if (elPeran) elPeran.value = 'kabeng';
            if (elJurusan && guru.jurusan_id) elJurusan.value = guru.jurusan_id;
        } else {
            if (elPeran) elPeran.value = 'guru_umum';
            if (elJurusan) elJurusan.value = '';
        }
    }
}

function onSiswaSelectedInUserForm(siswaId) {
    if (!siswaId || !window.dbSiswa) return;
    const siswa = window.dbSiswa.find(s => String(s.id) === String(siswaId));
    if (siswa) {
        const elUsername = document.getElementById('pengguna_nama_pengguna');
        const elNamaLengkap = document.getElementById('pengguna_nama_lengkap');
        const elToken = document.getElementById('pengguna_token');
        const elJurusan = document.getElementById('pengguna_jurusan_id');
        const elPeran = document.getElementById('pengguna_peran');

        if (elNamaLengkap) elNamaLengkap.value = siswa.nama_lengkap || siswa.nama_siswa;
        if (elUsername) {
            const cleanUser = siswa.nama_siswa.toLowerCase().replace(/[^a-z0-9]/g, '');
            elUsername.value = cleanUser;
        }
        if (elToken) elToken.value = siswa.token || '';
        if (elJurusan && siswa.jurusan_id) {
            elJurusan.value = siswa.jurusan_id;
        }
        if (elPeran) elPeran.value = 'siswa';
    }
}

function autoFormatNamaSiswaUsername(val) {
    const elUsername = document.getElementById('siswa_nama_siswa');
    if (elUsername) {
        elUsername.value = String(val).toLowerCase().replace(/[^a-z0-9]/g, '');
    }
}

function toggleGuruPeminjamMode(val) {
    const elCustom = document.getElementById('pinjam_guru_peminjam_custom');
    if (!elCustom) return;
    if (val === '__custom__') {
        elCustom.classList.remove('hidden');
        elCustom.focus();
    } else {
        elCustom.classList.add('hidden');
        elCustom.value = '';
    }
}

function togglePinjamUntukSiswa(isChecked) {
    const sec = document.getElementById('section_siswa_peminjam');
    const badge = document.getElementById('pinjam_untuk_siswa_badge');
    const selSiswa = document.getElementById('pinjam_peminjam_select');
    const elNisn = document.getElementById('pinjam_nisn');
    const elTA = document.getElementById('pinjam_tahun_ajaran');

    if (isChecked) {
        if (sec) sec.classList.remove('hidden');
        if (badge) {
            badge.textContent = 'Ya (Peminjam Siswa)';
            badge.className = 'text-xs font-bold text-sage-600 dark:text-amber-400';
        }
        if (selSiswa) selSiswa.required = false;
    } else {
        if (sec) sec.classList.add('hidden');
        if (badge) {
            badge.textContent = 'Tidak (Guru Langsung)';
            badge.className = 'text-xs font-medium text-slate-400 dark:text-slate-400';
        }
        if (selSiswa) {
            selSiswa.required = false;
            selSiswa.value = '';
        }
        if (window.pinjamSiswaCombobox) {
            window.pinjamSiswaCombobox.clear(false);
        }
        const custSiswa = document.getElementById('pinjam_peminjam_custom');
        if (custSiswa) {
            custSiswa.classList.add('hidden');
            custSiswa.required = false;
            custSiswa.value = '';
        }
        if (elNisn) elNisn.value = '';
        if (elTA) elTA.value = '';
    }
}

function autoFillGuruUsername(val) {
    const elUsername = document.getElementById('guru_nama_pengguna');
    const elEditId = document.getElementById('guru_edit_id');
    if (elUsername && (!elEditId || !elEditId.value)) {
        elUsername.value = (val || '').toLowerCase().replace(/[^a-z0-9]/g, '');
    }
}

function onSiswaSelectedInPeminjamanForm(val) {
    const elCustom = document.getElementById('pinjam_peminjam_custom');
    if (elCustom) {
        if (val === '__custom__') {
            elCustom.classList.remove('hidden');
            elCustom.required = true;
            elCustom.focus();
        } else {
            elCustom.classList.add('hidden');
            elCustom.required = false;
            elCustom.value = '';
        }
    }

    const elNisn = document.getElementById('pinjam_nisn');
    const elTA = document.getElementById('pinjam_tahun_ajaran');

    if (!val || val === '__custom__' || !window.dbSiswa) {
        if (elNisn) elNisn.value = '';
        return;
    }

    const cleanVal = String(val).trim().toLowerCase();
    const siswa = window.dbSiswa.find(s => 
        String(s.nama_siswa).trim().toLowerCase() === cleanVal || 
        String(s.nama_lengkap || '').trim().toLowerCase() === cleanVal ||
        String(s.id) === cleanVal
    );
    if (siswa) {
        const elJurusan = document.getElementById('pinjam_jurusan_id');

        if (elNisn) elNisn.value = siswa.nisn || '-';
        if (elTA) elTA.value = siswa.tahun_ajaran || '2026/2027';
        if (elJurusan && siswa.jurusan_id) {
            elJurusan.value = siswa.jurusan_id;
            if (typeof filterBarangSelectByJurusan === 'function') {
                filterBarangSelectByJurusan('pinjam_barang_id', siswa.jurusan_id);
            }
        }
    } else {
        if (elNisn) elNisn.value = '';
    }
}

function handleGuruMengajarChange(val) {
    const groupJurusan = document.getElementById('group_guru_jurusan');
    if (groupJurusan) {
        groupJurusan.classList.toggle('hidden', val === 'umum');
    }
}

async function generateTokenForUserForm() {
    try {
        const res = await fetch('api.php?action=generate_siswa_token');
        const data = await res.json();
        if (data.success && data.token) {
            const el = document.getElementById('pengguna_token');
            if (el) el.value = data.token;
        }
    } catch (e) {
        console.error(e);
    }
}

async function generateTokenForGuruForm() {
    try {
        const res = await fetch('api.php?action=generate_guru_token');
        const data = await res.json();
        if (data.success && data.token) {
            const el = document.getElementById('guru_token');
            if (el) el.value = data.token;
        }
    } catch (e) {
        console.error(e);
    }
}

async function generateTokenForSiswaForm() {
    try {
        const res = await fetch('api.php?action=generate_siswa_token');
        const data = await res.json();
        if (data.success && data.token) {
            const el = document.getElementById('siswa_token');
            if (el) el.value = data.token;
        }
    } catch (e) {
        console.error(e);
    }
}

function editGuru(id) {
    const item = (window.dbGuru || []).find(x => String(x.id) === String(id));
    if (item) {
        setUrlParam('tab', 'guru');
        setUrlParam('id', id);
        openModal('modalGuru', 'Edit Data Guru', item);
    }
}

function deleteGuru(id, name) {
    showDeleteConfirm('data guru ' + name, async () => {
        const formData = new FormData();
        formData.append('action', 'delete_guru');
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
                showToast(data.message || 'Gagal menghapus data guru.', 'error');
            }
        } catch (e) {
            showToast('Kesalahan server.', 'error');
        }
    });
}

function editSiswa(id) {
    const item = (window.dbSiswa || []).find(x => String(x.id) === String(id));
    if (item) {
        setUrlParam('tab', 'siswa');
        setUrlParam('id', id);
        openModal('modalSiswa', 'Edit Data Siswa', item);
    }
}

function deleteSiswa(id, name) {
    showDeleteConfirm('data siswa ' + name, async () => {
        const formData = new FormData();
        formData.append('action', 'delete_siswa');
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
                showToast(data.message || 'Gagal menghapus data siswa.', 'error');
            }
        } catch (e) {
            showToast('Kesalahan server.', 'error');
        }
    });
}

async function handleImportCSVSubmit(e, type) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    formData.append('action', type === 'guru' ? 'import_guru_csv' : 'import_siswa_csv');
    const fileInput = document.getElementById(type === 'guru' ? 'import_guru_file' : 'import_siswa_file');
    if (fileInput && fileInput.files[0]) {
        formData.append('file_csv', fileInput.files[0]);
    }

    try {
        const res = await fetch('api.php', { method: 'POST', body: formData });
        const data = await res.json();
        if (data.success) {
            showToast(data.message, 'success');
            closeModal(type === 'guru' ? 'modalImportGuruCSV' : 'modalImportSiswaCSV');
            setTimeout(() => location.reload(), 800);
        } else {
            showToast(data.message || 'Gagal mengimpor berkas CSV.', 'error');
        }
    } catch (err) {
        console.error(err);
        showToast('Kesalahan koneksi saat import CSV.', 'error');
    }
}

// --- FOTO / GAMBAR ALAT & BAHAN HELPERS ---
function previewBarangImage(input) {
    if (!input || !input.files || !input.files[0]) return;
    const file = input.files[0];
    if (!file.type || !file.type.startsWith('image/')) {
        showToast('File yang dipilih harus berupa foto/gambar (JPG, PNG, WEBP)!', 'warning');
        input.value = '';
        return;
    }
    const reader = new FileReader();
    reader.onload = function(e) {
        const imgPreview = document.getElementById('barang_image_preview');
        const previewBox = document.getElementById('barang_image_preview_box');
        const imgIcon = document.getElementById('barang_image_placeholder_icon');
        const btnRemove = document.getElementById('btn_remove_barang_image');
        const remInp = document.getElementById('barang_remove_image');

        if (imgPreview) {
            imgPreview.src = e.target.result;
            imgPreview.classList.remove('hidden');
        }
        if (previewBox) {
            previewBox.classList.remove('hidden');
            previewBox.classList.add('flex');
        }
        if (imgIcon) imgIcon.classList.add('hidden');
        if (btnRemove) btnRemove.classList.remove('hidden');
        if (remInp) remInp.value = '0';
    };
    reader.readAsDataURL(file);
}

function removeBarangImage() {
    const imgInput = document.getElementById('barang_image_input');
    const imgPreview = document.getElementById('barang_image_preview');
    const previewBox = document.getElementById('barang_image_preview_box');
    const imgIcon = document.getElementById('barang_image_placeholder_icon');
    const btnRemove = document.getElementById('btn_remove_barang_image');
    const remInp = document.getElementById('barang_remove_image');

    if (imgInput) imgInput.value = '';
    if (remInp) remInp.value = '1';
    if (imgPreview) {
        imgPreview.src = '';
        imgPreview.classList.add('hidden');
    }
    if (previewBox) {
        previewBox.classList.add('hidden');
        previewBox.classList.remove('flex');
    }
    if (imgIcon) imgIcon.classList.remove('hidden');
    if (btnRemove) btnRemove.classList.add('hidden');
}

function showImageModal(imgSrc, title = 'Foto Barang') {
    if (!imgSrc) return;
    showFotoPreview(imgSrc, title);
}

// --- MIGRASI KENAIKAN KELAS SISWA HELPERS ---
function openModalMigrasiSiswa() {
    if (!window.currentUser || window.currentUser.peran !== 'admin_sekolah') {
        showToast('Hanya Admin Sekolah yang memiliki wewenang migrasi kelas siswa!', 'warning');
        return;
    }
    const modal = document.getElementById('modalMigrasiSiswa');
    if (!modal) return;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.classList.add('modal-open');

    const selJurusan = document.getElementById('migrasi_jurusan_id');
    if (selJurusan) {
        selJurusan.removeAttribute('disabled');
        selJurusan.innerHTML = '<option value="">-- Semua Jurusan --</option>' +
            (window.dbJurusan || []).map(j => `<option value="${j.id}">${j.nama_jurusan}</option>`).join('');
    }

    const inpTa = document.getElementById('migrasi_tahun_ajaran');
    if (inpTa) {
        const tas = (window.dbSiswa || []).map(s => s.tahun_ajaran).filter(Boolean);
        let baseTa = tas[0] || '2026/2027';
        const match = baseTa.match(/(\d{4})\/(\d{4})/);
        if (match) {
            const y1 = parseInt(match[1]) + 1;
            const y2 = parseInt(match[2]) + 1;
            inpTa.value = `${y1}/${y2}`;
        } else {
            inpTa.value = '2027/2028';
        }
    }

    updateMigrasiCounts();
}

function updateMigrasiCounts() {
    const selJurusan = document.getElementById('migrasi_jurusan_id');
    const selectedJurusan = selJurusan ? selJurusan.value : '';

    let list = window.dbSiswa || [];
    if (selectedJurusan) {
        list = list.filter(s => String(s.jurusan_id) === String(selectedJurusan));
    }

    let c10 = 0, c11 = 0, c12 = 0;
    list.forEach(s => {
        const k = String(s.kelas || '').trim().toUpperCase();
        if (k.startsWith('12') || k.startsWith('XII-') || k.startsWith('XII ') || k === 'XII') {
            c12++;
        } else if (k.startsWith('11') || k.startsWith('XI-') || k.startsWith('XI ') || k === 'XI') {
            c11++;
        } else if (k.startsWith('10') || k.startsWith('X-') || k.startsWith('X ') || k === 'X') {
            c10++;
        }
    });

    const el10 = document.getElementById('migrasiCountKelas10');
    const el11 = document.getElementById('migrasiCountKelas11');
    const el12 = document.getElementById('migrasiCountKelas12');
    const elTot = document.getElementById('migrasiCountTotal');

    if (el10) el10.textContent = `${c10.toLocaleString()} Siswa`;
    if (el11) el11.textContent = `${c11.toLocaleString()} Siswa`;
    if (el12) el12.textContent = `${c12.toLocaleString()} Siswa`;
    if (elTot) elTot.textContent = (c10 + c11 + c12).toLocaleString();
}

async function handleMigrasiSiswaSubmit(e) {
    e.preventDefault();
    if (!window.currentUser || window.currentUser.peran !== 'admin_sekolah') {
        showToast('Hanya Admin Sekolah yang memiliki wewenang memproses migrasi kelas!', 'warning');
        return;
    }
    const btn = document.getElementById('btnSubmitMigrasiSiswa');
    const selJurusan = document.getElementById('migrasi_jurusan_id');
    const inpTa = document.getElementById('migrasi_tahun_ajaran');

    const ta = inpTa ? inpTa.value.trim() : '';
    const jId = selJurusan ? selJurusan.value : '';

    const elTot = document.getElementById('migrasiCountTotal');
    const totalCount = elTot ? elTot.textContent : '0';

    if (parseInt(totalCount.replace(/[^0-9]/g, '')) === 0) {
        showToast('Tidak ada data siswa yang memenuhi kriteria untuk dimigrasi!', 'warning');
        return;
    }

    if (!confirm(`Konfirmasi Migrasi Kenaikan Kelas:\n\nApakah Anda yakin ingin memproses kenaikan kelas untuk ${totalCount} siswa ke Tahun Ajaran ${ta}?\n\n• Kelas 10 naik ke Kelas 11\n• Kelas 11 naik ke Kelas 12\n• Kelas 12 dialihkan ke status LULUS\n\nTindakan ini tidak dapat dibatalkan secara otomatis.`)) {
        return;
    }

    if (btn) {
        btn.disabled = true;
        btn.innerHTML = `<svg class="w-4 h-4 animate-spin shrink-0" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> <span>Memproses Migrasi...</span>`;
    }

    try {
        const formData = new FormData();
        formData.append('action', 'migrate_kelas_siswa');
        formData.append('csrf_token', document.querySelector('#formMigrasiSiswa input[name="csrf_token"]')?.value || '');
        formData.append('jurusan_id', jId);
        formData.append('tahun_ajaran', ta);

        const res = await fetch('api.php', { method: 'POST', body: formData });
        const data = await res.json();

        if (data && data.success) {
            showToast(data.message, 'success');
            closeModal('modalMigrasiSiswa');
            setTimeout(() => {
                location.reload();
            }, 1200);
        } else {
            showToast(data.message || 'Gagal memproses migrasi kelas siswa.', 'error');
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = `<span>Ya, Jalankan Migrasi</span>`;
            }
        }
    } catch (err) {
        console.error(err);
        showToast('Terjadi kesalahan koneksi saat memproses migrasi.', 'error');
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = `<span>Ya, Jalankan Migrasi</span>`;
        }
    }
}

async function openModalRollbackMigrasiSiswa() {
    if (!window.currentUser || window.currentUser.peran !== 'admin_sekolah') {
        showToast('Hanya Admin Sekolah yang memiliki wewenang rollback migrasi kelas!', 'warning');
        return;
    }
    const modal = document.getElementById('modalRollbackMigrasiSiswa');
    if (!modal) return;

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    const emptyEl = document.getElementById('rollbackEmptyState');
    const dataCardEl = document.getElementById('rollbackDataCard');
    const btnSubmit = document.getElementById('btnSubmitRollbackMigrasi');
    const batchIdInp = document.getElementById('rollback_batch_id');

    if (emptyEl) emptyEl.classList.add('hidden');
    if (dataCardEl) dataCardEl.classList.add('hidden');
    if (btnSubmit) btnSubmit.classList.add('hidden');
    if (batchIdInp) batchIdInp.value = '';

    try {
        const res = await fetch('api.php?action=get_latest_migrasi_batch');
        const data = await res.json();

        if (data && data.success && data.has_batch && data.batch) {
            const batch = data.batch;
            if (batchIdInp) batchIdInp.value = batch.id || '';
            const elWaktu = document.getElementById('rollbackInfoWaktu');
            const elTaAsal = document.getElementById('rollbackInfoTaAsal');
            const elJur = document.getElementById('rollbackInfoJurusan');
            const el12 = document.getElementById('rollbackCount12');
            const el11 = document.getElementById('rollbackCount11');
            const el10 = document.getElementById('rollbackCount10');
            const elTot = document.getElementById('rollbackCountTotal');

            if (elWaktu) elWaktu.textContent = batch.created_at || '-';
            if (elTaAsal) elTaAsal.textContent = batch.tahun_ajaran_asal || '-';
            if (elJur) elJur.textContent = batch.nama_jurusan || 'Semua Jurusan';

            if (el12) el12.textContent = `${Number(batch.count_12 || 0).toLocaleString()} Siswa`;
            if (el11) el11.textContent = `${Number(batch.count_11 || 0).toLocaleString()} Siswa`;
            if (el10) el10.textContent = `${Number(batch.count_10 || 0).toLocaleString()} Siswa`;
            if (elTot) elTot.textContent = Number(batch.total_migrated || 0).toLocaleString();

            if (dataCardEl) dataCardEl.classList.remove('hidden');
            if (btnSubmit) {
                btnSubmit.classList.remove('hidden');
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = `<span>Ya, Rollback Migrasi</span>`;
            }
        } else {
            if (emptyEl) emptyEl.classList.remove('hidden');
            if (btnSubmit) btnSubmit.classList.add('hidden');
        }
    } catch (err) {
        console.error(err);
        if (emptyEl) emptyEl.classList.remove('hidden');
        showToast('Gagal memuat riwayat migrasi kelas.', 'error');
    }
}

async function handleRollbackMigrasiSubmit(e) {
    e.preventDefault();
    if (!window.currentUser || window.currentUser.peran !== 'admin_sekolah') {
        showToast('Hanya Admin Sekolah yang memiliki wewenang rollback migrasi kelas!', 'warning');
        return;
    }
    const batchId = document.getElementById('rollback_batch_id')?.value;
    if (!batchId) {
        showToast('ID batch migrasi tidak valid!', 'warning');
        return;
    }

    const totalStr = document.getElementById('rollbackCountTotal')?.textContent || '0';
    if (!confirm(`Konfirmasi Rollback Migrasi Kenaikan Kelas:\n\nApakah Anda yakin ingin MEMBATALKAN kenaikan kelas untuk ${totalStr} siswa ini?\n\n• Seluruh siswa yang naik kelas akan dikembalikan ke tingkatan kelas semula\n• Tahun ajaran siswa akan dipulihkan ke tahun ajaran sebelumnya\n\nTindakan ini akan mengembalikan data ke kondisi sebelum migrasi dijalankan.`)) {
        return;
    }

    const btn = document.getElementById('btnSubmitRollbackMigrasi');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = `<span>Memproses Rollback...</span>`;
    }

    try {
        const formData = new FormData();
        formData.append('action', 'rollback_migrasi_kelas_siswa');
        formData.append('csrf_token', document.querySelector('#formRollbackMigrasiSiswa input[name="csrf_token"]')?.value || '');
        formData.append('batch_id', batchId);

        const res = await fetch('api.php', { method: 'POST', body: formData });
        const data = await res.json();

        if (data && data.success) {
            showToast(data.message, 'success');
            closeModal('modalRollbackMigrasiSiswa');
            setTimeout(() => {
                location.reload();
            }, 1200);
        } else {
            showToast(data.message || 'Gagal memproses rollback migrasi.', 'error');
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = `<span>Ya, Rollback Migrasi</span>`;
            }
        }
    } catch (err) {
        console.error(err);
        showToast('Terjadi kesalahan koneksi saat memproses rollback migrasi.', 'error');
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a5 5 0 015 5v2m0 0l-4-4m4 4l4-4M3 10l4-4m-4 4l4 4"/></svg> <span>Ya, Rollback Migrasi</span>`;
        }
    }
}
// --- KELOLA AKSES MULTI-JURUSAN KEPALA BENGKEL ---
function resolveJurusanThemeColor(colorVal) {
    if (!colorVal) return '#2E7D32';
    const presets = {
        'kuning': '#EAB308',
        'orange': '#EA580C',
        'hijau': '#2E7D32',
        'merah': '#DC2626',
        'biru': '#2563EB',
        'ungu': '#7C3AED',
        'pink': '#E11D48',
        'cyan': '#0891B2',
        'violet': '#8B5CF6',
        'coklat': '#78350F',
        'abu': '#64748B'
    };
    const c = String(colorVal).trim().toLowerCase();
    if (presets[c]) return presets[c];
    if (c.startsWith('#')) return colorVal;
    if (/^[0-9a-f]{3,8}$/i.test(c)) return '#' + colorVal;
    return colorVal;
}

async function openModalMultiJurusanKabeng(penggunaId = null) {
    const modal = document.getElementById('modalMultiJurusanKabeng');
    if (!modal) return;

    const selectKabeng = document.getElementById('multi_kabeng_pengguna_id');
    if (selectKabeng) {
        // Filter pengguna yang merupakan kabeng atau admin_jurusan
        const kabengList = (window.dbPengguna || []).filter(u => u.peran === 'kabeng' || u.peran === 'admin_jurusan');
        selectKabeng.innerHTML = '<option value="">-- Pilih Kepala Bengkel --</option>' +
            kabengList.map(u => {
                const labelName = u.nama_lengkap || u.nama_pengguna;
                const labelJurusan = u.nama_jurusan ? ` (${u.nama_jurusan})` : '';
                return `<option value="${u.id}">${labelName}${labelJurusan}</option>`;
            }).join('');
    }

    const searchInp = document.getElementById('multi_jurusan_search_input');
    if (searchInp) searchInp.value = '';
    const clearBtn = document.getElementById('btn_clear_multi_jurusan_search');
    if (clearBtn) clearBtn.classList.add('hidden');

    if (penggunaId) {
        if (selectKabeng) selectKabeng.value = penggunaId;
        await onKabengSelectedForMulti(penggunaId);
    } else {
        if (selectKabeng) selectKabeng.value = '';
        const card = document.getElementById('multi_kabeng_info_card');
        if (card) card.classList.add('hidden');
        const container = document.getElementById('multi_jurusan_checkbox_container');
        if (container) {
            container.innerHTML = '<div class="p-4 text-center text-xs text-slate-400">Pilih Kepala Bengkel terlebih dahulu...</div>';
        }
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function filterMultiJurusanList(val) {
    const q = (val || '').toLowerCase().trim();
    const clearBtn = document.getElementById('btn_clear_multi_jurusan_search');
    if (clearBtn) {
        if (q) clearBtn.classList.remove('hidden');
        else clearBtn.classList.add('hidden');
    }
    const container = document.getElementById('multi_jurusan_checkbox_container');
    if (!container) return;
    const items = container.querySelectorAll('.multi-jurusan-item');
    let visibleCount = 0;
    items.forEach(item => {
        const text = item.getAttribute('data-search') || item.textContent.toLowerCase();
        if (!q || text.includes(q)) {
            item.classList.remove('hidden');
            visibleCount++;
        } else {
            item.classList.add('hidden');
        }
    });

    let emptyMsg = document.getElementById('multi_jurusan_empty_search');
    if (items.length > 0 && visibleCount === 0) {
        if (!emptyMsg) {
            emptyMsg = document.createElement('div');
            emptyMsg.id = 'multi_jurusan_empty_search';
            emptyMsg.className = 'p-4 text-center text-xs text-slate-400';
            emptyMsg.textContent = 'Tidak ada jurusan yang cocok dengan pencarian.';
            container.appendChild(emptyMsg);
        } else {
            emptyMsg.classList.remove('hidden');
        }
    } else if (emptyMsg) {
        emptyMsg.classList.add('hidden');
    }
}

function clearMultiJurusanSearch() {
    const input = document.getElementById('multi_jurusan_search_input');
    if (input) {
        input.value = '';
        filterMultiJurusanList('');
        input.focus();
    }
}

async function onKabengSelectedForMulti(penggunaId) {
    const card = document.getElementById('multi_kabeng_info_card');
    const container = document.getElementById('multi_jurusan_checkbox_container');
    const searchInp = document.getElementById('multi_jurusan_search_input');
    if (searchInp) searchInp.value = '';
    const clearBtn = document.getElementById('btn_clear_multi_jurusan_search');
    if (clearBtn) clearBtn.classList.add('hidden');

    if (!penggunaId) {
        if (card) card.classList.add('hidden');
        if (container) container.innerHTML = '<div class="p-4 text-center text-xs text-slate-400">Pilih Kepala Bengkel terlebih dahulu...</div>';
        return;
    }

    const user = (window.dbPengguna || []).find(u => String(u.id) === String(penggunaId));
    if (card && user) {
        card.classList.remove('hidden');
        const elNama = document.getElementById('multi_kabeng_nama');
        const elUsername = document.getElementById('multi_kabeng_username');
        const elJurusanAsal = document.getElementById('multi_kabeng_jurusan_asal');

        if (elNama) elNama.innerText = user.nama_lengkap || user.nama_pengguna;
        if (elUsername) elUsername.innerText = user.nama_pengguna;
        if (elJurusanAsal) {
            elJurusanAsal.innerText = user.nama_jurusan || 'Tidak Ada (Semua)';
            const jObj = (window.dbJurusan || []).find(j => String(j.id) === String(user.jurusan_id));
            elJurusanAsal.style.color = jObj ? resolveJurusanThemeColor(jObj.warna_tema) : '';
        }
    }

    if (container) {
        container.innerHTML = `<div class="p-4 text-center text-xs text-slate-400 flex items-center justify-center gap-2">
            <svg class="w-4 h-4 animate-spin text-indigo-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            <span>Memuat hak akses jurusan...</span>
        </div>`;
    }

    try {
        const res = await fetch(`api.php?action=get_kabeng_multi_jurusan&pengguna_id=${encodeURIComponent(penggunaId)}`);
        const data = await res.json();
        const allowedIds = (data && data.success && Array.isArray(data.jurusan_ids)) ? data.jurusan_ids.map(String) : [];

        if (container && window.dbJurusan) {
            const primaryJurusanId = user ? String(user.jurusan_id || '') : '';
            container.innerHTML = window.dbJurusan.map(j => {
                const jId = String(j.id);
                const isPrimary = (primaryJurusanId && jId === primaryJurusanId);
                const isChecked = isPrimary || allowedIds.includes(jId);
                const themeColor = resolveJurusanThemeColor(j.warna_tema);
                const searchKey = `${j.nama_jurusan || ''} ${j.kode_jurusan || ''}`.toLowerCase();

                return `
                    <label data-search="${escapeHtml(searchKey)}" class="multi-jurusan-item flex items-center justify-between p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800/50 cursor-pointer transition-colors">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" name="jurusan_ids[]" value="${j.id}" ${isChecked ? 'checked' : ''} ${isPrimary ? 'onclick="return false;"' : ''} class="w-4 h-4 text-indigo-600 rounded border-slate-300 dark:border-slate-700 focus:ring-indigo-500 cursor-pointer">
                            ${isPrimary ? `<input type="hidden" name="jurusan_ids[]" value="${j.id}">` : ''}
                            <span class="font-bold text-xs" style="color: ${themeColor};">${escapeHtml(j.nama_jurusan)}</span>
                        </div>
                        <div>
                            ${isPrimary 
                                ? `<span class="text-[11px] text-slate-400 dark:text-slate-500 font-semibold">(Jurusan Utama)</span>` 
                                : `<span class="text-[11px] text-slate-400 dark:text-slate-500">${escapeHtml(j.kode_jurusan || '')}</span>`
                            }
                        </div>
                    </label>
                `;
            }).join('');
        }
    } catch (err) {
        console.error(err);
        if (container) {
            container.innerHTML = '<div class="p-4 text-center text-xs text-red-500">Gagal memuat data akses jurusan.</div>';
        }
    }
}

async function handleMultiJurusanSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const btn = document.getElementById('btnSubmitMultiJurusan');

    const penggunaId = document.getElementById('multi_kabeng_pengguna_id')?.value;
    if (!penggunaId) {
        showToast('Pilih Kepala Bengkel terlebih dahulu!', 'warning');
        return;
    }

    if (btn) {
        btn.disabled = true;
        btn.innerHTML = `<svg class="w-4 h-4 animate-spin shrink-0" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> <span>Menyimpan...</span>`;
    }

    try {
        const formData = new FormData(form);
        formData.append('action', 'save_kabeng_multi_jurusan');

        const res = await fetch('api.php', { method: 'POST', body: formData });
        const data = await res.json();

        if (data && data.success) {
            showToast(data.message, 'success');
            closeModal('modalMultiJurusanKabeng');
            setTimeout(() => {
                location.reload();
            }, 800);
        } else {
            showToast(data.message || 'Gagal menyimpan akses multi-jurusan.', 'error');
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> <span>Simpan Akses Multi-Jurusan</span>`;
            }
        }
    } catch (err) {
        console.error(err);
        showToast('Terjadi kesalahan koneksi saat menyimpan akses.', 'error');
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> <span>Simpan Akses Multi-Jurusan</span>`;
        }
    }
}

function closeModalUbahPasswordToken(rememberDismiss = true) {
    closeModal('modalUbahPasswordToken');
    if (rememberDismiss) {
        const uid = window.currentUser ? window.currentUser.id : 'current';
        const key = 'dismissed_token_pwd_modal_' + uid;
        try { localStorage.setItem(key, '1'); } catch(e) {}
        try { sessionStorage.setItem('dismissed_token_pwd_modal', '1'); } catch(e) {}
        document.cookie = key + '=1; path=/; max-age=31536000; SameSite=Lax';
        try { fetch('api.php?action=dismiss_token_password_modal'); } catch(e) {}
    }
}

async function submitUbahPasswordToken(e) {
    e.preventDefault();
    const newPass = document.getElementById('inputTokenNewPassword')?.value || '';
    const confirmPass = document.getElementById('inputTokenConfirmPassword')?.value || '';
    const errText = document.getElementById('tokenPasswordErrorText');
    const btn = document.getElementById('btnSubmitUbahPasswordToken');

    if (errText) errText.classList.add('hidden');

    if (newPass.length < 6) {
        if (errText) {
            errText.textContent = 'Kata sandi minimal harus 6 karakter!';
            errText.classList.remove('hidden');
        }
        return;
    }

    if (newPass !== confirmPass) {
        if (errText) {
            errText.textContent = 'Konfirmasi kata sandi tidak cocok!';
            errText.classList.remove('hidden');
        }
        return;
    }

    if (window.currentUser && window.currentUser.token && newPass.toUpperCase() === window.currentUser.token.toUpperCase()) {
        if (errText) {
            errText.textContent = 'Kata sandi baru tidak boleh sama dengan token bawaan!';
            errText.classList.remove('hidden');
        }
        return;
    }

    const csrfInput = document.querySelector('#formUbahPasswordToken input[name="csrf_token"]');
    const formData = new FormData();
    formData.append('action', 'ubah_password_dari_token');
    formData.append('password', newPass);
    formData.append('confirm_password', confirmPass);
    if (csrfInput) formData.append('csrf_token', csrfInput.value);

    if (btn) {
        btn.disabled = true;
        btn.innerText = 'Menyimpan...';
    }

    try {
        const res = await fetch('api.php', { method: 'POST', body: formData });
        const data = await res.json();
        if (data.success) {
            showToast(data.message, 'success');
            if (window.currentUser) {
                window.currentUser.is_password_token = false;
            }
            const uid = window.currentUser ? window.currentUser.id : 'current';
            const key = 'dismissed_token_pwd_modal_' + uid;
            try { localStorage.setItem(key, '1'); } catch(e) {}
            try { sessionStorage.setItem('dismissed_token_pwd_modal', '1'); } catch(e) {}
            document.cookie = key + '=1; path=/; max-age=31536000; SameSite=Lax';
            closeModal('modalUbahPasswordToken');
        } else {
            showToast(data.message || 'Gagal mengubah kata sandi.', 'error');
            if (errText) {
                errText.textContent = data.message || 'Gagal mengubah kata sandi.';
                errText.classList.remove('hidden');
            }
        }
    } catch (err) {
        console.error(err);
        showToast('Terjadi kesalahan jaringan.', 'error');
    } finally {
        if (btn) {
            btn.disabled = false;
            btn.innerText = 'Save Changes';
        }
    }
}
</script>

<!-- 9. FLOATING BATCH DELETE TOAST NOTIFICATION CONTAINER -->
<div id="batchDeleteToast" class="fixed bottom-6 right-6 z-50 hidden items-center gap-3 bg-slate-900/90 dark:bg-neutral-800/95 text-white px-5 py-3.5 rounded-2xl shadow-2xl backdrop-blur-md border border-slate-700 animate-fade-in-up">
    <div class="flex items-center gap-2">
        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
        <span id="batchDeleteCountText" class="font-bold text-xs">0 data dipilih</span>
    </div>
    <div class="flex items-center gap-2 ml-2">
        <button type="button" onclick="executeBatchDelete()" class="px-3.5 py-1.5 bg-red-600 hover:bg-red-700 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            <span>Hapus Pilihan</span>
        </button>
        <button type="button" onclick="clearBatchSelection()" class="p-1.5 text-slate-400 hover:text-white rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
</div>
