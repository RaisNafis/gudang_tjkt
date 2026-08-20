<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/auth.php';

class LogAktivitas {
    public static function getAll($jurusan_id = null) {
        $db = Database::getInstance()->getConnection();
        if (!empty($jurusan_id)) {
            $stmt = $db->prepare("
                SELECT l.*, p.nama_lengkap, p.nama_pengguna, j.nama_jurusan 
                FROM log_aktivitas l
                LEFT JOIN pengguna p ON l.pengguna_id = p.id
                LEFT JOIN jurusan j ON COALESCE(l.jurusan_id, p.jurusan_id) = j.id
                WHERE l.jurusan_id = :jid1 OR p.jurusan_id = :jid2
                ORDER BY l.created_at DESC
            ");
            $stmt->execute([':jid1' => $jurusan_id, ':jid2' => $jurusan_id]);
        } else {
            $stmt = $db->query("
                SELECT l.*, p.nama_lengkap, p.nama_pengguna, j.nama_jurusan 
                FROM log_aktivitas l
                LEFT JOIN pengguna p ON l.pengguna_id = p.id
                LEFT JOIN jurusan j ON COALESCE(l.jurusan_id, p.jurusan_id) = j.id
                ORDER BY l.created_at DESC
            ");
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function clearAll($jurusan_id = null) {
        $db = Database::getInstance()->getConnection();
        if (!empty($jurusan_id)) {
            $stmt = $db->prepare("DELETE FROM log_aktivitas WHERE jurusan_id = :jid OR pengguna_id IN (SELECT id FROM pengguna WHERE jurusan_id = :jid2)");
            return $stmt->execute([':jid' => $jurusan_id, ':jid2' => $jurusan_id]);
        } else {
            return $db->exec("DELETE FROM log_aktivitas");
        }
    }

    public static function record($pengguna_id, $tindakan, $deskripsi = null) {
        $db = Database::getInstance()->getConnection();
        $id = generateUuid();
        $jurusan_id = $_SESSION['user']['jurusan_id'] ?? null;
        if (!$jurusan_id && $pengguna_id) {
            $chk = $db->prepare("SELECT jurusan_id FROM pengguna WHERE id = :pid LIMIT 1");
            $chk->execute([':pid' => $pengguna_id]);
            $jurusan_id = $chk->fetchColumn() ?: null;
        }

        $stmt = $db->prepare("
            INSERT INTO log_aktivitas (id, jurusan_id, pengguna_id, tindakan, deskripsi)
            VALUES (:id, :jid, :pid, :tindakan, :desk)
        ");
        return $stmt->execute([
            ':id' => $id,
            ':jid' => $jurusan_id,
            ':pid' => $pengguna_id,
            ':tindakan' => $tindakan,
            ':desk' => $deskripsi
        ]);
    }

    public static function log($tindakan, $deskripsi = null) {
        $userId = $_SESSION['user_id'] ?? ($_SESSION['user']['id'] ?? null);
        if ($userId) {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT id FROM pengguna WHERE id = :id LIMIT 1");
            $stmt->execute(['id' => $userId]);
            if (!$stmt->fetch()) {
                $userId = null;
            }
        }
        return self::record($userId, $tindakan, $deskripsi);
    }
}
