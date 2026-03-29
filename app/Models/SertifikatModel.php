<?php

namespace App\Models;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use CodeIgniter\Model;

class SertifikatModel extends Model
{
    protected $table = 'sertifikat';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nama_kegiatan',
        'nama_sertifikat_id',
        'nama_penerima',
        'fakultas_id',
        'nim',
        'file',
        'qr_code',
        'user_id',
        'user_name',
        'user_email',
        'created_at',
        'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $returnType = 'array';

    /**
     * Get all sertifikat data
     *
     * @return array
     */
    public function getAllSertifikat()
    {
        return $this->select('sertifikat.*, fakultas.nama_fakultas, nama_sertifikat.nama_sertifikat')
            ->join('fakultas', 'fakultas.id = sertifikat.fakultas_id')
            ->join('nama_sertifikat', 'nama_sertifikat.id = sertifikat.nama_sertifikat_id')
            ->findAll();
    }

    /**
     * Get sertifikat data filtered by user level
     *
     * @param int|null $level Level user untuk filter
     * @param int|null $userId ID user untuk filter (jika level 0)
     * @param string|null $userName Nama user untuk filter (jika level 0)
     * @param string|null $userEmail Email user untuk filter (jika level 0)
     * @return array
     */
    public function getSertifikatByUser($level = null, $userId = null, $userName = null, $userEmail = null)
    {
        $query = $this->select('sertifikat.*, fakultas.nama_fakultas, nama_sertifikat.nama_sertifikat')
            ->join('fakultas', 'fakultas.id = sertifikat.fakultas_id')
            ->join('nama_sertifikat', 'nama_sertifikat.id = sertifikat.nama_sertifikat_id');
        
        // Filter berdasarkan level user
        if ($level !== null) {
            if ($level == 0) {
                // Level 0 hanya melihat sertifikat yang dibuat oleh mereka
                // Filter berdasarkan user_id, user_name, atau user_email jika ada
                if ($userId !== null) {
                    $query->where('sertifikat.user_id', $userId);
                } elseif ($userName !== null) {
                    $query->where('sertifikat.user_name', $userName);
                } elseif ($userEmail !== null) {
                    $query->where('sertifikat.user_email', $userEmail);
                }
            } elseif (in_array($level, [1, 2])) {
                // Level 1 dan 2 melihat semua sertifikat
                // Tidak perlu filter tambahan
            }
        }
        
        return $query->orderBy('sertifikat.created_at', 'DESC')->findAll();
    }

    /**
     * Get sertifikat data by NIM
     *
     * @param string $nim
     * @return array
     */
    public function getSertifikatByNim($nim)
    {
        return $this->select('sertifikat.*, fakultas.nama_fakultas, nama_sertifikat.nama_sertifikat')
            ->join('fakultas', 'fakultas.id = sertifikat.fakultas_id')
            ->join('nama_sertifikat', 'nama_sertifikat.id = sertifikat.nama_sertifikat_id')
            ->where('sertifikat.nim', $nim)
            ->findAll();
    }

    /**
     * Create sertifikat with QR Code
     *
     * @param array $data
     * @return bool
     */
    public function createSertifikatWithQRCode(array $data)
    {
        try {
            // Generate QR Code text
            $qrText = base_url('sertifikat/verify/' . $data['nim']);
            $qrCodeFile = 'qrcode_' . $data['nim'] . '.png';
            $qrPath = WRITEPATH . 'uploads/qrcode/' . $qrCodeFile;

            // Ensure directory exists
            if (!is_dir(WRITEPATH . 'uploads/qrcode')) {
                mkdir(WRITEPATH . 'uploads/qrcode', 0777, true);
            }

            // QR Code options
            $options = new QROptions([
                'version'          => 5,
                'outputType'       => QRCode::OUTPUT_IMAGE_PNG,
                'eccLevel'        => QRCode::ECC_H,
                'scale'            => 10,
                'imageBase64'     => false,
                'imageTransparent' => false,
                'quietzoneSize'    => 4,
            ]);

            // Add logo if exists
            $logoPath = WRITEPATH . 'assets/logo1.png';
            if (file_exists($logoPath)) {
                $options->addLogo = true;
                $options->logoSpaceWidth = 10;
                $options->logoSpaceHeight = 10;
                $options->logoScale = 0.3;
                $options->logoPath = $logoPath;
            }

            // Generate QR Code
            $qrCode = new QRCode($options);
            $qrCode->render($qrText, $qrPath);

            // Add QR Code path to data
            $data['qr_code'] = 'uploads/qrcode/' . $qrCodeFile;

            // Save sertifikat data to database
            return $this->save($data);
        } catch (\Exception $e) {
            log_message('error', 'Error creating sertifikat with QR Code: ' . $e->getMessage());
            return false;
        }
    }
    public function getMonthlyData()
    {
        // Ambil parameter tahun dari request atau gunakan tahun sekarang
        $year = $this->request->getGet('year') ?? date('Y');

        $monthlyData = $this->sertifikatModel->getMonthlyCertificateCount($year);

        // Format data untuk semua bulan
        $result = [];
        for ($month = 1; $month <= 12; $month++) {
            $found = false;
            foreach ($monthlyData as $data) {
                if ($data['month'] == $month) {
                    $result[] = $data;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $result[] = ['month' => $month, 'count' => 0, 'year' => $year];
            }
        }

        return $this->respond($result);
    }

    public function getMonthlyCertificateCount($year)
    {
        return $this->db->query("
        SELECT MONTH(created_at) as month, COUNT(*) as count 
        FROM sertifikat 
        WHERE YEAR(created_at) = ? 
        GROUP BY MONTH(created_at)
        ORDER BY month ASC
    ", [$year])->getResultArray();
    }

    public function getAvailableYears()
    {
        $years = $this->db->query("
        SELECT DISTINCT YEAR(created_at) as year 
        FROM sertifikat 
        ORDER BY year DESC
    ")->getResultArray();

        // Pastikan mengembalikan array tahun saja
        return array_column($years, 'year');
    }

    public function getCertificateCountByFaculty()
    {
        return $this->db->query("
        SELECT f.nama_fakultas, COUNT(s.id) as count 
        FROM sertifikat s
        JOIN fakultas f ON s.fakultas_id = f.id
        GROUP BY f.nama_fakultas
        ORDER BY count DESC
    ")->getResultArray();
    }

    /**
     * Get sertifikat dengan pagination di database level (OPTIMAL untuk ratusan ribu data)
     * Menggunakan index yang sudah ada untuk performa maksimal
     *
     * @param string|null $search Search term untuk nama_penerima
     * @param int|null $namaSertifikatFilter Filter berdasarkan nama_sertifikat_id
     * @param int $perPage Jumlah item per halaman
     * @param int $offset Offset untuk pagination
     * @return array Array berisi data sertifikat
     */
    public function getSertifikatPaginated($search = null, $namaSertifikatFilter = null, $perPage = 12, $offset = 0)
    {
        $query = $this->select('sertifikat.*, fakultas.nama_fakultas, nama_sertifikat.nama_sertifikat')
            ->join('fakultas', 'fakultas.id = sertifikat.fakultas_id')
            ->join('nama_sertifikat', 'nama_sertifikat.id = sertifikat.nama_sertifikat_id');

        // Filter search - menggunakan LIKE dengan index jika FULLTEXT tidak tersedia
        if (!empty($search)) {
            // Gunakan LIKE untuk kompatibilitas, akan menggunakan index jika ada
            $query->like('sertifikat.nama_penerima', $search);
        }

        // Filter jenis sertifikat - menggunakan index idx_nama_sertifikat_id
        if (!empty($namaSertifikatFilter)) {
            $query->where('sertifikat.nama_sertifikat_id', $namaSertifikatFilter);
        }

        // Order by created_at DESC - menggunakan index idx_created_at
        $query->orderBy('sertifikat.created_at', 'DESC');

        // Limit dan offset di database level - sangat penting untuk performa
        return $query->limit($perPage, $offset)->findAll();
    }

    /**
     * Count total sertifikat dengan filter yang sama (untuk pagination)
     * Query terpisah untuk COUNT lebih efisien daripada count() di PHP
     * Tidak perlu join karena hanya menghitung jumlah baris di tabel sertifikat
     *
     * @param string|null $search Search term untuk nama_penerima
     * @param int|null $namaSertifikatFilter Filter berdasarkan nama_sertifikat_id
     * @return int Total jumlah sertifikat
     */
    public function countSertifikatFiltered($search = null, $namaSertifikatFilter = null)
    {
        // Query COUNT tidak perlu join karena hanya menghitung jumlah baris
        // Ini lebih efisien dan akan menggunakan index yang ada
        $query = $this->select('sertifikat.id');

        // Filter search - akan menggunakan index idx_nama_penerima jika FULLTEXT tersedia
        if (!empty($search)) {
            $query->like('sertifikat.nama_penerima', $search);
        }

        // Filter jenis sertifikat - akan menggunakan index idx_nama_sertifikat_id
        if (!empty($namaSertifikatFilter)) {
            $query->where('sertifikat.nama_sertifikat_id', $namaSertifikatFilter);
        }

        return $query->countAllResults();
    }
}
