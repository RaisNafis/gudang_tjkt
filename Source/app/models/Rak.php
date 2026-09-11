<?php
// app/models/Rak.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/auth.php';

class Rak {
    public static function getAll($jurusan_id = null) {
        $db = Database::getInstance()->getConnection();
        if (!empty($jurusan_id)) {
            $stmt = $db->prepare("
                SELECT r.*, j.nama_jurusan, 
                       (SELECT COUNT(*) FROM barang b WHERE b.rak_id = r.id) as total_barang,
                       (SELECT IFNULL(SUM(b.stok_tersedia), 0) FROM barang b WHERE b.rak_id = r.id) as total_stok_tersedia
                FROM rak r 
                LEFT JOIN jurusan j ON r.jurusan_id = j.id
                WHERE r.jurusan_id = :jid OR r.jurusan_id IS NULL
                ORDER BY r.created_at DESC
            ");
            $stmt->execute([':jid' => $jurusan_id]);
        } else {
            $stmt = $db->query("
                SELECT r.*, j.nama_jurusan, 
                       (SELECT COUNT(*) FROM barang b WHERE b.rak_id = r.id) as total_barang,
                       (SELECT IFNULL(SUM(b.stok_tersedia), 0) FROM barang b WHERE b.rak_id = r.id) as total_stok_tersedia
                FROM rak r 
                LEFT JOIN jurusan j ON r.jurusan_id = j.id
                ORDER BY r.created_at DESC
            ");
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("
            SELECT r.*, j.nama_jurusan 
            FROM rak r 
            LEFT JOIN jurusan j ON r.jurusan_id = j.id 
            WHERE r.id = :id LIMIT 1
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getItemsInRak($rak_id) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("
            SELECT b.*, k.nama_kategori, j.nama_jurusan, r.nama_rak, r.jenis as jenis_rak 
            FROM barang b 
            LEFT JOIN kategori k ON b.kategori_id = k.id 
            LEFT JOIN jurusan j ON b.jurusan_id = j.id 
            LEFT JOIN rak r ON b.rak_id = r.id 
            WHERE b.rak_id = :rid 
            ORDER BY b.nama_barang ASC
        ");
        $stmt->execute([':rid' => $rak_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function generateNextBarcode() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT barcode FROM rak WHERE barcode LIKE '8993%' ORDER BY barcode DESC LIMIT 1");
        $lastBarcode = $stmt->fetchColumn();
        
        if ($lastBarcode && preg_match('/^8993(\d+)$/', $lastBarcode, $matches)) {
            $nextNum = intval($matches[1]) + 1;
        } else {
            $nextNum = 1;
        }
        
        return '8993' . str_pad($nextNum, 8, '0', STR_PAD_LEFT);
    }

    public static function create($data) {
        $db = Database::getInstance()->getConnection();
        $id = generateUuid();
        $barcode = !empty($data['barcode']) ? $data['barcode'] : self::generateNextBarcode();
        $jenis = (!empty($data['jenis']) && strtolower($data['jenis']) === 'lemari') ? 'lemari' : 'rak';
        $stmt = $db->prepare("
            INSERT INTO rak (id, jurusan_id, nama_rak, jenis, barcode, kategori_rak, keterangan) 
            VALUES (:id, :jid, :nama, :jenis, :barcode, :kat, :ket)
        ");
        return $stmt->execute([
            ':id' => $id,
            ':jid' => !empty($data['jurusan_id']) ? $data['jurusan_id'] : null,
            ':nama' => $data['nama_rak'],
            ':jenis' => $jenis,
            ':barcode' => $barcode,
            ':kat' => !empty($data['kategori_rak']) ? $data['kategori_rak'] : null,
            ':ket' => !empty($data['keterangan']) ? $data['keterangan'] : null
        ]);
    }

    public static function update($id, $data) {
        $db = Database::getInstance()->getConnection();
        $jenis = (!empty($data['jenis']) && strtolower($data['jenis']) === 'lemari') ? 'lemari' : 'rak';
        $stmt = $db->prepare("
            UPDATE rak 
            SET jurusan_id = :jid, 
                nama_rak = :nama, 
                jenis = :jenis,
                barcode = :barcode, 
                kategori_rak = :kat, 
                keterangan = :ket, 
                updated_at = CURRENT_TIMESTAMP 
            WHERE id = :id
        ");
        return $stmt->execute([
            ':id' => $id,
            ':jid' => !empty($data['jurusan_id']) ? $data['jurusan_id'] : null,
            ':nama' => $data['nama_rak'],
            ':jenis' => $jenis,
            ':barcode' => !empty($data['barcode']) ? $data['barcode'] : self::generateNextBarcode(),
            ':kat' => !empty($data['kategori_rak']) ? $data['kategori_rak'] : null,
            ':ket' => !empty($data['keterangan']) ? $data['keterangan'] : null
        ]);
    }

    public static function delete($id) {
        $db = Database::getInstance()->getConnection();
        // Unset rak_id on barang when rak is deleted
        $stmtClear = $db->prepare("UPDATE barang SET rak_id = NULL WHERE rak_id = :id");
        $stmtClear->execute([':id' => $id]);

        $stmt = $db->prepare("DELETE FROM rak WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
