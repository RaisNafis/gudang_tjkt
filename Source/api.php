<?php
// api.php - Backend API handler for AJAX CRUD requests
require_once __DIR__ . '/app/helpers/auth.php';
require_once __DIR__ . '/app/models/Pengguna.php';
require_once __DIR__ . '/app/models/Kategori.php';
require_once __DIR__ . '/app/models/Barang.php';
require_once __DIR__ . '/app/models/Rak.php';
require_once __DIR__ . '/app/models/Jurusan.php';
require_once __DIR__ . '/app/models/BarangMasuk.php';
require_once __DIR__ . '/app/models/BarangKeluar.php';
require_once __DIR__ . '/app/models/Peminjaman.php';
require_once __DIR__ . '/app/models/LogAktivitas.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Sesi telah berakhir, silakan login kembali.']);
    exit;
}

$action = $_REQUEST['action'] ?? '';
$csrfToken = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !validateCsrfToken($csrfToken)) {
    echo json_encode(['success' => false, 'message' => 'Token CSRF tidak valid!']);
    exit;
}

$userRole = $_SESSION['user']['peran'] ?? 'siswa';
$disallowedSiswaActions = [
    'save_barang', 'delete_barang', 'bulk_delete_barang', 'import_barang_csv',
    'save_kategori', 'delete_kategori', 'bulk_delete_kategori',
    'save_rak', 'delete_rak', 'bulk_delete_rak',
    'save_pengguna', 'delete_pengguna', 'bulk_delete_pengguna',
    'save_jurusan', 'delete_jurusan', 'bulk_delete_jurusan',
    'save_barang_masuk', 'delete_barang_masuk', 'bulk_delete_barang_masuk',
    'save_barang_keluar', 'delete_barang_keluar', 'bulk_delete_barang_keluar'
];

if ($userRole === 'siswa' && in_array($action, $disallowedSiswaActions)) {
    echo json_encode(['success' => false, 'message' => 'Akses ditolak! Siswa tidak memiliki izin untuk menambah, merubah, atau menghapus data ini.']);
    exit;
}

try {
    if ($action === 'login_as_user') {
        $currentUserPeran = $_SESSION['user']['peran'] ?? '';
        $isOriginalAdmin = !empty($_SESSION['admin_sekolah_original_id']);

        if ($currentUserPeran !== 'admin_sekolah' && !$isOriginalAdmin) {
            echo json_encode(['success' => false, 'message' => 'Anda tidak memiliki hak akses untuk login ke akun lain!']);
            exit;
        }

        $target_id = $_POST['user_id'] ?? '';
        $userModel = new Pengguna();
        $targetUser = $userModel->findById($target_id);

        if (!$targetUser) {
            echo json_encode(['success' => false, 'message' => 'Pengguna tidak ditemukan!']);
            exit;
        }

        if (empty($_SESSION['admin_sekolah_original_id'])) {
            $_SESSION['admin_sekolah_original_id'] = $_SESSION['user_id'];
        }

        $_SESSION['user_id'] = $targetUser['id'];
        $_SESSION['user'] = $targetUser;

        LogAktivitas::log('IMPERSONATE_USER', 'Login beralih ke akun ' . $targetUser['nama_pengguna'] . ' (' . $targetUser['peran'] . ')');
        echo json_encode(['success' => true, 'message' => 'Berhasil beralih ke akun ' . $targetUser['nama_pengguna'] . '!', 'redirect' => 'index.php']);
        exit;
    }

    if ($action === 'revert_impersonation') {
        $origId = $_SESSION['admin_sekolah_original_id'] ?? null;
        if (!empty($origId)) {
            $userModel = new Pengguna();
            $adminUser = $userModel->findById($origId);
            if ($adminUser) {
                $_SESSION['user_id'] = $adminUser['id'];
                $_SESSION['user'] = $adminUser;
                unset($_SESSION['admin_sekolah_original_id']);
                LogAktivitas::log('REVERT_IMPERSONATION', 'Kembali ke akun Admin Sekolah ' . $adminUser['nama_pengguna']);
                echo json_encode(['success' => true, 'message' => 'Berhasil kembali ke akun Admin Sekolah!', 'redirect' => 'index.php']);
                exit;
            }
        }
        echo json_encode(['success' => false, 'message' => 'Sesi Admin Sekolah tidak ditemukan.']);
        exit;
    }

    if ($action === 'get_fresh_data') {
        $isSuperAdmin = ($_SESSION['user']['peran'] ?? '') === 'admin_sekolah';
        $currentJurusanId = $isSuperAdmin ? null : ($_SESSION['user']['jurusan_id'] ?? null);

        echo json_encode([
            'success' => true,
            'dbJurusan' => Jurusan::getAll(),
            'dbPengguna' => Pengguna::getAll($currentJurusanId),
            'dbKategori' => Kategori::getAll($currentJurusanId),
            'dbRak' => Rak::getAll($currentJurusanId),
            'dbBarang' => Barang::getAll($currentJurusanId),
            'dbBarangMasuk' => BarangMasuk::getAll($currentJurusanId),
            'dbBarangKeluar' => BarangKeluar::getAll($currentJurusanId),
            'dbPeminjaman' => Peminjaman::getAll($currentJurusanId),
            'dbLogAktivitas' => LogAktivitas::getAll($currentJurusanId)
        ]);
        exit;
    }

    if ($action === 'clear_log_aktivitas') {
        $isSuperAdmin = ($_SESSION['user']['peran'] ?? '') === 'admin_sekolah';
        $userJurusanId = $isSuperAdmin ? null : ($_SESSION['user']['jurusan_id'] ?? null);
        LogAktivitas::clearAll($userJurusanId);
        echo json_encode(['success' => true, 'message' => 'Seluruh catatan log aktivitas berhasil dibersihkan!']);
        exit;
    }

    if ($action === 'save_pengguna') {
        $p = new Pengguna();
        $id = $_POST['id'] ?? '';
        $isSuperAdmin = ($_SESSION['user']['peran'] ?? '') === 'admin_sekolah';
        $jurusanId = $isSuperAdmin ? (!empty($_POST['jurusan_id']) ? $_POST['jurusan_id'] : null) : ($_SESSION['user']['jurusan_id'] ?? null);

        $peranVal = $_POST['peran'] ?? 'siswa';
        if ($peranVal === 'admin_sekolah') {
            $jurusanId = null;
        }

        $data = [
            'jurusan_id' => $jurusanId,
            'nama_pengguna' => trim($_POST['nama_pengguna'] ?? ''),
            'nama_lengkap' => trim($_POST['nama_lengkap'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'peran' => $peranVal,
            'nomor_telepon' => trim($_POST['nomor_telepon'] ?? ''),
            'password' => $_POST['password'] ?? ''
        ];

        if (empty($data['nama_pengguna']) || empty($data['nama_lengkap'])) {
            echo json_encode(['success' => false, 'message' => 'Nama pengguna dan nama lengkap wajib diisi!']);
            exit;
        }

        if (empty($id) && empty($data['password'])) {
            echo json_encode(['success' => false, 'message' => 'Kata sandi wajib diisi untuk pengguna baru!']);
            exit;
        }

        // Validesi & simpan foto profil jika diunggah
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed)) {
                echo json_encode(['success' => false, 'message' => 'Format foto profil harus JPG, JPEG, PNG, atau WEBP!']);
                exit;
            }
            if ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
                echo json_encode(['success' => false, 'message' => 'Ukuran file foto profil maksimal 2MB!']);
                exit;
            }
            $uploadDir = __DIR__ . '/uploads/avatars/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $fileName = 'avatar_' . bin2hex(random_bytes(8)) . '.dat';
            if (compressAndSaveImageAsDat($_FILES['foto']['tmp_name'], $uploadDir . $fileName, 70)) {
                $data['foto_url'] = 'uploads/avatars/' . $fileName;
            }
        }

        if (!empty($id)) {
            $ok = $p->updateAdmin($id, $data);
            if (!empty($_SESSION['user_id']) && $_SESSION['user_id'] === $id) {
                $updatedUser = $p->findById($id);
                if ($updatedUser) {
                    $_SESSION['user'] = $updatedUser;
                }
            }
            LogAktivitas::log('EDIT_PENGGUNA', 'Mengubah data pengguna ' . $data['nama_pengguna']);
            echo json_encode(['success' => (bool)$ok, 'message' => 'Data pengguna berhasil diperbarui!']);
        } else {
            if ($p->exists($data['nama_pengguna'], $data['email'])) {
                echo json_encode(['success' => false, 'message' => 'Nama pengguna atau email sudah terdaftar!']);
                exit;
            }
            $ok = $p->register($data);
            LogAktivitas::log('TAMBAH_PENGGUNA', 'Menambah pengguna baru ' . $data['nama_pengguna']);
            echo json_encode(['success' => (bool)$ok, 'message' => 'Pengguna baru berhasil ditambahkan!']);
        }
        exit;
    }

    if ($action === 'delete_profile_photo') {
        $currentUser = currentUser();
        if (!$currentUser || empty($currentUser['id'])) {
            echo json_encode(['success' => false, 'message' => 'Sesi pengguna tidak valid!']);
            exit;
        }
        $p = new Pengguna();
        $userData = $p->findById($currentUser['id']);
        if ($userData && !empty($userData['foto_url'])) {
            $fileOnDisk = __DIR__ . '/' . $userData['foto_url'];
            if (file_exists($fileOnDisk)) {
                @unlink($fileOnDisk);
            }
        }
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE pengguna SET foto_url = NULL, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
        $ok = $stmt->execute([':id' => $currentUser['id']]);
        
        $_SESSION['user']['foto_url'] = null;
        LogAktivitas::log('HAPUS_FOTO_PROFIL', 'Menghapus foto profil akun');

        echo json_encode(['success' => (bool)$ok, 'message' => 'Foto profil berhasil dihapus!']);
        exit;
    }

    if ($action === 'delete_pengguna') {
        $id = $_POST['id'] ?? '';
        $isSelf = !empty($_SESSION['user_id']) && $_SESSION['user_id'] === $id;

        $p = new Pengguna();
        $ok = $p->delete($id);
        LogAktivitas::log('HAPUS_PENGGUNA', 'Menghapus pengguna ID: ' . $id);

        if ($isSelf) {
            logoutUser();
            echo json_encode([
                'success' => true,
                'is_self_deleted' => true,
                'redirect' => 'login.php',
                'message' => 'Akun Anda telah dihapus. Mengalihkan ke halaman login...'
            ]);
            exit;
        }

        echo json_encode(['success' => (bool)$ok, 'message' => 'Data pengguna berhasil dihapus!']);
        exit;
    }

    if ($action === 'save_jurusan') {
        if (($_SESSION['user']['peran'] ?? '') !== 'admin_sekolah') {
            echo json_encode(['success' => false, 'message' => 'Hanya Admin Sekolah yang dapat mengelola data jurusan!']);
            exit;
        }
        $id = $_POST['id'] ?? '';
        $data = [
            'nama_jurusan' => trim($_POST['nama_jurusan'] ?? ''),
            'deskripsi' => trim($_POST['deskripsi'] ?? ''),
            'warna_tema' => trim($_POST['warna_tema'] ?? 'kuning')
        ];

        if (empty($data['nama_jurusan'])) {
            echo json_encode(['success' => false, 'message' => 'Nama jurusan wajib diisi!']);
            exit;
        }

        if (!empty($id)) {
            $ok = Jurusan::update($id, $data);
            LogAktivitas::log('EDIT_JURUSAN', 'Mengubah data jurusan ' . $data['nama_jurusan']);
            echo json_encode(['success' => (bool)$ok, 'message' => 'Data jurusan berhasil diperbarui!']);
        } else {
            $ok = Jurusan::create($data);
            LogAktivitas::log('TAMBAH_JURUSAN', 'Menambah jurusan baru ' . $data['nama_jurusan']);
            echo json_encode(['success' => (bool)$ok, 'message' => 'Jurusan baru berhasil ditambahkan!']);
        }
        exit;
    }

    if ($action === 'delete_jurusan') {
        if (($_SESSION['user']['peran'] ?? '') !== 'admin_sekolah') {
            echo json_encode(['success' => false, 'message' => 'Hanya Admin Sekolah yang dapat mengelola data jurusan!']);
            exit;
        }
        $id = $_POST['id'] ?? '';
        $res = Jurusan::delete($id);
        if ($res['success']) {
            LogAktivitas::log('HAPUS_JURUSAN', 'Menghapus jurusan ID: ' . $id);
        }
        echo json_encode($res);
        exit;
    }

    if ($action === 'save_kategori') {
        $id = $_POST['id'] ?? '';
        $isSuperAdmin = ($_SESSION['user']['peran'] ?? '') === 'admin_sekolah';
        $jurusan_id = $isSuperAdmin ? (!empty($_POST['jurusan_id']) ? $_POST['jurusan_id'] : null) : ($_SESSION['user']['jurusan_id'] ?? null);
        $nama = trim($_POST['nama_kategori'] ?? '');
        $desk = trim($_POST['deskripsi'] ?? '');

        if (empty($nama)) {
            echo json_encode(['success' => false, 'message' => 'Nama kategori wajib diisi!']);
            exit;
        }

        if (!empty($id)) {
            $ok = Kategori::update($id, $nama, $desk, $jurusan_id);
            LogAktivitas::log('EDIT_KATEGORI', 'Mengubah kategori ' . $nama);
            echo json_encode(['success' => (bool)$ok, 'message' => 'Kategori berhasil diperbarui!']);
        } else {
            $ok = Kategori::create($nama, $desk, $jurusan_id);
            LogAktivitas::log('TAMBAH_KATEGORI', 'Menambah kategori ' . $nama);
            echo json_encode(['success' => (bool)$ok, 'message' => 'Kategori baru berhasil ditambahkan!']);
        }
        exit;
    }

    if ($action === 'delete_kategori') {
        $id = $_POST['id'] ?? '';
        $ok = Kategori::delete($id);
        LogAktivitas::log('HAPUS_KATEGORI', 'Menghapus kategori ID: ' . $id);
        echo json_encode(['success' => (bool)$ok, 'message' => 'Kategori berhasil dihapus!']);
        exit;
    }

    if ($action === 'save_rak') {
        $id = $_POST['id'] ?? '';
        $isSuperAdmin = ($_SESSION['user']['peran'] ?? '') === 'admin_sekolah';
        $jurusan_id = $isSuperAdmin ? (!empty($_POST['jurusan_id']) ? $_POST['jurusan_id'] : null) : ($_SESSION['user']['jurusan_id'] ?? null);
        $data = [
            'jurusan_id' => $jurusan_id,
            'nama_rak' => trim($_POST['nama_rak'] ?? ''),
            'barcode' => trim($_POST['barcode'] ?? ''),
            'kategori_rak' => trim($_POST['kategori_rak'] ?? ''),
            'keterangan' => trim($_POST['keterangan'] ?? '')
        ];

        if (empty($data['nama_rak'])) {
            echo json_encode(['success' => false, 'message' => 'Nama rak penyimpanan wajib diisi!']);
            exit;
        }

        if (!empty($id)) {
            $ok = Rak::update($id, $data);
            LogAktivitas::log('EDIT_RAK', 'Mengubah data rak ' . $data['nama_rak']);
            echo json_encode(['success' => (bool)$ok, 'message' => 'Data rak berhasil diperbarui!']);
        } else {
            $ok = Rak::create($data);
            LogAktivitas::log('TAMBAH_RAK', 'Menambah rak penyimpanan baru ' . $data['nama_rak']);
            echo json_encode(['success' => (bool)$ok, 'message' => 'Rak penyimpanan baru berhasil ditambahkan!']);
        }
        exit;
    }

    if ($action === 'delete_rak') {
        $id = $_POST['id'] ?? '';
        $ok = Rak::delete($id);
        LogAktivitas::log('HAPUS_RAK', 'Menghapus rak ID: ' . $id);
        echo json_encode(['success' => (bool)$ok, 'message' => 'Data rak berhasil dihapus!']);
        exit;
    }

    if ($action === 'get_items_in_rak') {
        $rak_id = $_REQUEST['rak_id'] ?? '';
        if (empty($rak_id)) {
            echo json_encode(['success' => false, 'message' => 'ID Rak tidak valid!']);
            exit;
        }
        $rak = Rak::getById($rak_id);
        $items = Rak::getItemsInRak($rak_id);
        echo json_encode([
            'success' => true,
            'rak' => $rak,
            'items' => $items
        ]);
        exit;
    }

    if ($action === 'save_barang') {
        $id = $_POST['id'] ?? '';
        $isSuperAdmin = ($_SESSION['user']['peran'] ?? '') === 'admin_sekolah';
        $jurusan_id = $isSuperAdmin ? (!empty($_POST['jurusan_id']) ? $_POST['jurusan_id'] : null) : ($_SESSION['user']['jurusan_id'] ?? null);
        $data = [
            'jurusan_id' => $jurusan_id,
            'kategori_id' => $_POST['kategori_id'] ?? null,
            'rak_id' => $_POST['rak_id'] ?? null,
            'nama_barang' => trim($_POST['nama_barang'] ?? ''),
            'merek' => trim($_POST['merek'] ?? ''),
            'barcode' => trim($_POST['barcode'] ?? ''),
            'stok_total' => (int)($_POST['stok_total'] ?? 0),
            'satuan' => trim($_POST['satuan'] ?? 'Unit')
        ];

        if (empty($data['barcode'])) {
            $data['barcode'] = Barang::generateNextBarcode();
        }
        if (empty($data['nama_barang'])) {
            echo json_encode(['success' => false, 'message' => 'Nama barang wajib diisi!']);
            exit;
        }

        if (!empty($id)) {
            $ok = Barang::update($id, $data);
            LogAktivitas::log('EDIT_BARANG', 'Mengubah barang ' . $data['nama_barang']);
            echo json_encode(['success' => (bool)$ok, 'message' => 'Data barang berhasil diperbarui!']);
        } else {
            $ok = Barang::create($data);
            LogAktivitas::log('TAMBAH_BARANG', 'Menambah barang baru ' . $data['nama_barang']);
            echo json_encode(['success' => (bool)$ok, 'message' => 'Barang baru berhasil ditambahkan!']);
        }
        exit;
    }

require_once __DIR__ . '/app/models/BarangMasuk.php';
require_once __DIR__ . '/app/models/BarangKeluar.php';

    if ($action === 'delete_barang') {
        $id = $_POST['id'] ?? '';
        $ok = Barang::delete($id);
        LogAktivitas::log('HAPUS_BARANG', 'Menghapus barang ID: ' . $id);
        echo json_encode(['success' => (bool)$ok, 'message' => 'Data barang berhasil dihapus!']);
        exit;
    }

    if ($action === 'scan_barcode' || $action === 'get_barang_by_barcode' || $action === 'scan_result') {
        $barcode = trim($_REQUEST['barcode'] ?? $_REQUEST['query'] ?? $_REQUEST['code'] ?? '');
        if (empty($barcode)) {
            echo json_encode(['success' => false, 'message' => 'Kode barcode tidak boleh kosong.']);
            exit;
        }

        $barang = Barang::getByBarcode($barcode);
        if ($barang) {
            echo json_encode([
                'success' => true,
                'data' => $barang
            ], JSON_PRETTY_PRINT);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Barang dengan barcode tersebut tidak ditemukan.',
                'data' => null
            ]);
        }
        exit;
    }

    if ($action === 'save_barang_keluar') {
        $id = $_POST['id'] ?? '';
        $barang_id = $_POST['barang_id'] ?? '';
        $nama_penerima = trim($_POST['nama_penerima'] ?? '');
        $jumlah = (int)($_POST['jumlah'] ?? 1);
        $catatan = trim($_POST['catatan'] ?? '');
        $pengguna_id = $_SESSION['user_id'] ?? null;

        if (empty($nama_penerima) || $jumlah < 1) {
            echo json_encode(['success' => false, 'message' => 'Isi nama penerima dan jumlah minimal 1!']);
            exit;
        }

        if (!empty($id)) {
            $res = BarangKeluar::update($id, $nama_penerima, $jumlah);
            if ($res['success']) {
                LogAktivitas::log('EDIT_BARANG_KELUAR', 'Mengubah data barang keluar untuk ' . $nama_penerima);
            }
            echo json_encode($res);
        } else {
            if (empty($barang_id)) {
                echo json_encode(['success' => false, 'message' => 'Pilih barang!']);
                exit;
            }
            $res = BarangKeluar::create($barang_id, $pengguna_id, $nama_penerima, $jumlah, $catatan);
            if ($res['success']) {
                LogAktivitas::log('BARANG_KELUAR', 'Pengeluaran barang untuk ' . $nama_penerima . ' (Jumlah: ' . $jumlah . ' Unit)');
            }
            echo json_encode($res);
        }
        exit;
    }

    if ($action === 'delete_barang_keluar') {
        $id = $_POST['id'] ?? '';
        $ok = BarangKeluar::delete($id);
        LogAktivitas::log('HAPUS_BARANG_KELUAR', 'Menghapus catatan barang keluar ID: ' . $id);
        echo json_encode(['success' => (bool)$ok, 'message' => 'Catatan barang keluar berhasil dihapus!']);
        exit;
    }

    if ($action === 'save_barang_masuk') {
        $id = $_POST['id'] ?? '';
        $barang_id = $_POST['barang_id'] ?? '';
        $nama_pemasok = trim($_POST['nama_pemasok'] ?? '');
        $jumlah = (int)($_POST['jumlah'] ?? 1);
        $catatan = trim($_POST['catatan'] ?? '');
        $pengguna_id = $_SESSION['user_id'] ?? null;

        if ($jumlah < 1) {
            echo json_encode(['success' => false, 'message' => 'Isi jumlah minimal 1!']);
            exit;
        }

        if (!empty($id)) {
            $res = BarangMasuk::update($id, $nama_pemasok, $jumlah);
            if (is_array($res)) {
                if ($res['success']) {
                    LogAktivitas::log('EDIT_BARANG_MASUK', 'Mengubah data barang masuk dari ' . $nama_pemasok);
                }
                echo json_encode($res);
            } else {
                echo json_encode(['success' => (bool)$res, 'message' => $res ? 'Transaksi barang masuk berhasil diperbarui!' : 'Gagal memperbarui transaksi barang masuk.']);
            }
        } else {
            if (empty($barang_id)) {
                echo json_encode(['success' => false, 'message' => 'Pilih barang!']);
                exit;
            }
            $ok = BarangMasuk::create($barang_id, $pengguna_id, $nama_pemasok, $jumlah, $catatan);
            if ($ok) {
                LogAktivitas::log('BARANG_MASUK', 'Penerimaan barang masuk (Jumlah: ' . $jumlah . ' Unit)');
                echo json_encode(['success' => true, 'message' => 'Transaksi barang masuk berhasil disimpan!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal menyimpan transaksi barang masuk.']);
            }
        }
        exit;
    }

    if ($action === 'delete_barang_masuk') {
        $id = $_POST['id'] ?? '';
        $ok = BarangMasuk::delete($id);
        LogAktivitas::log('HAPUS_BARANG_MASUK', 'Menghapus catatan barang masuk ID: ' . $id);
        echo json_encode(['success' => (bool)$ok, 'message' => 'Catatan barang masuk berhasil dihapus!']);
        exit;
    }

    if ($action === 'import_barang_csv') {
        if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['success' => false, 'message' => 'File CSV tidak valid atau tidak terunggah.']);
            exit;
        }

        $tmpName = $_FILES['csv_file']['tmp_name'];
        $handle = fopen($tmpName, 'r');
        if (!$handle) {
            echo json_encode(['success' => false, 'message' => 'Gagal membaca file CSV.']);
            exit;
        }

        // Hapus UTF-8 BOM jika ada
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        $header = fgetcsv($handle, 1000, ',');
        if (!$header) {
            fclose($handle);
            echo json_encode(['success' => false, 'message' => 'File CSV kosong atau format tidak sesuai.']);
            exit;
        }

        $header = array_map(function($h) {
            return strtolower(trim(str_replace(['"', "'", "\xEF\xBB\xBF"], '', $h)));
        }, $header);

        $imported = 0;
        $db = Database::getInstance()->getConnection();

        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
            if (empty($row) || count($row) < 2) continue;
            
            $data = [];
            foreach ($header as $idx => $colName) {
                $data[$colName] = isset($row[$idx]) ? trim($row[$idx]) : '';
            }

            $nama_barang = $data['nama_barang'] ?? $data['nama'] ?? '';
            if (empty($nama_barang)) continue;

            $kode_barang = !empty($data['kode_barang']) ? $data['kode_barang'] : ('BRG-' . rand(100, 999));
            $merek = !empty($data['merek']) ? $data['merek'] : null;
            $barcode = !empty($data['barcode']) ? $data['barcode'] : ('899' . rand(100000000, 999999999));
            $stok_total = isset($data['stok_total']) ? (int)$data['stok_total'] : (isset($data['stok']) ? (int)$data['stok'] : 10);

            $check = $db->prepare("SELECT id FROM barang WHERE kode_barang = :kode LIMIT 1");
            $check->execute([':kode' => $kode_barang]);
            $existing = $check->fetch();

            if ($existing) {
                $upd = $db->prepare("UPDATE barang SET nama_barang = :nama, merek = :merek, barcode = :barcode, stok_total = stok_total + :stok, stok_tersedia = stok_tersedia + :stok WHERE id = :id");
                $upd->execute([
                    ':nama' => $nama_barang,
                    ':merek' => $merek,
                    ':barcode' => $barcode,
                    ':stok' => $stok_total,
                    ':id' => $existing['id']
                ]);
            } else {
                Barang::create([
                    'kode_barang' => $kode_barang,
                    'nama_barang' => $nama_barang,
                    'merek' => $merek,
                    'barcode' => $barcode,
                    'stok_total' => $stok_total,
                    'kategori_id' => null
                ]);
            }
            $imported++;
        }

        fclose($handle);

        LogAktivitas::log('IMPORT_BARANG', 'Mengimpor ' . $imported . ' data barang dari file CSV');
        echo json_encode(['success' => true, 'message' => "Berhasil mengimpor $imported data barang!"]);
        exit;
    }

    require_once __DIR__ . '/app/models/Peminjaman.php';

    if ($action === 'save_peminjaman') {
        $id = $_POST['id'] ?? '';
        $barang_id = $_POST['barang_id'] ?? '';
        $peminjam = trim($_POST['peminjam'] ?? '');
        $jumlah = (int)($_POST['jumlah'] ?? 1);
        $tugas = trim($_POST['tugas'] ?? '');
        $tahun_ajaran = trim($_POST['tahun_ajaran'] ?? '2025/2026');
        $tanggal_pinjam = $_POST['tanggal_pinjam'] ?? '';
        $tanggal_kembali = $_POST['tanggal_kembali'] ?? '';
        $status = $_POST['status'] ?? null;
        $pengguna_id = $_SESSION['user_id'] ?? null;

        if (empty($peminjam) || $jumlah < 1) {
            echo json_encode(['success' => false, 'message' => 'Isi nama peminjam dan jumlah minimal 1!']);
            exit;
        }

        if (!empty($id)) {
            $res = Peminjaman::update($id, $peminjam, $jumlah, $tanggal_pinjam, $tanggal_kembali, $tugas, $status, $tahun_ajaran);
            if ($res['success']) {
                LogAktivitas::log('EDIT_PEMINJAMAN', 'Mengubah transaksi peminjaman oleh ' . $peminjam);
            }
            echo json_encode($res);
        } else {
            if (empty($barang_id)) {
                echo json_encode(['success' => false, 'message' => 'Pilih alat!']);
                exit;
            }
            $res = Peminjaman::create($barang_id, $pengguna_id, $jumlah, $peminjam, null, $tanggal_pinjam, $tanggal_kembali, $tugas, $tahun_ajaran);
            if ($res['success']) {
                LogAktivitas::log('PEMINJAMAN_ALAT', 'Peminjaman alat oleh ' . $peminjam . ' (Jumlah: ' . $jumlah . ' Unit)');
            }
            echo json_encode($res);
        }
        exit;
    }

    if ($action === 'delete_peminjaman') {
        $id = $_POST['id'] ?? '';
        $ok = Peminjaman::delete($id);
        LogAktivitas::log('HAPUS_PEMINJAMAN', 'Menghapus catatan peminjaman ID: ' . $id);
        echo json_encode(['success' => (bool)$ok, 'message' => 'Catatan peminjaman berhasil dihapus!']);
        exit;
    }

    if ($action === 'approve_peminjaman') {
        $id = $_POST['id'] ?? '';
        if (empty($id)) {
            echo json_encode(['success' => false, 'message' => 'ID Peminjaman tidak sah!']);
            exit;
        }
        $res = Peminjaman::approveReturn($id);
        if ($res['success']) {
            LogAktivitas::log('SETUJUI_PENGEMBALIAN', 'Menyetujui pengembalian alat peminjaman ID: ' . $id);
        }
        echo json_encode($res);
        exit;
    }

    if ($action === 'reject_peminjaman') {
        $id = $_POST['id'] ?? '';
        if (empty($id)) {
            echo json_encode(['success' => false, 'message' => 'ID Peminjaman tidak sah!']);
            exit;
        }
        $res = Peminjaman::rejectReturn($id);
        if ($res['success']) {
            LogAktivitas::log('TOLAK_PENGEMBALIAN', 'Menolak pengembalian alat peminjaman ID: ' . $id);
        }
        echo json_encode($res);
        exit;
    }

    if ($action === 'return_peminjaman') {
        $id = $_POST['id'] ?? '';
        if (empty($id)) {
            echo json_encode(['success' => false, 'message' => 'ID Peminjaman tidak sah!']);
            exit;
        }

        $buktiFotoUrl = null;
        if (isset($_FILES['bukti_foto']) && $_FILES['bukti_foto']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            $ext = strtolower(pathinfo($_FILES['bukti_foto']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, $allowed)) {
                $uploadDir = __DIR__ . '/uploads/bukti/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $fileName = 'bukti_' . bin2hex(random_bytes(8)) . '.dat';
                $destPath = $uploadDir . $fileName;

                if (compressAndSaveImageAsDat($_FILES['bukti_foto']['tmp_name'], $destPath, 70)) {
                    $buktiFotoUrl = 'uploads/bukti/' . $fileName;
                }
            }
        }

        $res = Peminjaman::returnItem($id, $buktiFotoUrl);
        if ($res['success']) {
            LogAktivitas::log('PENGEMBALIAN_ALAT', 'Pengembalian alat peminjaman ID: ' . $id);
        }
        echo json_encode($res);
        exit;
    }

    if (in_array($action, ['bulk_delete_jurusan', 'bulk_delete_pengguna', 'bulk_delete_kategori', 'bulk_delete_rak', 'bulk_delete_barang', 'bulk_delete_barang_masuk', 'bulk_delete_barang_keluar', 'bulk_delete_peminjaman'])) {
        $idsRaw = $_POST['ids'] ?? '[]';
        $ids = json_decode($idsRaw, true) ?: [];
        if (empty($ids)) {
            echo json_encode(['success' => false, 'message' => 'Pilih setidaknya 1 data untuk dihapus!']);
            exit;
        }

        $deletedCount = 0;
        if ($action === 'bulk_delete_jurusan') {
            foreach ($ids as $id) { 
                $r = Jurusan::delete($id); 
                if (!empty($r['success'])) $deletedCount++; 
            }
            LogAktivitas::log('BULK_DELETE_JURUSAN', "Menghapus $deletedCount data jurusan");
        } else if ($action === 'bulk_delete_rak') {
            foreach ($ids as $id) { if (Rak::delete($id)) $deletedCount++; }
            LogAktivitas::log('BULK_DELETE_RAK', "Menghapus $deletedCount data rak penyimpanan");
        } else if ($action === 'bulk_delete_pengguna') {
            $userModel = new Pengguna();
            $currentUserId = $_SESSION['user_id'] ?? null;
            $isSelfDeleted = false;

            foreach ($ids as $id) {
                if ($userModel->delete($id)) {
                    $deletedCount++;
                    if ($id === $currentUserId) {
                        $isSelfDeleted = true;
                    }
                }
            }
            LogAktivitas::log('BULK_DELETE_PENGGUNA', "Menghapus $deletedCount data pengguna");

            if ($isSelfDeleted) {
                logoutUser();
                echo json_encode([
                    'success' => true,
                    'is_self_deleted' => true,
                    'redirect' => 'login.php',
                    'message' => 'Akun Anda termasuk data yang dihapus. Mengalihkan ke halaman login...'
                ]);
                exit;
            }
        } else if ($action === 'bulk_delete_kategori') {
            foreach ($ids as $id) { if (Kategori::delete($id)) $deletedCount++; }
            LogAktivitas::log('BULK_DELETE_KATEGORI', "Menghapus $deletedCount kategori barang");
        } else if ($action === 'bulk_delete_barang') {
            foreach ($ids as $id) { if (Barang::delete($id)) $deletedCount++; }
            LogAktivitas::log('BULK_DELETE_BARANG', "Menghapus $deletedCount data barang");
        } else if ($action === 'bulk_delete_barang_masuk') {
            foreach ($ids as $id) { if (BarangMasuk::delete($id)) $deletedCount++; }
            LogAktivitas::log('BULK_DELETE_BARANG_MASUK', "Menghapus $deletedCount transaksi barang masuk");
        } else if ($action === 'bulk_delete_barang_keluar') {
            foreach ($ids as $id) { if (BarangKeluar::delete($id)) $deletedCount++; }
            LogAktivitas::log('BULK_DELETE_BARANG_KELUAR', "Menghapus $deletedCount transaksi barang keluar");
        } else if ($action === 'bulk_delete_peminjaman') {
            foreach ($ids as $id) { if (Peminjaman::delete($id)) $deletedCount++; }
            LogAktivitas::log('BULK_DELETE_PEMINJAMAN', "Menghapus $deletedCount transaksi peminjaman");
        }

        echo json_encode(['success' => true, 'message' => "Berhasil menghapus $deletedCount data terpilih!"]);
        exit;
    }

    echo json_encode(['success' => false, 'message' => 'Aksi tidak dikenal.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]);
}
