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
    private $mahasiswaModel;
    private $emailOtpModel;

    public function __construct()
    {
        $this->pemilihanModel = new PemilihanModel();
        $this->anggotaModel = new AnggotaModel;
        $this->pemilihanCalonModel = new PemilihanCalonModel();
        $this->pemilihanCalonSuaraModel = new \App\Models\PemilihanCalonSuaraModel();
        $this->mahasiswaModel = new \App\Models\MahasiswaModel();
        $this->emailOtpModel = new \App\Models\EmailOtpModel();
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

        $title = 'Pemilihan Calon';
        // Join the organisasi and anggota tables, and include pemilihan dates for clarity
        $data = $this->pemilihanCalonModel->select('pemilihan_calons.*, pemilihans.periode as pemilihan_periode, pemilihans.tanggal_mulai as voting_mulai, pemilihans.tanggal_akhir as voting_akhir, organisasis.name as organisasi_name, anggota_1.name as anggota_1_name, anggota_2.name as anggota_2_name')
            ->join('pemilihans', 'pemilihan_calons.pemilihan_id = pemilihans.id')
            ->join('organisasis', 'pemilihans.organisasi_id = organisasis.id')
            ->join('anggotas as anggota_1', 'pemilihan_calons.anggota_id_1 = anggota_1.id')
            ->join('anggotas as anggota_2', 'pemilihan_calons.anggota_id_2 = anggota_2.id')
            ->findAll();

        return view('page/pemilihan/calon/index', compact('title', 'data'));
    }

    public function getAnggotaByPemilihan($id)
    {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn'))
            return redirect()->to(url_to('login'));

        // Check if user is Admin or SuperAdmin (level 1 or 2)
        $level = session()->get('level');
        if (!in_array($level, [1, 2])) {
            return $this->response->setJSON(['error' => 'Anda tidak memiliki akses']);
        }

        $pemilihan = $this->pemilihanModel->find($id);
        $anggota = $this->anggotaModel->where('organisasi_id', $pemilihan['organisasi_id'])->findAll();
        return $this->response->setJSON($anggota);
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

        $title = 'Create Calon';
        // Get All pemilihan
        $pemilihans = $this->pemilihanModel->select('pemilihans.*, organisasis.name as organisasi_name')
            ->join('organisasis', 'pemilihans.organisasi_id = organisasis.id')
            ->orderBy('pemilihans.tanggal_mulai', 'DESC')
            ->findAll();

        return view('page/pemilihan/calon/create', compact('title', 'pemilihans'));
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
        // Handle file upload
        if ($this->request->getFile('gambar_1')->isValid()) {
            $file = $this->request->getFile('gambar_1');
            $fileName = $file->getRandomName();
            $file->move(FCPATH . 'uploads', $fileName);
            $data['gambar_1'] = $fileName;
        }
        // Handle file upload
        if ($this->request->getFile('gambar_2')->isValid()) {
            $file = $this->request->getFile('gambar_2');
            $fileName = $file->getRandomName();
            $file->move(FCPATH . 'uploads', $fileName);
            $data['gambar_2'] = $fileName;
        }
        $this->pemilihanCalonModel->insert($data);

        return redirect()->to(url_to('pemilihan.calon.index'))->with('success', 'Calon created successfully');
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

        $title = 'Edit Calon';
        $data = $this->pemilihanCalonModel->find($id);
        // Get all pemilihan
        $pemilihans = $this->pemilihanModel->select('pemilihans.*, organisasis.name as organisasi_name')
            ->join('organisasis', 'pemilihans.organisasi_id = organisasis.id')
            ->findAll();

        return view('page/pemilihan/calon/edit', compact('title', 'data', 'pemilihans'));
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
        // Handle file upload
        if ($this->request->getFile('gambar_1')->isValid()) {
            $file = $this->request->getFile('gambar_1');
            $fileName = $file->getRandomName();
            $file->move(FCPATH . 'uploads', $fileName);
            $data['gambar_1'] = $fileName;
        }
        // Handle file upload
        if ($this->request->getFile('gambar_2')->isValid()) {
            $file = $this->request->getFile('gambar_2');
            $fileName = $file->getRandomName();
            $file->move(FCPATH . 'uploads', $fileName);
            $data['gambar_2'] = $fileName;
        }
        $this->pemilihanCalonModel->update($id, $data);

        return redirect()->to(url_to('pemilihan.calon.index'))->with('success', 'Calon updated successfully');
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

        $this->pemilihanCalonModel->delete($id);

        return redirect()->to(url_to('pemilihan.calon.index'))->with('success', 'Calon deleted successfully');
    }

    public function getSuaraByPemilihan($id)
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

    public function deleteVote($pemilihanCalonId, $suaraId)
    {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(url_to('login'));
        }

        // Check if user is Admin or SuperAdmin (level 1 or 2)
        $level = session()->get('level');
        if (!in_array($level, [1, 2])) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Verify the vote belongs to this pemilihan_calon
        $suara = $this->pemilihanCalonSuaraModel
            ->where('pemilihan_calon_id', $pemilihanCalonId)
            ->where('id', $suaraId)
            ->first();

        if (!$suara) {
            return redirect()->back()
                ->with('error', 'Vote not found or doesn\'t belong to this election candidate');
        }

        try {
            $this->pemilihanCalonSuaraModel->delete($suaraId);
            return redirect()->back()
                ->with('success', 'Vote deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete vote: ' . $e->getMessage());
        }
    }

    // ======================
    // OTP Voting Endpoints
    // ======================

    public function requestOtp()
    {
        // Clean output buffer untuk memastikan tidak ada output sebelum JSON
        ob_clean();

        // Set content type untuk JSON response di awal
        header('Content-Type: application/json');

        try {
            $pemilihanCalonId = (int) $this->request->getPost('pemilihan_calon_id');

            // Cek apakah voting masih aktif
            $calon = $this->pemilihanCalonModel->select('pemilihans.tanggal_mulai, pemilihans.tanggal_akhir')
                ->join('pemilihans', 'pemilihan_calons.pemilihan_id = pemilihans.id')
                ->where('pemilihan_calons.id', $pemilihanCalonId)
                ->first();

            if ($calon && !empty($calon['tanggal_mulai']) && !empty($calon['tanggal_akhir'])) {
                $now = date('Y-m-d H:i:s');
                $mulai = strtotime($calon['tanggal_mulai']);
                $akhir = strtotime($calon['tanggal_akhir']);
                $sekarang = strtotime($now);

                if ($sekarang < $mulai) {
                    http_response_code(400);
                    echo json_encode(['message' => 'Voting belum dimulai. Voting akan dimulai pada ' . date('d F Y H:i', $mulai) . '.']);
                    exit;
                }

                if ($sekarang > $akhir) {
                    http_response_code(400);
                    echo json_encode(['message' => 'Voting sudah berakhir pada ' . date('d F Y H:i', $akhir) . '.']);
                    exit;
                }
            }

            // Bersihkan NIM dari format (hanya ambil angka)
            $nim = preg_replace('/\D/', '', trim((string) $this->request->getPost('nim')));
            $email = trim((string) $this->request->getPost('email'));
            $name = trim((string) $this->request->getPost('name'));

            // Validasi input
            if (empty($nim) || strlen($nim) !== 16) {
                http_response_code(400);
                echo json_encode(['message' => 'NIM harus 16 digit angka.']);
                exit;
            }

            if (empty($email)) {
                http_response_code(400);
                echo json_encode(['message' => 'Email wajib diisi.']);
                exit;
            }

            if (empty($name)) {
                http_response_code(400);
                echo json_encode(['message' => 'Nama wajib diisi.']);
                exit;
            }

            // Cari mahasiswa aktif berdasarkan NIM atau email
            $mhs = $this->mahasiswaModel
                ->groupStart()
                ->where('nim', $nim)
                ->orWhere('email', $email)
                ->groupEnd()
                ->where('status', 'aktif')
                ->first();

            if (!$mhs) {
                http_response_code(422);
                echo json_encode(['message' => 'Mahasiswa tidak ditemukan atau nonaktif.']);
                exit;
            }

            // Cek apakah user sudah pernah voting untuk pemilihan ini
            // Ambil pemilihan_id dari pemilihan_calon_id
            $calonData = $this->pemilihanCalonModel->select('pemilihan_id')->where('id', $pemilihanCalonId)->first();
            if ($calonData) {
                // Ambil semua pemilihan_calon_id yang memiliki pemilihan_id yang sama
                $calonIds = $this->pemilihanCalonModel->select('id')->where('pemilihan_id', $calonData['pemilihan_id'])->findColumn('id');

                // Cek apakah sudah ada suara dengan email yang sama untuk pemilihan ini
                $existingVote = $this->pemilihanCalonSuaraModel
                    ->whereIn('pemilihan_calon_id', $calonIds)
                    ->where('email', $mhs['email'])
                    ->first();

                if ($existingVote) {
                    http_response_code(409);
                    echo json_encode(['message' => 'Anda Sudah Voting']);
                    exit;
                }
            }

            // Rate limit sederhana: satu OTP per 60 detik per email
            $existing = $this->emailOtpModel->where('email', $mhs['email'])->where('purpose', 'vote')->orderBy('id', 'DESC')->first();
            if ($existing && !empty($existing['last_sent_at'])) {
                $last = strtotime($existing['last_sent_at']);
                if (time() - $last < 60) {
                    $remain = 60 - (time() - $last);
                    http_response_code(429);
                    echo json_encode(['message' => 'Terlalu sering. Coba lagi dalam ' . $remain . ' detik.']);
                    exit;
                }
            }

            // Generate 6 digit OTP
            $otp = random_int(100000, 999999);
            $hash = password_hash((string) $otp, PASSWORD_BCRYPT);

            // Simpan OTP
            $otpId = $this->emailOtpModel->insert([
                'email' => $mhs['email'],
                'otp_hash' => $hash,
                'purpose' => 'vote',
                'context_id' => $pemilihanCalonId,
                'resend_count' => $existing ? (int) $existing['resend_count'] + 1 : 0,
                'last_sent_at' => date('Y-m-d H:i:s'),
                'expires_at' => date('Y-m-d H:i:s', time() + 300), // 5 menit
                'created_ip' => $this->request->getIPAddress(),
                'user_agent' => $this->request->getUserAgent()->getAgentString(),
            ]);

            // Kirim email via SMTP Hostinger
            try {
                $emailService = \Config\Services::email();
                $emailService->clear();

                // Load email config
                $emailConfig = config('Email');

                // Set konfigurasi email
                $fromEmail = $emailConfig->SMTPUser ?? 'ormaonehorizonu@ormaone.com';
                $fromName = $emailConfig->fromName ?? 'OrmaOne E-Voting';

                $emailService->setFrom($fromEmail, $fromName);
                $emailService->setTo($mhs['email']);
                $emailService->setSubject('Kode OTP E-Voting (berlaku 5 menit)');

                // Template email HTML profesional
                $baseUrl = base_url();
                $logoUrl = $baseUrl . 'dist/landing/assets/img/logo1.png';

                $emailMessage = $this->generateOtpEmailTemplate($mhs['nama'], $otp, $logoUrl);

                $emailService->setMessage($emailMessage);
                $emailService->setMailType('html');

                // Coba kirim email
                $sent = $emailService->send(false);

                if (!$sent) {
                    // Log error detail
                    try {
                        $debug = $emailService->printDebugger(['headers']);
                        log_message('error', 'OTP email failed for ' . $mhs['email'] . ': ' . $debug);
                    } catch (\Throwable $e) {
                        log_message('error', 'OTP email failed for ' . $mhs['email'] . ': Could not get debug info');
                    }

                    // Tetap lanjutkan proses (OTP sudah disimpan di database)
                    // User bisa request OTP lagi jika email gagal
                    log_message('info', 'OTP generated but email failed: ' . $otp . ' for email: ' . $mhs['email']);
                } else {
                    log_message('info', 'OTP email sent successfully to: ' . $mhs['email']);
                }
            } catch (\Exception $emailError) {
                // Log error tapi jangan stop proses
                log_message('error', 'Email service error: ' . $emailError->getMessage());
                log_message('error', 'Email error trace: ' . $emailError->getTraceAsString());
                // OTP sudah disimpan, jadi tetap return success
            }

            // Buat atau perbarui suara pending (status 0) jika belum ada untuk pemilihan yang sama
            $calon = $this->pemilihanCalonModel->find($pemilihanCalonId);
            if ($calon) {
                // Cegah lebih dari 1 suara per pemilihan (cek berdasarkan email/NIM)
                $calonIds = $this->pemilihanCalonModel->select('id')->where('pemilihan_id', $calon['pemilihan_id'])->findColumn('id');
                $existingVote = $this->pemilihanCalonSuaraModel
                    ->whereIn('pemilihan_calon_id', $calonIds)
                    ->where('email', $mhs['email'])
                    ->first();
                if (!$existingVote) {
                    $this->pemilihanCalonSuaraModel->insert([
                        'pemilihan_calon_id' => $pemilihanCalonId,
                        'nim' => $mhs['nim'],
                        'name' => $mhs['nama'],
                        'email' => $mhs['email'],
                        'status' => 0,
                        'ip_address' => $this->request->getIPAddress(),
                        'user_agent' => $this->request->getUserAgent()->getAgentString(),
                    ]);
                } else {
                    // Update record pending yang ada (jika belum terverifikasi)
                    if ((int) $existingVote['status'] !== 1) {
                        $this->pemilihanCalonSuaraModel->update($existingVote['id'], [
                            'nim' => $mhs['nim'],
                            'name' => $mhs['nama'],
                        ]);
                    }
                }
            }

            // Return success response
            http_response_code(200);
            echo json_encode(['message' => 'OTP terkirim. Silakan cek email Anda.']);
            exit;

        } catch (\Exception $e) {
            // Log error untuk debugging
            log_message('error', 'OTP Request Error: ' . $e->getMessage());
            log_message('error', 'Stack Trace: ' . $e->getTraceAsString());
            log_message('error', 'File: ' . $e->getFile() . ' Line: ' . $e->getLine());

            // Return JSON error instead of HTML error page
            http_response_code(500);
            echo json_encode([
                'message' => 'Terjadi kesalahan sistem. Silakan coba lagi atau hubungi administrator.',
                'error' => CI_DEBUG ? $e->getMessage() : 'Internal server error'
            ]);
            exit;
        } catch (\Throwable $e) {
            // Catch semua error termasuk fatal error
            log_message('error', 'OTP Request Fatal Error: ' . $e->getMessage());
            log_message('error', 'Stack Trace: ' . $e->getTraceAsString());

            http_response_code(500);
            echo json_encode([
                'message' => 'Terjadi kesalahan sistem. Silakan coba lagi atau hubungi administrator.'
            ]);
            exit;
        }
    }

    public function verifyOtp()
    {
        // Clean output buffer untuk memastikan tidak ada output sebelum JSON
        ob_clean();

        // Set content type untuk JSON response di awal
        header('Content-Type: application/json');

        try {
            $pemilihanCalonId = (int) $this->request->getPost('pemilihan_calon_id');

            // Cek apakah voting masih aktif
            $calon = $this->pemilihanCalonModel->select('pemilihans.tanggal_mulai, pemilihans.tanggal_akhir')
                ->join('pemilihans', 'pemilihan_calons.pemilihan_id = pemilihans.id')
                ->where('pemilihan_calons.id', $pemilihanCalonId)
                ->first();

            if ($calon && !empty($calon['tanggal_mulai']) && !empty($calon['tanggal_akhir'])) {
                $now = date('Y-m-d H:i:s');
                $mulai = strtotime($calon['tanggal_mulai']);
                $akhir = strtotime($calon['tanggal_akhir']);
                $sekarang = strtotime($now);

                if ($sekarang < $mulai) {
                    http_response_code(400);
                    echo json_encode(['message' => 'Voting belum dimulai. Voting akan dimulai pada ' . date('d F Y H:i', $mulai) . '.']);
                    exit;
                }

                if ($sekarang > $akhir) {
                    http_response_code(400);
                    echo json_encode(['message' => 'Voting sudah berakhir pada ' . date('d F Y H:i', $akhir) . '.']);
                    exit;
                }
            }

            $email = trim((string) $this->request->getPost('email'));
            $otp = trim((string) $this->request->getPost('otp'));

            // Validasi input
            if (empty($email)) {
                http_response_code(400);
                echo json_encode(['message' => 'Email wajib diisi.']);
                exit;
            }

            if (empty($otp) || strlen($otp) !== 6 || !ctype_digit($otp)) {
                http_response_code(400);
                echo json_encode(['message' => 'OTP harus 6 digit angka.']);
                exit;
            }

            $otpRow = $this->emailOtpModel
                ->where('email', $email)
                ->where('purpose', 'vote')
                ->orderBy('id', 'DESC')
                ->first();

            if (!$otpRow) {
                http_response_code(400);
                echo json_encode(['message' => 'OTP tidak ditemukan.']);
                exit;
            }
            if (!empty($otpRow['used_at'])) {
                http_response_code(400);
                echo json_encode(['message' => 'OTP sudah digunakan.']);
                exit;
            }
            if (strtotime($otpRow['expires_at']) < time()) {
                http_response_code(400);
                echo json_encode(['message' => 'OTP kedaluwarsa.']);
                exit;
            }
            if (!password_verify($otp, $otpRow['otp_hash'])) {
                $this->emailOtpModel->update($otpRow['id'], ['attempt_count' => (int) $otpRow['attempt_count'] + 1]);
                http_response_code(400);
                echo json_encode(['message' => 'OTP salah.']);
                exit;
            }

            // Tandai OTP terpakai
            $this->emailOtpModel->update($otpRow['id'], ['used_at' => date('Y-m-d H:i:s')]);

            // Finalisasi suara: set status=1 (otomatis terkonfirmasi setelah OTP verified)
            $calon = $this->pemilihanCalonModel->find($pemilihanCalonId);
            $mhs = $this->mahasiswaModel->where('email', $email)->first();
            if ($calon && $mhs) {
                // Cegah lebih dari 1 suara per pemilihan (cek berdasarkan email/NIM)
                $calonIds = $this->pemilihanCalonModel->select('id')->where('pemilihan_id', $calon['pemilihan_id'])->findColumn('id');
                $vote = $this->pemilihanCalonSuaraModel
                    ->whereIn('pemilihan_calon_id', $calonIds)
                    ->where('email', $email)
                    ->first();

                if ($vote) {
                    // Update ke calon yang dipilih jika record sebelumnya pending pada calon lain
                    if ((int) $vote['pemilihan_calon_id'] === $pemilihanCalonId) {
                        $this->pemilihanCalonSuaraModel->update($vote['id'], [
                            'status' => 1,
                            'nim' => $mhs['nim'],
                            'name' => $mhs['nama'],
                        ]);
                    } else {
                        // Sudah ada suara pada pemilihan yang sama → tolak double vote
                        http_response_code(409);
                        echo json_encode(['message' => 'Anda sudah memberikan suara pada pemilihan ini.']);
                        exit;
                    }
                } else {
                    $this->pemilihanCalonSuaraModel->insert([
                        'pemilihan_calon_id' => $pemilihanCalonId,
                        'nim' => $mhs['nim'],
                        'name' => $mhs['nama'],
                        'email' => $mhs['email'],
                        'status' => 1,
                        'ip_address' => $this->request->getIPAddress(),
                        'user_agent' => $this->request->getUserAgent()->getAgentString(),
                    ]);
                }
            }

            // Return success response
            http_response_code(200);
            echo json_encode(['message' => 'Verifikasi berhasil. Suara Anda tercatat.']);
            exit;

        } catch (\Exception $e) {
            // Log error untuk debugging
            log_message('error', 'OTP Verify Error: ' . $e->getMessage());
            log_message('error', 'Stack Trace: ' . $e->getTraceAsString());
            log_message('error', 'File: ' . $e->getFile() . ' Line: ' . $e->getLine());

            // Return JSON error instead of HTML error page
            http_response_code(500);
            echo json_encode([
                'message' => 'Terjadi kesalahan sistem. Silakan coba lagi atau hubungi administrator.',
                'error' => CI_DEBUG ? $e->getMessage() : 'Internal server error'
            ]);
            exit;
        } catch (\Throwable $e) {
            // Catch semua error termasuk fatal error
            log_message('error', 'OTP Verify Fatal Error: ' . $e->getMessage());
            log_message('error', 'Stack Trace: ' . $e->getTraceAsString());

            http_response_code(500);
            echo json_encode([
                'message' => 'Terjadi kesalahan sistem. Silakan coba lagi atau hubungi administrator.'
            ]);
            exit;
        }
    }

    /**
     * Generate HTML email template untuk OTP
     */
    private function generateOtpEmailTemplate($nama, $otp, $logoUrl)
    {
        return '
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP E-Voting</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f5f5f5;
            line-height: 1.6;
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .email-header {
            padding: 40px 20px 20px;
            text-align: center;
            background-color: #ffffff;
        }
        .logo-container {
            margin-bottom: 20px;
        }
        .logo-container img {
            width: 60px;
            height: 60px;
            object-fit: contain;
            margin-bottom: 10px;
        }
        .brand-name {
            font-size: 28px;
            font-weight: bold;
            color: #333333;
            margin: 0;
            letter-spacing: -0.5px;
        }
        .email-body {
            padding: 30px 40px;
            color: #333333;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
            color: #666666;
        }
        .main-heading {
            font-size: 24px;
            font-weight: bold;
            color: #333333;
            margin-bottom: 20px;
            line-height: 1.4;
        }
        .instruction-text {
            font-size: 15px;
            color: #666666;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        .otp-button-container {
            text-align: center;
            margin: 30px 0;
        }
        .otp-button {
            display: inline-block;
            background-color: #980517;
            color: #ffffff !important;
            padding: 16px 40px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 2px;
            text-align: center;
            min-width: 200px;
        }
        .otp-code {
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 4px;
            margin-top: 10px;
            display: block;
        }
        .expiry-notice {
            font-size: 13px;
            color: #999999;
            text-align: center;
            margin-top: 20px;
            margin-bottom: 30px;
        }
        .fallback-section {
            margin-top: 30px;
            padding-top: 30px;
            border-top: 1px solid #eeeeee;
            font-size: 13px;
            color: #666666;
        }
        .fallback-text {
            margin-bottom: 10px;
        }
        .fallback-link {
            word-break: break-all;
            color: #980517;
            text-decoration: none;
        }
        .email-footer {
            padding: 20px 40px;
            text-align: center;
            background-color: #f9f9f9;
            border-top: 1px solid #eeeeee;
        }
        .copyright {
            font-size: 12px;
            color: #999999;
            margin: 0;
        }
        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 20px;
                border-radius: 4px;
            }
            .email-body {
                padding: 20px;
            }
            .email-footer {
                padding: 15px 20px;
            }
            .main-heading {
                font-size: 20px;
            }
            .otp-button {
                padding: 14px 30px;
                font-size: 16px;
            }
            .otp-code {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <div class="logo-container">
                <img src="' . htmlspecialchars($logoUrl, ENT_QUOTES, 'UTF-8') . '" alt="OrmaOne Logo">
            </div>
            <h1 class="brand-name">OrmaOne</h1>
        </div>
        
        <div class="email-body">
            <p class="greeting">Halo,</p>
            
            <h2 class="main-heading">Kode OTP E-Voting Anda</h2>
            
            <p class="instruction-text">
                Terima kasih telah menggunakan sistem E-Voting OrmaOne. Untuk menyelesaikan proses voting, 
                silakan gunakan kode OTP di bawah ini.
            </p>
            
            <div class="otp-button-container">
                <a href="#" class="otp-button" style="background-color: #980517; color: #ffffff !important; padding: 18px 50px; border-radius: 8px; text-decoration: none; display: inline-block; font-size: 18px; font-weight: bold; min-width: 250px; text-align: center;">
                    <span style="display: block; font-size: 16px; font-weight: 600; margin-bottom: 12px; letter-spacing: 1px;">Kode OTP</span>
                    <span style="display: block; font-size: 36px; font-weight: bold; letter-spacing: 8px; font-family: monospace; color: #ffffff !important;">' . htmlspecialchars($otp, ENT_QUOTES, 'UTF-8') . '</span>
                </a>
            </div>
            
            <p class="expiry-notice">
                Kode OTP berlaku 5 menit sejak dikirimkan. Jangan bagikan kode ini kepada siapa pun.
            </p>
            
            <div class="fallback-section">
                <p class="fallback-text">
                    <strong>Penting:</strong> Jika Anda tidak meminta kode OTP ini, abaikan email ini.
                </p>
            </div>
        </div>
        
        <div class="email-footer">
            <p class="copyright">' . date('Y') . ' © OrmaOne E-Voting System. All Rights Reserved.</p>
        </div>
    </div>
</body>
</html>';
    }

}
