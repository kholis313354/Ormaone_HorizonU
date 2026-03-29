<?php

namespace App\Models;

use CodeIgniter\Model;

class VisiMisiModel extends Model
{
    protected $table = 'visi_misi';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'organisasi_id',
        'visi',
        'misi',
        'tahun',
        'periode',
        'is_active'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'organisasi_id' => 'required|integer',
        'visi' => 'required',
        'misi' => 'required',
        'tahun' => 'required',
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;
}
