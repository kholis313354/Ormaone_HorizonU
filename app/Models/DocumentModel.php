<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentModel extends Model
{
    protected $table = 'arsip_document';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'judul',
        'kategori',
        'deskripsi',
        'file_path',
        'file_name',
        'file_size',
        'file_type',
        'tahun',
        'organisasi_id',
        'user_id',
        'user_name',
        'user_email',
        'user_level',
        'created_at',
        'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $returnType = 'array';

    /**
     * Get all documents with filtering by level
     *
     * @param int|null $level Level user untuk filter
     * @param int|null $userId ID user untuk filter (jika level 0)
     * @param int|null $year Tahun untuk filter
     * @param string|null $kategori Kategori untuk filter
     * @return array
     */
    public function getAllDocuments($level = null, $userId = null, $year = null, $kategori = null)
    {
        $query = $this->select('arsip_document.*');
        
        // Filter berdasarkan level user
        if ($level !== null) {
            if ($level == 0) {
                // Level 0 hanya melihat dokumen sendiri
                if ($userId !== null) {
                    $query->where('arsip_document.user_id', $userId);
                }
            } elseif (in_array($level, [1, 2])) {
                // Level 1 dan 2 melihat semua dokumen
                // Tidak perlu filter tambahan
            }
        }
        
        // Filter berdasarkan tahun
        if ($year !== null && $year !== 'all') {
            $query->where('YEAR(arsip_document.tahun)', $year);
        }
        
        // Filter berdasarkan kategori
        if ($kategori !== null && $kategori !== '') {
            $query->where('arsip_document.kategori', $kategori);
        }
        
        return $query->orderBy('arsip_document.created_at', 'DESC')->findAll();
    }

    /**
     * Get monthly document count for statistics
     *
     * @param int $year Tahun
     * @param int|null $level Level user untuk filter
     * @param int|null $userId ID user untuk filter
     * @return array
     */
    public function getMonthlyDocumentCount($year, $level = null, $userId = null)
    {
        $whereClause = "WHERE YEAR(tahun) = ?";
        $params = [$year];
        
        if ($level !== null && $level == 0 && $userId !== null) {
            $whereClause .= " AND user_id = ?";
            $params[] = $userId;
        }
        
        $sql = "
            SELECT MONTH(created_at) as month, COUNT(*) as count 
            FROM arsip_document 
            {$whereClause}
            GROUP BY MONTH(created_at)
            ORDER BY month ASC
        ";
        
        return $this->db->query($sql, $params)->getResultArray();
    }

    /**
     * Get available years from documents
     *
     * @param int|null $level Level user untuk filter
     * @param int|null $userId ID user untuk filter
     * @return array
     */
    public function getAvailableYears($level = null, $userId = null)
    {
        $query = "SELECT DISTINCT YEAR(tahun) as year FROM arsip_document";
        $params = [];
        
        if ($level !== null && $level == 0 && $userId !== null) {
            $query .= " WHERE user_id = ?";
            $params[] = $userId;
        }
        
        $query .= " ORDER BY year DESC";
        
        $years = $this->db->query($query, $params)->getResultArray();
        return array_column($years, 'year');
    }

    /**
     * Get document count by category
     *
     * @param int|null $level Level user untuk filter
     * @param int|null $userId ID user untuk filter
     * @return array
     */
    public function getDocumentCountByCategory($level = null, $userId = null)
    {
        $query = "
            SELECT kategori, COUNT(*) as count 
            FROM arsip_document
        ";
        $params = [];
        
        if ($level !== null && $level == 0 && $userId !== null) {
            $query .= " WHERE user_id = ?";
            $params[] = $userId;
        }
        
        $query .= " GROUP BY kategori ORDER BY count DESC";
        
        return $this->db->query($query, $params)->getResultArray();
    }

    /**
     * Get document by ID with user info
     *
     * @param int $id
     * @return array|null
     */
    public function getDocumentById($id)
    {
        return $this->find($id);
    }

    /**
     * Get total document count
     *
     * @param int|null $level Level user untuk filter
     * @param int|null $userId ID user untuk filter
     * @return int
     */
    public function getTotalDocumentCount($level = null, $userId = null)
    {
        $query = $this->select('COUNT(*) as total');
        
        if ($level !== null && $level == 0 && $userId !== null) {
            $query->where('user_id', $userId);
        }
        
        $result = $query->first();
        return $result['total'] ?? 0;
    }
}
