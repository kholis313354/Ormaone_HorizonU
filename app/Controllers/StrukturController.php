<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StrukturModel;
use App\Models\StrukturTampilanModel;
use App\Models\OrganisasiModel;

class StrukturController extends BaseController
{
    private $strukturModel;
    private $strukturTampilanModel;
    private $strukturTampilanDivisiModel;
    private $visiMisiModel;

    private $organisasiModel;
    private $pesanModel;
    private $prokerModel;
    private $anggaranModel;

    public function __construct()
    {
        $this->strukturModel = new StrukturModel();
        $this->strukturTampilanModel = new StrukturTampilanModel();
        $this->strukturTampilanDivisiModel = new \App\Models\StrukturTampilanDivisiModel();
        $this->visiMisiModel = new \App\Models\VisiMisiModel();
        $this->organisasiModel = new OrganisasiModel();
        $this->pesanModel = new \App\Models\PesanModel();
        $this->prokerModel = new \App\Models\ProgramKerjaModel();
        $this->anggaranModel = new \App\Models\AnggaranModel();
    }

    public function index()
    {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn'))
            return redirect()->to(url_to('login'));

        // Check if user is Admin, SuperAdmin, or Anggota Organisasi (level 0, 1, or 2)
        $level = session()->get('level');
        if (!in_array($level, [0, 1, 2])) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $title = 'Struktur Organisasi';

        // Query dengan JOIN untuk mendapatkan nama organisasi
        $builder = $this->strukturTampilanModel->select('struktur_tampilan.*, organisasis.name as organisasi_name')
            ->join('organisasis', 'organisasis.id = struktur_tampilan.organisasi_id', 'inner');

        // Filter berdasarkan level user
        if ($level == 1) {
            // SuperAdmin bisa lihat semua
            // Tidak perlu filter
        } else {
            // Admin (level 2) dan Anggota Organisasi (level 0) hanya bisa lihat struktur organisasinya sendiri
            $organisasiId = session()->get('organisasi_id');
            if ($organisasiId) {
                $builder->where('struktur_tampilan.organisasi_id', $organisasiId);
            } else {
                // Jika organisasi_id tidak ada di session, ambil dari database users
                $userId = session()->get('id');
                if ($userId) {
                    $userModel = new \App\Models\UserModel();
                    $user = $userModel->where('id', $userId)->first();
                    if ($user && isset($user['organisasi_id']) && !empty($user['organisasi_id'])) {
                        $builder->where('struktur_tampilan.organisasi_id', $user['organisasi_id']);
                    }
                }
            }
        }

        $data = $builder->orderBy('struktur_tampilan.tahun', 'DESC')
            ->orderBy('organisasis.name', 'ASC')
            ->findAll();

        // Fetch Divisions
        $divisiBuilder = $this->strukturTampilanDivisiModel->select('struktur_tampilan_divisi.*, organisasis.name as organisasi_name')
            ->join('organisasis', 'organisasis.id = struktur_tampilan_divisi.organisasi_id', 'inner');

        if ($level != 1) {
            $organisasiId = session()->get('organisasi_id');
            if ($organisasiId) {
                $divisiBuilder->where('struktur_tampilan_divisi.organisasi_id', $organisasiId);
            } else {
                $userId = session()->get('id');
                if ($userId) {
                    $userModel = new \App\Models\UserModel();
                    $user = $userModel->where('id', $userId)->first();
                    if ($user && isset($user['organisasi_id']) && !empty($user['organisasi_id'])) {
                        $divisiBuilder->where('struktur_tampilan_divisi.organisasi_id', $user['organisasi_id']);
                    }
                }
            }
        }

        $divisiData = $divisiBuilder->orderBy('struktur_tampilan_divisi.tahun', 'DESC')
            ->findAll();

        // Get unread count for notification badge (only for level 0 and 2)
        $unreadCount = 0;
        if (in_array($level, [0, 2])) {
            $unreadCount = $this->pesanModel->getUnreadCount();
        }

        // Fetch Visi Misi
        $visiMisiBuilder = $this->visiMisiModel->select('visi_misi.*, organisasis.name as organisasi_name')
            ->join('organisasis', 'organisasis.id = visi_misi.organisasi_id', 'inner');

        if ($level != 1) {
            $organisasiId = session()->get('organisasi_id');
            if ($organisasiId) {
                $visiMisiBuilder->where('visi_misi.organisasi_id', $organisasiId);
            } else {
                $userId = session()->get('id');
                if ($userId) {
                    $userModel = new \App\Models\UserModel();
                    $user = $userModel->where('id', $userId)->first();
                    if ($user && isset($user['organisasi_id']) && !empty($user['organisasi_id'])) {
                        $visiMisiBuilder->where('visi_misi.organisasi_id', $user['organisasi_id']);
                    }
                }
            }
        }

        $visiMisiData = $visiMisiBuilder->orderBy('visi_misi.tahun', 'DESC')->findAll();

        // Fetch Proker
        $prokerBuilder = $this->prokerModel->select('program_kerja.*, organisasis.name as organisasi_name')
            ->join('organisasis', 'organisasis.id = program_kerja.organisasi_id', 'inner');

        if ($level != 1) {
            $organisasiId = session()->get('organisasi_id');
            if ($organisasiId) {
                $prokerBuilder->where('program_kerja.organisasi_id', $organisasiId);
            } else {
                $userId = session()->get('id');
                if ($userId) {
                    $userModel = new \App\Models\UserModel();
                    $user = $userModel->where('id', $userId)->first();
                    if ($user && isset($user['organisasi_id']) && !empty($user['organisasi_id'])) {
                        $prokerBuilder->where('program_kerja.organisasi_id', $user['organisasi_id']);
                    }
                }
            }
        }

        $prokerData = $prokerBuilder->orderBy('program_kerja.tahun', 'DESC')->findAll();

        // Fetch Anggaran
        $anggaranBuilder = $this->anggaranModel->select('anggaran.*, organisasis.name as organisasi_name')
            ->join('organisasis', 'organisasis.id = anggaran.organisasi_id', 'inner');

        if ($level != 1) {
            $organisasiId = session()->get('organisasi_id');
            if ($organisasiId) {
                $anggaranBuilder->where('anggaran.organisasi_id', $organisasiId);
            } else {
                $userId = session()->get('id');
                if ($userId) {
                    $userModel = new \App\Models\UserModel();
                    $user = $userModel->where('id', $userId)->first();
                    if ($user && isset($user['organisasi_id']) && !empty($user['organisasi_id'])) {
                        $anggaranBuilder->where('anggaran.organisasi_id', $user['organisasi_id']);
                    }
                }
            }
        }
        $anggaranData = $anggaranBuilder->orderBy('anggaran.tahun', 'DESC')->findAll();

        return view('page/struktur/index', compact('title', 'data', 'divisiData', 'visiMisiData', 'prokerData', 'anggaranData', 'unreadCount'));
    }

    public function create()
    {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn'))
            return redirect()->to(url_to('login'));

        // Check if user is Admin, SuperAdmin, or Anggota Organisasi (level 0, 1, or 2)
        $level = session()->get('level');
        if (!in_array($level, [0, 2])) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $title = 'Create Struktur';

        // Get organisasi berdasarkan user dari table users dengan JOIN
        $userId = session()->get('id');
        $organisasiId = session()->get('organisasi_id');
        $organisasis = [];
        $currentOrganisasi = null;

        // Ambil data user dengan JOIN ke organisasis untuk mendapatkan data organisasi
        $userModel = new \App\Models\UserModel();

        if ($level == 1) {
            // SuperAdmin bisa pilih semua organisasi
            $organisasis = $this->organisasiModel->findAll();
        } else {
            // Admin/Anggota: ambil data organisasi dari relasi users ke organisasis
            // Pertama, ambil organisasi_id dari table users
            if ($userId) {
                $user = $userModel->where('id', $userId)->first();
                $userOrgId = null;

                if ($user && isset($user['organisasi_id']) && !empty($user['organisasi_id'])) {
                    $userOrgId = $user['organisasi_id'];
                } elseif ($organisasiId) {
                    // Fallback: gunakan organisasi_id dari session
                    $userOrgId = $organisasiId;
                }

                // Ambil data organisasi berdasarkan organisasi_id dari user
                if ($userOrgId) {
                    $currentOrganisasi = $this->organisasiModel->find($userOrgId);
                    if ($currentOrganisasi && !empty($currentOrganisasi)) {
                        $organisasis = [$currentOrganisasi];
                    }
                }
            }
        }

        // Get unread count for notification badge (only for level 0 and 2)
        $unreadCount = 0;
        if (in_array($level, [0, 2])) {
            $unreadCount = $this->pesanModel->getUnreadCount();
        }

        return view('page/struktur/create', compact('title', 'organisasis', 'currentOrganisasi', 'unreadCount'));
    }

    public function store()
    {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn'))
            return redirect()->to(url_to('login'));

        // Check if user is Admin, SuperAdmin, or Anggota Organisasi (level 0, 1, or 2)
        $level = session()->get('level');
        if (!in_array($level, [0, 2])) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $data = $this->request->getPost();

        // Ambil organisasi_id dengan berbagai cara
        $organisasiId = null;

        // 1. Dari form post data
        if (!empty($data['organisasi_id'])) {
            $organisasiId = (int) $data['organisasi_id'];
        }

        // 2. Dari session (jika tidak ada di form)
        if (empty($organisasiId)) {
            $organisasiId = session()->get('organisasi_id');
            if ($organisasiId) {
                $organisasiId = (int) $organisasiId;
            }
        }

        // 3. Ambil langsung dari database users (jika session juga kosong)
        if (empty($organisasiId)) {
            $userId = session()->get('id');
            if ($userId) {
                $userModel = new \App\Models\UserModel();
                $user = $userModel->where('id', $userId)->first();
                if ($user && isset($user['organisasi_id']) && !empty($user['organisasi_id'])) {
                    $organisasiId = (int) $user['organisasi_id'];
                }
            }
        }

        // Validasi organisasi_id harus ada dan valid
        if (empty($organisasiId)) {
            log_message('error', 'Struktur Store - Organisasi ID tidak ditemukan. User ID: ' . session()->get('id'));
            return redirect()->back()
                ->withInput()
                ->with('error', 'Organisasi tidak ditemukan. Silakan pastikan akun Anda memiliki organisasi yang terhubung.');
        }

        // Pastikan organisasi_id valid (ada di table organisasis)
        $organisasi = $this->organisasiModel->find($organisasiId);
        if (!$organisasi) {
            log_message('error', 'Struktur Store - Organisasi ID ' . $organisasiId . ' tidak ditemukan di table organisasis');
            return redirect()->back()
                ->withInput()
                ->with('error', 'Organisasi tidak valid. Organisasi ID: ' . $organisasiId . ' tidak ditemukan di database.');
        }

        // Set organisasi_id ke data
        $data['organisasi_id'] = $organisasiId;

        if (empty($data['tahun'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Tahun wajib diisi.');
        }

        // Handle upload gambar untuk 5 posisi
        $uploadPath = FCPATH . 'uploads/struktur/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        for ($i = 1; $i <= 6; $i++) {
            $file = $this->request->getFile('gambar_' . $i);
            if ($file && $file->isValid() && !$file->hasMoved()) {
                // Validasi file gambar
                if (
                    $file->getMimeType() === 'image/jpeg' ||
                    $file->getMimeType() === 'image/jpg' ||
                    $file->getMimeType() === 'image/png'
                ) {
                    $newName = $file->getRandomName();
                    if ($file->move($uploadPath, $newName)) {
                        $data['gambar_' . $i] = $newName;
                    }
                }
            }
        }

        // Set is_active default to 0 if not set
        if (!isset($data['is_active'])) {
            $data['is_active'] = 0;
        } else {
            $data['is_active'] = (int) $data['is_active'];
        }

        // Jika is_active = 1, set yang lain menjadi 0 untuk organisasi dan tahun yang sama
        if (isset($data['is_active']) && $data['is_active'] == 1 && !empty($data['tahun'])) {
            $this->strukturTampilanModel->where('organisasi_id', $organisasiId)
                ->where('tahun', $data['tahun'])
                ->set('is_active', 0)
                ->update();
        }

        // Ensure required fields are set dengan organisasi_id yang sudah divalidasi
        $dataToInsert = [
            'organisasi_id' => $organisasiId, // Sudah divalidasi di atas
            'tahun' => trim($data['tahun']),
            'is_active' => isset($data['is_active']) ? (int) $data['is_active'] : 0,
        ];

        // Log untuk debugging
        log_message('info', 'Struktur Store - Final organisasi_id untuk insert: ' . $organisasiId);
        log_message('info', 'Struktur Store - Tahun: ' . $dataToInsert['tahun']);
        log_message('info', 'Struktur Store - Is Active: ' . $dataToInsert['is_active']);

        // Add optional fields
        $optionalFields = [
            'periode',
            'nama_1',
            'jabatan_1',
            'gambar_1',
            'nama_2',
            'jabatan_2',
            'gambar_2',
            'nama_3',
            'jabatan_3',
            'gambar_3',
            'nama_4',
            'jabatan_4',
            'gambar_4',
            'nama_5',
            'jabatan_5',
            'gambar_5',
            'nama_6',
            'jabatan_6',
            'gambar_6',
            'prodi_1',
            'prodi_2',
            'prodi_3',
            'prodi_4',
            'prodi_5',
            'prodi_6',
        ];

        foreach ($optionalFields as $field) {
            if (isset($data[$field]) && $data[$field] !== '') {
                $dataToInsert[$field] = trim($data[$field]);
            } else {
                $dataToInsert[$field] = null;
            }
        }

        // Debug logging - log data yang akan diinsert
        log_message('info', 'Struktur Store - Starting insert process');
        log_message('info', 'Struktur Store - Data to insert: ' . json_encode($dataToInsert, JSON_UNESCAPED_UNICODE));
        log_message('info', 'Struktur Store - Organisasi ID: ' . $organisasiId);

        // Insert data using direct query builder (more reliable)
        try {
            $db = \Config\Database::connect();
            $builder = $db->table('struktur_tampilan');

            // Insert data directly using query builder
            $result = $builder->insert($dataToInsert);

            if ($result) {
                $insertId = $db->insertID();
                log_message('info', 'Struktur Store - Successfully inserted with ID: ' . $insertId);

                // Redirect ke index dengan success message
                return redirect()->to(url_to('struktur.index'))
                    ->with('success', 'Struktur berhasil dibuat');
            } else {
                // Check for database errors
                $dbError = $db->error();
                $errorMessage = 'Gagal menyimpan data ke database.';

                if (!empty($dbError['message'])) {
                    $errorMessage = 'Database error: ' . $dbError['message'];

                    // Check for foreign key constraint error
                    if (
                        strpos($dbError['message'], 'foreign key') !== false ||
                        strpos($dbError['message'], '1452') !== false ||
                        strpos($dbError['message'], 'Cannot add or update') !== false
                    ) {
                        $errorMessage = 'Organisasi tidak valid. Pastikan organisasi yang dipilih ada di database.';
                    }
                }

                log_message('error', 'Struktur Store - Insert failed');
                log_message('error', 'Struktur Store - DB Error: ' . json_encode($dbError, JSON_UNESCAPED_UNICODE));
                log_message('error', 'Struktur Store - Data attempted: ' . json_encode($dataToInsert, JSON_UNESCAPED_UNICODE));
                log_message('error', 'Struktur Store - Organisasi ID: ' . $organisasiId);

                return redirect()->back()
                    ->withInput()
                    ->with('error', $errorMessage);
            }
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            $errorMessage = $e->getMessage();
            if (strpos($errorMessage, 'foreign key') !== false || strpos($errorMessage, '1452') !== false) {
                $errorMessage = 'Organisasi tidak valid. Pastikan organisasi yang dipilih ada di database.';
            }

            log_message('error', 'Struktur Store - DatabaseException: ' . $e->getMessage());
            log_message('error', 'Struktur Store - Code: ' . $e->getCode());
            log_message('error', 'Struktur Store - Data attempted: ' . json_encode($dataToInsert, JSON_UNESCAPED_UNICODE));

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error database: ' . $errorMessage);
        } catch (\Exception $e) {
            log_message('error', 'Struktur Store - Exception: ' . $e->getMessage());
            log_message('error', 'Struktur Store - File: ' . $e->getFile() . ' Line: ' . $e->getLine());
            log_message('error', 'Struktur Store - Trace: ' . $e->getTraceAsString());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn'))
            return redirect()->to(url_to('login'));

        // Check if user is Admin, SuperAdmin, or Anggota Organisasi (level 0, 1, or 2)
        $level = session()->get('level');
        if (!in_array($level, [0, 2])) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $title = 'Edit Struktur';
        $data = $this->strukturTampilanModel->find($id);

        if (!$data) {
            return redirect()->to(url_to('struktur.index'))
                ->with('error', 'Struktur tidak ditemukan');
        }

        // Get organisasi berdasarkan user (jika level 0 atau 2)
        $organisasiId = session()->get('organisasi_id');
        $userId = session()->get('id');
        $organisasis = [];
        $currentOrganisasi = null;

        if ($level == 1) {
            // SuperAdmin bisa pilih semua organisasi
            $organisasis = $this->organisasiModel->findAll();
        } else {
            // Admin/Anggota: ambil data organisasi dari relasi users ke organisasis
            if ($userId) {
                $userModel = new \App\Models\UserModel();
                $user = $userModel->where('id', $userId)->first();
                $userOrgId = null;

                if ($user && isset($user['organisasi_id']) && !empty($user['organisasi_id'])) {
                    $userOrgId = $user['organisasi_id'];
                } elseif ($organisasiId) {
                    // Fallback: gunakan organisasi_id dari session
                    $userOrgId = $organisasiId;
                } elseif (isset($data['organisasi_id']) && !empty($data['organisasi_id'])) {
                    // Gunakan organisasi_id dari data struktur yang sedang diedit
                    $userOrgId = $data['organisasi_id'];
                }

                // Ambil data organisasi berdasarkan organisasi_id dari user
                if ($userOrgId) {
                    $currentOrganisasi = $this->organisasiModel->find($userOrgId);
                    if ($currentOrganisasi && !empty($currentOrganisasi)) {
                        $organisasis = [$currentOrganisasi];
                    }
                }
            }

            // Jika masih kosong, coba ambil dari data struktur yang sedang diedit
            if (empty($organisasis) && isset($data['organisasi_id']) && !empty($data['organisasi_id'])) {
                $orgFromData = $this->organisasiModel->find($data['organisasi_id']);
                if ($orgFromData && !empty($orgFromData)) {
                    $currentOrganisasi = $orgFromData;
                    $organisasis = [$orgFromData];
                }
            }
        }

        // Get unread count for notification badge (only for level 0 and 2)
        $unreadCount = 0;
        if (in_array($level, [0, 2])) {
            $unreadCount = $this->pesanModel->getUnreadCount();
        }

        return view('page/struktur/edit', compact('title', 'data', 'organisasis', 'currentOrganisasi', 'unreadCount'));
    }

    public function update($id)
    {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn'))
            return redirect()->to(url_to('login'));

        // Check if user is Admin, SuperAdmin, or Anggota Organisasi (level 0, 1, or 2)
        $level = session()->get('level');
        if (!in_array($level, [0, 2])) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $postData = $this->request->getPost();
        $existing = $this->strukturTampilanModel->find($id);

        if (!$existing) {
            return redirect()->to(url_to('struktur.index'))
                ->with('error', 'Data struktur tidak ditemukan');
        }

        // Validasi organisasi_id
        if (empty($postData['organisasi_id'])) {
            $postData['organisasi_id'] = session()->get('organisasi_id');
        }

        $organisasiId = (int) $postData['organisasi_id'];
        $organisasi = $this->organisasiModel->find($organisasiId);
        if (!$organisasi) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Organisasi tidak valid. Silakan pilih organisasi yang benar.');
        }

        // Handle upload gambar untuk 5 posisi
        $uploadPath = FCPATH . 'uploads/struktur/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        for ($i = 1; $i <= 6; $i++) {
            $file = $this->request->getFile('gambar_' . $i);
            if ($file && $file->isValid() && !$file->hasMoved()) {
                // Validasi file gambar
                if (
                    $file->getMimeType() === 'image/jpeg' ||
                    $file->getMimeType() === 'image/jpg' ||
                    $file->getMimeType() === 'image/png'
                ) {
                    // Hapus gambar lama jika ada
                    if (!empty($existing['gambar_' . $i]) && file_exists($uploadPath . $existing['gambar_' . $i])) {
                        @unlink($uploadPath . $existing['gambar_' . $i]);
                    }

                    $newName = $file->getRandomName();
                    if ($file->move($uploadPath, $newName)) {
                        $postData['gambar_' . $i] = $newName;
                    }
                }
            } else {
                // Jika tidak ada file baru, keep gambar lama
                $postData['gambar_' . $i] = $existing['gambar_' . $i] ?? null;
            }
        }

        // Prepare data untuk update
        $dataToUpdate = [
            'organisasi_id' => $organisasiId,
            'tahun' => trim($postData['tahun']),
            'is_active' => isset($postData['is_active']) ? (int) $postData['is_active'] : 0,
        ];

        // Add optional fields
        $optionalFields = [
            'periode',
            'nama_1',
            'jabatan_1',
            'gambar_1',
            'nama_2',
            'jabatan_2',
            'gambar_2',
            'nama_3',
            'jabatan_3',
            'gambar_3',
            'nama_4',
            'jabatan_4',
            'gambar_4',
            'nama_5',
            'jabatan_5',
            'gambar_5',
            'nama_6',
            'jabatan_6',
            'gambar_6',
            'prodi_1',
            'prodi_2',
            'prodi_3',
            'prodi_4',
            'prodi_5',
            'prodi_6',
        ];

        foreach ($optionalFields as $field) {
            if (isset($postData[$field]) && $postData[$field] !== '') {
                $dataToUpdate[$field] = trim($postData[$field]);
            } else {
                $dataToUpdate[$field] = null;
            }
        }

        // Jika is_active = 1, set yang lain menjadi 0 untuk organisasi dan tahun yang sama
        if ($dataToUpdate['is_active'] == 1) {
            $this->strukturTampilanModel->where('organisasi_id', $organisasiId)
                ->where('tahun', $dataToUpdate['tahun'])
                ->where('id !=', $id)
                ->set('is_active', 0)
                ->update();
        }

        // Update data menggunakan query builder langsung
        try {
            $db = \Config\Database::connect();
            $builder = $db->table('struktur_tampilan');
            $result = $builder->where('id', $id)->update($dataToUpdate);

            if ($result) {
                log_message('info', 'Struktur Update - Successfully updated ID: ' . $id);
                return redirect()->to(url_to('struktur.index'))
                    ->with('success', 'Struktur berhasil diupdate');
            } else {
                $dbError = $db->error();
                log_message('error', 'Struktur Update - Failed to update ID: ' . $id);
                log_message('error', 'Struktur Update - DB Error: ' . json_encode($dbError, JSON_UNESCAPED_UNICODE));

                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Gagal mengupdate data. Silakan coba lagi.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Struktur Update - Exception: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn'))
            return redirect()->to(url_to('login'));

        // Check if user is Admin, SuperAdmin, or Anggota Organisasi (level 0, 1, or 2)
        $level = session()->get('level');
        if (!in_array($level, [0, 1, 2])) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Hapus gambar sebelum delete
        $data = $this->strukturTampilanModel->find($id);
        if ($data) {
            $uploadPath = FCPATH . 'uploads/struktur/';
            for ($i = 1; $i <= 6; $i++) {
                if (!empty($data['gambar_' . $i]) && file_exists($uploadPath . $data['gambar_' . $i])) {
                    @unlink($uploadPath . $data['gambar_' . $i]);
                }
            }
        }

        $this->strukturTampilanModel->delete($id);

        return redirect()->to(url_to('struktur.index'))->with('success', 'Struktur deleted successfully');
    }

    public function createDivisi()
    {
        if (!session()->get('isLoggedIn'))
            return redirect()->to(url_to('login'));
        $level = session()->get('level');
        if (!in_array($level, [0, 2])) {
            return redirect()->to(url_to('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $title = 'Tambah Divisi';
        $userId = session()->get('id');
        $organisasiId = session()->get('organisasi_id');
        $organisasis = [];
        $currentOrganisasi = null;
        $userModel = new \App\Models\UserModel();

        // Retrieve organizations similar to create method
        if ($level == 1) {
            $organisasis = $this->organisasiModel->findAll();
        } else {
            if ($userId) {
                $user = $userModel->where('id', $userId)->first();
                $userOrgId = isset($user['organisasi_id']) ? $user['organisasi_id'] : $organisasiId;
                if ($userOrgId) {
                    $currentOrganisasi = $this->organisasiModel->find($userOrgId);
                    if ($currentOrganisasi)
                        $organisasis = [$currentOrganisasi];
                }
            }
        }

        $unreadCount = in_array($level, [0, 2]) ? $this->pesanModel->getUnreadCount() : 0;
        return view('page/struktur/create_divisi', compact('title', 'organisasis', 'currentOrganisasi', 'unreadCount'));
    }

    public function storeDivisi()
    {
        if (!session()->get('isLoggedIn'))
            return redirect()->to(url_to('login'));

        $level = session()->get('level');
        if (!in_array($level, [0, 2]))
            return redirect()->back()->with('error', 'Access Denied');

        $data = $this->request->getPost();

        // Validate Organization
        $organisasiId = $data['organisasi_id'] ?? session()->get('organisasi_id') ?? null;
        if (!$organisasiId) {
            // Fallback to user data
            $userId = session()->get('id');
            $userModel = new \App\Models\UserModel();
            $user = $userModel->where('id', $userId)->first();
            $organisasiId = $user['organisasi_id'] ?? null;
        }

        if (!$organisasiId) {
            return redirect()->back()->withInput()->with('error', 'Organisasi tidak ditemukan.');
        }

        // Check Max 3 Divisions Limit
        $tahun = $data['tahun'] ?? '';
        $existingCount = $this->strukturTampilanDivisiModel->countDivisions($organisasiId, $tahun);
        if ($existingCount >= 3) {
            return redirect()->back()->withInput()->with('error', 'Maksimal 3 divisi untuk tahun periode tersebut.');
        }

        // Process Images (Ketua + 8 Members)
        $uploadPath = FCPATH . 'uploads/struktur/divisi/';
        if (!is_dir($uploadPath))
            mkdir($uploadPath, 0777, true);

        // Process Ketua
        $fileKetua = $this->request->getFile('gambar_ketua');
        if ($fileKetua && $fileKetua->isValid() && !$fileKetua->hasMoved()) {
            $newName = $fileKetua->getRandomName();
            $fileKetua->move($uploadPath, $newName);
            $data['gambar_ketua'] = $newName;
        }

        // Process 8 Members
        for ($i = 1; $i <= 8; $i++) {
            $file = $this->request->getFile('gambar_anggota_' . $i);
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move($uploadPath, $newName);
                $data['gambar_anggota_' . $i] = $newName;
            }
        }

        // Add prodi_ketua manually if not handled by getPost() properly, usually it is.
        // The data variable comes from getPost(), so simple fields like prodi_* are already there.
        // We just need to make sure they are passed to save().
        // $data = $this->request->getPost() already has them.

        $data['organisasi_id'] = $organisasiId;

        $this->strukturTampilanDivisiModel->save($data);
        return redirect()->to(url_to('struktur.index'))->with('success', 'Divisi berhasil ditambahkan');
    }

    public function editDivisi($id)
    {
        if (!session()->get('isLoggedIn'))
            return redirect()->to(url_to('login'));
        $level = session()->get('level');
        if (!in_array($level, [0, 2]))
            return redirect()->back()->with('error', 'Access Denied');

        $data = $this->strukturTampilanDivisiModel->find($id);
        if (!$data)
            return redirect()->to(url_to('struktur.index'))->with('error', 'Data not found');

        $title = 'Edit Divisi';
        $unreadCount = in_array($level, [0, 2]) ? $this->pesanModel->getUnreadCount() : 0;

        // Populate organization data logic similar to edit method...
        // Simplified for brevity as usually organization is fixed for editing
        $organisasis = [];
        $currentOrganisasi = $this->organisasiModel->find($data['organisasi_id']);
        if ($currentOrganisasi)
            $organisasis = [$currentOrganisasi];

        return view('page/struktur/edit_divisi', compact('title', 'data', 'organisasis', 'currentOrganisasi', 'unreadCount'));
    }

    public function updateDivisi($id)
    {
        if (!session()->get('isLoggedIn'))
            return redirect()->to(url_to('login'));

        $level = session()->get('level');
        if (!in_array($level, [0, 2]))
            return redirect()->back()->with('error', 'Access Denied');

        $data = $this->request->getPost();
        $existing = $this->strukturTampilanDivisiModel->find($id);
        if (!$existing)
            return redirect()->back()->with('error', 'Data not found');

        $uploadPath = FCPATH . 'uploads/struktur/divisi/';
        if (!is_dir($uploadPath))
            mkdir($uploadPath, 0777, true);

        // Update Ketua Image
        $fileKetua = $this->request->getFile('gambar_ketua');
        if ($fileKetua && $fileKetua->isValid() && !$fileKetua->hasMoved()) {
            if (!empty($existing['gambar_ketua']) && file_exists($uploadPath . $existing['gambar_ketua'])) {
                @unlink($uploadPath . $existing['gambar_ketua']);
            }
            $newName = $fileKetua->getRandomName();
            $fileKetua->move($uploadPath, $newName);
            $data['gambar_ketua'] = $newName;
        } else {
            $data['gambar_ketua'] = $existing['gambar_ketua'];
        }

        // Update Members Images
        for ($i = 1; $i <= 8; $i++) {
            $file = $this->request->getFile('gambar_anggota_' . $i);
            if ($file && $file->isValid() && !$file->hasMoved()) {
                if (!empty($existing['gambar_anggota_' . $i]) && file_exists($uploadPath . $existing['gambar_anggota_' . $i])) {
                    @unlink($uploadPath . $existing['gambar_anggota_' . $i]);
                }
                $newName = $file->getRandomName();
                $file->move($uploadPath, $newName);
                $data['gambar_anggota_' . $i] = $newName;
            } else {
                $data['gambar_anggota_' . $i] = $existing['gambar_anggota_' . $i];
            }
        }

        $this->strukturTampilanDivisiModel->update($id, $data);
        return redirect()->to(url_to('struktur.index'))->with('success', 'Divisi berhasil diupdate');
    }

    public function deleteDivisi($id)
    {
        if (!session()->get('isLoggedIn'))
            return redirect()->to(url_to('login'));
        $level = session()->get('level');
        if (!in_array($level, [0, 2]))
            return redirect()->back();

        $data = $this->strukturTampilanDivisiModel->find($id);
        if ($data) {
            $uploadPath = FCPATH . 'uploads/struktur/divisi/';
            if (!empty($data['gambar_ketua']))
                @unlink($uploadPath . $data['gambar_ketua']);
            for ($i = 1; $i <= 8; $i++) {
                if (!empty($data['gambar_anggota_' . $i]))
                    @unlink($uploadPath . $data['gambar_anggota_' . $i]);
            }
        }
        $this->strukturTampilanDivisiModel->delete($id);
        return redirect()->to(url_to('struktur.index'))->with('success', 'Divisi deleted');
    }

    // --- VISI MISI ---

    public function createVisiMisi()
    {
        if (!session()->get('isLoggedIn'))
            return redirect()->to(url_to('login'));
        $level = session()->get('level');
        if (!in_array($level, [0, 2])) {
            return redirect()->to(url_to('dashboard'))->with('error', 'Access Denied');
        }

        $title = 'Tambah Visi Misi';
        $userId = session()->get('id');
        $organisasiId = session()->get('organisasi_id');
        $organisasis = [];
        $currentOrganisasi = null;
        $userModel = new \App\Models\UserModel();

        if ($level == 1) {
            $organisasis = $this->organisasiModel->findAll();
        } else {
            if ($userId) {
                $user = $userModel->where('id', $userId)->first();
                $userOrgId = isset($user['organisasi_id']) ? $user['organisasi_id'] : $organisasiId;
                if ($userOrgId) {
                    $currentOrganisasi = $this->organisasiModel->find($userOrgId);
                    if ($currentOrganisasi)
                        $organisasis = [$currentOrganisasi];
                }
            }
        }

        $unreadCount = in_array($level, [0, 2]) ? $this->pesanModel->getUnreadCount() : 0;
        return view('page/struktur/create_visi_misi', compact('title', 'organisasis', 'currentOrganisasi', 'unreadCount'));
    }

    public function storeVisiMisi()
    {
        if (!session()->get('isLoggedIn'))
            return redirect()->to(url_to('login'));

        $level = session()->get('level');
        if (!in_array($level, [0, 2]))
            return redirect()->back()->with('error', 'Access Denied');

        $data = $this->request->getPost();

        if (empty($data['organisasi_id'])) {
            // Fallback logic similar to storeDivisi
            $userId = session()->get('id');
            $userModel = new \App\Models\UserModel();
            $user = $userModel->where('id', $userId)->first();
            $data['organisasi_id'] = $user['organisasi_id'] ?? session()->get('organisasi_id');
        }

        if (empty($data['organisasi_id'])) {
            return redirect()->back()->withInput()->with('error', 'Organisasi Invalid');
        }

        // Jika is_active = 1, matikan yang lain
        if (isset($data['is_active']) && $data['is_active'] == 1) {
            $this->visiMisiModel->where('organisasi_id', $data['organisasi_id'])
                ->where('tahun', $data['tahun'])
                ->set('is_active', 0)
                ->update();
        }

        $this->visiMisiModel->save($data);
        return redirect()->to(url_to('struktur.index'))->with('success', 'Visi Misi berhasil ditambahkan');
    }

    public function editVisiMisi($id)
    {
        if (!session()->get('isLoggedIn'))
            return redirect()->to(url_to('login'));
        $level = session()->get('level');
        if (!in_array($level, [0, 2]))
            return redirect()->back()->with('error', 'Access Denied');

        $data = $this->visiMisiModel->find($id);
        if (!$data)
            return redirect()->to(url_to('struktur.index'))->with('error', 'Data not found');

        $title = 'Edit Visi Misi';
        $currentOrganisasi = $this->organisasiModel->find($data['organisasi_id']);
        $unreadCount = in_array($level, [0, 2]) ? $this->pesanModel->getUnreadCount() : 0;

        return view('page/struktur/edit_visi_misi', compact('title', 'data', 'currentOrganisasi', 'unreadCount'));
    }

    public function updateVisiMisi($id)
    {
        if (!session()->get('isLoggedIn'))
            return redirect()->to(url_to('login'));

        $level = session()->get('level');
        if (!in_array($level, [0, 2]))
            return redirect()->back()->with('error', 'Access Denied');

        $data = $this->request->getPost();

        if (isset($data['is_active']) && $data['is_active'] == 1) {
            $existing = $this->visiMisiModel->find($id);
            $this->visiMisiModel->where('organisasi_id', $existing['organisasi_id'])
                ->where('tahun', $data['tahun'])
                ->where('id !=', $id)
                ->set('is_active', 0)
                ->update();
        }

        $this->visiMisiModel->update($id, $data);
        return redirect()->to(url_to('struktur.index'))->with('success', 'Visi Misi updated');
    }

    public function deleteVisiMisi($id)
    {
        if (!session()->get('isLoggedIn'))
            return redirect()->to(url_to('login'));
        $level = session()->get('level');
        if (!in_array($level, [0, 2]))
            return redirect()->back();

        $this->visiMisiModel->delete($id);
        return redirect()->to(url_to('struktur.index'))->with('success', 'Visi Misi deleted');
    }

    // --- PROKER ---

    public function createProker()
    {
        if (!session()->get('isLoggedIn'))
            return redirect()->to(url_to('login'));
        $level = session()->get('level');
        if (!in_array($level, [0, 2]))
            return redirect()->back()->with('error', 'Access Denied');

        $title = 'Tambah Program Kerja';
        $userId = session()->get('id');
        $organisasiId = session()->get('organisasi_id');
        $organisasis = [];
        $currentOrganisasi = null;
        $userModel = new \App\Models\UserModel();

        if ($level == 1) {
            $organisasis = $this->organisasiModel->findAll();
        } else {
            if ($userId) {
                $user = $userModel->where('id', $userId)->first();
                $userOrgId = isset($user['organisasi_id']) ? $user['organisasi_id'] : $organisasiId;
                if ($userOrgId) {
                    $currentOrganisasi = $this->organisasiModel->find($userOrgId);
                    if ($currentOrganisasi)
                        $organisasis = [$currentOrganisasi];
                }
            }
        }

        $unreadCount = in_array($level, [0, 2]) ? $this->pesanModel->getUnreadCount() : 0;
        return view('page/struktur/create_proker', compact('title', 'organisasis', 'currentOrganisasi', 'unreadCount'));
    }

    public function storeProker()
    {
        if (!session()->get('isLoggedIn'))
            return redirect()->to(url_to('login'));

        $level = session()->get('level');
        if (!in_array($level, [0, 2]))
            return redirect()->back()->with('error', 'Access Denied');
        $data = $this->request->getPost();

        // Handle JSON data
        $prokerList = [];
        $totalDanaBerkurang = 0;

        if (isset($data['proker']) && is_array($data['proker'])) {
            foreach ($data['proker'] as $key => $program) {
                if (!empty($program)) {
                    $dana = isset($data['dana_berkurang'][$key]) ? str_replace('.', '', $data['dana_berkurang'][$key]) : 0;
                    $totalDanaBerkurang += (float) $dana;

                    $prokerList[] = [
                        'program' => $program,
                        'status' => $data['status_proker'][$key] ?? 'Coming soon',
                        'dana_berkurang' => $dana,
                        'link_berita' => $data['link_berita'][$key] ?? '',
                        'keterangan' => $data['keterangan'][$key] ?? ''
                    ];
                }
            }
        }
        $data['deskripsi'] = json_encode($prokerList);
        $data['dana_berkurang'] = $totalDanaBerkurang;

        // Use default global status if not provided
        if (!isset($data['is_active'])) {
            $data['is_active'] = 'Coming soon';
        }

        $this->prokerModel->save($data);
        return redirect()->to(url_to('struktur.index'))->with('success', 'Program Kerja berhasil ditambahkan');
    }

    public function editProker($id)
    {
        if (!session()->get('isLoggedIn'))
            return redirect()->to(url_to('login'));
        $level = session()->get('level');
        if (!in_array($level, [0, 2]))
            return redirect()->back()->with('error', 'Access Denied');

        $data = $this->prokerModel->find($id);
        if (!$data)
            return redirect()->to(url_to('struktur.index'))->with('error', 'Data not found');

        $title = 'Edit Program Kerja';
        $currentOrganisasi = $this->organisasiModel->find($data['organisasi_id']);
        $unreadCount = in_array($level, [0, 2]) ? $this->pesanModel->getUnreadCount() : 0;

        return view('page/struktur/edit_proker', compact('title', 'data', 'currentOrganisasi', 'unreadCount'));
    }

    public function updateProker($id)
    {
        if (!session()->get('isLoggedIn'))
            return redirect()->to(url_to('login'));

        $level = session()->get('level');
        if (!in_array($level, [0, 2]))
            return redirect()->back()->with('error', 'Access Denied');
        $data = $this->request->getPost();

        $prokerList = [];
        $totalDanaBerkurang = 0;

        if (isset($data['proker']) && is_array($data['proker'])) {
            foreach ($data['proker'] as $key => $program) {
                if (!empty($program)) {
                    $dana = isset($data['dana_berkurang'][$key]) ? str_replace('.', '', $data['dana_berkurang'][$key]) : 0;
                    $totalDanaBerkurang += (float) $dana;

                    $prokerList[] = [
                        'program' => $program,
                        'status' => $data['status_proker'][$key] ?? 'Coming soon',
                        'dana_berkurang' => $dana,
                        'link_berita' => $data['link_berita'][$key] ?? '',
                        'keterangan' => $data['keterangan'][$key] ?? ''
                    ];
                }
            }
        }
        $data['deskripsi'] = json_encode($prokerList);
        $data['dana_berkurang'] = $totalDanaBerkurang;

        // Note: is_active is now a string enum ('Coming soon', 'Progres', 'Finish')
        // If not provided in form, we don't update it to preserve existing or use default
        if (!isset($data['is_active'])) {
            // We can omit it or set a default. Usually better to preserve existing.
            // But for new ones (store), we set default.
        }

        $this->prokerModel->update($id, $data);
        return redirect()->to(url_to('struktur.index'))->with('success', 'Program Kerja updated');
    }

    public function deleteProker($id)
    {
        if (!session()->get('isLoggedIn'))
            return redirect()->to(url_to('login'));

        $level = session()->get('level');
        if (!in_array($level, [0, 2]))
            return redirect()->back()->with('error', 'Access Denied');
        $this->prokerModel->delete($id);
        return redirect()->to(url_to('struktur.index'))->with('success', 'Program Kerja deleted');
    }

    // --- ANGGARAN METHODS ---

    public function createAnggaran()
    {
        if (!session()->get('isLoggedIn'))
            return redirect()->to(url_to('login'));
        $level = session()->get('level');
        if (!in_array($level, [0, 2]))
            return redirect()->back()->with('error', 'Access Denied');

        $title = 'Tambah Anggaran';
        $userId = session()->get('id');
        $organisasiId = session()->get('organisasi_id');
        $organisasis = [];
        $currentOrganisasi = null;

        if ($level == 1) {
            $organisasis = $this->organisasiModel->findAll();
        } else {
            $userOrgId = session()->get('organisasi_id');
            if ($userOrgId) {
                $currentOrganisasi = $this->organisasiModel->find($userOrgId);
                if ($currentOrganisasi)
                    $organisasis = [$currentOrganisasi];
            } else {
                // Fallback to user model if session doesn't have it
                $userModel = new \App\Models\UserModel();
                $user = $userModel->find(session()->get('id'));
                if ($user && isset($user['organisasi_id'])) {
                    $currentOrganisasi = $this->organisasiModel->find($user['organisasi_id']);
                    if ($currentOrganisasi)
                        $organisasis = [$currentOrganisasi];
                }
            }
        }

        $unreadCount = in_array($level, [0, 2]) ? $this->pesanModel->getUnreadCount() : 0;
        return view('page/struktur/create_anggaran', compact('title', 'organisasis', 'currentOrganisasi', 'unreadCount'));
    }

    public function storeAnggaran()
    {
        if (!session()->get('isLoggedIn'))
            return redirect()->to(url_to('login'));

        $data = $this->request->getPost();
        // Clean formatted Rupiah (strip dots)
        if (isset($data['jumlah'])) {
            $data['jumlah'] = str_replace('.', '', $data['jumlah']);
        }

        $level = session()->get('level');
        $userOrgId = session()->get('organisasi_id');

        // Security: Prevent non-SuperAdmin from adding to other orgs
        if ($level != 1 && $data['organisasi_id'] != $userOrgId) {
            return redirect()->back()->with('error', 'Unauthorized access to organization');
        }

        if (!$this->anggaranModel->save($data)) {
            return redirect()->back()->withInput()->with('errors', $this->anggaranModel->errors());
        }

        return redirect()->to(url_to('struktur.index'))->with('success', 'Anggaran berhasil disimpan');
    }

    public function editAnggaran($id)
    {
        if (!session()->get('isLoggedIn'))
            return redirect()->to(url_to('login'));
        $level = session()->get('level');
        if (!in_array($level, [0, 2]))
            return redirect()->back()->with('error', 'Access Denied');

        $data = $this->anggaranModel->find($id);
        if (!$data)
            return redirect()->to(url_to('struktur.index'))->with('error', 'Data not found');

        // Security: Check if user belongs to this org
        if ($level != 1 && $data['organisasi_id'] != session()->get('organisasi_id')) {
            return redirect()->to(url_to('struktur.index'))->with('error', 'Access Denied');
        }

        $title = 'Edit Anggaran';
        $currentOrganisasi = $this->organisasiModel->find($data['organisasi_id']);
        $unreadCount = in_array($level, [0, 2]) ? $this->pesanModel->getUnreadCount() : 0;

        return view('page/struktur/edit_anggaran', compact('title', 'data', 'currentOrganisasi', 'unreadCount'));
    }

    public function updateAnggaran($id)
    {
        if (!session()->get('isLoggedIn'))
            return redirect()->to(url_to('login'));

        $level = session()->get('level');
        $existing = $this->anggaranModel->find($id);
        if (!$existing)
            return redirect()->to(url_to('struktur.index'))->with('error', 'Data not found');

        // Security
        if ($level != 1 && $existing['organisasi_id'] != session()->get('organisasi_id')) {
            return redirect()->to(url_to('struktur.index'))->with('error', 'Access Denied');
        }

        $data = $this->request->getPost();
        // Clean formatted Rupiah (strip dots)
        if (isset($data['jumlah'])) {
            $data['jumlah'] = str_replace('.', '', $data['jumlah']);
        }

        // Force organization_id to match existing if not SuperAdmin
        if ($level != 1)
            $data['organisasi_id'] = $existing['organisasi_id'];

        if (!$this->anggaranModel->update($id, $data)) {
            return redirect()->back()->withInput()->with('errors', $this->anggaranModel->errors());
        }

        return redirect()->to(url_to('struktur.index'))->with('success', 'Anggaran berhasil diupdate');
    }

    public function deleteAnggaran($id)
    {
        if (!session()->get('isLoggedIn'))
            return redirect()->to(url_to('login'));

        $level = session()->get('level');
        $existing = $this->anggaranModel->find($id);
        if (!$existing)
            return redirect()->to(url_to('struktur.index'))->with('error', 'Data not found');

        // Security
        if ($level != 1 && $existing['organisasi_id'] != session()->get('organisasi_id')) {
            return redirect()->to(url_to('struktur.index'))->with('error', 'Access Denied');
        }

        $this->anggaranModel->delete($id);
        return redirect()->to(url_to('struktur.index'))->with('success', 'Anggaran berhasil dihapus');
    }
}

