<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/auth.php';

class Jurusan {
    public static function getAll() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM jurusan ORDER BY created_at ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM jurusan WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        $db = Database::getInstance()->getConnection();
        $id = generateUuid();
        $stmt = $db->prepare("
            INSERT INTO jurusan (id, nama_jurusan, deskripsi, warna_tema) 
            VALUES (:id, :nama, :desk, :warna)
        ");
        return $stmt->execute([
            ':id' => $id,
            ':nama' => $data['nama_jurusan'],
            ':desk' => $data['deskripsi'] ?? null,
            ':warna' => !empty($data['warna_tema']) ? $data['warna_tema'] : 'kuning'
        ]);
    }

    public static function update($id, $data) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("
            UPDATE jurusan 
            SET nama_jurusan = :nama, 
                deskripsi = :desk, 
                warna_tema = :warna,
                updated_at = CURRENT_TIMESTAMP 
            WHERE id = :id
        ");
        return $stmt->execute([
            ':id' => $id,
            ':nama' => $data['nama_jurusan'],
            ':desk' => $data['deskripsi'] ?? null,
            ':warna' => !empty($data['warna_tema']) ? $data['warna_tema'] : 'kuning'
        ]);
    }

    public static function delete($id) {
        $db = Database::getInstance()->getConnection();
        
        // Cek apakah jurusan digunakan oleh pengguna atau barang
        $chk1 = $db->prepare("SELECT COUNT(*) FROM barang WHERE jurusan_id = :id");
        $chk1->execute([':id' => $id]);
        if ($chk1->fetchColumn() > 0) {
            return ['success' => false, 'message' => 'Jurusan tidak dapat dihapus karena masih digunakan oleh data barang!'];
        }

        $chk2 = $db->prepare("SELECT COUNT(*) FROM pengguna WHERE jurusan_id = :id");
        $chk2->execute([':id' => $id]);
        if ($chk2->fetchColumn() > 0) {
            return ['success' => false, 'message' => 'Jurusan tidak dapat dihapus karena masih digunakan oleh data pengguna!'];
        }

        $stmt = $db->prepare("DELETE FROM jurusan WHERE id = :id");
        $ok = $stmt->execute([':id' => $id]);
        return ['success' => (bool)$ok, 'message' => $ok ? 'Jurusan berhasil dihapus!' : 'Gagal menghapus jurusan.'];
    }
}
