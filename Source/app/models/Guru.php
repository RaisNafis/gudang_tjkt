<?php
// app/models/Guru.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/Pengguna.php';

class Guru {
    public static function getAll($jurusan_id = null, $include_umum = false) {
        $db = Database::getInstance()->getConnection();
        if (!$db) return [];
        
        if (!empty($jurusan_id)) {
            $whereClause = $include_umum ? "WHERE g.jurusan_id = :jid OR g.mengajar = 'umum'" : "WHERE g.jurusan_id = :jid";
            $stmt = $db->prepare("
                SELECT g.*, j.nama_jurusan 
                FROM guru g 
                LEFT JOIN jurusan j ON g.jurusan_id = j.id 
                $whereClause
                ORDER BY g.nama_guru ASC
            ");
            $stmt->execute([':jid' => $jurusan_id]);
        } else {
            $stmt = $db->query("
                SELECT g.*, j.nama_jurusan 
                FROM guru g 
                LEFT JOIN jurusan j ON g.jurusan_id = j.id 
                ORDER BY g.nama_guru ASC
            ");
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public static function count($jurusan_id = null, $include_umum = false) {
        $db = Database::getInstance()->getConnection();
        if (!$db) return 0;
        if (!empty($jurusan_id)) {
            $whereClause = $include_umum ? "WHERE jurusan_id = :jid OR mengajar = 'umum'" : "WHERE jurusan_id = :jid";
            $stmt = $db->prepare("SELECT COUNT(*) FROM guru $whereClause");
            $stmt->execute([':jid' => $jurusan_id]);
            return (int)$stmt->fetchColumn();
        }
        return (int)$db->query("SELECT COUNT(*) FROM guru")->fetchColumn();
    }

    public static function getById($id) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("
            SELECT g.*, j.nama_jurusan 
            FROM guru g 
            LEFT JOIN jurusan j ON g.jurusan_id = j.id 
            WHERE g.id = :id LIMIT 1
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function generateUniqueToken() {
        $db = Database::getInstance()->getConnection();
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        do {
            $token = '';
            for ($i = 0; $i < 5; $i++) {
                $token .= $chars[random_int(0, strlen($chars) - 1)];
            }
            $stmt = $db->prepare("SELECT COUNT(*) FROM guru WHERE token = :t");
            $stmt->execute([':t' => $token]);
        } while ($stmt->fetchColumn() > 0);
        return $token;
    }

    public static function findByToken($token) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM guru WHERE token = :t LIMIT 1");
        $stmt->execute([':t' => trim($token)]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function save($data) {
        $db = Database::getInstance()->getConnection();
        $id = !empty($data['id']) ? $data['id'] : generateUuid();
        $token = !empty($data['token']) ? trim($data['token']) : self::generateUniqueToken();

        $rawUsername = !empty($data['nama_pengguna']) ? $data['nama_pengguna'] : ($data['nama_guru'] ?? '');
        $nama_pengguna = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $rawUsername));
        if (empty($nama_pengguna)) $nama_pengguna = 'guru';

        $mengajar = $data['mengajar'] ?? 'bengkel';
        $jurusan_id = ($mengajar === 'bengkel' && !empty($data['jurusan_id'])) ? $data['jurusan_id'] : null;

        if (!empty($data['id'])) {
            $stmt = $db->prepare("
                UPDATE guru SET 
                    nama_guru = :nama_guru,
                    nama_pengguna = :nama_pengguna,
                    token = :token,
                    mengajar = :mengajar,
                    jurusan_id = :jurusan_id,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :id
            ");
            $ok = $stmt->execute([
                ':nama_guru' => trim($data['nama_guru']),
                ':nama_pengguna' => $nama_pengguna,
                ':token' => $token,
                ':mengajar' => $mengajar,
                ':jurusan_id' => $jurusan_id,
                ':id' => $id
            ]);
        } else {
            $stmt = $db->prepare("
                INSERT INTO guru (id, nama_guru, nama_pengguna, token, mengajar, jurusan_id)
                VALUES (:id, :nama_guru, :nama_pengguna, :token, :mengajar, :jurusan_id)
            ");
            $ok = $stmt->execute([
                ':id' => $id,
                ':nama_guru' => trim($data['nama_guru']),
                ':nama_pengguna' => $nama_pengguna,
                ':token' => $token,
                ':mengajar' => $mengajar,
                ':jurusan_id' => $jurusan_id
            ]);
        }

        if ($ok) {
            Pengguna::syncFromGuru($id);
            return $id;
        }
        return false;
    }

    public static function delete($id) {
        $db = Database::getInstance()->getConnection();
        Pengguna::deleteByGuruId($id);
        $stmt = $db->prepare("DELETE FROM guru WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
