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
                <h1 data-aos="fade-up">Welcome to <span>ORMAONE</span></h1>
                <p data-aos="fade-up" data-aos-delay="100"><b>Satu Web untuk semua kebutuhan organisasi mahasiswa!
                        Kelola
                        acara, koordinasi anggota, pengumuman, dan administrasi dengan mudah dalam satu platform
                        terpadu.</b><br>
                </p>
                <div class="d-flex" data-aos="fade-up" data-aos-delay="200">
                </div>
                <img src="<?= base_url('dist/landing/assets/img/pak.png') ?>" class="img-fluid hero-img" alt="" data-aos="zoom-out"
                    data-aos-delay="300">
            </div>
        </div>

    </section><!-- /Hero Section -->
    <!-- About Section -->
    <section id="about" class="about section">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-6 content" data-aos="fade-up" data-aos-delay="100">
                    <p class="who-we-are">What's new on</p>
                    <h3>OrmaOne</h3>
                    <p class="fst-italic">
                        OrmaOne (Satu aplikasi untuk semua kebutuhan Ormawa) :
                        <br>
                        Sebagai solusi atas permasalahan tersebut, diperlukan suatu sistem informasi terintegrasi
                        yang mampu
                        mengatasi kendala administratif dan meningkatkan transparansi dalam pengelolaan Ormawa.
                        Salah satu solusi
                        yang dapat diterapkan adalah dalam permasalahan tersebut penulis membuat penelitian dengan
                        judul sistem
                        informasi berbasis web bernama "OrmaOne", yang mencakup fitur e-voting, pendataan anggota
                        ormawa digital,
                        serta pembuatan e-sertifikat otomatis guna mendukung operasional Ormawa secara lebih
                        efektif.
                    </p>
                    <ul>
                        <li><i class="bi bi-check-circle"></i> <span>Meningkatkan efisiensi dan transparansi dalam
                                proses
                                pemilihan ketua dan pengurus Ormawa melalui implementasi sistem e-voting yang aman
                                dan mudah
                                digunakan.</span></li>
                        <li><i class="bi bi-check-circle"></i> <span>Memudahkan pengelolaan dan akses data anggota
                                secara terpusat
                                dan terstruktur melalui pendataan anggota digital, sehingga mempermudah komunikasi
                                dan memantau
                                partisipasi anggota dalam kegiatan.
                            </span></li>
                        <li><i class="bi bi-check-circle"></i> <span>Menyederhanakan dan mempercepat proses
                                penerbitan
                                e-sertifikat sebagai bentuk apresiasi kepada anggota yang berpartisipasi dalam
                                kegiatan Ormawa,
                                sehingga mengurangi beban administrasi dan meminimalkan kesalahan.</span></li>
                    </ul>
                    <a href="#" class="read-more"><span>Daftar | login</span><i
                            class="bi bi-arrow-right"></i></a>
                </div>

                <div class="col-lg-6 about-images" data-aos="fade-up" data-aos-delay="200">
                    <div class="row gy-4">
                        <div class="col-lg-6">
                            <img src="<?= base_url('dist/landing/assets/img/BEM_fotbar.jpeg') ?>" class="img-fluid" alt="">
                        </div>
                        <div class="col-lg-6">
                            <div class="row gy-4">
                                <div class="col-lg-12">
                                    <img src="<?= base_url('dist/landing/assets/img/BEM_fotbar2.png') ?>" class="img-fluid" alt="">
                                </div>
                                <div class="col-lg-12">
                                    <img src="<?= base_url('dist/landing/assets/img/BEM_fotbar3.png') ?>" class="img-fluid" alt="">
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </section><!-- /About Section -->

    <!-- Clients Section -->
    <section id="clients" class="clients section">

        <div class="container" data-aos="fade-up">

            <div class="row gy-4">

                <div class="col-xl-2 col-md-3 col-6 client-logo">
                    <a href="https://bos.kemenag.go.id/">
                        <img src="<?= base_url('dist/landing/assets/img/clients/himasif.jpg') ?>" class="img-fluid" alt="">
                    </a>
                </div><!-- End Client Item -->

                <div class="col-xl-2 col-md-3 col-6 client-logo">
                    <a href="https://ijopmadrasah.kemenag.go.id/">
                        <img src="<?= base_url('dist/landing/assets/img/clients/bem.jpg') ?>" class="img-fluid" alt="">
                    </a>
                </div><!-- End Client Item -->

                <div class="col-xl-2 col-md-3 col-6 client-logo">
                    <a href="https://erkam.kemenag.go.id/">
                        <img src="<?= base_url('dist/landing/assets/img/clients/LOGO MERAH BEM.png') ?>" class="img-fluid" alt="">
                    </a>
                </div><!-- End Client Item -->

                <div class="col-xl-2 col-md-3 col-6 client-logo">
                    <a href="https://bos.kemenag.go.id/">
                        <img src="<?= base_url('dist/landing/assets/img/clients/himasif.jpg') ?>" class="img-fluid" alt="">
                    </a>
                </div><!-- End Client Item -->

                <div class="col-xl-2 col-md-3 col-6 client-logo">
                    <a href="https://ijopmadrasah.kemenag.go.id/">
                        <img src="<?= base_url('dist/landing/assets/img/clients/bem.jpg') ?>" class="img-fluid" alt="">
                    </a>
                </div><!-- End Client Item -->

                <div class="col-xl-2 col-md-3 col-6 client-logo">
                    <a href="https://erkam.kemenag.go.id/">
                        <img src="<?= base_url('dist/landing/assets/img/clients/LOGO MERAH BEM.png') ?>" class="img-fluid" alt="">
                    </a>
                </div><!-- End Client Item -->

            </div>

        </div>
    </section>




    <!-- Contact Section -->
    <section id="contact" class="contact section">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <h2>Contact</h2>
            <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
        </div><!-- End Section Title -->

        <div class="container" data-aos="fade-up" data-aos-delay="100">

            <div class="row gy-4">

                <div class="col-lg-6">
                    <div class="info-item d-flex flex-column justify-content-center align-items-center"
                        data-aos="fade-up" data-aos-delay="200">
                        <i class="bi bi-geo-alt"></i>
                        <h3>Address</h3>
                        <p>Jl. Husni Hamid No. 1, Nagasari, 41312</p>
                    </div>
                </div><!-- End Info Item -->

                <div class="col-lg-3 col-md-6">
                    <div class="info-item d-flex flex-column justify-content-center align-items-center"
                        data-aos="fade-up" data-aos-delay="300">
                        <i class="bi bi-telephone"></i>
                        <h3>Call Us</h3>
                        <p>+62-812-9755-4172</p>
                    </div>
                </div><!-- End Info Item -->

                <div class="col-lg-3 col-md-6">
                    <div class="info-item d-flex flex-column justify-content-center align-items-center"
                        data-aos="fade-up" data-aos-delay="400">
                        <i class="bi bi-envelope"></i>
                        <h3>Email Us</h3>
                        <p>BemHorizon@gmail.com</p>
                    </div>
                </div><!-- End Info Item -->

            </div>

            <div class="row gy-4 mt-1">
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="300">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.7084579862653!2d107.30037727355553!3d-6.301985161673499!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6977e86cfea273%3A0xc7878bb42a30e610!2sKantor%20Kementerian%20Agama%20Kab.%20Karawang!5e0!3m2!1sid!2sid!4v1731467152042!5m2!1sid!2sid"
                        width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div><!-- End Google Maps -->

                <div class="col-lg-6">
                    <form action="#" method="post" class="php-email-form" data-aos="fade-up"
                        data-aos-delay="400">
                        <div class="row gy-4">

                            <div class="col-md-6">
                                <input type="text" name="name" class="form-control"
                                    placeholder="Your Name" required="">
                            </div>

                            <div class="col-md-6 ">
                                <input type="email" class="form-control" name="email"
                                    placeholder="Your Email" required="">
                            </div>

                            <div class="col-md-12">
                                <input type="text" class="form-control" name="subject"
                                    placeholder="Subject" required="">
                            </div>

                            <div class="col-md-12">
                                <textarea class="form-control" name="message" rows="6" placeholder="Message" required=""></textarea>
                            </div>

                            <div class="col-md-12 text-center">
                                <div class="loading">Loading</div>
                                <div class="error-message"></div>
                                <div class="sent-message">Your message has been sent. Thank you!</div>

                                <button type="submit">Send Message</button>
                            </div>

                        </div>
                    </form>
                </div><!-- End Contact Form -->

            </div>

        </div>

    </section><!-- /Contact Section -->
<?= $this->endSection() ?>