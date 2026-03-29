<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;

class RateLimitFilter implements FilterInterface
{
    use ResponseTrait;

    public function before(RequestInterface $request, $arguments = null)
    {
        $ip = $request->getIPAddress();
        $limit = 100; // Maksimal request per menit
        $time = 60;   // Waktu dalam detik (1 menit)

        $cache = \Config\Services::cache();
        $key = 'rate_limit_' . md5($ip);

        $current = $cache->get($key);

        if (!$current) {
            $cache->save($key, 1, $time);
        } elseif ($current >= $limit) {
            // Jika melebihi batas, kembalikan error 429 Too Many Requests
            $response = service('response');
            $response->setStatusCode(429);
            $response->setBody('<h1>429 Too Many Requests</h1><p>Anda melakukan request terlalu cepat. Santai dulu kawan, coba lagi nanti.</p>');
            return $response;
        } else {
            $cache->increment($key);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
