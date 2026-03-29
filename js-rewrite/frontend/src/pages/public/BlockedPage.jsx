import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';

export default function BlockedPage() {
  const [ipAddress, setIpAddress] = useState('Memuat...');
  const [hostname, setHostname] = useState('Memuat...');

  useEffect(() => {
    // Dummy fetch for client IP
    fetch('https://api.ipify.org?format=json')
      .then(res => res.json())
      .then(data => {
        setIpAddress(data.ip);
        setHostname(data.ip); // Mock hostname
      })
      .catch(() => {
        setIpAddress('Tidak diketahui');
        setHostname('Tidak diketahui');
      });
  }, []);

  return (
    <div className="container mt-5">
      <div className="card border-danger">
        <div className="card-header bg-danger text-white">
          <h4 className="mb-0">Akses Diblokir</h4>
        </div>
        <div className="card-body">
          <div className="text-center mb-4">
            <i className="fas fa-shield-alt fa-5x text-danger"></i>
          </div>
          <h3 className="text-center">VPN/Proxy Terdeteksi</h3>
          <p className="lead text-center">
            Sistem kami mendeteksi Anda menggunakan VPN atau Proxy. 
            Untuk keamanan voting, harap matikan VPN/Proxy Anda.
          </p>
          
          <div className="mt-4 p-3 bg-light rounded">
            <h5>Detail Teknis:</h5>
            <ul>
              <li>Alamat IP: <code>{ipAddress}</code></li>
              <li>Hostname: <code>{hostname}</code></li>
            </ul>
          </div>
          
          <div className="mt-4 text-center">
            <Link to="/" className="btn btn-primary">
              <i className="fas fa-home"></i> Kembali ke Beranda
            </Link>
          </div>
        </div>
      </div>
    </div>
  );
}
