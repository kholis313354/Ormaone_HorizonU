<?php

namespace App\Controllers\Organisasi;

use App\Controllers\BaseController;
use App\Models\OrganisasiModel;
use App\Models\AnggotaModel;
use App\Models\KepengurusanModel;

class Kepengurusan extends BaseController
{
    private $organisasiModel;
    private $anggotaModel;
    private $kepengurusanModel;

    public function __construct() {
        $this->organisasiModel = new OrganisasiModel;
        $this->anggotaModel = new AnggotaModel;
        $this->kepengurusanModel = new KepengurusanModel();
    }

    public function index() {
         // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        $title = 'Kepengurusan';
        // Join the organisasi and anggota tables
        $data = $this->kepengurusanModel->select('kepengurusans.*, organisasis.name as organisasi_name, anggotas.name as anggota_name')
            ->join('organisasis', 'kepengurusans.organisasi_id = organisasis.id')
            ->join('anggotas', 'kepengurusans.anggota_id = anggotas.id')
            ->findAll();

        return view('page/organisasi/kepengurusan/index', compact('title', 'data'));
    }

    public function getAnggotaByOrganisasi($id) {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        $anggota = $this->anggotaModel->where('organisasi_id', $id)->findAll();
        return $this->response->setJSON($anggota);
    }

    public function create() {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        $title = 'Create Kepengurusan';
        // Get all organizations
        $organisasis = $this->organisasiModel->findAll();

        return view('page/organisasi/kepengurusan/create', compact('title', 'organisasis'));
    }

    public function store() {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        $data = $this->request->getPost();
        $this->kepengurusanModel->insert($data);

        return redirect()->to(url_to('organisasi.kepengurusan.index'))->with('success', 'Kepengurusan created successfully');
    }

    public function edit($id) {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        $title = 'Edit Kepengurusan';
        $data = $this->kepengurusanModel->find($id);
        // Get all organizations
        $organisasis = $this->organisasiModel->findAll();

        return view('page/organisasi/kepengurusan/edit', compact('title', 'data', 'organisasis'));
    }

    public function update($id) {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        $data = $this->request->getPost();
        $this->kepengurusanModel->update($id, $data);

        return redirect()->to(url_to('organisasi.kepengurusan.index'))->with('success', 'Kepengurusan updated successfully');
    }

    public function delete($id) {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        $this->kepengurusanModel->delete($id);

        return redirect()->to(url_to('organisasi.kepengurusan.index'))->with('success', 'Kepengurusan deleted successfully');
    }
}
