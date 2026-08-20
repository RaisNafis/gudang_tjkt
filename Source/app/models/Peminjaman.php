<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/auth.php';

class Peminjaman {
    public static function getAll($jurusan_id = null) {
        $db = Database::getInstance()->getConnection();
        if (!empty($jurusan_id)) {
            $stmt = $db->prepare("
                SELECT pm.*, b.nama_barang, b.satuan, j.nama_jurusan,
                       COALESCE(NULLIF(pm.nama_peminjam, ''), p.nama_lengkap, p.nama_pengguna) as nama_peminjam,
                       COALESCE(NULLIF(p.nama_lengkap, ''), p.nama_pengguna, 'Admin') as nama_petugas
                FROM peminjaman pm
                JOIN barang b ON pm.barang_id = b.id
                LEFT JOIN pengguna p ON pm.pengguna_id = p.id
                LEFT JOIN jurusan j ON COALESCE(pm.jurusan_id, b.jurusan_id) = j.id
                WHERE pm.jurusan_id = :jid1 OR b.jurusan_id = :jid2
                ORDER BY pm.created_at ASC
            ");
            $stmt->execute([':jid1' => $jurusan_id, ':jid2' => $jurusan_id]);
        } else {
            $stmt = $db->query("
                SELECT pm.*, b.nama_barang, b.satuan, j.nama_jurusan,
                       COALESCE(NULLIF(pm.nama_peminjam, ''), p.nama_lengkap, p.nama_pengguna) as nama_peminjam,
                       COALESCE(NULLIF(p.nama_lengkap, ''), p.nama_pengguna, 'Admin') as nama_petugas
                FROM peminjaman pm
                JOIN barang b ON pm.barang_id = b.id
                LEFT JOIN pengguna p ON pm.pengguna_id = p.id
                LEFT JOIN jurusan j ON COALESCE(pm.jurusan_id, b.jurusan_id) = j.id
                ORDER BY pm.created_at ASC
            ");
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findById($id) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("
            SELECT pm.*, b.nama_barang, b.satuan, j.nama_jurusan,
                   COALESCE(NULLIF(pm.nama_peminjam, ''), p.nama_lengkap, p.nama_pengguna) as nama_peminjam,
                   COALESCE(NULLIF(p.nama_lengkap, ''), p.nama_pengguna, 'Admin') as nama_petugas
            FROM peminjaman pm
            JOIN barang b ON pm.barang_id = b.id
            LEFT JOIN pengguna p ON pm.pengguna_id = p.id
            LEFT JOIN jurusan j ON COALESCE(pm.jurusan_id, b.jurusan_id) = j.id
            WHERE pm.id = :id
            LIMIT 1
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($barang_id, $pengguna_id, $jumlah, $nama_peminjam = null, $catatan = null, $tanggal_pinjam = null, $tanggal_kembali = null, $tugas = null, $tahun_ajaran = '2025/2026') {
        $db = Database::getInstance()->getConnection();
        $id = generateUuid();

        if ($pengguna_id) {
            $chkUser = $db->prepare("SELECT id FROM pengguna WHERE id = :pid LIMIT 1");
            $chkUser->execute([':pid' => $pengguna_id]);
            if (!$chkUser->fetch()) {
                $pengguna_id = null;
            }
        }

        if (!$pengguna_id) {
            $firstUser = $db->query("SELECT id FROM pengguna LIMIT 1")->fetch();
            $pengguna_id = $firstUser['id'] ?? null;
        }

        if (!$pengguna_id) {
            return ['success' => false, 'message' => 'Pengguna tidak terdaftar pada sistem.'];
        }

        $db->beginTransaction();
        try {
            $check = $db->prepare("SELECT stok_tersedia, jurusan_id FROM barang WHERE id = :bid LIMIT 1");
            $check->execute([':bid' => $barang_id]);
            $brg = $check->fetch();

            $jurusan_id = $brg['jurusan_id'] ?? null;

            $tglPinjam = !empty($tanggal_pinjam) ? date('Y-m-d H:i:s', strtotime($tanggal_pinjam)) : date('Y-m-d H:i:s');
            $tglKembali = !empty($tanggal_kembali) ? date('Y-m-d H:i:s', strtotime($tanggal_kembali)) : null;
            $status = !empty($tglKembali) ? 'dikembalikan' : 'dipinjam';
            $thnAjaran = !empty($tahun_ajaran) ? $tahun_ajaran : '2025/2026';

            if ($status === 'dipinjam' && (!$brg || $brg['stok_tersedia'] < $jumlah)) {
                $db->rollBack();
                return ['success' => false, 'message' => 'Stok alat tidak mencukupi untuk dipinjam! (Tersedia: ' . ($brg['stok_tersedia'] ?? 0) . ' Unit)'];
            }

            $stmt = $db->prepare("
                INSERT INTO peminjaman (id, jurusan_id, barang_id, pengguna_id, nama_peminjam, jumlah, status, catatan, tugas, tahun_ajaran, tanggal_pinjam, tanggal_kembali)
                VALUES (:id, :jid, :bid, :pid, :peminjam, :jumlah, :status, :catatan, :tugas, :thn_ajaran, :tgl_pinjam, :tgl_kembali)
            ");
            $stmt->execute([
                ':id' => $id,
                ':jid' => $jurusan_id,
                ':bid' => $barang_id,
                ':pid' => $pengguna_id,
                ':peminjam' => $nama_peminjam,
                ':jumlah' => $jumlah,
                ':status' => $status,
                ':catatan' => $catatan,
                ':tugas' => $tugas,
                ':thn_ajaran' => $thnAjaran,
                ':tgl_pinjam' => $tglPinjam,
                ':tgl_kembali' => $tglKembali
            ]);

            if ($status === 'dipinjam') {
                $upd = $db->prepare("
                    UPDATE barang 
                    SET stok_tersedia = GREATEST(0, stok_tersedia - :jml) 
                    WHERE id = :bid
                ");
                $upd->execute([':jml' => $jumlah, ':bid' => $barang_id]);
            }

            $db->commit();
            return ['success' => true, 'message' => 'Peminjaman alat berhasil disimpan!'];
        } catch (Exception $e) {
            $db->rollBack();
            return ['success' => false, 'message' => 'Gagal menyimpan peminjaman: ' . $e->getMessage()];
        }
    }

    public static function returnItem($id, $buktiFoto = null) {
        $db = Database::getInstance()->getConnection();
        $db->beginTransaction();
        try {
            $check = $db->prepare("SELECT barang_id, jumlah, status FROM peminjaman WHERE id = :id LIMIT 1");
            $check->execute([':id' => $id]);
            $pinjam = $check->fetch();

            if (!$pinjam || $pinjam['status'] === 'dikembalikan') {
                $db->rollBack();
                return ['success' => false, 'message' => 'Peminjaman sudah dikembalikan atau tidak ditemukan.'];
            }

            if (!empty($buktiFoto)) {
                $stmt = $db->prepare("
                    UPDATE peminjaman 
                    SET status = 'pending', tanggal_kembali = NOW(), bukti_foto = :bukti 
                    WHERE id = :id
                ");
                $stmt->execute([':id' => $id, ':bukti' => $buktiFoto]);
            } else {
                $stmt = $db->prepare("
                    UPDATE peminjaman 
                    SET status = 'pending', tanggal_kembali = NOW() 
                    WHERE id = :id
                ");
                $stmt->execute([':id' => $id]);
            }

            $db->commit();
            return ['success' => true, 'message' => 'Pengajuan pengembalian berhasil diunggah! Menunggu persetujuan admin/petugas.'];
        } catch (Exception $e) {
            $db->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public static function approveReturn($id) {
        $db = Database::getInstance()->getConnection();
        $db->beginTransaction();
        try {
            $check = $db->prepare("SELECT barang_id, jumlah, status FROM peminjaman WHERE id = :id LIMIT 1");
            $check->execute([':id' => $id]);
            $pinjam = $check->fetch();

            if (!$pinjam) {
                $db->rollBack();
                return ['success' => false, 'message' => 'Peminjaman tidak ditemukan.'];
            }

            if ($pinjam['status'] === 'dikembalikan') {
                $db->rollBack();
                return ['success' => false, 'message' => 'Peminjaman sudah dikembalikan.'];
            }

            $stmt = $db->prepare("
                UPDATE peminjaman 
                SET status = 'dikembalikan', tanggal_kembali = COALESCE(tanggal_kembali, NOW()) 
                WHERE id = :id
            ");
            $stmt->execute([':id' => $id]);

            $upd = $db->prepare("
                UPDATE barang 
                SET stok_tersedia = LEAST(stok_total, stok_tersedia + :jml) 
                WHERE id = :bid
            ");
            $upd->execute([':jml' => $pinjam['jumlah'], ':bid' => $pinjam['barang_id']]);

            $db->commit();
            return ['success' => true, 'message' => 'Pengembalian barang berhasil disetujui! Stok barang telah dikembalikan.'];
        } catch (Exception $e) {
            $db->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public static function rejectReturn($id) {
        $db = Database::getInstance()->getConnection();
        $db->beginTransaction();
        try {
            $check = $db->prepare("SELECT barang_id, jumlah, status FROM peminjaman WHERE id = :id LIMIT 1");
            $check->execute([':id' => $id]);
            $pinjam = $check->fetch();

            if (!$pinjam) {
                $db->rollBack();
                return ['success' => false, 'message' => 'Peminjaman tidak ditemukan.'];
            }

            if ($pinjam['status'] === 'dikembalikan') {
                $db->rollBack();
                return ['success' => false, 'message' => 'Peminjaman sudah dikembalikan.'];
            }

            $stmt = $db->prepare("
                UPDATE peminjaman 
                SET status = 'ditolak' 
                WHERE id = :id
            ");
            $stmt->execute([':id' => $id]);

            $db->commit();
            return ['success' => true, 'message' => 'Pengajuan pengembalian barang berhasil ditolak. Peminjam perlu mengunggah foto bukti ulang.'];
        } catch (Exception $e) {
            $db->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public static function update($id, $nama_peminjam, $jumlah, $tanggal_pinjam = null, $tanggal_kembali = null, $tugas = null, $status = null, $tahun_ajaran = null) {
        $db = Database::getInstance()->getConnection();
        $db->beginTransaction();
        try {
            $check = $db->prepare("SELECT barang_id, jumlah, status FROM peminjaman WHERE id = :id LIMIT 1");
            $check->execute([':id' => $id]);
            $old = $check->fetch();

            if (!$old) {
                $db->rollBack();
                return ['success' => false, 'message' => 'Data peminjaman tidak ditemukan.'];
            }

            $tglPinjam = !empty($tanggal_pinjam) ? date('Y-m-d H:i:s', strtotime($tanggal_pinjam)) : date('Y-m-d H:i:s');
            $tglKembali = !empty($tanggal_kembali) ? date('Y-m-d H:i:s', strtotime($tanggal_kembali)) : null;
            $newStatus = !empty($status) ? $status : (!empty($tglKembali) ? 'dikembalikan' : 'dipinjam');

            if ($old['status'] === 'dipinjam' && $newStatus === 'dikembalikan') {
                $updStok = $db->prepare("UPDATE barang SET stok_tersedia = LEAST(stok_total, stok_tersedia + :jml) WHERE id = :bid");
                $updStok->execute([':jml' => $old['jumlah'], ':bid' => $old['barang_id']]);
            } else if (($old['status'] === 'dikembalikan' || $old['status'] === 'pending' || $old['status'] === 'ditolak') && $newStatus === 'dipinjam') {
                if ($old['status'] === 'dikembalikan') {
                    $brgCheck = $db->prepare("SELECT stok_tersedia FROM barang WHERE id = :bid LIMIT 1");
                    $brgCheck->execute([':bid' => $old['barang_id']]);
                    $brg = $brgCheck->fetch();

                    if ($brg['stok_tersedia'] < $jumlah) {
                        $db->rollBack();
                        return ['success' => false, 'message' => 'Stok barang tidak mencukupi untuk peminjaman!'];
                    }
                    $updStok = $db->prepare("UPDATE barang SET stok_tersedia = GREATEST(0, stok_tersedia - :jml) WHERE id = :bid");
                    $updStok->execute([':jml' => $jumlah, ':bid' => $old['barang_id']]);
                }
                $tglKembali = null;
            } else if ($old['status'] === 'dipinjam' && $newStatus === 'dipinjam') {
                $diff = $jumlah - $old['jumlah'];
                if ($diff != 0) {
                    $brgCheck = $db->prepare("SELECT stok_tersedia FROM barang WHERE id = :bid LIMIT 1");
                    $brgCheck->execute([':bid' => $old['barang_id']]);
                    $brg = $brgCheck->fetch();

                    if ($diff > 0 && ($brg['stok_tersedia'] < $diff)) {
                        $db->rollBack();
                        return ['success' => false, 'message' => 'Stok barang tidak mencukupi untuk penambahan jumlah pinjam!'];
                    }

                    $updStok = $db->prepare("UPDATE barang SET stok_tersedia = GREATEST(0, stok_tersedia - :diff) WHERE id = :bid");
                    $updStok->execute([':diff' => $diff, ':bid' => $old['barang_id']]);
                }
            }

            $stmt = $db->prepare("UPDATE peminjaman SET nama_peminjam = :peminjam, jumlah = :jumlah, status = :status, tugas = :tugas, tahun_ajaran = COALESCE(:thn_ajaran, tahun_ajaran), tanggal_pinjam = :tgl_pinjam, tanggal_kembali = :tgl_kembali WHERE id = :id");
            $stmt->execute([
                ':peminjam' => $nama_peminjam, 
                ':jumlah' => $jumlah, 
                ':status' => $newStatus,
                ':tugas' => $tugas,
                ':thn_ajaran' => $tahun_ajaran,
                ':tgl_pinjam' => $tglPinjam,
                ':tgl_kembali' => $tglKembali,
                ':id' => $id
            ]);

            $db->commit();
            return ['success' => true, 'message' => 'Data peminjaman berhasil diperbarui!'];
        } catch (Exception $e) {
            $db->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public static function delete($id) {
        $db = Database::getInstance()->getConnection();
        $db->beginTransaction();
        try {
            $check = $db->prepare("SELECT barang_id, jumlah, status FROM peminjaman WHERE id = :id LIMIT 1");
            $check->execute([':id' => $id]);
            $old = $check->fetch();

            if ($old && $old['status'] === 'dipinjam') {
                $upd = $db->prepare("UPDATE barang SET stok_tersedia = LEAST(stok_total, stok_tersedia + :jml) WHERE id = :bid");
                $upd->execute([':jml' => $old['jumlah'], ':bid' => $old['barang_id']]);
            }

            $stmt = $db->prepare("DELETE FROM peminjaman WHERE id = :id");
            $stmt->execute([':id' => $id]);

            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            return false;
        }
    }
}
