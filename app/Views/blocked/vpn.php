<?= $this->extend('components/layouts/landing') ?>

<?= $this->section('content') ?>
<div class="container mt-5">
    <div class="card border-danger">
        <div class="card-header bg-danger text-white">
            <h4 class="mb-0">Akses Diblokir</h4>
        </div>
        <div class="card-body">
            <div class="text-center mb-4">
                <i class="fas fa-shield-alt fa-5x text-danger"></i>
            </div>
            <h3 class="text-center">VPN/Proxy Terdeteksi</h3>
            <p class="lead text-center">
                Sistem kami mendeteksi Anda menggunakan VPN atau Proxy. 
                Untuk keamanan voting, harap matikan VPN/Proxy Anda.
            </p>
            
            <div class="mt-4 p-3 bg-light rounded">
                <h5>Detail Teknis:</h5>
                <ul>
                    <li>Alamat IP: <code><?= $_SERVER['REMOTE_ADDR'] ?></code></li>
                    <li>Hostname: <code><?= gethostbyaddr($_SERVER['REMOTE_ADDR']) ?></code></li>
                </ul>
            </div>
            
            <div class="mt-4 text-center">
                <a href="<?= base_url() ?>" class="btn btn-primary">
                    <i class="fas fa-home"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>