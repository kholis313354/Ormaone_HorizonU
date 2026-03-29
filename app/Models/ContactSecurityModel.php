<?php

namespace App\Models;

use CodeIgniter\Model;

class ContactSecurityModel extends Model
{
    protected $table            = 'contact_security';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['ip_address', 'email', 'attempts', 'last_attempt', 'blocked_until', 'is_blocked', 'reason'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Check if IP is blocked
     */
    public function isIpBlocked($ipAddress)
    {
        $record = $this->where('ip_address', $ipAddress)
                      ->where('is_blocked', 1)
                      ->first();
        
        if (!$record) {
            return false;
        }
        
        // Check if block has expired
        if ($record['blocked_until'] && strtotime($record['blocked_until']) < time()) {
            // Unblock
            $this->update($record['id'], [
                'is_blocked' => 0,
                'blocked_until' => null
            ]);
            return false;
        }
        
        return true;
    }

    /**
     * Check if email is blocked
     */
    public function isEmailBlocked($email)
    {
        $record = $this->where('email', $email)
                      ->where('is_blocked', 1)
                      ->first();
        
        if (!$record) {
            return false;
        }
        
        // Check if block has expired
        if ($record['blocked_until'] && strtotime($record['blocked_until']) < time()) {
            // Unblock
            $this->update($record['id'], [
                'is_blocked' => 0,
                'blocked_until' => null
            ]);
            return false;
        }
        
        return true;
    }

    /**
     * Record attempt and check rate limit
     */
    public function recordAttempt($ipAddress, $email = null, $maxAttempts = 5, $timeWindow = 3600)
    {
        // Check existing record
        $where = ['ip_address' => $ipAddress];
        if ($email) {
            $where['email'] = $email;
        }
        
        $record = $this->where($where)->first();
        
        $now = date('Y-m-d H:i:s');
        $oneHourAgo = date('Y-m-d H:i:s', time() - $timeWindow);
        
        if ($record) {
            // Check if last attempt was within time window
            if ($record['last_attempt'] >= $oneHourAgo) {
                $attempts = $record['attempts'] + 1;
                
                // Block if exceeds max attempts
                if ($attempts > $maxAttempts) {
                    $blockedUntil = date('Y-m-d H:i:s', time() + (24 * 3600)); // Block for 24 hours
                    $this->update($record['id'], [
                        'attempts' => $attempts,
                        'last_attempt' => $now,
                        'is_blocked' => 1,
                        'blocked_until' => $blockedUntil,
                        'reason' => 'Terlalu banyak percobaan dalam waktu singkat'
                    ]);
                    return ['blocked' => true, 'blocked_until' => $blockedUntil];
                } else {
                    $this->update($record['id'], [
                        'attempts' => $attempts,
                        'last_attempt' => $now
                    ]);
                    return ['blocked' => false, 'attempts' => $attempts];
                }
            } else {
                // Reset attempts if outside time window
                $this->update($record['id'], [
                    'attempts' => 1,
                    'last_attempt' => $now
                ]);
                return ['blocked' => false, 'attempts' => 1];
            }
        } else {
            // Create new record
            $this->insert([
                'ip_address' => $ipAddress,
                'email' => $email,
                'attempts' => 1,
                'last_attempt' => $now
            ]);
            return ['blocked' => false, 'attempts' => 1];
        }
    }

    /**
     * Block IP or email
     */
    public function block($ipAddress = null, $email = null, $duration = 86400, $reason = 'Spam detected')
    {
        $blockedUntil = date('Y-m-d H:i:s', time() + $duration);
        
        $where = [];
        if ($ipAddress) {
            $where['ip_address'] = $ipAddress;
        }
        if ($email) {
            $where['email'] = $email;
        }
        
        if (empty($where)) {
            return false;
        }
        
        $record = $this->where($where)->first();
        
        if ($record) {
            $this->update($record['id'], [
                'is_blocked' => 1,
                'blocked_until' => $blockedUntil,
                'reason' => $reason
            ]);
        } else {
            $this->insert([
                'ip_address' => $ipAddress,
                'email' => $email,
                'is_blocked' => 1,
                'blocked_until' => $blockedUntil,
                'reason' => $reason
            ]);
        }
        
        return true;
    }
}

