<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel;
    }
    public function index() {
        $title = 'Login';

        // Check if the user is already logged in
        if (session()->get('isLoggedIn')) return redirect()->to(url_to('dashboard'));

        return view('auth/login', compact('title'));
    }

    public function login() {
        // Get the request data
        if(!$this->validate([
            'email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'Email is required',
                    'valid_email' => 'Email is not valid'
                ]
            ],
            'password' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Password is required'
                ]
            ]
        ])) return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());

        $data = $this->validator->getValidated();

        // Check if the user exists
        $user = $this->userModel->where('email', $data['email'])->first();
        if(!$user) return redirect()->back()->withInput()->with('errors', ['email' => 'No record found with this email']);
        // Check if the password is correct
        if(!password_verify($data['password'], $user['password'])) return redirect()->back()->withInput()->with('errors', ['password' => 'Password is incorrect']);

        // Set session
        $sessionData = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'isLoggedIn' => true
        ];
        session()->set($sessionData);
        session()->setFlashdata('success', 'Welcome back ' . $user['name']);
        return redirect()->to(url_to('dashboard'));
    }

    public function logout() {
        // Destroy the session
        session()->destroy();
        return redirect()->to(url_to('login'))->with('success', 'You have been logged out');
    }
}
