<?= $this->extend('components/layouts/app') ?>\
<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
        <i class="bi bi-justify fs-3"></i>
    </a>
</header>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Dashboard</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                <div class="stats-icon purple mb-2">
                                    <i class="bi-people"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Total Anggota</h6>
                                <h6 class="font-extrabold mb-0"><?= $totalAnggota ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                <div class="stats-icon blue mb-2">
                                    <i class="iconly-boldProfile"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Total Pemilihan</h6>
                                <h6 class="font-extrabold mb-0"><?= $totalPemilihan ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                <div class="stats-icon green mb-2">
                                    <i class="iconly-boldAdd-User"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Total Calon</h6>
                                <h6 class="font-extrabold mb-0"><?= $totalCalon ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                <div class="stats-icon red mb-2">
                                    <i class="bi-reception-4"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Total Suara</h6>
                                <h6 class="font-extrabold mb-0"><?= $totalSuara ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section">
        <div class="row">
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                <div class="stats-icon bg-success  mb-2">
                                    <i class="bi-person-workspace"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Program kerja</h6>
                                <h6 class="font-extrabold mb-0">0</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                <div class="stats-icon bg-warning mb-2">
                                    <i class="bi-file-earmark-text"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Total E-Sertifikat</h6>
                                <h6 class="font-extrabold mb-0">0</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                <div class="stats-icon bg-info mb-2">
                                    <i class="bi-bootstrap"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Total Blogger</h6>
                                <h6 class="font-extrabold mb-0">0</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                <div class="stats-icon bg-danger mb-2">
                                    <i class="iconly-boldBookmark"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Total Notulen</h6>
                                <h6 class="font-extrabold mb-0">0</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- batas card -->
<!-- <div class="row">
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card bg-brown text-white mb-4">
            <div class="card-body d-flex align-items-center">
                <i class="fas fa-users fa-2x me-3"></i>
                <div>
                    <div class="fw-semibold">Total Anggota</div>
                    <div class="fs-4 fw-bold">260</div>
                </div>
            </div>

        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bg-brown text-white mb-4">
            <div class="card-body d-flex align-items-center">

                <i class="fas fa-id-card fa-2x me-3"></i>
                <div>
                    <div class="fw-semibold">Anggota Aktif</div>
                    <div class="fs-4 fw-bold">230</div>
                </div>
            </div>

        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bg-brown text-white mb-4">
            <div class="card-body d-flex align-items-center">

                <i class="fas fa-briefcase fa-2x me-3"></i>
                <div>
                    <div class="fw-semibold">Program Kerja</div>
                    <div class="fs-4 fw-bold">5</div>
                </div>
            </div>

        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bg-brown text-white mb-4">
            <div class="card-body d-flex align-items-center">

                <i class="fab fa-blogger-b fa-2x me-3"></i>
                <div>
                    <div class="fw-semibold">Artikel Blog</div>
                    <div class="fs-4 fw-bold">20</div>
                </div>
            </div>

        </div>
    </div>


</div> -->
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
<h1 class="mt-4"></h1>
<ol class="breadcrumb mb-4">
</ol>


<div class="card-body mb-4">
    <div class="row">
        <div class="col-xl-4">
            <div class="bg-white p-4 rounded-4 shadow-sm">
                <h6 class="fw-semibold mb-3">Agenda Mendatang</h6>

                <!-- Item Agenda -->
                <div class="d-flex justify-content-between align-items-center bg-light rounded-4 p-3 mb-3 shadow-sm">
                    <div class="d-flex align-items-center">
                        <i class="far fa-calendar-alt fa-lg me-3 text-success"></i>
                        <div>
                            <div class="fw-semibold text-dark">Rapat Umum Anggota</div>
                            <div class="text-muted" style="font-size: 0.85rem;">20 Februari 2024 • 09:00 WIB</div>
                        </div>
                    </div>
                    <div class="text-muted fw-semibold">Lihat Detail</div>
                </div>

                <!-- Copy Paste untuk item lainnya -->
                <div class="d-flex justify-content-between align-items-center bg-light rounded-4 p-3 mb-3 shadow-sm">
                    <div class="d-flex align-items-center">
                        <i class="far fa-calendar-alt fa-lg me-3 text-success"></i>
                        <div>
                            <div class="fw-semibold text-dark">Rapat Umum Anggota</div>
                            <div class="text-muted" style="font-size: 0.85rem;">20 Februari 2024 • 09:00 WIB</div>
                        </div>
                    </div>
                    <div class="text-muted fw-semibold">Lihat Detail</div>
                </div>

                <div class="d-flex justify-content-between align-items-center bg-light rounded-4 p-3 shadow-sm">
                    <div class="d-flex align-items-center">
                        <i class="far fa-calendar-alt fa-lg me-3 text-success"></i>
                        <div>
                            <div class="fw-semibold text-dark">Rapat Umum Anggota</div>
                            <div class="text-muted" style="font-size: 0.85rem;">20 Februari 2024 • 09:00 WIB</div>
                        </div>
                    </div>
                    <div class="text-muted fw-semibold">Lihat Detail</div>
                </div>

            </div>
        </div>
        <div class="col-xl-4">
            <div class="bg-white p-4 rounded-4 shadow-sm">
                <h6 class="fw-semibold mb-3">Notulen Hasil Rapat</h6>

                <!-- Item Notulen -->
                <div class="d-flex justify-content-between align-items-center bg-light rounded-4 p-3 mb-3 shadow-sm">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-calendar-check fa-lg me-3 text-danger"></i>
                        <div>
                            <div class="fw-semibold text-dark text-truncate" style="max-width: 200px;">
                                Hasil Rapat Hari Rabu P...
                            </div>
                            <div class="text-muted" style="font-size: 0.85rem;">20 Juni 2025 • 10.00 WIB</div>
                        </div>
                    </div>
                    <div class="text-muted fw-semibold">Lihat Detail</div>
                </div>

                <!-- Salin item untuk 3 notulen lainnya -->
                <div class="d-flex justify-content-between align-items-center bg-light rounded-4 p-3 mb-3 shadow-sm">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-calendar-check fa-lg me-3 text-danger"></i>
                        <div>
                            <div class="fw-semibold text-dark text-truncate" style="max-width: 200px;">
                                Hasil Rapat Hari Rabu P...
                            </div>
                            <div class="text-muted" style="font-size: 0.85rem;">20 Juni 2025 • 10.00 WIB</div>
                        </div>
                    </div>
                    <div class="text-muted fw-semibold">Lihat Detail</div>
                </div>

                <div class="d-flex justify-content-between align-items-center bg-light rounded-4 p-3 mb-3 shadow-sm">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-calendar-check fa-lg me-3 text-danger"></i>
                        <div>
                            <div class="fw-semibold text-dark text-truncate" style="max-width: 200px;">
                                Hasil Rapat Hari Rabu P...
                            </div>
                            <div class="text-muted" style="font-size: 0.85rem;">20 Juni 2025 • 10.00 WIB</div>
                        </div>
                    </div>
                    <div class="text-muted fw-semibold">Lihat Detail</div>
                </div>

                <div class="d-flex justify-content-between align-items-center bg-light rounded-4 p-3 shadow-sm">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-calendar-check fa-lg me-3 text-danger"></i>
                        <div>
                            <div class="fw-semibold text-dark text-truncate" style="max-width: 200px;">
                                Hasil Rapat Hari Rabu P...
                            </div>
                            <div class="text-muted" style="font-size: 0.85rem;">20 Juni 2025 • 10.00 WIB</div>
                        </div>
                    </div>
                    <div class="text-muted fw-semibold">Lihat Detail</div>
                </div>

            </div>
        </div>
        <div class="col-xl-4">
            <div class="bg-white rounded-4 shadow-sm d-flex justify-content-center align-items-center"
                style="height: 250px;">
                <div class="text-center text-dark fw-semibold">
                    <i class="fas fa-bullhorn me-2 text-secondary"></i> <!-- Optional icon -->
                    New Update
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script src=" https://cdn.jsdelivr.net/npm/chart.js@4.4.9/dist/chart.umd.min.js "></script>
<script>
    var ctxY = document.getElementById('chart-voting').getContext('2d');
    var labels = [];
    var datas = [];

    <?php foreach ($totalSuaras as $key => $val): ?>
        labels.push('<?= $key ?>');
        datas.push(<?= $val ?>);
    <?php endforeach; ?>

    // Tambahkan 5 warna berbeda
    var backgroundColors = [
        '#980517', // biru
        '#1cc88a', // hijau
        '#36b9cc', // biru muda
        '#f6c23e', // kuning
        '#e74a3b' // merah
    ];

    // Sesuaikan jumlah warna dengan jumlah data (ulang jika data > 5)
    var dynamicColors = datas.map((_, i) => backgroundColors[i % backgroundColors.length]);

    var chartSuara4 = new Chart(ctxY, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Suara',
                data: datas,
                borderRadius: 5,
                backgroundColor: dynamicColors
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

<?= $this->endSection() ?>