<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/auth.php';

class Kategori {
    public static function getAll($jurusan_id = null) {
        $db = Database::getInstance()->getConnection();
        if (!empty($jurusan_id)) {
            $stmt = $db->prepare("
                SELECT k.*, j.nama_jurusan 
                FROM kategori k 
                LEFT JOIN jurusan j ON k.jurusan_id = j.id 
                WHERE k.jurusan_id = :jid 
                ORDER BY k.created_at ASC
            ");
            $stmt->execute([':jid' => $jurusan_id]);
        } else {
            $stmt = $db->query("
                SELECT k.*, j.nama_jurusan 
                FROM kategori k 
                LEFT JOIN jurusan j ON k.jurusan_id = j.id 
                ORDER BY k.created_at ASC
            ");
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function count($jurusan_id = null) {
        $db = Database::getInstance()->getConnection();
        if (!empty($jurusan_id)) {
            $stmt = $db->prepare("SELECT COUNT(*) as total FROM kategori WHERE jurusan_id = :jid");
            $stmt->execute([':jid' => $jurusan_id]);
        } else {
            $stmt = $db->query("SELECT COUNT(*) as total FROM kategori");
        }
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res['total'] ?? 0;
    }

    public static function create($nama, $deskripsi, $jurusan_id = null) {
        $db = Database::getInstance()->getConnection();
        $id = generateUuid();
        $stmt = $db->prepare("INSERT INTO kategori (id, jurusan_id, nama_kategori, deskripsi) VALUES (:id, :jid, :nama, :desk)");
        return $stmt->execute([
            ':id' => $id,
            ':jid' => !empty($jurusan_id) ? $jurusan_id : null,
            ':nama' => $nama,
            ':desk' => $deskripsi
        ]);
    }

    public static function update($id, $nama, $deskripsi, $jurusan_id = null) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE kategori SET jurusan_id = :jid, nama_kategori = :nama, deskripsi = :desk, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
        return $stmt->execute([
            ':id' => $id,
            ':jid' => !empty($jurusan_id) ? $jurusan_id : null,
            ':nama' => $nama,
            ':desk' => $deskripsi
        ]);
    }

    public static function delete($id) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM kategori WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
