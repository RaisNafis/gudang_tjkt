<?php
// app/helpers/auth.php

date_default_timezone_set('Asia/Jakarta');

// 6. Security Header: Sembunyikan X-Powered-By
header_remove('X-Powered-By');
@ini_set('expose_php', '0');

// 4. Konfigurasi Sesi Aman (Cookie Security: HttpOnly & SameSite Lax & Strict Mode)
if (session_status() === PHP_SESSION_NONE) {
    @ini_set('session.use_strict_mode', '1');
    @ini_set('session.use_only_cookies', '1');
    $isSecure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'domain'   => '',
        'secure'   => $isSecure,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

/**
 * 3. CSRF Protection Helpers
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }

    // Safety check: Pastikan user yang sedang login masih ada di database
    $user = currentUser();
    if (!$user) {
        logoutUser();
        setFlash('error', 'Akun Anda tidak ditemukan atau telah dihapus.');
        header('Location: login.php');
        exit;
    }
}

function getCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCsrfToken($token) {
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Cek apakah pengguna sudah login
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Dapatkan data pengguna yang sedang login
 */
function currentUser($refresh = false) {
    static $cachedUser = null;
    if (!$refresh && $cachedUser !== null) {
        return $cachedUser;
    }
    if (!empty($_SESSION['user_id'])) {
        require_once __DIR__ . '/../config/database.php';
        $db = Database::getInstance()->getConnection();
        if ($db) {
            $stmt = $db->prepare("
                SELECT p.id, p.jurusan_id, p.nama_pengguna, p.nama_lengkap, p.email, p.peran, p.nomor_telepon, p.foto_url,
                       p.token, p.kata_sandi_hash,
                       j.nama_jurusan, j.kode_jurusan, j.warna_tema
                FROM pengguna p
                LEFT JOIN jurusan j ON p.jurusan_id = j.id
                WHERE p.id = :id LIMIT 1
            ");
            $stmt->execute([':id' => $_SESSION['user_id']]);
            $u = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($u) {
                // Cek apakah kata sandi saat ini masih berupa token bawaan
                $isPasswordToken = false;
                if (!empty($u['token']) && !empty($u['kata_sandi_hash'])) {
                    $isPasswordToken = password_verify($u['token'], $u['kata_sandi_hash']) || password_verify(strtoupper($u['token']), $u['kata_sandi_hash']);
                }
                $u['is_password_token'] = $isPasswordToken;
                unset($u['kata_sandi_hash']);

                // Normalisasi peran backward-compatibility
                if ($u['peran'] === 'admin_jurusan') $u['peran'] = 'kabeng';
                if ($u['peran'] === 'petugas') $u['peran'] = 'guru_jurusan';

                // Cek apakah ada switch jurusan aktif di sesi
                if (!empty($_SESSION['active_jurusan_id'])) {
                    $targetJurusanId = $_SESSION['active_jurusan_id'];
                    $hasAccess = false;
                    if ($u['peran'] === 'admin_sekolah') {
                        $hasAccess = true;
                    } elseif ($u['jurusan_id'] === $targetJurusanId) {
                        $hasAccess = true;
                    } else {
                        $stmtCheck = $db->prepare("SELECT 1 FROM pengguna_multi_jurusan WHERE pengguna_id = :uid AND jurusan_id = :jid LIMIT 1");
                        $stmtCheck->execute([':uid' => $u['id'], ':jid' => $targetJurusanId]);
                        $hasAccess = (bool) $stmtCheck->fetchColumn();
                    }

                    if ($hasAccess) {
                        $stmtJ = $db->prepare("SELECT id, nama_jurusan, deskripsi, warna_tema FROM jurusan WHERE id = :jid LIMIT 1");
                        $stmtJ->execute([':jid' => $targetJurusanId]);
                        $activeJ = $stmtJ->fetch(PDO::FETCH_ASSOC);
                        if ($activeJ) {
                            $u['jurusan_id'] = $activeJ['id'];
                            $u['nama_jurusan'] = $activeJ['nama_jurusan'];
                            $u['warna_tema'] = $activeJ['warna_tema'];
                        }
                    } else {
                        unset($_SESSION['active_jurusan_id']);
                    }
                }

                $_SESSION['user'] = $u;
                $cachedUser = $u;
                return $cachedUser;
            }
        }
    }
    $cachedUser = $_SESSION['user'] ?? null;
    return $cachedUser;
}

/**
 * Kompresi dan simpan file gambar dengan ekstensi .dat
 */
function compressAndSaveImageAsDat($sourcePath, $destinationPath, $quality = 70) {
    $dir = dirname($destinationPath);
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }

    $info = @getimagesize($sourcePath);
    if (!$info) {
        return move_uploaded_file($sourcePath, $destinationPath);
    }

    $mime = $info['mime'];
    $origW = $info[0];
    $origH = $info[1];
    $image = null;

    if (function_exists('imagecreatefromjpeg') && ($mime === 'image/jpeg' || $mime === 'image/jpg')) {
        $image = @imagecreatefromjpeg($sourcePath);
    } elseif (function_exists('imagecreatefrompng') && $mime === 'image/png') {
        $image = @imagecreatefrompng($sourcePath);
    } elseif (function_exists('imagecreatefromwebp') && $mime === 'image/webp') {
        $image = @imagecreatefromwebp($sourcePath);
    }

    if ($image) {
        // Auto-scale large images down to max width 1200px for maximum performance & low storage usage
        $maxDimension = 1200;
        if ($origW > $maxDimension || $origH > $maxDimension) {
            if ($origW >= $origH) {
                $newW = $maxDimension;
                $newH = (int)round(($origH / $origW) * $maxDimension);
            } else {
                $newH = $maxDimension;
                $newW = (int)round(($origW / $origH) * $maxDimension);
            }
            $resized = imagecreatetruecolor($newW, $newH);
            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newW, $newH, $origW, $origH);
            imagedestroy($image);
            $image = $resized;
        }

        ob_start();
        if (function_exists('imagewebp')) {
            imagewebp($image, null, $quality);
        } else {
            imagejpeg($image, null, $quality);
        }
        $compressedData = ob_get_clean();
        imagedestroy($image);
        return file_put_contents($destinationPath, $compressedData) !== false;
    } else {
        return move_uploaded_file($sourcePath, $destinationPath);
    }
}

/**
 * Atur pesan flash alert
 */
function setFlash($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Ambil dan hapus pesan flash alert
 */
function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Hapus seluruh sesi (Logout)
 */
function logoutUser() {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
}

/**
 * Generasi UUID v4 unik untuk primary key
 */
function generateUuid() {
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

/**
 * Konversi warna tema jurusan (preset nama atau kode hex) menjadi kode hex CSS valid
 */
function resolveJurusanThemeColor($colorVal) {
    if (empty($colorVal)) return '#2E7D32';
    $presets = [
        'kuning' => '#EAB308',
        'orange' => '#EA580C',
        'hijau'  => '#2E7D32',
        'merah'  => '#DC2626',
        'biru'   => '#2563EB',
        'ungu'   => '#7C3AED',
        'pink'   => '#E11D48',
        'cyan'   => '#0891B2',
        'violet' => '#8B5CF6',
        'coklat' => '#78350F',
        'abu'    => '#64748B'
    ];
    $c = strtolower(trim($colorVal));
    if (isset($presets[$c])) {
        return $presets[$c];
    }
    if (str_starts_with($c, '#')) {
        return $colorVal;
    }
    if (preg_match('/^[0-9a-fA-F]{3,8}$/', $c)) {
        return '#' . $colorVal;
    }
    return $colorVal;
}

