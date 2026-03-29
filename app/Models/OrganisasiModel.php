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

    // Method baru untuk mendapatkan mapping kode fakultas
    public function getFakultasMapping()
    {
        $organisasis = $this->findAll();
        $mapping = [];

        foreach ($organisasis as $org) {
            $kodes = $org['kode_fakultas'] === 'NULL' ? null : explode(',', $org['kode_fakultas']);
            $mapping[$org['id']] = [
                'kodes' => $kodes,
                'nama' => $org['name']
            ];
        }

        return $mapping;
    }

    // Method untuk mendapatkan organisasi yang di-group berdasarkan type untuk navigation
    public function getOrganisasiForNavigation()
    {
        $organisasis = $this->orderBy('type', 'ASC')
                           ->orderBy('name', 'ASC')
                           ->findAll();
        
        $grouped = [];
        
        foreach ($organisasis as $org) {
            $type = $org['type'] ?? 'Other';
            if (!isset($grouped[$type])) {
                $grouped[$type] = [];
            }
            $grouped[$type][] = $org;
        }
        
        return $grouped;
    }
}