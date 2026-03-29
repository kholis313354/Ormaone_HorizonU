<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class User extends BaseController
{
    private $userModel;
    private $pesanModel;
    private $organisasiModel;

    public function __construct() {
        $this->userModel = new UserModel;
        $this->pesanModel = new \App\Models\PesanModel();
        $this->organisasiModel = new \App\Models\OrganisasiModel();
    }

    public function index() {
         // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        
        // Check if user is Admin (level 2 only)
        $level = session()->get('level');
        if ($level != 2) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        $title = 'User';
        // Join dengan organisasi untuk mendapatkan nama organisasi
        $data = $this->userModel->select('users.*, organisasis.name as organisasi_name')
            ->join('organisasis', 'organisasis.id = users.organisasi_id', 'left')
            ->findAll();

        // Get unread count for notification badge (only for level 0 and 2)
        $unreadCount = 0;
        $level = session()->get('level');
        if (in_array($level, [0, 2])) {
            $unreadCount = $this->pesanModel->getUnreadCount();
        }

        return view('page/user/index', compact('title', 'data', 'unreadCount'));
    }

    public function create() {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        
        // Check if user is Admin (level 2 only)
        $level = session()->get('level');
        if ($level != 2) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        $title = 'Create User';
        
        // Get all organisasi for dropdown
        $organisasis = $this->organisasiModel->findAll();

        // Get unread count for notification badge (only for level 0 and 2)
        $unreadCount = 0;
        $level = session()->get('level');
        if (in_array($level, [0, 2])) {
            $unreadCount = $this->pesanModel->getUnreadCount();
        }

        return view('page/user/create', compact('title', 'unreadCount', 'organisasis'));
    }

    public function store() {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        
        // Check if user is Admin (level 2 only)
        $level = session()->get('level');
        if ($level != 2) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        $data = $this->request->getPost();
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $this->userModel->insert($data);

        return redirect()->to(url_to('user.index'))->with('success', 'User created successfully');
    }

    public function edit($id) {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        
        // Check if user is Admin (level 2 only)
        $level = session()->get('level');
        if ($level != 2) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        $title = 'Edit User';
        $data = $this->userModel->find($id);
        
        // Get all organisasi for dropdown
        $organisasis = $this->organisasiModel->findAll();

        // Get unread count for notification badge (only for level 0 and 2)
        $unreadCount = 0;
        $level = session()->get('level');
        if (in_array($level, [0, 2])) {
            $unreadCount = $this->pesanModel->getUnreadCount();
        }

        return view('page/user/edit', compact('title', 'data', 'unreadCount', 'organisasis'));
    }

    public function update($id) {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        
        // Check if user is Admin (level 2 only)
        $level = session()->get('level');
        if ($level != 2) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        $data = $this->request->getPost();
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        } else {
            unset($data['password']);
        }

        $this->userModel->update($id, $data);

        return redirect()->to(url_to('user.index'))->with('success', 'User updated successfully');
    }

    public function delete($id) {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        
        // Check if user is Admin (level 2 only)
        $level = session()->get('level');
        if ($level != 2) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        $this->userModel->delete($id);

        return redirect()->to(url_to('user.index'))->with('success', 'User deleted successfully');
    }
}
