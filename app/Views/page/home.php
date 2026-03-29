<?= $this->extend('components/layouts/landing') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>
<?= $this->section('styles') ?>
<style>
    /* Clients Carousel Styles */
    .clients-carousel-wrapper {
        overflow: hidden;
        width: 100%;
        position: relative;
    }

    .clients-carousel {
        display: flex;
        transition: transform 0.5s ease-in-out;
        will-change: transform;
    }

    .client-logo-item {
        flex: 0 0 auto;
        width: calc(100% / 6);
        padding: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 120px;
        /* Fixed height untuk konsistensi */
    }

    .client-logo-item img {
        width: 100px;
        height: 100px;
        object-fit: contain;
        object-position: center;
        filter: grayscale(100%);
        opacity: 0.6;
        transition: all 0.3s ease;
    }

    .client-logo-item img:hover {
        filter: grayscale(0%);
        opacity: 1;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .client-logo-item {
            width: calc(100% / 4);
        }
    }

    @media (max-width: 768px) {
        .client-logo-item {
            width: calc(100% / 3);
        }
    }

    @media (max-width: 576px) {
        .client-logo-item {
            width: calc(100% / 2);
        }
    }

    /* Security: Honeypot field styling */
    #website {
        position: absolute !important;
        left: -9999px !important;
        opacity: 0 !important;
        pointer-events: none !important;
        visibility: hidden !important;
    }

    /* Form security styling */
    .btn-submit {
        position: relative;
        min-width: 150px;
    }

    .spinner-border-sm {
        width: 1rem;
        height: 1rem;
        border-width: 0.2em;
    }

    #char_count {
        font-weight: 500;
    }
</style>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<!-- Hero Section -->
<section id="hero" class="hero section">
    <div class="hero-bg">
        <img src="<?= base_url('dist/landing/assets/img/bg_1.png') ?>" alt="Background" loading="eager"
            decoding="async">
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
            <img src="<?= base_url('dist/landing/assets/img/clients/pak.png') ?>" class="img-fluid hero-img"
                alt="Hero Image" data-aos="zoom-out" data-aos-delay="300" loading="lazy" decoding="async">
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
                    <br>diperlukan suatu sistem informasi terintegrasi
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

            </div>

            <div class="col-lg-6 about-images" data-aos="fade-up" data-aos-delay="200">
                <div class="row gy-4">
                    <div class="col-lg-6">
                        <div class="row gy-4">
                            <div class="col-lg-12">
                                <img src="<?= base_url('dist/landing/assets/img/fotbarBem.jpeg') ?>" class="img-fluid"
                                    alt="Foto Baris BEM" loading="lazy" decoding="async">
                            </div>
                            <div class="col-lg-12">
                                <img src="<?= base_url('dist/landing/assets/img/fotbarBem1.jpeg') ?>" class="img-fluid"
                                    alt="Foto Baris BEM 1" loading="lazy" decoding="async">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="row gy-4">
                            <div class="col-lg-12">
                                <img src="<?= base_url('dist/landing/assets/img/fotbarBem2.jpeg') ?>" class="img-fluid"
                                    alt="Foto Baris BEM 2" loading="lazy" decoding="async">
                            </div>
                            <div class="col-lg-12">
                                <img src="<?= base_url('dist/landing/assets/img/fotbarBem.jpeg') ?>" class="img-fluid"
                                    alt="Foto Baris BEM" loading="lazy" decoding="async">
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
        <?php if (!empty($organisasis)): ?>
            <?php
            $organisasisWithImage = array_filter($organisasis, function ($org) {
                return !empty($org['image']);
            });
            ?>
            <?php if (!empty($organisasisWithImage)): ?>
                <div class="clients-carousel-wrapper">
                    <div class="clients-carousel" id="clientsCarousel">
                        <?php foreach ($organisasisWithImage as $organisasi): ?>
                            <div class="client-logo-item">
                                <img src="<?= base_url('uploads/' . $organisasi['image']) ?>" class="img-fluid"
                                    alt="<?= esc($organisasi['name']) ?>" title="<?= esc($organisasi['name']) ?>" loading="lazy"
                                    decoding="async">
                            </div>
                        <?php endforeach; ?>
                        <!-- Duplicate items untuk infinite loop -->
                        <?php foreach ($organisasisWithImage as $organisasi): ?>
                            <div class="client-logo-item">
                                <img src="<?= base_url('uploads/' . $organisasi['image']) ?>" class="img-fluid"
                                    alt="<?= esc($organisasi['name']) ?>" title="<?= esc($organisasi['name']) ?>" loading="lazy"
                                    decoding="async">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p class="text-muted">Belum ada organisasi yang terdaftar</p>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <p class="text-muted">Belum ada organisasi yang terdaftar</p>
            </div>
        <?php endif; ?>
    </div>
</section>




<!-- Contact Section -->
<section id="contact" class="contact section">

    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
        <h2>Contact</h2>
        <p>Hubungi kami untuk pertanyaan, Aspirasi Mahasiswa, atau dukungan. Kami siap membantu Anda. Silakan mengisi formulir kontak di bawah ini, dan kami akan segera
            merespons Via Email. Masukan Anda sangat penting bagi kami!</p>
    </div><!-- End Section Title -->

    <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">

            <div class="col-lg-6">
                <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up"
                    data-aos-delay="200">
                    <i class="bi bi-geo-alt"></i>
                    <h3>Address</h3>
                    <p>Jl. Husni Hamid No. 1, Nagasari, 41312</p>
                </div>
            </div><!-- End Info Item -->

            <div class="col-lg-3 col-md-6">
                <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up"
                    data-aos-delay="300">
                    <i class="bi bi-telephone"></i>
                    <h3>Call Us</h3>
                    <p>+62-812-9755-4172</p>
                </div>
            </div><!-- End Info Item -->

            <div class="col-lg-3 col-md-6">
                <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up"
                    data-aos-delay="400">
                    <i class="bi bi-envelope"></i>
                    <h3>Email Us</h3>
                    <p>BemHorizon@gmail.com</p>
                </div>
            </div><!-- End Info Item -->

        </div>

        <div class="row gy-4 mt-1">
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="300">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.8037018131095!2d107.28976947499078!3d-6.289512593699482!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e697760017df9ad%3A0x74508c4a886051a4!2sHorizon%20University%20Indonesia!5e0!3m2!1sid!2sid!4v1746497191779!5m2!1sid!2sid"
                    width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div><!-- End Google Maps -->

            <div class="col-lg-6">
                <!-- Tambahkan ini di bagian <head> atau sebelum </body> -->
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                <!-- Form Kirim Pesan -->
                <form action="<?= site_url('contact/send') ?>" method="post" class="php-email-form" id="contactForm"
                    data-aos="fade-up" data-aos-delay="400">
                    <?= csrf_field() ?>

                    <!-- Honeypot Field (Hidden from users, but bots will fill it) -->
                    <div style="position: absolute; left: -9999px; opacity: 0; pointer-events: none;"
                        aria-hidden="true">
                        <label for="website">Website (jangan isi jika Anda manusia)</label>
                        <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                    </div>

                    <!-- Form Start Time (for time-based validation) -->
                    <input type="hidden" name="form_start_time" id="form_start_time" value="<?= time() ?>">

                    <div class="row gy-4">
                        <div class="col-md-6">
                            <input type="text" name="name" id="contact_name" class="form-control"
                                placeholder="Name" required minlength="2" maxlength="100" pattern="[A-Za-z\s]+"
                                value="<?= old('name') ?>"
                                oninvalid="this.setCustomValidity('Nama hanya boleh mengandung huruf dan spasi, minimal 2 karakter')"
                                oninput="this.setCustomValidity('')">
                        </div>

                        <div class="col-md-6">
                            <input type="email" class="form-control" name="email" id="contact_email"
                                placeholder="Email Kampus" required maxlength="255" value="<?= old('email') ?>">
                        </div>

                        <div class="col-md-12">
                            <input type="text" class="form-control" name="subject" id="contact_subject"
                                placeholder="Subject" required minlength="3" maxlength="200"
                                value="<?= old('subject') ?>">
                        </div>

                        <div class="col-md-12">
                            <textarea class="form-control" name="message" id="contact_message" rows="6"
                                placeholder="Message" required minlength="10" maxlength="2000"
                                oninput="updateCharCount(this)"><?= old('message') ?></textarea>
                            <small class="text-muted">
                                <span id="char_count">0</span> / 2000 karakter
                            </small>
                        </div>

                        <div class="col-md-12 text-center">
                            <button type="submit" id="submitBtn" class="btn-submit">
                                <span id="submitText">Send Message</span>
                                <span id="submitLoader" style="display: none;">
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span> Mengirim...
                                </span>
                            </button>
                        </div>

                    </div>
                </form>

                <script>
                    // Character counter
                    function updateCharCount(textarea) {
                        const count = textarea.value.length;
                        const charCountEl = document.getElementById('char_count');
                        if (charCountEl) {
                            charCountEl.textContent = count;
                            if (count > 1800) {
                                charCountEl.style.color = '#dc3545';
                            } else if (count > 1500) {
                                charCountEl.style.color = '#ffc107';
                            } else {
                                charCountEl.style.color = '#6c757d';
                            }
                        }
                    }

                    // Initialize character count on page load
                    document.addEventListener('DOMContentLoaded', function () {
                        const messageTextarea = document.getElementById('contact_message');
                        if (messageTextarea) {
                            updateCharCount(messageTextarea);
                        }

                        // Form validation and security
                        const form = document.getElementById('contactForm');
                        if (form) {
                            // Prevent double submission
                            let isSubmitting = false;

                            form.addEventListener('submit', function (e) {
                                if (isSubmitting) {
                                    e.preventDefault();
                                    return false;
                                }

                                // Validate honeypot
                                const honeypot = document.getElementById('website');
                                if (honeypot && honeypot.value !== '') {
                                    e.preventDefault();
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Akses Ditolak',
                                        text: 'Form tidak valid.',
                                        confirmButtonColor: '#d33'
                                    });
                                    return false;
                                }

                                // Validate time
                                const formStartTime = parseInt(document.getElementById('form_start_time').value);
                                const currentTime = Math.floor(Date.now() / 1000);
                                const timeElapsed = currentTime - formStartTime;

                                if (timeElapsed < 5) {
                                    e.preventDefault();
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Terlalu Cepat',
                                        text: 'Mohon isi form dengan lebih teliti. Form dikirim terlalu cepat.',
                                        confirmButtonColor: '#ffc107'
                                    });
                                    return false;
                                }

                                // Validate Email Domain
                                const emailInput = document.getElementById('contact_email');
                                if (emailInput) {
                                    const email = emailInput.value.trim();
                                    if (!email.endsWith('@krw.horizon.ac.id')) {
                                        e.preventDefault();
                                        Swal.fire({
                                            icon: 'warning',
                                            title: 'Gunakan Email Kampus',
                                            text: 'Email harus menggunakan Email Kampus',
                                            confirmButtonColor: '#ffc107'
                                        });
                                        return false;
                                    }
                                }

                                // Validate inputs
                                const name = document.getElementById('contact_name').value.trim();
                                const email = document.getElementById('contact_email').value.trim();
                                const subject = document.getElementById('contact_subject').value.trim();
                                const message = document.getElementById('contact_message').value.trim();

                                // Check for suspicious content
                                const spamPatterns = ['viagra', 'casino', 'lottery', 'winner', 'click here', 'buy now'];
                                let spamCount = 0;
                                const messageLower = message.toLowerCase();
                                const subjectLower = subject.toLowerCase();

                                spamPatterns.forEach(pattern => {
                                    if (messageLower.includes(pattern) || subjectLower.includes(pattern)) {
                                        spamCount++;
                                    }
                                });

                                if (spamCount >= 3) {
                                    e.preventDefault();
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Pesan Terdeteksi sebagai Spam',
                                        text: 'Pesan Anda mengandung konten yang mencurigakan. Silakan gunakan bahasa yang lebih formal.',
                                        confirmButtonColor: '#d33'
                                    });
                                    return false;
                                }

                                // Show loading
                                isSubmitting = true;
                                const submitBtn = document.getElementById('submitBtn');
                                const submitText = document.getElementById('submitText');
                                const submitLoader = document.getElementById('submitLoader');

                                if (submitBtn) submitBtn.disabled = true;
                                if (submitText) submitText.style.display = 'none';
                                if (submitLoader) submitLoader.style.display = 'inline-block';

                                // Allow form to submit normally
                                return true;
                            });
                        }
                    });
                </script>

                <!-- Cek SweetAlert Flashdata -->
                <?php if (session()->getFlashdata('success')): ?>
                    <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "WebSite",
          "name": "OrmaOne",
          "url": "https://ormaone.com/",
          "potentialAction": {
            "@type": "SearchAction",
            "target": "https://ormaone.com/search?q={search_term_string}",
            "query-input": "required name=search_term_string"
          },
          "publisher": {
            "@type": "Organization",
            "name": "OrmaOne",
            "logo": {
              "@type": "ImageObject",
              "url": "https://ormaone.com/dist/landing/assets/img/logo1.png",
              "width": 600,
              "height": 200
            }
          }
        }
        </script>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Terima kasih🙏',
                            html: '<p style="margin-bottom: 10px;">Pesan Anda telah berhasil kami terima.</p><p style="margin: 0;">Tim kami akan segera meninjau dan membalas melalui email yang Anda berikan.</p>',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        });
                    </script>
                <?php endif; ?>

                <!-- Menampilkan pesan error jika email tidak sesuai -->
                <?php if (session()->getFlashdata('error')): ?>
                    <?php
                    $errorMessage = session()->getFlashdata('error');
                    // Escape untuk JavaScript, tapi tetap biarkan HTML
                    $errorMessageJs = json_encode($errorMessage);
                    ?>
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            html: <?= $errorMessageJs ?>,
                            confirmButtonColor: '#d33',
                            confirmButtonText: 'OK'
                        });
                    </script>
                <?php endif; ?>



            </div><!-- End Contact Form -->

        </div>

    </div>

</section><!-- /Contact Section -->
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const carousel = document.getElementById('clientsCarousel');
        if (!carousel) return;

        const items = carousel.querySelectorAll('.client-logo-item');
        if (items.length === 0) return;

        // Hitung jumlah item asli (setengah dari total karena ada duplikasi)
        const totalItems = items.length;
        const originalItems = totalItems / 2;

        let currentIndex = 0;
        let itemWidth = 0;
        let intervalId = null;
        const slideInterval = 2000; // 2 detik

        function calculateItemWidth() {
            if (items.length > 0) {
                itemWidth = items[0].offsetWidth;
            }
        }

        function moveCarousel() {
            calculateItemWidth();

            // Jika sudah sampai di akhir set pertama, reset ke awal tanpa animasi
            if (currentIndex >= originalItems) {
                carousel.style.transition = 'none';
                currentIndex = 0;
                carousel.style.transform = `translateX(0)`;
                // Force reflow
                void carousel.offsetWidth;
                carousel.style.transition = 'transform 0.5s ease-in-out';
            }

            // Geser ke kanan satu item
            const translateX = -(currentIndex * itemWidth);
            carousel.style.transform = `translateX(${translateX}px)`;

            currentIndex++;
        }

        function startCarousel() {
            calculateItemWidth();
            if (intervalId) {
                clearInterval(intervalId);
            }
            intervalId = setInterval(moveCarousel, slideInterval);
        }

        // Mulai carousel otomatis setelah halaman dimuat
        setTimeout(function () {
            startCarousel();
        }, 1000);

        // Pause saat hover
        const wrapper = carousel.closest('.clients-carousel-wrapper');
        if (wrapper) {
            wrapper.addEventListener('mouseenter', function () {
                if (intervalId) {
                    clearInterval(intervalId);
                }
            });

            wrapper.addEventListener('mouseleave', function () {
                startCarousel();
            });
        }

        // Handle window resize
        let resizeTimer;
        window.addEventListener('resize', function () {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function () {
                calculateItemWidth();
                // Reset carousel position
                carousel.style.transition = 'none';
                currentIndex = 0;
                carousel.style.transform = `translateX(0)`;
                void carousel.offsetWidth;
                carousel.style.transition = 'transform 0.5s ease-in-out';
                // Restart carousel
                startCarousel();
            }, 250);
        });
    });
</script>
<?= $this->endSection() ?>