<?php

namespace App\Controllers;

use App\Models\SertifikatModel;
use App\Models\FakultasModel;
use App\Models\NamaSertifikatModel;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use App\Libraries\QrcodeGenerator;
use CodeIgniter\Controller;
use CodeIgniter\API\ResponseTrait;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class Sertifikat extends BaseController
{
    use ResponseTrait;

    protected $sertifikatModel;
    protected $fakultasModel;
    protected $namaSertifikatModel;

    public function __construct()
    {
        $this->sertifikatModel = new SertifikatModel();
        $this->fakultasModel = new FakultasModel();
        $this->namaSertifikatModel = new NamaSertifikatModel();
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
        if ($redirect = $this->checkLogin()) return $redirect;
        
        $level = session()->get('level');
        
        // Level 1 (SuperAdmin) tidak memiliki akses ke E-Sertifikat
        if ($level == 1) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        $userId = session()->get('id');
        $userName = session()->get('name');
        $userEmail = session()->get('email');
        
        // Get sertifikat berdasarkan level user
        $allSertifikat = $this->sertifikatModel->getSertifikatByUser($level, $userId, $userName, $userEmail);
        
        $title = 'E-Sertifikat';
        $data = [
            'title' => 'E-Sertifikat',
            'sertifikat' => $this->sertifikatModel->getSertifikatByNim(session()->get('nim')),
            'allSertifikat' => $allSertifikat,
            'facultyStats' => $this->sertifikatModel->getCertificateCountByFaculty(),
            'level' => $level
        ];

        return view('page/sertifikat/index', $data);
    }

    public function create()
    {
        if ($redirect = $this->checkLogin()) return $redirect;
        
        $data = [
            'title' => 'Tambah Sertifikat',
            'validation' => \Config\Services::validation(),
            'fakultas' => $this->fakultasModel->findAll(),
            'nama_sertifikat' => $this->namaSertifikatModel->findAll(),
        ];

        return view('page/sertifikat/create', $data);
    }

    public function save()
    {
        if ($redirect = $this->checkLogin()) return $redirect;
        
        if (!$this->validate([
            'nama_kegiatan' => 'required',
            'nama_sertifikat_id' => 'required',
            'fakultas_id' => 'required',
            'nim' => 'required',
            'nama_penerima' => 'required',
            'file' => [
                'rules' => 'uploaded[file]|max_size[file,2048]|mime_in[file,application/pdf,image/jpeg,image/png]',
                'errors' => [
                    'uploaded' => 'Pilih file sertifikat terlebih dahulu',
                    'max_size' => 'Ukuran file maksimal 2MB',
                    'mime_in' => 'Format file harus PDF, JPG, atau PNG',
                ]
            ]
        ])) {
            return redirect()->to('/sertifikat/create')->withInput();
        }

        $file = $this->request->getFile('file');
        
        // Validasi file
        if (!$file->isValid()) {
            log_message('error', 'File tidak valid saat upload');
            session()->setFlashdata('error', 'File tidak valid.');
            return redirect()->to('/sertifikat/create')->withInput();
        }
        
        // Log info file sebelum upload
        $originalSize = $file->getSize();
        log_message('info', 'File diterima - Original size: ' . round($originalSize / (1024 * 1024), 2) . ' MB, Name: ' . $file->getName());
        
        $fileName = $file->getRandomName();
        $uploadDir = FCPATH . 'uploads/sertifikat/';
        
        // Pastikan direktori upload ada
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $file->move($uploadDir, $fileName);

        // Cek ukuran file setelah upload (file yang sudah di-compress di frontend)
        $uploadedFilePath = $uploadDir . $fileName;
        $fileSize = file_exists($uploadedFilePath) ? filesize($uploadedFilePath) : $file->getSize();
        $maxSize = 2 * 1024 * 1024; // 2MB in bytes
        
        // empty_certificate selalu true karena di form sudah di-set sebagai hidden input dengan value="1"
        $emptyCertificate = $this->request->getVar('empty_certificate') == '1' || $this->request->getVar('empty_certificate') !== null;
        
        log_message('info', 'Save sertifikat - empty_certificate: ' . ($emptyCertificate ? 'true' : 'false') . ', fileSize setelah upload: ' . round($fileSize / (1024 * 1024), 2) . ' MB, fileName: ' . $fileName);

        // Jika empty_certificate aktif, cek ukuran file
        // File yang sudah di-compress di frontend seharusnya sudah < 2MB
        if ($emptyCertificate && $fileSize > $maxSize) {
            // Hapus file yang sudah diupload
            @unlink($uploadedFilePath);
            
            session()->setFlashdata('error', 'Ukuran file lebih dari 2MB (' . round($fileSize / (1024 * 1024), 2) . ' MB). File dengan ukuran lebih dari 2MB tidak dapat ditambahkan nama dan QR code. Silakan compress file terlebih dahulu.');
            return redirect()->to('/sertifikat/create')->withInput();
        }

        $data = [
            'nama_kegiatan' => $this->request->getVar('nama_kegiatan'),
            'nama_sertifikat_id' => $this->request->getVar('nama_sertifikat_id'),
            'nama_penerima' => $this->request->getVar('nama_penerima'),
            'fakultas_id' => $this->request->getVar('fakultas_id'),
            'nim' => $this->request->getVar('nim'),
            'file' => $fileName,
            'user_id' => session()->get('id'),
            'user_name' => session()->get('name'),
            'user_email' => session()->get('email'),
        ];

        if (!$this->sertifikatModel->save($data)) {
            // Hapus file jika gagal save
            @unlink($uploadedFilePath);
            session()->setFlashdata('error', 'Gagal menyimpan data sertifikat.');
            return redirect()->to('/sertifikat/create')->withInput();
        }

        $certificateId = $this->sertifikatModel->getInsertID();
        log_message('info', 'Sertifikat berhasil disimpan dengan ID: ' . $certificateId . ', empty_certificate: ' . ($emptyCertificate ? 'true' : 'false'));

        // Generate QR Code dan Nama Penerima jika empty_certificate aktif
        // empty_certificate selalu true karena form sudah di-set otomatis
        if ($emptyCertificate) {
            log_message('info', 'Memulai generate QR Code dan nama untuk sertifikat ID: ' . $certificateId);
            try {
                // Generate QR Code dan nama penerima
                $newFileName = $this->generateCertificateWithQR($fileName, $certificateId, $data);
                
                if ($newFileName === false) {
                    log_message('error', 'Gagal generate QR Code dan nama untuk sertifikat ID: ' . $certificateId);
                    session()->setFlashdata('error', 'Data sertifikat berhasil disimpan, namun gagal menambahkan QR Code dan nama penerima. Silakan edit sertifikat untuk menambahkan QR Code dan nama.');
                } else {
                    // Jika nama file berubah (PDF -> PNG), update sudah dilakukan di generateCertificateWithQR
                    log_message('info', 'Berhasil generate QR Code dan nama untuk sertifikat ID: ' . $certificateId . ', File: ' . $newFileName);
                }
            } catch (\Exception $e) {
                log_message('error', 'Error saat generate QR Code: ' . $e->getMessage());
                log_message('error', 'Stack trace: ' . $e->getTraceAsString());
                session()->setFlashdata('error', 'Data sertifikat berhasil disimpan, namun terjadi error saat menambahkan QR Code dan nama penerima: ' . $e->getMessage());
            }
        } else {
            log_message('info', 'empty_certificate tidak aktif, skip generate QR Code untuk sertifikat ID: ' . $certificateId);
        }

        session()->setFlashdata('pesan', 'Data sertifikat berhasil ditambahkan.');
        return redirect()->to(url_to('sertifikat.index'));
    }

    public function verify($id = null)
    {
        // Tetap bisa diakses tanpa login
        if ($id) {
            $sertifikat = $this->sertifikatModel->find($id);
            
            if ($sertifikat) {
                return view('page/sertifikat/verifikasi', [
                    'title' => 'Verifikasi Sertifikat',
                    'sertifikat' => $sertifikat,
                    'verified' => true,
                    'message' => 'Sertifikat valid'
                ]);
            }
        }

        return view('page/sertifikat/verifikasi', [
            'title' => 'Verifikasi Sertifikat',
            'verified' => false,
            'message' => 'Sertifikat tidak ditemukan'
        ]);
    }

    public function edit($id)
    {
        if ($redirect = $this->checkLogin()) return $redirect;
        
        $data = [
            'title' => 'Edit Sertifikat',
            'sertifikat' => $this->sertifikatModel->find($id),
            'validation' => \Config\Services::validation(),
            'fakultas' => $this->fakultasModel->findAll(),
            'nama_sertifikat' => $this->namaSertifikatModel->findAll(),
        ];

        return view('page/sertifikat/edit', $data);
    }

    public function update($id)
    {
        if ($redirect = $this->checkLogin()) return $redirect;
        
        if (!$this->validate([
            'nama_kegiatan' => 'required',
            'nama_sertifikat_id' => 'required',
            'fakultas_id' => 'required',
            'nim' => 'required',
            'nama_penerima' => 'required',
            'file' => [
                'rules' => 'max_size[file,2048]|mime_in[file,application/pdf,image/jpeg,image/png]',
                'errors' => [
                    'max_size' => 'Ukuran file maksimal 2MB',
                    'mime_in' => 'Format file harus PDF, JPG, atau PNG',
                ]
            ]
        ])) {
            return redirect()->to('/sertifikat/edit/' . $id)->withInput();
        }

        $file = $this->request->getFile('file');
        $sertifikat = $this->sertifikatModel->find($id);

        $fileName = $sertifikat['file'];
        $shouldRegenerate = false;

        if ($file->isValid() && !$file->hasMoved()) {
            $oldFilePath = FCPATH . 'uploads/sertifikat/' . $sertifikat['file'];
            if (file_exists($oldFilePath) && is_file($oldFilePath)) {
                @unlink($oldFilePath);
            }

            $fileName = $file->getRandomName();
            $uploadDir = FCPATH . 'uploads/sertifikat/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $file->move($uploadDir, $fileName);
            $shouldRegenerate = true;
        }

        $data = [
            'id' => $id,
            'nama_kegiatan' => $this->request->getVar('nama_kegiatan'),
            'nama_sertifikat_id' => $this->request->getVar('nama_sertifikat_id'),
            'nama_penerima' => $this->request->getVar('nama_penerima'),
            'fakultas_id' => $this->request->getVar('fakultas_id'),
            'nim' => $this->request->getVar('nim'),
            'file' => $fileName,
        ];

        // Check if nama_penerima changed or file was updated
        if ($shouldRegenerate || $sertifikat['nama_penerima'] !== $data['nama_penerima']) {
            $newFileName = $this->generateCertificateWithQR($fileName, $id, $data);
            // Jika nama file berubah (PDF -> PNG), update data
            if ($newFileName !== false && $newFileName !== $fileName) {
                $data['file'] = $newFileName;
            }
        }

        $this->sertifikatModel->save($data);
        session()->setFlashdata('pesan', 'Data sertifikat berhasil diubah.');
        return redirect()->to(url_to('sertifikat.index'));
    }

    public function download($id)
    {
        // Tetap bisa diakses tanpa login
        $sertifikat = $this->sertifikatModel->find($id);
        return $this->response->download('uploads/sertifikat/' . $sertifikat['file'], null);
    }

    public function delete($id)
    {
        if ($redirect = $this->checkLogin()) return $redirect;
        
        $sertifikat = $this->sertifikatModel->find($id);
        
        // Hapus file sertifikat jika ada
        if (!empty($sertifikat['file'])) {
            $filePath = FCPATH . 'uploads/sertifikat/' . $sertifikat['file'];
            if (file_exists($filePath) && is_file($filePath)) {
                @unlink($filePath);
            }
        }
        
        // Hapus QR code jika ada (pastikan tidak kosong dan merupakan file, bukan direktori)
        if (!empty($sertifikat['qr_code'])) {
            $qrCodePath = FCPATH . 'uploads/qrcodes/' . $sertifikat['qr_code'];
            if (file_exists($qrCodePath) && is_file($qrCodePath)) {
                @unlink($qrCodePath);
            }
        }
        
        $this->sertifikatModel->delete($id);
        session()->setFlashdata('pesan', 'Data sertifikat berhasil dihapus.');
        return redirect()->to(url_to('sertifikat.index'));
    }

    /**
     * Bulk delete sertifikat
     */
    public function bulkDelete()
    {
        if ($redirect = $this->checkLogin()) return $redirect;
        
        // Log request method dan data
        log_message('info', 'Bulk Delete Request - Method: ' . $this->request->getMethod());
        log_message('info', 'Bulk Delete Request - Post Data: ' . json_encode($this->request->getPost()));
        
        // Hanya level 1, 2, dan 0 yang bisa bulk delete
        if (!in_array(session()->get('level'), [1, 2, 0])) {
            session()->setFlashdata('error', 'Anda tidak memiliki akses untuk menghapus sertifikat.');
            return redirect()->to(url_to('sertifikat.index'));
        }
        
        $ids = $this->request->getPost('ids');
        
        if (empty($ids) || !is_array($ids)) {
            log_message('error', 'Bulk Delete - No IDs provided or not array');
            session()->setFlashdata('error', 'Tidak ada sertifikat yang dipilih untuk dihapus.');
            return redirect()->to(url_to('sertifikat.index'));
        }
        
        log_message('info', 'Bulk Delete - Processing ' . count($ids) . ' IDs: ' . json_encode($ids));
        
        $successCount = 0;
        $errorCount = 0;
        
        foreach ($ids as $id) {
            try {
                $sertifikat = $this->sertifikatModel->find($id);
                
                if (!$sertifikat) {
                    $errorCount++;
                    continue;
                }
                
                // Hapus file sertifikat
                if (!empty($sertifikat['file'])) {
                    $filePath = FCPATH . 'uploads/sertifikat/' . $sertifikat['file'];
                    if (file_exists($filePath) && is_file($filePath)) {
                        @unlink($filePath);
                    }
                }
                
                // Hapus QR code jika ada (pastikan tidak kosong dan merupakan file, bukan direktori)
                if (!empty($sertifikat['qr_code'])) {
                    $qrCodePath = FCPATH . 'uploads/qrcodes/' . $sertifikat['qr_code'];
                    if (file_exists($qrCodePath) && is_file($qrCodePath)) {
                        @unlink($qrCodePath);
                    }
                }
                
                // Hapus dari database
                if ($this->sertifikatModel->delete($id)) {
                    $successCount++;
                } else {
                    $errorCount++;
                }
            } catch (\Exception $e) {
                log_message('error', 'Bulk delete error: ' . $e->getMessage());
                $errorCount++;
            }
        }
        
        if ($successCount > 0) {
            session()->setFlashdata('pesan', "Berhasil menghapus {$successCount} sertifikat." . ($errorCount > 0 ? " {$errorCount} sertifikat gagal dihapus." : ''));
        } else {
            session()->setFlashdata('error', 'Gagal menghapus sertifikat yang dipilih.');
        }
        
        return redirect()->to(url_to('sertifikat.index'));
    }

    public function getMonthlyData()
    {
        // Tetap bisa diakses tanpa login
        $year = $this->request->getGet('year') ?? date('Y');
        $monthlyData = $this->sertifikatModel->getMonthlyCertificateCount($year);

        // Format data untuk semua bulan
        $result = array_fill(1, 12, ['month' => 0, 'count' => 0, 'year' => (int)$year]);

        foreach ($monthlyData as $data) {
            $result[$data['month']] = [
                'month' => (int)$data['month'],
                'count' => (int)$data['count'],
                'year' => (int)$year
            ];
        }

        // Hapus bulan 0 (indeks array dimulai dari 1)
        unset($result[0]);

        return $this->respond(array_values($result));
    }

    public function getAvailableYears()
    {
        // Tetap bisa diakses tanpa login
        $years = $this->sertifikatModel->getAvailableYears();
        return $this->respond($years);
    }

    public function getFacultyDistribution()
    {
        // Tetap bisa diakses tanpa login
        $data = $this->sertifikatModel->getCertificateCountByFaculty();
        return $this->respond($data);
    }

    private function generateCertificateWithQR($fileName, $certificateId, $data)
    {
        // Fungsi internal tidak perlu pengecekan login
        $filePath = FCPATH . 'uploads/sertifikat/' . $fileName;
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $wasPdf = ($fileExt === 'pdf');
        
        // Jika PDF, file hasil akan menjadi PNG
        $newFileName = $wasPdf ? preg_replace('/\.pdf$/i', '.png', $fileName) : $fileName;
        $newFilePath = FCPATH . 'uploads/sertifikat/' . $newFileName;
        $finalPath = FCPATH . 'uploads/sertifikat/final_' . time() . '_' . ($wasPdf ? $newFileName : $fileName);
        
        // Pastikan file asli ada
        if (!file_exists($filePath)) {
            log_message('error', 'File sertifikat tidak ditemukan: ' . $filePath);
            return false;
        }
        
        log_message('info', 'Generate QR Code - File path: ' . $filePath . ', Certificate ID: ' . $certificateId . ', Was PDF: ' . ($wasPdf ? 'true' : 'false'));

        $qrGenerator = new \App\Libraries\QrcodeGenerator();
        $qrCodePath = FCPATH . 'uploads/temp_qrcode_' . time() . '.png';
        
        // Pastikan direktori uploads ada
        $uploadsDir = FCPATH . 'uploads/';
        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0755, true);
        }
        
        // 1. Generate QR Code
        $verificationUrl = base_url('verifikasi/'.$certificateId);
        if (!$qrGenerator->generateVerificationQR($verificationUrl, $qrCodePath)) {
            log_message('error', 'Gagal generate QR Code');
            return false;
        }

        // 2. Tambahkan logo ke QR jika ada
        $logoPath = FCPATH . 'public/images/logo1.png';
        if (file_exists($logoPath)) {
            $qrGenerator->mergeLogoToQR($qrCodePath, $logoPath);
        }

        // 3. Handle file sertifikat
        if ($wasPdf) {
            $pdfImagePath = FCPATH . 'uploads/temp_pdf_' . time() . '.png';
            if (!$this->convertPdfToImage($filePath, $pdfImagePath)) {
                log_message('error', 'Gagal konversi PDF ke gambar');
                return false;
            }
            $workingFile = $pdfImagePath;
            // Set fileExt untuk proses selanjutnya (akan jadi PNG)
            $fileExt = 'png';
        } else {
            $workingFile = $filePath;
            // Untuk PNG/JPEG/JPG, gunakan ekstensi asli
            // Normalisasi jpeg menjadi jpg untuk konsistensi
            if ($fileExt === 'jpeg') {
                $fileExt = 'jpg';
            }
        }

        // 4. Salin ke path final
        if (!copy($workingFile, $finalPath)) {
            log_message('error', 'Gagal menyalin file sertifikat');
            return false;
        }

        // 5. Tambahkan log untuk memverifikasi file sebelum modifikasi
        if (!file_exists($finalPath)) {
            log_message('error', 'File final tidak ditemukan sebelum modifikasi');
            return false;
        }

        // 6. Gabungkan QR Code ke sertifikat
        // Untuk PDF yang dikonversi, gunakan 'png', untuk PNG/JPEG/JPG gunakan ekstensi asli
        $mergeExt = $wasPdf ? 'png' : $fileExt;
        if (!$qrGenerator->mergeQRWithCertificate($finalPath, $qrCodePath, $mergeExt)) {
            log_message('error', 'Gagal menggabungkan QR Code');
            return false;
        }

        // 7. Dapatkan dimensi gambar untuk penempatan teks yang tepat
        $imageInfo = getimagesize($finalPath);
        $certWidth = $imageInfo[0];
        $certHeight = $imageInfo[1];

        // 8. Tambahkan nama penerima dengan posisi yang lebih tepat
        if (!$qrGenerator->addRecipientName($finalPath, $data['nama_penerima'], [
            'fontSize' => 120, // Perbesar ukuran font yang lama 120
            'color' => '#0000', // Warna lebih kontras (biru tua)
            'xOffset' => $certWidth * 0.5, // Tengah horizontal
            'yOffset' => $certHeight * 0.55, // 45% dari tinggi (lebih atas)
            'fontPath' => FCPATH . 'fonts/PinyonScript-Regular.ttf' // Pastikan path sesuai
        ])) {
            log_message('error', 'Gagal menambahkan nama penerima');
            return false;
        }

        // 9. Tambahkan langkah diagnostik: simpan salinan sementara
        $tempDebugPath = FCPATH . 'uploads/debug_' . time() . '_' . $newFileName;
        if (!copy($finalPath, $tempDebugPath)) {
            log_message('error', 'Gagal membuat salinan debug');
        } else {
            log_message('debug', 'Salinan debug dibuat di: ' . $tempDebugPath);
        }

        // 10. Verifikasi output
        if (!file_exists($finalPath) || filesize($finalPath) == 0) {
            log_message('error', 'File output tidak valid setelah penambahan teks');
            return false;
        }

        // 11. Hapus file asli (PDF atau gambar lama)
        if (file_exists($filePath) && is_file($filePath)) {
            @unlink($filePath);
        }
        
        // 12. Jika file baru berbeda dengan file lama (PDF -> PNG), hapus file lama jika masih ada
        if ($wasPdf && $newFilePath !== $filePath && file_exists($newFilePath) && is_file($newFilePath)) {
            @unlink($newFilePath);
        }
        
        // 13. Ganti dengan file yang sudah dimodifikasi
        if (!rename($finalPath, $newFilePath)) {
            log_message('error', 'Gagal replace file sertifikat');
            return false;
        }

        // 14. Update nama file di database jika berubah (PDF -> PNG)
        if ($wasPdf && $newFileName !== $fileName) {
            $this->sertifikatModel->update($certificateId, ['file' => $newFileName]);
            log_message('info', 'File name updated in database from ' . $fileName . ' to ' . $newFileName);
        }

        // 15. Bersihkan file temporary
        @unlink($qrCodePath);
        if (isset($pdfImagePath) && file_exists($pdfImagePath)) {
            @unlink($pdfImagePath);
        }
        if (file_exists($tempDebugPath)) {
            @unlink($tempDebugPath);
        }

        // Return nama file baru untuk keperluan update di caller
        return $newFileName;
    }

    private function convertPdfToImage($pdfPath, $outputPath)
    {
        try {
            $imagick = new \Imagick();
            $imagick->setResolution(300, 300);
            $imagick->readImage($pdfPath.'[0]');
            $imagick->setImageFormat('png');
            $imagick->writeImage($outputPath);
            $imagick->clear();
            return true;
        } catch (\Exception $e) {
            log_message('error', 'PDF to Image Error: '.$e->getMessage());
            throw new \RuntimeException('Failed to convert PDF to image');
        }
    }

    public function all()
    {
        // Tetap bisa diakses tanpa login
        $namaSertifikatModel = new NamaSertifikatModel();
        $search = $this->request->getGet('search');
        $namaSertifikatFilter = $this->request->getGet('nama_sertifikat');
        
        // Pagination Configuration - dipindahkan ke database level untuk performa optimal
        $perPage = 12; // 3 kolom x 4 baris
        $currentPage = (int)($this->request->getGet('page') ?? 1);
        $currentPage = max(1, $currentPage); // Pastikan minimal 1
        
        // Hitung total items dengan query COUNT yang optimal (menggunakan index)
        $totalItems = $this->sertifikatModel->countSertifikatFiltered($search, $namaSertifikatFilter);
        $totalPages = ceil($totalItems / $perPage);
        $offset = ($currentPage - 1) * $perPage;

        // Ambil data dengan pagination di database level - sangat efisien untuk ratusan ribu data
        // Query ini akan menggunakan index yang sudah ada:
        // - idx_nama_sertifikat_id untuk filter
        // - idx_created_at untuk sorting
        // - idx_fakultas_id untuk join
        // - idx_nama_penerima (FULLTEXT) untuk search jika tersedia
        $allSertifikat = $this->sertifikatModel->getSertifikatPaginated($search, $namaSertifikatFilter, $perPage, $offset);

        $data = [
            'title' => 'Semua Sertifikat',
            'allSertifikat' => $allSertifikat,
            'nama_sertifikat' => $namaSertifikatModel->findAll(),
            'search' => $search, // Kirim nilai search kembali ke view
            'totalItems' => $totalItems,
            'totalPages' => $totalPages,
            'currentPage' => $currentPage,
            'perPage' => $perPage
        ];

        return view('page/sertifikat', $data);
    }

    /**
     * Download template Excel untuk import sertifikat dengan dropdown
     */
    public function downloadTemplate()
    {
        if ($redirect = $this->checkLogin()) return $redirect;

        // Ambil data dari database
        $namaSertifikatList = $this->namaSertifikatModel->findAll();
        $fakultasList = $this->fakultasModel->findAll();

        // Buat spreadsheet baru
        $spreadsheet = new Spreadsheet();
        
        // Sheet 1: Data Template
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Sertifikat');
        
        // Header
        $sheet->setCellValue('A1', 'Nama Kegiatan');
        $sheet->setCellValue('B1', 'Nama Sertifikat');
        $sheet->setCellValue('C1', 'Nama Penerima');
        $sheet->setCellValue('D1', 'Fakultas');
        $sheet->setCellValue('E1', 'NIM');
        
        // Style header
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4']
            ],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ];
        $sheet->getStyle('A1:E1')->applyFromArray($headerStyle);
        
        // Set column width
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(25);
        $sheet->getColumnDimension('E')->setWidth(20);
        
        // Contoh data
        $sheet->setCellValue('A2', 'Workshop Teknologi Informasi');
        $sheet->setCellValue('B2', $namaSertifikatList[0]['nama_sertifikat'] ?? 'Sertifikat Peserta');
        $sheet->setCellValue('C2', 'Anisa');
        $sheet->setCellValue('D2', $fakultasList[0]['nama_fakultas'] ?? 'Fakultas Teknik');
        $sheet->setCellValue('E2', '1234567890');
        
        $sheet->setCellValue('A3', 'Seminar Nasional');
        $sheet->setCellValue('B3', $namaSertifikatList[1]['nama_sertifikat'] ?? 'Sertifikat Pembicara');
        $sheet->setCellValue('C3', 'Faiz');
        $sheet->setCellValue('D3', $fakultasList[1]['nama_fakultas'] ?? 'Fakultas Ekonomi');
        $sheet->setCellValue('E3', '987654321');
        
        // Sheet 2: Data Nama Sertifikat (untuk dropdown)
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('List Nama Sertifikat');
        $sheet2->setCellValue('A1', 'Nama Sertifikat');
        $sheet2->getStyle('A1')->applyFromArray($headerStyle);
        $row = 2;
        foreach ($namaSertifikatList as $ns) {
            $sheet2->setCellValue('A' . $row, $ns['nama_sertifikat']);
            $row++;
        }
        $sheet2->getColumnDimension('A')->setWidth(30);
        
        // Sheet 3: Data Fakultas (untuk dropdown)
        $sheet3 = $spreadsheet->createSheet();
        $sheet3->setTitle('List Fakultas');
        $sheet3->setCellValue('A1', 'Nama Fakultas');
        $sheet3->getStyle('A1')->applyFromArray($headerStyle);
        $row = 2;
        foreach ($fakultasList as $f) {
            $sheet3->setCellValue('A' . $row, $f['nama_fakultas']);
            $row++;
        }
        $sheet3->getColumnDimension('A')->setWidth(30);
        
        // Kembali ke sheet utama
        $spreadsheet->setActiveSheetIndex(0);
        
        // Buat named ranges untuk dropdown
        $lastRowSertifikat = count($namaSertifikatList) + 1;
        $lastRowFakultas = count($fakultasList) + 1;
        
        // Tambahkan data validation (dropdown) untuk kolom B (Nama Sertifikat)
        $validation = $sheet->getCell('B2')->getDataValidation();
        $validation->setType(DataValidation::TYPE_LIST);
        $validation->setErrorStyle(DataValidation::STYLE_STOP);
        $validation->setAllowBlank(false);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setShowDropDown(true);
        $validation->setErrorTitle('Input error');
        $validation->setError('Nilai tidak valid. Pilih dari dropdown.');
        $validation->setPromptTitle('Pilih Nama Sertifikat');
        $validation->setPrompt('Silakan pilih nama sertifikat dari dropdown.');
        $validation->setFormula1('=\'List Nama Sertifikat\'!$A$2:$A$' . $lastRowSertifikat);
        
        // Copy validation ke baris lain (maksimal 1000 baris)
        for ($i = 2; $i <= 1000; $i++) {
            $sheet->getCell('B' . $i)->setDataValidation(clone $validation);
        }
        
        // Tambahkan data validation (dropdown) untuk kolom D (Fakultas)
        $validation2 = $sheet->getCell('D2')->getDataValidation();
        $validation2->setType(DataValidation::TYPE_LIST);
        $validation2->setErrorStyle(DataValidation::STYLE_STOP);
        $validation2->setAllowBlank(false);
        $validation2->setShowInputMessage(true);
        $validation2->setShowErrorMessage(true);
        $validation2->setShowDropDown(true);
        $validation2->setErrorTitle('Input error');
        $validation2->setError('Nilai tidak valid. Pilih dari dropdown.');
        $validation2->setPromptTitle('Pilih Fakultas');
        $validation2->setPrompt('Silakan pilih fakultas dari dropdown.');
        $validation2->setFormula1('=\'List Fakultas\'!$A$2:$A$' . $lastRowFakultas);
        
        // Copy validation ke baris lain (maksimal 1000 baris)
        for ($i = 2; $i <= 1000; $i++) {
            $sheet->getCell('D' . $i)->setDataValidation(clone $validation2);
        }
        
        // Freeze header row
        $sheet->freezePane('A2');
        
        // Output file
        $filename = 'template_import_sertifikat_' . date('Y-m-d') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * Import batch sertifikat dari Excel/CSV
     */
    public function importBatch()
    {
        try {
            // Set waktu eksekusi lebih lama untuk proses import yang mungkin memakan waktu
            set_time_limit(300); // 5 menit
            
            if ($redirect = $this->checkLogin()) return $redirect;

            if (!$this->request->isAJAX()) {
                return $this->fail('Request harus menggunakan AJAX', 400);
            }

            // CSRF validation sudah di-handle oleh filter exception
            // Route ini sudah di-except dari CSRF filter di Filters.php

            // Ambil file dari FormData
            $excelFile = $this->request->getFile('excel_file');
            $baseCertificateFile = $this->request->getFile('base_certificate_file');
            
            if (!$excelFile || !$excelFile->isValid()) {
                return $this->fail('File Excel tidak valid', 400);
            }
            
            if (!$baseCertificateFile || !$baseCertificateFile->isValid()) {
                return $this->fail('File sertifikat dasar tidak valid', 400);
            }

            // Validasi ukuran file sertifikat (maksimal 2MB)
            $maxSize = 2 * 1024 * 1024; // 2MB
            if ($baseCertificateFile->getSize() > $maxSize) {
                return $this->fail('Ukuran file sertifikat dasar terlalu besar. Maksimal 2MB. File Anda: ' . round($baseCertificateFile->getSize() / (1024 * 1024), 2) . ' MB', 400);
            }

            // Baca data dari FormData
            $dataJson = $this->request->getPost('data');
            if (empty($dataJson)) {
                return $this->fail('Data tidak ditemukan', 400);
            }
            
            $importData = json_decode($dataJson, true);
            if (!is_array($importData) || empty($importData)) {
                return $this->fail('Data tidak valid', 400);
            }

            $emptyCertificate = $this->request->getPost('empty_certificate') == '1';
            
            // Simpan file sertifikat dasar ke temp untuk digunakan
            $tempDir = FCPATH . 'uploads/temp/';
            if (!is_dir($tempDir)) {
                mkdir($tempDir, 0755, true);
            }
            
            $baseFileName = time() . '_' . $baseCertificateFile->getRandomName();
            $baseCertificateFile->move($tempDir, $baseFileName);
            $filePath = $tempDir . $baseFileName;

            $successCount = 0;
            $errorCount = 0;
            $errors = [];
            $createdIds = [];

        foreach ($importData as $index => $row) {
            try {
                // Validasi data
                if (empty($row['nama_kegiatan']) || 
                    empty($row['nama_penerima']) || 
                    empty($row['nim']) ||
                    empty($row['nama_sertifikat_id']) ||
                    empty($row['fakultas_id'])) {
                    $errors[] = "Baris " . ($index + 2) . ": Data tidak lengkap";
                    $errorCount++;
                    continue;
                }

                // Handle file sertifikat - copy file dasar untuk setiap sertifikat
                $fileExt = pathinfo($baseFileName, PATHINFO_EXTENSION);
                $fileName = time() . '_' . $index . '_' . uniqid() . '.' . $fileExt;
                $targetPath = FCPATH . 'uploads/sertifikat/' . $fileName;
                
                if (!copy($filePath, $targetPath)) {
                    $errors[] = "Baris " . ($index + 2) . ": Gagal menyalin file sertifikat";
                    $errorCount++;
                    continue;
                }

                // Simpan data sertifikat
                $data = [
                    'nama_kegiatan' => $row['nama_kegiatan'],
                    'nama_sertifikat_id' => $row['nama_sertifikat_id'],
                    'nama_penerima' => $row['nama_penerima'],
                    'fakultas_id' => $row['fakultas_id'],
                    'nim' => $row['nim'],
                    'file' => $fileName,
                    'user_id' => session()->get('id'),
                    'user_name' => session()->get('name'),
                    'user_email' => session()->get('email'),
                ];

                if (!$this->sertifikatModel->save($data)) {
                    // Hapus file jika gagal save
                    if ($fileName && file_exists(FCPATH . 'uploads/sertifikat/' . $fileName)) {
                        @unlink(FCPATH . 'uploads/sertifikat/' . $fileName);
                    }
                    $errors[] = "Baris " . ($index + 2) . ": Gagal menyimpan data";
                    $errorCount++;
                    continue;
                }

                $certificateId = $this->sertifikatModel->getInsertID();
                $createdIds[] = $certificateId;

                // Generate QR Code dan nama jika diperlukan
                if ($emptyCertificate && $fileName) {
                    $newFileName = $this->generateCertificateWithQR($fileName, $certificateId, $data);
                    // Jika nama file berubah (PDF -> PNG), update database
                    if ($newFileName !== false && $newFileName !== $fileName) {
                        $this->sertifikatModel->update($certificateId, ['file' => $newFileName]);
                    }
                }

                $successCount++;

            } catch (\Exception $e) {
                $errors[] = "Baris " . ($index + 2) . ": " . $e->getMessage();
                $errorCount++;
                log_message('error', 'Import Error: ' . $e->getMessage());
            }
        }

            // Hapus file temp setelah selesai
            if (isset($filePath) && file_exists($filePath)) {
                @unlink($filePath);
            }

            return $this->respond([
                'success' => true,
                'message' => "Import selesai. Berhasil: $successCount, Gagal: $errorCount",
                'success_count' => $successCount,
                'error_count' => $errorCount,
                'errors' => $errors,
                'created_ids' => $createdIds
            ]);
            
        } catch (\Exception $e) {
            // Log error untuk debugging
            log_message('error', 'Import Batch Error: ' . $e->getMessage());
            log_message('error', 'Stack Trace: ' . $e->getTraceAsString());
            
            // Hapus file temp jika ada
            if (isset($filePath) && file_exists($filePath)) {
                @unlink($filePath);
            }
            
            // Return error dalam format JSON yang benar
            return $this->fail([
                'message' => 'Terjadi kesalahan saat memproses import',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    /**
     * Upload file sertifikat dasar untuk import batch
     */
    public function uploadBaseFile()
    {
        if ($redirect = $this->checkLogin()) return $redirect;

        if (!$this->request->isAJAX()) {
            return $this->fail('Request harus menggunakan AJAX', 400);
        }

        // CSRF validation sudah di-handle oleh filter exception
        // Route ini sudah di-except dari CSRF filter di Filters.php
        // Jadi tidak perlu validasi manual CSRF di sini

        $file = $this->request->getFile('base_file');
        
        if (!$file) {
            log_message('error', 'File tidak ditemukan pada uploadBaseFile');
            return $this->fail([
                'message' => 'File tidak ditemukan',
                'error' => 'File tidak ditemukan. Pastikan file sudah dipilih.'
            ], 400);
        }
        
        if (!$file->isValid()) {
            $error = $file->getError();
            $errorString = $file->getErrorString();
            log_message('error', 'File tidak valid pada uploadBaseFile. Error: ' . $error . ' - ' . $errorString);
            return $this->fail([
                'message' => 'File tidak valid',
                'error' => 'File tidak valid: ' . $errorString
            ], 400);
        }

        // Validasi file
        if (!$file->hasMoved()) {
            $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
            $mimeType = $file->getMimeType();
            
            if (!in_array($mimeType, $allowedTypes)) {
                return $this->fail('Format file tidak didukung. Gunakan JPG, PNG, atau PDF', 400);
            }

            // Validasi ukuran file (maksimal 2MB)
            $maxSize = 2 * 1024 * 1024; // 2MB
            $fileSize = $file->getSize();
            
            if ($fileSize > $maxSize) {
                return $this->fail('Ukuran file terlalu besar. Maksimal 2MB. File Anda: ' . round($fileSize / (1024 * 1024), 2) . ' MB', 400);
            }

            // Simpan ke folder temp
            $tempDir = FCPATH . 'uploads/temp/';
            if (!is_dir($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            $fileName = time() . '_' . $file->getRandomName();
            $file->move($tempDir, $fileName);

            return $this->respond([
                'success' => true,
                'file_name' => $fileName,
                'file_size' => $fileSize,
                'message' => 'File berhasil diupload'
            ]);
        }

        return $this->fail('Gagal mengupload file', 500);
    }
}