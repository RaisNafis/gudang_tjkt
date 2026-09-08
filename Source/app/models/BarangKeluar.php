<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/auth.php';

class BarangKeluar {
    public static function getAll($jurusan_id = null) {
        $db = Database::getInstance()->getConnection();
        if (!empty($jurusan_id)) {
            $stmt = $db->prepare("
                SELECT bk.*, b.nama_barang, b.satuan, p.nama_lengkap as nama_petugas, j.nama_jurusan
                FROM barang_keluar bk
                JOIN barang b ON bk.barang_id = b.id
                LEFT JOIN pengguna p ON bk.pengguna_id = p.id
                LEFT JOIN jurusan j ON COALESCE(bk.jurusan_id, b.jurusan_id) = j.id
                WHERE bk.jurusan_id = :jid1 OR b.jurusan_id = :jid2
                ORDER BY bk.created_at ASC
            ");
            $stmt->execute([':jid1' => $jurusan_id, ':jid2' => $jurusan_id]);
        } else {
            $stmt = $db->query("
                SELECT bk.*, b.nama_barang, b.satuan, p.nama_lengkap as nama_petugas, j.nama_jurusan
                FROM barang_keluar bk
                JOIN barang b ON bk.barang_id = b.id
                LEFT JOIN pengguna p ON bk.pengguna_id = p.id
                LEFT JOIN jurusan j ON COALESCE(bk.jurusan_id, b.jurusan_id) = j.id
                ORDER BY bk.created_at ASC
            ");
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create($barang_id, $pengguna_id, $nama_penerima, $jumlah, $catatan = null) {
        $db = Database::getInstance()->getConnection();
        $id = generateUuid();

        if ($pengguna_id) {
            $chkUser = $db->prepare("SELECT id FROM pengguna WHERE id = :pid LIMIT 1");
            $chkUser->execute([':pid' => $pengguna_id]);
            if (!$chkUser->fetch()) {
                $pengguna_id = null;
            }
        }
        
        $db->beginTransaction();
        try {
            $check = $db->prepare("SELECT stok_tersedia, jurusan_id FROM barang WHERE id = :bid LIMIT 1");
            $check->execute([':bid' => $barang_id]);
            $brg = $check->fetch();

            if (!$brg || $brg['stok_tersedia'] < $jumlah) {
                $db->rollBack();
                return ['success' => false, 'message' => 'Stok barang tidak mencukupi! (Tersedia: ' . ($brg['stok_tersedia'] ?? 0) . ' Unit)'];
            }

            $jurusan_id = $brg['jurusan_id'] ?? null;

            $stmt = $db->prepare("
                INSERT INTO barang_keluar (id, jurusan_id, barang_id, pengguna_id, nama_penerima, jumlah, catatan)
                VALUES (:id, :jid, :bid, :pid, :penerima, :jumlah, :catatan)
            ");
            $stmt->execute([
                ':id' => $id,
                ':jid' => $jurusan_id,
                ':bid' => $barang_id,
                ':pid' => $pengguna_id,
                ':penerima' => $nama_penerima,
                ':jumlah' => $jumlah,
                ':catatan' => $catatan
            ]);

            $upd = $db->prepare("
                UPDATE barang 
                SET stok_tersedia = GREATEST(0, stok_tersedia - :jml) 
                WHERE id = :bid
            ");
            $upd->execute([':jml' => $jumlah, ':bid' => $barang_id]);

            $db->commit();
            return ['success' => true, 'message' => 'Transaksi barang keluar berhasil disimpan!'];
        } catch (Exception $e) {
            $db->rollBack();
            return ['success' => false, 'message' => 'Gagal menyimpan barang keluar: ' . $e->getMessage()];
        }
    }

    public static function update($id, $nama_penerima, $jumlah) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM barang_keluar WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $old = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$old) return ['success' => false, 'message' => 'Data barang keluar tidak ditemukan'];

        $db->beginTransaction();
        try {
            $diff = (int)$jumlah - (int)$old['jumlah'];
            if ($diff > 0) {
                $check = $db->prepare("SELECT stok_tersedia FROM barang WHERE id = :bid LIMIT 1");
                $check->execute([':bid' => $old['barang_id']]);
                $brg = $check->fetch();
                if (!$brg || $brg['stok_tersedia'] < $diff) {
                    $db->rollBack();
                    return ['success' => false, 'message' => 'Stok tidak mencukupi untuk penambahan jumlah keluar!'];
                }
            }

            $updStok = $db->prepare("UPDATE barang SET stok_tersedia = GREATEST(0, stok_tersedia - :diff) WHERE id = :bid");
            $updStok->execute([':diff' => $diff, ':bid' => $old['barang_id']]);

            $upd = $db->prepare("UPDATE barang_keluar SET nama_penerima = :penerima, jumlah = :jumlah, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
            $upd->execute([':penerima' => $nama_penerima, ':jumlah' => $jumlah, ':id' => $id]);

            $db->commit();
            return ['success' => true, 'message' => 'Transaksi barang keluar berhasil diperbarui!'];
        } catch (Exception $e) {
            $db->rollBack();
            return ['success' => false, 'message' => 'Gagal mengubah barang keluar: ' . $e->getMessage()];
        }
    }

    public static function delete($id) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM barang_keluar WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $old = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$old) return false;

        $db->beginTransaction();
        try {
            $updStok = $db->prepare("UPDATE barang SET stok_tersedia = stok_tersedia + :j WHERE id = :bid");
            $updStok->execute([':j' => $old['jumlah'], ':bid' => $old['barang_id']]);

            $del = $db->prepare("DELETE FROM barang_keluar WHERE id = :id");
            $del->execute([':id' => $id]);

            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            return false;
        }
    }
}
