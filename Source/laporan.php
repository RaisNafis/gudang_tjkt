<?php
// laporan.php - Cetak dan Export Laporan Transaksi Inventaris (Barang Masuk, Bahan Keluar, Peminjaman Alat)
date_default_timezone_set('Asia/Jakarta');

require_once __DIR__ . '/app/helpers/auth.php';
require_once __DIR__ . '/app/config/database.php';
require_once __DIR__ . '/app/models/Jurusan.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$currentUser = currentUser();
$userPeran = $currentUser['peran'] ?? 'siswa';

// Pembatasan akses: Siswa tidak diizinkan mencetak laporan inventaris umum
if ($userPeran === 'siswa') {
    http_response_code(403);
    die("<div style='font-family:sans-serif;text-align:center;padding:50px;'><h2>Akses Ditolak</h2><p>Anda tidak memiliki izin untuk mengakses laporan ini.</p><a href='dashboard.php'>Kembali ke Dashboard</a></div>");
}

$db = Database::getInstance()->getConnection();
if (!$db) {
    die("Koneksi database gagal.");
}

// Tangkap Parameter Request
$jenis = strtolower($_GET['jenis'] ?? 'barang_masuk');
if (!in_array($jenis, ['barang_masuk', 'barang_keluar', 'peminjaman'])) {
    $jenis = 'barang_masuk';
}

$tglMulai = !empty($_GET['tgl_mulai']) ? trim($_GET['tgl_mulai']) : '';
$tglSelesai = !empty($_GET['tgl_selesai']) ? trim($_GET['tgl_selesai']) : '';
$jurusanId = !empty($_GET['jurusan_id']) ? trim($_GET['jurusan_id']) : '';
$statusPinjam = strtolower($_GET['status'] ?? 'semua');
$format = strtolower($_GET['format'] ?? 'pdf');

// Pembatasan jurusan bagi selain Admin Sekolah
if ($userPeran !== 'admin_sekolah' && !empty($currentUser['jurusan_id'])) {
    $jurusanId = $currentUser['jurusan_id'];
}

// Helper Format Tanggal Indonesia
function tglIndo($tgl, $includeTime = false) {
    if (empty($tgl)) return '-';
    $timestamp = strtotime($tgl);
    if (!$timestamp) return '-';

    $bulanList = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];

    $d = date('j', $timestamp);
    $m = $bulanList[(int)date('n', $timestamp)];
    $y = date('Y', $timestamp);

    $res = "{$d} {$m} {$y}";
    if ($includeTime) {
        $res .= ' pukul ' . date('H:i', $timestamp) . ' WIB';
    }
    return $res;
}

// Tentukan Keterangan Periode untuk Header Laporan
$periodeLabel = "Seluruh Periode (Semua Waktu)";
if (!empty($tglMulai) && !empty($tglSelesai)) {
    if ($tglMulai === $tglSelesai) {
        $periodeLabel = tglIndo($tglMulai);
    } else {
        $periodeLabel = tglIndo($tglMulai) . " s/d " . tglIndo($tglSelesai);
    }
} elseif (!empty($tglMulai)) {
    $periodeLabel = "Mulai " . tglIndo($tglMulai) . " s/d Sekarang";
} elseif (!empty($tglSelesai)) {
    $periodeLabel = "Hingga " . tglIndo($tglSelesai);
}

// Ambil Nama Jurusan Terpilih (jika ada)
$namaJurusanFilter = "Semua Jurusan / Departemen";
if (!empty($jurusanId)) {
    $stmtJ = $db->prepare("SELECT nama_jurusan FROM jurusan WHERE id = :jid LIMIT 1");
    $stmtJ->execute([':jid' => $jurusanId]);
    $resJ = $stmtJ->fetchColumn();
    if ($resJ) {
        $namaJurusanFilter = $resJ;
    }
}

// Konfigurasi Judul Laporan berdasarkan Jenis Transaksi
$judulLaporan = "";
$subJudulLaporan = "";
$dataRows = [];

if ($jenis === 'barang_masuk') {
    $judulLaporan = "LAPORAN TRANSAKSI ALAT & BAHAN MASUK";
    $subJudulLaporan = "Daftar Penerimaan, Pengadaan, dan Stok Masuk Inventaris";

    $sql = "
        SELECT bm.*, 
               b.nama_barang, b.satuan, b.jenis as jenis_barang, b.merek, b.barcode,
               j.nama_jurusan,
               COALESCE(NULLIF(p.nama_lengkap, ''), p.nama_pengguna, 'Petugas') as nama_petugas
        FROM barang_masuk bm
        JOIN barang b ON bm.barang_id = b.id
        LEFT JOIN pengguna p ON bm.pengguna_id = p.id
        LEFT JOIN jurusan j ON COALESCE(bm.jurusan_id, b.jurusan_id) = j.id
        WHERE 1=1
    ";
    $params = [];

    if (!empty($jurusanId)) {
        $sql .= " AND (bm.jurusan_id = :jid OR b.jurusan_id = :jid) ";
        $params[':jid'] = $jurusanId;
    }
    if (!empty($tglMulai)) {
        $sql .= " AND DATE(COALESCE(bm.tanggal_masuk, bm.created_at)) >= :tgl_mulai ";
        $params[':tgl_mulai'] = $tglMulai;
    }
    if (!empty($tglSelesai)) {
        $sql .= " AND DATE(COALESCE(bm.tanggal_masuk, bm.created_at)) <= :tgl_selesai ";
        $params[':tgl_selesai'] = $tglSelesai;
    }

    $sql .= " ORDER BY COALESCE(bm.tanggal_masuk, bm.created_at) DESC ";
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $dataRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

} elseif ($jenis === 'barang_keluar') {
    $judulLaporan = "LAPORAN TRANSAKSI BAHAN KELUAR";
    $subJudulLaporan = "Daftar Pemakaian, Pengeluaran, dan Distribusi Bahan Praktik";

    $sql = "
        SELECT bk.*, 
               b.nama_barang, b.satuan, b.jenis as jenis_barang, b.merek, b.barcode,
               j.nama_jurusan,
               COALESCE(NULLIF(p.nama_lengkap, ''), p.nama_pengguna, 'Petugas') as nama_petugas
        FROM barang_keluar bk
        JOIN barang b ON bk.barang_id = b.id
        LEFT JOIN pengguna p ON bk.pengguna_id = p.id
        LEFT JOIN jurusan j ON COALESCE(bk.jurusan_id, b.jurusan_id) = j.id
        WHERE 1=1
    ";
    $params = [];

    if (!empty($jurusanId)) {
        $sql .= " AND (bk.jurusan_id = :jid OR b.jurusan_id = :jid) ";
        $params[':jid'] = $jurusanId;
    }
    if (!empty($tglMulai)) {
        $sql .= " AND DATE(COALESCE(bk.tanggal_keluar, bk.created_at)) >= :tgl_mulai ";
        $params[':tgl_mulai'] = $tglMulai;
    }
    if (!empty($tglSelesai)) {
        $sql .= " AND DATE(COALESCE(bk.tanggal_keluar, bk.created_at)) <= :tgl_selesai ";
        $params[':tgl_selesai'] = $tglSelesai;
    }

    $sql .= " ORDER BY COALESCE(bk.tanggal_keluar, bk.created_at) DESC ";
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $dataRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

} elseif ($jenis === 'peminjaman') {
    $judulLaporan = "LAPORAN SIRKULASI PEMINJAMAN ALAT";
    $subJudulLaporan = "Catatan Peminjaman, Pengembalian, dan Penggunaan Alat Laboratorium";

    $sql = "
        SELECT pm.*, 
               b.nama_barang, b.satuan, b.jenis as jenis_barang, b.merek, b.barcode,
               j.nama_jurusan,
               COALESCE(NULLIF(p.nama_lengkap, ''), p.nama_pengguna, 'Petugas') as nama_petugas
        FROM peminjaman pm
        JOIN barang b ON pm.barang_id = b.id
        LEFT JOIN pengguna p ON pm.pengguna_id = p.id
        LEFT JOIN jurusan j ON COALESCE(pm.jurusan_id, b.jurusan_id) = j.id
        WHERE 1=1
    ";
    $params = [];

    if (!empty($jurusanId)) {
        $sql .= " AND (pm.jurusan_id = :jid OR b.jurusan_id = :jid) ";
        $params[':jid'] = $jurusanId;
    }
    if (!empty($statusPinjam) && $statusPinjam !== 'semua') {
        $sql .= " AND pm.status = :status ";
        $params[':status'] = $statusPinjam;
    }
    if (!empty($tglMulai)) {
        $sql .= " AND DATE(COALESCE(pm.tanggal_pinjam, pm.created_at)) >= :tgl_mulai ";
        $params[':tgl_mulai'] = $tglMulai;
    }
    if (!empty($tglSelesai)) {
        $sql .= " AND DATE(COALESCE(pm.tanggal_pinjam, pm.created_at)) <= :tgl_selesai ";
        $params[':tgl_selesai'] = $tglSelesai;
    }

    $sql .= " ORDER BY COALESCE(pm.tanggal_pinjam, pm.created_at) DESC ";
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $dataRows = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Hitung Ringkasan / Total
$totalTransaksi = count($dataRows);
$totalJumlahBarang = array_sum(array_map(fn($r) => (int)($r['jumlah'] ?? 0), $dataRows));

// =========================================================================
// MODE EXPORT EXCEL (.xls)
// =========================================================================
if ($format === 'excel') {
    $cleanJenis = ucfirst(str_replace('_', ' ', $jenis));
    $filename = "Laporan_{$cleanJenis}_" . date('Ymd_His') . ".xls";

    header('Content-Type: application/vnd.ms-excel; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title><?= htmlspecialchars($judulLaporan); ?></title>
        <style>
            body { font-family: Arial, sans-serif; font-size: 11pt; }
            table { border-collapse: collapse; width: 100%; margin-top: 15px; }
            th, td { border: 1px solid #333333; padding: 6px 8px; vertical-align: middle; }
            th { background-color: #2E7D32; color: #ffffff; font-weight: bold; text-align: center; }
            .header-title { font-size: 14pt; font-weight: bold; text-align: center; margin-bottom: 3px; }
            .header-sub { font-size: 11pt; text-align: center; margin-bottom: 5px; }
            .header-meta { font-size: 10pt; margin-top: 10px; margin-bottom: 10px; }
            .text-center { text-align: center; }
            .text-right { text-align: right; }
            .total-row { background-color: #f1f8e9; font-weight: bold; }
        </style>
    </head>
    <body>
        <div class="header-title">SMK NEGERI 2 PANGKALPINANG</div>
        <div class="header-title"><?= htmlspecialchars($judulLaporan); ?></div>
        <div class="header-sub"><?= htmlspecialchars($subJudulLaporan); ?></div>

        <table style="border: none; margin-bottom: 10px;">
            <tr style="border: none;">
                <td style="border: none; width: 120px;"><strong>Periode:</strong></td>
                <td style="border: none;"><?= htmlspecialchars($periodeLabel); ?></td>
                <td style="border: none; width: 120px;"><strong>Dicetak Pada:</strong></td>
                <td style="border: none;"><?= tglIndo(date('Y-m-d H:i:s'), true); ?></td>
            </tr>
            <tr style="border: none;">
                <td style="border: none;"><strong>Jurusan:</strong></td>
                <td style="border: none;"><?= htmlspecialchars($namaJurusanFilter); ?></td>
                <td style="border: none;"><strong>Petugas/Operator:</strong></td>
                <td style="border: none;"><?= htmlspecialchars($currentUser['nama_lengkap'] ?? $currentUser['nama_pengguna']); ?></td>
            </tr>
        </table>

        <table>
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <?php if ($jenis === 'barang_masuk'): ?>
                        <th>Nama Alat / Bahan</th>
                        <th>Barcode</th>
                        <th>Jurusan</th>
                        <th>Jumlah</th>
                        <th>Satuan</th>
                        <th>Petugas Penerima</th>
                        <th>Tanggal Masuk</th>
                        <th>Catatan / Keterangan</th>
                    <?php elseif ($jenis === 'barang_keluar'): ?>
                        <th>Nama Bahan</th>
                        <th>Barcode</th>
                        <th>Jurusan</th>
                        <th>Jumlah</th>
                        <th>Satuan</th>
                        <th>Penerima / Pemakai</th>
                        <th>Petugas Pengeluar</th>
                        <th>Tanggal Keluar</th>
                        <th>Catatan / Alasan</th>
                    <?php elseif ($jenis === 'peminjaman'): ?>
                        <th>Nama Alat</th>
                        <th>Barcode</th>
                        <th>Jurusan</th>
                        <th>Nama Peminjam / Siswa</th>
                        <th>Guru Pembimbing</th>
                        <th>Jumlah</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tenggat / Kembali</th>
                        <th>Status</th>
                        <th>Petugas</th>
                        <th>Keperluan / Tugas</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($dataRows)): ?>
                    <tr>
                        <td colspan="10" class="text-center" style="padding: 20px; color: #777;">Tidak ada data transaksi pada periode yang dipilih.</td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($dataRows as $row): ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?></td>
                            <?php if ($jenis === 'barang_masuk'): ?>
                                <td><?= htmlspecialchars($row['nama_barang']); ?></td>
                                <td class="text-center"><?= htmlspecialchars($row['barcode'] ?? '-'); ?></td>
                                <td><?= htmlspecialchars($row['nama_jurusan'] ?? 'Umum'); ?></td>
                                <td class="text-right"><?= number_format((int)$row['jumlah']); ?></td>
                                <td class="text-center"><?= htmlspecialchars($row['satuan'] ?? 'Unit'); ?></td>
                                <td><?= htmlspecialchars($row['nama_petugas']); ?></td>
                                <td class="text-center"><?= tglIndo($row['tanggal_masuk'] ?? $row['created_at']); ?></td>
                                <td><?= htmlspecialchars($row['catatan'] ?? '-'); ?></td>
                            <?php elseif ($jenis === 'barang_keluar'): ?>
                                <td><?= htmlspecialchars($row['nama_barang']); ?></td>
                                <td class="text-center"><?= htmlspecialchars($row['barcode'] ?? '-'); ?></td>
                                <td><?= htmlspecialchars($row['nama_jurusan'] ?? 'Umum'); ?></td>
                                <td class="text-right"><?= number_format((int)$row['jumlah']); ?></td>
                                <td class="text-center"><?= htmlspecialchars($row['satuan'] ?? 'Unit'); ?></td>
                                <td><?= htmlspecialchars($row['nama_penerima'] ?? '-'); ?></td>
                                <td><?= htmlspecialchars($row['nama_petugas']); ?></td>
                                <td class="text-center"><?= tglIndo($row['tanggal_keluar'] ?? $row['created_at']); ?></td>
                                <td><?= htmlspecialchars($row['catatan'] ?? '-'); ?></td>
                            <?php elseif ($jenis === 'peminjaman'): ?>
                                <td><?= htmlspecialchars($row['nama_barang']); ?></td>
                                <td class="text-center"><?= htmlspecialchars($row['barcode'] ?? '-'); ?></td>
                                <td><?= htmlspecialchars($row['nama_jurusan'] ?? 'Umum'); ?></td>
                                <td><?= htmlspecialchars($row['nama_peminjam'] ?? '-'); ?></td>
                                <td><?= htmlspecialchars($row['guru_peminjam'] ?? '-'); ?></td>
                                <td class="text-right"><?= number_format((int)$row['jumlah']); ?> <?= htmlspecialchars($row['satuan'] ?? 'Unit'); ?></td>
                                <td class="text-center"><?= tglIndo($row['tanggal_pinjam'] ?? $row['created_at']); ?></td>
                                <td class="text-center"><?= !empty($row['tanggal_kembali']) ? tglIndo($row['tanggal_kembali']) : '-'; ?></td>
                                <td class="text-center"><?= ucfirst(htmlspecialchars($row['status'])); ?></td>
                                <td><?= htmlspecialchars($row['nama_petugas']); ?></td>
                                <td>
                                    <?= htmlspecialchars($row['tugas'] ?? $row['catatan'] ?? '-'); ?>
                                    <?php if (!empty($row['tempat_pemakaian'])): ?>
                                        <br><small style="color: #64748b;">(Lokasi: <?= htmlspecialchars($row['tempat_pemakaian']); ?>)</small>
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="total-row">
                        <td colspan="4" class="text-center">TOTAL KESELURUHAN (<?= number_format($totalTransaksi); ?> TRANSAKSI)</td>
                        <td class="text-right"><?= number_format($totalJumlahBarang); ?></td>
                        <td colspan="<?= ($jenis === 'peminjaman') ? 6 : 4; ?>"></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </body>
    </html>
    <?php
    exit;
}

// =========================================================================
// MODE PRINT / CETAK HTML (KOP SURAT RESMI SEKOLAH & PDF READY)
// =========================================================================
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($judulLaporan); ?> - SMK Negeri 2 Pangkalpinang</title>
    <link rel="icon" type="image/svg+xml" href="assets/img/belmoti.svg">
    <style>
        @page {
            size: A4 landscape;
            margin: 12mm 15mm 15mm 15mm;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Times New Roman', Times, serif;
        }

        body {
            background-color: #f1f5f9;
            color: #111827;
            padding: 20px;
            font-size: 11pt;
            line-height: 1.4;
        }

        .no-print-bar {
            position: fixed;
            top: 15px;
            left: 50%;
            transform: translateX(-50%);
            background: #1e293b;
            color: #fff;
            padding: 10px 20px;
            border-radius: 50px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 9999;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            font-size: 13px;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all 0.15s ease-in-out;
            font-size: 13px;
        }

        .btn-print {
            background: #10b981;
            color: #ffffff;
        }
        .btn-print:hover {
            background: #059669;
        }

        .btn-excel {
            background: #0284c7;
            color: #ffffff;
        }
        .btn-excel:hover {
            background: #0369a1;
        }

        .btn-close {
            background: #475569;
            color: #ffffff;
        }
        .btn-close:hover {
            background: #334155;
        }

        /* Lembar Kertas Laporan */
        .paper {
            background: #ffffff;
            width: 100%;
            max-width: 297mm;
            margin: 45px auto 30px auto;
            padding: 20mm 20mm;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border-radius: 4px;
        }

        /* Kop Surat Resmi */
        .kop-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            border-bottom: 3px double #000000;
            padding-bottom: 12px;
            margin-bottom: 18px;
            text-align: center;
        }

        .kop-logo {
            width: 80px;
            height: 80px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .kop-text {
            flex-grow: 1;
        }

        .kop-text h4 {
            font-size: 12pt;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .kop-text h3 {
            font-size: 13pt;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .kop-text h2 {
            font-size: 16pt;
            font-weight: 900;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin: 2px 0;
            color: #000;
        }

        .kop-text p {
            font-size: 9.5pt;
            margin: 1px 0;
        }

        .kop-text .kontak {
            font-size: 9pt;
            font-style: italic;
        }

        /* Judul Laporan */
        .report-header {
            text-align: center;
            margin-bottom: 18px;
        }

        .report-header h1 {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            margin-bottom: 4px;
        }

        .report-header .sub {
            font-size: 10.5pt;
            color: #4b5563;
        }

        /* Meta Information Table */
        .meta-table {
            width: 100%;
            margin-bottom: 15px;
            font-size: 10pt;
            border-collapse: collapse;
        }

        .meta-table td {
            padding: 3px 0;
            vertical-align: top;
        }

        /* Data Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
            margin-bottom: 20px;
        }

        .data-table th, .data-table td {
            border: 1px solid #1f2937;
            padding: 6px 8px;
            vertical-align: middle;
        }

        .data-table th {
            background-color: #f3f4f6;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            font-size: 9pt;
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #fafafa;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }

        /* Summary Badges */
        .summary-box {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        .summary-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 11px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .summary-card strong {
            color: #0f172a;
            font-size: 13px;
        }

        /* Signature Section */
        .signature-section {
            margin-top: 30px;
            width: 100%;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
            font-size: 10.5pt;
        }

        .sign-col {
            width: 260px;
            text-align: center;
        }

        .sign-space {
            height: 70px;
        }

        .sign-name {
            font-weight: bold;
            text-decoration: underline;
        }

        .badge-status {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8.5pt;
            font-weight: bold;
            text-transform: capitalize;
            border: 1px solid #cbd5e1;
        }
        .badge-dipinjam { background: #fef3c7; color: #92400e; border-color: #fde68a; }
        .badge-dikembalikan { background: #dcfce7; color: #166534; border-color: #bbf7d0; }
        .badge-terlambat { background: #fee2e2; color: #991b1b; border-color: #fecaca; }

        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .paper {
                box-shadow: none;
                padding: 0;
                margin: 0;
                max-width: 100%;
            }
            .data-table th {
                background-color: #f3f4f6 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .badge-status {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

    <!-- Floating Action Toolbar (Hidden during print) -->
    <div class="no-print-bar no-print">
        <span style="font-weight: 500; opacity: 0.9;">Pratinjau Dokumen Cetak</span>
        <button type="button" onclick="window.print()" class="btn-action btn-print">
            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            <span>Cetak / Simpan PDF</span>
        </button>
        <a href="<?= htmlspecialchars($_SERVER['REQUEST_URI'] . (strpos($_SERVER['REQUEST_URI'], '?') !== false ? '&' : '?') . 'format=excel'); ?>" class="btn-action btn-excel">
            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span>Download Excel</span>
        </a>
        <button type="button" onclick="window.close()" class="btn-action btn-close">
            <span>Tutup</span>
        </button>
    </div>

    <div class="paper">
        <!-- KOP SURAT RESMI SEKOLAH -->
        <div class="kop-container">
            <img src="assets/img/belmoti.png" onerror="this.src='assets/img/belmoti.svg'" alt="Logo Sekolah" class="kop-logo">
            <div class="kop-text">
                <h4>PEMERINTAH PROVINSI KEPULAUAN BANGKA BELITUNG</h4>
                <h3>DINAS PENDIDIKAN</h3>
                <h2>SMK NEGERI 2 PANGKALPINANG</h2>
                <p>Jl. Sumedang, Kacang Pedang, Kec. Gerunggang, Kota Pangkal Pinang, Kepulauan Bangka Belitung 33125</p>
                <p class="kontak">Website: smkn2pangkalpinang.sch.id &bull; Pos-el: info@smkn2pangkalpinang.sch.id &bull; Telepon: (0717) 422235</p>
            </div>
        </div>

        <!-- JUDUL & SUBJUDUL -->
        <div class="report-header">
            <h1><?= htmlspecialchars($judulLaporan); ?></h1>
            <div class="sub"><?= htmlspecialchars($subJudulLaporan); ?></div>
        </div>

        <!-- META DATA INFORMASI LAPORAN -->
        <table class="meta-table">
            <tr>
                <td style="width: 140px; font-weight: bold;">Periode Laporan</td>
                <td style="width: 10px;">:</td>
                <td style="width: 45%;"><?= htmlspecialchars($periodeLabel); ?></td>
                <td style="width: 140px; font-weight: bold;">Tanggal Cetak</td>
                <td style="width: 10px;">:</td>
                <td><?= tglIndo(date('Y-m-d H:i:s'), true); ?></td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Jurusan / Unit Kerja</td>
                <td>:</td>
                <td><?= htmlspecialchars($namaJurusanFilter); ?></td>
                <td style="font-weight: bold;">Petugas Pencetak</td>
                <td>:</td>
                <td><?= htmlspecialchars($currentUser['nama_lengkap'] ?? $currentUser['nama_pengguna']); ?> (<?= ucfirst(htmlspecialchars($userPeran)); ?>)</td>
            </tr>
            <?php if ($jenis === 'peminjaman' && !empty($statusPinjam) && $statusPinjam !== 'semua'): ?>
            <tr>
                <td style="font-weight: bold;">Filter Status</td>
                <td>:</td>
                <td colspan="4"><span class="badge-status badge-<?= htmlspecialchars($statusPinjam); ?>"><?= ucfirst(htmlspecialchars($statusPinjam)); ?></span></td>
            </tr>
            <?php endif; ?>
        </table>

        <!-- RINGKASAN DATA (NO-PRINT OPTIONAL, REMAINS VISIBLE AS NICE SUMMARY) -->
        <div class="summary-box no-print">
            <div class="summary-card">
                <span>Total Catatan Transaksi:</span>
                <strong><?= number_format($totalTransaksi); ?> Data</strong>
            </div>
            <div class="summary-card">
                <span>Total Volume Barang:</span>
                <strong><?= number_format($totalJumlahBarang); ?> Unit / Pcs</strong>
            </div>
        </div>

        <!-- TABEL DATA LAPORAN -->
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 32px;">No</th>
                    <?php if ($jenis === 'barang_masuk'): ?>
                        <th>Nama Alat & Bahan</th>
                        <th style="width: 110px;">Barcode</th>
                        <th style="width: 120px;">Jurusan</th>
                        <th style="width: 70px;">Jumlah</th>
                        <th style="width: 65px;">Satuan</th>
                        <th style="width: 130px;">Petugas Penerima</th>
                        <th style="width: 100px;">Tgl Masuk</th>
                        <th>Keterangan / Catatan</th>
                    <?php elseif ($jenis === 'barang_keluar'): ?>
                        <th>Nama Bahan</th>
                        <th style="width: 110px;">Barcode</th>
                        <th style="width: 120px;">Jurusan</th>
                        <th style="width: 70px;">Jumlah</th>
                        <th style="width: 65px;">Satuan</th>
                        <th style="width: 130px;">Penerima / Pemakai</th>
                        <th style="width: 120px;">Petugas</th>
                        <th style="width: 100px;">Tgl Keluar</th>
                        <th>Keterangan / Keperluan</th>
                    <?php elseif ($jenis === 'peminjaman'): ?>
                        <th>Nama Alat Praktik</th>
                        <th style="width: 100px;">Barcode</th>
                        <th style="width: 100px;">Jurusan</th>
                        <th>Nama Peminjam</th>
                        <th style="width: 110px;">Guru Pengajar</th>
                        <th style="width: 65px;">Jumlah</th>
                        <th style="width: 95px;">Tgl Pinjam</th>
                        <th style="width: 95px;">Tgl Kembali</th>
                        <th style="width: 85px;">Status</th>
                        <th>Catatan / Tugas</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($dataRows)): ?>
                    <tr>
                        <td colspan="10" class="text-center" style="padding: 25px; color: #6b7280; font-style: italic;">
                            Tidak ada catatan data transaksi yang ditemukan pada rentang periode ini.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($dataRows as $row): ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?></td>
                            <?php if ($jenis === 'barang_masuk'): ?>
                                <td class="font-bold"><?= htmlspecialchars($row['nama_barang']); ?></td>
                                <td class="text-center" style="font-family: monospace;"><?= htmlspecialchars($row['barcode'] ?? '-'); ?></td>
                                <td><?= htmlspecialchars($row['nama_jurusan'] ?? 'Umum'); ?></td>
                                <td class="text-right font-bold"><?= number_format((int)$row['jumlah']); ?></td>
                                <td class="text-center"><?= htmlspecialchars($row['satuan'] ?? 'Unit'); ?></td>
                                <td><?= htmlspecialchars($row['nama_petugas']); ?></td>
                                <td class="text-center"><?= tglIndo($row['tanggal_masuk'] ?? $row['created_at']); ?></td>
                                <td><?= htmlspecialchars($row['catatan'] ?? '-'); ?></td>
                            <?php elseif ($jenis === 'barang_keluar'): ?>
                                <td class="font-bold"><?= htmlspecialchars($row['nama_barang']); ?></td>
                                <td class="text-center" style="font-family: monospace;"><?= htmlspecialchars($row['barcode'] ?? '-'); ?></td>
                                <td><?= htmlspecialchars($row['nama_jurusan'] ?? 'Umum'); ?></td>
                                <td class="text-right font-bold"><?= number_format((int)$row['jumlah']); ?></td>
                                <td class="text-center"><?= htmlspecialchars($row['satuan'] ?? 'Unit'); ?></td>
                                <td class="font-bold"><?= htmlspecialchars($row['nama_penerima'] ?? '-'); ?></td>
                                <td><?= htmlspecialchars($row['nama_petugas']); ?></td>
                                <td class="text-center"><?= tglIndo($row['tanggal_keluar'] ?? $row['created_at']); ?></td>
                                <td><?= htmlspecialchars($row['catatan'] ?? '-'); ?></td>
                            <?php elseif ($jenis === 'peminjaman'): ?>
                                <td class="font-bold"><?= htmlspecialchars($row['nama_barang']); ?></td>
                                <td class="text-center" style="font-family: monospace;"><?= htmlspecialchars($row['barcode'] ?? '-'); ?></td>
                                <td><?= htmlspecialchars($row['nama_jurusan'] ?? 'Umum'); ?></td>
                                <td class="font-bold"><?= htmlspecialchars($row['nama_peminjam'] ?? '-'); ?></td>
                                <td><?= htmlspecialchars($row['guru_peminjam'] ?? '-'); ?></td>
                                <td class="text-right font-bold"><?= number_format((int)$row['jumlah']); ?> <?= htmlspecialchars($row['satuan'] ?? 'Unit'); ?></td>
                                <td class="text-center"><?= tglIndo($row['tanggal_pinjam'] ?? $row['created_at']); ?></td>
                                <td class="text-center"><?= !empty($row['tanggal_kembali']) ? tglIndo($row['tanggal_kembali']) : '<span style="color:#9ca3af;">Belum</span>'; ?></td>
                                <td class="text-center">
                                    <span class="badge-status badge-<?= htmlspecialchars($row['status']); ?>">
                                        <?= ucfirst(htmlspecialchars($row['status'])); ?>
                                    </span>
                                </td>
                                <td>
                                    <?= htmlspecialchars($row['tugas'] ?? $row['catatan'] ?? '-'); ?>
                                    <?php if (!empty($row['tempat_pemakaian'])): ?>
                                        <div style="font-size: 8pt; color: #475569; margin-top: 3px;"><strong>Lokasi:</strong> <?= htmlspecialchars($row['tempat_pemakaian']); ?></div>
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                    <tr style="background-color: #f8fafc; font-weight: bold;">
                        <td colspan="4" class="text-center">TOTAL KESELURUHAN (<?= number_format($totalTransaksi); ?> TRANSAKSI)</td>
                        <td class="text-right"><?= number_format($totalJumlahBarang); ?></td>
                        <td colspan="<?= ($jenis === 'peminjaman') ? 6 : 4; ?>"></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- LEMBAR TANDA TANGAN / PENGESAHAN DOKUMEN -->
        <div class="signature-section">
            <div class="sign-col">
                <p>Mengetahui,</p>
                <p>Kepala Program Keahlian / Bengkel</p>
                <div class="sign-space"></div>
                <p class="sign-name">_____________________________</p>
                <p>NIP. .................................................</p>
            </div>
            <div class="sign-col">
                <p>Pangkalpinang, <?= tglIndo(date('Y-m-d')); ?></p>
                <p>Pengelola / Petugas Inventaris,</p>
                <div class="sign-space"></div>
                <p class="sign-name"><?= htmlspecialchars($currentUser['nama_lengkap'] ?? $currentUser['nama_pengguna'] ?? 'Petugas Gudang'); ?></p>
                <p>NIP/ID. <?= htmlspecialchars($currentUser['nama_pengguna'] ?? '-'); ?></p>
            </div>
        </div>
    </div>

    <script>
        // Otomatis membuka dialog print jika parameter print=1 diberikan
        <?php if (!empty($_GET['autoprint'])): ?>
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                window.print();
            }, 600);
        });
        <?php endif; ?>
    </script>
</body>
</html>
