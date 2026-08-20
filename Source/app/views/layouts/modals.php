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
<div id="modalPengguna" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-4 overflow-y-auto bg-slate-900/40 backdrop-blur-sm animate-fade-in-up">
    <div class="bg-white rounded-3xl border border-sage-200 shadow-2xl w-full max-w-lg overflow-hidden">
        <div class="p-6 bg-sage-50/80 border-b border-sage-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800" id="modalPenggunaTitle">Tambah Data Pengguna</h3>
            </div>
            <button onclick="closeModal('modalPengguna')" class="text-slate-400 hover:text-red-600 p-1.5 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form onsubmit="handleFormSubmit(event, 'Pengguna')" class="p-6 space-y-4 text-xs">
            <input type="hidden" name="csrf_token" value="<?= getCsrfToken(); ?>">
            <input type="hidden" id="pengguna_edit_id" value="">
            <div>
                <label class="block font-bold text-slate-700 mb-1">Nama Pengguna (Username) <span class="text-red-500">*</span></label>
                <input type="text" id="pengguna_nama_pengguna" required class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600" placeholder="contoh: siswa_budi">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" id="pengguna_nama_lengkap" required class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600" placeholder="contoh: Budi Prasetyo">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Email</label>
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
                    <option value="">-- Semua Jurusan (Super Admin) --</option>
                </select>
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Peran Akses <span class="text-red-500">*</span></label>
                <select id="pengguna_peran" required onchange="handlePeranChange(this.value)" class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600">
                    <option value="siswa">Siswa</option>
                    <option value="petugas">Petugas Gudang</option>
                    <option value="admin_jurusan">Admin Jurusan</option>
                    <?php if (!empty($user['peran']) && $user['peran'] === 'admin_sekolah'): ?>
                        <option value="admin_sekolah">Admin Sekolah</option>
                    <?php endif; ?>
                </select>
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1" id="pengguna_password_label">Kata Sandi</label>
                <div class="relative flex items-center">
                    <input type="password" id="pengguna_password" class="w-full px-3.5 py-2.5 pr-10 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600" placeholder="••••••••">
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
                <h3 class="text-base font-bold text-slate-800" id="modalBarangTitle">Tambah Barang & Barcode</h3>
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
                <label class="block font-bold text-slate-700 mb-1">Nama Barang Inventaris <span class="text-red-500">*</span></label>
                <input type="text" id="barang_nama" required class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600" placeholder="contoh: Router Mikrotik Hex Gr3">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Kategori Barang</label>
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
                <button type="submit" class="px-5 py-2 bg-sage-600 text-white font-bold rounded-xl shadow-md shadow-sage-600/20 hover:bg-sage-700">Simpan Barang</button>
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
                <h3 class="text-base font-bold text-slate-800" id="modalBarangMasukTitle">Catat Transaksi Barang Masuk</h3>
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
                <select id="masuk_jurusan_id" onchange="filterBarangSelectByJurusan('masuk_barang_id', this.value)" class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600">
                    <option value="">-- Semua Jurusan --</option>
                </select>
            </div>
            <?php endif; ?>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Pilih Barang <span class="text-red-500">*</span></label>
                <select id="masuk_barang_id" required class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600">
                    <option value="">-- Pilih Barang --</option>
                </select>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Pemasok / Supplier</label>
                    <input type="text" id="masuk_pemasok" class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600" placeholder="PT Network Utama">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Jumlah Masuk <span class="text-red-500">*</span></label>
                    <input type="number" id="masuk_jumlah" min="1" value="5" required class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-bold text-sage-700 focus:outline-none focus:border-sage-600">
                </div>
            </div>
            <div class="pt-3 flex justify-end gap-3 border-t border-sage-100">
                <button type="button" onclick="closeModal('modalBarangMasuk')" class="px-4 py-2 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2 bg-sage-600 text-white font-bold rounded-xl shadow-md shadow-sage-600/20 hover:bg-sage-700">Simpan Barang Masuk</button>
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
                <h3 class="text-base font-bold text-slate-800" id="modalBarangKeluarTitle">Catat Transaksi Barang Keluar</h3>
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
                <select id="keluar_jurusan_id" onchange="filterBarangSelectByJurusan('keluar_barang_id', this.value)" class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600">
                    <option value="">-- Semua Jurusan --</option>
                </select>
            </div>
            <?php endif; ?>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Pilih Barang <span class="text-red-500">*</span></label>
                <select id="keluar_barang_id" required class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600">
                    <option value="">-- Pilih Barang --</option>
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
                <textarea id="keluar_keterangan" rows="2" class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600" placeholder="contoh: Pemakaian alat praktik pemeliharaan jaringan"></textarea>
            </div>
            <div class="pt-3 flex justify-end gap-3 border-t border-sage-100">
                <button type="button" onclick="closeModal('modalBarangKeluar')" class="px-4 py-2 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2 bg-sage-600 text-white font-bold rounded-xl shadow-md shadow-sage-600/20 hover:bg-sage-700">Simpan Barang Keluar</button>
            </div>
        </form>
    </div>
</div>

<!-- 5. MODAL PEMINJAMAN ALAT -->
<div id="modalPeminjaman" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-4 overflow-y-auto bg-slate-900/40 backdrop-blur-sm animate-fade-in-up">
    <div class="bg-white rounded-3xl border border-sage-200 shadow-2xl w-full max-w-lg overflow-hidden">
        <div class="p-6 bg-sage-50/80 border-b border-sage-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-sage-600 text-white flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800">Form Pengajuan Peminjaman Alat</h3>
            </div>
            <button onclick="closeModal('modalPeminjaman')" class="text-slate-400 hover:text-red-600 p-1.5 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form onsubmit="handleFormSubmit(event, 'Peminjaman Alat')" class="p-6 space-y-4 text-xs">
            <input type="hidden" name="csrf_token" value="<?= getCsrfToken(); ?>">
            <input type="hidden" id="pinjam_edit_id" value="">
            <?php if (!empty($user['peran']) && $user['peran'] === 'admin_sekolah'): ?>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Jurusan / Departemen</label>
                <select id="pinjam_jurusan_id" onchange="filterBarangSelectByJurusan('pinjam_barang_id', this.value)" class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600">
                    <option value="">-- Semua Jurusan --</option>
                </select>
            </div>
            <?php endif; ?>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Pilih Alat Inventaris <span class="text-red-500">*</span></label>
                <select id="pinjam_barang_id" required class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600">
                    <option value="">-- Pilih Alat --</option>
                </select>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Peminjam (Siswa) <span class="text-red-500">*</span></label>
                    <input type="text" id="pinjam_peminjam" required class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600" placeholder="Ahmad Rizki (XI TKJ 1)">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Jumlah Pinjam <span class="text-red-500">*</span></label>
                    <input type="number" id="pinjam_jumlah" min="1" value="1" required class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-bold text-slate-800 focus:outline-none focus:border-sage-600">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Tugas / Keperluan Praktik</label>
                    <input type="text" id="pinjam_tugas" class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600" placeholder="contoh: Praktik Jaringan Komputer">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Tahun Ajaran (Siswa) <span class="text-red-500">*</span></label>
                    <input type="text" id="pinjam_tahun_ajaran" value="2025/2026" required class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600" placeholder="2025/2026">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Tanggal Meminjam</label>
                    <input type="datetime-local" id="pinjam_tanggal_pinjam" value="<?= date('Y-m-d\TH:i'); ?>" onclick="try{this.showPicker()}catch(e){}" class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600 cursor-pointer">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Tanggal Pengembalian (Balik)</label>
                    <input type="datetime-local" id="pinjam_tanggal_kembali" onclick="try{this.showPicker()}catch(e){}" class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600 cursor-pointer">
                </div>
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Status Peminjaman</label>
                <select id="pinjam_status" class="w-full px-3.5 py-2.5 bg-sage-50/50 border border-sage-200 rounded-xl font-bold text-slate-800 focus:outline-none focus:border-sage-600">
                    <option value="dipinjam">Dipinjam (Masih Dipinjam)</option>
                    <option value="dikembalikan">Dikembalikan (Sudah Kembali)</option>
                </select>
            </div>
            <div class="pt-3 flex justify-end gap-3 border-t border-sage-100">
                <button type="button" onclick="closeModal('modalPeminjaman')" class="px-4 py-2 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2 bg-sage-600 text-white font-bold rounded-xl shadow-md shadow-sage-600/20 hover:bg-sage-700">Kirim Pengajuan</button>
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

<!-- MODAL KONFIRMASI PENGEMBALIAN ALAT + UPLOAD FOTO BUKTI (OPSIONAL / NULL) -->
<div id="modalConfirmPengembalian" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-4 overflow-y-auto bg-slate-900/40 backdrop-blur-sm animate-fade-in-up">
    <div class="bg-white rounded-3xl border border-sage-200 shadow-2xl w-full max-w-sm overflow-hidden">
        <form id="formConfirmPengembalian" onsubmit="executeKembalikanPeminjaman(event)" class="p-6 text-center">
            <input type="hidden" name="csrf_token" value="<?= getCsrfToken(); ?>">
            <input type="hidden" id="confirm_kembali_id" value="">

            <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto mb-3.5 shadow-sm">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>

            <h3 class="text-base font-bold text-slate-800 mb-1">Konfirmasi Pengembalian Alat</h3>
            <p class="text-xs text-slate-500 mb-4 leading-relaxed" id="confirmKembaliMessage">
                Apakah Anda yakin barang/alat ini telah dikembalikan oleh peminjam?
            </p>

            <div class="text-left mb-5">
                <label class="block font-bold text-slate-700 text-xs mb-1">Bukti Foto Pengembalian <span class="text-slate-400 font-normal">(Opsional / Boleh Kosong)</span></label>
                <input type="file" id="confirm_kembali_bukti_foto" accept="image/*" class="w-full px-3 py-2 bg-sage-50/50 border border-sage-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-sage-600 text-xs cursor-pointer file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700">
                <p class="text-[10px] text-slate-400 mt-1">*Jika diunggah, foto dikompresi menjadi format file <strong>.dat</strong></p>
            </div>

            <div class="flex items-center justify-center gap-2.5">
                <button type="button" onclick="closeModal('modalConfirmPengembalian')" class="px-4 py-2 bg-red-600 text-white font-bold text-xs rounded-xl hover:bg-red-700 transition-colors w-1/2">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white font-bold text-xs rounded-xl shadow-md shadow-emerald-600/20 hover:bg-emerald-700 transition-colors w-1/2 flex items-center justify-center gap-1">
                    <span>Ya, Kembalikan</span>
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
function formatForDateTimeLocal(dateStr) {
    if (!dateStr) return '';
    const d = new Date(dateStr);
    if (isNaN(d.getTime())) return '';
    const pad = n => String(n).padStart(2, '0');
    return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
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
    el.innerHTML = '<option value="">-- Pilih Barang --</option>' +
        filtered.map(b => `<option value="${b.id}">${b.nama_barang} (Tersedia: ${b.stok_tersedia} ${b.satuan || 'Unit'})</option>`).join('');
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

    if ((modalId === 'modalBarangMasuk' || modalId === 'modalBarangKeluar' || modalId === 'modalPeminjaman') && window.dbBarang) {
        const selectElem = modal.querySelector('select');
        if (selectElem) {
            selectElem.innerHTML = '<option value="">-- Pilih Barang --</option>' +
                window.dbBarang.map(b => `<option value="${b.id}">${b.nama_barang} (Tersedia: ${b.stok_tersedia} ${b.satuan || 'Unit'})</option>`).join('');
        }
    }

    if (window.dbJurusan) {
        const isAdminSekolah = window.currentUser && window.currentUser.peran === 'admin_sekolah';
        const userJurusanId = window.currentUser ? window.currentUser.jurusan_id : null;

        ['pengguna_jurusan_id', 'kategori_jurusan_id', 'rak_jurusan_id', 'barang_jurusan_id', 'masuk_jurusan_id', 'keluar_jurusan_id', 'pinjam_jurusan_id'].forEach(selectId => {
            const el = document.getElementById(selectId);
            if (el) {
                if (isAdminSekolah) {
                    el.removeAttribute('disabled');
                    el.innerHTML = '<option value="">-- Semua / Pilih Jurusan --</option>' +
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
            }
        });
    }

    if (modalId === 'modalBarang') {
        const selectJur = document.getElementById('barang_jurusan_id');
        const initialJurId = editData ? editData.jurusan_id : (selectJur ? selectJur.value : null);
        filterKategoriAndRakByJurusan(initialJurId, editData ? editData.kategori_id : null, editData ? editData.rak_id : null);
    }

    // Set judul modal
    if (customTitle) {
        const titleElem = modal.querySelector('h3');
        if (titleElem) titleElem.innerText = customTitle;
    }

    // Reset semua field terlebih dahulu
    const form = modal.querySelector('form');
    if (form) form.reset();

    const populateFields = () => {
        if (editData) {
            if (modalId === 'modalJurusan') {
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
                const elKategori = document.getElementById('barang_kategori_id');
                const elRak = document.getElementById('barang_rak_id');
                const elMerek = document.getElementById('barang_merek');
                const elStok = document.getElementById('barang_stok');
                const elSatuan = document.getElementById('barang_satuan');

                if (elId) elId.value = editData.id || '';
                if (elJurusan) elJurusan.value = editData.jurusan_id || '';
                if (elBarcode) elBarcode.value = editData.barcode || '';
                if (elNama) elNama.value = editData.nama_barang || '';
                filterKategoriAndRakByJurusan(editData.jurusan_id || '', editData.kategori_id || '', editData.rak_id || '');
                if (elMerek) elMerek.value = editData.merek || '';
                if (elStok) elStok.value = editData.stok_total || 0;
                if (elSatuan) elSatuan.value = editData.satuan || 'Unit';
            }
            else if (modalId === 'modalBarangMasuk') {
                const elId = document.getElementById('masuk_edit_id');
                const elJurusan = document.getElementById('masuk_jurusan_id');
                const elBarang = document.getElementById('masuk_barang_id');
                const elPemasok = document.getElementById('masuk_pemasok');
                const elJumlah = document.getElementById('masuk_jumlah');

                if (elId) elId.value = editData.id || '';
                if (elJurusan) elJurusan.value = editData.jurusan_id || '';
                if (elBarang) elBarang.value = editData.barang_id || '';
                if (elPemasok) elPemasok.value = editData.nama_pemasok || '';
                if (elJumlah) elJumlah.value = editData.jumlah || 1;
            }
            else if (modalId === 'modalBarangKeluar') {
                const elId = document.getElementById('keluar_edit_id');
                const elJurusan = document.getElementById('keluar_jurusan_id');
                const elBarang = document.getElementById('keluar_barang_id');
                const elPenerima = document.getElementById('keluar_penerima');
                const elJumlah = document.getElementById('keluar_jumlah');
                const elKet = document.getElementById('keluar_keterangan');

                if (elId) elId.value = editData.id || '';
                if (elJurusan) elJurusan.value = editData.jurusan_id || '';
                if (elBarang) elBarang.value = editData.barang_id || '';
                if (elPenerima) elPenerima.value = editData.nama_penerima || '';
                if (elJumlah) elJumlah.value = editData.jumlah || 1;
                if (elKet) elKet.value = editData.catatan || '';
            }
            else if (modalId === 'modalPeminjaman') {
                const elId = document.getElementById('pinjam_edit_id');
                const elJurusan = document.getElementById('pinjam_jurusan_id');
                const elBarang = document.getElementById('pinjam_barang_id');
                const elPeminjam = document.getElementById('pinjam_peminjam');
                const elJumlah = document.getElementById('pinjam_jumlah');
                const elTugas = document.getElementById('pinjam_tugas');
                const elTahun = document.getElementById('pinjam_tahun_ajaran');
                const elTglPinjam = document.getElementById('pinjam_tanggal_pinjam');
                const elTglKembali = document.getElementById('pinjam_tanggal_kembali');
                const elStatus = document.getElementById('pinjam_status');

                if (elId) elId.value = editData.id || '';
                if (elJurusan) elJurusan.value = editData.jurusan_id || '';
                if (elBarang) elBarang.value = editData.barang_id || '';
                if (elPeminjam) elPeminjam.value = editData.nama_peminjam || '';
                if (elJumlah) elJumlah.value = editData.jumlah || 1;
                if (elTugas) elTugas.value = editData.keperluan_tugas || '';
                if (elTglPinjam) elTglPinjam.value = formatForDateTimeLocal(editData.tanggal_pinjam);
                if (elTglKembali) elTglKembali.value = formatForDateTimeLocal(editData.tanggal_kembali);
                if (elStatus) elStatus.value = editData.status || 'dipinjam';
                if (elTahun) elTahun.value = editData.tahun_ajaran || '2025/2026';

                // Jika status sudah 'dikembalikan', KUNCI (disable) semua field kecuali Tanggal dan Status!
                const isReturned = (editData.status === 'dikembalikan');
                [elJurusan, elBarang, elPeminjam, elJumlah, elTugas, elTahun].forEach(el => {
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
            ['jurusan_edit_id', 'pengguna_edit_id', 'kategori_edit_id', 'rak_edit_id', 'barang_edit_id', 'masuk_edit_id', 'keluar_edit_id', 'pinjam_edit_id'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.value = '';
            });

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
                const elNama = document.getElementById('pengguna_nama_pengguna');
                const elLengkap = document.getElementById('pengguna_nama_lengkap');
                const elPrefix = document.getElementById('pengguna_email_prefix');
                const elDomain = document.getElementById('pengguna_email_domain');
                const elPeran = document.getElementById('pengguna_peran');
                const elPass = document.getElementById('pengguna_password');
                const elTel = document.getElementById('pengguna_telepon');
                const elFoto = document.getElementById('pengguna_foto');
                const pwHint = document.getElementById('pengguna_password_hint');

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

                if (elKategori) elKategori.value = '';
                if (elRak) elRak.value = '';
                if (elSatuan) elSatuan.value = 'Unit';
                if (elBarcode) elBarcode.value = window.nextCodes?.barcode || '';
            } else if (modalId === 'modalBarangMasuk') {
                const elPemasok = document.getElementById('masuk_pemasok');
                const elJumlah = document.getElementById('masuk_jumlah');
                if (elPemasok) elPemasok.value = '';
                if (elJumlah) elJumlah.value = 5;
            } else if (modalId === 'modalBarangKeluar') {
                const elPenerima = document.getElementById('keluar_penerima');
                const elJumlah = document.getElementById('keluar_jumlah');
                const elKet = document.getElementById('keluar_keterangan');
                if (elPenerima) elPenerima.value = '';
                if (elJumlah) elJumlah.value = 1;
                if (elKet) elKet.value = '';
            } else if (modalId === 'modalPeminjaman') {
                const elJurusan = document.getElementById('pinjam_jurusan_id');
                const elBarang = document.getElementById('pinjam_barang_id');
                const elPeminjam = document.getElementById('pinjam_peminjam');
                const elJumlah = document.getElementById('pinjam_jumlah');
                const elTugas = document.getElementById('pinjam_tugas');
                const elTglPinjam = document.getElementById('pinjam_tanggal_pinjam');
                const elTglKembali = document.getElementById('pinjam_tanggal_kembali');
                const elStatus = document.getElementById('pinjam_status');

                [elJurusan, elBarang, elPeminjam, elJumlah, elTugas, elTglPinjam, elTglKembali, elStatus].forEach(el => {
                    if (el) {
                        el.disabled = false;
                        el.classList.remove('bg-slate-100/80', 'cursor-not-allowed');
                    }
                });

                if (elPeminjam) elPeminjam.value = window.currentUser?.nama_lengkap || window.currentUser?.nama_pengguna || '';
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
        formData.append('kategori_id', document.getElementById('barang_kategori_id')?.value || '');
        formData.append('rak_id', document.getElementById('barang_rak_id')?.value || '');
        formData.append('merek', document.getElementById('barang_merek')?.value || '');
        formData.append('barcode', document.getElementById('barang_barcode')?.value || '');
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
        formData.append('nama_pemasok', document.getElementById('masuk_pemasok')?.value || '');
        formData.append('jumlah', document.getElementById('masuk_jumlah')?.value || '1');
    } else if (actionName === 'Peminjaman Alat' || actionName === 'Peminjaman') {
        apiAction = 'save_peminjaman';
        formData.append('id', document.getElementById('pinjam_edit_id')?.value || '');
        formData.append('jurusan_id', document.getElementById('pinjam_jurusan_id')?.value || '');
        formData.append('barang_id', document.getElementById('pinjam_barang_id')?.value || '');
        formData.append('peminjam', document.getElementById('pinjam_peminjam')?.value || '');
        formData.append('jumlah', document.getElementById('pinjam_jumlah')?.value || '1');
        formData.append('tugas', document.getElementById('pinjam_tugas')?.value || '');
        formData.append('tahun_ajaran', document.getElementById('pinjam_tahun_ajaran')?.value || '2025/2026');
        formData.append('tanggal_pinjam', document.getElementById('pinjam_tanggal_pinjam')?.value || '');
        formData.append('tanggal_kembali', document.getElementById('pinjam_tanggal_kembali')?.value || '');
        formData.append('status', document.getElementById('pinjam_status')?.value || 'dipinjam');
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
        msgElem.innerText = `Apakah Anda yakin barang/alat "${item.nama_barang || 'ini'}" (${item.jumlah || 1} ${item.satuan || 'Unit'}) telah dikembalikan oleh ${item.nama_peminjam || 'peminjam'}?`;
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
