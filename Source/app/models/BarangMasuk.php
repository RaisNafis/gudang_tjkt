<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/auth.php';

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

    public static function create($barang_id, $pengguna_id, $nama_pemasok, $jumlah, $catatan = null) {
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

        $db->beginTransaction();
        try {
            $stmt = $db->prepare("
                INSERT INTO barang_masuk (id, jurusan_id, barang_id, pengguna_id, nama_pemasok, jumlah, catatan)
                VALUES (:id, :jid, :bid, :pid, :pemasok, :jumlah, :catatan)
            ");
            $stmt->execute([
                ':id' => $id,
                ':jid' => $jurusan_id,
                ':bid' => $barang_id,
                ':pid' => $pengguna_id,
                ':pemasok' => $nama_pemasok,
                ':jumlah' => $jumlah,
                ':catatan' => $catatan
            ]);

            // Update stok barang
            $upd = $db->prepare("
                UPDATE barang 
                SET stok_total = stok_total + :jml1, stok_tersedia = stok_tersedia + :jml2 
                WHERE id = :bid
            ");
            $upd->execute([':jml1' => $jumlah, ':jml2' => $jumlah, ':bid' => $barang_id]);

            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            return false;
        }
    }

    public static function update($id, $nama_pemasok, $jumlah) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM barang_masuk WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $old = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$old) return ['success' => false, 'message' => 'Data transaksi barang masuk tidak ditemukan'];

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

            $updStok = $db->prepare("UPDATE barang SET stok_total = GREATEST(0, stok_total + :diff1), stok_tersedia = GREATEST(0, stok_tersedia + :diff2) WHERE id = :bid");
            $updStok->execute([':diff1' => $diff, ':diff2' => $diff, ':bid' => $old['barang_id']]);

            $upd = $db->prepare("UPDATE barang_masuk SET nama_pemasok = :pemasok, jumlah = :jumlah, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
            $upd->execute([':pemasok' => $nama_pemasok, ':jumlah' => $jumlah, ':id' => $id]);

            $db->commit();
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
            $updStok = $db->prepare("UPDATE barang SET stok_total = GREATEST(0, stok_total - :j1), stok_tersedia = GREATEST(0, stok_tersedia - :j2) WHERE id = :bid");
            $updStok->execute([':j1' => $old['jumlah'], ':j2' => $old['jumlah'], ':bid' => $old['barang_id']]);

            $del = $db->prepare("DELETE FROM barang_masuk WHERE id = :id");
            $del->execute([':id' => $id]);

            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            return false;
        }
    }
}
