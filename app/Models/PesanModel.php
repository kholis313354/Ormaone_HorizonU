<?php

namespace App\Models;

use CodeIgniter\Model;

class PesanModel extends Model
{
    protected $table            = 'pesan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement  = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'email', 'subject', 'message', 'status'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'name'    => 'required|min_length[3]|max_length[255]',
        'email'   => 'required|valid_email|max_length[255]',
        'subject' => 'required|min_length[3]|max_length[255]',
        'message' => 'required|min_length[10]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind       = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Get all pesan with pagination
     */
    public function getAllPesan($perPage = 10, $page = 1)
    {
        return $this->orderBy('created_at', 'DESC')
                    ->paginate($perPage, 'default', $page);
    }

    /**
     * Get pesan by status
     */
    public function getPesanByStatus($status)
    {
        return $this->where('status', $status)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Mark pesan as read
     */
    public function markAsRead($id)
    {
        return $this->update($id, ['status' => 'read']);
    }

    /**
     * Mark pesan as replied
     */
    public function markAsReplied($id)
    {
        return $this->update($id, ['status' => 'replied']);
    }

    /**
     * Get count of unread pesan
     */
    public function getUnreadCount()
    {
        return $this->where('status', 'unread')->countAllResults();
    }
}

