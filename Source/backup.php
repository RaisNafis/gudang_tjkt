<?php
// backup.php - Endpoint untuk membuat dan mengunduh backup seluruh database SQL
date_default_timezone_set('Asia/Jakarta');

require_once __DIR__ . '/app/helpers/auth.php';
require_once __DIR__ . '/app/config/database.php';
require_once __DIR__ . '/app/models/LogAktivitas.php';

if (!isLoggedIn()) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Sesi telah berakhir, silakan login kembali.']);
    exit;
}

$currentUser = currentUser();
$userPeran = $currentUser['peran'] ?? '';
$isSuperAdmin = ($userPeran === 'admin_sekolah');
$isOriginalAdmin = !empty($_SESSION['admin_sekolah_original_id']);

// Hanya Admin Sekolah (Super Admin) atau wewenang admin yang diizinkan membackup database
if (!$isSuperAdmin && !$isOriginalAdmin && !in_array($userPeran, ['admin_sekolah', 'admin_jurusan', 'kabeng', 'admin'])) {
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Akses ditolak! Anda tidak memiliki izin untuk membackup database.']);
    exit;
}

// Validasi CSRF Token
$csrfToken = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? $_GET['csrf_token'] ?? '';
if (!validateCsrfToken($csrfToken)) {
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Token CSRF tidak valid!']);
    exit;
}

try {
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    if (!$pdo) {
        throw new Exception("Gagal terhubung ke database server.");
    }

    $dbName = 'gudang_tkj';
    $timestamp = date('Y-m-d_H-i-s');
    $humanTime = date('Y-m-d H:i:s');
    $filename = "backup_{$dbName}_{$timestamp}.sql";

    // Alokasi memori dan batas waktu untuk backup penuh
    @ini_set('memory_limit', '512M');
    @set_time_limit(300);

    // Bersihkan buffer sebelumnya jika ada
    while (ob_get_level()) {
        ob_end_clean();
    }

    // Set Header Response Download Attachment
    header('Content-Type: application/sql; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

    // Header komentar SQL
    echo "-- ========================================================\n";
    echo "-- BACKUP DATABASE SISTEM INVENTARIS GUDANG SEKOLAH\n";
    echo "-- Database  : `{$dbName}`\n";
    echo "-- Tanggal   : {$humanTime} WIB\n";
    echo "-- Pengguna  : " . ($currentUser['nama_lengkap'] ?? $currentUser['nama_pengguna'] ?? 'Admin') . " (" . ($userPeran) . ")\n";
    echo "-- Server    : MySQL / MariaDB (phpMyAdmin Compatible)\n";
    echo "-- ========================================================\n\n";

    echo "SET FOREIGN_KEY_CHECKS = 0;\n";
    echo "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
    echo "SET time_zone = \"+07:00\";\n";
    echo "SET NAMES utf8mb4;\n\n";

    // Dapatkan semua tabel dalam database
    $stmtTables = $pdo->query("SHOW FULL TABLES WHERE Table_type = 'BASE TABLE'");
    $tableRows = $stmtTables->fetchAll(PDO::FETCH_NUM);
    $tables = array_map(fn($r) => $r[0], $tableRows);

    foreach ($tables as $table) {
        echo "-- --------------------------------------------------------\n";
        echo "-- Struktur dari tabel `{$table}`\n";
        echo "-- --------------------------------------------------------\n\n";
        echo "DROP TABLE IF EXISTS `{$table}`;\n";

        // Query pembuatan tabel DDL
        $stmtCreate = $pdo->query("SHOW CREATE TABLE `{$table}`");
        $createRow = $stmtCreate->fetch(PDO::FETCH_NUM);
        $createTableSql = $createRow[1] ?? '';
        echo $createTableSql . ";\n\n";

        // Hitung total baris data
        $stmtCount = $pdo->query("SELECT COUNT(*) FROM `{$table}`");
        $rowCount = (int) $stmtCount->fetchColumn();

        if ($rowCount > 0) {
            echo "--\n";
            echo "-- Dumping data untuk tabel `{$table}` ({$rowCount} baris)\n";
            echo "--\n\n";

            // Dapatkan nama kolom
            $stmtCols = $pdo->query("SHOW COLUMNS FROM `{$table}`");
            $colRows = $stmtCols->fetchAll(PDO::FETCH_ASSOC);
            $columnNames = array_map(fn($c) => '`' . $c['Field'] . '`', $colRows);
            $colsList = implode(', ', $columnNames);

            // Fetch data per-chunk agar hemat memori
            $chunkSize = 200;
            $offset = 0;

            while ($offset < $rowCount) {
                $stmtRows = $pdo->prepare("SELECT * FROM `{$table}` LIMIT :limit OFFSET :offset");
                $stmtRows->bindValue(':limit', $chunkSize, PDO::PARAM_INT);
                $stmtRows->bindValue(':offset', $offset, PDO::PARAM_INT);
                $stmtRows->execute();
                $rows = $stmtRows->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($rows)) {
                    $insertValues = [];
                    foreach ($rows as $row) {
                        $values = [];
                        foreach ($row as $val) {
                            if ($val === null) {
                                $values[] = 'NULL';
                            } else {
                                $values[] = $pdo->quote((string) $val);
                            }
                        }
                        $insertValues[] = '(' . implode(', ', $values) . ')';
                    }

                    echo "INSERT INTO `{$table}` ({$colsList}) VALUES\n" . implode(",\n", $insertValues) . ";\n\n";
                }

                $offset += $chunkSize;
            }
        } else {
            echo "-- Tabel `{$table}` tidak berisi data.\n\n";
        }
    }

    echo "-- --------------------------------------------------------\n";
    echo "SET FOREIGN_KEY_CHECKS = 1;\n";
    echo "COMMIT;\n\n";
    echo "-- ========================================================\n";
    echo "-- SELESAI: Backup Seluruh Database `{$dbName}` Berhasil\n";
    echo "-- Total Tabel: " . count($tables) . " tabel berhasil diekspor\n";
    echo "-- ========================================================\n";

    // Catat ke Log Aktivitas
    try {
        LogAktivitas::add(
            $currentUser['id'] ?? null,
            'Backup Database',
            'Berhasil membackup seluruh database inventaris (' . count($tables) . ' tabel) ke file: ' . $filename
        );
    } catch (Exception $eLog) {
        // Abaikan jika pencatatan log tidak dapat dipanggil saat streaming
    }

    exit;
} catch (Exception $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Gagal membackup database: ' . $e->getMessage()]);
    exit;
}
