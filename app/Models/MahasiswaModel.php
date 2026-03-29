<?php

namespace App\Models;

use CodeIgniter\Model;

class MahasiswaModel extends Model
{
    protected $table      = 'mahasiswa';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['nama', 'nim', 'email', 'status'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules    = [
        'nama'     => 'required|min_length[3]|max_length[255]',
        'nim'      => 'required|min_length[5]|max_length[20]|is_unique[mahasiswa.nim]',
        'email'    => 'required|valid_email|is_unique[mahasiswa.email]',
        'status'   => 'required|in_list[aktif,nonaktif]',
    ];
    
    protected $validationMessages = [
        'nama' => [
            'required' => 'Nama mahasiswa harus diisi',
            'min_length' => 'Nama mahasiswa minimal 3 karakter',
            'max_length' => 'Nama mahasiswa maksimal 255 karakter',
        ],
        'nim' => [
            'required' => 'NIM harus diisi',
            'min_length' => 'NIM minimal 5 karakter',
            'max_length' => 'NIM maksimal 20 karakter',
            'is_unique' => 'NIM sudah terdaftar',
        ],
        'email' => [
            'required' => 'Email harus diisi',
            'valid_email' => 'Email tidak valid',
            'is_unique' => 'Email sudah terdaftar',
        ],
        'status' => [
            'required' => 'Status harus diisi',
            'in_list' => 'Status harus aktif atau nonaktif',
        ],
    ];
    
    protected $skipValidation     = false;
}