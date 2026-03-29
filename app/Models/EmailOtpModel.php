<?php

namespace App\Models;

use CodeIgniter\Model;

class EmailOtpModel extends Model
{
    protected $table      = 'email_otps';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'email', 'otp_hash', 'purpose', 'context_id', 'attempt_count', 'resend_count',
        'last_sent_at', 'expires_at', 'used_at', 'created_ip', 'user_agent',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}


