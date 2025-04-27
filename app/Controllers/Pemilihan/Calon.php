<?php

namespace App\Controllers\Pemilihan;

use App\Controllers\BaseController;
use App\Models\PemilihanModel;
use App\Models\AnggotaModel;
use App\Models\PemilihanCalonModel;

class Calon extends BaseController
{
    private $pemilihanModel;
    private $anggotaModel;
    private $pemilihanCalonModel;
    private $pemilihanCalonSuaraModel;

    public function __construct() {
        $this->pemilihanModel = new PemilihanModel();
        $this->anggotaModel = new AnggotaModel;
        $this->pemilihanCalonModel = new PemilihanCalonModel();
        $this->pemilihanCalonSuaraModel = new \App\Models\PemilihanCalonSuaraModel();
    }

    public function index() {
         // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        $title = 'Pemilihan Calon';
        // Join the organisasi and anggota tables
        $data = $this->pemilihanCalonModel->select('pemilihan_calons.*, pemilihans.periode as pemilihan_periode, organisasis.name as organisasi_name, anggota_1.name as anggota_1_name, anggota_2.name as anggota_2_name')
            ->join('pemilihans', 'pemilihan_calons.pemilihan_id = pemilihans.id')
            ->join('organisasis', 'pemilihans.organisasi_id = organisasis.id')
            ->join('anggotas as anggota_1', 'pemilihan_calons.anggota_id_1 = anggota_1.id')
            ->join('anggotas as anggota_2', 'pemilihan_calons.anggota_id_2 = anggota_2.id')
            ->findAll();

        return view('page/pemilihan/calon/index', compact('title', 'data'));
    }

    public function getAnggotaByPemilihan($id) {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        $pemilihan = $this->pemilihanModel->find($id);
        $anggota = $this->anggotaModel->where('organisasi_id', $pemilihan['organisasi_id'])->findAll();
        return $this->response->setJSON($anggota);
    }

    public function create() {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        $title = 'Create Calon';
        // Get All pemilihan
        $pemilihans = $this->pemilihanModel->select('pemilihans.*, organisasis.name as organisasi_name')
            ->join('organisasis', 'pemilihans.organisasi_id = organisasis.id')
            ->findAll();

        return view('page/pemilihan/calon/create', compact('title', 'pemilihans'));
    }

    public function store() {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        $data = $this->request->getPost();
        // Handle file upload
        if ($this->request->getFile('gambar_1')->isValid()) {
            $file = $this->request->getFile('gambar_1');
            $fileName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads', $fileName);
            $data['gambar_1'] = $fileName;
        }
        // Handle file upload
        if ($this->request->getFile('gambar_2')->isValid()) {
            $file = $this->request->getFile('gambar_2');
            $fileName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads', $fileName);
            $data['gambar_2'] = $fileName;
        }
        $this->pemilihanCalonModel->insert($data);

        return redirect()->to(url_to('pemilihan.calon.index'))->with('success', 'Calon created successfully');
    }

    public function edit($id) {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        $title = 'Edit Calon';
        $data = $this->pemilihanCalonModel->find($id);
        // Get all pemilihan
        $pemilihans = $this->pemilihanModel->select('pemilihans.*, organisasis.name as organisasi_name')
            ->join('organisasis', 'pemilihans.organisasi_id = organisasis.id')
            ->findAll();

        return view('page/pemilihan/calon/edit', compact('title', 'data', 'pemilihans'));
    }

    public function update($id) {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        $data = $this->request->getPost();
        // Handle file upload
        if ($this->request->getFile('gambar_1')->isValid()) {
            $file = $this->request->getFile('gambar_1');
            $fileName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads', $fileName);
            $data['gambar_1'] = $fileName;
        }
        // Handle file upload
        if ($this->request->getFile('gambar_2')->isValid()) {
            $file = $this->request->getFile('gambar_2');
            $fileName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads', $fileName);
            $data['gambar_2'] = $fileName;
        }
        $this->pemilihanCalonModel->update($id, $data);

        return redirect()->to(url_to('pemilihan.calon.index'))->with('success', 'Calon updated successfully');
    }

    public function delete($id) {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        $this->pemilihanCalonModel->delete($id);

        return redirect()->to(url_to('pemilihan.calon.index'))->with('success', 'Calon deleted successfully');
    }

    public function getSuaraByPemilihan($id) {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        $title = 'Suara Pemilihan';
        $pemilihan = $this->pemilihanCalonModel->select('pemilihan_calons.*, pemilihans.periode as pemilihan_periode, organisasis.name as organisasi_name, anggota_1.name as anggota_1_name, anggota_2.name as anggota_2_name')
            ->join('pemilihans', 'pemilihan_calons.pemilihan_id = pemilihans.id')
            ->join('organisasis', 'pemilihans.organisasi_id = organisasis.id')
            ->join('anggotas as anggota_1', 'pemilihan_calons.anggota_id_1 = anggota_1.id')
            ->join('anggotas as anggota_2', 'pemilihan_calons.anggota_id_2 = anggota_2.id')
            ->where('pemilihan_calons.id', $id)
            ->first();
        $data = $this->pemilihanCalonSuaraModel->select('pemilihan_calon_suara.*')
            ->join('pemilihan_calons', 'pemilihan_calons.id = pemilihan_calon_suara.pemilihan_calon_id')
            ->where('pemilihan_calon_suara.pemilihan_calon_id', $id)
            ->findAll();

        return view('page/pemilihan/calon/suara', compact('title', 'pemilihan', 'data'));
    }

    public function confirmVote($pemilihanId, $suaraId) {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        $this->pemilihanCalonSuaraModel->update($suaraId, ['status' => 1]);

        return redirect()->to(url_to('pemilihan.calon.suara', $pemilihanId))->with('success', 'Vote confirmed successfully');
    }

    public function revokeVote($pemilihanId, $suaraId) {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        $this->pemilihanCalonSuaraModel->update($suaraId, ['status' => 0]);

        return redirect()->to(url_to('pemilihan.calon.suara', $pemilihanId))->with('success', 'Vote revoked successfully');
    }
}
