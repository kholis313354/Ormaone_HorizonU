<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FakultasModel;

class Fakultas extends BaseController
{
    private $fakultasModel;
    private $pesanModel;

    public function __construct()
    {
        $this->fakultasModel = new FakultasModel();
        $this->pesanModel = new \App\Models\PesanModel();
    }

    public function index()
    {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(url_to('login'));
        }

        // Check if user is Admin or SuperAdmin (level 1 or 2)
        $level = session()->get('level');
        if (!in_array($level, [1, 2])) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $title = 'Fakultas';
        $data = $this->fakultasModel->findAll();

        // Get unread count for notification badge (only for level 0 and 2)
        $unreadCount = 0;
        if (in_array($level, [0, 2])) {
            $unreadCount = $this->pesanModel->getUnreadCount();
        }

        return view('page/fakultas/index', compact('title', 'data', 'unreadCount'));
    }

    public function create()
    {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(url_to('login'));
        }

        // Check if user is Admin or SuperAdmin (level 1 or 2)
        $level = session()->get('level');
        if (!in_array($level, [1, 2])) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $title = 'Tambah Fakultas';
        
        // Get unread count for notification badge (only for level 0 and 2)
        $unreadCount = 0;
        if (in_array($level, [0, 2])) {
            $unreadCount = $this->pesanModel->getUnreadCount();
        }
        
        return view('page/fakultas/create', compact('title', 'unreadCount'));
    }

    public function store()
    {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(url_to('login'));
        }

        // Check if user is Admin or SuperAdmin (level 1 or 2)
        $level = session()->get('level');
        if (!in_array($level, [1, 2])) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $data = $this->request->getPost();

        // Validation
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama_fakultas' => [
                'rules' => 'required|min_length[3]|max_length[100]|is_unique[fakultas.nama_fakultas]',
                'errors' => [
                    'required' => 'Nama fakultas harus diisi',
                    'min_length' => 'Nama fakultas minimal 3 karakter',
                    'max_length' => 'Nama fakultas maksimal 100 karakter',
                    'is_unique' => 'Nama fakultas sudah ada'
                ]
            ]
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $this->fakultasModel->insert($data);

        return redirect()->to(url_to('fakultas.index'))
            ->with('success', 'Fakultas berhasil ditambahkan');
    }

    public function edit($id)
    {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(url_to('login'));
        }

        // Check if user is Admin or SuperAdmin (level 1 or 2)
        $level = session()->get('level');
        if (!in_array($level, [1, 2])) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $title = 'Edit Fakultas';
        $data = $this->fakultasModel->find($id);

        if (!$data) {
            return redirect()->to(url_to('fakultas.index'))
                ->with('error', 'Fakultas tidak ditemukan');
        }

        // Get unread count for notification badge (only for level 0 and 2)
        $unreadCount = 0;
        if (in_array($level, [0, 2])) {
            $unreadCount = $this->pesanModel->getUnreadCount();
        }

        return view('page/fakultas/edit', compact('title', 'data', 'unreadCount'));
    }

    public function update($id)
    {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(url_to('login'));
        }

        // Check if user is Admin or SuperAdmin (level 1 or 2)
        $level = session()->get('level');
        if (!in_array($level, [1, 2])) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $data = $this->request->getPost();

        // Validation
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama_fakultas' => [
                'rules' => "required|min_length[3]|max_length[100]|is_unique[fakultas.nama_fakultas,id,{$id}]",
                'errors' => [
                    'required' => 'Nama fakultas harus diisi',
                    'min_length' => 'Nama fakultas minimal 3 karakter',
                    'max_length' => 'Nama fakultas maksimal 100 karakter',
                    'is_unique' => 'Nama fakultas sudah ada'
                ]
            ]
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $this->fakultasModel->update($id, $data);

        return redirect()->to(url_to('fakultas.index'))
            ->with('success', 'Fakultas berhasil diupdate');
    }

    public function delete($id)
    {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(url_to('login'));
        }

        // Check if user is Admin or SuperAdmin (level 1 or 2)
        $level = session()->get('level');
        if (!in_array($level, [1, 2])) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $this->fakultasModel->delete($id);

        return redirect()->to(url_to('fakultas.index'))
            ->with('success', 'Fakultas berhasil dihapus');
    }
}

