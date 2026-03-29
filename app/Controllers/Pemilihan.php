<?php

namespace App\Controllers;

use App\Models\OrganisasiModel;
use App\Models\PemilihanModel;

class Pemilihan extends BaseController
{
    private $organisasiModel;
    private $pemilihanModel;
    private $pesanModel;

    public function __construct()
    {
        $this->organisasiModel = new OrganisasiModel;
        $this->pemilihanModel = new PemilihanModel;
        $this->pesanModel = new \App\Models\PesanModel();
    }

    public function index()
    {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn'))
            return redirect()->to(url_to('login'));

        // Check if user is Admin or SuperAdmin (level 1 or 2)
        $level = session()->get('level');
        if (!in_array($level, [1, 2])) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $title = 'Pemilihan';

        // Get filter year from query parameter
        $selectedYear = $this->request->getGet('tahun');
        if (!$selectedYear) {
            $selectedYear = 'all'; // Default to show all
        }

        // Get all available years from periode
        $allYears = $this->pemilihanModel->select('periode')
            ->findAll();

        $availableYears = [];
        foreach ($allYears as $item) {
            // Extract year from periode (format: "2024/2025" or "2024-2025" or "2024")
            if (preg_match('/(\d{4})/', $item['periode'], $matches)) {
                $year = (int) $matches[1];
                if (!in_array($year, $availableYears)) {
                    $availableYears[] = $year;
                }
            }
        }
        rsort($availableYears); // Sort descending

        // Filter data by year
        $query = $this->pemilihanModel->select('pemilihans.*, organisasis.name as organisasi_name')
            ->join('organisasis', 'pemilihans.organisasi_id = organisasis.id');

        if ($selectedYear && $selectedYear != 'all') {
            $query->where("pemilihans.periode LIKE", "%{$selectedYear}%");
        }

        $data = $query->orderBy('pemilihans.created_at', 'DESC')->findAll();

        // Get unread count for notification badge (only for level 0 and 2)
        $unreadCount = 0;
        if (in_array($level, [0, 2])) {
            $unreadCount = $this->pesanModel->getUnreadCount();
        }

        return view('page/pemilihan/index', compact('title', 'data', 'availableYears', 'selectedYear', 'unreadCount'));
    }

    public function create()
    {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn'))
            return redirect()->to(url_to('login'));

        // Check if user is Admin or SuperAdmin (level 1 or 2)
        $level = session()->get('level');
        if (!in_array($level, [1, 2])) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $title = 'Create Pemilihan';

        // Get all organizations
        $organisasis = $this->organisasiModel->findAll();

        return view('page/pemilihan/create', compact('title', 'organisasis'));
    }

    public function store()
    {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn'))
            return redirect()->to(url_to('login'));

        // Check if user is Admin or SuperAdmin (level 1 or 2)
        $level = session()->get('level');
        if (!in_array($level, [1, 2])) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $data = $this->request->getPost();

        // Convert datetime-local format (Y-m-dTH:i) to SQL format (Y-m-d H:i:s)
        if (isset($data['tanggal_mulai'])) {
            $data['tanggal_mulai'] = str_replace('T', ' ', $data['tanggal_mulai']);
        }
        if (isset($data['tanggal_akhir'])) {
            $data['tanggal_akhir'] = str_replace('T', ' ', $data['tanggal_akhir']);
        }

        $this->pemilihanModel->insert($data);

        // Preserve year filter if exists
        $tahun = $this->request->getGet('tahun');
        $redirectUrl = url_to('pemilihan.index');
        if ($tahun) {
            $redirectUrl .= '?tahun=' . $tahun;
        }

        return redirect()->to($redirectUrl)->with('success', 'Pemilihan created successfully');
    }

    public function edit($id)
    {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn'))
            return redirect()->to(url_to('login'));

        // Check if user is Admin or SuperAdmin (level 1 or 2)
        $level = session()->get('level');
        if (!in_array($level, [1, 2])) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $title = 'Edit Pemilihan';
        $data = $this->pemilihanModel->find($id);
        // Get all organizations
        $organisasis = $this->organisasiModel->findAll();

        return view('page/pemilihan/edit', compact('title', 'data', 'organisasis'));
    }

    public function update($id)
    {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn'))
            return redirect()->to(url_to('login'));

        // Check if user is Admin or SuperAdmin (level 1 or 2)
        $level = session()->get('level');
        if (!in_array($level, [1, 2])) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $data = $this->request->getPost();

        // Convert datetime-local format (Y-m-dTH:i) to SQL format (Y-m-d H:i:s)
        if (isset($data['tanggal_mulai'])) {
            $data['tanggal_mulai'] = str_replace('T', ' ', $data['tanggal_mulai']);
        }
        if (isset($data['tanggal_akhir'])) {
            $data['tanggal_akhir'] = str_replace('T', ' ', $data['tanggal_akhir']);
        }

        $this->pemilihanModel->update($id, $data);

        // Preserve year filter if exists
        $tahun = $this->request->getGet('tahun');
        $redirectUrl = url_to('pemilihan.index');
        if ($tahun) {
            $redirectUrl .= '?tahun=' . $tahun;
        }

        return redirect()->to($redirectUrl)->with('success', 'Pemilihan updated successfully');
    }

    public function delete($id)
    {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn'))
            return redirect()->to(url_to('login'));

        // Check if user is Admin or SuperAdmin (level 1 or 2)
        $level = session()->get('level');
        if (!in_array($level, [1, 2])) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $this->pemilihanModel->delete($id);

        // Preserve year filter if exists
        $tahun = $this->request->getGet('tahun');
        $redirectUrl = url_to('pemilihan.index');
        if ($tahun) {
            $redirectUrl .= '?tahun=' . $tahun;
        }

        return redirect()->to($redirectUrl)->with('success', 'Pemilihan deleted successfully');
    }
}
