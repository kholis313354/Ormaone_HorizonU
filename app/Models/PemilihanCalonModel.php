<?php

namespace App\Models;

use CodeIgniter\Model;

class PemilihanCalonModel extends Model
{
    protected $table      = 'pemilihan_calons';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'pemilihan_id',
        'anggota_id_1',
        'anggota_id_2',
        'gambar_1',
        'gambar_2',
        'number',
        'description',
    ];

    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}