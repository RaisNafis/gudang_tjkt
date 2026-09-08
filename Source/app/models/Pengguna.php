<?php
// app/models/Pengguna.php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/auth.php';

class Pengguna {
    private $db;

    public static function getAll($jurusan_id = null) {
        $db = Database::getInstance()->getConnection();
        if (!$db) return [];
        if (!empty($jurusan_id)) {
            $stmt = $db->prepare("
                SELECT p.id, p.jurusan_id, p.nama_pengguna, p.nama_lengkap, p.email, p.peran, p.nomor_telepon, p.foto_url, 
                       p.status_pengguna, p.token, p.guru_id, p.siswa_id, p.created_at, j.nama_jurusan,
                       s.kelas, s.nisn
                FROM pengguna p
                LEFT JOIN jurusan j ON p.jurusan_id = j.id
                LEFT JOIN siswa s ON p.siswa_id = s.id
                WHERE p.jurusan_id = :jid
                ORDER BY p.created_at ASC
            ");
            $stmt->execute([':jid' => $jurusan_id]);
        } else {
            $stmt = $db->query("
                SELECT p.id, p.jurusan_id, p.nama_pengguna, p.nama_lengkap, p.email, p.peran, p.nomor_telepon, p.foto_url, 
                       p.status_pengguna, p.token, p.guru_id, p.siswa_id, p.created_at, j.nama_jurusan,
                       s.kelas, s.nisn
                FROM pengguna p
                LEFT JOIN jurusan j ON p.jurusan_id = j.id
                LEFT JOIN siswa s ON p.siswa_id = s.id
                ORDER BY p.created_at ASC
            ");
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Cari pengguna berdasarkan ID
     */
    public function findById($id) {
        if (!$this->db) return null;

        $stmt = $this->db->prepare("
            SELECT p.*, j.nama_jurusan 
            FROM pengguna p 
            LEFT JOIN jurusan j ON p.jurusan_id = j.id 
            WHERE p.id = :id 
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Cari pengguna berdasarkan nama_pengguna, email, atau token
     */
    public function findByUsernameOrEmail($identifier) {
        if (!$this->db) return null;

        $stmt = $this->db->prepare("
            SELECT * FROM pengguna 
            WHERE nama_pengguna = :u 
               OR (email IS NOT NULL AND email = :e AND email != '') 
               OR (token IS NOT NULL AND token = :t AND token != '')
            LIMIT 1
        ");
        $stmt->execute(['u' => $identifier, 'e' => $identifier, 't' => $identifier]);
        return $stmt->fetch();
    }

    /**
     * Cek keberadaan nama pengguna atau email
     */
    public function exists($nama_pengguna, $email = null) {
        if (!$this->db) return false;

        $stmt = $this->db->prepare("
            SELECT id FROM pengguna 
            WHERE nama_pengguna = :u OR (email IS NOT NULL AND email = :e AND email != '') 
            LIMIT 1
        ");
        $stmt->execute(['u' => $nama_pengguna, 'e' => $email]);
        return (bool) $stmt->fetch();
    }

    /**
     * Registrasi pengguna baru
     */
    public function register($data) {
        if (!$this->db) return false;

        $id = generateUuid();
        $stmt = $this->db->prepare("
            INSERT INTO pengguna (id, jurusan_id, nama_pengguna, nama_lengkap, email, kata_sandi_hash, peran, nomor_telepon, foto_url, status_pengguna, token, guru_id, siswa_id)
            VALUES (:id, :jid, :u, :nl, :e, :p, :peran, :tel, :foto, :sp, :token, :gid, :sid)
        ");

        return $stmt->execute([
            'id' => $id,
            'jid' => !empty($data['jurusan_id']) ? $data['jurusan_id'] : null,
            'u' => $data['nama_pengguna'],
            'nl' => $data['nama_lengkap'],
            'e' => !empty($data['email']) ? $data['email'] : null,
            'p' => password_hash($data['password'] ?? 'admin123', PASSWORD_BCRYPT),
            'peran' => $data['peran'] ?? 'siswa',
            'tel' => !empty($data['nomor_telepon']) ? $data['nomor_telepon'] : null,
            'foto' => !empty($data['foto_url']) ? $data['foto_url'] : null,
            'sp' => !empty($data['status_pengguna']) ? $data['status_pengguna'] : 'tidak_ada',
            'token' => !empty($data['token']) ? $data['token'] : null,
            'gid' => !empty($data['guru_id']) ? $data['guru_id'] : null,
            'sid' => !empty($data['siswa_id']) ? $data['siswa_id'] : null
        ]);
    }

    /**
     * Update profil pengguna
     */
    public function updateProfile($id, $data) {
        if (!$this->db) return false;

        $fields = ["nama_lengkap = :nl", "email = :e", "nomor_telepon = :tel"];
        $params = [
            'id' => $id,
            'nl' => $data['nama_lengkap'],
            'e' => !empty($data['email']) ? $data['email'] : null,
            'tel' => !empty($data['nomor_telepon']) ? $data['nomor_telepon'] : null
        ];

        if (!empty($data['foto_url'])) {
            $fields[] = "foto_url = :foto";
            $params['foto'] = $data['foto_url'];
        }

        if (!empty($data['password_baru'])) {
            $fields[] = "kata_sandi_hash = :p";
            $params['p'] = password_hash($data['password_baru'], PASSWORD_BCRYPT);
        }

        $sql = "UPDATE pengguna SET " . implode(", ", $fields) . ", updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Update pengguna oleh Admin
     */
    public function updateAdmin($id, $data) {
        if (!$this->db) return false;

        $fields = [
            "jurusan_id = :jid", 
            "nama_pengguna = :u", 
            "nama_lengkap = :nl", 
            "email = :e", 
            "peran = :peran", 
            "nomor_telepon = :tel",
            "status_pengguna = :sp",
            "token = :token",
            "guru_id = :gid",
            "siswa_id = :sid"
        ];
        $params = [
            'id' => $id,
            'jid' => !empty($data['jurusan_id']) ? $data['jurusan_id'] : null,
            'u' => $data['nama_pengguna'],
            'nl' => $data['nama_lengkap'],
            'e' => !empty($data['email']) ? $data['email'] : null,
            'peran' => $data['peran'] ?? 'siswa',
            'tel' => !empty($data['nomor_telepon']) ? $data['nomor_telepon'] : null,
            'sp' => !empty($data['status_pengguna']) ? $data['status_pengguna'] : 'tidak_ada',
            'token' => !empty($data['token']) ? $data['token'] : null,
            'gid' => !empty($data['guru_id']) ? $data['guru_id'] : null,
            'sid' => !empty($data['siswa_id']) ? $data['siswa_id'] : null
        ];

        if (!empty($data['foto_url'])) {
            $fields[] = "foto_url = :foto";
            $params['foto'] = $data['foto_url'];
        }

        if (!empty($data['password'])) {
            $fields[] = "kata_sandi_hash = :p";
            $params['p'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        $sql = "UPDATE pengguna SET " . implode(", ", $fields) . ", updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Hapus pengguna
     */
    public function delete($id) {
        if (!$this->db) return false;
        $stmt = $this->db->prepare("DELETE FROM pengguna WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Sinkronisasi data Pengguna otomatis dari Data Guru
     */
    public static function syncFromGuru($guruId) {
        $db = Database::getInstance()->getConnection();
        if (!$db || empty($guruId)) return false;

        $stmtG = $db->prepare("SELECT * FROM guru WHERE id = :id LIMIT 1");
        $stmtG->execute([':id' => $guruId]);
        $guru = $stmtG->fetch(PDO::FETCH_ASSOC);
        if (!$guru) return false;

        $namaGuru = trim($guru['nama_guru']);
        $mengajar = $guru['mengajar'] ?? 'bengkel';
        $token = trim($guru['token'] ?? '');

        // Aturan Peran & Jurusan:
        // Guru Bengkel -> Peran: 'admin_jurusan', Jurusan: jurusan_id guru
        // Guru Umum -> Peran: 'guru_umum', Jurusan: null (tidak ada)
        if ($mengajar === 'bengkel') {
            $peran = 'admin_jurusan';
            $jurusanId = !empty($guru['jurusan_id']) ? $guru['jurusan_id'] : null;
        } else {
            $peran = 'guru_umum';
            $jurusanId = null;
        }

        $namaPenggunaGuru = !empty($guru['nama_pengguna']) ? strtolower(preg_replace('/[^a-z0-9]/i', '', $guru['nama_pengguna'])) : strtolower(preg_replace('/[^a-z0-9]/i', '', $namaGuru));
        if (empty($namaPenggunaGuru)) $namaPenggunaGuru = 'guru';

        $stmtP = $db->prepare("SELECT id, nama_pengguna, peran, jurusan_id FROM pengguna WHERE guru_id = :gid LIMIT 1");
        $stmtP->execute([':gid' => $guruId]);
        $existing = $stmtP->fetch(PDO::FETCH_ASSOC);

        $tokenPassHash = password_hash(!empty($token) ? $token : 'guru123', PASSWORD_BCRYPT);

        if ($existing) {
            // Jika akun pengguna ini sudah diatur peran khusus (misal admin_sekolah atau petugas), pertahankan peran tersebut
            if (!empty($existing['peran']) && in_array($existing['peran'], ['admin_sekolah', 'petugas'])) {
                $peran = $existing['peran'];
                if ($peran === 'admin_sekolah') {
                    $jurusanId = null;
                }
            }

            $stmtUp = $db->prepare("
                UPDATE pengguna SET 
                    nama_pengguna = :u,
                    nama_lengkap = :nl,
                    jurusan_id = :jid,
                    peran = :peran,
                    status_pengguna = 'guru',
                    token = :token,
                    kata_sandi_hash = :p,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :id
            ");
            return $stmtUp->execute([
                ':u' => $namaPenggunaGuru,
                ':nl' => $namaGuru,
                ':jid' => $jurusanId,
                ':peran' => $peran,
                ':token' => $token,
                ':p' => $tokenPassHash,
                ':id' => $existing['id']
            ]);
        } else {
            $baseUsername = $namaPenggunaGuru;
            if (strlen($baseUsername) > 50) $baseUsername = substr($baseUsername, 0, 50);

            $username = $baseUsername;
            $counter = 1;
            while (true) {
                $stmtC = $db->prepare("SELECT COUNT(*) FROM pengguna WHERE nama_pengguna = :u");
                $stmtC->execute([':u' => $username]);
                if ($stmtC->fetchColumn() == 0) break;
                $username = $baseUsername . $counter;
                $counter++;
            }

            $newUserId = generateUuid();

            $stmtIns = $db->prepare("
                INSERT INTO pengguna (id, jurusan_id, nama_pengguna, nama_lengkap, email, kata_sandi_hash, peran, nomor_telepon, foto_url, status_pengguna, token, guru_id, siswa_id)
                VALUES (:id, :jid, :u, :nl, NULL, :p, :peran, NULL, NULL, 'guru', :token, :gid, NULL)
            ");
            return $stmtIns->execute([
                ':id' => $newUserId,
                ':jid' => $jurusanId,
                ':u' => $username,
                ':nl' => $namaGuru,
                ':p' => $tokenPassHash,
                ':peran' => $peran,
                ':token' => $token,
                ':gid' => $guruId
            ]);
        }
    }

    /**
     * Sinkronisasi data Pengguna otomatis dari Data Siswa
     */
    public static function syncFromSiswa($siswaId) {
        $db = Database::getInstance()->getConnection();
        if (!$db || empty($siswaId)) return false;

        $stmtS = $db->prepare("SELECT * FROM siswa WHERE id = :id LIMIT 1");
        $stmtS->execute([':id' => $siswaId]);
        $siswa = $stmtS->fetch(PDO::FETCH_ASSOC);
        if (!$siswa) return false;

        $namaSiswa = trim($siswa['nama_siswa']);
        $namaLengkap = !empty($siswa['nama_lengkap']) ? trim($siswa['nama_lengkap']) : $namaSiswa;
        $token = trim($siswa['token'] ?? '');
        $jurusanId = !empty($siswa['jurusan_id']) ? $siswa['jurusan_id'] : null;
        $peran = 'siswa';

        $stmtP = $db->prepare("SELECT id, nama_pengguna FROM pengguna WHERE siswa_id = :sid LIMIT 1");
        $stmtP->execute([':sid' => $siswaId]);
        $existing = $stmtP->fetch(PDO::FETCH_ASSOC);

        $tokenPassHash = password_hash(!empty($token) ? $token : 'siswa123', PASSWORD_BCRYPT);

        if ($existing) {
            $stmtUp = $db->prepare("
                UPDATE pengguna SET 
                    nama_lengkap = :nl,
                    jurusan_id = :jid,
                    peran = :peran,
                    status_pengguna = 'siswa',
                    token = :token,
                    kata_sandi_hash = :p,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :id
            ");
            return $stmtUp->execute([
                ':nl' => $namaLengkap,
                ':jid' => $jurusanId,
                ':peran' => $peran,
                ':token' => $token,
                ':p' => $tokenPassHash,
                ':id' => $existing['id']
            ]);
        } else {
            $baseUsername = strtolower(preg_replace('/[^a-z0-9]/i', '', $namaSiswa));
            if (empty($baseUsername)) $baseUsername = 'siswa';
            if (strlen($baseUsername) > 20) $baseUsername = substr($baseUsername, 0, 20);

            $username = $baseUsername;
            $counter = 1;
            while (true) {
                $stmtC = $db->prepare("SELECT COUNT(*) FROM pengguna WHERE nama_pengguna = :u");
                $stmtC->execute([':u' => $username]);
                if ($stmtC->fetchColumn() == 0) break;
                $username = $baseUsername . $counter;
                $counter++;
            }

            $newUserId = generateUuid();

            $stmtIns = $db->prepare("
                INSERT INTO pengguna (id, jurusan_id, nama_pengguna, nama_lengkap, email, kata_sandi_hash, peran, nomor_telepon, foto_url, status_pengguna, token, guru_id, siswa_id)
                VALUES (:id, :jid, :u, :nl, NULL, :p, :peran, NULL, NULL, 'siswa', :token, NULL, :sid)
            ");
            return $stmtIns->execute([
                ':id' => $newUserId,
                ':jid' => $jurusanId,
                ':u' => $username,
                ':nl' => $namaLengkap,
                ':p' => $tokenPassHash,
                ':peran' => $peran,
                ':token' => $token,
                ':sid' => $siswaId
            ]);
        }
    }

    /**
     * Hapus pengguna berdasarkan guru_id
     */
    public static function deleteByGuruId($guruId) {
        $db = Database::getInstance()->getConnection();
        if (!$db || empty($guruId)) return false;
        $stmt = $db->prepare("DELETE FROM pengguna WHERE guru_id = :gid");
        return $stmt->execute([':gid' => $guruId]);
    }

    /**
     * Hapus pengguna berdasarkan siswa_id
     */
    public static function deleteBySiswaId($siswaId) {
        $db = Database::getInstance()->getConnection();
        if (!$db || empty($siswaId)) return false;
        $stmt = $db->prepare("DELETE FROM pengguna WHERE siswa_id = :sid");
        return $stmt->execute([':sid' => $siswaId]);
    }

    /**
     * Sinkronisasi seluruh guru dan siswa yang belum memiliki akun pengguna
     */
    public static function syncAllGuruAndSiswa() {
        $db = Database::getInstance()->getConnection();
        if (!$db) return;

        $gurus = $db->query("SELECT id FROM guru")->fetchAll(PDO::FETCH_COLUMN);
        foreach ($gurus as $gid) {
            self::syncFromGuru($gid);
        }

        $siswas = $db->query("SELECT id FROM siswa")->fetchAll(PDO::FETCH_COLUMN);
        foreach ($siswas as $sid) {
            self::syncFromSiswa($sid);
        }
    }
}
