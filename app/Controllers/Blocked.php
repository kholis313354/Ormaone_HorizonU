<?php

namespace App\Controllers;

class Blocked extends BaseController
{
    public function vpn() {
        $data = [
            'title' => 'VPN Terdeteksi',
            'ip' => $this->request->getIPAddress(),
            'hostname' => gethostbyaddr($this->request->getIPAddress())
        ];
        
        return view('blocked/vpn', $data);
    }
    
    public function wifi() {
        $data = [
            'title' => 'WiFi Terdeteksi'
        ];
        
        return view('blocked/wifi', $data);
    }
}