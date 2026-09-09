<?php
/**
 * Native Scan QR Code REST API Endpoint
 * URL Examples:
 * - GET /api/scan.php?barang_id={BARANG_ID_OR_BARCODE}
 * - GET /api/scan.php?rak_id={RAK_ID_OR_BARCODE}
 * - GET /api/scan.php?code={CODE_OR_ID}
 */

// Enable output buffering for REST API responses
ob_start();

// Enable CORS for mobile apps & web scanners
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../app/config/database.php';

$barangId = $_GET['barang_id'] ?? $_POST['barang_id'] ?? null;
$rakId = $_GET['rak_id'] ?? $_POST['rak_id'] ?? null;
$code = $_GET['code'] ?? $_POST['code'] ?? $_GET['barcode'] ?? $_GET['id'] ?? null;

// Jika parameter berupa URL penuh atau relative query (contoh: http://.../api/scan.php?barang_id=XYZ)
$checkUrl = $code ?? $barangId ?? $rakId;
if (!empty($checkUrl) && (strpos($checkUrl, 'http://') === 0 || strpos($checkUrl, 'https://') === 0 || strpos($checkUrl, '?') !== false)) {
    $parsedUrl = parse_url($checkUrl);
    if (!empty($parsedUrl['query'])) {
        parse_str($parsedUrl['query'], $urlParams);
        if (!empty($urlParams['barang_id'])) {
            $barangId = $urlParams['barang_id'];
        }
        if (!empty($urlParams['rak_id'])) {
            $rakId = $urlParams['rak_id'];
        }
        if (!empty($urlParams['code'])) {
            $code = $urlParams['code'];
        } elseif (!empty($urlParams['id'])) {
            if (empty($barangId)) $barangId = $urlParams['id'];
        } elseif (!empty($urlParams['barcode'])) {
            if (empty($code)) $code = $urlParams['barcode'];
        }
    }
}

if (empty($barangId) && empty($rakId) && empty($code)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Parameter barang_id, rak_id, atau code wajib diisi!'
    ]);
    exit;
}

try {
    $db = Database::getInstance()->getConnection();

    // 1. Jika barang_id dikirimkan khusus
    if (!empty($barangId)) {
        $stmtBrg = $db->prepare("
            SELECT b.*, k.nama_kategori, r.nama_rak, j.nama_jurusan
            FROM barang b
            LEFT JOIN kategori k ON b.kategori_id = k.id
            LEFT JOIN rak r ON b.rak_id = r.id
            LEFT JOIN jurusan j ON b.jurusan_id = j.id
            WHERE b.id = :id1 OR b.barcode = :id2 OR b.kode_barang = :id3
            LIMIT 1
        ");
        $stmtBrg->execute([':id1' => $barangId, ':id2' => $barangId, ':id3' => $barangId]);
        $barang = $stmtBrg->fetch(PDO::FETCH_ASSOC);

        if ($barang) {
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'type' => 'BARANG_INVENTORY',
                'message' => 'Data barang berhasil ditemukan',
                'data' => [
                    'id' => $barang['id'],
                    'kode_barang' => $barang['kode_barang'] ?? '',
                    'nama_barang' => $barang['nama_barang'] ?? '',
                    'merek' => $barang['merek'] ?? '-',
                    'barcode' => $barang['barcode'] ?? $barangId,
                    'stok_total' => intval($barang['stok_total'] ?? 0),
                    'stok_tersedia' => intval($barang['stok_tersedia'] ?? 0),
                    'satuan' => $barang['satuan'] ?? 'Unit',
                    'jurusan_id' => $barang['jurusan_id'] ?? null,
                    'nama_jurusan' => $barang['nama_jurusan'] ?? 'Semua Jurusan',
                    'kategori_id' => $barang['kategori_id'] ?? null,
                    'nama_kategori' => $barang['nama_kategori'] ?? '-',
                    'rak_id' => $barang['rak_id'] ?? null,
                    'nama_rak' => $barang['nama_rak'] ?? '-',
                    'foto' => $barang['foto'] ?? null,
                    'created_at' => $barang['created_at'] ?? null
                ]
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            exit;
        }
    }

    // 2. Jika rak_id dikirimkan khusus
    if (!empty($rakId)) {
        $stmtRak = $db->prepare("
            SELECT r.*, j.nama_jurusan
            FROM rak r
            LEFT JOIN jurusan j ON r.jurusan_id = j.id
            WHERE r.id = :id1 OR r.barcode = :id2
            LIMIT 1
        ");
        $stmtRak->execute([':id1' => $rakId, ':id2' => $rakId]);
        $rak = $stmtRak->fetch(PDO::FETCH_ASSOC);

        if ($rak) {
            $stmtItems = $db->prepare("
                SELECT id, kode_barang, nama_barang, stok_tersedia, satuan
                FROM barang
                WHERE rak_id = :rak_id
            ");
            $stmtItems->execute([':rak_id' => $rak['id']]);
            $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

            http_response_code(200);
            echo json_encode([
                'success' => true,
                'type' => 'RAK_INVENTORY',
                'message' => 'Data rak berhasil ditemukan',
                'data' => [
                    'id' => $rak['id'],
                    'nama_rak' => $rak['nama_rak'] ?? '',
                    'barcode' => $rak['barcode'] ?? $rakId,
                    'kategori_rak' => $rak['kategori_rak'] ?? '-',
                    'jurusan_id' => $rak['jurusan_id'] ?? null,
                    'nama_jurusan' => $rak['nama_jurusan'] ?? 'Semua Jurusan',
                    'total_items' => count($items),
                    'items' => array_map(function($i) {
                        return [
                            'id' => $i['id'],
                            'kode' => $i['kode_barang'] ?? '',
                            'nama' => $i['nama_barang'] ?? '',
                            'stok_tersedia' => intval($i['stok_tersedia'] ?? 0),
                            'satuan' => $i['satuan'] ?? 'Unit'
                        ];
                    }, $items)
                ]
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            exit;
        }
    }

    // 3. Fallback jika parameter 'code' umum yang dikirimkan
    if (!empty($code)) {
        // Cek barang dahulu
        $stmtBrg = $db->prepare("
            SELECT b.*, k.nama_kategori, r.nama_rak, j.nama_jurusan
            FROM barang b
            LEFT JOIN kategori k ON b.kategori_id = k.id
            LEFT JOIN rak r ON b.rak_id = r.id
            LEFT JOIN jurusan j ON b.jurusan_id = j.id
            WHERE b.barcode = :code1 OR b.id = :code2 OR b.kode_barang = :code3
            LIMIT 1
        ");
        $stmtBrg->execute([':code1' => $code, ':code2' => $code, ':code3' => $code]);
        $barang = $stmtBrg->fetch(PDO::FETCH_ASSOC);

        if ($barang) {
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'type' => 'BARANG_INVENTORY',
                'message' => 'Data barang berhasil ditemukan',
                'data' => [
                    'id' => $barang['id'],
                    'kode_barang' => $barang['kode_barang'] ?? '',
                    'nama_barang' => $barang['nama_barang'] ?? '',
                    'merek' => $barang['merek'] ?? '-',
                    'barcode' => $barang['barcode'] ?? $code,
                    'stok_total' => intval($barang['stok_total'] ?? 0),
                    'stok_tersedia' => intval($barang['stok_tersedia'] ?? 0),
                    'satuan' => $barang['satuan'] ?? 'Unit',
                    'jurusan_id' => $barang['jurusan_id'] ?? null,
                    'nama_jurusan' => $barang['nama_jurusan'] ?? 'Semua Jurusan',
                    'kategori_id' => $barang['kategori_id'] ?? null,
                    'nama_kategori' => $barang['nama_kategori'] ?? '-',
                    'rak_id' => $barang['rak_id'] ?? null,
                    'nama_rak' => $barang['nama_rak'] ?? '-',
                    'foto' => $barang['foto'] ?? null,
                    'created_at' => $barang['created_at'] ?? null
                ]
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            exit;
        }

        // Cek rak
        $stmtRak = $db->prepare("
            SELECT r.*, j.nama_jurusan
            FROM rak r
            LEFT JOIN jurusan j ON r.jurusan_id = j.id
            WHERE r.barcode = :code1 OR r.id = :code2
            LIMIT 1
        ");
        $stmtRak->execute([':code1' => $code, ':code2' => $code]);
        $rak = $stmtRak->fetch(PDO::FETCH_ASSOC);

        if ($rak) {
            $stmtItems = $db->prepare("
                SELECT id, kode_barang, nama_barang, stok_tersedia, satuan
                FROM barang
                WHERE rak_id = :rak_id
            ");
            $stmtItems->execute([':rak_id' => $rak['id']]);
            $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

            http_response_code(200);
            echo json_encode([
                'success' => true,
                'type' => 'RAK_INVENTORY',
                'message' => 'Data rak berhasil ditemukan',
                'data' => [
                    'id' => $rak['id'],
                    'nama_rak' => $rak['nama_rak'] ?? '',
                    'barcode' => $rak['barcode'] ?? $code,
                    'kategori_rak' => $rak['kategori_rak'] ?? '-',
                    'jurusan_id' => $rak['jurusan_id'] ?? null,
                    'nama_jurusan' => $rak['nama_jurusan'] ?? 'Semua Jurusan',
                    'total_items' => count($items),
                    'items' => array_map(function($i) {
                        return [
                            'id' => $i['id'],
                            'kode' => $i['kode_barang'] ?? '',
                            'nama' => $i['nama_barang'] ?? '',
                            'stok_tersedia' => intval($i['stok_tersedia'] ?? 0),
                            'satuan' => $i['satuan'] ?? 'Unit'
                        ];
                    }, $items)
                ]
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            exit;
        }
    }

    http_response_code(404);
    echo json_encode([
        'success' => false,
        'message' => 'Data Barang / Rak tidak ditemukan.'
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
    ]);
}
