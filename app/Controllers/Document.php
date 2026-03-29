<?php

namespace App\Controllers;

use App\Models\DocumentModel;
use App\Models\OrganisasiModel;
use CodeIgniter\API\ResponseTrait;

class Document extends BaseController
{
    use ResponseTrait;

    protected $documentModel;
    protected $organisasiModel;
    protected $pesanModel;

    public function __construct()
    {
        $this->documentModel = new DocumentModel();
        $this->organisasiModel = new OrganisasiModel();
        $this->pesanModel = new \App\Models\PesanModel();
        helper(['form', 'url']);
    }

    // Fungsi helper untuk pengecekan login
    private function checkLogin()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(url_to('login'))->with('error', 'Silakan login terlebih dahulu');
        }
        return null;
    }

    public function index()
    {
        if ($redirect = $this->checkLogin())
            return $redirect;

        $level = session()->get('level');

        // Level 1 (SuperAdmin) tidak memiliki akses ke Document
        if ($level == 1) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        $userId = session()->get('id');
        $selectedYear = $this->request->getGet('tahun') ?? date('Y');
        $selectedKategori = $this->request->getGet('kategori') ?? null;

        // Get available years
        $availableYears = $this->documentModel->getAvailableYears($level, $userId);

        // Get documents with filters
        $documents = $this->documentModel->getAllDocuments($level, $userId, $selectedYear, $selectedKategori);

        // Get statistics for level 1 and 2
        $stats = [];
        if (in_array($level, [1, 2])) {
            $stats = [
                'total' => $this->documentModel->getTotalDocumentCount($level, $userId),
                'byCategory' => $this->documentModel->getDocumentCountByCategory($level, $userId),
                'monthlyData' => $this->documentModel->getMonthlyDocumentCount($selectedYear, $level, $userId),
            ];
        }

        // Set title berdasarkan kategori
        $title = 'Arsip Document';
        if ($selectedKategori && !empty($selectedKategori)) {
            $title = 'Arsip Document - ' . $selectedKategori;
        }

        // Get unread count for notification badge (only for level 0 and 2)
        $unreadCount = 0;
        if (in_array($level, [0, 2])) {
            $unreadCount = $this->pesanModel->getUnreadCount();
        }

        $data = [
            'title' => $title,
            'documents' => $documents ?? [],
            'availableYears' => $availableYears ?? [],
            'selectedYear' => $selectedYear ?? date('Y'),
            'selectedKategori' => $selectedKategori ?? null,
            'level' => $level ?? 0,
            'stats' => $stats ?? [],
            'organisasi' => $this->organisasiModel->findAll(),
            'unreadCount' => $unreadCount,
        ];

        return view('page/document/index', $data);
    }

    public function create()
    {
        if ($redirect = $this->checkLogin())
            return $redirect;

        // Level 1 (SuperAdmin) tidak memiliki akses
        $level = session()->get('level');
        if ($level == 1) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Get kategori from URL parameter if exists
        $kategori = $this->request->getGet('kategori') ?? null;

        // Get unread count for notification badge (only for level 0 and 2)
        $level = session()->get('level');
        $unreadCount = 0;
        if (in_array($level, [0, 2])) {
            $unreadCount = $this->pesanModel->getUnreadCount();
        }

        $data = [
            'title' => 'Tambah Document',
            'validation' => \Config\Services::validation(),
            'organisasi' => $this->organisasiModel->findAll(),
            'selectedKategori' => $kategori,
            'unreadCount' => $unreadCount,
        ];

        return view('page/document/create', $data);
    }

    public function store()
    {
        if ($redirect = $this->checkLogin())
            return $redirect;

        // Level 1 (SuperAdmin) tidak memiliki akses
        $level = session()->get('level');
        if ($level == 1) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $rules = [
            'judul' => 'required|max_length[255]',
            'kategori' => 'required|in_list[PB,CA,PRS,POA,KPI,AD/ART]',
            'deskripsi' => 'permit_empty',
            'tahun' => 'required|numeric|min_length[4]|max_length[4]',
            'file' => [
                'rules' => 'uploaded[file]|max_size[file,10240]|mime_in[file,application/pdf,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document]',
                'errors' => [
                    'uploaded' => 'Pilih file document terlebih dahulu',
                    'max_size' => 'Ukuran file maksimal 10MB',
                    'mime_in' => 'Format file harus PDF, PPT, PPTX, DOC, atau DOCX',
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $file = $this->request->getFile('file');

        if ($file->isValid() && !$file->hasMoved()) {
            $fileName = $file->getRandomName();
            $filePath = 'uploads/document/';

            // Create directory if not exists
            if (!is_dir(FCPATH . $filePath)) {
                mkdir(FCPATH . $filePath, 0777, true);
            }

            $file->move(FCPATH . $filePath, $fileName);

            $data = [
                'judul' => $this->request->getPost('judul'),
                'kategori' => $this->request->getPost('kategori'),
                'deskripsi' => $this->request->getPost('deskripsi'),
                'tahun' => $this->request->getPost('tahun'),
                'file_path' => $filePath . $fileName,
                'file_name' => $file->getClientName(),
                'file_size' => $file->getSize(),
                'file_type' => $file->getClientMimeType(),
                'organisasi_id' => null,
                'user_id' => session()->get('id'),
                'user_name' => session()->get('name'),
                'user_email' => session()->get('email'),
                'user_level' => session()->get('level'),
            ];

            if ($this->documentModel->save($data)) {
                session()->setFlashdata('pesan', 'Document berhasil ditambahkan.');
                return redirect()->to(url_to('document.index'));
            } else {
                session()->setFlashdata('error', 'Gagal menyimpan document.');
                return redirect()->back()->withInput();
            }
        } else {
            session()->setFlashdata('error', 'Gagal mengupload file.');
            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        if ($redirect = $this->checkLogin())
            return $redirect;

        // Level 1 (SuperAdmin) tidak memiliki akses
        $level = session()->get('level');
        if ($level == 1) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $document = $this->documentModel->getDocumentById($id);

        if (!$document) {
            session()->setFlashdata('error', 'Document tidak ditemukan.');
            return redirect()->to(url_to('document.index'));
        }

        // Check permission: level 0 hanya bisa edit dokumen sendiri
        $level = session()->get('level');
        $userId = session()->get('id');
        if ($level == 0 && $document['user_id'] != $userId) {
            session()->setFlashdata('error', 'Anda tidak memiliki akses untuk mengedit document ini.');
            return redirect()->to(url_to('document.index'));
        }

        // Get unread count for notification badge (only for level 0 and 2)
        $unreadCount = 0;
        if (in_array($level, [0, 2])) {
            $unreadCount = $this->pesanModel->getUnreadCount();
        }

        $data = [
            'title' => 'Edit Document',
            'document' => $document,
            'validation' => \Config\Services::validation(),
            'organisasi' => $this->organisasiModel->findAll(),
            'unreadCount' => $unreadCount,
        ];

        return view('page/document/edit', $data);
    }

    public function update($id)
    {
        if ($redirect = $this->checkLogin())
            return $redirect;

        // Level 1 (SuperAdmin) tidak memiliki akses
        $level = session()->get('level');
        if ($level == 1) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $document = $this->documentModel->getDocumentById($id);

        if (!$document) {
            session()->setFlashdata('error', 'Document tidak ditemukan.');
            return redirect()->to(url_to('document.index'));
        }

        // Check permission
        $level = session()->get('level');
        $userId = session()->get('id');
        if ($level == 0 && $document['user_id'] != $userId) {
            session()->setFlashdata('error', 'Anda tidak memiliki akses untuk mengedit document ini.');
            return redirect()->to(url_to('document.index'));
        }

        $rules = [
            'judul' => 'required|max_length[255]',
            'kategori' => 'required|in_list[PB,CA,PRS,POA,KPI,AD/ART]',
            'deskripsi' => 'permit_empty',
            'tahun' => 'required|numeric|min_length[4]|max_length[4]',
            'file' => [
                'rules' => 'max_size[file,10240]|mime_in[file,application/pdf,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document]',
                'errors' => [
                    'max_size' => 'Ukuran file maksimal 10MB',
                    'mime_in' => 'Format file harus PDF, PPT, PPTX, DOC, atau DOCX',
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $file = $this->request->getFile('file');
        $filePath = $document['file_path'];
        $fileName = $document['file_name'];
        $fileSize = $document['file_size'];
        $fileType = $document['file_type'];

        // Update file if new file is uploaded
        if ($file->isValid() && !$file->hasMoved()) {
            // Delete old file
            if (file_exists(FCPATH . $document['file_path'])) {
                unlink(FCPATH . $document['file_path']);
            }

            $newFileName = $file->getRandomName();
            $filePath = 'uploads/document/';

            if (!is_dir(FCPATH . $filePath)) {
                mkdir(FCPATH . $filePath, 0777, true);
            }

            $file->move(FCPATH . $filePath, $newFileName);

            $filePath = $filePath . $newFileName;
            $fileName = $file->getClientName();
            $fileSize = $file->getSize();
            $fileType = $file->getClientMimeType();
        }

        $data = [
            'id' => $id,
            'judul' => $this->request->getPost('judul'),
            'kategori' => $this->request->getPost('kategori'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'tahun' => $this->request->getPost('tahun'),
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_size' => $fileSize,
            'file_type' => $fileType,
            'organisasi_id' => null,
        ];

        if ($this->documentModel->save($data)) {
            session()->setFlashdata('pesan', 'Document berhasil diupdate.');
            return redirect()->to(url_to('document.index'));
        } else {
            session()->setFlashdata('error', 'Gagal mengupdate document.');
            return redirect()->back()->withInput();
        }
    }

    public function delete($id)
    {
        if ($redirect = $this->checkLogin())
            return $redirect;

        // Level 1 (SuperAdmin) tidak memiliki akses
        $level = session()->get('level');
        if ($level == 1) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $document = $this->documentModel->getDocumentById($id);

        if (!$document) {
            session()->setFlashdata('error', 'Document tidak ditemukan.');
            return redirect()->to(url_to('document.index'));
        }

        // Check permission: level 0 hanya bisa delete dokumen sendiri, level 1,2 bisa delete semua
        $level = session()->get('level');
        $userId = session()->get('id');
        if ($level == 0 && $document['user_id'] != $userId) {
            session()->setFlashdata('error', 'Anda tidak memiliki akses untuk menghapus document ini.');
            return redirect()->to(url_to('document.index'));
        }

        // Delete file
        if (file_exists(FCPATH . $document['file_path'])) {
            unlink(FCPATH . $document['file_path']);
        }

        if ($this->documentModel->delete($id)) {
            session()->setFlashdata('pesan', 'Document berhasil dihapus.');
        } else {
            session()->setFlashdata('error', 'Gagal menghapus document.');
        }

        return redirect()->to(url_to('document.index'));
    }

    public function download($id)
    {
        if ($redirect = $this->checkLogin())
            return $redirect;

        $document = $this->documentModel->getDocumentById($id);

        if (!$document) {
            session()->setFlashdata('error', 'Document tidak ditemukan.');
            return redirect()->to(url_to('document.index'));
        }

        $filePath = FCPATH . $document['file_path'];

        if (!file_exists($filePath)) {
            session()->setFlashdata('error', 'File tidak ditemukan.');
            return redirect()->to(url_to('document.index'));
        }

        return $this->response->download($filePath, null);
    }

    // API Methods for Statistics
    public function getMonthlyData()
    {
        $level = session()->get('level');
        $userId = session()->get('id');
        $year = $this->request->getGet('year') ?? date('Y');

        $monthlyData = $this->documentModel->getMonthlyDocumentCount($year, $level, $userId);

        // Format data untuk semua bulan
        $result = [];
        for ($month = 1; $month <= 12; $month++) {
            $found = false;
            foreach ($monthlyData as $data) {
                if ($data['month'] == $month) {
                    $result[] = $data;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $result[] = ['month' => $month, 'count' => 0, 'year' => $year];
            }
        }

        return $this->respond($result);
    }

    public function getAvailableYears()
    {
        $level = session()->get('level');
        $userId = session()->get('id');

        $years = $this->documentModel->getAvailableYears($level, $userId);
        return $this->respond($years);
    }

    public function getCategoryDistribution()
    {
        $level = session()->get('level');
        $userId = session()->get('id');

        $data = $this->documentModel->getDocumentCountByCategory($level, $userId);
        return $this->respond($data);
    }
}

