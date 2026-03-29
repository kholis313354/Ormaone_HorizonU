<?php

namespace App\Controllers\Organisasi;

use App\Controllers\BaseController;
use App\Models\OrganisasiModel;

class Item extends BaseController
{
    private $organisasiModel;
    private $pesanModel;

    public function __construct() {
        $this->organisasiModel = new OrganisasiModel;
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
        
        $title = 'Organisasi';
        $data = $this->organisasiModel->findAll();

        // Get unread count for notification badge (only for level 0 and 2)
        $unreadCount = 0;
        if (in_array($level, [0, 2])) {
            $unreadCount = $this->pesanModel->getUnreadCount();
        }

        return view('page/organisasi/item/index', compact('title', 'data', 'unreadCount'));
        
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
        
        $title = 'Create Organisasi';

        // Get unread count for notification badge (only for level 0 and 2)
        $unreadCount = 0;
        if (in_array($level, [0, 2])) {
            $unreadCount = $this->pesanModel->getUnreadCount();
        }

        return view('page/organisasi/item/create', compact('title', 'unreadCount'));
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
        // Handle file upload
        if ($this->request->getFile('logo')->isValid()) {
            $file = $this->request->getFile('logo');
            $fileName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads', $fileName);
            $data['image'] = $fileName;
        }

        $this->organisasiModel->insert($data);

        return redirect()->to(url_to('organisasi.item.index'))->with('success', 'Organisasi created successfully');
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
        
        $title = 'Edit Organisasi';
        $data = $this->organisasiModel->find($id);

        // Get unread count for notification badge (only for level 0 and 2)
        $unreadCount = 0;
        if (in_array($level, [0, 2])) {
            $unreadCount = $this->pesanModel->getUnreadCount();
        }

        return view('page/organisasi/item/edit', compact('title', 'data', 'unreadCount'));
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
        // Handle file upload
        if ($this->request->getFile('logo')->isValid()) {
            $file = $this->request->getFile('logo');
            $fileName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads', $fileName);
            $data['image'] = $fileName;
        } else {
            unset($data['image']);
        }

        $this->organisasiModel->update($id, $data);

        return redirect()->to(url_to('organisasi.item.index'))->with('success', 'Organisasi updated successfully');
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
        
        $this->organisasiModel->delete($id);

        return redirect()->to(url_to('organisasi.item.index'))->with('success', 'Organisasi deleted successfully');
        
    }
    public function voting()
{
    // Kode sebelumnya...
    
    $organisasiModel = new OrganisasiModel();
    $fakultasMapping = $organisasiModel->getFakultasMapping();
    
    return view('page/voting_detail', [
        'title' => $title,
        'calon' => $calon,
        'suara' => $suara,
        'totalSuaras' => $totalSuaras,
        'fakultasMapping' => $fakultasMapping // Kirim data mapping ke view
        
    ]);
    
}}
