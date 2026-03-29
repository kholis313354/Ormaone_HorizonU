<?php

namespace App\Models;

use CodeIgniter\Model;

class KalenderModel extends Model
{
    protected $table = 'kalender';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id',
        'event_title',
        'event_color',
        'start_date',
        'end_date',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $returnType = 'array';

    /**
     * Get events filtered by user level
     * Level 0: hanya melihat event mereka sendiri
     * Level 2: melihat semua event dari user level 0 (dengan name, email)
     * Level 1: tidak memiliki akses (redirect di controller)
     *
     * @param int|null $level Level user
     * @param int|null $userId ID user
     * @param string|null $userName Nama user
     * @param string|null $userEmail Email user
     * @param string|null $startDate Tanggal mulai untuk filter (format: Y-m-d)
     * @param string|null $endDate Tanggal akhir untuk filter (format: Y-m-d)
     * @return array
     */
    public function getEventsByUser($level = null, $userId = null, $userName = null, $userEmail = null, $startDate = null, $endDate = null)
    {
        $builder = $this->builder();
        
        // Join dengan users untuk mendapatkan name dan email
        // Pastikan event_title tidak null dengan COALESCE
        $builder->select('kalender.id, kalender.user_id, COALESCE(kalender.event_title, \'\') as event_title, kalender.event_color, kalender.start_date, kalender.end_date, kalender.created_at, kalender.updated_at, users.name as user_name, users.email as user_email, users.level as user_level');
        $builder->join('users', 'users.id = kalender.user_id');
        
        // Filter berdasarkan level user
        if ($level !== null) {
            if ($level == 0) {
                // Level 0: hanya melihat event mereka sendiri
                if ($userId !== null) {
                    $builder->where('kalender.user_id', $userId);
                } elseif ($userName !== null) {
                    $builder->where('users.name', $userName);
                } elseif ($userEmail !== null) {
                    $builder->where('users.email', $userEmail);
                }
            } elseif ($level == 2) {
                // Level 2: melihat semua event dari user level 0 DAN event yang dibuat oleh mereka sendiri
                // Query: (users.level = 0) OR (kalender.user_id = userId AND users.level = 2)
                if ($userId !== null) {
                    $builder->groupStart();
                    $builder->where('users.level', 0);
                    $builder->orGroupStart();
                    $builder->where('kalender.user_id', $userId);
                    $builder->where('users.level', 2);
                    $builder->groupEnd();
                    $builder->groupEnd();
                } else {
                    // Fallback: jika userId tidak ada, hanya ambil event level 0
                    $builder->where('users.level', 0);
                }
            }
            // Level 1 tidak memiliki akses (sudah di-handle di controller dengan redirect)
        } else {
            // Jika level tidak diset, ambil semua event (untuk debugging)
            // Hapus komentar ini jika ingin debugging
            // log_message('debug', 'KalenderModel - No level filter applied');
        }
        
        // Debug: log query yang akan dijalankan
        log_message('debug', 'KalenderModel getEventsByUser - Level: ' . ($level ?? 'null') . ', UserId: ' . ($userId ?? 'null') . ', StartDate: ' . ($startDate ?? 'null') . ', EndDate: ' . ($endDate ?? 'null'));
        
        // Filter berdasarkan tanggal
        // Ambil semua event yang overlap dengan range tanggal yang diminta
        // Event ditampilkan jika: start_date <= endDate AND end_date >= startDate
        // FullCalendar mengirim end sebagai exclusive (tanggal pertama bulan berikutnya)
        if ($startDate !== null && $endDate !== null) {
            // End date dari FullCalendar adalah exclusive (tanggal pertama bulan berikutnya)
            // Jadi kita perlu memastikan event yang overlap ter-cover
            // Event overlap jika: start_date < endDate AND end_date >= startDate
            // Gunakan <= untuk endDate karena FullCalendar mengirim end sebagai exclusive
            $builder->where('DATE(kalender.start_date) <=', $endDate);
            $builder->where('DATE(kalender.end_date) >=', $startDate);
        } elseif ($startDate !== null) {
            // Jika hanya startDate, ambil event yang berakhir setelah startDate
            $builder->where('DATE(kalender.end_date) >=', $startDate);
        } elseif ($endDate !== null) {
            // Jika hanya endDate, ambil event yang mulai sebelum endDate
            $builder->where('DATE(kalender.start_date) <=', $endDate);
        }
        
        $builder->orderBy('kalender.start_date', 'ASC');
        
        // Debug: log SQL query
        $sql = $builder->getCompiledSelect(false);
        log_message('debug', 'KalenderModel SQL Query: ' . $sql);
        
        $query = $builder->get();
        $results = $query->getResultArray();
        
        // Debug: log hasil query
        log_message('debug', 'KalenderModel getEventsByUser - Found ' . count($results) . ' events');
        if (!empty($results)) {
            foreach ($results as $idx => $result) {
                log_message('debug', 'KalenderModel Event ' . $idx . ': ID=' . ($result['id'] ?? 'null') . ', Title=' . ($result['event_title'] ?? 'null') . ', UserId=' . ($result['user_id'] ?? 'null') . ', StartDate=' . ($result['start_date'] ?? 'null'));
            }
        }
        
        return $results;
    }

    /**
     * Get events for calendar view (month/week/day)
     *
     * @param int|null $level Level user
     * @param int|null $userId ID user
     * @param string|null $userName Nama user
     * @param string|null $userEmail Email user
     * @param string $viewType Type view: 'month', 'week', 'day'
     * @param string $date Date untuk view (format: Y-m-d)
     * @return array
     */
    public function getEventsForView($level = null, $userId = null, $userName = null, $userEmail = null, $viewType = 'month', $date = null)
    {
        if ($date === null) {
            $date = date('Y-m-d');
        }
        
        $startDate = null;
        $endDate = null;
        
        // Tentukan range tanggal berdasarkan view type
        switch ($viewType) {
            case 'month':
                // Bulan penuh
                $startDate = date('Y-m-01', strtotime($date));
                $endDate = date('Y-m-t', strtotime($date));
                break;
            case 'week':
                // Minggu (Senin - Minggu)
                $dayOfWeek = date('w', strtotime($date));
                $monday = date('Y-m-d', strtotime($date . ' -' . ($dayOfWeek == 0 ? 6 : $dayOfWeek - 1) . ' days'));
                $sunday = date('Y-m-d', strtotime($monday . ' +6 days'));
                $startDate = $monday;
                $endDate = $sunday;
                break;
            case 'day':
                // Hari tertentu
                $startDate = $date;
                $endDate = $date;
                break;
        }
        
        return $this->getEventsByUser($level, $userId, $userName, $userEmail, $startDate, $endDate);
    }

    /**
     * Get event by ID
     *
     * @param int $id
     * @return array|null
     */
    public function getEventById($id)
    {
        $builder = $this->builder();
        // Pastikan event_title tidak null dengan COALESCE
        $builder->select('kalender.id, kalender.user_id, COALESCE(kalender.event_title, \'\') as event_title, kalender.event_color, kalender.start_date, kalender.end_date, kalender.created_at, kalender.updated_at, users.name as user_name, users.email as user_email, users.level as user_level');
        $builder->join('users', 'users.id = kalender.user_id');
        $builder->where('kalender.id', $id);
        $query = $builder->get();
        
        $result = $query->getRowArray();
        
        // Pastikan event_title tidak null
        if ($result && empty($result['event_title'])) {
            $result['event_title'] = '';
        }
        
        return $result;
    }
}

