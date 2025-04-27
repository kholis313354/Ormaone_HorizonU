<?php

namespace App\Models;

use CodeIgniter\Model;

class AnggotaModel extends Model
{
    protected $table      = 'anggotas';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'organisasi_id',
        'user_id',
        'nim',
        'name',
        'kelas',
        'jurusan',
        'phone',
        'notes',
    ];

    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
