<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $title = 'Login';

        // Check if the user is already logged in
        if (session()->get('isLoggedIn')) {
            return redirect()->to(url_to('dashboard'));
        }

        return view('auth/login', compact('title'));
    }

    public function login()
    {
        // Rate limiting with sanitized IP
        $ipAddress = $this->request->getIPAddress();
        $safeIp = str_replace([':', '.'], '-', $ipAddress);
        
        $throttler = \Config\Services::throttler();
        if ($throttler->check('login-' . $safeIp, 5, MINUTE) === false) {
            return redirect()->back()
                ->withInput()
                ->with('errors', ['email' => 'Terlalu banyak percobaan. Silakan tunggu 1 menit.']);
        }

        // Get the request data
        if (!$this->validate([
            'email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'Email harus diisi',
                    'valid_email' => 'Email atau password salah'
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[8]',
                'errors' => [
                    'required' => 'Password harus diisi',
                    'min_length' => 'Email atau password salah'
                ]
            ]
        ])) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = $this->validator->getValidated();

        // Check if the user exists
        $user = $this->userModel->where('email', $data['email'])->first();
        if (!$user) {
            log_message('notice', 'Percobaan login gagal untuk email: '.$data['email'].' dari IP: '.$ipAddress);
            return redirect()->back()
                ->withInput()
                ->with('errors', ['email' => 'Email atau password salah']);
        }

        // Check if the password is correct
        if (!password_verify($data['password'], $user['password'])) {
            log_message('notice', 'Percobaan login gagal untuk user ID: '.$user['id'].' dari IP: '.$ipAddress);
            return redirect()->back()
                ->withInput()
                ->with('errors', ['password' => 'Email atau password salah']);
        }

        // Regenerate session ID to prevent session fixation
        session()->regenerate();

        // Set session
        $sessionData = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'level' => $user['level'],
            'organisasi_id' => $user['organisasi_id'] ?? null,
            'profile_photo' => $user['profile_photo'] ?? null,
            'isLoggedIn' => true,
            'last_login' => time(),
            'ip_address' => $ipAddress
        ];
        
        session()->set($sessionData);
        session()->setFlashdata('success', 'Selamat Datang ' . $user['name']);
        
        log_message('info', 'User ID: '.$user['id'].' berhasil login dari IP: '.$ipAddress);
        
        return redirect()->to(url_to('dashboard'));
    }

    public function logout()
    {
        if (session()->has('id')) {
            log_message('info', 'User ID: '.session()->get('id').' logout');
        }
        
        // Destroy the session
        session()->destroy();
        return redirect()->to(url_to('login'))
            ->with('success', 'Anda telah logout');
    }
}