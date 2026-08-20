<?php
// app/controllers/ProfileController.php

require_once __DIR__ . '/../models/Pengguna.php';
require_once __DIR__ . '/../helpers/auth.php';

class ProfileController {
    private $penggunaModel;

    public function __construct() {
        $this->penggunaModel = new Pengguna();
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: dashboard.php');
            exit;
        }

        // 3. Validasi CSRF Token
        $token = $_POST['csrf_token'] ?? '';
        if (!validateCsrfToken($token)) {
            setFlash('error', 'Permintaan tidak sah (CSRF token tidak valid).');
            header('Location: dashboard.php');
            exit;
        }

        // 8. Strict Session Access (Hardcoded UUID fallback REMOVED)
        $user = currentUser();
        if (!$user || empty($user['id'])) {
            setFlash('error', 'Sesi tidak valid. Silakan masuk kembali.');
            header('Location: login.php');
            exit;
        }

        $userId = $user['id'];
        $nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
        $email_prefix = trim($_POST['email_prefix'] ?? '');
        $email_domain = trim($_POST['email_domain'] ?? '');

        if (!empty($email_prefix)) {
            $domain = !empty($email_domain) ? $email_domain : 'smk2pangkalpinang.sch.id';
            $email = $email_prefix . '@' . $domain;
        } else {
            $email = trim($_POST['email'] ?? '');
        }

        $nomor_telepon = trim($_POST['nomor_telepon'] ?? '');
        $password_lama = $_POST['password_lama'] ?? '';
        $password_baru = $_POST['password_baru'] ?? '';

        if (empty($nama_lengkap)) {
            setFlash('error', 'Nama lengkap tidak boleh kosong.');
            header('Location: dashboard.php?tab=pengaturan-profil');
            exit;
        }

        // Verifikasi kata sandi lama jika ingin mengubah kata sandi
        if (!empty($password_baru)) {
            if (empty($password_lama)) {
                setFlash('error', 'Masukkan kata sandi lama untuk mengonfirmasi perubahan kata sandi.');
                header('Location: dashboard.php?tab=pengaturan-profil');
                exit;
            }

            $currentUserData = $this->penggunaModel->findById($userId);
            if (!$currentUserData || !password_verify($password_lama, $currentUserData['kata_sandi_hash'])) {
                setFlash('error', 'Kata sandi lama tidak sesuai.');
                header('Location: dashboard.php?tab=pengaturan-profil');
                exit;
            }
        }

        // Processing Profile Photo Upload
        $fotoUrl = null;
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed)) {
                setFlash('error', 'Format foto profil harus JPG, JPEG, PNG, atau WEBP!');
                header('Location: dashboard.php?tab=pengaturan-profil');
                exit;
            }
            if ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
                setFlash('error', 'Ukuran foto profil maksimal 2MB!');
                header('Location: dashboard.php?tab=pengaturan-profil');
                exit;
            }
            $uploadDir = __DIR__ . '/../../uploads/avatars/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $fileName = 'avatar_' . bin2hex(random_bytes(8)) . '.dat';
            if (compressAndSaveImageAsDat($_FILES['foto']['tmp_name'], $uploadDir . $fileName, 70)) {
                $fotoUrl = 'uploads/avatars/' . $fileName;
            }
        }

        $updateParams = [
            'nama_lengkap'  => $nama_lengkap,
            'email'         => $email,
            'nomor_telepon' => $nomor_telepon,
            'password_baru' => $password_baru
        ];
        if ($fotoUrl) {
            $updateParams['foto_url'] = $fotoUrl;
        }

        $success = $this->penggunaModel->updateProfile($userId, $updateParams);

        if ($success) {
            $_SESSION['user']['nama_lengkap'] = $nama_lengkap;
            $_SESSION['user']['email'] = $email;
            $_SESSION['user']['nomor_telepon'] = $nomor_telepon;
            if ($fotoUrl) {
                $_SESSION['user']['foto_url'] = $fotoUrl;
            }
            setFlash('success', 'Profil Anda berhasil diperbarui!');
        } else {
            setFlash('error', 'Gagal memperbarui profil.');
        }

        header('Location: dashboard.php?tab=pengaturan-profil');
        exit;
    }
}
