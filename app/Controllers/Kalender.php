<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KalenderModel;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;

class Kalender extends BaseController
{
    use ResponseTrait;

    protected $kalenderModel;
    protected $userModel;

    public function __construct()
    {
        $this->kalenderModel = new KalenderModel();
        $this->userModel = new UserModel();
        helper(['form', 'url', 'date']);
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
        
        // Level 1 (SuperAdmin) tidak memiliki akses ke Kalender
        if ($level == 1) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        $userId = session()->get('id');
        $userName = session()->get('name');
        $userEmail = session()->get('email');
        
        // Get view type (month, week, day) dan date dari query parameter
        $viewType = $this->request->getGet('view') ?? 'month';
        $date = $this->request->getGet('date') ?? date('Y-m-d');
        
        // Get events untuk view yang dipilih
        $events = $this->kalenderModel->getEventsForView($level, $userId, $userName, $userEmail, $viewType, $date);
        
        // Untuk level 2: group events by user level 0 untuk ditampilkan dengan pemisah
        // Hanya tampilkan event dari user level 0, bukan event yang dibuat oleh level 2 sendiri
        $groupedEvents = [];
        if ($level == 2) {
            foreach ($events as $event) {
                // Hanya group event dari user level 0
                $eventUserLevel = $event['user_level'] ?? null;
                if ($eventUserLevel == 0) {
                    $userKey = $event['user_id'] . '_' . $event['user_email'];
                    if (!isset($groupedEvents[$userKey])) {
                        $groupedEvents[$userKey] = [
                            'user_id' => $event['user_id'],
                            'user_name' => $event['user_name'],
                            'user_email' => $event['user_email'],
                            'events' => []
                        ];
                    }
                    $groupedEvents[$userKey]['events'][] = $event;
                }
            }
        }
        
        $title = 'Kalender Digital';
        $data = [
            'title' => $title,
            'events' => $events,
            'groupedEvents' => $groupedEvents,
            'level' => $level,
            'viewType' => $viewType,
            'currentDate' => $date,
            'userId' => $userId,
            'userName' => $userName,
            'userEmail' => $userEmail,
        ];

        return view('page/kalender/index', $data);
    }

    public function store()
    {
        if ($redirect = $this->checkLogin()) return $redirect;
        
        $rules = [
            'event_title' => 'required|max_length[255]',
            'event_color' => 'required|in_list[danger,success,primary,warning]',
            'start_date' => 'required|valid_date',
            'end_date' => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userId = session()->get('id');
        
        // Ambil dan validasi data
        $eventTitle = trim($this->request->getPost('event_title'));
        $eventColor = $this->request->getPost('event_color');
        $startDate = $this->request->getPost('start_date');
        $endDate = $this->request->getPost('end_date');
        
        // Pastikan format datetime sesuai (Y-m-d H:i:s)
        // Jika format datetime-local (Y-m-d\TH:i), konversi ke Y-m-d H:i:s
        if (strpos($startDate, 'T') !== false) {
            $startDate = str_replace('T', ' ', $startDate) . ':00';
        }
        if (strpos($endDate, 'T') !== false) {
            $endDate = str_replace('T', ' ', $endDate) . ':00';
        }
        
        $data = [
            'user_id' => $userId,
            'event_title' => $eventTitle,
            'event_color' => $eventColor,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
        
        // Debug: log data yang akan disimpan
        log_message('debug', 'Kalender store - Data: ' . json_encode($data));

        if ($this->kalenderModel->save($data)) {
            session()->setFlashdata('pesan', 'Event berhasil ditambahkan.');
            log_message('debug', 'Kalender store - Event saved successfully with ID: ' . $this->kalenderModel->getInsertID());
        } else {
            $errors = $this->kalenderModel->errors();
            log_message('error', 'Kalender store - Failed to save event. Errors: ' . json_encode($errors));
            session()->setFlashdata('error', 'Gagal menyimpan event: ' . implode(', ', $errors));
        }
        
        // Redirect kembali dengan view type dan date
        $viewType = $this->request->getPost('view_type') ?? 'month';
        $date = $this->request->getPost('current_date') ?? date('Y-m-d');
        return redirect()->to(url_to('kalender.index') . '?view=' . $viewType . '&date=' . $date);
    }

    public function update($id)
    {
        if ($redirect = $this->checkLogin()) return $redirect;
        
        $event = $this->kalenderModel->getEventById($id);
        
        if (!$event) {
            session()->setFlashdata('error', 'Event tidak ditemukan.');
            return redirect()->to(url_to('kalender.index'));
        }
        
        // Check permission
        $level = session()->get('level');
        $userId = session()->get('id');
        $eventUserLevel = $event['user_level'] ?? null;
        
        if ($level == 0) {
            // Level 0 hanya bisa edit event sendiri
            if ($event['user_id'] != $userId) {
                session()->setFlashdata('error', 'Anda tidak memiliki akses untuk mengedit event ini.');
                return redirect()->to(url_to('kalender.index'));
            }
        } elseif ($level == 2) {
            // Level 2 hanya bisa edit event yang dibuat oleh mereka sendiri
            // Tidak bisa edit event yang dibuat oleh user level 0
            if ($event['user_id'] != $userId) {
                session()->setFlashdata('error', 'Anda tidak memiliki akses untuk mengedit event ini. Anda hanya dapat mengedit event yang Anda buat sendiri.');
                return redirect()->to(url_to('kalender.index'));
            }
        }
        
        $rules = [
            'event_title' => 'required|max_length[255]',
            'event_color' => 'required|in_list[danger,success,primary,warning]',
            'start_date' => 'required|valid_date',
            'end_date' => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'id' => $id,
            'event_title' => $this->request->getPost('event_title'),
            'event_color' => $this->request->getPost('event_color'),
            'start_date' => $this->request->getPost('start_date'),
            'end_date' => $this->request->getPost('end_date'),
        ];

        if ($this->kalenderModel->save($data)) {
            session()->setFlashdata('pesan', 'Event berhasil diupdate.');
        } else {
            session()->setFlashdata('error', 'Gagal mengupdate event.');
        }
        
        // Redirect kembali dengan view type dan date
        $viewType = $this->request->getPost('view_type') ?? 'month';
        $date = $this->request->getPost('current_date') ?? date('Y-m-d');
        return redirect()->to(url_to('kalender.index') . '?view=' . $viewType . '&date=' . $date);
    }

    public function delete($id)
    {
        if ($redirect = $this->checkLogin()) return $redirect;
        
        $event = $this->kalenderModel->getEventById($id);
        
        if (!$event) {
            session()->setFlashdata('error', 'Event tidak ditemukan.');
            return redirect()->to(url_to('kalender.index'));
        }
        
        // Check permission
        $level = session()->get('level');
        $userId = session()->get('id');
        $eventUserLevel = $event['user_level'] ?? null;
        
        if ($level == 0) {
            // Level 0 hanya bisa delete event sendiri
            if ($event['user_id'] != $userId) {
                session()->setFlashdata('error', 'Anda tidak memiliki akses untuk menghapus event ini.');
                return redirect()->to(url_to('kalender.index'));
            }
        } elseif ($level == 2) {
            // Level 2 hanya bisa delete event yang dibuat oleh mereka sendiri
            // Tidak bisa delete event yang dibuat oleh user level 0
            if ($event['user_id'] != $userId) {
                session()->setFlashdata('error', 'Anda tidak memiliki akses untuk menghapus event ini. Anda hanya dapat menghapus event yang Anda buat sendiri.');
                return redirect()->to(url_to('kalender.index'));
            }
        }
        
        if ($this->kalenderModel->delete($id)) {
            session()->setFlashdata('pesan', 'Event berhasil dihapus.');
        } else {
            session()->setFlashdata('error', 'Gagal menghapus event.');
        }
        
        // Redirect kembali dengan view type dan date
        $viewType = $this->request->getGet('view') ?? 'month';
        $date = $this->request->getGet('date') ?? date('Y-m-d');
        return redirect()->to(url_to('kalender.index') . '?view=' . $viewType . '&date=' . $date);
    }

    // API untuk mendapatkan events dalam format JSON (untuk FullCalendar)
    public function getEvents()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->respond(['error' => 'Unauthorized'], 401);
        }
        
        $level = session()->get('level');
        $userId = session()->get('id');
        $userName = session()->get('name');
        $userEmail = session()->get('email');
        
        // Get date range dari query parameter
        $start = $this->request->getGet('start') ?? date('Y-m-d');
        $end = $this->request->getGet('end') ?? date('Y-m-d', strtotime('+1 month'));
        
        // FullCalendar mengirim end sebagai exclusive (tanggal pertama bulan berikutnya)
        // Untuk filter, kita tetap gunakan end as-is karena filter sudah handle overlap dengan benar
        // Tapi untuk memastikan semua event ter-cover, kita bisa extend sedikit
        
        // Debug: log parameter sebelum query
        log_message('debug', 'Kalender getEvents - Before query - Level: ' . $level . ', UserId: ' . $userId . ', UserName: ' . ($userName ?? 'null') . ', UserEmail: ' . ($userEmail ?? 'null') . ', Start: ' . $start . ', End: ' . $end);
        
        $events = $this->kalenderModel->getEventsByUser($level, $userId, $userName, $userEmail, $start, $end);
        
        // Debug: log untuk troubleshooting
        log_message('debug', 'Kalender getEvents - Level: ' . $level . ', UserId: ' . $userId . ', Start: ' . $start . ', End: ' . $end . ', Events count: ' . count($events));
        
        // Log raw events untuk debugging
        if (!empty($events)) {
            log_message('debug', 'Kalender getEvents - Raw events: ' . json_encode($events));
        }
        
        // Format events untuk FullCalendar
        $formattedEvents = [];
        foreach ($events as $event) {
            // Log setiap event yang diproses
            log_message('debug', 'Kalender getEvents - Processing event: ' . json_encode($event));
            // Pastikan event_title tidak null dan di-trim
            $eventTitle = isset($event['event_title']) ? trim($event['event_title']) : '';
            
            // Jika event_title kosong, gunakan default title
            if (empty($eventTitle)) {
                $eventTitle = 'Event Tanpa Judul';
            }
            
            // Pastikan format tanggal sesuai ISO 8601 untuk FullCalendar
            $startDate = $event['start_date'] ?? date('Y-m-d H:i:s');
            $endDate = $event['end_date'] ?? date('Y-m-d H:i:s');
            
            // Jika start_date dan end_date sama (event satu hari), pastikan end_date adalah akhir hari
            $startDateOnly = date('Y-m-d', strtotime($startDate));
            $endDateOnly = date('Y-m-d', strtotime($endDate));
            
            if ($startDateOnly === $endDateOnly) {
                // Jika event hanya satu hari, pastikan end_date berbeda dari start_date
                // FullCalendar memerlukan end_date yang berbeda untuk menampilkan event dengan benar
                $startTime = date('H:i:s', strtotime($startDate));
                $endTime = date('H:i:s', strtotime($endDate));
                
                // Jika waktu sama atau 00:00:00, tambahkan minimal 1 jam atau set ke akhir hari
                if ($endTime === '00:00:00' || $endTime === $startTime) {
                    // Jika waktu sama atau 00:00:00, tambahkan 1 jam untuk memastikan event terlihat
                    $endDateTime = new \DateTime($endDate);
                    $endDateTime->modify('+1 hour');
                    $endDate = $endDateTime->format('Y-m-d H:i:s');
                    
                    // Jika setelah ditambah 1 jam masih di hari yang sama, gunakan itu
                    // Jika sudah melewati hari, set ke akhir hari (23:59:59)
                    $newEndDateOnly = date('Y-m-d', strtotime($endDate));
                    if ($newEndDateOnly !== $startDateOnly) {
                        // Jika sudah melewati hari, set ke akhir hari yang sama dengan start
                        $endDate = $startDateOnly . ' 23:59:59';
                    }
                } else {
                    // Jika sudah ada waktu spesifik dan berbeda, pastikan minimal 1 jam dari start
                    $startDateTime = new \DateTime($startDate);
                    $endDateTime = new \DateTime($endDate);
                    $diff = $endDateTime->getTimestamp() - $startDateTime->getTimestamp();
                    
                    // Jika selisih kurang dari 1 jam, tambahkan 1 jam
                    if ($diff < 3600) {
                        $endDateTime = clone $startDateTime;
                        $endDateTime->modify('+1 hour');
                        $endDate = $endDateTime->format('Y-m-d H:i:s');
                    }
                }
            }
            
            // Konversi format tanggal ke ISO 8601 jika belum
            if (strpos($startDate, 'T') === false) {
                // Jika format datetime (Y-m-d H:i:s), konversi ke ISO 8601
                if (strpos($startDate, ' ') !== false) {
                    $startDate = str_replace(' ', 'T', $startDate);
                } else {
                    // Jika hanya date (Y-m-d), tambahkan waktu default
                    $startDate = $startDate . 'T00:00:00';
                }
            }
            
            if (strpos($endDate, 'T') === false) {
                // Jika format datetime (Y-m-d H:i:s), konversi ke ISO 8601
                if (strpos($endDate, ' ') !== false) {
                    $endDate = str_replace(' ', 'T', $endDate);
                } else {
                    // Jika hanya date (Y-m-d), tambahkan waktu default
                    $endDate = $endDate . 'T23:59:59';
                }
            }
            
            // Pastikan timezone sesuai (Asia/Jakarta)
            try {
                // Buat DateTime dengan timezone Asia/Jakarta
                $timezone = new \DateTimeZone('Asia/Jakarta');
                
                // Parse tanggal dengan asumsi timezone Asia/Jakarta
                $startDateTime = new \DateTime($startDate, $timezone);
                $endDateTime = new \DateTime($endDate, $timezone);
                
                // Pastikan end_date tidak lebih kecil dari start_date
                if ($endDateTime < $startDateTime) {
                    // Jika end_date lebih kecil, set ke akhir hari yang sama dengan start_date
                    $endDateTime = clone $startDateTime;
                    $endDateTime->setTime(23, 59, 59);
                    log_message('debug', 'Kalender getEvents - End date adjusted for event ID: ' . ($event['id'] ?? 'null'));
                }
                
                // Format ke ISO 8601 untuk FullCalendar
                $startDate = $startDateTime->format('Y-m-d\TH:i:s');
                $endDate = $endDateTime->format('Y-m-d\TH:i:s');
            } catch (\Exception $e) {
                log_message('error', 'Error parsing date in getEvents: ' . $e->getMessage() . ' - StartDate: ' . $startDate . ', EndDate: ' . $endDate);
                // Fallback: gunakan format asli jika parsing gagal
            }
            
            $colorCode = $this->getColorCode($event['event_color'] ?? 'primary');
            
            // Pastikan end_date tidak sama dengan start_date untuk event satu hari
            // FullCalendar memerlukan end_date yang berbeda untuk menampilkan event dengan benar
            if ($startDate === $endDate) {
                // Jika start dan end sama, tambahkan 1 jam ke end_date
                try {
                    $endDateTime = new \DateTime($endDate);
                    $endDateTime->modify('+1 hour');
                    $endDate = $endDateTime->format('Y-m-d\TH:i:s');
                } catch (\Exception $e) {
                    // Fallback: tambahkan 1 jam secara manual
                    $endDate = date('Y-m-d\TH:i:s', strtotime($endDate . ' +1 hour'));
                }
            }
            
            $formattedEvent = [
                'id' => $event['id'] ?? null,
                'title' => $eventTitle, // Event title yang sudah di-validasi dan di-trim
                'start' => $startDate,
                'end' => $endDate,
                'allDay' => false, // Pastikan bukan allDay event
                'color' => $colorCode,
                'backgroundColor' => $colorCode,
                'borderColor' => $colorCode,
                'textColor' => '#ffffff', // Warna teks putih untuk kontras
                'extendedProps' => [
                    'user_id' => $event['user_id'] ?? null,
                    'user_name' => $event['user_name'] ?? '',
                    'user_email' => $event['user_email'] ?? '',
                    'user_level' => $event['user_level'] ?? 0,
                    'event_color' => $event['event_color'] ?? 'primary',
                    'event_title' => $eventTitle, // Simpan juga di extendedProps untuk referensi
                ]
            ];
            
            $formattedEvents[] = $formattedEvent;
            
            // Log setiap event yang diformat
            log_message('debug', 'Kalender getEvents - Formatted event: ' . json_encode($formattedEvent));
        }
        
        // Debug: log formatted events untuk troubleshooting
        log_message('debug', 'Kalender getEvents - Total formatted events: ' . count($formattedEvents));
        log_message('debug', 'Kalender getEvents - Formatted events: ' . json_encode($formattedEvents));
        
        return $this->respond($formattedEvents);
    }

    // Helper untuk mendapatkan color code
    private function getColorCode($color)
    {
        $colors = [
            'danger' => '#dc3545',
            'success' => '#28a745',
            'primary' => '#007bff',
            'warning' => '#ffc107',
        ];
        
        return $colors[$color] ?? '#007bff';
    }
}

