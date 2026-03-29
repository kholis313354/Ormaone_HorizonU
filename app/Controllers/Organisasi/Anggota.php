<?php

namespace App\Controllers\Organisasi;

use App\Controllers\BaseController;
use App\Models\OrganisasiModel;
use App\Models\AnggotaModel;
use App\Models\FakultasModel;

class Anggota extends BaseController
{
    private $organisasiModel;
    private $anggotaModel;
    private $fakultasModel;
    private $pesanModel;

    public function __construct() {
        $this->organisasiModel = new OrganisasiModel();
        $this->anggotaModel = new AnggotaModel();
        $this->fakultasModel = new FakultasModel();
        $this->pesanModel = new \App\Models\PesanModel();
    }

    public function index() {
         // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        
        // Check if user is Admin or SuperAdmin (level 1 or 2)
        $level = session()->get('level');
        if (!in_array($level, [1, 2])) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        $title = 'Anggota';
        // Join the anggota, organisasi, dan fakultas tables (left join users dan fakultas karena bisa null)
        $data = $this->anggotaModel->select('anggotas.*, organisasis.name as organisasi_name, users.email as user_email, fakultas.nama_fakultas as fakultas_name')
            ->join('organisasis', 'anggotas.organisasi_id = organisasis.id')
            ->join('users', 'anggotas.user_id = users.id', 'left')
            ->join('fakultas', 'anggotas.fakultas_id = fakultas.id', 'left')
            ->findAll();

        // Get unread count for notification badge (only for level 0 and 2)
        $unreadCount = 0;
        if (in_array($level, [0, 2])) {
            $unreadCount = $this->pesanModel->getUnreadCount();
        }

        return view('page/organisasi/anggota/index', compact('title', 'data', 'unreadCount'));
    }

    public function create() {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        
        // Check if user is Admin or SuperAdmin (level 1 or 2)
        $level = session()->get('level');
        if (!in_array($level, [1, 2])) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        $title = 'Create Anggota';
        // Get all organizations
        $organisasis = $this->organisasiModel->findAll();
        // Get all fakultas
        $fakultas = $this->fakultasModel->findAll();

        // Get unread count for notification badge (only for level 0 and 2)
        $unreadCount = 0;
        if (in_array($level, [0, 2])) {
            $unreadCount = $this->pesanModel->getUnreadCount();
        }

        return view('page/organisasi/anggota/create', compact('title', 'organisasis', 'fakultas', 'unreadCount'));
    }

    public function store() {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        
        // Check if user is Admin or SuperAdmin (level 1 or 2)
        $level = session()->get('level');
        if (!in_array($level, [1, 2])) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        $data = $this->request->getPost();

        // Hapus email dan password dari data (tidak digunakan untuk membuat user)
        unset($data['email']);
        unset($data['password']);
        
        // Hapus user_id dari data (user hanya dibuat di fitur user)
        // user_id akan null jika tidak ada user yang terkait
        unset($data['user_id']);
        
        $this->anggotaModel->insert($data);

        return redirect()->to(url_to('organisasi.anggota.index'))->with('success', 'Anggota created successfully');
    }

    public function edit($id) {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        
        // Check if user is Admin or SuperAdmin (level 1 or 2)
        $level = session()->get('level');
        if (!in_array($level, [1, 2])) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        $title = 'Edit Anggota';
        // Join users dan fakultas to get email dan nama fakultas (left join karena bisa null)
        $data = $this->anggotaModel->select('anggotas.*, users.email as user_email, fakultas.nama_fakultas as fakultas_name')
            ->join('users', 'anggotas.user_id = users.id', 'left')
            ->join('fakultas', 'anggotas.fakultas_id = fakultas.id', 'left')
            ->where('anggotas.id', $id)
            ->first();
        if (!$data) return redirect()->to(url_to('organisasi.anggota.index'))->with('error', 'Anggota not found');

        // Get all organizations
        $organisasis = $this->organisasiModel->findAll();
        // Get all fakultas
        $fakultas = $this->fakultasModel->findAll();

        // Get unread count for notification badge (only for level 0 and 2)
        $unreadCount = 0;
        if (in_array($level, [0, 2])) {
            $unreadCount = $this->pesanModel->getUnreadCount();
        }

        return view('page/organisasi/anggota/edit', compact('title', 'data', 'organisasis', 'fakultas', 'unreadCount'));
    }

    public function update($id) {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        
        // Check if user is Admin or SuperAdmin (level 1 or 2)
        $level = session()->get('level');
        if (!in_array($level, [1, 2])) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        $data = $this->request->getPost();

        // Hapus email dan password dari data (tidak digunakan untuk update user)
        unset($data['email']);
        unset($data['password']);
        
        // Jangan update user_id (user hanya diupdate di fitur user)
        unset($data['user_id']);
        
        $this->anggotaModel->update($id, $data);

        return redirect()->to(url_to('organisasi.anggota.index'))->with('success', 'Anggota updated successfully');
    }

    public function delete($id) {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        
        // Check if user is Admin or SuperAdmin (level 1 or 2)
        $level = session()->get('level');
        if (!in_array($level, [1, 2])) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Hanya hapus anggota, tidak hapus user (user hanya dihapus di fitur user)
        $this->anggotaModel->delete($id);

        return redirect()->to(url_to('organisasi.anggota.index'))->with('success', 'Anggota deleted successfully');
    }
}
