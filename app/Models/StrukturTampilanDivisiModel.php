<?php

namespace App\Models;

use CodeIgniter\Model;

class StrukturTampilanDivisiModel extends Model
{
    protected $table = 'struktur_tampilan_divisi';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'organisasi_id',
        'nama_divisi',
        'tahun',
        'periode',
        'nama_ketua',
        'jabatan_ketua',
        'gambar_ketua',
        'prodi_ketua',
        'nama_anggota_1',
        'jabatan_anggota_1',
        'gambar_anggota_1',
        'nama_anggota_2',
        'jabatan_anggota_2',
        'gambar_anggota_2',
        'nama_anggota_3',
        'jabatan_anggota_3',
        'gambar_anggota_3',
        'nama_anggota_4',
        'jabatan_anggota_4',
        'gambar_anggota_4',
        'nama_anggota_5',
        'jabatan_anggota_5',
        'gambar_anggota_5',
        'nama_anggota_6',
        'jabatan_anggota_6',
        'gambar_anggota_6',
        'nama_anggota_7',
        'jabatan_anggota_7',
        'gambar_anggota_7',
        'nama_anggota_8',
        'jabatan_anggota_8',
        'gambar_anggota_8',
        'prodi_anggota_1',
        'prodi_anggota_2',
        'prodi_anggota_3',
        'prodi_anggota_4',
        'prodi_anggota_5',
        'prodi_anggota_6',
        'prodi_anggota_7',
        'prodi_anggota_8',
    ];

    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $useTimestamps = true;
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    protected $validationRules = [
        'organisasi_id' => 'required|integer',
        'nama_divisi' => 'required|max_length[100]',
        'tahun' => 'required|max_length[50]',
    ];

    /**
     * Get divisions by organization and year
     */
    public function getByOrganisasiAndTahun($organisasiId, $tahun)
    {
        return $this->where('organisasi_id', $organisasiId)
            ->where('tahun', $tahun)
            ->findAll();
    }

    /**
     * Check if organization has reached max divisions (3) for a specific year
     */
    public function countDivisions($organisasiId, $tahun)
    {
        return $this->where('organisasi_id', $organisasiId)
            ->where('tahun', $tahun)
            ->countAllResults();
    }
}
