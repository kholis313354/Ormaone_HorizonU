<?= $this->extend('components/layouts/landing') ?>

<?= $this->section('title') ?>
Verifikasi Gagal - <?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <h4 class="card-title text-center mb-0">
                            <i class="bi bi-x-circle-fill me-2"></i>
                            Verifikasi Gagal
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <img src="<?= base_url('dist/landing/assets/img/logo_horizon.png') ?>" alt="Logo" style="height: 80px;">
                            <h3 class="mt-3" style="color: #980517;">Horizon University Indonesia</h3>
                        </div>

                        <div class="alert alert-danger">
                            <h5 class="alert-heading">Sertifikat tidak ditemukan!</h5>
                            <p class="mb-0">Sertifikat yang Anda coba verifikasi tidak ditemukan dalam database kami. Mungkin sertifikat telah dihapus atau sertifikat tidak valid.</p>
                        </div>

                        <div class="text-center mt-4">
                            <a href="<?= base_url('sertifikat') ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali ke Halaman Sertifikat
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>