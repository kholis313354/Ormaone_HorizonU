<?php

namespace App\Models;

use CodeIgniter\Model;

class OrganisasiModel extends Model
{
    protected $table      = 'organisasis';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name',
        'description',
        'kode_fakultas',
        'type',
        'image',
    ];

    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}