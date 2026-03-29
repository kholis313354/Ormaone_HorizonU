<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class BlockVPN implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Daftar IP VPN/proxy yang diketahui
        $vpnIps = [
            // Cloudflare
            '104.16.0.0/12', '172.64.0.0/13', '173.245.48.0/20',
            // AWS
            '3.0.0.0/9', '18.0.0.0/8', '52.0.0.0/8',
            // Google Cloud
            '8.34.208.0/20', '8.35.192.0/21', '23.236.48.0/20',
            // DigitalOcean
            '104.131.0.0/16', '104.236.0.0/16', '107.170.0.0/16',
            // Lainnya
            '185.93.0.0/16', '192.169.0.0/16', '198.27.0.0/16'
        ];

        // Cek header yang biasanya ada di VPN/proxy
        $suspiciousHeaders = [
            'HTTP_VIA', 'HTTP_X_FORWARDED_FOR', 'HTTP_FORWARDED_FOR',
            'HTTP_X_FORWARDED', 'HTTP_FORWARDED', 'HTTP_CLIENT_IP',
            'HTTP_FORWARDED_FOR_IP', 'VIA', 'X_FORWARDED_FOR',
            'FORWARDED_FOR', 'X_FORWARDED', 'FORWARDED', 'CLIENT_IP',
            'FORWARDED_FOR_IP', 'HTTP_PROXY_CONNECTION'
        ];

        $userIp = $request->getIPAddress();

        // Cek apakah IP termasuk dalam range VPN/proxy
        foreach ($vpnIps as $vpnIp) {
            if ($this->ipInRange($userIp, $vpnIp)) {
                return $this->blockAccess();
            }
        }

        // Cek header mencurigakan
        foreach ($suspiciousHeaders as $header) {
            if ($request->getHeader($header) !== null) {
                return $this->blockAccess();
            }
        }

        // Cek jika IP publik berbeda dengan IP asli (mungkin proxy)
        $forwardedIp = $request->getHeader('X-Forwarded-For');
        if ($forwardedIp && $forwardedIp->getValue() !== $userIp) {
            return $this->blockAccess();
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak ada tindakan setelah request
    }

    private function ipInRange($ip, $range)
    {
        if (strpos($range, '/') === false) {
            return $ip === $range;
        }

        list($subnet, $bits) = explode('/', $range);
        $ip = ip2long($ip);
        $subnet = ip2long($subnet);
        $mask = -1 << (32 - $bits);
        $subnet &= $mask;

        return ($ip & $mask) === $subnet;
    }

    private function blockAccess()
    {
        return redirect()->to('/blocked')->with('error', 'Akses menggunakan VPN, Proxy, atau jaringan yang tidak diperbolehkan telah diblokir. Mohon gunakan koneksi data seluler langsung untuk voting.');
    }
}