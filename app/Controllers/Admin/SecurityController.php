<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SecurityLogModel;

class SecurityController extends BaseController
{
    protected $securityModel;

    public function __construct()
    {
        $this->securityModel = new SecurityLogModel();
    }

    public function index()
    {
        // Only allow level 2 (Admin) - double check
        if (session()->get('level') != 2) {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $blockedIpModel = new \App\Models\BlockedIpModel();

        // Fitur Delete otomatis Log Keamanan > 3 hari (Permintaan User)
        // IP Diblokir tidak dihapus (tetap tersimpan)
        $tigaHariLalu = date('Y-m-d H:i:s', strtotime('-3 days'));
        $this->securityModel->where('created_at <', $tigaHariLalu)->delete();

        // $stats = $this->securityModel->getStats(); // This line is replaced by direct assignment in $data

        $data = [
            'title' => 'Keamanan Website',
            'logs' => $this->securityModel->orderBy('created_at', 'DESC')->findAll(1000), // Batasi 1000 log terakhir untuk performa
            // 'pager' => $this->securityModel->pager, // Tidak butuh pager server-side
            'blocked_ips' => $blockedIpModel->orderBy('created_at', 'DESC')->findAll(),
            'stats' => [
                'total_today' => $this->securityModel->where('created_at >=', date('Y-m-d 00:00:00'))->countAllResults(),
                'total_week' => $this->securityModel->where('created_at >=', date('Y-m-d 00:00:00', strtotime('-7 days')))->countAllResults(),
            ]
        ];

        return view('page/keamanan/index', $data);
    }

    public function blockIp()
    {
        if (session()->get('level') != 2)
            return redirect()->back();

        $ip = $this->request->getPost('ip_address');
        $reason = $this->request->getPost('reason');

        $model = new \App\Models\BlockedIpModel();
        if ($model->block($ip, $reason, session()->get('nama_user') ?? 'Admin')) {
            return redirect()->back()->with('success', 'IP ' . esc($ip) . ' berhasil diblokir.');
        } else {
            return redirect()->back()->with('error', 'Gagal memblokir IP atau IP sudah diblokir.');
        }
    }

    public function unblockIp($id)
    {
        if (session()->get('level') != 2)
            return redirect()->back();

        $model = new \App\Models\BlockedIpModel();
        if ($model->unblock($id)) {
            return redirect()->back()->with('success', 'IP berhasil dibuka blokirnya.');
        } else {
            return redirect()->back()->with('error', 'Gagal membuka blokir IP.');
        }
    }
    public function delete($id)
    {
        if (session()->get('level') != 2)
            return redirect()->back();

        if ($this->securityModel->delete($id)) {
            return redirect()->back()->with('success', 'Log berhasil dihapus.');
        } else {
            return redirect()->back()->with('error', 'Gagal menghapus log.');
        }
    }
}
