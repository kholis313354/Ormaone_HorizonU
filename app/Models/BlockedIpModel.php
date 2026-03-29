<?php

namespace App\Models;

use CodeIgniter\Model;

class BlockedIpModel extends Model
{
    protected $table = 'blocked_ips';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'ip_address',
        'reason',
        'blocked_by',
        'created_at'
    ];

    protected $useTimestamps = false;

    public function isBlocked($ip)
    {
        // Check database directly. For high performance this should be cached.
        // We will implement caching in the Filter.
        return $this->where('ip_address', $ip)->countAllResults() > 0;
    }

    public function block($ip, $reason, $by = 'Admin')
    {
        // Prevent duplicate
        if ($this->isBlocked($ip))
            return false;

        $this->insert([
            'ip_address' => $ip,
            'reason' => $reason,
            'blocked_by' => $by,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // Clear cache for this IP check if used
        cache()->delete('blocked_ip_' . str_replace([':', '.'], '_', $ip));

        return true;
    }

    public function unblock($id)
    {
        $ipData = $this->find($id);
        if ($ipData) {
            $ip = $ipData['ip_address'];
            $this->delete($id);
            // Clear cache
            cache()->delete('blocked_ip_' . str_replace([':', '.'], '_', $ip));
            return true;
        }
        return false;
    }
}
