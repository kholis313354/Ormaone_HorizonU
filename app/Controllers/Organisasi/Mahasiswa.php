<?php 
// app/Controllers/Organisasi/Mahasiswa.php

namespace App\Controllers\Organisasi;

use App\Controllers\BaseController;
use App\Models\MahasiswaModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\API\ResponseTrait;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class Mahasiswa extends BaseController
{
    use ResponseTrait;
    
    protected $mahasiswaModel;

    public function __construct()
    {
        $this->mahasiswaModel = new MahasiswaModel();
    }

    // Fungsi helper untuk pengecekan login dan level akses
    private function checkAuth()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(url_to('login'))->with('error', 'Silakan login terlebih dahulu');
        }
        
        $level = session()->get('level');
        if (!in_array($level, [1, 2])) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        return null;
    }

    public function index()
    {
        if ($redirect = $this->checkAuth()) return $redirect;
        
        $title = 'Data Mahasiswa';
        $data = [
            'title' => 'Data Mahasiswa',
            'data' => $this->mahasiswaModel->orderBy('created_at', 'DESC')->findAll(),
        ];

        return view('page/organisasi/mahasiswa/index', $data);
    }

    public function create()
    {
        if ($redirect = $this->checkAuth()) return $redirect;
        
        $data = [
            'title' => 'Tambah Mahasiswa',
            'validation' => \Config\Services::validation(),
        ];

        return view('page/organisasi/mahasiswa/create', $data);
    }

    public function store()
    {
        if ($redirect = $this->checkAuth()) return $redirect;
        
        // Validasi input
        if (!$this->validate($this->mahasiswaModel->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
            'nim' => $this->request->getPost('nim'),
            'email' => $this->request->getPost('email'),
            'status' => $this->request->getPost('status'),
        ];

        $this->mahasiswaModel->save($data);

        // Redirect ke halaman index dengan flash message
        return redirect()->to(url_to('organisasi.mahasiswa.index'))->with('message', 'Data mahasiswa berhasil ditambahkan');
    }

    public function update($id)
    {
        if ($redirect = $this->checkAuth()) return $redirect;
        
        // Cek apakah data ada
        $mahasiswa = $this->mahasiswaModel->find($id);
        if (!$mahasiswa) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Validasi dasar untuk semua field
        $rules = [
            'nama' => 'required|min_length[3]|max_length[255]',
            'status' => 'required|in_list[aktif,nonaktif]'
        ];

        // Validasi khusus NIM jika diubah
        if ($this->request->getPost('nim') !== $mahasiswa['nim']) {
            $rules['nim'] = 'required|min_length[5]|max_length[20]|is_unique[mahasiswa.nim]';
        }

        // Validasi khusus Email jika diubah
        if ($this->request->getPost('email') !== $mahasiswa['email']) {
            $rules['email'] = 'required|valid_email|is_unique[mahasiswa.email]';
        }

        // Jalankan validasi
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Siapkan data untuk update
        $data = ['id' => $id];
        
        // Hanya update field yang diubah
        if ($this->request->getPost('nama') !== $mahasiswa['nama']) {
            $data['nama'] = $this->request->getPost('nama');
        }
        
        if ($this->request->getPost('nim') !== $mahasiswa['nim']) {
            $data['nim'] = $this->request->getPost('nim');
        }
        
        if ($this->request->getPost('email') !== $mahasiswa['email']) {
            $data['email'] = $this->request->getPost('email');
        }
        
        if ($this->request->getPost('status') !== $mahasiswa['status']) {
            $data['status'] = $this->request->getPost('status');
        }

        // Jika ada perubahan, lakukan update
        if (count($data) > 1) { // Lebih dari 1 karena ada 'id'
            $data['updated_at'] = date('Y-m-d H:i:s');
            $this->mahasiswaModel->save($data);
            return redirect()->to(url_to('organisasi.mahasiswa.index'))->with('message', 'Data berhasil diupdate');
        }

        // Jika tidak ada perubahan
        return redirect()->to(url_to('organisasi.mahasiswa.index'))->with('message', 'Tidak ada perubahan data');
    }

    public function edit($id)
    {
        if ($redirect = $this->checkAuth()) return $redirect;
        
        // Cari data mahasiswa
        $mahasiswa = $this->mahasiswaModel->find($id);
        
        // Jika data tidak ditemukan
        if (!$mahasiswa) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Edit Data Mahasiswa',
            'mahasiswa' => $mahasiswa,
            'validation' => \Config\Services::validation()
        ];

        return view('page/organisasi/mahasiswa/edit', $data);
    }

    public function delete($id)
    {
        if ($redirect = $this->checkAuth()) return $redirect;
        
        // Cek apakah data ada
        $mahasiswa = $this->mahasiswaModel->find($id);
        if (!$mahasiswa) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Hapus data
        $this->mahasiswaModel->delete($id);

        // Redirect ke index dengan pesan sukses
        return redirect()->to(url_to('organisasi.mahasiswa.index'))->with('message', 'Data mahasiswa berhasil dihapus');
    }

    /**
     * Bulk delete mahasiswa
     */
    public function bulkDelete()
    {
        if ($redirect = $this->checkAuth()) return $redirect;
        
        // Hanya level 1 dan 2 yang bisa bulk delete
        if (!in_array(session()->get('level'), [1, 2])) {
            session()->setFlashdata('error', 'Anda tidak memiliki akses untuk menghapus mahasiswa.');
            return redirect()->to(url_to('organisasi.mahasiswa.index'));
        }
        
        $ids = $this->request->getPost('ids');
        
        if (empty($ids) || !is_array($ids)) {
            session()->setFlashdata('error', 'Tidak ada mahasiswa yang dipilih untuk dihapus.');
            return redirect()->to(url_to('organisasi.mahasiswa.index'));
        }
        
        $successCount = 0;
        $errorCount = 0;
        
        foreach ($ids as $id) {
            try {
                $mahasiswa = $this->mahasiswaModel->find($id);
                
                if (!$mahasiswa) {
                    $errorCount++;
                    continue;
                }
                
                // Hapus dari database
                if ($this->mahasiswaModel->delete($id)) {
                    $successCount++;
                } else {
                    $errorCount++;
                }
            } catch (\Exception $e) {
                log_message('error', 'Bulk delete mahasiswa error: ' . $e->getMessage());
                $errorCount++;
            }
        }
        
        if ($successCount > 0) {
            session()->setFlashdata('pesan', "Berhasil menghapus {$successCount} mahasiswa." . ($errorCount > 0 ? " {$errorCount} mahasiswa gagal dihapus." : ''));
        } else {
            session()->setFlashdata('error', 'Gagal menghapus mahasiswa yang dipilih.');
        }
        
        return redirect()->to(url_to('organisasi.mahasiswa.index'));
    }

    /**
     * Download template Excel untuk import mahasiswa dengan dropdown
     */
    public function downloadTemplate()
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        // Buat spreadsheet baru
        $spreadsheet = new Spreadsheet();
        
        // Sheet 1: Data Template
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Mahasiswa');
        
        // Header
        $sheet->setCellValue('A1', 'Nama');
        $sheet->setCellValue('B1', 'NIM');
        $sheet->setCellValue('C1', 'Email');
        $sheet->setCellValue('D1', 'Status');
        
        // Style header
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4']
            ],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ];
        $sheet->getStyle('A1:D1')->applyFromArray($headerStyle);
        
        // Set column width
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(35);
        $sheet->getColumnDimension('D')->setWidth(15);
        
        // Contoh data
        $sheet->setCellValue('A2', 'John Doe');
        $sheet->setCellValue('B2', '1234567890');
        $sheet->setCellValue('C2', 'john.doe@example.com');
        $sheet->setCellValue('D2', 'aktif');
        
        $sheet->setCellValue('A3', 'Jane Smith');
        $sheet->setCellValue('B3', '0987654321');
        $sheet->setCellValue('C3', 'jane.smith@example.com');
        $sheet->setCellValue('D3', 'aktif');
        
        $sheet->setCellValue('A4', 'Ahmad Fauzi');
        $sheet->setCellValue('B4', '1122334455');
        $sheet->setCellValue('C4', 'ahmad.fauzi@example.com');
        $sheet->setCellValue('D4', 'nonaktif');
        
        // Sheet 2: Data Status (untuk dropdown)
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('List Status');
        $sheet2->setCellValue('A1', 'Status');
        $sheet2->getStyle('A1')->applyFromArray($headerStyle);
        $sheet2->setCellValue('A2', 'aktif');
        $sheet2->setCellValue('A3', 'nonaktif');
        $sheet2->getColumnDimension('A')->setWidth(20);
        
        // Kembali ke sheet utama
        $spreadsheet->setActiveSheetIndex(0);
        
        // Tambahkan data validation (dropdown) untuk kolom D (Status)
        $validation = $sheet->getCell('D2')->getDataValidation();
        $validation->setType(DataValidation::TYPE_LIST);
        $validation->setErrorStyle(DataValidation::STYLE_STOP);
        $validation->setAllowBlank(false);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setShowDropDown(true);
        $validation->setErrorTitle('Input error');
        $validation->setError('Nilai tidak valid. Pilih dari dropdown: aktif atau nonaktif.');
        $validation->setPromptTitle('Pilih Status');
        $validation->setPrompt('Silakan pilih status dari dropdown: aktif atau nonaktif.');
        $validation->setFormula1('=\'List Status\'!$A$2:$A$3');
        
        // Copy validation ke baris lain (maksimal 5000 baris untuk support 2000+ data)
        for ($i = 2; $i <= 5000; $i++) {
            $sheet->getCell('D' . $i)->setDataValidation(clone $validation);
        }
        
        // Freeze header row
        $sheet->freezePane('A2');
        
        // Output file
        $filename = 'template_import_mahasiswa_' . date('Y-m-d') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * Import batch mahasiswa dari Excel/CSV
     */
    public function importBatch()
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        try {
            // Set waktu eksekusi lebih lama untuk proses import yang mungkin memakan waktu
            set_time_limit(300); // 5 menit
            
            if (!$this->request->isAJAX()) {
                return $this->fail('Request harus menggunakan AJAX', 400);
            }

            $jsonData = $this->request->getJSON(true);
            
            if (!isset($jsonData['data']) || !is_array($jsonData['data'])) {
                return $this->fail('Data tidak valid', 400);
            }

            $importData = $jsonData['data'];

            if (empty($importData)) {
                return $this->fail('Tidak ada data untuk diimport', 400);
            }

            $successCount = 0;
            $errorCount = 0;
            $errors = [];
            $createdIds = [];
            $skippedCount = 0;

            // Ambil semua NIM dan Email yang sudah ada untuk validasi cepat
            $existingNims = $this->mahasiswaModel->select('nim')->findAll();
            $existingEmails = $this->mahasiswaModel->select('email')->findAll();
            $existingNimList = array_column($existingNims, 'nim');
            $existingEmailList = array_column($existingEmails, 'email');

            foreach ($importData as $index => $row) {
                try {
                    // Validasi data
                    if (empty($row['nama']) || empty($row['nim']) || empty($row['email']) || empty($row['status'])) {
                        $errors[] = "Baris " . ($index + 2) . ": Data tidak lengkap";
                        $errorCount++;
                        continue;
                    }

                    // Validasi status
                    if (!in_array(strtolower($row['status']), ['aktif', 'nonaktif'])) {
                        $errors[] = "Baris " . ($index + 2) . ": Status tidak valid. Harus 'aktif' atau 'nonaktif'";
                        $errorCount++;
                        continue;
                    }

                    // Validasi email format
                    if (!filter_var($row['email'], FILTER_VALIDATE_EMAIL)) {
                        $errors[] = "Baris " . ($index + 2) . ": Format email tidak valid";
                        $errorCount++;
                        continue;
                    }

                    // Cek duplikasi NIM
                    if (in_array($row['nim'], $existingNimList)) {
                        $errors[] = "Baris " . ($index + 2) . ": NIM '{$row['nim']}' sudah terdaftar";
                        $errorCount++;
                        $skippedCount++;
                        continue;
                    }

                    // Cek duplikasi Email
                    if (in_array($row['email'], $existingEmailList)) {
                        $errors[] = "Baris " . ($index + 2) . ": Email '{$row['email']}' sudah terdaftar";
                        $errorCount++;
                        $skippedCount++;
                        continue;
                    }

                    // Simpan data mahasiswa
                    $data = [
                        'nama' => trim($row['nama']),
                        'nim' => trim($row['nim']),
                        'email' => trim(strtolower($row['email'])),
                        'status' => strtolower(trim($row['status'])),
                    ];

                    if (!$this->mahasiswaModel->save($data)) {
                        $errors[] = "Baris " . ($index + 2) . ": Gagal menyimpan data - " . implode(', ', $this->mahasiswaModel->errors());
                        $errorCount++;
                        continue;
                    }

                    $mahasiswaId = $this->mahasiswaModel->getInsertID();
                    $createdIds[] = $mahasiswaId;
                    
                    // Tambahkan ke list existing untuk validasi selanjutnya
                    $existingNimList[] = $data['nim'];
                    $existingEmailList[] = $data['email'];

                    $successCount++;

                } catch (\Exception $e) {
                    $errors[] = "Baris " . ($index + 2) . ": " . $e->getMessage();
                    $errorCount++;
                    log_message('error', 'Import Mahasiswa Error: ' . $e->getMessage());
                }
            }

            return $this->respond([
                'success' => true,
                'message' => "Import selesai. Berhasil: $successCount, Gagal: $errorCount" . ($skippedCount > 0 ? " (Skipped: $skippedCount)" : ''),
                'success_count' => $successCount,
                'error_count' => $errorCount,
                'skipped_count' => $skippedCount,
                'errors' => $errors,
                'created_ids' => $createdIds
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Import Batch Mahasiswa Error: ' . $e->getMessage());
            log_message('error', 'Stack Trace: ' . $e->getTraceAsString());
            
            return $this->fail([
                'message' => 'Terjadi kesalahan saat memproses import',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}