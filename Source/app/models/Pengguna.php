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
                SELECT p.id, p.jurusan_id, p.nama_pengguna, p.nama_lengkap, p.email, p.peran, p.nomor_telepon, p.foto_url, p.created_at, j.nama_jurusan
                FROM pengguna p
                LEFT JOIN jurusan j ON p.jurusan_id = j.id
                WHERE p.jurusan_id = :jid
                ORDER BY p.created_at ASC
            ");
            $stmt->execute([':jid' => $jurusan_id]);
        } else {
            $stmt = $db->query("
                SELECT p.id, p.jurusan_id, p.nama_pengguna, p.nama_lengkap, p.email, p.peran, p.nomor_telepon, p.foto_url, p.created_at, j.nama_jurusan
                FROM pengguna p
                LEFT JOIN jurusan j ON p.jurusan_id = j.id
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
     * Cari pengguna berdasarkan nama_pengguna (atau email jika ada)
     */
    public function findByUsernameOrEmail($identifier) {
        if (!$this->db) return null;

        $stmt = $this->db->prepare("
            SELECT * FROM pengguna 
            WHERE nama_pengguna = :u OR (email IS NOT NULL AND email = :e AND email != '') 
            LIMIT 1
        ");
        $stmt->execute(['u' => $identifier, 'e' => $identifier]);
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
            INSERT INTO pengguna (id, jurusan_id, nama_pengguna, nama_lengkap, email, kata_sandi_hash, peran, nomor_telepon, foto_url)
            VALUES (:id, :jid, :u, :nl, :e, :p, :peran, :tel, :foto)
        ");

        return $stmt->execute([
            'id' => $id,
            'jid' => !empty($data['jurusan_id']) ? $data['jurusan_id'] : null,
            'u' => $data['nama_pengguna'],
            'nl' => $data['nama_lengkap'],
            'e' => !empty($data['email']) ? $data['email'] : null,
            'p' => password_hash($data['password'], PASSWORD_BCRYPT),
            'peran' => $data['peran'] ?? 'siswa',
            'tel' => !empty($data['nomor_telepon']) ? $data['nomor_telepon'] : null,
            'foto' => !empty($data['foto_url']) ? $data['foto_url'] : null
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

        $fields = ["jurusan_id = :jid", "nama_pengguna = :u", "nama_lengkap = :nl", "email = :e", "peran = :peran", "nomor_telepon = :tel"];
        $params = [
            'id' => $id,
            'jid' => !empty($data['jurusan_id']) ? $data['jurusan_id'] : null,
            'u' => $data['nama_pengguna'],
            'nl' => $data['nama_lengkap'],
            'e' => !empty($data['email']) ? $data['email'] : null,
            'peran' => $data['peran'] ?? 'siswa',
            'tel' => !empty($data['nomor_telepon']) ? $data['nomor_telepon'] : null
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
}
