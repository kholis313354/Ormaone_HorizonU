<?php

namespace App\Models;

use CodeIgniter\Model;

class ProgramKerjaModel extends Model
{
    protected $table = 'program_kerja';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'organisasi_id',
        'judul',
        'tahun',
        'periode',
        'deskripsi', // JSON data
        'is_active'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'organisasi_id' => 'required|integer',
        'judul' => 'required',
        'tahun' => 'required',
    ];
}
