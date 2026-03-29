<?php

namespace App\Models;

use CodeIgniter\Model;

class StrukturTampilanModel extends Model
{
    protected $table = 'struktur_tampilan';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'organisasi_id',
        'tahun',
        'periode',
        'nama_1',
        'jabatan_1',
        'gambar_1',
        'nama_2',
        'jabatan_2',
        'gambar_2',
        'nama_3',
        'jabatan_3',
        'gambar_3',
        'nama_4',
        'jabatan_4',
        'gambar_4',
        'nama_5',
        'jabatan_5',
        'gambar_5',
        'nama_6',
        'jabatan_6',
        'gambar_6',
        'prodi_1',
        'prodi_2',
        'prodi_3',
        'prodi_4',
        'prodi_5',
        'prodi_6',
        'is_active',
    ];

    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $useTimestamps = true;
    protected $skipValidation = false;
    protected $cleanValidationRules = true;
    protected $beforeInsert = [];
    protected $beforeUpdate = [];

    // Validation rules
    protected $validationRules = [
        'organisasi_id' => 'required|integer',
        'tahun' => 'required|max_length[50]',
        'periode' => 'permit_empty|max_length[100]',
        'is_active' => 'permit_empty|in_list[0,1]',
        'nama_1' => 'permit_empty|max_length[100]',
        'jabatan_1' => 'permit_empty|max_length[100]',
        'gambar_1' => 'permit_empty|max_length[255]',
        'nama_2' => 'permit_empty|max_length[100]',
        'jabatan_2' => 'permit_empty|max_length[100]',
        'gambar_2' => 'permit_empty|max_length[255]',
        'nama_3' => 'permit_empty|max_length[100]',
        'jabatan_3' => 'permit_empty|max_length[100]',
        'gambar_3' => 'permit_empty|max_length[255]',
        'nama_4' => 'permit_empty|max_length[100]',
        'jabatan_4' => 'permit_empty|max_length[100]',
        'gambar_4' => 'permit_empty|max_length[255]',
        'nama_5' => 'permit_empty|max_length[100]',
        'jabatan_5' => 'permit_empty|max_length[100]',
        'gambar_5' => 'permit_empty|max_length[255]',
        'nama_6' => 'permit_empty|max_length[100]',
        'jabatan_6' => 'permit_empty|max_length[100]',
        'gambar_6' => 'permit_empty|max_length[255]',
        'prodi_1' => 'permit_empty|max_length[100]',
        'prodi_2' => 'permit_empty|max_length[100]',
        'prodi_3' => 'permit_empty|max_length[100]',
        'prodi_4' => 'permit_empty|max_length[100]',
        'prodi_5' => 'permit_empty|max_length[100]',
        'prodi_6' => 'permit_empty|max_length[100]',
    ];

    protected $validationMessages = [
        'organisasi_id' => [
            'required' => 'Organisasi wajib dipilih',
            'integer' => 'Organisasi harus berupa angka'
        ],
        'tahun' => [
            'required' => 'Tahun wajib diisi',
            'max_length' => 'Tahun maksimal 50 karakter'
        ],
    ];

    /**
     * Get struktur tampilan by organisasi_id dan tahun
     */
    public function getByOrganisasiAndTahun($organisasiId, $tahun = null)
    {
        if ($tahun) {
            return $this->where('organisasi_id', $organisasiId)
                ->where('tahun', $tahun)
                ->first();
        } else {
            return $this->where('organisasi_id', $organisasiId)
                ->where('is_active', 1)
                ->orderBy('tahun', 'DESC')
                ->first();
        }
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
        $result = $this->where('organisasi_id', $organisasiId)
            ->where('is_active', 1)
            ->orderBy('tahun', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->first();

        // Jika tidak ada yang aktif, ambil yang terbaru
        if (!$result) {
            $result = $this->where('organisasi_id', $organisasiId)
                ->orderBy('tahun', 'DESC')
                ->orderBy('created_at', 'DESC')
                ->first();
        }

        return $result;
    }
}

