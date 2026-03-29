<?php

namespace App\Models;

use CodeIgniter\Model;

class BeritaModel extends Model
{
    protected $table = 'berita';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'gambar',
        'nama_kegiatan',
        'kategori',
        'deskripsi1',
        'deskripsi2',
        'deskripsi3',
        'fakultas_id',
        'tanggal',
        'status',  // Add this if you have a status field
        'link',  // Link untuk video YouTube (podcast)
        'user_id',
        'user_name',
        'user_email',
        'created_at',
        'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getAllWithFakultas()
    {
        $builder = $this->builder();
        $builder->select('berita.*, fakultas.nama_fakultas');
        $builder->join('fakultas', 'fakultas.id = berita.fakultas_id');

        // Add this if you have a status/published field
        // $builder->where('berita.status', 'published');

        $builder->orderBy('berita.tanggal', 'DESC');
        $query = $builder->get();

        return $query->getResultArray();
    }

    /**
     * Get berita data filtered by user level
     *
     * @param int|null $level Level user untuk filter
     * @param int|null $userId ID user untuk filter (jika level 0)
     * @param string|null $userName Nama user untuk filter (jika level 0)
     * @param string|null $userEmail Email user untuk filter (jika level 0)
     * @return array
     */
    public function getBeritaByUser($level = null, $userId = null, $userName = null, $userEmail = null)
    {
        $builder = $this->builder();
        $builder->select('berita.*, fakultas.nama_fakultas');
        $builder->join('fakultas', 'fakultas.id = berita.fakultas_id');
        
        // Filter berdasarkan level user
        if ($level !== null) {
            if ($level == 0) {
                // Level 0 hanya melihat berita yang dibuat oleh mereka
                // Filter berdasarkan user_id, user_name, atau user_email jika ada
                if ($userId !== null) {
                    $builder->where('berita.user_id', $userId);
                } elseif ($userName !== null) {
                    $builder->where('berita.user_name', $userName);
                } elseif ($userEmail !== null) {
                    $builder->where('berita.user_email', $userEmail);
                }
            } elseif (in_array($level, [1, 2])) {
                // Level 1 dan 2 melihat semua berita
                // Tidak perlu filter tambahan
            }
        }
        
        $builder->orderBy('berita.tanggal', 'DESC');
        $query = $builder->get();

        return $query->getResultArray();
    }

    public function countByFakultas($namaFakultas)
    {
        return $this->join('fakultas', 'fakultas.id = berita.fakultas_id')
            ->where('fakultas.nama_fakultas', $namaFakultas)
            ->countAllResults();
    }

    // Add this new method for debugging
    public function debugQuery()
    {
        $db = \Config\Database::connect();
        return $db->query('SELECT berita.*, fakultas.nama_fakultas 
                          FROM berita 
                          JOIN fakultas ON fakultas.id = berita.fakultas_id
                          ORDER BY berita.tanggal DESC')
            ->getResultArray();
    }
    public function getAllPublishedWithFakultas($kategori = null)
    {
        $builder = $this->select('berita.*, fakultas.nama_fakultas')
            ->join('fakultas', 'fakultas.id = berita.fakultas_id')
            ->where('berita.status', 'published'); // Sesuaikan dengan kolom status Anda
        
        // Filter berdasarkan kategori jika dipilih
        if (!empty($kategori) && in_array($kategori, ['blogger', 'podcast'])) {
            $builder->where('berita.kategori', $kategori);
        }
        
        return $builder->orderBy('berita.tanggal', 'DESC')
            ->findAll();
    }

    public function getDistinctCategories()
    {
        return $this->distinct()
            ->select('kategori')
            ->where('status', 'published') // Filter hanya yang published
            ->orderBy('kategori', 'ASC')
            ->findAll();
    }

    public function getBeritaCountByFaculty()
    {
        return $this->db->query("
            SELECT f.nama_fakultas, COUNT(b.id) as count 
            FROM berita b
            JOIN fakultas f ON b.fakultas_id = f.id
            GROUP BY f.nama_fakultas
            ORDER BY count DESC
        ")->getResultArray();
    }
}
