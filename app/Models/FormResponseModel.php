<?php

namespace App\Models;

use CodeIgniter\Model;

class FormResponseModel extends Model
{
    protected $table = 'form_responses';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'form_id',
        'user_id',
        'respondent_name',
        'respondent_email',
        'ip_address',
        'user_agent',
        'status',
        'submitted_at'
    ];

    // Dates
    protected $useTimestamps = false;
    // submitted_at is handled manually or via timestamp.
}
