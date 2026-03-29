<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\BlockedIpModel;

class IpBlocker implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $ip = $request->getIPAddress();
        $cacheKey = 'blocked_ip_' . str_replace([':', '.'], '_', $ip);

        // Check Cache first for performance
        $isBlocked = cache()->get($cacheKey);

        if ($isBlocked === null) {
            // If not in cache, check DB
            $model = new BlockedIpModel();
            if ($model->isBlocked($ip)) {
                $isBlocked = true;
                // Cache it as blocked for 1 hour to save DB hits
                cache()->save($cacheKey, true, 3600);
            } else {
                $isBlocked = false;
                // Cache it as allowed (false) for 5 minutes
                // (so we don't hit DB on every request for valid users, but catch blocks relatively fast)
                cache()->save($cacheKey, false, 300);
            }
        }

        if ($isBlocked) {
            // Return 403 Forbidden with a simple message
            $response = service('response');
            $response->setStatusCode(403);
            $response->setBody('
                <div style="display:flex; justify-content:center; align-items:center; height:100vh; background:#000; color:red; font-family:monospace; text-align:center;">
                    <div>
                        <h1 style="font-size:3rem;">🚫 AKSES DITOLAK 🚫</h1>
                        <p style="font-size:1.5rem;">Belajar nya Kurang Jauh Deks,<br>Belajar Lagi Ya Kalo mau Menyerang Website nya🙏</p>
                        <small>IP: ' . esc($ip) . '</small>
                    </div>
                </div>
            ');
            return $response;
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing here
    }
}
