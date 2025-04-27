<?= $this->extend('components/layouts/landing') ?>

<?= $this->section('title') ?>
    <?= $title ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
    <!-- Hero Section -->
    <section id="hero" class="hero section">
        <div class="hero-bg">
            <img src="<?= base_url('dist/landing/assets/img/bg_1.png') ?>" alt="">
        </div>
        <div class="container text-center">
            <div class="d-flex flex-column justify-content-center align-items-center">
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-6 text-center">
                        <img src="<?= base_url('dist/landing/assets/img/logo_horizon.png') ?>" width="20%" height="20px" class="img-fluid" alt="Sertifikat">
                        <h2 style="color: #980517;">Horizon University Indonesia</h2>
                        <h2 style="color: #980517;">Verifikasi Sertifikat Digital</h2>
                        <p>Sistem verifikasi sertifikat digital yang aman dan terpercaya. Cari dan verifikasi sertifikat Anda dengan mudah.</p>
                        <!-- Form Pencarian -->
                        <div class="container mt-2">
                            <form method="GET" action="" class="d-flex justify-content-end mb-4">
                                <input type="text" name="search" class="form-control me-2  search-bar"
                                    placeholder="Cari nama lengkap..." value="">
                                <button type="submit" class="btn btn-primary">Cari</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!-- /Hero Section -->
    <br><br>
    <!-- ======= Gallery Section ======= -->
    <section id="gallery" class="gallery">
        <div class="container-fluid">
            <div class="row gy-4 justify-content-center">
                <?php for($i = 0; $i < 6; $i++): ?>
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="gallery-item h-100">
                            <!-- Nama -->
                            <p>
                                Kholiskamal354
                            </p>
                            <!-- Gambar Sertifikat -->
                            <img src="<?= base_url('dist/landing/assets/img/final-1732003966-Page 1.jpg') ?>" class="img-fluid" alt="Sertifikat">
                            <div class="gallery-links d-flex align-items-center justify-content-center">
                                <!-- Tautan Gambar Sertifikat -->
                                <a href="<?= base_url('dist/landing/assets/img/final-1732003966-Page 1.jpg') ?>"
                                    title="Sertifikat-digital"
                                    class="glightbox preview-link">
                                    <i class="bi bi-arrows-angle-expand"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    </section><!-- End Gallery Section -->
<?= $this->endSection() ?>
