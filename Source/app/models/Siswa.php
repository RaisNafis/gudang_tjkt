<?php
// app/models/Siswa.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/Pengguna.php';

class Siswa {
    public static function getAll($jurusan_id = null) {
        $db = Database::getInstance()->getConnection();
        if (!$db) return [];

        if (!empty($jurusan_id)) {
            $stmt = $db->prepare("
                SELECT s.id, s.nisn, s.nama_siswa, s.nama_lengkap, s.token, s.kelas, s.jurusan_id, s.tahun_ajaran, j.nama_jurusan 
                FROM siswa s 
                LEFT JOIN jurusan j ON s.jurusan_id = j.id 
                WHERE s.jurusan_id = :jid 
                ORDER BY s.kelas ASC, s.nama_lengkap ASC
            ");
            $stmt->execute([':jid' => $jurusan_id]);
        } else {
            $stmt = $db->query("
                SELECT s.id, s.nisn, s.nama_siswa, s.nama_lengkap, s.token, s.kelas, s.jurusan_id, s.tahun_ajaran, j.nama_jurusan 
                FROM siswa s 
                LEFT JOIN jurusan j ON s.jurusan_id = j.id 
                ORDER BY s.kelas ASC, s.nama_lengkap ASC
            ");
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public static function count($jurusan_id = null) {
        $db = Database::getInstance()->getConnection();
        if (!$db) return 0;
        if (!empty($jurusan_id)) {
            $stmt = $db->prepare("SELECT COUNT(*) FROM siswa WHERE jurusan_id = :jid");
            $stmt->execute([':jid' => $jurusan_id]);
            return (int)$stmt->fetchColumn();
        }
        return (int)$db->query("SELECT COUNT(*) FROM siswa")->fetchColumn();
    }

    public static function getById($id) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("
            SELECT s.*, j.nama_jurusan 
            FROM siswa s 
            LEFT JOIN jurusan j ON s.jurusan_id = j.id 
            WHERE s.id = :id LIMIT 1
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function findByToken($token) {
        $db = Database::getInstance()->getConnection();
        if (!$db || empty($token)) return null;
        $stmt = $db->prepare("
            SELECT s.*, j.nama_jurusan 
            FROM siswa s 
            LEFT JOIN jurusan j ON s.jurusan_id = j.id 
            WHERE s.token = :token LIMIT 1
        ");
        $stmt->execute([':token' => trim($token)]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function findByNisn($nisn) {
        $db = Database::getInstance()->getConnection();
        if (!$db || empty($nisn)) return null;
        $stmt = $db->prepare("
            SELECT s.*, j.nama_jurusan 
            FROM siswa s 
            LEFT JOIN jurusan j ON s.jurusan_id = j.id 
            WHERE s.nisn = :nisn LIMIT 1
        ");
        $stmt->execute([':nisn' => trim($nisn)]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function generateUniqueToken() {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $token = substr(str_shuffle(str_repeat($chars, 5)), 0, 5);
        
        $db = Database::getInstance()->getConnection();
        if (!$db) return $token;
        $stmt = $db->prepare("SELECT COUNT(*) FROM siswa WHERE token = :t");
        $stmt->execute([':t' => $token]);
        if ($stmt->fetchColumn() > 0) {
            return self::generateUniqueToken();
        }
        return $token;
    }

    public static function save($data) {
        $db = Database::getInstance()->getConnection();
        $id = !empty($data['id']) ? $data['id'] : generateUuid();
        $token = !empty($data['token']) ? trim($data['token']) : self::generateUniqueToken();
        $namaLengkap = !empty($data['nama_lengkap']) ? trim($data['nama_lengkap']) : trim($data['nama_siswa']);
        $nisn = !empty($data['nisn']) ? trim($data['nisn']) : null;

        if (!empty($data['id'])) {
            $stmt = $db->prepare("
                UPDATE siswa SET 
                    nisn = :nisn,
                    nama_siswa = :nama_siswa,
                    nama_lengkap = :nama_lengkap,
                    token = :token,
                    kelas = :kelas,
                    jurusan_id = :jurusan_id,
                    tahun_ajaran = :tahun_ajaran,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :id
            ");
            $ok = $stmt->execute([
                ':nisn' => $nisn,
                ':nama_siswa' => trim($data['nama_siswa']),
                ':nama_lengkap' => $namaLengkap,
                ':token' => $token,
                ':kelas' => trim($data['kelas']),
                ':jurusan_id' => !empty($data['jurusan_id']) ? $data['jurusan_id'] : null,
                ':tahun_ajaran' => !empty($data['tahun_ajaran']) ? trim($data['tahun_ajaran']) : '2026/2027',
                ':id' => $id
            ]);
        } else {
            $stmt = $db->prepare("
                INSERT INTO siswa (id, nisn, nama_siswa, nama_lengkap, token, kelas, jurusan_id, tahun_ajaran)
                VALUES (:id, :nisn, :nama_siswa, :nama_lengkap, :token, :kelas, :jurusan_id, :tahun_ajaran)
            ");
            $ok = $stmt->execute([
                ':id' => $id,
                ':nisn' => $nisn,
                ':nama_siswa' => trim($data['nama_siswa']),
                ':nama_lengkap' => $namaLengkap,
                ':token' => $token,
                ':kelas' => trim($data['kelas']),
                ':jurusan_id' => !empty($data['jurusan_id']) ? $data['jurusan_id'] : null,
                ':tahun_ajaran' => !empty($data['tahun_ajaran']) ? trim($data['tahun_ajaran']) : '2026/2027'
            ]);
        }

        if ($ok) {
            Pengguna::syncFromSiswa($id);
            return $id;
        }
        return false;
    }

    public static function delete($id) {
        $db = Database::getInstance()->getConnection();
        Pengguna::deleteBySiswaId($id);
        $stmt = $db->prepare("DELETE FROM siswa WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Resolusi ID Jurusan dari string nama kelas (misal: '10-DKV 1', '11-TKJ', '12-TSM')
     */
    public static function resolveJurusanIdFromKelas($kelas, $jurusanCache = null) {
        $db = Database::getInstance()->getConnection();
        if ($jurusanCache === null) {
            $jurusanCache = $db->query("SELECT id, kode_jurusan, nama_jurusan FROM jurusan")->fetchAll(PDO::FETCH_ASSOC);
        }

        $cleanKelas = strtoupper(trim($kelas));
        $extractedCode = '';
        if (preg_match('/^[0-9]+-([A-Z]+)/', $cleanKelas, $m)) {
            $extractedCode = $m[1];
        }

        $codeMap = [
            'DKV' => 'DKV',
            'DPIB' => 'DPIB',
            'GPT' => 'GPT',
            'LAS' => 'LAS',
            'TE' => 'ELIND',
            'TITL' => 'TITL',
            'TKJ' => 'TKJ',
            'TKP' => 'TKP',
            'TKR' => 'TKR',
            'TPM' => 'PM',
            'TPTUP' => 'TPTUP',
            'TSM' => 'TSM'
        ];

        $targetCode = $codeMap[$extractedCode] ?? $extractedCode;

        foreach ($jurusanCache as $j) {
            $jNama = strtoupper($j['nama_jurusan']);
            $jKode = strtoupper($j['kode_jurusan'] ?? '');

            if (!empty($targetCode)) {
                if (strpos($jNama, "($targetCode)") !== false || strpos($jNama, " $targetCode") !== false || $jKode === "JUR-$targetCode" || $jKode === $targetCode) {
                    return $j['id'];
                }
            }
            if (!empty($extractedCode)) {
                if (strpos($jNama, "($extractedCode)") !== false || strpos($jNama, " $extractedCode") !== false || $jKode === "JUR-$extractedCode") {
                    return $j['id'];
                }
            }
        }

        return null;
    }

    /**
     * Batch import data siswa berkinerja tinggi dengan transaksi database
     */
    public static function batchImport($records, $overrideJurusanId = null) {
        $db = Database::getInstance()->getConnection();
        if (!$db || empty($records)) {
            return ['success' => false, 'message' => 'Tidak ada data untuk diimpor.'];
        }

        $jurusanCache = $db->query("SELECT id, kode_jurusan, nama_jurusan FROM jurusan")->fetchAll(PDO::FETCH_ASSOC);

        $existingTokens = [];
        $resTokens = $db->query("SELECT token FROM siswa WHERE token IS NOT NULL")->fetchAll(PDO::FETCH_COLUMN);
        foreach ($resTokens as $t) {
            $existingTokens[$t] = true;
        }

        $existingNisnMap = [];
        $resNisn = $db->query("SELECT id, nisn FROM siswa WHERE nisn IS NOT NULL AND nisn != ''")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($resNisn as $r) {
            $existingNisnMap[$r['nisn']] = $r['id'];
        }

        $existingUsernames = [];
        $resUsernames = $db->query("SELECT nama_pengguna FROM pengguna")->fetchAll(PDO::FETCH_COLUMN);
        foreach ($resUsernames as $u) {
            $existingUsernames[strtolower($u)] = true;
        }

        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $generateTokenLocal = function() use (&$existingTokens, $chars) {
            while (true) {
                $token = substr(str_shuffle(str_repeat($chars, 5)), 0, 5);
                if (!isset($existingTokens[$token])) {
                    $existingTokens[$token] = true;
                    return $token;
                }
            }
        };

        $db->beginTransaction();
        try {
            $stmtInsertSiswa = $db->prepare("
                INSERT INTO siswa (id, nisn, nama_siswa, nama_lengkap, token, kelas, jurusan_id, tahun_ajaran)
                VALUES (:id, :nisn, :nama_siswa, :nama_lengkap, :token, :kelas, :jurusan_id, :tahun_ajaran)
            ");

            $stmtUpdateSiswa = $db->prepare("
                UPDATE siswa SET 
                    nama_siswa = :nama_siswa,
                    nama_lengkap = :nama_lengkap,
                    kelas = :kelas,
                    jurusan_id = :jurusan_id,
                    tahun_ajaran = :tahun_ajaran,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :id
            ");

            $stmtInsertUser = $db->prepare("
                INSERT INTO pengguna (id, jurusan_id, nama_pengguna, nama_lengkap, email, kata_sandi_hash, peran, nomor_telepon, foto_url, status_pengguna, token, guru_id, siswa_id)
                VALUES (:id, :jid, :u, :nl, NULL, :p, 'siswa', NULL, NULL, 'siswa', :token, NULL, :sid)
            ");

            $stmtUpdateUser = $db->prepare("
                UPDATE pengguna SET 
                    jurusan_id = :jid,
                    nama_lengkap = :nl,
                    updated_at = CURRENT_TIMESTAMP
                WHERE siswa_id = :sid
            ");

            $inserted = 0;
            $updated = 0;

            foreach ($records as $row) {
                $nisn = !empty($row['nisn']) ? trim((string)$row['nisn']) : null;
                $nama = trim((string)($row['nama'] ?? $row['nama_siswa'] ?? ''));
                if (empty($nama)) continue;

                $kelas = trim((string)($row['kelas'] ?? ''));
                $tahunAjaran = !empty($row['tahun_ajaran']) ? trim((string)$row['tahun_ajaran']) : '2026/2027';
                $jurusanId = !empty($overrideJurusanId) ? $overrideJurusanId : self::resolveJurusanIdFromKelas($kelas, $jurusanCache);

                if ($nisn && isset($existingNisnMap[$nisn])) {
                    $existingId = $existingNisnMap[$nisn];
                    $stmtUpdateSiswa->execute([
                        ':nama_siswa' => $nama,
                        ':nama_lengkap' => $nama,
                        ':kelas' => $kelas,
                        ':jurusan_id' => $jurusanId,
                        ':tahun_ajaran' => $tahunAjaran,
                        ':id' => $existingId
                    ]);

                    $stmtUpdateUser->execute([
                        ':jid' => $jurusanId,
                        ':nl' => $nama,
                        ':sid' => $existingId
                    ]);
                    $updated++;
                } else {
                    $siswaId = generateUuid();
                    $token = $generateTokenLocal();
                    $stmtInsertSiswa->execute([
                        ':id' => $siswaId,
                        ':nisn' => $nisn,
                        ':nama_siswa' => $nama,
                        ':nama_lengkap' => $nama,
                        ':token' => $token,
                        ':kelas' => $kelas,
                        ':jurusan_id' => $jurusanId,
                        ':tahun_ajaran' => $tahunAjaran
                    ]);

                    $baseUsername = strtolower(preg_replace('/[^a-z0-9]/i', '', $nama));
                    if (empty($baseUsername)) $baseUsername = 'siswa';
                    if (strlen($baseUsername) > 20) $baseUsername = substr($baseUsername, 0, 20);

                    $username = $baseUsername;
                    $counter = 1;
                    while (isset($existingUsernames[$username])) {
                        $username = $baseUsername . $counter;
                        $counter++;
                    }
                    $existingUsernames[$username] = true;

                    $newUserId = generateUuid();
                    $tokenPassHash = password_hash($token, PASSWORD_DEFAULT);

                    $stmtInsertUser->execute([
                        ':id' => $newUserId,
                        ':jid' => $jurusanId,
                        ':u' => $username,
                        ':nl' => $nama,
                        ':p' => $tokenPassHash,
                        ':token' => $token,
                        ':sid' => $siswaId
                    ]);

                    if ($nisn) {
                        $existingNisnMap[$nisn] = $siswaId;
                    }
                    $inserted++;
                }
            }

            $db->commit();
            return [
                'success' => true,
                'inserted' => $inserted,
                'updated' => $updated,
                'total' => $inserted + $updated,
                'message' => "Berhasil memproses " . ($inserted + $updated) . " data siswa ($inserted baru, $updated diperbarui)."
            ];
        } catch (Exception $e) {
            $db->rollBack();
            return ['success' => false, 'message' => 'Gagal memproses batch import: ' . $e->getMessage()];
        }
    }

    public static function migrateKelas($params = []) {
        $db = Database::getInstance()->getConnection();
        if (!$db) return ['success' => false, 'message' => 'Koneksi database gagal'];

        $jurusanId = !empty($params['jurusan_id']) ? $params['jurusan_id'] : null;
        $tahunAjaran = !empty($params['tahun_ajaran']) ? trim($params['tahun_ajaran']) : null;
        $userId = !empty($params['pengguna_id']) ? $params['pengguna_id'] : ($_SESSION['user_id'] ?? null);

        try {
            $db->beginTransaction();

            $sql = "SELECT id, kelas, jurusan_id, tahun_ajaran FROM siswa WHERE 1=1";
            $bindParams = [];
            if (!empty($jurusanId)) {
                $sql .= " AND jurusan_id = :jid";
                $bindParams[':jid'] = $jurusanId;
            }

            $stmt = $db->prepare($sql);
            $stmt->execute($bindParams);
            $allSiswa = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $updateStmt = $db->prepare("UPDATE siswa SET kelas = :kelas, tahun_ajaran = :ta, updated_at = NOW() WHERE id = :id");

            $batchId = generateUuid();
            $migrasiItems = [];
            $defaultTahunAsal = '2026/2027';

            $count12 = 0;
            $count11 = 0;
            $count10 = 0;

            // 1. TAHAP 1: KELAS 12 -> LULUS
            foreach ($allSiswa as $idx => $s) {
                $k = trim($s['kelas'] ?? '');
                if (preg_match('/^(12|XII)(\s|-|$)/i', $k) || strtoupper($k) === 'XII' || $k === '12') {
                    $oldK = $k;
                    $oldTa = !empty($s['tahun_ajaran']) ? $s['tahun_ajaran'] : $defaultTahunAsal;
                    $newTa = !empty($tahunAjaran) ? $tahunAjaran : $oldTa;
                    $newK = 'LULUS';

                    $updateStmt->execute([
                        ':kelas' => $newK,
                        ':ta' => $newTa,
                        ':id' => $s['id']
                    ]);
                    $migrasiItems[] = [
                        'id' => generateUuid(),
                        'batch_id' => $batchId,
                        'siswa_id' => $s['id'],
                        'kelas_asal' => $oldK,
                        'kelas_tujuan' => $newK,
                        'tahun_ajaran_asal' => $oldTa,
                        'tahun_ajaran_tujuan' => $newTa
                    ];
                    $count12++;
                    unset($allSiswa[$idx]);
                }
            }

            // 2. TAHAP 2: KELAS 11 -> KELAS 12
            foreach ($allSiswa as $idx => $s) {
                $k = trim($s['kelas'] ?? '');
                if (preg_match('/^(11|XI)(\s|-|$)/i', $k) || strtoupper($k) === 'XI' || $k === '11') {
                    if (str_starts_with($k, '11-')) $newK = '12-' . substr($k, 3);
                    elseif (str_starts_with($k, '11 ')) $newK = '12 ' . substr($k, 3);
                    elseif (str_starts_with($k, '11')) $newK = '12' . substr($k, 2);
                    elseif (stripos($k, 'XI-') === 0) $newK = 'XII-' . substr($k, 3);
                    elseif (stripos($k, 'XI ') === 0) $newK = 'XII ' . substr($k, 3);
                    elseif (strcasecmp($k, 'XI') === 0) $newK = 'XII';
                    else $newK = '12-' . $k;

                    $oldK = $k;
                    $oldTa = !empty($s['tahun_ajaran']) ? $s['tahun_ajaran'] : $defaultTahunAsal;
                    $newTa = !empty($tahunAjaran) ? $tahunAjaran : $oldTa;

                    $updateStmt->execute([
                        ':kelas' => $newK,
                        ':ta' => $newTa,
                        ':id' => $s['id']
                    ]);
                    $migrasiItems[] = [
                        'id' => generateUuid(),
                        'batch_id' => $batchId,
                        'siswa_id' => $s['id'],
                        'kelas_asal' => $oldK,
                        'kelas_tujuan' => $newK,
                        'tahun_ajaran_asal' => $oldTa,
                        'tahun_ajaran_tujuan' => $newTa
                    ];
                    $count11++;
                    unset($allSiswa[$idx]);
                }
            }

            // 3. TAHAP 3: KELAS 10 -> KELAS 11
            foreach ($allSiswa as $idx => $s) {
                $k = trim($s['kelas'] ?? '');
                if (preg_match('/^(10|X)(\s|-|$)/i', $k) || strtoupper($k) === 'X' || $k === '10') {
                    if (str_starts_with($k, '10-')) $newK = '11-' . substr($k, 3);
                    elseif (str_starts_with($k, '10 ')) $newK = '11 ' . substr($k, 3);
                    elseif (str_starts_with($k, '10')) $newK = '11' . substr($k, 2);
                    elseif (stripos($k, 'X-') === 0) $newK = 'XI-' . substr($k, 2);
                    elseif (stripos($k, 'X ') === 0) $newK = 'XI ' . substr($k, 2);
                    elseif (strcasecmp($k, 'X') === 0) $newK = 'XI';
                    else $newK = '11-' . $k;

                    $oldK = $k;
                    $oldTa = !empty($s['tahun_ajaran']) ? $s['tahun_ajaran'] : $defaultTahunAsal;
                    $newTa = !empty($tahunAjaran) ? $tahunAjaran : $oldTa;

                    $updateStmt->execute([
                        ':kelas' => $newK,
                        ':ta' => $newTa,
                        ':id' => $s['id']
                    ]);
                    $migrasiItems[] = [
                        'id' => generateUuid(),
                        'batch_id' => $batchId,
                        'siswa_id' => $s['id'],
                        'kelas_asal' => $oldK,
                        'kelas_tujuan' => $newK,
                        'tahun_ajaran_asal' => $oldTa,
                        'tahun_ajaran_tujuan' => $newTa
                    ];
                    $count10++;
                    unset($allSiswa[$idx]);
                }
            }

            $totalMigrated = count($migrasiItems);

            if ($totalMigrated > 0) {
                // Catat batch migrasi
                $insertBatchStmt = $db->prepare("
                    INSERT INTO migrasi_kelas_batch (
                        id, pengguna_id, jurusan_id, tahun_ajaran_asal, tahun_ajaran_tujuan, 
                        count_10, count_11, count_12, total_migrated, status, created_at
                    ) VALUES (
                        :id, :uid, :jid, :ta_asal, :ta_tujuan, 
                        :c10, :c11, :c12, :total, 'migrated', NOW()
                    )
                ");
                $insertBatchStmt->execute([
                    ':id' => $batchId,
                    ':uid' => $userId,
                    ':jid' => $jurusanId,
                    ':ta_asal' => $migrasiItems[0]['tahun_ajaran_asal'] ?? $defaultTahunAsal,
                    ':ta_tujuan' => $tahunAjaran ?? $defaultTahunAsal,
                    ':c10' => $count10,
                    ':c11' => $count11,
                    ':c12' => $count12,
                    ':total' => $totalMigrated
                ]);

                // Catat item snapshot
                $insertItemStmt = $db->prepare("
                    INSERT INTO migrasi_kelas_item (
                        id, batch_id, siswa_id, kelas_asal, kelas_tujuan, tahun_ajaran_asal, tahun_ajaran_tujuan
                    ) VALUES (
                        :id, :bid, :sid, :ka, :kt, :taa, :tat
                    )
                ");
                foreach ($migrasiItems as $item) {
                    $insertItemStmt->execute([
                        ':id' => $item['id'],
                        ':bid' => $item['batch_id'],
                        ':sid' => $item['siswa_id'],
                        ':ka' => $item['kelas_asal'],
                        ':kt' => $item['kelas_tujuan'],
                        ':taa' => $item['tahun_ajaran_asal'],
                        ':tat' => $item['tahun_ajaran_tujuan']
                    ]);
                }
            }

            $db->commit();

            return [
                'success' => true,
                'batch_id' => $batchId,
                'count_10' => $count10,
                'count_11' => $count11,
                'count_12' => $count12,
                'total_migrated' => $totalMigrated,
                'tahun_ajaran' => $tahunAjaran
            ];
        } catch (Exception $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public static function getLatestMigrasiBatch($jurusanId = null) {
        $db = Database::getInstance()->getConnection();
        if (!$db) return null;

        $sql = "
            SELECT b.*, p.nama_lengkap as nama_pengguna, j.nama_jurusan
            FROM migrasi_kelas_batch b
            LEFT JOIN pengguna p ON b.pengguna_id = p.id
            LEFT JOIN jurusan j ON b.jurusan_id = j.id
            WHERE b.status = 'migrated'
        ";
        $params = [];
        if (!empty($jurusanId)) {
            $sql .= " AND (b.jurusan_id = :jid OR b.jurusan_id IS NULL)";
            $params[':jid'] = $jurusanId;
        }
        $sql .= " ORDER BY b.created_at DESC LIMIT 1";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function rollbackMigrasi($batchId = null, $jurusanId = null) {
        $db = Database::getInstance()->getConnection();
        if (!$db) return ['success' => false, 'message' => 'Koneksi database gagal'];

        try {
            $db->beginTransaction();

            $batch = null;
            if (!empty($batchId)) {
                $stmtB = $db->prepare("SELECT * FROM migrasi_kelas_batch WHERE id = :id AND status = 'migrated' LIMIT 1");
                $stmtB->execute([':id' => $batchId]);
                $batch = $stmtB->fetch(PDO::FETCH_ASSOC);
            } else {
                $batch = self::getLatestMigrasiBatch($jurusanId);
            }

            if (!$batch) {
                $db->rollBack();
                return ['success' => false, 'message' => 'Tidak ditemukan riwayat migrasi aktif yang dapat di-rollback.'];
            }

            $bid = $batch['id'];
            $stmtItems = $db->prepare("SELECT siswa_id, kelas_asal, tahun_ajaran_asal FROM migrasi_kelas_item WHERE batch_id = :bid");
            $stmtItems->execute([':bid' => $bid]);
            $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

            if (empty($items)) {
                $db->rollBack();
                return ['success' => false, 'message' => 'Detail data siswa untuk batch migrasi ini tidak ditemukan.'];
            }

            $updateSiswa = $db->prepare("UPDATE siswa SET kelas = :kelas, tahun_ajaran = :ta, updated_at = NOW() WHERE id = :id");
            $restoredCount = 0;
            foreach ($items as $it) {
                $updateSiswa->execute([
                    ':kelas' => $it['kelas_asal'],
                    ':ta' => $it['tahun_ajaran_asal'],
                    ':id' => $it['siswa_id']
                ]);
                $restoredCount++;
            }

            $stmtUpBatch = $db->prepare("UPDATE migrasi_kelas_batch SET status = 'rolled_back', rolled_back_at = NOW() WHERE id = :bid");
            $stmtUpBatch->execute([':bid' => $bid]);

            $db->commit();

            return [
                'success' => true,
                'batch_id' => $bid,
                'total_restored' => $restoredCount,
                'tahun_ajaran_restored' => $batch['tahun_ajaran_asal'],
                'count_10' => $batch['count_10'],
                'count_11' => $batch['count_11'],
                'count_12' => $batch['count_12'],
                'created_at' => $batch['created_at']
            ];
        } catch (Exception $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            return ['success' => false, 'message' => 'Gagal melakukan rollback migrasi: ' . $e->getMessage()];
        }
    }
}
