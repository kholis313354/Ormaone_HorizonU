<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    private $anggotaModel;
    private $pemilihanModel;
    private $pemilihanCalonModel;
    private $pemilihanCalonSuaraModel;

    public function __construct() {
        $this->anggotaModel = new \App\Models\AnggotaModel();
        $this->pemilihanModel = new \App\Models\PemilihanModel();
        $this->pemilihanCalonModel = new \App\Models\PemilihanCalonModel();
        $this->pemilihanCalonSuaraModel = new \App\Models\PemilihanCalonSuaraModel();
    }

    public function index() {
         // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        $title = 'Dashboard';

        // Total Anggota
        $totalAnggota = $this->anggotaModel->countAll();
        // Total Pemilihan
        $totalPemilihan = $this->pemilihanModel->countAll();
        // Total Calon
        $totalCalon = $this->pemilihanCalonModel->countAll();
        // Total Suara
        $totalSuara = $this->pemilihanCalonSuaraModel->countAll();

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

        return view('page/dashboard', compact('title', 'totalAnggota', 'totalPemilihan', 'totalCalon', 'totalSuara', 'totalSuaras'));
    }
}
