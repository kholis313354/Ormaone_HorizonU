<?php

namespace App\Models;

use CodeIgniter\Model;

class AnggaranModel extends Model
{
    protected $table = 'anggaran';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'organisasi_id',
        'tahun',
        'jumlah',
        'dana_berkurang',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'organisasi_id' => 'required|integer',
        'tahun' => 'required|max_length[50]',
        'jumlah' => 'required|decimal',
        'dana_berkurang' => 'permit_empty|decimal',
    ];

    /**
     * Get budget by organization and year
     */
    public function getByOrganisasiAndTahun($organisasiId, $tahun)
    {
        return $this->where('organisasi_id', $organisasiId)
            ->where('tahun', $tahun)
            ->first();
    }
}
