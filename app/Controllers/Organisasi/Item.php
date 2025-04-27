<?php

namespace App\Controllers\Organisasi;

use App\Controllers\BaseController;
use App\Models\OrganisasiModel;

class Item extends BaseController
{
    private $organisasiModel;

    public function __construct() {
        $this->organisasiModel = new OrganisasiModel;
    }

    public function index() {
         // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        $title = 'Organisasi';
        $data = $this->organisasiModel->findAll();

        return view('page/organisasi/item/index', compact('title', 'data'));
    }

    public function create() {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        $title = 'Create Organisasi';

        return view('page/organisasi/item/create', compact('title'));
    }

    public function store() {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        $data = $this->request->getPost();
        // Handle file upload
        if ($this->request->getFile('logo')->isValid()) {
            $file = $this->request->getFile('logo');
            $fileName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads', $fileName);
            $data['logo'] = $fileName;
        }

        $this->organisasiModel->insert($data);

        return redirect()->to(url_to('organisasi.item.index'))->with('success', 'Organisasi created successfully');
    }

    public function edit($id) {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        $title = 'Edit Organisasi';
        $data = $this->organisasiModel->find($id);

        return view('page/organisasi/item/edit', compact('title', 'data'));
    }

    public function update($id) {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        $data = $this->request->getPost();
        // Handle file upload
        if ($this->request->getFile('logo')->isValid()) {
            $file = $this->request->getFile('logo');
            $fileName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads', $fileName);
            $data['logo'] = $fileName;
        } else {
            unset($data['logo']);
        }

        $this->organisasiModel->update($id, $data);

        return redirect()->to(url_to('organisasi.item.index'))->with('success', 'Organisasi updated successfully');
    }

    public function delete($id) {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        $this->organisasiModel->delete($id);

        return redirect()->to(url_to('organisasi.item.index'))->with('success', 'Organisasi deleted successfully');
    }
}
