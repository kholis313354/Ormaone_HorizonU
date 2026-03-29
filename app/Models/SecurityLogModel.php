<?php

namespace App\Models;

use CodeIgniter\Model;

class SecurityLogModel extends Model
{
    protected $table = 'security_logs';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'ip_address',
        'user_agent',
        'request_url',
        'request_method',
        'event_type',
        'description',
        'created_at'
    ];

    // Dates
    protected $useTimestamps = false; // We handle created_at manually or let DB do it, but CI usually expects useTimestamps=true for automatic handling. user requested manual sql, better set manual.
    // Actually simpler to let CI handle it if we add 'created_at' to allowedFields and pass it.

    public function logEvent($ip, $agent, $url, $method, $type, $desc)
    {
        return $this->insert([
            'ip_address' => $ip,
            'user_agent' => $agent,
            'request_url' => $url,
            'request_method' => $method,
            'event_type' => $type,
            'description' => $desc,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    // Get stats for dashboard
    public function getStats()
    {
        return [
            'total_today' => $this->where('DATE(created_at)', date('Y-m-d'))->countAllResults(),
            'total_week' => $this->where('created_at >=', date('Y-m-d', strtotime('-7 days')))->countAllResults(),
            'top_ips' => $this->select('ip_address, COUNT(*) as count')
                ->groupBy('ip_address')
                ->orderBy('count', 'DESC')
                ->limit(5)
                ->find(),
            'top_attacks' => $this->select('event_type, COUNT(*) as count')
                ->groupBy('event_type')
                ->orderBy('count', 'DESC')
                ->limit(5)
                ->find(),
        ];
    }
}
