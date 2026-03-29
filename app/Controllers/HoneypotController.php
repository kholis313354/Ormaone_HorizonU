<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\BlockedIpModel;

class HoneypotController extends Controller
{
    public function trap()
    {
        $request = service('request');
        $ip = $request->getIPAddress();

        // Log alasan blokir
        $reason = "Terjebak Honeypot: Mengakses URL terlarang (" . current_url() . ")";

        // Auto Block IP
        $model = new BlockedIpModel();
        $model->block($ip, $reason, 'System Auto-Trap');

        // Tampilkan 404 palsu agar penyerang bingung, padahal aslinya sudah diblokir untuk request selanjutnya
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }
}
