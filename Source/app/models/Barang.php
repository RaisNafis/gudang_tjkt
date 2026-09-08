<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/auth.php';

class Barang {
    public static function getAll($jurusan_id = null) {
        $db = Database::getInstance()->getConnection();
        if (!empty($jurusan_id)) {
            $stmt = $db->prepare("
                SELECT b.*, b.stok_awal as stok_total, k.nama_kategori, j.nama_jurusan, r.nama_rak, r.kategori_rak,
                       (SELECT COALESCE(SUM(pm.jumlah), 0) FROM peminjaman pm WHERE pm.barang_id = b.id AND pm.status != 'dikembalikan') as total_dipinjam,
                       (SELECT COALESCE(SUM(bk.jumlah), 0) FROM barang_keluar bk WHERE bk.barang_id = b.id) as total_keluar
                FROM barang b 
                LEFT JOIN kategori k ON b.kategori_id = k.id 
                LEFT JOIN jurusan j ON b.jurusan_id = j.id 
                LEFT JOIN rak r ON b.rak_id = r.id 
                WHERE b.jurusan_id = :jid 
                ORDER BY b.created_at ASC
            ");
            $stmt->execute([':jid' => $jurusan_id]);
        } else {
            $stmt = $db->query("
                SELECT b.*, b.stok_awal as stok_total, k.nama_kategori, j.nama_jurusan, r.nama_rak, r.kategori_rak,
                       (SELECT COALESCE(SUM(pm.jumlah), 0) FROM peminjaman pm WHERE pm.barang_id = b.id AND pm.status != 'dikembalikan') as total_dipinjam,
                       (SELECT COALESCE(SUM(bk.jumlah), 0) FROM barang_keluar bk WHERE bk.barang_id = b.id) as total_keluar
                FROM barang b 
                LEFT JOIN kategori k ON b.kategori_id = k.id 
                LEFT JOIN jurusan j ON b.jurusan_id = j.id 
                LEFT JOIN rak r ON b.rak_id = r.id 
                ORDER BY b.created_at ASC
            ");
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getStats($jurusan_id = null) {
        $db = Database::getInstance()->getConnection();
        if (!empty($jurusan_id)) {
            $totalItems = $db->prepare("SELECT COUNT(*) as total FROM barang WHERE jurusan_id = :jid");
            $totalItems->execute([':jid' => $jurusan_id]);
            $totalItemsVal = $totalItems->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

            $totalAvailable = $db->prepare("SELECT SUM(stok_tersedia) as total FROM barang WHERE jurusan_id = :jid");
            $totalAvailable->execute([':jid' => $jurusan_id]);
            $totalAvailableVal = $totalAvailable->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

            $totalBorrowing = $db->prepare("SELECT COUNT(*) as total FROM peminjaman WHERE jurusan_id = :jid AND status != 'dikembalikan'");
            $totalBorrowing->execute([':jid' => $jurusan_id]);
            $totalBorrowingVal = $totalBorrowing->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

            $totalLogs = $db->prepare("SELECT COUNT(*) as total FROM log_aktivitas WHERE jurusan_id = :jid");
            $totalLogs->execute([':jid' => $jurusan_id]);
            $totalLogsVal = $totalLogs->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
        } else {
            $totalItemsVal = $db->query("SELECT COUNT(*) as total FROM barang")->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
            $totalAvailableVal = $db->query("SELECT SUM(stok_tersedia) as total FROM barang")->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
            $totalBorrowingVal = $db->query("SELECT COUNT(*) as total FROM peminjaman WHERE status != 'dikembalikan'")->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
            $totalLogsVal = $db->query("SELECT COUNT(*) as total FROM log_aktivitas")->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
        }

        return [
            'total_items' => $totalItemsVal,
            'total_available' => $totalAvailableVal,
            'total_borrowing' => $totalBorrowingVal,
            'total_logs' => $totalLogsVal
        ];
    }

    public static function create($data) {
        $db = Database::getInstance()->getConnection();
        $id = generateUuid();
        $jenis = (!empty($data['jenis']) && strtolower($data['jenis']) === 'bahan') ? 'bahan' : 'alat';
        $stokAwal = isset($data['stok_awal']) ? (int)$data['stok_awal'] : (int)($data['stok_total'] ?? 0);
        $stmt = $db->prepare("
            INSERT INTO barang (id, jurusan_id, kategori_id, rak_id, nama_barang, jenis, merek, barcode, stok_awal, stok_tersedia, satuan) 
            VALUES (:id, :jid, :kat, :rak, :nama, :jenis, :merek, :barcode, :stok_awal, :stok_tersedia, :satuan)
        ");
        return $stmt->execute([
            ':id' => $id,
            ':jid' => !empty($data['jurusan_id']) ? $data['jurusan_id'] : null,
            ':kat' => !empty($data['kategori_id']) ? $data['kategori_id'] : null,
            ':rak' => !empty($data['rak_id']) ? $data['rak_id'] : null,
            ':nama' => $data['nama_barang'],
            ':jenis' => $jenis,
            ':merek' => !empty($data['merek']) ? $data['merek'] : null,
            ':barcode' => !empty($data['barcode']) ? $data['barcode'] : null,
            ':stok_awal' => $stokAwal,
            ':stok_tersedia' => $stokAwal,
            ':satuan' => !empty($data['satuan']) ? $data['satuan'] : 'Unit'
        ]);
    }

    public static function update($id, $data) {
        $db = Database::getInstance()->getConnection();
        $stmtOld = $db->prepare("SELECT stok_awal, stok_tersedia FROM barang WHERE id = :id LIMIT 1");
        $stmtOld->execute([':id' => $id]);
        $old = $stmtOld->fetch(PDO::FETCH_ASSOC);

        if (!$old) return false;

        $newStokAwal = isset($data['stok_awal']) ? (int)$data['stok_awal'] : (int)($data['stok_total'] ?? 0);
        $diff = $newStokAwal - (int)$old['stok_awal'];
        $newStokTersedia = max(0, (int)$old['stok_tersedia'] + $diff);
        $jenis = (!empty($data['jenis']) && strtolower($data['jenis']) === 'bahan') ? 'bahan' : 'alat';

        $stmt = $db->prepare("
            UPDATE barang 
            SET jurusan_id = :jid, 
                kategori_id = :kat, 
                rak_id = :rak, 
                nama_barang = :nama, 
                jenis = :jenis,
                merek = :merek, 
                barcode = :barcode, 
                stok_awal = :stok_awal, 
                stok_tersedia = :stok_tersedia, 
                satuan = :satuan, 
                updated_at = CURRENT_TIMESTAMP 
            WHERE id = :id
        ");
        return $stmt->execute([
            ':id' => $id,
            ':jid' => !empty($data['jurusan_id']) ? $data['jurusan_id'] : null,
            ':kat' => !empty($data['kategori_id']) ? $data['kategori_id'] : null,
            ':rak' => !empty($data['rak_id']) ? $data['rak_id'] : null,
            ':nama' => $data['nama_barang'],
            ':jenis' => $jenis,
            ':merek' => !empty($data['merek']) ? $data['merek'] : null,
            ':barcode' => !empty($data['barcode']) ? $data['barcode'] : null,
            ':stok_awal' => $newStokAwal,
            ':stok_tersedia' => $newStokTersedia,
            ':satuan' => !empty($data['satuan']) ? $data['satuan'] : 'Unit'
        ]);
    }

    public static function delete($id) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM barang WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public static function getByBarcode($barcode) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("
            SELECT b.*, k.nama_kategori, j.nama_jurusan, r.nama_rak, r.kategori_rak 
            FROM barang b 
            LEFT JOIN kategori k ON b.kategori_id = k.id 
            LEFT JOIN jurusan j ON b.jurusan_id = j.id 
            LEFT JOIN rak r ON b.rak_id = r.id 
            WHERE b.barcode = :code OR b.id = :code OR b.kode_barang = :code
            LIMIT 1
        ");
        $stmt->execute([':code' => $barcode]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function generateNextBarcode() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT barcode FROM barang WHERE barcode LIKE '899%' ORDER BY created_at DESC");
        $rows = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $maxNum = 0;
        foreach ($rows as $code) {
            if (preg_match('/^899(\d+)$/', trim($code), $matches)) {
                $num = (int)$matches[1];
                if ($num > $maxNum) {
                    $maxNum = $num;
                }
            }
        }
        if ($maxNum > 0) {
            $nextNum = $maxNum + 1;
            return '899' . str_pad($nextNum, 9, '0', STR_PAD_LEFT);
        }
        return '899100100001';
    }
}
