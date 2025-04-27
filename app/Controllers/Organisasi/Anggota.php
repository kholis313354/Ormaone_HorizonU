<?php

namespace App\Controllers\Organisasi;

use App\Controllers\BaseController;
use App\Models\OrganisasiModel;
use App\Models\UserModel;
use App\Models\AnggotaModel;

class Anggota extends BaseController
{
    private $organisasiModel;
    private $userModel;
    private $anggotaModel;

    public function __construct() {
        $this->organisasiModel = new OrganisasiModel();
        $this->userModel = new UserModel;
        $this->anggotaModel = new AnggotaModel;
    }

    public function index() {
         // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        $title = 'Anggota';
        // Join the anggota and organisasi tables
        $data = $this->anggotaModel->select('anggotas.*, organisasis.name as organisasi_name, users.email as user_email')
            ->join('organisasis', 'anggotas.organisasi_id = organisasis.id')
            ->join('users', 'anggotas.user_id = users.id')
            ->findAll();

        return view('page/organisasi/anggota/index', compact('title', 'data'));
    }

    public function create() {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        $title = 'Create Anggota';
        // Get all organizations
        $organisasis = $this->organisasiModel->findAll();

        return view('page/organisasi/anggota/create', compact('title', 'organisasis'));
    }

    public function store() {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        $data = $this->request->getPost();

        // Create new user
        $userData = [
            'name' => $data['nim'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
        ];
        $this->userModel->insert($userData);
        $data['user_id'] = $this->userModel->insertID();
        $this->anggotaModel->insert($data);

        return redirect()->to(url_to('organisasi.anggota.index'))->with('success', 'Anggota created successfully');
    }

    public function edit($id) {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        $title = 'Edit Anggota';
        // Join users to get emaik
        $data = $this->anggotaModel->select('anggotas.*, users.email as user_email')
            ->join('users', 'anggotas.user_id = users.id')
            ->where('anggotas.id', $id)
            ->first();
        if (!$data) return redirect()->to(url_to('organisasi.anggota.index'))->with('error', 'Anggota not found');

        // Get all organizations
        $organisasis = $this->organisasiModel->findAll();

        return view('page/organisasi/anggota/edit', compact('title', 'data', 'organisasis'));
    }

    public function update($id) {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        $data = $this->request->getPost();

        // Update user
        $userData = [
            'name' => $data['nim'],
            'email' => $data['email'],
        ];
        if(!empty($data['password'])) $userData['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $this->userModel->update($data['user_id'], $userData);
        $this->anggotaModel->update($id, $data);

        return redirect()->to(url_to('organisasi.anggota.index'))->with('success', 'Anggota updated successfully');
    }

    public function delete($id) {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));

        $anggota = $this->anggotaModel->find($id);
        $this->userModel->delete($anggota['user_id']);
        $this->anggotaModel->delete($id);

        return redirect()->to(url_to('organisasi.anggota.index'))->with('success', 'Anggota deleted successfully');
    }
}
