<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Settings extends BaseController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(url_to('login'));
        }

        $userId = session()->get('id');
        $user = $this->userModel->find($userId);

        if (!$user) {
            session()->setFlashdata('error', 'User tidak ditemukan.');
            return redirect()->to(url_to('dashboard'));
        }

        $title = 'Pengaturan';
        return view('page/settings/index', compact('title', 'user'));
    }

    public function updateName()
    {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(url_to('login'));
        }

        $userId = session()->get('id');
        $user = $this->userModel->find($userId);

        if (!$user) {
            session()->setFlashdata('error', 'User tidak ditemukan.');
            return redirect()->to(url_to('settings.index'));
        }

        // Validasi
        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => [
                'rules' => 'required|min_length[3]|max_length[255]',
                'errors' => [
                    'required' => 'Nama harus diisi',
                    'min_length' => 'Nama minimal 3 karakter',
                    'max_length' => 'Nama maksimal 255 karakter',
                ]
            ]
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $name = $this->request->getPost('name');

        // Update database
        $this->userModel->update($userId, ['name' => $name]);

        // Update session
        $sessionData = session()->get();
        $sessionData['name'] = $name;
        session()->set($sessionData);

        session()->setFlashdata('success', 'Nama berhasil diubah.');
        return redirect()->to(url_to('settings.index'));
    }

    public function updateEmail()
    {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(url_to('login'));
        }

        $userId = session()->get('id');
        $user = $this->userModel->find($userId);

        if (!$user) {
            session()->setFlashdata('error', 'User tidak ditemukan.');
            return redirect()->to(url_to('settings.index'));
        }

        // Validasi
        $validation = \Config\Services::validation();
        $validation->setRules([
            'email' => [
                'rules' => 'required|valid_email|is_unique[users.email,id,' . $userId . ']',
                'errors' => [
                    'required' => 'Email harus diisi',
                    'valid_email' => 'Format email tidak valid',
                    'is_unique' => 'Email sudah digunakan oleh user lain',
                ]
            ]
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $newEmail = $this->request->getPost('email');

        // Jika email sama dengan yang lama, tidak perlu verifikasi
        if ($newEmail === $user['email']) {
            session()->setFlashdata('info', 'Email tidak berubah.');
            return redirect()->to(url_to('settings.index'));
        }

        // Generate token verifikasi
        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', time() + 3600); // 1 jam

        // Simpan token ke database (sementara simpan di kolom password dengan prefix khusus)
        // Atau bisa buat tabel terpisah untuk email_verifications
        // Untuk sementara, kita simpan di session dan kirim email
        $verificationData = [
            'user_id' => $userId,
            'new_email' => $newEmail,
            'token' => $token,
            'expires_at' => $expiresAt
        ];

        // Simpan data verifikasi di cache agar bisa diakses dari perangkat/ sesi lain
        $cacheKey = $this->getEmailVerificationCacheKey($token);
        cache()->save($cacheKey, $verificationData, 3600);

        // Kirim email aktivasi
        $this->sendEmailVerification($user['name'], $newEmail, $token);

        session()->setFlashdata('success', 'Email verifikasi telah dikirim ke ' . $newEmail . '. Silakan cek email Anda untuk mengaktifkan email baru.');
        return redirect()->to(url_to('settings.index'));
    }

    public function verifyEmail($token)
    {
        $cacheKey = $this->getEmailVerificationCacheKey($token);
        $verificationData = cache($cacheKey);

        if (!$verificationData || ($verificationData['token'] ?? null) !== $token) {
            session()->setFlashdata('error', 'Token verifikasi tidak valid atau sudah kadaluarsa.');
            return redirect()->to(url_to('login'));
        }

        // Cek apakah token masih valid (belum expired)
        if (strtotime($verificationData['expires_at']) < time()) {
            cache()->delete($cacheKey);
            session()->setFlashdata('error', 'Token verifikasi sudah kadaluarsa. Silakan request ulang.');
            return redirect()->to(url_to('login'));
        }

        $userId = $verificationData['user_id'];
        $newEmail = $verificationData['new_email'];

        // Update email di database
        $this->userModel->update($userId, ['email' => $newEmail]);

        // Hapus data verifikasi dari cache
        cache()->delete($cacheKey);

        // Reset sesi login agar user diarahkan kembali ke halaman login
        $this->clearAuthSession();

        session()->setFlashdata([
            'success' => 'Email berhasil diverifikasi. Silakan login menggunakan email baru Anda.',
            'login_prefill_email' => $newEmail
        ]);

        return redirect()->to(url_to('login'));
    }

    public function updatePassword()
    {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(url_to('login'));
        }

        $userId = session()->get('id');
        $user = $this->userModel->find($userId);

        if (!$user) {
            session()->setFlashdata('error', 'User tidak ditemukan.');
            return redirect()->to(url_to('settings.index'));
        }

        // Validasi
        $validation = \Config\Services::validation();
        $validation->setRules([
            'old_password' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Password lama harus diisi',
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[8]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/]',
                'errors' => [
                    'required' => 'Password baru harus diisi',
                    'min_length' => 'Password minimal 8 karakter',
                    'regex_match' => 'Password harus mengandung minimal: 1 huruf kecil, 1 huruf besar, 1 angka, dan 1 karakter khusus (@$!%*?&)',
                ]
            ],
            'password_confirm' => [
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => 'Konfirmasi password harus diisi',
                    'matches' => 'Konfirmasi password tidak cocok dengan password',
                ]
            ]
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Verifikasi password lama
        $oldPassword = $this->request->getPost('old_password');
        if (!password_verify($oldPassword, $user['password'])) {
            session()->setFlashdata('error', 'Password lama tidak benar.');
            return redirect()->back()->withInput();
        }

        // Cek apakah password baru sama dengan password lama
        $newPassword = $this->request->getPost('password');
        if (password_verify($newPassword, $user['password'])) {
            session()->setFlashdata('error', 'Password baru harus berbeda dengan password lama.');
            return redirect()->back()->withInput();
        }

        // Generate token verifikasi
        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', time() + 3600); // 1 jam

        // Hash password baru untuk disimpan sementara
        $hashedNewPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        // Simpan data perubahan password di session
        $passwordChangeData = [
            'user_id' => $userId,
            'new_password_hash' => $hashedNewPassword,
            'token' => $token,
            'expires_at' => $expiresAt
        ];

        session()->set('password_change', $passwordChangeData);

        // Kirim email verifikasi perubahan password
        $this->sendPasswordChangeVerification($user['name'], $user['email'], $token);

        session()->setFlashdata('success', 'Email verifikasi perubahan password telah dikirim ke ' . $user['email'] . '. Silakan cek email Anda untuk menyelesaikan proses perubahan password.');
        return redirect()->to(url_to('settings.index'));
    }

    public function verifyPasswordChange($token)
    {
        $passwordChangeData = session()->get('password_change');

        if (!$passwordChangeData || $passwordChangeData['token'] !== $token) {
            session()->setFlashdata('error', 'Token verifikasi tidak valid atau sudah kadaluarsa.');
            return redirect()->to(url_to('settings.index'));
        }

        // Cek apakah token masih valid (belum expired)
        if (strtotime($passwordChangeData['expires_at']) < time()) {
            session()->remove('password_change');
            session()->setFlashdata('error', 'Token verifikasi sudah kadaluarsa. Silakan request ulang perubahan password.');
            return redirect()->to(url_to('settings.index'));
        }

        $userId = $passwordChangeData['user_id'];
        $hashedNewPassword = $passwordChangeData['new_password_hash'];

        // Update password di database
        $this->userModel->update($userId, ['password' => $hashedNewPassword]);

        // Hapus data perubahan password dari session
        session()->remove('password_change');

        $title = 'Password Berhasil Diubah';
        return view('page/settings/verify-password', compact('title'));
    }

    private function sendPasswordChangeVerification($name, $email, $token)
    {
        $emailService = \Config\Services::email();
        $emailService->clear();

        $emailConfig = config('Email');
        $fromEmail = $emailConfig->SMTPUser ?? 'ormaonehorizonu@ormaone.com';
        $fromName = $emailConfig->fromName ?? 'OrmaOne';

        $verificationUrl = base_url('settings/verify-password/' . $token);

        $emailService->setFrom($fromEmail, $fromName);
        $emailService->setTo($email);
        $emailService->setSubject('Verifikasi Perubahan Password - OrmaOne');

        $message = $this->generatePasswordChangeTemplate($name, $verificationUrl);
        $emailService->setMessage($message);
        $emailService->setMailType('html');

        return $emailService->send(false);
    }

    private function sendEmailVerification($name, $email, $token)
    {
        $emailService = \Config\Services::email();
        $emailService->clear();

        $emailConfig = config('Email');
        $fromEmail = $emailConfig->SMTPUser ?? 'ormaonehorizonu@ormaone.com';
        $fromName = $emailConfig->fromName ?? 'OrmaOne';

        $verificationUrl = base_url('settings/verify-email/' . $token);

        $emailService->setFrom($fromEmail, $fromName);
        $emailService->setTo($email);
        $emailService->setSubject('Verifikasi Perubahan Email - OrmaOne');

        $message = $this->generateEmailVerificationTemplate($name, $verificationUrl);
        $emailService->setMessage($message);
        $emailService->setMailType('html');

        return $emailService->send(false);
    }

    private function generateEmailVerificationTemplate($name, $verificationUrl)
    {
        $baseUrl = base_url();
        $logoUrl = $baseUrl . 'dist/landing/assets/img/logo1.png';

        return '
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Perubahan Email</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f5f5f5;
            line-height: 1.6;
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .email-header {
            padding: 40px 20px 20px;
            text-align: center;
            background-color: #ffffff;
        }
        .logo-container {
            margin-bottom: 20px;
        }
        .logo-container img {
            width: 60px;
            height: 60px;
            object-fit: contain;
            margin-bottom: 10px;
        }
        .brand-name {
            font-size: 28px;
            font-weight: bold;
            color: #333333;
            margin: 0;
            letter-spacing: -0.5px;
        }
        .email-body {
            padding: 30px 40px;
            color: #333333;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
            color: #666666;
        }
        .main-heading {
            font-size: 24px;
            font-weight: bold;
            color: #333333;
            margin-bottom: 20px;
            line-height: 1.4;
        }
        .instruction-text {
            font-size: 15px;
            color: #666666;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .verification-button {
            display: inline-block;
            background-color: #980517;
            color: #ffffff !important;
            padding: 16px 40px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 1px;
            text-align: center;
            min-width: 200px;
        }
        .verification-button:hover {
            background-color: #7a0412;
        }
        .link-text {
            font-size: 13px;
            color: #666666;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        .verification-link {
            word-break: break-all;
            color: #980517;
            text-decoration: none;
            font-size: 12px;
        }
        .expiry-notice {
            font-size: 13px;
            color: #999999;
            text-align: center;
            margin-top: 20px;
            margin-bottom: 30px;
        }
        .fallback-section {
            margin-top: 30px;
            padding-top: 30px;
            border-top: 1px solid #eeeeee;
            font-size: 13px;
            color: #666666;
        }
        .fallback-text {
            margin-bottom: 10px;
        }
        .email-footer {
            padding: 20px 40px;
            text-align: center;
            background-color: #f9f9f9;
            border-top: 1px solid #eeeeee;
        }
        .copyright {
            font-size: 12px;
            color: #999999;
            margin: 0;
        }
        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 20px;
                border-radius: 4px;
            }
            .email-body {
                padding: 20px;
            }
            .email-footer {
                padding: 15px 20px;
            }
            .main-heading {
                font-size: 20px;
            }
            .verification-button {
                padding: 14px 30px;
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <div class="logo-container">
                <img src="' . htmlspecialchars($logoUrl, ENT_QUOTES, 'UTF-8') . '" alt="OrmaOne Logo">
            </div>
            <h1 class="brand-name">OrmaOne</h1>
        </div>
        
        <div class="email-body">
            <p class="greeting">Halo <strong>' . htmlspecialchars($name) . '</strong>,</p>
            
            <h2 class="main-heading">Verifikasi Perubahan Email</h2>
            
            <p class="instruction-text">
                Anda telah meminta untuk mengubah email Anda. Untuk menyelesaikan proses ini, 
                silakan klik tombol di bawah ini:
            </p>
            
            <div class="button-container">
                <a href="' . htmlspecialchars($verificationUrl, ENT_QUOTES, 'UTF-8') . '" class="verification-button" style="background-color: #980517; color: #ffffff !important; padding: 18px 50px; border-radius: 8px; text-decoration: none; display: inline-block; font-size: 18px; font-weight: bold; min-width: 250px; text-align: center;">
                    Verifikasi Email
                </a>
            </div>
            
            <p class="link-text">Atau salin dan buka link berikut di browser Anda:</p>
            <p style="word-break: break-all; text-align: center;">
                <a href="' . htmlspecialchars($verificationUrl, ENT_QUOTES, 'UTF-8') . '" class="verification-link">' . htmlspecialchars($verificationUrl, ENT_QUOTES, 'UTF-8') . '</a>
            </p>
            
            <p class="expiry-notice">
                Link ini akan kadaluarsa dalam 1 jam. Jangan bagikan link ini kepada siapa pun.
            </p>
            
            <div class="fallback-section">
                <p class="fallback-text">
                    <strong>Penting:</strong> Jika Anda tidak meminta perubahan email ini, abaikan email ini.
                </p>
            </div>
        </div>
        
        <div class="email-footer">
            <p class="copyright">' . date('Y') . ' © OrmaOne E-Voting System. All Rights Reserved.</p>
        </div>
    </div>
</body>
</html>';
    }

    private function generatePasswordChangeTemplate($name, $verificationUrl)
    {
        $baseUrl = base_url();
        $logoUrl = $baseUrl . 'dist/landing/assets/img/logo1.png';

        return '
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Perubahan Password</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f5f5f5;
            line-height: 1.6;
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .email-header {
            padding: 40px 20px 20px;
            text-align: center;
            background-color: #ffffff;
        }
        .logo-container {
            margin-bottom: 20px;
        }
        .logo-container img {
            width: 60px;
            height: 60px;
            object-fit: contain;
            margin-bottom: 10px;
        }
        .brand-name {
            font-size: 28px;
            font-weight: bold;
            color: #333333;
            margin: 0;
            letter-spacing: -0.5px;
        }
        .email-body {
            padding: 30px 40px;
            color: #333333;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
            color: #666666;
        }
        .main-heading {
            font-size: 24px;
            font-weight: bold;
            color: #333333;
            margin-bottom: 20px;
            line-height: 1.4;
        }
        .instruction-text {
            font-size: 15px;
            color: #666666;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .verification-button {
            display: inline-block;
            background-color: #980517;
            color: #ffffff !important;
            padding: 16px 40px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 1px;
            text-align: center;
            min-width: 200px;
        }
        .verification-button:hover {
            background-color: #7a0412;
        }
        .link-text {
            font-size: 13px;
            color: #666666;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        .verification-link {
            word-break: break-all;
            color: #980517;
            text-decoration: none;
            font-size: 12px;
        }
        .expiry-notice {
            font-size: 13px;
            color: #999999;
            text-align: center;
            margin-top: 20px;
            margin-bottom: 30px;
        }
        .fallback-section {
            margin-top: 30px;
            padding-top: 30px;
            border-top: 1px solid #eeeeee;
            font-size: 13px;
            color: #666666;
        }
        .fallback-text {
            margin-bottom: 10px;
        }
        .email-footer {
            padding: 20px 40px;
            text-align: center;
            background-color: #f9f9f9;
            border-top: 1px solid #eeeeee;
        }
        .copyright {
            font-size: 12px;
            color: #999999;
            margin: 0;
        }
        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 20px;
                border-radius: 4px;
            }
            .email-body {
                padding: 20px;
            }
            .email-footer {
                padding: 15px 20px;
            }
            .main-heading {
                font-size: 20px;
            }
            .verification-button {
                padding: 14px 30px;
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <div class="logo-container">
                <img src="' . htmlspecialchars($logoUrl, ENT_QUOTES, 'UTF-8') . '" alt="OrmaOne Logo">
            </div>
            <h1 class="brand-name">OrmaOne</h1>
        </div>
        
        <div class="email-body">
            <p class="greeting">Halo <strong>' . htmlspecialchars($name) . '</strong>,</p>
            
            <h2 class="main-heading">Verifikasi Perubahan Password</h2>
            
            <p class="instruction-text">
                Anda telah meminta untuk mengubah password Anda. Untuk menyelesaikan proses ini, 
                silakan klik tombol di bawah ini:
            </p>
            
            <div class="button-container">
                <a href="' . htmlspecialchars($verificationUrl, ENT_QUOTES, 'UTF-8') . '" class="verification-button" style="background-color: #980517; color: #ffffff !important; padding: 18px 50px; border-radius: 8px; text-decoration: none; display: inline-block; font-size: 18px; font-weight: bold; min-width: 250px; text-align: center;">
                    Verifikasi Perubahan Password
                </a>
            </div>
            
            <p class="link-text">Atau salin dan buka link berikut di browser Anda:</p>
            <p style="word-break: break-all; text-align: center;">
                <a href="' . htmlspecialchars($verificationUrl, ENT_QUOTES, 'UTF-8') . '" class="verification-link">' . htmlspecialchars($verificationUrl, ENT_QUOTES, 'UTF-8') . '</a>
            </p>
            
            <p class="expiry-notice">
                Link ini akan kadaluarsa dalam 1 jam. Jangan bagikan link ini kepada siapa pun.
            </p>
            
            <div class="fallback-section">
                <p class="fallback-text">
                    <strong>Penting:</strong> Jika Anda tidak meminta perubahan password ini, abaikan email ini dan pastikan akun Anda aman.
                </p>
            </div>
        </div>
        
        <div class="email-footer">
            <p class="copyright">' . date('Y') . ' © OrmaOne E-Voting System. All Rights Reserved.</p>
        </div>
    </div>
</body>
</html>';
    }

    /**
     * Menghapus data sesi autentikasi saat ini.
     *
     * Hal ini memastikan pengguna melakukan login ulang setelah proses
     * verifikasi email selesai agar data sesi menggunakan email terbaru.
     */
    private function clearAuthSession(): void
    {
        $authKeys = [
            'isLoggedIn',
            'id',
            'name',
            'email',
            'level',
            'profile_photo',
            'last_login',
            'ip_address',
        ];

        foreach ($authKeys as $key) {
            if (session()->has($key)) {
                session()->remove($key);
            }
        }
    }

    private function getEmailVerificationCacheKey(string $token): string
    {
        return 'email_verification_' . $token;
    }
}

