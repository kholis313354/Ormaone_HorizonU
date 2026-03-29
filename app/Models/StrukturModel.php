<?php

namespace App\Models;

use CodeIgniter\Model;

class StrukturModel extends Model
{
    protected $table      = 'strukturs';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'organisasi_id',
        'tahun',
        'periode',
        'struktur_data',
        'is_active',
    ];

    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $useTimestamps = true;

    // Validation rules
    protected $validationRules = [
        'organisasi_id' => 'required|integer',
        'tahun' => 'required|max_length[9]',
        'periode' => 'permit_empty|max_length[50]',
        'struktur_data' => 'required',
        'is_active' => 'permit_empty|in_list[0,1]',
    ];

    /**
     * Get struktur by organisasi_id dan tahun
     */
    public function getByOrganisasiAndTahun($organisasiId, $tahun = null)
    {
        $builder = $this->where('organisasi_id', $organisasiId);
        
        if ($tahun) {
            $builder->where('tahun', $tahun);
        } else {
            $builder->where('is_active', 1)->orderBy('tahun', 'DESC');
        }
        
        return $builder->first();
    }

    /**
     * Get all tahun yang tersedia untuk organisasi tertentu
     */
    public function getAvailableYears($organisasiId)
    {
        return $this->select('tahun')
            ->where('organisasi_id', $organisasiId)
            ->groupBy('tahun')
            ->orderBy('tahun', 'DESC')
            ->findAll();
    }

    /**
     * Get struktur aktif
     */
    public function getActiveStruktur($organisasiId)
    {
        return $this->where('organisasi_id', $organisasiId)
            ->where('is_active', 1)
            ->orderBy('tahun', 'DESC')
            ->first();
    }
}

