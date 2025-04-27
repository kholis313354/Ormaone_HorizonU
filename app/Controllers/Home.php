<?php

namespace App\Controllers;

class Home extends BaseController
{
    private $pemilihanModel;
    private $pemilihanCalonModel;
    private $pemilihanCalonSuaraModel;
 
    public function __construct() {
        $this->pemilihanModel = new \App\Models\PemilihanModel();
        $this->pemilihanCalonModel = new \App\Models\PemilihanCalonModel();
        $this->pemilihanCalonSuaraModel = new \App\Models\PemilihanCalonSuaraModel();
    }

    public function index() {
        $title = 'Home';

        return view('page/home', compact('title'));
    }

    public function voting() {
        $title = 'Voting';
        $calon = $this->pemilihanCalonModel->select('
                pemilihan_calons.*, 
                pemilihans.periode as pemilihan_periode, 
                organisasis.name as organisasi_name, 
                anggota_1.name as anggota_1_name,
                anggota_1.nim as anggota_1_nim, 
                anggota_2.name as anggota_2_name,
                anggota_2.nim as anggota_2_nim
            ')
            ->join('pemilihans', 'pemilihan_calons.pemilihan_id = pemilihans.id')
            ->join('organisasis', 'pemilihans.organisasi_id = organisasis.id')
            ->join('anggotas as anggota_1', 'pemilihan_calons.anggota_id_1 = anggota_1.id')
            ->join('anggotas as anggota_2', 'pemilihan_calons.anggota_id_2 = anggota_2.id')
            ->where('pemilihans.status', 'publish')
            ->orderBy('pemilihan_calons.number', 'ASC')
            ->findAll();

        // Sum all suara
        $totalSuaras = [];
        $pemilihan = $this->pemilihanCalonModel->select('
                pemilihan_calons.*,
                anggota_1.name as anggota_1_name,
                anggota_1.nim as anggota_1_nim,
                anggota_2.name as anggota_2_name,
                anggota_2.nim as anggota_2_nim'
            )
            ->join('anggotas as anggota_1', 'pemilihan_calons.anggota_id_1 = anggota_1.id')
            ->join('anggotas as anggota_2', 'pemilihan_calons.anggota_id_2 = anggota_2.id')
            ->findAll();

        foreach ($pemilihan as $key => $value) {
            $total = $this->pemilihanCalonSuaraModel->where('pemilihan_calon_id', $value['id'])->where('status', '1')->countAllResults();
            $totalSuaras[$value['anggota_1_name'] . ' & ' . $value['anggota_2_name']] = $total;
        }

        // Pimilihan first pemilihan yang publish
        $pemilihan = $this->pemilihanModel->where('status', 'publish')
            ->orderBy('created_at', 'DESC')
            ->first();

        return view('page/voting', compact('title', 'calon', 'totalSuaras', 'pemilihan'));
    }

    public function votingDetail($id) {
        $title = 'Voting Detail';
        $calon = $this->pemilihanCalonModel->select('
                pemilihan_calons.*, 
                pemilihans.periode as pemilihan_periode, 
                organisasis.name as organisasi_name, 
                anggota_1.name as anggota_1_name,
                anggota_1.nim as anggota_1_nim, 
                anggota_2.name as anggota_2_name,
                anggota_2.nim as anggota_2_nim
            ')
            ->join('pemilihans', 'pemilihan_calons.pemilihan_id = pemilihans.id')
            ->join('organisasis', 'pemilihans.organisasi_id = organisasis.id')
            ->join('anggotas as anggota_1', 'pemilihan_calons.anggota_id_1 = anggota_1.id')
            ->join('anggotas as anggota_2', 'pemilihan_calons.anggota_id_2 = anggota_2.id')
            ->where('pemilihans.status', 'publish')
            ->where('pemilihan_calons.id', $id)
            ->first();

        // Get list suara
        $suara = $this->pemilihanCalonSuaraModel->select('email, name, nim')
            ->where('pemilihan_calon_id', $id)
            ->findAll();

        // Sum all suara
        $totalSuaras = [];
        $pemilihan = $this->pemilihanCalonModel->select('
                pemilihan_calons.*,
                anggota_1.name as anggota_1_name,
                anggota_1.nim as anggota_1_nim,
                anggota_2.name as anggota_2_name,
                anggota_2.nim as anggota_2_nim'
            )
            ->where('pemilihan_id', $calon['pemilihan_id'])
            ->join('anggotas as anggota_1', 'pemilihan_calons.anggota_id_1 = anggota_1.id')
            ->join('anggotas as anggota_2', 'pemilihan_calons.anggota_id_2 = anggota_2.id')
            ->findAll();

        foreach ($pemilihan as $key => $value) {
            $total = $this->pemilihanCalonSuaraModel->where('pemilihan_calon_id', $value['id'])->where('status', '1')->countAllResults();
            $totalSuaras[$value['anggota_1_name'] . ' & ' . $value['anggota_2_name']] = $total;
        }

        return view('page/voting-detail', compact('title', 'calon', 'suara', 'totalSuaras'));
    }

    public function vote() {
        $data = $this->request->getPost();
        $userAgent = $this->request->getUserAgent()->getAgentString();
        $ipAddress = $this->request->getIPAddress();
        $data['user_agent'] = $userAgent;
        $data['ip_address'] = $ipAddress;

        // Check nim, user_agent, and ip_address
        $existingVote = $this->pemilihanCalonSuaraModel->where('nim', $data['nim'])
            // ->orWhere('user_agent', $userAgent)
            ->orWhere('ip_address', $ipAddress)
            ->first();
        if ($existingVote) {
            return redirect()->back()->with('errors', 'You have already voted.');
        }

        // Insert vote data
        $this->pemilihanCalonSuaraModel->insert($data);
        return redirect()->to(url_to('voting.detail', $data['pemilihan_calon_id']))->with('success', 'Vote submitted successfully.');
    }

    public function sertifikat() {
        $title = 'Sertifikat';
        return view('page/sertifikat', compact('title'));
    }

    public function berita() {
        $title = 'Berita';
        return view('page/berita', compact('title'));
    }

    public function struktur() {
        $title = 'Struktur';
        return view('page/struktur', compact('title'));
    }
}
