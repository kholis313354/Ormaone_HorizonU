<?= $this->extend('components/layouts/landing') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>
<?= $this->section('styles') ?>
<link href="<?= base_url('dist/landing/assets/css/main1.css') ?>" rel="stylesheet">
<link href="<?= base_url('dist/landing/assets/css/styles4.css') ?>" rel="stylesheet" />
<link rel="stylesheet" href="<?= base_url('dist/landing/assets/css/styles5.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<!-- Hero Section -->
<header class="py-5 bg-light border-bottom mb-4">
    <div class="container">
        <div class="text-center my-5">
            <img src="<?= base_url('dist/landing/assets/img/Vector.png') ?>" class="img-fluid" alt="Sertifikat">
            <h1 class="fw-bolder" style="color: #980517;">E-Voting</h1>
            <p class="lead mb-0">E-Voting Organisasi Mahasiswa adalah sistem pemilihan elektronik yang dirancang
                untuk mempermudah, mempercepat, dan meningkatkan transparansi dalam proses demokrasi kampus.</p>
        </div>
        <div class=" d-flex justify-content-center">
            <!-- Time with bootstrap 5 -->
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
        </div>
    </div>
</header>


<!-- Card content-->
<!-- Card content -->
<div class="card mb-4 border-0 shadow-sm" style="border-radius: 12px;">
    <div class="card-body p-2 p-md-4">
        <h5 class="card-title text-center mb-2 mb-md-3" style="color: #980517; font-weight: 600; font-size: clamp(0.9rem, 3.5vw, 1.25rem);">
            Statistik Voting ORMAWA
        </h5>

        <div class="chart-container p-2 bg-white rounded-3" style="box-shadow: 0 1px 4px rgba(0,0,0,0.05);">
            <h6 class="text-center mb-1 mb-md-2 fw-semibold" style="color: #333; font-size: clamp(0.8rem, 2.5vw, 1rem);">
                Jumlah Suara Voting
            </h6>
            <div class="chart-wrapper" style="height: clamp(180px, 40vh, 250px); min-height: 180px; position: relative;">
                <canvas id="chart-voting"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Card conten end -->
<!-- Bootstrap core JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Core theme JS-->

<!-- Card Kandidat Start -->
<div class="container-fluid categories pb-5">
    <div class="container pb-5">
        <div class="text-center mx-auto pb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 800px;">
            <h1 class="display-5 text-capitalize mb-3">Kandidat <span style="color: #980517;">BEM Universitas</span></h1>
        </div>
        <div class="categories-carousel owl-carousel wow fadeInUp" data-wow-delay="0.1s">
            <?php foreach ($calon as $c) : ?>
                <div class="categories-item p-4">
                    <div class="categories-item-inner d-flex flex-column h-100">
                        <div class="categories-img rounded-top" style="background-color: #980517; height: 250px; overflow: hidden;">
                            <img src="<?= base_url('uploads/' . $c['gambar_1']) ?>" class="img-fluid w-100 h-100 object-fit-cover rounded-top" alt="">
                        </div>
                        <div class="categories-content rounded-bottom p-4 d-flex flex-column flex-grow-1">
                            <a href="#" class="h4 d-block mb-3 text-truncate"><?= htmlspecialchars($c['anggota_1_name'] ?? '') ?> - <?= htmlspecialchars($c['anggota_2_name'] ?? '') ?></a>
                            <div class="categories-review mb-4 flex-grow-1">
                                <div class="card-description" style="
                                    display: -webkit-box;
                                    -webkit-line-clamp: 4;
                                    -webkit-box-orient: vertical;
                                    overflow: hidden;
                                    text-overflow: ellipsis;
                                    min-height: 96px;">
                                    <?= html_entity_decode(strip_tags($c['description'] ?? '')) ?>
                                </div>
                            </div>
                            <a href="<?= url_to('voting.detail', $c['id']) ?>" class="btn rounded-pill d-flex justify-content-center py-3 mt-auto"
                                style="background-color: #980517; color: #fff;">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<!-- Card Kandidat End -->
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('dist/landing/assets/lib/wow/wow.min.js') ?>"></script>
<script src="<?= base_url('dist/landing/assets/lib/easing/easing.min.js') ?>"></script>
<script src="<?= base_url('dist/landing/assets/lib/waypoints/waypoints.min.js') ?>"></script>
<script src="<?= base_url('dist/landing/assets/lib/counterup/counterup.min.js') ?>"></script>
<script src="<?= base_url('dist/landing/assets/lib/owlcarousel/owl.carousel.min.js') ?>"></script>
<script src="<?= base_url('dist/landing/assets/js/main.js') ?>"></script>
<!-- script card Content -->
<script>
    var ctxY = document.getElementById('chart-voting').getContext('2d');
    var labels = [];
    var datas = [];
    <?php foreach ($totalSuaras as $key => $val): ?>
        labels.push('<?= $key ?>');
        datas.push(<?= $val ?>);
    <?php endforeach; ?>

    // Define 5 different colors for the bars
    var backgroundColors = [
        '#980517', // Original maroon
        '#4A6FDC', // Blue
        '#2E8B57', // Sea green
        '#FF8C00', // Dark orange
        '#9932CC' // Dark orchid
    ];

    // Create array of colors matching each candidate
    var barColors = [];
    for (var i = 0; i < labels.length; i++) {
        barColors.push(backgroundColors[i % backgroundColors.length]);
    }

    var chartSuara4 = new Chart(ctxY, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                data: datas,
                backgroundColor: barColors,
                borderWidth: 1,
                borderRadius: 4,
                barPercentage: 0.6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    enabled: window.innerWidth > 576,
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
                            return ' Jumlah Suara: ' + context.raw;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.05)',
                        drawBorder: false
                    },
                    ticks: {
                        stepSize: 50,
                        font: {
                            weight: 'bold',
                            size: window.innerWidth < 576 ? 8 : 10
                        },
                        padding: 5
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            weight: 'bold',
                            size: window.innerWidth < 576 ? 8 : 10
                        },
                        padding: 5
                    }
                }
            },
            layout: {
                padding: {
                    top: 10,
                    right: window.innerWidth < 576 ? 5 : 10,
                    left: window.innerWidth < 576 ? 5 : 10,
                    bottom: 10
                }
            }
        }
    });

    // Handle window resize
    function handleResize() {
        const isMobile = window.innerWidth < 576;
        const isTablet = window.innerWidth < 768;

        chartSuara4.options.plugins.tooltip.enabled = !isMobile;
        chartSuara4.options.scales.y.ticks.font.size = isMobile ? 8 : (isTablet ? 9 : 10);
        chartSuara4.options.scales.x.ticks.font.size = isMobile ? 8 : (isTablet ? 9 : 10);
        chartSuara4.options.plugins.tooltip.titleFont.size = isTablet ? 10 : 12;
        chartSuara4.options.plugins.tooltip.bodyFont.size = isTablet ? 9 : 11;
        chartSuara4.options.layout.padding = {
            top: 10,
            right: isMobile ? 5 : 10,
            left: isMobile ? 5 : 10,
            bottom: 10
        };
        chartSuara4.update();
    }

    window.addEventListener('resize', handleResize);
    window.addEventListener('orientationchange', handleResize);
</script>
<script>
    // Set the date we're counting down to (1 month from now)
    <?php
    $date = $pemilihan['tanggal_akhir'];
    $date = date('Y-m-d H:i:s', strtotime($date));
    $date = new DateTime($date);
    $date = $date->format('Y-m-d H:i:s');
    $date = strtotime($date) * 1000; // Convert to milliseconds
    ?>
    const countDownDate = <?= $date ?>;

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
        document.getElementById("days").innerHTML = days.toString().padStart(2, '0');
        document.getElementById("hours").innerHTML = hours.toString().padStart(2, '0');
        document.getElementById("minutes").innerHTML = minutes.toString().padStart(2, '0');
        document.getElementById("seconds").innerHTML = seconds.toString().padStart(2, '0');

        // If the countdown is finished, write some text
        if (distance < 0) {
            clearInterval(x);
            document.getElementById("days").innerHTML = "00";
            document.getElementById("hours").innerHTML = "00";
            document.getElementById("minutes").innerHTML = "00";
            document.getElementById("seconds").innerHTML = "00";
        }
    }, 1000);
</script>
<?= $this->endSection() ?>