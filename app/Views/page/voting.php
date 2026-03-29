<?= $this->extend('components/layouts/landing') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>
<?= $this->section('styles') ?>
<link href="<?= base_url('dist/landing/assets/css/main1.css') ?>" rel="stylesheet">
<link href="<?= base_url('dist/landing/assets/css/styles4.css') ?>" rel="stylesheet" />
<link rel="stylesheet" href="<?= base_url('dist/landing/assets/css/styles5.css') ?>">
<!-- Owl Carousel CSS -->
<link rel="stylesheet" href="<?= base_url('dist/landing/assets/lib/owlcarousel/assets/owl.carousel.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('dist/landing/assets/lib/owlcarousel/assets/owl.theme.default.min.css') ?>">
<style>
    /* ============================
       STYLE KANDIDAT E-VOTING
       ============================ */

    /* Section wrapper */
    .categories {
        background-color: #ffffff;
    }

    /* Fallback layout: sebelum Owl Carousel terinisialisasi,
       tampilkan item sebagai grid rapi */
    .categories-carousel {
        display: flex;
        flex-wrap: wrap;
        gap: 24px;
        justify-content: center;
    }

    /* Setelah Owl Carousel aktif, biarkan plugin yang atur layout */
    .categories-carousel.owl-loaded {
        display: block;
    }

    /* Kartu kandidat */
    .categories-item {
        background: #ffffff;
        border-radius: 18px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
        min-width: 260px;
        max-width: 320px;
        flex: 0 0 auto;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .categories-item:hover {
        transform: translateY(-6px);
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.12);
    }

    .categories-item-inner {
        height: 100%;
    }

    /* Gambar kandidat */
    .categories-img {
        border-top-left-radius: 18px;
        border-top-right-radius: 18px;
        overflow: hidden;
        background-color: #980517;
    }

    .categories-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    /* Konten kartu */
    .categories-content {
        background-color: #ffffff;
        border-bottom-left-radius: 18px;
        border-bottom-right-radius: 18px;
    }

    .categories-content .h4 {
        font-size: 1rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 0.5rem;
    }

    .categories-content .h4:hover {
        color: #980517;
        text-decoration: none;
    }

    .card-description {
        font-size: 0.9rem;
        color: #4b5563;
        line-height: 1.5;
    }

    .categories .btn {
        font-weight: 600;
        letter-spacing: 0.02em;
    }

    /* Responsive tweak */
    @media (max-width: 576px) {
        .categories-item {
            min-width: 240px;
            max-width: 100%;
        }
    }
</style>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<?php
// Load helper untuk encrypt ID
helper('nav');
?>
<!-- Hero Section -->
<header class="py-5 bg-light border-bottom mb-4">
    <div class="container">
        <div class="text-center my-5">
            <br><br><br>
            <img src="<?= base_url('dist/landing/assets/img/Vector.png') ?>" class="img-fluid" alt="Sertifikat">
            <h1 class="fw-bolder" style="color: #980517;">E-Voting</h1>
            <p class="lead mb-0">E-Voting Organisasi Mahasiswa adalah sistem pemilihan elektronik yang dirancang
                untuk mempermudah, mempercepat, dan meningkatkan transparansi dalam proses demokrasi kampus.</p>
        </div>
        <div class=" d-flex justify-content-center">
            <!-- Time with bootstrap 5 -->
            <?php if (!isset($isSelesai) || !$isSelesai): ?>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header " style="background-color: #980517;">
                                <h4 class="mb-0 text-center text-white">Batas waktu Voting</h4>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-3">
                                        <h3 id="days" class="display-4" style="color: #980517;">00</h3>
                                        <p>Hari</p>
                                    </div>
                                    <div class="col-3">
                                        <h3 id="hours" class="display-4" style="color: #980517;">00</h3>
                                        <p>Jam</p>
                                    </div>
                                    <div class="col-3">
                                        <h3 id="minutes" class="display-4" style="color: #980517;">00</h3>
                                        <p>Menit</p>
                                    </div>
                                    <div class="col-3">
                                        <h3 id="seconds" class="display-4" style="color: #980517;">00</h3>
                                        <p>Detik</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <!-- Tampilkan 0 semua jika status selesai -->
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header " style="background-color: #980517;">
                                <h4 class="mb-0 text-center text-white">Batas waktu Voting</h4>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-3">
                                        <h3 id="days" class="display-4" style="color: #980517;">00</h3>
                                        <p>Hari</p>
                                    </div>
                                    <div class="col-3">
                                        <h3 id="hours" class="display-4" style="color: #980517;">00</h3>
                                        <p>Jam</p>
                                    </div>
                                    <div class="col-3">
                                        <h3 id="minutes" class="display-4" style="color: #980517;">00</h3>
                                        <p>Menit</p>
                                    </div>
                                    <div class="col-3">
                                        <h3 id="seconds" class="display-4" style="color: #980517;">00</h3>
                                        <p>Detik</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</header>


<!-- Card content-->
<!-- Card content -->
<?php if (!isset($isSelesai) || !$isSelesai): ?>
<div class="card mb-4 border-0 shadow-sm" style="border-radius: 12px;">
    <div class="card-body p-2 p-md-4">
        <div class="chart-container p-2 bg-white rounded-3" style="box-shadow: 0 1px 4px rgba(0,0,0,0.05);">
            <h6 class="text-center mb-1 mb-md-2 fw-semibold" style="color: #333; font-size: clamp(0.8rem, 2.5vw, 1rem);">
                Jumlah seluruh Suara Voting
            </h6>
            <div class="chart-wrapper" style="height: clamp(180px, 40vh, 250px); min-height: 180px; position: relative;">
                <canvas id="chart-voting"></canvas>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Card conten end -->
<!-- Bootstrap sudah di-load di layout dengan defer, tidak perlu duplikat -->

<!-- Card Kandidat Start -->
<div class="container-fluid categories pb-5">
    <div class="container pb-5">
        <div class="text-center mx-auto pb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 800px;">
            <h1 class="display-5 text-capitalize mb-3">Kandidat <span style="color: #980517;">E-Voting</span></h1>
        </div>
        
        <?php 
        // Debug: Cek data calon
        // var_dump($calon); // Uncomment untuk debug
        
        // Cek apakah ada data calon dan validasi struktur data
        if (empty($calon) || !is_array($calon) || count($calon) === 0): ?>
            <div class="alert alert-info text-center" role="alert">
                <h5>Tidak ada kandidat yang tersedia saat ini.</h5>
                <p>Belum ada pemilihan yang aktif atau kandidat belum terdaftar.</p>
            </div>
        <?php else:
        // Kelompokkan kandidat berdasarkan organisasi
        $groupedCandidates = [];
        foreach ($calon as $c) {
            // Validasi: pastikan $c adalah array dan memiliki key 'organisasi_name'
            if (!is_array($c)) {
                // Log error untuk debugging (bisa dihapus di production)
                log_message('error', 'Invalid calon data: ' . print_r($c, true));
                continue; // Skip jika data tidak valid
            }
            
            // Pastikan key 'organisasi_name' ada dan tidak null
            if (!isset($c['organisasi_name']) || empty($c['organisasi_name'])) {
                continue; // Skip jika organisasi_name tidak ada
            }
            
            $orgName = (string)$c['organisasi_name'];
            if (!array_key_exists($orgName, $groupedCandidates)) {
                $groupedCandidates[$orgName] = [];
            }
            $groupedCandidates[$orgName][] = $c;
        }
        
        // Tampilkan per kelompok organisasi
        foreach ($groupedCandidates as $orgName => $candidates): ?>
            <div class="mb-5">
                <h2 class="text-capitalize mb-4 wow fadeInUp" data-wow-delay="0.1s">Kandidat <?= esc(data: $orgName) ?></h2>
                <div class="categories-carousel owl-carousel wow fadeInUp" data-wow-delay="0.1s">
                    <?php foreach ($candidates as $c): ?>
                        <div class="categories-item p-4">
                            <div class="categories-item-inner d-flex flex-column h-100">
                                <div class="categories-img rounded-top" style="background-color: #980517; height: 250px; overflow: hidden;">
                                    <img src="<?= base_url('uploads/' . esc($c['gambar_1'])) ?>" class="img-fluid w-100 h-100 object-fit-cover rounded-top" alt="" loading="lazy" decoding="async">
                                </div>
                                <div class="categories-content rounded-bottom p-4 d-flex flex-column flex-grow-1">
                                    <a href="#" class="h4 d-block mb-3 text-truncate">
                                        <?= esc($c['anggota_1_name'] ?? '') ?> - <?= esc($c['anggota_2_name'] ?? '') ?>
                                    </a>
                                    <div class="categories-review mb-4 flex-grow-1">
                                        <div class="card-description" style="
                                            display: -webkit-box;
                                            -webkit-line-clamp: 4;
                                            -webkit-box-orient: vertical;
                                            overflow: hidden;
                                            text-overflow: ellipsis;
                                            min-height: 96px;">
                                            <?= esc(strip_tags($c['description'] ?? '')) ?>
                                        </div>
                                    </div>
                                    <a href="<?= base_url('voting/' . encrypt_voting_id((int)$c['id'])) ?>" class="btn rounded-pill d-flex justify-content-center py-3 mt-auto"
                                        style="background-color: #980517; color: #fff;">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; 
        endif; // end if empty($calon) ?>
    </div>
</div>
<!-- Card Kandidat End -->
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Library JS untuk animasi dan carousel -->
<script src="<?= base_url('dist/landing/assets/lib/wow/wow.min.js') ?>"></script>
<script src="<?= base_url('dist/landing/assets/lib/easing/easing.min.js') ?>"></script>
<script src="<?= base_url('dist/landing/assets/lib/waypoints/waypoints.min.js') ?>"></script>
<script src="<?= base_url('dist/landing/assets/lib/counterup/counterup.min.js') ?>"></script>
<!-- Owl Carousel JS - harus di-load sebelum inisialisasi -->
<script src="<?= base_url('dist/landing/assets/lib/owlcarousel/owl.carousel.min.js') ?>"></script>
<script src="<?= base_url('dist/landing/assets/js/main.js') ?>"></script>

<!-- Initialize Owl Carousel untuk kandidat -->
<script>
    // Tunggu jQuery dan Owl Carousel ter-load
    function initOwlCarousel() {
        // Cek apakah jQuery sudah ter-load
        if (typeof jQuery === 'undefined') {
            console.log('Menunggu jQuery...');
            setTimeout(initOwlCarousel, 100);
            return;
        }
        
        // Cek apakah Owl Carousel sudah ter-load
        if (typeof jQuery.fn.owlCarousel === 'undefined') {
            console.log('Menunggu Owl Carousel...');
            setTimeout(initOwlCarousel, 100);
            return;
        }
        
        console.log('Menginisialisasi Owl Carousel...');
        var carousels = jQuery('.categories-carousel');
        console.log('Ditemukan ' + carousels.length + ' carousel');
        
        carousels.each(function(index) {
            var $carousel = jQuery(this);
            if (!$carousel.hasClass('owl-loaded')) {
                console.log('Inisialisasi carousel ke-' + (index + 1));
                $carousel.owlCarousel({
                    autoplay: true,
                    autoplayTimeout: 5000,
                    autoplayHoverPause: true,
                    smartSpeed: 1000,
                    dots: false,
                    loop: true,
                    margin: 25,
                    nav: true,
                    navText: [
                        '<i class="bi bi-chevron-left"></i>',
                        '<i class="bi bi-chevron-right"></i>'
                    ],
                    responsiveClass: true,
                    responsive: {
                        0: {
                            items: 1,
                            nav: false
                        },
                        576: {
                            items: 2,
                            nav: false
                        },
                        768: {
                            items: 2,
                            nav: true
                        },
                        992: {
                            items: 3,
                            nav: true
                        },
                        1200: {
                            items: 4,
                            nav: true
                        }
                    }
                });
                console.log('Carousel ke-' + (index + 1) + ' berhasil diinisialisasi');
            } else {
                console.log('Carousel ke-' + (index + 1) + ' sudah diinisialisasi');
            }
        });
    }
    
    // Mulai inisialisasi setelah semua script ter-load
    // Gunakan multiple event untuk memastikan script ter-load
    if (document.readyState === 'complete') {
        setTimeout(initOwlCarousel, 1000);
    } else {
        window.addEventListener('load', function() {
            setTimeout(initOwlCarousel, 1000);
        });
    }
    
    // Fallback: coba lagi setelah beberapa detik
    setTimeout(function() {
        if (typeof jQuery !== 'undefined' && typeof jQuery.fn.owlCarousel !== 'undefined') {
            initOwlCarousel();
        }
    }, 2000);
</script>
<!-- script card Content -->
<?php if (!isset($isSelesai) || !$isSelesai): ?>
<script>
    // Tunggu Chart.js ter-load karena menggunakan defer
    document.addEventListener('DOMContentLoaded', function() {
        // Tunggu Chart.js library ter-load
        function initChart() {
            if (typeof Chart === 'undefined') {
                setTimeout(initChart, 100);
                return;
            }
            
            var ctxY = document.getElementById('chart-voting');
            if (!ctxY) {
                console.log('Canvas chart-voting tidak ditemukan');
                return;
            }
            
            ctxY = ctxY.getContext('2d');
    var labels = [];
    var datas = [];
    <?php foreach ($totalSuaras as $key => $val): ?>
        // Bersihkan nama kandidat dengan menghapus bagian setelah '-' jika ada
        var cleanLabel = '<?= $key ?>'.split('-')[0].trim();
        // Hapus teks 'Total suara:' jika ada
        cleanLabel = cleanLabel.replace('Total suara:', '').trim();
        labels.push(cleanLabel);
        datas.push(<?= $val ?>);
    <?php endforeach; ?>

    // Define colors for the pie chart (konsisten di semua halaman)
    var backgroundColors = [
        '#980517', // Maroon (original color)
        '#4A6FDC', // Blue
        '#2E8B57', // Sea Green
        '#FF8C00', // Dark Orange
        '#9932CC', // Dark Orchid
        '#FF6347', // Tomato
        '#20B2AA', // Light Sea Green
        '#FFD700', // Gold
        '#9370DB', // Medium Purple
        '#3CB371', // Medium Sea Green
        '#FF4500', // Orange Red
        '#4682B4', // Steel Blue
        '#32CD32', // Lime Green
        '#DA70D6', // Orchid
        '#F08080', // Light Coral
        '#1E90FF', // Dodger Blue
        '#9ACD32', // Yellow Green
        '#BA55D3', // Medium Orchid
        '#5F9EA0'  // Cadet Blue
    ];

    // Assign colors to each segment berdasarkan label untuk konsistensi
    // Buat mapping label ke warna agar label yang sama selalu mendapat warna yang sama
    // Menggunakan hash sederhana untuk menentukan warna berdasarkan nama kandidat
    var labelColorMap = {};
    
    // Fungsi untuk generate hash sederhana dari string
    function hashString(str) {
        var hash = 0;
        for (var i = 0; i < str.length; i++) {
            var char = str.charCodeAt(i);
            hash = ((hash << 5) - hash) + char;
            hash = hash & hash; // Convert to 32bit integer
        }
        return Math.abs(hash);
    }
    
    // Assign warna berdasarkan hash nama kandidat untuk konsistensi
    labels.forEach(function(label) {
        if (!labelColorMap[label]) {
            var hash = hashString(label);
            var colorIndex = hash % backgroundColors.length;
            labelColorMap[label] = backgroundColors[colorIndex];
        }
    });
    
    var pieColors = labels.map(function(label) {
        return labelColorMap[label] || backgroundColors[0];
    });

    var chartSuara4 = new Chart(ctxY, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: datas,
                backgroundColor: pieColors,
                borderColor: '#ffffff',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: window.innerWidth < 576 ? 'bottom' : 'right',
                    labels: {
                        font: {
                            size: window.innerWidth < 576 ? 10 : 12,
                            weight: 'bold'
                        },
                        padding: 20,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        generateLabels: function(chart) {
                            var data = chart.data;
                            if (data.labels.length && data.datasets.length) {
                                return data.labels.map(function(label, i) {
                                    return {
                                        text: label + '\nTotal suara: ' + data.datasets[0].data[i],
                                        fillStyle: data.datasets[0].backgroundColor[i],
                                        hidden: false,
                                        lineWidth: 1,
                                        strokeStyle: '#ffffff',
                                        pointStyle: 'circle',
                                        font: {
                                            size: window.innerWidth < 576 ? 10 : 12,
                                            weight: 'bold'
                                        }
                                    };
                                });
                            }
                            return [];
                        }
                    }
                },
                tooltip: {
                    enabled: true,
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    titleFont: {
                        size: window.innerWidth < 768 ? 10 : 12,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: window.innerWidth < 768 ? 9 : 11
                    },
                    callbacks: {
                        label: function(context) {
                            return 'Total suara: ' + context.raw;
                        },
                        afterLabel: function(context) {
                            return ''; // Kosongkan bagian afterLabel
                        }
                    }
                }
            },
            cutout: window.innerWidth < 576 ? '50%' : '30%',
            animation: {
                animateScale: true,
                animateRotate: true
            }
        }
    });

    // Handle window resize
    function handleResize() {
        const isMobile = window.innerWidth < 576;
        const isTablet = window.innerWidth < 768;

        chartSuara4.options.plugins.legend.position = isMobile ? 'bottom' : 'right';
        chartSuara4.options.plugins.legend.labels.font.size = isMobile ? 10 : (isTablet ? 11 : 12);
        chartSuara4.options.plugins.tooltip.titleFont.size = isTablet ? 10 : 12;
        chartSuara4.options.plugins.tooltip.bodyFont.size = isTablet ? 9 : 11;
        chartSuara4.options.cutout = isMobile ? '50%' : '30%';
        chartSuara4.update();
    }

            window.addEventListener('resize', handleResize);
            window.addEventListener('orientationchange', handleResize);
        }
        
        // Mulai inisialisasi chart
        initChart();
    });
</script>
<!-- end script card content -->
<?php endif; ?>
<?php if (!isset($isSelesai) || !$isSelesai): ?>
<script>
    // Set the date we're counting down to
    document.addEventListener('DOMContentLoaded', function() {
        <?php
        // Pastikan $pemilihan ada dan memiliki tanggal_akhir dari database
        $countdownDate = null;
        if ($pemilihan && !empty($pemilihan['tanggal_akhir'])) {
            try {
                // Ambil tanggal_akhir dari database
                $tanggalAkhir = $pemilihan['tanggal_akhir'];
                
                // Handle berbagai format tanggal dari database
                // Format bisa: Y-m-d H:i:s, Y-m-d H:i:s.000000, atau Y-m-d
                if (is_string($tanggalAkhir)) {
                    // Hapus microseconds jika ada (format: Y-m-d H:i:s.000000)
                    $tanggalAkhir = preg_replace('/\.\d+$/', '', trim($tanggalAkhir));
                    
                    // Jika hanya tanggal tanpa waktu, tambahkan waktu 23:59:59
                    if (strpos($tanggalAkhir, ' ') === false) {
                        $tanggalAkhir = $tanggalAkhir . ' 23:59:59';
                    }
                    
                    // Parse ke timestamp
                    $timestamp = strtotime($tanggalAkhir);
                    
                    if ($timestamp !== false && $timestamp > 0) {
                        $countdownDate = $timestamp * 1000; // Convert to milliseconds untuk JavaScript
                    } else {
                        // Jika parsing gagal, gunakan default
                        $countdownDate = strtotime('+1 month') * 1000;
                    }
                } else {
                    // Jika bukan string, gunakan default
                    $countdownDate = strtotime('+1 month') * 1000;
                }
            } catch (\Exception $e) {
                // Jika error, gunakan default
                $countdownDate = strtotime('+1 month') * 1000;
            }
        } else {
            // Default: 1 month from now jika tidak ada pemilihan aktif
            $countdownDate = strtotime('+1 month') * 1000;
        }
        ?>
        const countDownDate = <?= $countdownDate ?>;

        // Cek apakah elemen countdown ada
        const daysEl = document.getElementById("days");
        const hoursEl = document.getElementById("hours");
        const minutesEl = document.getElementById("minutes");
        const secondsEl = document.getElementById("seconds");
        
        if (!daysEl || !hoursEl || !minutesEl || !secondsEl) {
            console.log('Elemen countdown tidak ditemukan');
            return;
        }

        // Update the countdown every 1 second
        const x = setInterval(function() {
            // Get today's date and time
            const now = new Date().getTime();

            // Find the distance between now and the countdown date
            const distance = countDownDate - now;
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Display the result
            if (daysEl) daysEl.innerHTML = days.toString().padStart(2, '0');
            if (hoursEl) hoursEl.innerHTML = hours.toString().padStart(2, '0');
            if (minutesEl) minutesEl.innerHTML = minutes.toString().padStart(2, '0');
            if (secondsEl) secondsEl.innerHTML = seconds.toString().padStart(2, '0');

            // If the countdown is finished, write some text
            if (distance < 0) {
                clearInterval(x);
                if (daysEl) daysEl.innerHTML = "00";
                if (hoursEl) hoursEl.innerHTML = "00";
                if (minutesEl) minutesEl.innerHTML = "00";
                if (secondsEl) secondsEl.innerHTML = "00";
            }
        }, 1000);
    });
</script>
<?php endif; ?>
<?= $this->endSection() ?>