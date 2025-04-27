<?php

namespace App\Models;

use CodeIgniter\Model;

class KepengurusanModel extends Model
{
    protected $table = 'kepengurusans';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'organisasi_id',
        'anggota_id',
        'jabatan',
        'periode',
    ];

    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
