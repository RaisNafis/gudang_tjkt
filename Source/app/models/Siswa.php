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
}
