<?php

namespace App\Controllers;

use App\Models\OrganisasiModel;
use CodeIgniter\API\ResponseTrait;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Form extends BaseController
{
    use ResponseTrait;

    protected $organisasiModel;
    protected $pesanModel;
    protected $db;

    public function __construct()
    {
        $this->organisasiModel = new OrganisasiModel();
        $this->pesanModel = new \App\Models\PesanModel();
        $this->db = \Config\Database::connect();
        helper(['form', 'url', 'security']);
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
        $userId = session()->get('id');

        // Get unread count for notification badge (only for level 0 and 2)
        $unreadCount = 0;
        if (in_array($level, [0, 2])) {
            $unreadCount = $this->pesanModel->getUnreadCount();
        }

        // Get forms dengan join ke organisasi dan user untuk mendapatkan level user
        $builder = $this->db->table('forms');
        $builder->select('forms.*, organisasis.name as organisasi_name, users.level as user_level');
        $builder->join('organisasis', 'forms.organisasi_id = organisasis.id', 'left');
        $builder->join('users', 'forms.user_id = users.id', 'left');
        $builder->where('forms.deleted_at', null);

        // Auto-generate QR Code untuk form yang sudah published tapi belum ada QR Code
        $this->autoGenerateMissingQRCodes();

        // Filter berdasarkan level user
        if ($level == 0) {
            // Level 0 hanya melihat form yang dibuatnya sendiri atau form dari organisasinya
            $builder->groupStart();
            $builder->where('forms.user_id', $userId);
            // Jika user punya organisasi, tampilkan juga form dari organisasinya
            $builder->orGroupStart();
            $builder->where('forms.organisasi_id IN (SELECT organisasi_id FROM anggotas WHERE user_id = ' . $userId . ')', null, false);
            $builder->groupEnd();
            $builder->groupEnd();
        } elseif ($level == 2) {
            // Level 2 (Admin) hanya melihat form dari level admin juga
            $builder->where('users.level', 2);
        } elseif ($level == 1) {
            // Level 1 (SuperAdmin) hanya melihat form dari level superadmin
            $builder->where('users.level', 1);
        }

        $builder->orderBy('forms.created_at', 'DESC');
        $forms = $builder->get()->getResultArray();

        // Tambahkan user_level ke setiap form untuk grouping
        foreach ($forms as &$form) {
            if (!isset($form['user_level'])) {
                $user = $this->db->table('users')->where('id', $form['user_id'])->get()->getRowArray();
                $form['user_level'] = $user['level'] ?? 0;
            }
        }

        $data = [
            'title' => 'Gform',
            'data' => $forms,
            'unreadCount' => $unreadCount,
        ];

        return view('page/form/index', $data);
    }

    public function create()
    {
        if ($redirect = $this->checkLogin())
            return $redirect;

        $level = session()->get('level');

        // Get unread count for notification badge (only for level 0 and 2)
        $unreadCount = 0;
        if (in_array($level, [0, 2])) {
            $unreadCount = $this->pesanModel->getUnreadCount();
        }

        $data = [
            'title' => 'Tambah Form',
            'organisasis' => $this->organisasiModel->findAll(),
            'unreadCount' => $unreadCount,
        ];

        return view('page/form/create', $data);
    }

    public function store()
    {
        if ($redirect = $this->checkLogin())
            return $redirect;

        $rules = [
            'title' => 'required|max_length[255]',
            'form_type' => 'required|in_list[absensi,quiz,survey,pendaftaran,umum]',
            'status' => 'required|in_list[draft,published,closed]',
            'organisasi_id' => 'permit_empty|integer',
            'description' => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userId = session()->get('id');
        $status = $this->request->getPost('status');

        // Generate encrypted link dan QR Code hanya jika status published
        $encryptedLink = null;
        $qrCodePath = null;
        $publishedAt = null;

        if ($status == 'published') {
            // Generate encrypted link
            $encryptedLink = bin2hex(random_bytes(16));
            $publishedAt = date('Y-m-d H:i:s');

            log_message('info', 'Creating form with published status. Encrypted link: ' . $encryptedLink);
        } else {
            // Jika status bukan published, pastikan tidak ada encrypted link dan QR code
            $encryptedLink = null;
            $qrCodePath = null;
            $publishedAt = null;
        }

        // Handle header image upload
        $headerImagePath = null;
        $headerImage = $this->request->getFile('header_image');
        if ($headerImage && $headerImage->isValid() && !$headerImage->hasMoved()) {
            $newName = $headerImage->getRandomName();
            // Store in a dedicated directory
            $uploadPath = 'uploads/form_headers/';
            if (!is_dir(FCPATH . $uploadPath)) {
                mkdir(FCPATH . $uploadPath, 0777, true);
            }
            $headerImage->move(FCPATH . $uploadPath, $newName);
            $headerImagePath = $uploadPath . $newName;
        }

        // Simpan form terlebih dahulu
        $data = [
            'organisasi_id' => $this->request->getPost('organisasi_id') ?: null,
            'user_id' => $userId,
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'form_type' => $this->request->getPost('form_type'),
            'status' => $status,
            'encrypted_link' => $encryptedLink,
            'qr_code_path' => $qrCodePath,
            'published_at' => $publishedAt,
            'header_image_path' => $headerImagePath,
        ];

        $this->db->table('forms')->insert($data);
        $formId = $this->db->insertID();

        log_message('info', 'Form created with ID: ' . $formId . ', Status: ' . $status);

        // Generate QR Code SETELAH form disimpan (jika status published)
        $qrCodeGenerated = false;
        if ($status == 'published' && $encryptedLink) {
            log_message('info', 'Generating QR Code for new form ID: ' . $formId . ', Encrypted link: ' . $encryptedLink);

            // Generate QR Code dengan retry mechanism
            $maxRetries = 3;
            $retryCount = 0;
            $qrCodePath = null;

            while ($retryCount < $maxRetries && !$qrCodePath) {
                $retryCount++;
                log_message('debug', 'QR Code generation attempt ' . $retryCount . ' of ' . $maxRetries);

                $qrCodePath = $this->generateQRCodeFile($encryptedLink);

                if ($qrCodePath) {
                    // Normalize path untuk web (forward slash, tanpa leading slash)
                    $qrCodePath = str_replace('\\', '/', $qrCodePath);
                    $qrCodePath = preg_replace('/\/+/', '/', $qrCodePath);
                    $qrCodePath = ltrim($qrCodePath, '/');

                    // Verifikasi file benar-benar ada dan tidak kosong
                    $fullPath = FCPATH . $qrCodePath;
                    $fullPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $fullPath);
                    $fullPath = preg_replace('/[\/\\\]+/', DIRECTORY_SEPARATOR, $fullPath);

                    if (file_exists($fullPath)) {
                        $fileSize = filesize($fullPath);
                        if ($fileSize > 0) {
                            log_message('info', 'QR Code generated successfully: ' . $qrCodePath . ' (Size: ' . $fileSize . ' bytes)');

                            // Update database dengan QR code path
                            $this->db->table('forms')
                                ->where('id', $formId)
                                ->update(['qr_code_path' => $qrCodePath]);

                            log_message('info', 'QR Code path updated in database for form ID: ' . $formId);
                            $qrCodeGenerated = true;
                            break;
                        } else {
                            log_message('warning', 'QR Code file is empty, retrying... Path: ' . $fullPath);
                            @unlink($fullPath);
                            $qrCodePath = null;
                        }
                    } else {
                        log_message('warning', 'QR Code file does not exist, retrying... Path: ' . $fullPath);
                        log_message('debug', 'QR Code path from library: ' . $qrCodePath);
                        log_message('debug', 'FCPATH: ' . FCPATH);
                        $qrCodePath = null;
                    }
                } else {
                    log_message('warning', 'QR Code generation returned null/false, retrying... Attempt: ' . $retryCount);
                }

                // Tunggu sebentar sebelum retry (kecuali attempt terakhir)
                if ($retryCount < $maxRetries && !$qrCodePath) {
                    usleep(500000); // 0.5 detik
                }
            }

            if (!$qrCodeGenerated) {
                log_message('error', 'QR Code generation failed after ' . $maxRetries . ' attempts for form ID: ' . $formId);
                log_message('error', 'Form ID: ' . $formId . ', Encrypted Link: ' . $encryptedLink);
                log_message('error', 'Please check:');
                log_message('error', '1. Directory permissions for uploads/qrcode/');
                log_message('error', '2. PHP GD extension is installed');
                log_message('error', '3. Library chillerlan/php-qrcode is properly installed');
            } else {
                // Verifikasi sekali lagi bahwa QR Code benar-benar ada di database
                $formCheck = $this->db->table('forms')->where('id', $formId)->get()->getRowArray();
                if ($formCheck && !empty($formCheck['qr_code_path'])) {
                    $finalQrPath = FCPATH . $formCheck['qr_code_path'];
                    $finalQrPath = str_replace('/', DIRECTORY_SEPARATOR, $finalQrPath);
                    if (file_exists($finalQrPath) && filesize($finalQrPath) > 0) {
                        log_message('info', 'QR Code verified in database for form ID: ' . $formId . ', Path: ' . $formCheck['qr_code_path']);
                    } else {
                        log_message('warning', 'QR Code path in database but file not found: ' . $finalQrPath);
                    }
                }
            }
        }

        // Set flash message berdasarkan status dan QR code
        if ($status == 'published') {
            if ($qrCodeGenerated) {
                session()->setFlashdata('success', 'Form berhasil dibuat dan QR Code berhasil digenerate secara otomatis! QR Code dapat dilihat di halaman index.');
            } else {
                session()->setFlashdata('warning', 'Form berhasil dibuat, namun QR Code gagal digenerate. Silakan klik tombol "QR & Link" di halaman index untuk generate QR Code secara manual.');
            }
        } else {
            session()->setFlashdata('success', 'Form berhasil dibuat sebagai draft.');
        }

        // Save form fields if provided
        $fieldsData = $this->request->getPost('fields_data', FILTER_UNSAFE_RAW);
        log_message('error', 'Store Fields Data: ' . $fieldsData);
        if ($fieldsData) {
            $fields = json_decode($fieldsData, true);
            if (is_array($fields) && !empty($fields)) {
                foreach ($fields as $index => $field) {
                    helper('security'); // Load helper if not already loaded
                    $fieldData = [
                        'form_id' => $formId,
                        'field_type' => $field['type'],
                        'label' => clean_html($field['label']),
                        'placeholder' => $field['placeholder'] ?? null,
                        'description' => isset($field['description']) ? clean_html($field['description']) : null,
                        'options' => !empty($field['options']) ? json_encode($field['options']) : null,
                        'is_required' => $field['isRequired'] ?? false,
                        'order' => $index,
                        'is_active' => true,
                    ];
                    $this->db->table('form_fields')->insert($fieldData);
                }
            }
        }

        // Flash message sudah di-set sebelumnya, redirect
        return redirect()->to(url_to('form.index'));
    }

    public function edit($id)
    {
        if ($redirect = $this->checkLogin())
            return $redirect;

        $form = $this->db->table('forms')->where('id', $id)->where('deleted_at', null)->get()->getRowArray();

        if (!$form) {
            return redirect()->to(url_to('form.index'))->with('error', 'Form tidak ditemukan');
        }

        // Check authorization
        $level = session()->get('level');
        $userId = session()->get('id');
        if ($level != 1 && $level != 2 && $form['user_id'] != $userId) {
            return redirect()->to(url_to('form.index'))->with('error', 'Anda tidak memiliki akses');
        }

        // Get unread count for notification badge (only for level 0 and 2)
        $unreadCount = 0;
        if (in_array($level, [0, 2])) {
            $unreadCount = $this->pesanModel->getUnreadCount();
        }

        // Get form fields
        $fields = $this->db->table('form_fields')
            ->where('form_id', $id)
            ->where('is_active', true)
            ->orderBy('order', 'ASC')
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'Edit Form',
            'form' => $form,
            'fields' => $fields,
            'organisasis' => $this->organisasiModel->findAll(),
            'unreadCount' => $unreadCount,
        ];

        return view('page/form/edit', $data);
    }

    public function update($id)
    {
        if ($redirect = $this->checkLogin())
            return $redirect;

        $form = $this->db->table('forms')->where('id', $id)->where('deleted_at', null)->get()->getRowArray();

        if (!$form) {
            return redirect()->to(url_to('form.index'))->with('error', 'Form tidak ditemukan');
        }

        // Check authorization
        $level = session()->get('level');
        $userId = session()->get('id');
        if ($level != 1 && $level != 2 && $form['user_id'] != $userId) {
            return redirect()->to(url_to('form.index'))->with('error', 'Anda tidak memiliki akses');
        }

        $rules = [
            'title' => 'required|max_length[255]',
            'form_type' => 'required|in_list[absensi,quiz,survey,pendaftaran,umum]',
            'status' => 'required|in_list[draft,published,closed]',
            'organisasi_id' => 'permit_empty|integer',
            'description' => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $encryptedLink = $form['encrypted_link'];
        $qrCodePath = $form['qr_code_path'];
        $publishedAt = $form['published_at'];
        $newStatus = $this->request->getPost('status');

        // Generate encrypted link dan QR Code jika status berubah ke published
        if ($newStatus == 'published') {
            // Generate encrypted link jika belum ada
            if (!$encryptedLink) {
                $encryptedLink = bin2hex(random_bytes(16));
            }

            // Generate QR Code jika belum ada atau file tidak ada
            $qrCodeFullPath = $qrCodePath ? (FCPATH . $qrCodePath) : null;
            if ($qrCodeFullPath) {
                $qrCodeFullPath = str_replace('/', DIRECTORY_SEPARATOR, $qrCodeFullPath);
            }

            if (!$qrCodePath || !$qrCodeFullPath || !file_exists($qrCodeFullPath) || filesize($qrCodeFullPath) == 0) {
                log_message('info', 'Generating QR Code for form update. Form ID: ' . $id . ', Encrypted link: ' . $encryptedLink);
                $qrCodePath = $this->generateQRCodeFile($encryptedLink);

                if ($qrCodePath) {
                    // Normalize path untuk web (forward slash, tanpa leading slash)
                    $qrCodePath = str_replace('\\', '/', $qrCodePath);
                    $qrCodePath = preg_replace('/\/+/', '/', $qrCodePath);
                    $qrCodePath = ltrim($qrCodePath, '/');

                    $fullPath = FCPATH . $qrCodePath;
                    $fullPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $fullPath);
                    $fullPath = preg_replace('/[\/\\\]+/', DIRECTORY_SEPARATOR, $fullPath);

                    // Verifikasi file benar-benar ada
                    if (!file_exists($fullPath)) {
                        log_message('warning', 'QR Code path returned but file does not exist: ' . $fullPath);
                        $qrCodePath = null;
                    } else if (filesize($fullPath) == 0) {
                        log_message('warning', 'QR Code file is empty: ' . $fullPath);
                        @unlink($fullPath);
                        $qrCodePath = null;
                    }
                }

                // Jika gagal, coba sekali lagi
                if (!$qrCodePath) {
                    usleep(500000); // 0.5 detik delay
                    $qrCodePath = $this->generateQRCodeFile($encryptedLink);

                    if ($qrCodePath) {
                        // Normalize path untuk web (forward slash, tanpa leading slash)
                        $qrCodePath = str_replace('\\', '/', $qrCodePath);
                        $qrCodePath = preg_replace('/\/+/', '/', $qrCodePath);
                        $qrCodePath = ltrim($qrCodePath, '/');
                    }
                }
            }

            // Set published_at jika belum ada
            if (!$publishedAt) {
                $publishedAt = date('Y-m-d H:i:s');
            }
        } elseif ($newStatus == 'draft') {
            // Jika status berubah ke draft, enkripsi link dan qr code dibiarkan (opsional)
            // Tapi requirement di store set null. Di sini kita biarkan saja agar tidak hilang jika publish lagi.
        }

        // Handle header image upload
        $headerImage = $this->request->getFile('header_image');
        $headerImagePath = $form['header_image_path'] ?? null; // Default to existing

        if ($headerImage && $headerImage->isValid() && !$headerImage->hasMoved()) {
            // Delete old image if exists
            if (!empty($form['header_image_path']) && file_exists(FCPATH . $form['header_image_path'])) {
                @unlink(FCPATH . $form['header_image_path']);
            }

            $newName = $headerImage->getRandomName();
            $uploadPath = 'uploads/form_headers/';
            if (!is_dir(FCPATH . $uploadPath)) {
                mkdir(FCPATH . $uploadPath, 0777, true);
            }
            $headerImage->move(FCPATH . $uploadPath, $newName);
            $headerImagePath = $uploadPath . $newName;
        }

        $data = [
            'organisasi_id' => $this->request->getPost('organisasi_id') ?: null,
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'form_type' => $this->request->getPost('form_type'),
            'status' => $this->request->getPost('status'),
            'encrypted_link' => $encryptedLink,
            'qr_code_path' => $qrCodePath,
            'published_at' => $publishedAt,
            'header_image_path' => $headerImagePath,
        ];

        $this->db->table('forms')->where('id', $id)->update($data);

        // Update form fields if provided
        $fieldsData = $this->request->getPost('fields_data', FILTER_UNSAFE_RAW);
        log_message('error', 'Update Fields Data: ' . $fieldsData);
        if ($fieldsData) {
            // 1. Get ALL old active fields to check for images to delete
            $oldFields = $this->db->table('form_fields')
                ->where('form_id', $id)
                ->where('is_active', true)
                ->get()
                ->getResultArray();

            // 2. Parse new fields
            $newFields = json_decode($fieldsData, true);

            // 3. Find images that were removed
            if (is_array($newFields)) {
                $this->cleanupUnusedImages($oldFields, $newFields);
            }

            // 4. Delete old fields (Hard delete to keep DB clean, or Soft delete if preferred. 
            //    Since we are replacing them entirely based on the editor state, Hard Delete of old active fields is often cleaner 
            //    unless we need history. The previous code used 'is_active'=>false. I will stick to that to allow "undo" logic if ever implemented, 
            //    or just permanent delete. User asked for "hapus". Let's Hard Delete the old 'is_active' ones to prevent bloat.)
            //    Actually, previous code did update(['is_active' => false]). Let's stick to that but maybe clean up truly old ones?
            //    Let's stick to the existing soft-delete pattern for fields to be safe, but we ALREADY deleted the images above.

            $this->db->table('form_fields')
                ->where('form_id', $id)
                ->update(['is_active' => false]);

            // 5. Insert new fields
            if (is_array($newFields) && !empty($newFields)) {
                foreach ($newFields as $index => $field) {
                    helper('security');
                    $fieldData = [
                        'form_id' => $id,
                        'field_type' => $field['type'],
                        'label' => clean_html($field['label']), // Contains HTML with images
                        'placeholder' => $field['placeholder'] ?? null,
                        'description' => isset($field['description']) ? clean_html($field['description']) : null,
                        'options' => !empty($field['options']) ? json_encode($field['options']) : null,
                        'is_required' => $field['isRequired'] ?? false,
                        'order' => $index,
                        'is_active' => true,
                    ];
                    $this->db->table('form_fields')->insert($fieldData);
                }
            }
        }

        // Flash message
        session()->setFlashdata('success', 'Form berhasil diupdate.');
        return redirect()->to(url_to('form.index'));
    }

    public function delete($id)
    {
        if ($redirect = $this->checkLogin())
            return $redirect;

        $form = $this->db->table('forms')->where('id', $id)->get()->getRowArray(); // Get including deleted for force delete logic if needed, but usually we just get active.

        if (!$form) {
            return redirect()->to(url_to('form.index'))->with('error', 'Form tidak ditemukan');
        }

        // Check authorization
        $level = session()->get('level');
        $userId = session()->get('id');
        if ($level != 1 && $level != 2 && $form['user_id'] != $userId) {
            return redirect()->to(url_to('form.index'))->with('error', 'Anda tidak memiliki akses');
        }

        // 1. Hapus Header Image
        if (!empty($form['header_image_path']) && file_exists(FCPATH . $form['header_image_path'])) {
            @unlink(FCPATH . $form['header_image_path']);
        }

        // 2. Hapus QR Code
        if (!empty($form['qr_code_path'])) {
            $qrCodeFullPath = FCPATH . ltrim($form['qr_code_path'], '/');
            if (file_exists($qrCodeFullPath)) {
                @unlink($qrCodeFullPath);
            }
        }

        // 3. Hapus Field Images (Embedded in HTML)
        $fields = $this->db->table('form_fields')->where('form_id', $id)->get()->getResultArray();
        foreach ($fields as $field) {
            $this->deleteImagesFromHtml($field['label']);
            $this->deleteImagesFromHtml($field['description']);
        }

        // 4. Hapus Uploaded Files in Responses (e.g. user uploaded files)
        // This is important if 'file' type fields existed.
        // Logic currently stores them in `uploads/form_files/`.
        // Note: Code does not explicitly track paths easily without parsing, but `form_responses` logic might need checking. 
        // We will skip deep response file cleanup for now unless specifically requested, as it requires parsing JSON/table structure of responses.
        // However, user said "file yang gambar ter upload". This likely refers to the FIELD images (creator side).

        // 5. Hard Delete Data
        $this->db->table('form_fields')->where('form_id', $id)->delete();
        $this->db->table('form_responses')->where('form_id', $id)->delete();
        $this->db->table('forms')->where('id', $id)->delete();

        return redirect()->to(url_to('form.index'))->with('success', 'Form dan data terkait berhasil dihapus secara permanen.');
    }

    // --- Helper Functions for Image Cleanup ---

    /**
     * Compare old fields and new fields to find deleted images and remove the files.
     */
    private function cleanupUnusedImages($oldFields, $newFields)
    {
        $oldImages = [];
        $newImages = [];

        // Collect all image paths from old fields
        foreach ($oldFields as $field) {
            $oldImages = array_merge($oldImages, $this->extractImagesFromHtml($field['label']));
            $oldImages = array_merge($oldImages, $this->extractImagesFromHtml($field['description']));
        }

        // Collect all image paths from new fields
        foreach ($newFields as $field) {
            $newImages = array_merge($newImages, $this->extractImagesFromHtml($field['label']));
            $newImages = array_merge($newImages, $this->extractImagesFromHtml($field['description'] ?? ''));
        }

        // Find images present in old but NOT in new
        $imagesToDelete = array_diff($oldImages, $newImages);

        // Delete them
        foreach ($imagesToDelete as $imagePath) {
            $fullPath = FCPATH . ltrim($imagePath, '/'); // Ensure proper path
            if (file_exists($fullPath)) {
                @unlink($fullPath);
            }
        }
    }

    /**
     * Extract image src paths matching our upload directory.
     */
    private function extractImagesFromHtml($html)
    {
        if (empty($html))
            return [];

        $images = [];
        // Regex to find images in uploads/form_images/
        // Matches src=".../uploads/form_images/filename.ext"
        // We only care about the relative path 'uploads/form_images/...'
        if (preg_match_all('/src=["\']([^"\']*uploads\/form_images\/[^"\']+)["\']/', $html, $matches)) {
            foreach ($matches[1] as $fullUrl) {
                // Extract relative path from URL if it contains base_url
                // Simple heuristic: look for 'uploads/form_images/' and everything after
                $pos = strpos($fullUrl, 'uploads/form_images/');
                if ($pos !== false) {
                    $images[] = substr($fullUrl, $pos);
                }
            }
        }
        return array_unique($images);
    }

    /**
     * Delete all images found in HTML content
     */
    private function deleteImagesFromHtml($html)
    {
        $images = $this->extractImagesFromHtml($html);
        foreach ($images as $imagePath) {
            $fullPath = FCPATH . ltrim($imagePath, '/');
            if (file_exists($fullPath)) {
                @unlink($fullPath);
            }
        }
    }

    public function response($id)
    {
        if ($redirect = $this->checkLogin())
            return $redirect;

        $form = $this->db->table('forms')->where('id', $id)->where('deleted_at', null)->get()->getRowArray();

        if (!$form) {
            return redirect()->to(url_to('form.index'))->with('error', 'Form tidak ditemukan');
        }

        // Check authorization
        $level = session()->get('level');
        $userId = session()->get('id');
        if ($level != 1 && $level != 2 && $form['user_id'] != $userId) {
            return redirect()->to(url_to('form.index'))->with('error', 'Anda tidak memiliki akses');
        }

        // Get responses
        $responses = $this->db->table('form_responses')
            ->where('form_id', $id)
            ->where('status', 'submitted')
            ->orderBy('submitted_at', 'DESC')
            ->get()
            ->getResultArray();

        // Get unread count for notification badge (only for level 0 and 2)
        $unreadCount = 0;
        if (in_array($level, [0, 2])) {
            $unreadCount = $this->pesanModel->getUnreadCount();
        }

        $data = [
            'title' => 'Response Form: ' . $form['title'],
            'form' => $form,
            'responses' => $responses ?? [],
            'unreadCount' => $unreadCount,
        ];

        return view('page/form/response', $data);
    }

    // Public routes (tanpa autentikasi)
    public function public($encryptedLink)
    {
        $form = $this->db->table('forms')
            ->where('encrypted_link', $encryptedLink)
            ->where('status', 'published')
            ->where('deleted_at', null)
            ->get()
            ->getRowArray();

        if (!$form) {
            return view('errors/html/error_404', [
                'message' => 'Form tidak ditemukan atau sudah ditutup'
            ]);
        }

        // Get fields
        $fields = $this->db->table('form_fields')
            ->where('form_id', $form['id'])
            ->where('is_active', 1)
            ->orderBy('order', 'ASC')
            ->get()
            ->getResultArray();

        $data = [
            'form' => $form,
            'fields' => $fields,
        ];

        return view('page/form/public', $data);
    }

    public function publicSubmit($encryptedLink)
    {
        $form = $this->db->table('forms')
            ->where('encrypted_link', $encryptedLink)
            ->where('status', 'published')
            ->where('deleted_at', null)
            ->get()
            ->getRowArray();

        if (!$form) {
            return redirect()->back()->with('error', 'Form tidak ditemukan atau sudah ditutup');
        }

        // Security checks
        // Honeypot check
        if ($this->request->getPost('website') !== '') {
            return redirect()->back()->with('error', 'Akses ditolak');
        }

        // Time-based validation
        $formStartTime = $this->request->getPost('form_start_time');
        if ($formStartTime && (time() - $formStartTime) < 5) {
            return redirect()->back()->with('error', 'Form dikirim terlalu cepat');
        }

        // Anti-spam: Check if IP address already submitted this form
        $ipAddress = $this->request->getIPAddress();
        $existingResponse = $this->db->table('form_responses')
            ->where('form_id', $form['id'])
            ->where('ip_address', $ipAddress)
            ->where('status', 'submitted')
            ->get()
            ->getRowArray();

        if ($existingResponse) {
            // Check if submitted within last 24 hours
            $submittedAt = strtotime($existingResponse['submitted_at']);
            $timeDiff = time() - $submittedAt;

            if ($timeDiff < 86400) { // 24 hours in seconds
                return redirect()->back()->with('error', 'Anda sudah mengisi form ini sebelumnya. Terima kasih atas partisipasi Anda!');
            }
        }

        // Check session flag for double submission prevention
        $sessionKey = 'form_submitted_' . $form['id'];
        if (session()->get($sessionKey)) {
            return redirect()->back()->with('error', 'Anda sudah mengisi form ini. Terima kasih atas partisipasi Anda!');
        }

        // Get fields
        $fields = $this->db->table('form_fields')
            ->where('form_id', $form['id'])
            ->where('is_active', 1)
            ->get()
            ->getResultArray();

        // Validate fields
        foreach ($fields as $field) {
            $fieldName = 'field_' . $field['id'];

            // 1. Check File Size (for ALL file fields, required or not)
            if ($field['field_type'] == 'file') {
                $file = $this->request->getFile($fieldName);
                // If a file is uploaded (isValid), check its size
                if ($file && $file->isValid()) {
                    // Limit: 5MB = 5 * 1024 = 5120 KB
                    if ($file->getSizeByUnit('kb') > 5120) {
                        return redirect()->back()->with('error', 'Ukuran file pada field "' . $field['label'] . '" terlalu besar. Maksimal 5MB.')->withInput();
                    }
                }
            }

            // 2. Check Required Fields
            if ($field['is_required']) {
                if ($field['field_type'] == 'file') {
                    // Validate file upload existence
                    $file = $this->request->getFile($fieldName);
                    if (!$file || !$file->isValid()) {
                        return redirect()->back()->with('error', 'Field ' . $field['label'] . ' wajib diisi')->withInput();
                    }
                } else {
                    // Validate other field types
                    $value = $this->request->getPost($fieldName);
                    if (empty($value)) {
                        return redirect()->back()->with('error', 'Field ' . $field['label'] . ' wajib diisi')->withInput();
                    }
                }
            }
        }

        // Create response
        $responseData = [
            'form_id' => $form['id'],
            'user_id' => session()->get('id') ?: null,
            'respondent_name' => $this->request->getPost('respondent_name') ?: null,
            'respondent_email' => $this->request->getPost('respondent_email') ?: null,
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()->getAgentString(),
            'status' => 'submitted',
            'submitted_at' => date('Y-m-d H:i:s'),
        ];

        $this->db->table('form_responses')->insert($responseData);
        $responseId = $this->db->insertID();

        // Save field responses
        foreach ($fields as $field) {
            $fieldName = 'field_' . $field['id'];
            $filePath = null;
            $value = null;

            // Handle file upload
            if ($field['field_type'] == 'file') {
                $file = $this->request->getFile($fieldName);
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $fileName = $file->getRandomName();
                    // Organize uploads by form ID
                    $filePath = 'uploads/form_files/form_' . $form['id'] . '/';

                    if (!is_dir(FCPATH . $filePath)) {
                        mkdir(FCPATH . $filePath, 0777, true);
                    }

                    $file->move(FCPATH . $filePath, $fileName);
                    $filePath = $filePath . $fileName;
                    $value = $file->getClientName(); // Store original filename
                }
            } else {
                // Handle other field types
                $value = $this->request->getPost($fieldName);

                // Handle array values (checkbox)
                if (is_array($value)) {
                    $value = json_encode($value);
                }
            }

            // Save field response if value exists or file was uploaded
            if ($value !== null || $filePath !== null) {
                $fieldResponseData = [
                    'response_id' => $responseId,
                    'field_id' => $field['id'],
                    'value' => $value !== null ? (is_string($value) ? $value : json_encode($value)) : null,
                    'file_path' => $filePath,
                ];

                $this->db->table('form_field_responses')->insert($fieldResponseData);
            }
        }

        // Set session flag to prevent double submission (persist for 24 hours)
        session()->set($sessionKey, true);
        session()->set($sessionKey . '_time', time());

        return redirect()->back()->with('success', 'Form berhasil dikirim. Terima kasih!');
    }

    // Helper method untuk generate QR Code
    private function generateQRCodeFile($encryptedLink)
    {
        try {
            // Pastikan encrypted link tidak kosong
            if (empty($encryptedLink)) {
                log_message('error', 'Encrypted link is empty, cannot generate QR code');
                return null;
            }

            $qrCodeFrom = new \App\Libraries\QrcodeFrom();

            // Generate full URL untuk form public - pastikan full URL dengan http/https
            // base_url() seharusnya sudah menghasilkan full URL, tapi kita pastikan
            $publicUrl = base_url('form/public/' . $encryptedLink);

            // Pastikan URL adalah full URL (dengan http/https)
            // Jika base_url belum menghasilkan full URL, tambahkan protocol dan host
            if (!preg_match('/^https?:\/\//', $publicUrl)) {
                // Dapatkan protocol dari request atau config
                $request = \Config\Services::request();
                $protocol = $request->getProtocol(); // 'http' atau 'https'
                $host = $request->getServer('HTTP_HOST') ?? 'localhost';

                // Jika URL dimulai dengan /, tambahkan protocol dan host
                if (strpos($publicUrl, '/') === 0) {
                    $publicUrl = $protocol . '://' . $host . $publicUrl;
                } else {
                    $publicUrl = $protocol . '://' . $host . '/' . ltrim($publicUrl, '/');
                }
            }

            log_message('info', 'Generating QR Code with full URL: ' . $publicUrl);
            log_message('debug', 'Encrypted link: ' . $encryptedLink);

            // Pastikan directory uploads/qrcode ada
            $qrcodeDir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'qrcode';
            $qrcodeDir = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $qrcodeDir);
            $qrcodeDir = preg_replace('/[\/\\\]+/', DIRECTORY_SEPARATOR, $qrcodeDir);

            if (!is_dir($qrcodeDir)) {
                log_message('info', 'Creating QR code directory: ' . $qrcodeDir);
                if (!mkdir($qrcodeDir, 0777, true)) {
                    log_message('error', 'Failed to create QR code directory: ' . $qrcodeDir);
                    return null;
                }
                log_message('info', 'QR code directory created successfully: ' . $qrcodeDir);
            }

            // Pastikan directory writable
            if (!is_writable($qrcodeDir)) {
                log_message('warning', 'QR code directory is not writable: ' . $qrcodeDir);
                @chmod($qrcodeDir, 0777);
                if (!is_writable($qrcodeDir)) {
                    log_message('error', 'QR code directory still not writable after chmod: ' . $qrcodeDir);
                    return null;
                }
                log_message('info', 'QR code directory is now writable: ' . $qrcodeDir);
            }

            $qrCodePath = 'uploads/qrcode/form_' . $encryptedLink . '.png';
            $fullPath = FCPATH . $qrCodePath;

            // Normalize path untuk Windows - pastikan konsisten
            $fullPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $fullPath);
            $fullPath = preg_replace('/[\/\\\]+/', DIRECTORY_SEPARATOR, $fullPath);

            log_message('debug', 'Generating QR Code - URL: ' . $publicUrl);
            log_message('debug', 'Generating QR Code - FullPath: ' . $fullPath);
            log_message('debug', 'Generating QR Code - FCPATH: ' . FCPATH);
            log_message('debug', 'Generating QR Code - Directory: ' . $qrcodeDir);

            // Generate QR Code menggunakan library baru
            log_message('info', 'Calling QrcodeFrom->generateFormQR with URL: ' . $publicUrl . ', Path: ' . $fullPath);
            $result = $qrCodeFrom->generateFormQR($publicUrl, $fullPath);

            if ($result === false) {
                log_message('error', 'QrcodeFrom->generateFormQR returned false for URL: ' . $publicUrl);
                log_message('error', 'Full path attempted: ' . $fullPath);
                log_message('error', 'Directory exists: ' . (is_dir($qrcodeDir) ? 'yes' : 'no'));
                log_message('error', 'Directory writable: ' . (is_writable($qrcodeDir) ? 'yes' : 'no'));
                return null;
            }

            // Normalize result path untuk web (forward slash)
            $result = str_replace('\\', '/', $result);
            $result = preg_replace('/\/+/', '/', $result);
            $result = ltrim($result, '/');

            // Verifikasi file benar-benar ada
            $fullResultPath = FCPATH . $result;
            $fullResultPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $fullResultPath);
            $fullResultPath = preg_replace('/[\/\\\]+/', DIRECTORY_SEPARATOR, $fullResultPath);

            if (!file_exists($fullResultPath)) {
                log_message('error', 'QR Code file not found after generation: ' . $fullResultPath);
                log_message('error', 'Expected path: ' . $fullPath);
                log_message('error', 'Returned path: ' . $result);
                log_message('error', 'FCPATH: ' . FCPATH);

                // Coba cek apakah file ada di lokasi lain
                if (file_exists($fullPath)) {
                    log_message('info', 'File found at expected path, updating result');
                    $result = str_replace(FCPATH, '', $fullPath);
                    $result = str_replace('\\', '/', $result);
                    $fullResultPath = $fullPath;
                } else {
                    return null;
                }
            }

            // Verifikasi file tidak kosong
            $fileSize = filesize($fullResultPath);
            if ($fileSize == 0) {
                log_message('error', 'QR Code file is empty: ' . $fullResultPath);
                @unlink($fullResultPath);
                return null;
            }

            // Library sudah return path relatif, langsung return
            log_message('info', 'QR Code generated successfully: ' . $result . ' (Size: ' . $fileSize . ' bytes)');
            log_message('info', 'Full path: ' . $fullResultPath);
            log_message('info', 'URL encoded in QR Code: ' . $publicUrl);
            return $result;
        } catch (\Exception $e) {
            log_message('error', 'QR Code Generation Error: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return null;
        } catch (\Throwable $e) {
            log_message('error', 'QR Code Generation Throwable Error: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return null;
        }
    }

    // Auto-generate QR Code untuk form yang sudah published tapi belum ada QR Code
    private function autoGenerateMissingQRCodes()
    {
        try {
            // Cari semua form yang status published
            $forms = $this->db->table('forms')
                ->where('status', 'published')
                ->where('deleted_at', null)
                ->get()
                ->getResultArray();

            log_message('debug', 'Checking ' . count($forms) . ' published forms for missing QR codes');

            foreach ($forms as $form) {
                $needsQRCode = false;
                $needsEncryptedLink = false;

                // Cek apakah encrypted link ada
                if (empty($form['encrypted_link'])) {
                    $needsEncryptedLink = true;
                    $needsQRCode = true;
                    log_message('debug', 'Form ID ' . $form['id'] . ' needs encrypted link');
                } else {
                    $encryptedLink = $form['encrypted_link'];
                }

                // Cek apakah QR Code path ada dan file benar-benar ada
                if (empty($form['qr_code_path'])) {
                    $needsQRCode = true;
                    log_message('debug', 'Form ID ' . $form['id'] . ' has no QR code path');
                } else {
                    // Normalize path untuk file check
                    $checkPath = ltrim($form['qr_code_path'], '/');
                    $checkPath = str_replace('\\', '/', $checkPath);
                    $qrCodeFullPath = FCPATH . $checkPath;
                    $qrCodeFullPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $qrCodeFullPath);
                    $qrCodeFullPath = preg_replace('/[\/\\\]+/', DIRECTORY_SEPARATOR, $qrCodeFullPath);

                    if (!file_exists($qrCodeFullPath)) {
                        $needsQRCode = true;
                        log_message('debug', 'Form ID ' . $form['id'] . ' QR code file does not exist: ' . $qrCodeFullPath);
                    } else {
                        // File ada, cek apakah kosong
                        if (filesize($qrCodeFullPath) == 0) {
                            $needsQRCode = true;
                            log_message('debug', 'Form ID ' . $form['id'] . ' QR code file is empty');
                        }
                    }
                }

                // Generate encrypted link jika diperlukan
                if ($needsEncryptedLink) {
                    $encryptedLink = bin2hex(random_bytes(16));
                    $this->db->table('forms')
                        ->where('id', $form['id'])
                        ->update(['encrypted_link' => $encryptedLink]);
                    log_message('info', 'Generated encrypted link for form ID: ' . $form['id']);
                }

                // Generate QR Code jika diperlukan
                if ($needsQRCode) {
                    log_message('info', 'Auto-generating QR Code for form ID: ' . $form['id'] . ', Title: ' . $form['title'] . ', Encrypted Link: ' . $encryptedLink);

                    // Generate dengan retry mechanism
                    $maxRetries = 3;
                    $retryCount = 0;
                    $qrCodePath = null;

                    while ($retryCount < $maxRetries && !$qrCodePath) {
                        $retryCount++;
                        log_message('debug', 'Auto-generate QR Code attempt ' . $retryCount . ' of ' . $maxRetries . ' for form ID: ' . $form['id']);

                        $qrCodePath = $this->generateQRCodeFile($encryptedLink);

                        if ($qrCodePath) {
                            // Normalize path untuk web (forward slash, tanpa leading slash)
                            $qrCodePath = str_replace('\\', '/', $qrCodePath);
                            $qrCodePath = preg_replace('/\/+/', '/', $qrCodePath);
                            $qrCodePath = ltrim($qrCodePath, '/');

                            $fullPath = FCPATH . $qrCodePath;
                            $fullPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $fullPath);
                            $fullPath = preg_replace('/[\/\\\]+/', DIRECTORY_SEPARATOR, $fullPath);

                            if (file_exists($fullPath) && filesize($fullPath) > 0) {
                                $this->db->table('forms')
                                    ->where('id', $form['id'])
                                    ->update(['qr_code_path' => $qrCodePath]);
                                log_message('info', 'QR Code auto-generated successfully for form ID: ' . $form['id'] . ', Path: ' . $qrCodePath . ', Size: ' . filesize($fullPath) . ' bytes');
                                break;
                            } else {
                                log_message('warning', 'QR Code file not found or empty after generation for form ID: ' . $form['id'] . ', Retrying...');
                                $qrCodePath = null;
                            }
                        } else {
                            log_message('warning', 'Failed to auto-generate QR Code for form ID: ' . $form['id'] . ', Attempt: ' . $retryCount);
                        }

                        if ($retryCount < $maxRetries && !$qrCodePath) {
                            usleep(500000); // 0.5 detik
                        }
                    }

                    if (!$qrCodePath) {
                        log_message('error', 'Failed to auto-generate QR Code after ' . $maxRetries . ' attempts for form ID: ' . $form['id']);
                    }
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Error in autoGenerateMissingQRCodes: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
        }
    }

    // Field Management Methods (untuk fitur dinamis field)
    public function storeField($formId)
    {
        if ($redirect = $this->checkLogin())
            return $redirect;

        // Implementation untuk tambah field
        // TODO: Implementasi lengkap
        return redirect()->back()->with('success', 'Field berhasil ditambahkan');
    }

    public function updateField($formId, $fieldId)
    {
        if ($redirect = $this->checkLogin())
            return $redirect;

        // Implementation untuk update field
        // TODO: Implementasi lengkap
        return redirect()->back()->with('success', 'Field berhasil diupdate');
    }

    public function deleteField($formId, $fieldId)
    {
        if ($redirect = $this->checkLogin())
            return $redirect;

        // Implementation untuk hapus field
        // TODO: Implementasi lengkap
        return redirect()->back()->with('success', 'Field berhasil dihapus');
    }

    public function reorderField($formId)
    {
        if ($redirect = $this->checkLogin())
            return $redirect;

        // Implementation untuk reorder field
        // TODO: Implementasi lengkap
        return redirect()->back()->with('success', 'Field berhasil diurutkan');
    }

    public function publish($id)
    {
        if ($redirect = $this->checkLogin())
            return $redirect;

        // Implementation untuk publish form
        // TODO: Implementasi lengkap
        return redirect()->back()->with('success', 'Form berhasil dipublish');
    }

    public function unpublish($id)
    {
        if ($redirect = $this->checkLogin())
            return $redirect;

        // Implementation untuk unpublish form
        // TODO: Implementasi lengkap
        return redirect()->back()->with('success', 'Form berhasil diunpublish');
    }

    public function generateQRCode($id)
    {
        if ($redirect = $this->checkLogin())
            return $redirect;

        $form = $this->db->table('forms')->where('id', $id)->where('deleted_at', null)->get()->getRowArray();

        if (!$form) {
            if ($this->request->isAJAX()) {
                return $this->respond(['success' => false, 'message' => 'Form tidak ditemukan'], 404);
            }
            return redirect()->to(url_to('form.index'))->with('error', 'Form tidak ditemukan');
        }

        // Check authorization
        $level = session()->get('level');
        $userId = session()->get('id');
        if ($level != 1 && $level != 2 && $form['user_id'] != $userId) {
            if ($this->request->isAJAX()) {
                return $this->respond(['success' => false, 'message' => 'Anda tidak memiliki akses'], 403);
            }
            return redirect()->to(url_to('form.index'))->with('error', 'Anda tidak memiliki akses');
        }

        // Generate encrypted link jika belum ada
        if (!$form['encrypted_link']) {
            $encryptedLink = bin2hex(random_bytes(16));
            $this->db->table('forms')->where('id', $id)->update(['encrypted_link' => $encryptedLink]);
        } else {
            $encryptedLink = $form['encrypted_link'];
        }

        // Generate QR Code dengan retry
        $maxRetries = 3;
        $retryCount = 0;
        $qrCodePath = null;

        while ($retryCount < $maxRetries && !$qrCodePath) {
            $retryCount++;
            $qrCodePath = $this->generateQRCodeFile($encryptedLink);

            if ($qrCodePath) {
                // Normalize path untuk web (forward slash)
                $qrCodePath = str_replace('\\', '/', $qrCodePath);
                $qrCodePath = preg_replace('/\/+/', '/', $qrCodePath);
                $qrCodePath = ltrim($qrCodePath, '/');

                // Verifikasi file benar-benar ada dan tidak kosong
                $fullPath = FCPATH . $qrCodePath;
                $fullPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $fullPath);
                $fullPath = preg_replace('/[\/\\\]+/', DIRECTORY_SEPARATOR, $fullPath);

                if (file_exists($fullPath)) {
                    $fileSize = filesize($fullPath);
                    if ($fileSize > 0) {
                        log_message('debug', 'QR Code file verified: ' . $fullPath . ' (Size: ' . $fileSize . ' bytes)');
                        break;
                    } else {
                        log_message('warning', 'QR Code file is empty, deleting: ' . $fullPath);
                        @unlink($fullPath);
                        $qrCodePath = null;
                    }
                } else {
                    log_message('warning', 'QR Code file does not exist: ' . $fullPath);
                    $qrCodePath = null;
                }
            }

            if ($retryCount < $maxRetries && !$qrCodePath) {
                usleep(500000); // 0.5 detik
            }
        }

        if ($qrCodePath) {
            // Update database dengan QR code path
            $updateData = ['qr_code_path' => $qrCodePath];

            // Jika form belum published, set status ke published juga
            if ($form['status'] != 'published') {
                $updateData['status'] = 'published';
                $updateData['published_at'] = date('Y-m-d H:i:s');
            }

            $this->db->table('forms')->where('id', $id)->update($updateData);

            log_message('info', 'QR Code generated successfully for form ID: ' . $id . ', Path: ' . $qrCodePath);

            if ($this->request->isAJAX()) {
                // Path sudah dinormalisasi di atas, langsung gunakan
                $urlPath = $qrCodePath; // Sudah dinormalisasi: forward slash, tanpa leading slash
                $qrCodeUrl = base_url($urlPath);

                log_message('info', 'AJAX Response - QR Code Path: ' . $qrCodePath);
                log_message('info', 'AJAX Response - URL Path: ' . $urlPath);
                log_message('info', 'AJAX Response - Full URL: ' . $qrCodeUrl);
                log_message('info', 'AJAX Response - FCPATH: ' . FCPATH);

                // Verifikasi file benar-benar ada sebelum return
                $verifyPath = FCPATH . $urlPath;
                $verifyPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $verifyPath);
                $verifyPath = preg_replace('/[\/\\\]+/', DIRECTORY_SEPARATOR, $verifyPath);

                log_message('info', 'AJAX Response - Verifying file: ' . $verifyPath);
                log_message('info', 'AJAX Response - File exists: ' . (file_exists($verifyPath) ? 'YES' : 'NO'));

                if (file_exists($verifyPath)) {
                    $fileSize = filesize($verifyPath);
                    log_message('info', 'AJAX Response - File size: ' . $fileSize . ' bytes');

                    if ($fileSize > 0) {
                        log_message('info', 'AJAX Response - File verified: ' . $verifyPath . ' (Size: ' . $fileSize . ' bytes)');

                        // Set response headers untuk JSON
                        $this->response->setContentType('application/json');

                        return $this->respond([
                            'success' => true,
                            'message' => 'QR Code berhasil digenerate',
                            'qr_code_url' => $qrCodeUrl,
                            'qr_code_path' => $qrCodePath,
                            'file_size' => $fileSize,
                            'debug' => [
                                'fcpath' => FCPATH,
                                'url_path' => $urlPath,
                                'verify_path' => $verifyPath,
                                'file_exists' => file_exists($verifyPath),
                                'file_size' => $fileSize
                            ]
                        ], 200);
                    } else {
                        log_message('error', 'AJAX Response - File is empty: ' . $verifyPath);
                        $this->response->setContentType('application/json');
                        return $this->respond([
                            'success' => false,
                            'message' => 'QR Code file kosong setelah generate',
                            'qr_code_url' => null,
                            'debug' => [
                                'verify_path' => $verifyPath,
                                'file_exists' => true,
                                'file_size' => 0
                            ]
                        ], 500);
                    }
                } else {
                    log_message('error', 'AJAX Response - File verification failed: ' . $verifyPath);
                    log_message('error', 'AJAX Response - Directory exists: ' . (is_dir(dirname($verifyPath)) ? 'YES' : 'NO'));
                    log_message('error', 'AJAX Response - Directory writable: ' . (is_writable(dirname($verifyPath)) ? 'YES' : 'NO'));

                    $this->response->setContentType('application/json');
                    return $this->respond([
                        'success' => false,
                        'message' => 'QR Code file tidak ditemukan setelah generate',
                        'qr_code_url' => null,
                        'debug' => [
                            'fcpath' => FCPATH,
                            'url_path' => $urlPath,
                            'verify_path' => $verifyPath,
                            'file_exists' => false,
                            'directory_exists' => is_dir(dirname($verifyPath)),
                            'directory_writable' => is_writable(dirname($verifyPath))
                        ]
                    ], 500);
                }
            }

            return redirect()->back()->with('success', 'QR Code berhasil digenerate');
        } else {
            // Log detailed error
            log_message('error', 'Failed to generate QR Code for form ID: ' . $id . ' after ' . $maxRetries . ' attempts');
            log_message('error', 'Encrypted link: ' . $encryptedLink);
            log_message('error', 'Form status: ' . $form['status']);

            $errorMessage = 'Gagal generate QR Code setelah ' . $maxRetries . ' kali percobaan. ';
            $errorMessage .= 'Silakan cek log untuk detail error atau coba lagi nanti.';

            if ($this->request->isAJAX()) {
                return $this->respond([
                    'success' => false,
                    'message' => $errorMessage,
                    'encrypted_link' => $encryptedLink,
                    'form_link' => base_url('form/public/' . $encryptedLink)
                ], 500);
            }
            return redirect()->back()->with('error', $errorMessage);
        }
    }

    // Method untuk view QR Code (public access)
    public function viewQRCode($filename)
    {
        $filePath = FCPATH . 'uploads/qrcode/' . $filename;

        if (!file_exists($filePath)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Return image dengan header yang benar
        $this->response->setHeader('Content-Type', 'image/png');
        $this->response->setHeader('Content-Length', filesize($filePath));
        $this->response->setHeader('Cache-Control', 'public, max-age=3600');

        return $this->response->setBody(file_get_contents($filePath));
    }

    public function exportExcel($id)
    {
        if ($redirect = $this->checkLogin())
            return $redirect;

        $form = $this->db->table('forms')->where('id', $id)->where('deleted_at', null)->get()->getRowArray();

        if (!$form) {
            return redirect()->back()->with('error', 'Form tidak ditemukan');
        }

        // Check authorization
        $level = session()->get('level');
        $userId = session()->get('id');
        if ($level != 1 && $level != 2 && $form['user_id'] != $userId) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses');
        }

        // Get responses
        $responses = $this->db->table('form_responses')
            ->where('form_id', $id)
            ->where('status', 'submitted')
            ->orderBy('submitted_at', 'ASC')
            ->get()
            ->getResultArray();

        // Get form fields
        $fields = $this->db->table('form_fields')
            ->where('form_id', $id)
            ->where('is_active', true)
            ->orderBy('order', 'ASC')
            ->get()
            ->getResultArray();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set Header Style
        $headerStyle = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'borders' => ['bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E0E0E0']
            ]
        ];

        // Header Rows
        $headers = ['No', 'Waktu Submit', 'Nama Responden', 'Email Responden', 'IP Address'];
        foreach ($fields as $field) {
            $headers[] = $field['label'];
        }

        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->applyFromArray($headerStyle);

        // Data Rows
        $row = 2;
        $no = 1;

        if (!empty($responses)) {
            foreach ($responses as $response) {
                $sheet->setCellValue('A' . $row, $no++);
                $sheet->setCellValue('B' . $row, $response['submitted_at']);
                $sheet->setCellValue('C' . $row, $response['respondent_name'] ?? '-');
                $sheet->setCellValue('D' . $row, $response['respondent_email'] ?? '-');
                $sheet->setCellValue('E' . $row, $response['ip_address']);

                // Get field values
                $col = 'F';
                foreach ($fields as $field) {
                    $fieldResponse = $this->db->table('form_field_responses')
                        ->where('response_id', $response['id'])
                        ->where('field_id', $field['id'])
                        ->get()
                        ->getRowArray();

                    $value = '-';
                    if ($fieldResponse) {
                        if ($field['field_type'] == 'file' && $fieldResponse['file_path']) {
                            // Helper text untuk file, bisa diganti full link jika perlu
                            $value = base_url($fieldResponse['file_path']);
                        } else {
                            $val = $fieldResponse['value'];
                            // Check json
                            $decoded = json_decode($val, true);
                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                $value = implode(', ', $decoded);
                            } else {
                                $value = $val;
                            }
                        }
                    }

                    $sheet->setCellValue($col . $row, $value);
                    $col++;
                }
                $row++;
            }
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'Response_' . preg_replace('/[^a-zA-Z0-9]/', '_', $form['title']) . '_' . date('Y-m-d_His');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function printResponse($id)
    {
        if ($redirect = $this->checkLogin())
            return $redirect;

        $form = $this->db->table('forms')->where('id', $id)->where('deleted_at', null)->get()->getRowArray();

        if (!$form) {
            return redirect()->back()->with('error', 'Form tidak ditemukan');
        }

        // Check authorization (Reuse logic)
        $level = session()->get('level');
        $userId = session()->get('id');
        if ($level != 1 && $level != 2 && $form['user_id'] != $userId) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses');
        }

        $responses = $this->db->table('form_responses')
            ->where('form_id', $id)
            ->where('status', 'submitted')
            ->orderBy('submitted_at', 'ASC')
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'Print Response: ' . $form['title'],
            'form' => $form,
            'responses' => $responses ?? [],
        ];

        return view('page/form/print_response', $data);
    }

    public function uploadImage()
    {
        if ($redirect = $this->checkLogin()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $file = $this->request->getFile('image');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Validate file type
            $mimeType = $file->getMimeType();
            if (strpos($mimeType, 'image/') !== 0) {
                return $this->response->setJSON(['success' => false, 'message' => 'Invalid file type. Only images are allowed.']);
            }

            $newName = $file->getRandomName();
            $uploadPath = 'uploads/form_images/';

            if (!is_dir(FCPATH . $uploadPath)) {
                mkdir(FCPATH . $uploadPath, 0777, true);
            }

            $file->move(FCPATH . $uploadPath, $newName);

            return $this->response->setJSON([
                'success' => true,
                'url' => base_url($uploadPath . $newName)
            ]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Upload failed']);
    }
}

