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
                            <div class="card-header bg-primary text-white">
                                <h4 class="mb-0 text-center">Countdown Timer</h4>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-3">
                                        <h3 id="days" class="display-4">00</h3>
                                        <p>Days</p>
                                    </div>
                                    <div class="col-3">
                                        <h3 id="hours" class="display-4">00</h3>
                                        <p>Hours</p>
                                    </div>
                                    <div class="col-3">
                                        <h3 id="minutes" class="display-4">00</h3>
                                        <p>Minutes</p>
                                    </div>
                                    <div class="col-3">
                                        <h3 id="seconds" class="display-4">00</h3>
                                        <p>Seconds</p>
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
<div class="card mb-4">
    <div class="card-body">
        <div class="card-title title-body-chart">Statistik Voting ORMAWA</div>
        <div class="row">

            <div class="col-xl-12 col-md-12 chart-arrow">
                <div id="chart-container">
                    <div class="chart" id="chart1">
                        <div class="card card-chart mb-1">
                            <h6 class="fw-semibold">Jumlah Suara Voting</h6>
                            <canvas id="chart-voting" height="250px"></canvas>
                        </div>
                    </div>
                </div>
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
            <h1 class="display-5 text-capitalize mb-3">Kandidat <span style="color: #980517;">BEM
                    Universitas</span>
            </h1>
        </div>
        <div class="categories-carousel owl-carousel wow fadeInUp" data-wow-delay="0.1s">
            <?php foreach ($calon as $c) : ?>
                <div class="categories-item p-4">
                    <div class="categories-item-inner">
                        <div class="categories-img rounded-top" style="background-color: #980517">
                            <img src="<?= base_url('uploads/' . $c['gambar_1']) ?>" class="img-fluid w-100 rounded-top" alt="">
                        </div>
                        <div class="categories-content rounded-bottom p-4">

                            <a href="#" class="h4 d-block mb-3"><?= $c['anggota_1_name'] ?> - <?= $c['anggota_2_name'] ?></a>
                            <div class="categories-review mb-4">
                                <p class="mb-3">"<?= $c['description'] ?>"</p>
                            </div>
                            <a href="<?= url_to('voting.detail', $c['id']) ?>" class="btn  rounded-pill d-flex justify-content-center py-3"
                                style=" background-color: #980517; color: #fff;">
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
<script>
    var ctxY = document.getElementById('chart-voting').getContext('2d');
    var labels = [];
    var datas = [];
    <?php foreach ($totalSuaras as $key => $val): ?>
        labels.push('<?= $key ?>');
        datas.push(<?= $val ?>);
    <?php endforeach; ?>
    var chartSuara4 = new Chart(ctxY, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Suara',
                data: datas,
                borderRadius: 5,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 50
                    }
                }
            }
        }
    });
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