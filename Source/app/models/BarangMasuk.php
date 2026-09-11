<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/Barang.php';

class BarangMasuk {
    public static function getAll($jurusan_id = null) {
        $db = Database::getInstance()->getConnection();
        if (!empty($jurusan_id)) {
            $stmt = $db->prepare("
                SELECT bm.*, b.nama_barang, b.satuan, p.nama_lengkap as nama_petugas, j.nama_jurusan
                FROM barang_masuk bm
                JOIN barang b ON bm.barang_id = b.id
                LEFT JOIN pengguna p ON bm.pengguna_id = p.id
                LEFT JOIN jurusan j ON COALESCE(bm.jurusan_id, b.jurusan_id) = j.id
                WHERE bm.jurusan_id = :jid1 OR b.jurusan_id = :jid2
                ORDER BY bm.created_at ASC
            ");
            $stmt->execute([':jid1' => $jurusan_id, ':jid2' => $jurusan_id]);
        } else {
            $stmt = $db->query("
                SELECT bm.*, b.nama_barang, b.satuan, p.nama_lengkap as nama_petugas, j.nama_jurusan
                FROM barang_masuk bm
                JOIN barang b ON bm.barang_id = b.id
                LEFT JOIN pengguna p ON bm.pengguna_id = p.id
                LEFT JOIN jurusan j ON COALESCE(bm.jurusan_id, b.jurusan_id) = j.id
                ORDER BY bm.created_at ASC
            ");
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create($barang_id, $pengguna_id, $jumlah, $catatan = null, $tanggal_masuk = null) {
        $db = Database::getInstance()->getConnection();
        $id = generateUuid();
        
        if ($pengguna_id) {
            $chkUser = $db->prepare("SELECT id FROM pengguna WHERE id = :pid LIMIT 1");
            $chkUser->execute([':pid' => $pengguna_id]);
            if (!$chkUser->fetch()) {
                $pengguna_id = null;
            }
        }

        $chkBrg = $db->prepare("SELECT jurusan_id FROM barang WHERE id = :bid LIMIT 1");
        $chkBrg->execute([':bid' => $barang_id]);
        $jurusan_id = $chkBrg->fetchColumn() ?: null;

        if (empty($tanggal_masuk)) {
            $tanggal_masuk = date('Y-m-d H:i:s');
        } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal_masuk)) {
            $tanggal_masuk .= ' ' . date('H:i:s');
        }

        $db->beginTransaction();
        try {
            $stmt = $db->prepare("
                INSERT INTO barang_masuk (id, jurusan_id, barang_id, pengguna_id, jumlah, catatan, tanggal_masuk)
                VALUES (:id, :jid, :bid, :pid, :jumlah, :catatan, :tanggal_masuk)
            ");
            $stmt->execute([
                ':id' => $id,
                ':jid' => $jurusan_id,
                ':bid' => $barang_id,
                ':pid' => $pengguna_id,
                ':jumlah' => $jumlah,
                ':catatan' => $catatan,
                ':tanggal_masuk' => $tanggal_masuk
            ]);

            // Update stok barang
            $upd = $db->prepare("
                UPDATE barang 
                SET stok_tersedia = stok_tersedia + :jml 
                WHERE id = :bid
            ");
            $upd->execute([':jml' => $jumlah, ':bid' => $barang_id]);

            $db->commit();
            Barang::recalculateStok($barang_id);
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            return false;
        }
    }

    public static function update($id, $jumlah, $catatan = null, $tanggal_masuk = null) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM barang_masuk WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $old = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$old) return ['success' => false, 'message' => 'Data transaksi barang masuk tidak ditemukan'];

        if (!empty($tanggal_masuk) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal_masuk)) {
            $existingTime = !empty($old['tanggal_masuk']) ? substr($old['tanggal_masuk'], 11) : date('H:i:s');
            $tanggal_masuk .= ' ' . ($existingTime ?: date('H:i:s'));
        }

        $db->beginTransaction();
        try {
            $diff = (int)$jumlah - (int)$old['jumlah'];
            if ($diff < 0) {
                $check = $db->prepare("SELECT stok_tersedia FROM barang WHERE id = :bid LIMIT 1");
                $check->execute([':bid' => $old['barang_id']]);
                $brg = $check->fetch(PDO::FETCH_ASSOC);
                if (!$brg || ((int)$brg['stok_tersedia'] + $diff) < 0) {
                    $db->rollBack();
                    return ['success' => false, 'message' => 'Stok tidak mencukupi untuk pengurangan barang masuk! (Tersedia: ' . ($brg['stok_tersedia'] ?? 0) . ' Unit)'];
                }
            }

            $updStok = $db->prepare("UPDATE barang SET stok_tersedia = GREATEST(0, stok_tersedia + :diff) WHERE id = :bid");
            $updStok->execute([':diff' => $diff, ':bid' => $old['barang_id']]);

            if (!empty($tanggal_masuk)) {
                $upd = $db->prepare("UPDATE barang_masuk SET jumlah = :jumlah, catatan = :catatan, tanggal_masuk = :tanggal_masuk, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
                $upd->execute([':jumlah' => $jumlah, ':catatan' => $catatan, ':tanggal_masuk' => $tanggal_masuk, ':id' => $id]);
            } else {
                $upd = $db->prepare("UPDATE barang_masuk SET jumlah = :jumlah, catatan = :catatan, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
                $upd->execute([':jumlah' => $jumlah, ':catatan' => $catatan, ':id' => $id]);
            }

            $db->commit();
            Barang::recalculateStok($old['barang_id']);
            return ['success' => true, 'message' => 'Transaksi barang masuk berhasil diperbarui!'];
        } catch (Exception $e) {
            $db->rollBack();
            return ['success' => false, 'message' => 'Gagal mengubah barang masuk: ' . $e->getMessage()];
        }
    }

    public static function delete($id) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM barang_masuk WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $old = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$old) return false;

        $db->beginTransaction();
        try {
            $updStok = $db->prepare("UPDATE barang SET stok_tersedia = GREATEST(0, stok_tersedia - :j) WHERE id = :bid");
            $updStok->execute([':j' => $old['jumlah'], ':bid' => $old['barang_id']]);

            $del = $db->prepare("DELETE FROM barang_masuk WHERE id = :id");
            $del->execute([':id' => $id]);

            $db->commit();
            Barang::recalculateStok($old['barang_id']);
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            return false;
        }
    }
}
