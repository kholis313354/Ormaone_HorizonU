<?php

namespace App\Models;

use CodeIgniter\Model;

class PemilihanCalonSuaraModel extends Model
{
    protected $table      = 'pemilihan_calon_suara';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'pemilihan_calon_id',
        'nim',
        'name',
        'email',
        'anggota_id',
        'ip_address',
        'user_agent',
        'kode_fakultas',
        'status',
    ];

    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
