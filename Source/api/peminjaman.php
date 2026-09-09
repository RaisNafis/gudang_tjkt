<?php
/**
 * Native REST API for Peminjaman Alat (Mobile & Web Client REST API)
 * URL: http://localhost:8000/api/peminjaman.php
 */

// Enable output buffering for REST API responses
ob_start();

// Enable CORS for mobile apps and external API consumers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, X-CSRF-TOKEN");
header("Content-Type: application/json; charset=UTF-8");

// Handle Preflight OPTIONS Request for CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../app/helpers/auth.php';
require_once __DIR__ . '/../app/models/Peminjaman.php';
require_once __DIR__ . '/../app/models/Barang.php';
require_once __DIR__ . '/../app/models/LogAktivitas.php';

// Decode JSON Body Input if sent as application/json
$rawInput = file_get_contents('php://input');
$jsonBody = [];
if (!empty($rawInput)) {
    $decoded = json_decode($rawInput, true);
    if (is_array($decoded)) {
        $jsonBody = $decoded;
    }
}

// Combine $_GET, $_POST, and JSON Body inputs
$params = array_merge($_GET, $_POST, $jsonBody);
$action = $params['action'] ?? '';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Handle Auth / User Session (Optional session or header pid/token)
$userId = $_SESSION['user_id'] ?? $params['pengguna_id'] ?? $_SERVER['HTTP_X_USER_ID'] ?? null;
$userRole = $_SESSION['user']['peran'] ?? $params['user_role'] ?? 'petugas';

try {
    // ----------------------------------------------------------------------
    // 1. GET DETAIL (by id) or LIST ALL (GET Method or action=list/detail)
    // ----------------------------------------------------------------------
    if ($method === 'GET' || $action === 'list' || $action === 'detail') {
        $id = $params['id'] ?? null;

        if (!empty($id)) {
            // Get detail of single loan transaction
            $item = Peminjaman::findById($id);
            if ($item) {
                http_response_code(200);
                echo json_encode([
                    'success' => true,
                    'message' => 'Detail peminjaman berhasil diambil',
                    'data' => $item
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Data peminjaman tidak ditemukan'
                ]);
            }
            exit;
        }

        // List all loan transactions with optional filtering
        $jurusanId = $params['jurusan_id'] ?? null;
        $statusFilter = $params['status'] ?? null;
        $search = $params['search'] ?? null;

        $loans = Peminjaman::getAll($jurusanId);

        // Apply Status Filter if provided
        if (!empty($statusFilter)) {
            $loans = array_values(array_filter($loans, function($l) use ($statusFilter) {
                return strtolower($l['status']) === strtolower($statusFilter);
            }));
        }

        // Apply Search Keyword if provided
        if (!empty($search)) {
            $q = strtolower($search);
            $loans = array_values(array_filter($loans, function($l) use ($q) {
                return str_contains(strtolower($l['nama_peminjam'] ?? ''), $q) ||
                       str_contains(strtolower($l['nama_barang'] ?? ''), $q) ||
                       str_contains(strtolower($l['tugas'] ?? ''), $q) ||
                       str_contains(strtolower($l['nama_petugas'] ?? ''), $q);
            }));
        }

        // Apply Pagination (page & limit)
        $page = isset($params['page']) ? max(1, intval($params['page'])) : null;
        $limit = isset($params['limit']) ? max(1, min(100, intval($params['limit']))) : null;

        $totalRecords = count($loans);
        $paginatedLoans = $loans;
        $paginationMeta = null;

        if ($page !== null || $limit !== null) {
            $page = $page ?? 1;
            $limit = $limit ?? 20;
            $totalPages = (int)ceil($totalRecords / $limit) ?: 1;
            $offset = ($page - 1) * $limit;
            $paginatedLoans = array_slice($loans, $offset, $limit);

            $paginationMeta = [
                'current_page' => $page,
                'per_page' => $limit,
                'total_records' => $totalRecords,
                'total_pages' => $totalPages
            ];
        }

        http_response_code(200);
        echo json_encode([
            'success' => true,
            'total' => $totalRecords,
            'message' => 'Daftar peminjaman berhasil diambil',
            'pagination' => $paginationMeta,
            'data' => $paginatedLoans
        ]);
        exit;
    }

    // ----------------------------------------------------------------------
    // 2. CREATE NEW LOAN (POST method or action=create)
    // ----------------------------------------------------------------------
    if (($method === 'POST' && (empty($action) || $action === 'create' || $action === 'save')) || $action === 'save_peminjaman') {
        $barangId = $params['barang_id'] ?? null;
        $namaPeminjam = $params['nama_peminjam'] ?? null;
        $jumlah = intval($params['jumlah'] ?? 1);
        $tugas = $params['tugas'] ?? $params['keperluan'] ?? null;
        $catatan = $params['catatan'] ?? null;
        $tanggalPinjam = $params['tanggal_pinjam'] ?? null;
        $tanggalKembali = $params['tanggal_kembali'] ?? null;

        if (empty($barangId) || empty($namaPeminjam)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Parameter barang_id dan nama_peminjam wajib diisi!'
            ]);
            exit;
        }

        // Validasi jurusan pengguna vs jurusan barang
        $userSession = currentUser();
        if (!empty($userSession['peran']) && $userSession['peran'] !== 'admin_sekolah' && !empty($userSession['jurusan_id'])) {
            $dbConn = Database::getInstance()->getConnection();
            $stmtCheckBrg = $dbConn->prepare("
                SELECT b.id, b.nama_barang, b.jurusan_id, j.nama_jurusan 
                FROM barang b 
                LEFT JOIN jurusan j ON b.jurusan_id = j.id 
                WHERE b.id = :bid LIMIT 1
            ");
            $stmtCheckBrg->execute([':bid' => $barangId]);
            $brgData = $stmtCheckBrg->fetch(PDO::FETCH_ASSOC);
            if ($brgData && !empty($brgData['jurusan_id']) && $brgData['jurusan_id'] !== $userSession['jurusan_id']) {
                $targetJur = $brgData['nama_jurusan'] ?? 'jurusan lain';
                $myJur = $userSession['nama_jurusan'] ?? 'jurusan Anda';
                http_response_code(403);
                echo json_encode([
                    'success' => false,
                    'message' => "Peminjaman ditolak! Akun Anda terdaftar pada {$myJur}. Anda hanya dapat meminjam alat & bahan milik jurusan Anda. Barang ini terdaftar pada {$targetJur}."
                ]);
                exit;
            }
        }

        $tahunAjaran = $params['tahun_ajaran'] ?? '2025/2026';
        $res = Peminjaman::create($barangId, $userId, $jumlah, $namaPeminjam, $catatan, $tanggalPinjam, $tanggalKembali, $tugas, $tahunAjaran);

        if ($res['success']) {
            LogAktivitas::log('TAMBAH_PEMINJAMAN', 'Menambahkan peminjaman barang ' . $namaPeminjam);
            http_response_code(201);
            echo json_encode([
                'success' => true,
                'message' => $res['message']
            ]);
        } else {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $res['message']
            ]);
        }
        exit;
    }

    // ----------------------------------------------------------------------
    // 3. RETURN BORROWED ITEM (action=return / return_peminjaman)
    // ----------------------------------------------------------------------
    if ($action === 'return' || $action === 'return_peminjaman' || $action === 'kembalikan') {
        $id = $params['id'] ?? $params['peminjaman_id'] ?? null;

        if (empty($id)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Parameter id transaksi peminjaman wajib diisi!'
            ]);
            exit;
        }

        $buktiFotoUrl = null;

        // Process Upload Bukti Foto (jika diunggah), kompresi ke format .dat
        if (isset($_FILES['bukti_foto']) && $_FILES['bukti_foto']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['bukti_foto'];
            $uploadDir = __DIR__ . '/../uploads/bukti/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $extension = '.dat';
            $filename = 'bukti_' . time() . '_' . bin2hex(random_bytes(4)) . $extension;
            $destination = $uploadDir . $filename;

            // Gunakan helper kompresi foto ke format .dat
            if (compressAndSaveImageAsDat($file['tmp_name'], $destination, 70)) {
                $buktiFotoUrl = 'uploads/bukti/' . $filename;
            } else {
                move_uploaded_file($file['tmp_name'], $destination);
                $buktiFotoUrl = 'uploads/bukti/' . $filename;
            }
        }

        $res = Peminjaman::returnItem($id, $buktiFotoUrl);

        if ($res['success']) {
            LogAktivitas::log('KEMBALIKAN_PEMINJAMAN', 'Mengonfirmasi pengembalian alat (ID: ' . $id . ')');
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => $res['message'],
                'bukti_foto' => $buktiFotoUrl
            ]);
        } else {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $res['message']
            ]);
        }
        exit;
    }

    // ----------------------------------------------------------------------
    // 4. UPDATE LOAN TRANSACTION (PUT method or action=update)
    // ----------------------------------------------------------------------
    if ($method === 'PUT' || $action === 'update' || $action === 'edit_peminjaman') {
        $id = $params['id'] ?? null;
        $namaPeminjam = $params['nama_peminjam'] ?? null;
        $jumlah = intval($params['jumlah'] ?? 1);
        $tugas = $params['tugas'] ?? null;
        $tanggalPinjam = $params['tanggal_pinjam'] ?? null;
        $tanggalKembali = $params['tanggal_kembali'] ?? null;
        $status = $params['status'] ?? null;
        $tahunAjaran = $params['tahun_ajaran'] ?? null;

        if (empty($id) || empty($namaPeminjam)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Parameter id dan nama_peminjam wajib diisi!'
            ]);
            exit;
        }

        $res = Peminjaman::update($id, $namaPeminjam, $jumlah, $tanggalPinjam, $tanggalKembali, $tugas, $status, $tahunAjaran);

        if ($res['success']) {
            LogAktivitas::log('EDIT_PEMINJAMAN', 'Memperbarui transaksi peminjaman ' . $namaPeminjam);
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => $res['message']
            ]);
        } else {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $res['message']
            ]);
        }
        exit;
    }

    if ($action === 'approve' || $action === 'approve_peminjaman') {
        $id = $params['id'] ?? null;
        if (empty($id)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Parameter id wajib diisi!']);
            exit;
        }
        $res = Peminjaman::approveReturn($id);
        if ($res['success']) {
            LogAktivitas::log('SETUJUI_PENGEMBALIAN', 'Menyetujui pengembalian alat peminjaman ID: ' . $id);
            http_response_code(200);
            echo json_encode(['success' => true, 'message' => $res['message'], 'id' => $id]);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $res['message']]);
        }
        exit;
    }

    if ($action === 'reject' || $action === 'reject_peminjaman') {
        $id = $params['id'] ?? null;
        if (empty($id)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Parameter id wajib diisi!']);
            exit;
        }
        $res = Peminjaman::rejectReturn($id);
        if ($res['success']) {
            LogAktivitas::log('TOLAK_PENGEMBALIAN', 'Menolak pengembalian alat peminjaman ID: ' . $id);
            http_response_code(200);
            echo json_encode(['success' => true, 'message' => $res['message'], 'id' => $id]);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $res['message']]);
        }
        exit;
    }

    // ----------------------------------------------------------------------
    // 5. DELETE LOAN TRANSACTION (DELETE method or action=delete)
    // ----------------------------------------------------------------------
    if ($method === 'DELETE' || $action === 'delete' || $action === 'delete_peminjaman') {
        $id = $params['id'] ?? null;

        if (empty($id)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Parameter id transaksi peminjaman wajib diisi!'
            ]);
            exit;
        }

        $res = Peminjaman::delete($id);

        if ($res) {
            LogAktivitas::log('HAPUS_PEMINJAMAN', 'Menghapus transaksi peminjaman (ID: ' . $id . ')');
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Data peminjaman berhasil dihapus'
            ]);
        } else {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Gagal menghapus data peminjaman'
            ]);
        }
        exit;
    }

    // Invalid action fallback
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Action atau HTTP Method tidak valid.'
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
    ]);
}
