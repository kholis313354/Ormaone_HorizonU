<?php

namespace App\Models;

use CodeIgniter\Model;

class NamaSertifikatModel extends Model
{
    protected $table = 'nama_sertifikat';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama_sertifikat', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $returnType = 'array';
}
