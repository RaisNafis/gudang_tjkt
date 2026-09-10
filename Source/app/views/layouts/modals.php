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
                    <option value="petugas">Petugas Gudang</option>
                    <option value="admin_jurusan">Admin Jurusan</option>
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
<div id="modalBarang" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-4 overflow-y-auto bg-slate-900/40 backdrop-blur-sm animate-fade-in-up">
    <div class="bg-white rounded-3xl border border-sage-200 shadow-2xl w-full max-w-lg overflow-hidden">
        <div class="p-6 bg-sage-50/80 border-b border-sage-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800" id="modalBarangTitle">Tambah Alat / Bahan & Barcode</h3>
            </div>
            <button onclick="closeModal('modalBarang')" class="text-slate-400 hover:text-red-600 p-1.5 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form onsubmit="handleFormSubmit(event, 'Barang')" class="p-6 space-y-4 text-xs">
            <input type="hidden" name="csrf_token" value="<?= getCsrfToken(); ?>">
            <input type="hidden" id="barang_edit_id" value="">
            <?php if (!empty($user['peran']) && $user['peran'] === 'admin_sekolah'): ?>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Jurusan / Departemen <span class="text-red-500">*</span></label>
                <select id="barang_jurusan_id" onchange="filterKategoriAndRakByJurusan(this.value)" class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600">
                    <option value="">-- Pilih Jurusan --</option>
                </select>
            </div>
            <?php endif; ?>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Kode Barcode</label>
                <input type="text" id="barang_barcode" readonly class="w-full px-3.5 py-2.5 bg-slate-100/70 dark:bg-neutral-900/80 border border-sage-200 rounded-xl font-mono font-bold text-slate-700 dark:text-slate-300 cursor-not-allowed focus:outline-none" placeholder="899100100004">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Nama Alat / Bahan <span class="text-red-500">*</span></label>
                <input type="text" id="barang_nama" required class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600" placeholder="contoh: Router Mikrotik Hex Gr3 / Kabel UTP">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Jenis Inventaris <span class="text-red-500">*</span></label>
                <select id="barang_jenis" required class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600">
                    <option value="alat">Alat (Dapat dipinjam & dikembalikan)</option>
                    <option value="bahan">Bahan (Material habis pakai)</option>
                </select>
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Kategori</label>
                <select id="barang_kategori_id" class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600">
                    <option value="">-- Pilih Kategori --</option>
                </select>
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Rak Penyimpanan</label>
                <select id="barang_rak_id" class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600">
                    <option value="">-- Pilih Rak Penyimpanan --</option>
                </select>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Merek / Brand</label>
                    <input type="text" id="barang_merek" class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600" placeholder="Mikrotik">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Stok Awal</label>
                    <input type="number" id="barang_stok" min="0" value="10" class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-bold text-slate-800 focus:outline-none focus:border-sage-600">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Satuan <span class="text-red-500">*</span></label>
                    <input type="text" id="barang_satuan" required value="Unit" class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600" placeholder="Unit, Meter, Pcs...">
                </div>
            </div>
            <div class="pt-3 flex justify-end gap-3 border-t border-sage-100">
                <button type="button" onclick="closeModal('modalBarang')" class="px-4 py-2 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2 bg-sage-600 text-white font-bold rounded-xl shadow-md shadow-sage-600/20 hover:bg-sage-700">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<!-- 4. MODAL BARANG MASUK -->
<div id="modalBarangMasuk" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-4 overflow-y-auto bg-slate-900/40 backdrop-blur-sm animate-fade-in-up">
    <div class="bg-white rounded-3xl border border-sage-200 shadow-2xl w-full max-w-lg overflow-hidden">
        <div class="p-6 bg-sage-50/80 border-b border-sage-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800" id="modalBarangMasukTitle">Catat Transaksi Alat & Bahan Masuk</h3>
            </div>
            <button onclick="closeModal('modalBarangMasuk')" class="text-slate-400 hover:text-red-600 p-1.5 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form onsubmit="handleFormSubmit(event, 'Barang Masuk')" class="p-6 space-y-4 text-xs">
            <input type="hidden" name="csrf_token" value="<?= getCsrfToken(); ?>">
            <input type="hidden" id="masuk_edit_id" value="">
            <?php if (!empty($user['peran']) && $user['peran'] === 'admin_sekolah'): ?>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Jurusan / Departemen</label>
                <select id="masuk_jurusan_id" onchange="filterBarangMasukOptions()" class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600">
                    <option value="">-- Semua Jurusan --</option>
                </select>
            </div>
            <?php endif; ?>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Pilih Jenis Barang <span class="text-red-500">*</span></label>
                <select id="masuk_jenis" onchange="filterBarangMasukOptions()" class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600">
                    <option value="">-- Semua Jenis (Alat & Bahan) --</option>
                    <option value="alat">Alat</option>
                    <option value="bahan">Bahan</option>
                </select>
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1" id="masuk_barang_label">Pilih Alat / Bahan <span class="text-red-500">*</span></label>
                <select id="masuk_barang_id" required class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600">
                    <option value="">-- Pilih Alat / Bahan --</option>
                </select>
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Jumlah Masuk <span class="text-red-500">*</span></label>
                <input type="number" id="masuk_jumlah" min="1" value="5" required class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-bold text-sage-700 focus:outline-none focus:border-sage-600">
            </div>
            <div class="pt-3 flex justify-end gap-3 border-t border-sage-100">
                <button type="button" onclick="closeModal('modalBarangMasuk')" class="px-4 py-2 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2 bg-sage-600 text-white font-bold rounded-xl shadow-md shadow-sage-600/20 hover:bg-sage-700">Simpan Alat & Bahan Masuk</button>
            </div>
        </form>
    </div>
</div>

<!-- 4.5. MODAL BARANG KELUAR -->
<div id="modalBarangKeluar" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-4 overflow-y-auto bg-slate-900/40 backdrop-blur-sm animate-fade-in-up">
    <div class="bg-white rounded-3xl border border-sage-200 shadow-2xl w-full max-w-lg overflow-hidden">
        <div class="p-6 bg-sage-50/80 border-b border-sage-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12"/></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800" id="modalBarangKeluarTitle">Catat Transaksi Bahan Keluar</h3>
            </div>
            <button onclick="closeModal('modalBarangKeluar')" class="text-slate-400 hover:text-red-600 p-1.5 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form onsubmit="handleFormSubmit(event, 'Barang Keluar')" class="p-6 space-y-4 text-xs">
            <input type="hidden" name="csrf_token" value="<?= getCsrfToken(); ?>">
            <input type="hidden" id="keluar_edit_id" value="">
            <?php if (!empty($user['peran']) && $user['peran'] === 'admin_sekolah'): ?>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Jurusan / Departemen</label>
                <select id="keluar_jurusan_id" onchange="filterBarangKeluarOptions()" class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600">
                    <option value="">-- Semua Jurusan --</option>
                </select>
            </div>
            <?php endif; ?>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Pilih Bahan <span class="text-red-500">*</span></label>
                <select id="keluar_barang_id" required class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600">
                    <option value="">-- Pilih Bahan --</option>
                    <?php if (!empty($dbBarang)): ?>
                        <?php foreach ($dbBarang as $b): ?>
                            <?php if (strtolower($b['jenis'] ?? 'alat') === 'bahan'): ?>
                                <option value="<?= htmlspecialchars($b['id']); ?>"><?= htmlspecialchars($b['nama_barang']); ?> (Tersedia: <?= htmlspecialchars($b['stok_tersedia']); ?> <?= htmlspecialchars($b['satuan'] ?? 'Unit'); ?>)</option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Penerima / Peruntukan <span class="text-red-500">*</span></label>
                    <input type="text" id="keluar_penerima" required class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600" placeholder="contoh: Lab Komputer 2 / Ahmad">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Jumlah Keluar <span class="text-red-500">*</span></label>
                    <input type="number" id="keluar_jumlah" min="1" value="1" required class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-bold text-sage-700 focus:outline-none focus:border-sage-600">
                </div>
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Keterangan / Alasan</label>
                <textarea id="keluar_keterangan" rows="2" class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600" placeholder="contoh: Pemakaian bahan praktik jaringan"></textarea>
            </div>
            <div class="pt-3 flex justify-end gap-3 border-t border-sage-100">
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
                <select id="pinjam_jenis" onchange="filterBarangPinjamOptions()" class="w-full px-3.5 py-2.5 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-white focus:outline-none focus:border-sage-600">
                    <option value="">-- Semua Jenis (Alat & Bahan) --</option>
                    <option value="alat">Alat</option>
                    <option value="bahan">Bahan</option>
                </select>
            </div>
            <div id="wrap_pinjam_barang">
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1" id="pinjam_barang_label">Pilih Inventaris <span class="text-red-500">*</span></label>
                <select id="pinjam_barang_id" required class="w-full px-3.5 py-2.5 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-white focus:outline-none focus:border-sage-600">
                    <option value="">-- Pilih Inventaris --</option>
                </select>
            </div>
            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Guru Peminjam</label>
                <select id="pinjam_guru_peminjam_select" onchange="toggleGuruPeminjamMode(this.value)" class="w-full px-3.5 py-2.5 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-white focus:outline-none focus:border-sage-600">
                    <option value="">-- Pilih Guru Peminjam (Otomatis Guru Login jika kosong) --</option>
                </select>
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
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Siswa Peminjam <span class="text-red-500">*</span></label>
                    <select id="pinjam_peminjam_select" onchange="onSiswaSelectedInPeminjamanForm(this.value)" class="w-full px-3.5 py-2.5 bg-sage-50/50 dark:bg-slate-800 border border-sage-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-white focus:outline-none focus:border-sage-600">
                        <option value="">-- Pilih Siswa Peminjam --</option>
                    </select>
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
        <!-- TOMBOL AKSI DISAMPING KANAN ATAS (DOWNLOAD & CLOSE) -->
        <div class="absolute -top-12 -right-2 md:-top-14 md:-right-14 flex items-center gap-2.5 z-20">
            <!-- TOMBOL DOWNLOAD IMAGE -->
            <button type="button" onclick="downloadFotoPreview()" class="text-slate-300 hover:text-white p-2.5 rounded-full bg-slate-800/90 hover:bg-slate-700 transition-colors shadow-2xl border border-slate-700/80 flex items-center justify-center" title="Unduh / Download Foto Bukti">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            </button>
            <!-- TOMBOL CLOSE X -->
            <button type="button" onclick="closeModal('modalFotoPreview')" class="text-slate-300 hover:text-white p-2.5 rounded-full bg-slate-800/90 hover:bg-slate-700 transition-colors shadow-2xl border border-slate-700/80 flex items-center justify-center" title="Tutup Preview (ESC)">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <!-- GAMBAR PREVIEW UKURAN BESAR MURNI TANPA BORDER RADIUS -->
        <img id="previewFotoImg" src="" alt="Bukti Foto" class="max-h-[92vh] max-w-[95vw] min-w-[320px] sm:min-w-[480px] md:min-w-[600px] w-auto h-auto object-contain rounded-none shadow-2xl block" loading="lazy" decoding="async">
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

// Fungsi membuka modal, mendukung pengisian data otomatis untuk mode Edit

function filterBarangSelectByJurusan(targetSelectId, selectedJurusanId) {
    const el = document.getElementById(targetSelectId);
    if (!el || !window.dbBarang) return;
    let filtered = window.dbBarang;
    if (selectedJurusanId) {
        filtered = window.dbBarang.filter(b => String(b.jurusan_id) === String(selectedJurusanId));
    }
    if (targetSelectId === 'keluar_barang_id') {
        filtered = filtered.filter(b => String(b.jenis || 'alat').toLowerCase() === 'bahan');
    }
    const defaultLabel = (targetSelectId === 'keluar_barang_id') ? '-- Pilih Bahan --' : '-- Pilih Barang --';
    el.innerHTML = `<option value="">${defaultLabel}</option>` +
        filtered.map(b => `<option value="${b.id}">${b.nama_barang} (Tersedia: ${b.stok_tersedia} ${b.satuan || 'Unit'})</option>`).join('');
}

function filterBarangMasukOptions(preselectedBarangId = null) {
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
        if (checked) {
            selectBarang.removeAttribute('required');
        } else {
            selectBarang.setAttribute('required', 'required');
        }
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

        if (selectJenis && found.jenis) {
            selectJenis.value = found.jenis.toLowerCase();
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
            selectBarang.classList.add('ring-2', 'ring-emerald-500');
            setTimeout(() => selectBarang.classList.remove('ring-2', 'ring-emerald-500'), 2500);
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
}

function filterBarangPinjamOptions(preselectedBarangId = null) {
    const selectJenis = document.getElementById('pinjam_jenis');
    const selectBarang = document.getElementById('pinjam_barang_id');
    const selectJur = document.getElementById('pinjam_jurusan_id');
    const labelBarang = document.getElementById('pinjam_barang_label');

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
        if (labelBarang) labelBarang.innerHTML = 'Pilih Inventaris <span class="text-red-500">*</span>';
    }

    const defaultPrompt = chosenJenis === 'alat' ? '-- Pilih Alat --' : (chosenJenis === 'bahan' ? '-- Pilih Bahan --' : '-- Pilih Inventaris --');

    selectBarang.innerHTML = `<option value="">${defaultPrompt}</option>` +
        filtered.map(b => `<option value="${b.id}">${b.nama_barang} (Tersedia: ${b.stok_tersedia} ${b.satuan || 'Unit'})</option>`).join('');

    const targetVal = preselectedBarangId !== null ? preselectedBarangId : selectBarang.value;
    if (targetVal && filtered.some(b => String(b.id) === String(targetVal))) {
        selectBarang.value = targetVal;
    } else {
        selectBarang.value = '';
    }
}

function filterGuruPinjamOptions(preselectedGuru = null) {
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
                break;
            }
        }
    }
}

function filterSiswaPinjamOptions(preselectedSiswa = null) {
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
        filterBarangMasukOptions(editData ? editData.barang_id : null);
    } else if (modalId === 'modalBarangKeluar') {
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

                if (elPeran) elPeran.value = editData.peran || 'siswa';
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
            }
            else if (modalId === 'modalBarangMasuk') {
                const elId = document.getElementById('masuk_edit_id');
                const elJurusan = document.getElementById('masuk_jurusan_id');
                const elJenis = document.getElementById('masuk_jenis');
                const elBarang = document.getElementById('masuk_barang_id');
                const elJumlah = document.getElementById('masuk_jumlah');

                if (elId) elId.value = editData.id || '';
                if (elJurusan) elJurusan.value = editData.jurusan_id || '';
                const foundB = (window.dbBarang || []).find(b => String(b.id) === String(editData.barang_id));
                if (elJenis) elJenis.value = foundB ? (foundB.jenis || '') : '';
                filterBarangMasukOptions(editData.barang_id || '');
                if (elJumlah) elJumlah.value = editData.jumlah || 1;
            }
            else if (modalId === 'modalBarangKeluar') {
                const elId = document.getElementById('keluar_edit_id');
                const elJurusan = document.getElementById('keluar_jurusan_id');
                const elPenerima = document.getElementById('keluar_penerima');
                const elJumlah = document.getElementById('keluar_jumlah');
                const elKet = document.getElementById('keluar_keterangan');

                if (elId) elId.value = editData.id || '';
                if (elJurusan) elJurusan.value = editData.jurusan_id || '';
                filterBarangKeluarOptions(editData.barang_id || '');
                if (elPenerima) elPenerima.value = editData.nama_penerima || '';
                if (elJumlah) elJumlah.value = editData.jumlah || 1;
                if (elKet) elKet.value = editData.catatan || '';
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

                const foundB = (window.dbBarang || []).find(b => String(b.id) === String(editData.barang_id));
                if (elJenis) elJenis.value = foundB ? (foundB.jenis || '') : '';
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
                    }

                    if (elNisn) elNisn.value = rawNisn || (matchedSiswa ? matchedSiswa.nisn : '');
                    if (elTahun) elTahun.value = editData.tahun_ajaran || (matchedSiswa ? matchedSiswa.tahun_ajaran : '2026/2027');
                } else {
                    if (selectSiswa) selectSiswa.value = '';
                    if (customSiswa) { customSiswa.classList.add('hidden'); customSiswa.required = false; customSiswa.value = ''; }
                    if (elNisn) elNisn.value = '';
                    if (elTahun) elTahun.value = '';
                }

                // Jika status sudah 'dikembalikan', KUNCI (disable) semua field kecuali Tanggal dan Status!
                const isReturned = (editData.status === 'dikembalikan');
                [elJurusan, elJenis, elBarang, selectSiswa, customSiswa, elJumlah, elTugas, elTahun, elCheckSiswa, selectGuru, customGuru].forEach(el => {
                    if (el) {
                        el.disabled = isReturned;
                        if (isReturned) {
                            el.classList.add('bg-slate-100/80', 'cursor-not-allowed');
                        } else {
                            el.classList.remove('bg-slate-100/80', 'cursor-not-allowed');
                        }
                    }
                });
            }
        } else {
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
            } else if (modalId === 'modalBarangMasuk') {
                const elJenis = document.getElementById('masuk_jenis');
                if (elJenis) elJenis.value = '';
                filterBarangMasukOptions();
                const elJumlah = document.getElementById('masuk_jumlah');
                if (elJumlah) elJumlah.value = 5;
            } else if (modalId === 'modalBarangKeluar') {
                filterBarangKeluarOptions();
                const elPenerima = document.getElementById('keluar_penerima');
                const elJumlah = document.getElementById('keluar_jumlah');
                const elKet = document.getElementById('keluar_keterangan');
                if (elPenerima) elPenerima.value = '';
                if (elJumlah) elJumlah.value = 1;
                if (elKet) elKet.value = '';
            } else if (modalId === 'modalPeminjaman') {
                const elJenis = document.getElementById('pinjam_jenis');
                if (elJenis) elJenis.value = '';
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

                [elJurusan, elJenis, elBarang, selectSiswa, customSiswa, elJumlah, elTugas, elTglPinjam, elTglKembali, elStatus, checkUntukSiswa, selectGuru, customGuru, inputNisn, inputTA].forEach(el => {
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
    } else if (actionName === 'Barang Keluar') {
        apiAction = 'save_barang_keluar';
        formData.append('id', document.getElementById('keluar_edit_id')?.value || '');
        formData.append('jurusan_id', document.getElementById('keluar_jurusan_id')?.value || '');
        formData.append('barang_id', document.getElementById('keluar_barang_id')?.value || '');
        formData.append('nama_penerima', document.getElementById('keluar_penerima')?.value || '');
        formData.append('jumlah', document.getElementById('keluar_jumlah')?.value || '1');
        formData.append('catatan', document.getElementById('keluar_keterangan')?.value || '');
    } else if (actionName === 'Barang Masuk') {
        apiAction = 'save_barang_masuk';
        formData.append('id', document.getElementById('masuk_edit_id')?.value || '');
        formData.append('jurusan_id', document.getElementById('masuk_jurusan_id')?.value || '');
        formData.append('barang_id', document.getElementById('masuk_barang_id')?.value || '');
        formData.append('jumlah', document.getElementById('masuk_jumlah')?.value || '1');
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
                showNotification('Pilih inventaris barang yang akan dipinjam!', 'warning');
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

function showFotoPreview(srcUrl, title = 'Preview Foto', caption = '') {
    const imgElem = document.getElementById('previewFotoImg');
    const titleElem = document.getElementById('previewFotoTitle');
    const captionElem = document.getElementById('previewFotoCaption');

    if (imgElem) imgElem.src = srcUrl;
    if (titleElem) titleElem.innerText = title;
    if (captionElem) captionElem.innerText = caption;

    openModal('modalFotoPreview');
}

function downloadFotoPreview() {
    const imgElem = document.getElementById('previewFotoImg');
    if (!imgElem || !imgElem.src) {
        showToast('Foto bukti belum tersedia untuk diunduh.', 'error');
        return;
    }

    const src = imgElem.src;
    const filename = 'bukti_foto_pengembalian_' + Date.now() + '.png';

    if (src.startsWith('data:')) {
        const a = document.createElement('a');
        a.href = src;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        showToast('Mengunduh foto bukti pengembalian...', 'success');
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
                showToast('Mengunduh foto bukti pengembalian...', 'success');
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
        // Guru Bengkel -> Peran: Admin Jurusan ('admin_jurusan'), Jurusan: jurusan_id guru
        // Guru Umum -> Peran: Guru Umum ('guru_umum'), Jurusan: "" (Tidak Ada)
        if (guru.mengajar === 'bengkel') {
            if (elPeran) elPeran.value = 'admin_jurusan';
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
        if (selSiswa) selSiswa.required = true;
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
