<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    private $anggotaModel;
    private $pemilihanModel;
    private $pemilihanCalonModel;
    private $pemilihanCalonSuaraModel;
    private $sertifikatModel;
    private $beritaModel;
    private $pesanModel;
    private $kalenderModel;
    private $documentModel;
    private $formModel;

    public function __construct()
    {
        $this->anggotaModel = new \App\Models\AnggotaModel();
        $this->pemilihanModel = new \App\Models\PemilihanModel();
        $this->pemilihanCalonModel = new \App\Models\PemilihanCalonModel();
        $this->pemilihanCalonSuaraModel = new \App\Models\PemilihanCalonSuaraModel();
        $this->sertifikatModel = new \App\Models\SertifikatModel();
        $this->beritaModel = new \App\Models\BeritaModel();
        $this->pesanModel = new \App\Models\PesanModel();
        $this->kalenderModel = new \App\Models\KalenderModel();
        $this->documentModel = new \App\Models\DocumentModel();
        $this->formModel = new \App\Models\FormModel();
    }

    public function index()
    {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn'))
            return redirect()->to(url_to('login'));
        $title = 'Dashboard';

        // Total Anggota
        $totalAnggota = $this->anggotaModel->countAll();
        // Total Document (was Total Pemilihan)
        $totalDocument = $this->documentModel->countAll();
        // Total Calon
        $totalCalon = $this->pemilihanCalonModel->countAll();
        // Total Suara
        $totalSuara = $this->pemilihanCalonSuaraModel->countAll();
        // Total Sertifikat
        $totalSertifikat = $this->sertifikatModel->countAll();
        // Total Berita/Blogger
        $totalBerita = $this->beritaModel->countAll();

        // Get unread count for notification badge (only for level 0 and 2)
        $unreadCount = 0;
        $level = session()->get('level');
        if (in_array($level, [0, 2])) {
            $unreadCount = $this->pesanModel->getUnreadCount();
        }

        // Get semua pemilihan aktif (status publish)
        $pemilihanAktif = $this->pemilihanModel->where('status', 'publish')
            ->orderBy('created_at', 'DESC')
            ->findAll();

        // Check if there's any pemilihan with status "selesai"
        $pemilihanSelesai = $this->pemilihanModel->where('status', 'selesai')
            ->orderBy('created_at', 'DESC')
            ->first();

        // Sum all suara dari semua pemilihan aktif
        $totalSuaras = [];
        $votingData = [];
        $showStatistik = false;

        if (!empty($pemilihanAktif)) {
            $showStatistik = true;

            // Ambil semua calon dari semua pemilihan aktif
            $pemilihanCalons = $this->pemilihanCalonModel->select('
                    pemilihan_calons.*,
                    pemilihans.status as pemilihan_status,
                    anggota_1.name as anggota_1_name,
                    anggota_1.nim as anggota_1_nim,
                    anggota_2.name as anggota_2_name,
                    anggota_2.nim as anggota_2_nim'
            )
                ->join('pemilihans', 'pemilihan_calons.pemilihan_id = pemilihans.id')
                ->join('anggotas as anggota_1', 'pemilihan_calons.anggota_id_1 = anggota_1.id')
                ->join('anggotas as anggota_2', 'pemilihan_calons.anggota_id_2 = anggota_2.id')
                ->where('pemilihans.status', 'publish')
                ->findAll();

            foreach ($pemilihanCalons as $key => $value) {
                $total = $this->pemilihanCalonSuaraModel->where('pemilihan_calon_id', $value['id'])->where('status', '1')->countAllResults();

                // Gabungkan suara jika ada calon dengan nama yang sama dari pemilihan berbeda
                $calonKey = $value['anggota_1_name'] . ' & ' . $value['anggota_2_name'];
                if (isset($totalSuaras[$calonKey])) {
                    $totalSuaras[$calonKey] += $total;
                } else {
                    $totalSuaras[$calonKey] = $total;
                }

                // Get voting data for this calon
                $suaraCalon = $this->pemilihanCalonSuaraModel->select('name, nim, email')
                    ->where('pemilihan_calon_id', $value['id'])
                    ->where('status', '1')
                    ->orderBy('created_at', 'DESC')
                    ->findAll();

                $votingData = array_merge($votingData, $suaraCalon);
            }
        } elseif ($pemilihanSelesai) {
            // Jika tidak ada pemilihan aktif tapi ada yang selesai, sembunyikan statistik
            $showStatistik = false;
        }

        // --- Additional Data for Dashboard Layout ---

        // 1. Upcoming Events (Agenda)
        $upcomingEvents = $this->kalenderModel->getEventsByUser(null, null, null, null, date('Y-m-d'));
        // Limit manually since getEventsByUser returns array
        $upcomingEvents = array_slice($upcomingEvents, 0, 5);

        // 2. Recent Messages (Aspirasi)
        $recentMessages = $this->pesanModel->orderBy('created_at', 'DESC')
            ->limit(3)
            ->findAll();

        // 3. Recent Documents (Arsip)
        $recentDocs = $this->documentModel->orderBy('created_at', 'DESC')
            ->limit(5)
            ->findAll();

        // 4. Latest Berita (Ganti Active Forms)
        // Ambil berita terbaru (misal 5-10 item)
        $latestBerita = $this->beritaModel->orderBy('tanggal', 'DESC')
            ->limit(10)
            ->findAll();

        $hideFooter = true;

        // Notification Logic
        $notifications = $this->getNotifications($level, session()->get('id'));

        $data = [
            'title' => $title,
            'totalAnggota' => $totalAnggota,
            'totalDocument' => $totalDocument,
            'totalCalon' => $totalCalon,
            'totalSuara' => $totalSuara,
            'totalSertifikat' => $totalSertifikat,
            'totalBerita' => $totalBerita,
            'unreadCount' => $unreadCount,
            'notifications' => $notifications, // Pass notifications to view
            'pemilihanAktif' => $pemilihanAktif,
            'pemilihanSelesai' => $pemilihanSelesai,
            'showStatistik' => $showStatistik,
            'pemilihanCalons' => $showStatistik ? $pemilihanCalons : [],
            'totalSuaras' => $totalSuaras,
            'votingData' => $votingData,
            'upcomingEvents' => $upcomingEvents,
            'recentMessages' => $recentMessages,
            'recentDocs' => $recentDocs,
            'latestBerita' => $latestBerita,
            'hideFooter' => $hideFooter
        ];

        return view('page/dashboard', $data);
    }

    private function getNotifications($level, $userId)
    {
        $notifications = [];
        $db = \Config\Database::connect();

        // Helper untuk sensor nama
        $censorName = function ($name) {
            $parts = explode(' ', $name);
            if (count($parts) > 1) {
                return $parts[0] . ' ' . substr($parts[1], 0, 1) . '***';
            }
            return substr($name, 0, 1) . '***';
        };

        // Helper untuk format time ago
        $timeAgo = function ($datetime) {
            $time = strtotime($datetime);
            $diff = time() - $time;

            if ($diff < 60) {
                return 'Baru saja';
            }

            $intervals = [
                31536000 => 'tahun',
                2592000 => 'bulan',
                604800 => 'minggu',
                86400 => 'hari',
                3600 => 'jam',
                60 => 'menit'
            ];

            foreach ($intervals as $secs => $label) {
                $d = $diff / $secs;
                if ($d >= 1) {
                    $r = round($d);
                    return $r . ' ' . $label . ' yang lalu';
                }
            }
            return 'Baru saja';
        };

        // LEVEL 1: Voting & Form Responses (User's Forms)
        if ($level == 1) {
            // 1. Voting Data
            $votes = $db->table('pemilihan_calon_suara')
                ->select('name, created_at') // Ambil nama pemilih
                ->orderBy('created_at', 'DESC')
                ->limit(5)
                ->get()
                ->getResultArray();

            foreach ($votes as $vote) {
                $notifications[] = [
                    'icon' => 'fas fa-vote-yea text-primary',
                    'message' => $censorName($vote['name']) . ' telah memilih kandidat ini',
                    'time' => $timeAgo($vote['created_at']),
                    'link' => '#!',
                    'category' => 'Voting',
                    'timestamp' => strtotime($vote['created_at'])
                ];
            }

            // 2. Form Responses (Forms created by user)
            $forms = $this->formModel->where('user_id', $userId)->findAll();
            if (!empty($forms)) {
                $formIds = array_column($forms, 'id');
                $responses = $db->table('form_responses')
                    ->select('form_responses.*, forms.title')
                    ->join('forms', 'forms.id = form_responses.form_id')
                    ->whereIn('form_id', $formIds)
                    ->orderBy('submitted_at', 'DESC')
                    ->limit(5)
                    ->get()
                    ->getResultArray();

                foreach ($responses as $resp) {
                    $notifications[] = [
                        'icon' => 'fas fa-reply text-success',
                        'message' => 'Respon baru pada form: ' . esc($resp['title']),
                        'time' => $timeAgo($resp['submitted_at']),
                        'link' => url_to('form.response', $resp['form_id']),
                        'category' => 'Form',
                        'timestamp' => strtotime($resp['submitted_at'])
                    ];
                }
            }
        }

        // LEVEL 2: Docs, Calendar, Sertifikat, Berita, Form Responses (Admin Forms)
        if ($level == 2) {
            // 1. Documents (Category, Nama Organisasi)
            // Note: DocumentModel uses 'arsip_document' table
            $docs = $this->documentModel->select('arsip_document.*, organisasis.name as org_name')
                ->join('organisasis', 'organisasis.id = arsip_document.organisasi_id', 'left')
                ->orderBy('arsip_document.created_at', 'DESC')
                ->limit(3)
                ->findAll();

            foreach ($docs as $doc) {
                $notifications[] = [
                    'icon' => 'fas fa-file-alt text-info',
                    'message' => 'Dokumen baru: ' . esc($doc['kategori'] ?? 'Umum') . ' - ' . esc($doc['org_name']),
                    'time' => $timeAgo($doc['created_at']),
                    'link' => url_to('document.index'),
                    'category' => 'Document',
                    'timestamp' => strtotime($doc['created_at'])
                ];
            }

            // 2. Calendar (Date, Title, Org Name)
            // Kalender -> Users -> Organisasis
            $events = $this->kalenderModel->select('kalender.*, organisasis.name as org_name')
                ->join('users', 'users.id = kalender.user_id')
                ->join('organisasis', 'organisasis.id = users.organisasi_id', 'left')
                ->orderBy('kalender.created_at', 'DESC')
                ->limit(3)
                ->findAll();

            foreach ($events as $event) {
                $orgName = !empty($event['org_name']) ? $event['org_name'] : 'Admin';
                $title = !empty($event['event_title']) ? $event['event_title'] : 'Kegiatan';
                $notifications[] = [
                    'icon' => 'fas fa-calendar-alt text-warning',
                    'message' => 'Event: ' . esc($title) . ' (' . date('d M', strtotime($event['start_date'])) . ') - ' . esc($orgName),
                    'time' => $timeAgo($event['created_at']),
                    'link' => url_to('kalender.index'),
                    'category' => 'Kalender',
                    'timestamp' => strtotime($event['created_at'])
                ];
            }

            // 3. E-Sertifikat (Nama Sertifikat, Org Name)
            // Asumsi: sertifikat dibuat oleh user yang terhubung ke organisasi, atau kita ambil dari session/join user
            // Disini tampilkan "Sertifikat telah dibuat oleh [Nama User/Org]"
            // Kita pakai user_name dari tabel sertifikat saja utk simpel
            $certs = $this->sertifikatModel->orderBy('created_at', 'DESC')->limit(3)->findAll();
            foreach ($certs as $cert) {
                // Untuk "Nama Organisasi", kita bisa ambil dari relasi fakultas atau user. 
                // Request: "menampilkan Sertifikat telah di buat oleh nama organisasi"
                // Saya gunakan user_name sebagai proxy atau "Administrator" jika kosong
                $creator = !empty($cert['user_name']) ? $cert['user_name'] : 'Admin';
                $notifications[] = [
                    'icon' => 'fas fa-certificate text-success',
                    'message' => 'Sertifikat telah dibuat oleh ' . esc($creator),
                    'time' => $timeAgo($cert['created_at']),
                    'link' => url_to('sertifikat.index'),
                    'category' => 'Sertifikat',
                    'timestamp' => strtotime($cert['created_at'])
                ];
            }

            // 4. Berita (Berita telah dibuat oleh nama organisasi)
            // Berita -> Users -> Organisasis (atau via Fakultas jika preferensi user)
            // Kita pakai Users -> Organisasis agar konsisten
            $news = $this->beritaModel->select('berita.*, organisasis.name as org_name')
                ->join('users', 'users.id = berita.user_id')
                ->join('organisasis', 'organisasis.id = users.organisasi_id', 'left')
                ->orderBy('created_at', 'DESC')
                ->limit(3)
                ->findAll();

            foreach ($news as $n) {
                $orgName = !empty($n['org_name']) ? $n['org_name'] : 'Admin';
                $notifications[] = [
                    'icon' => 'fas fa-newspaper text-danger',
                    'message' => 'Berita telah dibuat oleh ' . esc($orgName),
                    'time' => $timeAgo($n['created_at']),
                    'link' => url_to('berita.index'),
                    'category' => 'Berita',
                    'timestamp' => strtotime($n['created_at'])
                ];
            }

            // 5. Form Responses (All/Admin forms)
            $responses = $db->table('form_responses')
                ->select('form_responses.*, forms.title')
                ->join('forms', 'forms.id = form_responses.form_id')
                ->orderBy('submitted_at', 'DESC')
                ->limit(5)
                ->get()
                ->getResultArray();

            foreach ($responses as $resp) {
                $notifications[] = [
                    'icon' => 'fas fa-reply text-success',
                    'message' => 'Respon baru pada form: ' . esc($resp['title']),
                    'time' => $timeAgo($resp['submitted_at']),
                    'link' => url_to('form.response', $resp['form_id']),
                    'category' => 'Form',
                    'timestamp' => strtotime($resp['submitted_at'])
                ];
            }
        }

        // LEVEL 0: Form Responses (User's Forms)
        if ($level == 0) {
            $forms = $this->formModel->where('user_id', $userId)->findAll();
            if (!empty($forms)) {
                $formIds = array_column($forms, 'id');
                $responses = $db->table('form_responses')
                    ->select('form_responses.*, forms.title')
                    ->join('forms', 'forms.id = form_responses.form_id')
                    ->whereIn('form_id', $formIds)
                    ->orderBy('submitted_at', 'DESC')
                    ->limit(5)
                    ->get()
                    ->getResultArray();

                foreach ($responses as $resp) {
                    $notifications[] = [
                        'icon' => 'fas fa-reply text-success',
                        'message' => 'Respon baru pada form: ' . esc($resp['title']),
                        'time' => $timeAgo($resp['submitted_at']),
                        'link' => url_to('form.response', $resp['form_id']),
                        'category' => 'Form',
                        'timestamp' => strtotime($resp['submitted_at'])
                    ];
                }
            }
        }

        // Sort notifications by timestamp DESC
        usort($notifications, function ($a, $b) {
            return $b['timestamp'] - $a['timestamp'];
        });

        // Limit total display
        return array_slice($notifications, 0, 10);
    }
}