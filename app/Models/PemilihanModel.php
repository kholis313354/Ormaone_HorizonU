<?php

namespace App\Models;

use CodeIgniter\Model;

class PemilihanModel extends Model
{
    protected $table      = 'pemilihans';
    protected $primaryKey = 'id';

    // organisasi_id, periode (informasi), tanggal_mulai, tanggal_akhir, status (draft, publish, selesai)
    protected $allowedFields = [
        'organisasi_id',
        'periode',
        'tanggal_mulai',
        'tanggal_akhir',
        'status',
    ];

    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}