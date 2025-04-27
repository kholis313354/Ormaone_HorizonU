<?php

namespace App\Controllers;

use App\Models\OrganisasiModel;
use App\Models\PemilihanModel;

class Pemilihan extends BaseController
{
    private $organisasiModel;
    private $pemilihanModel;

    public function __construct() {
        $this->organisasiModel = new OrganisasiModel;
        $this->pemilihanModel = new PemilihanModel;
    }

    public function index() {
         // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        $title = 'Pemilihan';
        $data = $this->pemilihanModel->select('pemilihans.*, organisasis.name as organisasi_name')
            ->join('organisasis', 'pemilihans.organisasi_id = organisasis.id')
            ->findAll();

        return view('page/pemilihan/index', compact('title', 'data'));
    }

    public function create() {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        $title = 'Create Pemilihan';

        // Get all organizations
        $organisasis = $this->organisasiModel->findAll();

        return view('page/pemilihan/create', compact('title', 'organisasis'));
    }

    public function store() {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        $data = $this->request->getPost();
        $this->pemilihanModel->insert($data);

        return redirect()->to(url_to('pemilihan.index'))->with('success', 'Pemilihan created successfully');
    }

    public function edit($id) {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        $title = 'Edit Pemilihan';
        $data = $this->pemilihanModel->find($id);
        // Get all organizations
        $organisasis = $this->organisasiModel->findAll();

        return view('page/pemilihan/edit', compact('title', 'data', 'organisasis'));
    }

    public function update($id) {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        $data = $this->request->getPost();
        $this->pemilihanModel->update($id, $data);

        return redirect()->to(url_to('pemilihan.index'))->with('success', 'Pemilihan updated successfully');
    }

    public function delete($id) {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        $this->pemilihanModel->delete($id);

        return redirect()->to(url_to('pemilihan.index'))->with('success', 'Pemilihan deleted successfully');
    }
}
