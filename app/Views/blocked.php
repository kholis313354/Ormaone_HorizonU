<?= $this->extend('components/layouts/landing') ?>

<?= $this->section('content') ?>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="alert alert-danger">
                <h2>Akses Diblokir</h2>
                <p class="mb-4">
                    <?= esc(session()->getFlashdata('error') ?? 'Anda tidak dapat mengakses halaman ini menggunakan VPN, Proxy, atau jaringan yang tidak diperbolehkan.') ?>
                </p>
                <p>Untuk melakukan voting, silahkan:</p>
                <ol class="text-start">
                    <li>Matikan VPN/Proxy jika sedang digunakan</li>
                    <li>Gunakan koneksi data seluler langsung (bukan WiFi)</li>
                    <li>Pastikan tidak menggunakan browser yang memiliki fitur proxy built-in</li>
                </ol>
                <a href="<?= base_url() ?>" class="btn btn-primary mt-3">Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>