<?php

namespace App\Cells;

use App\Models\PemilihanCalonSuaraModel;
use App\Models\FormResponseModel;
use App\Models\SertifikatModel;
use App\Models\DocumentModel;
use App\Models\BeritaModel;
use App\Models\KalenderModel;
use App\Models\FormModel;
use App\Models\PesanModel;

class NotificationCell
{
    protected $pemilihanCalonSuaraModel;
    protected $formResponseModel;
    protected $sertifikatModel;
    protected $documentModel;
    protected $beritaModel;
    protected $kalenderModel;
    protected $formModel;
    protected $pesanModel;

    public function __construct()
    {
        $this->pemilihanCalonSuaraModel = new PemilihanCalonSuaraModel();
        $this->formResponseModel = new FormResponseModel();
        $this->sertifikatModel = new SertifikatModel();
        $this->documentModel = new DocumentModel();
        $this->beritaModel = new BeritaModel();
        $this->kalenderModel = new KalenderModel();
        $this->formModel = new FormModel();
        $this->pesanModel = new PesanModel();
    }

    public function render()
    {
        $level = session()->get('level');
        $userId = session()->get('id');

        // Jika belum login, kosongkan notifikasi
        if (!$userId) {
            return view('Cells/notification', ['notifications' => [], 'unreadCount' => 0]);
        }

        $notifications = $this->getNotifications($level, $userId);

        // Unread message count
        $unreadCount = 0;
        if (in_array($level, [0, 2])) {
            $unreadCount = $this->pesanModel->getUnreadCount();
        }

        return view('Cells/notification', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount
        ]);
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
