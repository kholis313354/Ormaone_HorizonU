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
            <img src="<?= base_url('dist/landing/assets/img/Vector.png') ?>" class="img-fluid" alt="">
            <h1 class="fw-bolder" style="color: #980517;">E-Voting</h1>
        </div>
    </div>
</header>
<?php include_once(APPPATH . 'Views/components/errors.php'); ?>
<?php include_once(APPPATH . 'Views/components/flash.php'); ?>

<!-- Card content-->
<div class="card mb-2">
    <div class="card-body">
        <div class="card-title title-body-chart">Statistik Voting ORMAWA</div>
        <div class="row">
            <!-- Bagian Chart -->
            <div class="col-xl-5 col-md-12 chart-arrow">
                <div id="chart-container">
                    <div class="chart" id="chart1">
                        <div class="card card-chart mb-1">
                            <h6 class="fw-semibold">Jumlah Suara Voting</h6>
                            <canvas id="chart-voting" height="250px"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bagian Data Voting -->
            <div class="col-xl-6 col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <!-- Header dan Pencarian -->
                        <div
                            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
                            <h4 class="fw-bold" style="color: #980517;">Data Voting</h4>
                        </div>

                        <!-- Table Responsive -->
                        <div class="table-responsive">
                            <table class="table" id="datatable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>NIM</th>
                                        <th>Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    <?php foreach ($suara as $s) : ?>
                                        <tr>
                                            <td style="color: #6c757d;"><?= $no ?></td>
                                            <td style="color: #6c757d;"><?= $s['name'] ?></td>
                                            <td style="color: #6c757d;"><?= $s['nim'] ?></td>
                                            <td style="color: #6c757d;"><?= $s['email'] ?></td>
                                        </tr>
                                        <?php $no++; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nimInput = document.getElementById('nim');
        const kodeFakultasInput = document.getElementById('kode_fakultas');

        if (nimInput) {
            nimInput.addEventListener('input', function() {
                const nim = this.value;
                if (nim.length === 16) {
                    const kodeFakultas = nim.substring(5, 10);
                    kodeFakultasInput.value = kodeFakultas;
                } else {
                    kodeFakultasInput.value = '';
                }
            });
        }
    });
</script>
<!-- Core theme JS-->


</section><!-- /Hero Section -->

<!-- More Features Section -->
<section id="more-features" class="more-features section">

    <div class="container">

        <div class="row justify-content-around gy-4">

            <div class="col-lg-6 d-flex flex-column justify-content-center order-2 order-lg-1"
                data-aos="fade-up" data-aos-delay="100">
                <h3><?= $calon['anggota_1_name'] ?> - <?= $calon['anggota_2_name'] ?></h3>
                <p><?= $calon['description'] ?></p>

                <!-- <div class="row">
                        <div class="col-lg-6 icon-box d-flex">
                            <div>
                                <h4>Biodata</h4>
                                <p><i class="bi bi-bank2 fs-6"></i>Prodi Sistem informasi</p>
                                <p><i class="bi bi-mortarboard fs-6"></i>Angkatan : 2023</p>

                            </div>
                        </div>
                        <div class="col-lg-6 icon-box d-flex">
                            <i class="bi bi-list-ul flex-shrink-0"></i>
                            <div>
                                <h4>Pengalaman Organisasi</h4>
                                <p>1.Wakil Himpunan Mahasiswa (HIMA) 2023
                                </p>
                                <p>2.Koordinator Divisi Akademik 2024</p>
                            </div>
                        </div>
                        <div class="col-lg-6 icon-box d-flex">
                            <i class="bi bi-rocket flex-shrink-0"></i>
                            <div>
                                <h4>Visi</h4>
                                <p>Mewujudkan BEM yang inklusif, progresif, dan berorientasi pada kepentingan
                                    mahasiswa.</p>
                            </div>
                        </div>
                        <div class="col-lg-6 icon-box d-flex">
                            <i class="bi bi-bullseye flex-shrink-0"></i>
                            <div>
                                <h4>Misi</h4>
                                <p>1.Mengembangkan program-program inovatif</p>
                                <p>2.Meningkatkan keterlibatan Mahasiswa</p>
                                <p>3.Memperkuat kerjasama dengan stakeholders</p>
                            </div>
                        </div>
                    </div> -->
            </div>

            <div class="features-image col-lg-5 order-1 order-lg-2" data-aos="fade-up" data-aos-delay="200">
                <img src="<?= base_url('uploads/' . $calon['gambar_1']) ?>" alt="">
            </div>

        </div>

    </div>
    <div class="categories-content rounded-bottom p-4">
        <div class="d-flex justify-content-center">
            <a href="#" id="voteButton" class="btn btn-sm rounded-pill d-flex justify-content-center py-2 px-4"
                style="background-color: #980517; color: #fff; width: fit-content;" data-bs-toggle="modal" data-bs-target="#modalCalon" onclick="document.getElementById('pemilihan_calon_id').value = <?= $calon['id'] ?>;">
                Vote Sekarang
            </a>
        </div>
        <!-- Script untuk SweetAlert Form -->
    </div>
</section>
<!-- /More Features Section -->
<div class="modal fade" id="modalCalon" tabindex="-1" role="dialog" aria-labelledby="modalCalonLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex justify-content-center align-item-center">
                    <img src="<?= base_url('dist/landing/assets/img/Vector.png') ?>" class="img-fluid" alt="">
                </div>
                <h5 class="modal-title" id="modalCalonLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?= url_to('vote') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <input type="hidden" name="pemilihan_calon_id" id="pemilihan_calon_id">
                    <!-- <div class="mb-3"> -->
                    <input type="hidden" class="form-control" id="kode_fakultas" name="kode_fakultas" placeholder="Kode Fakultas" required>
                    <!-- </div> -->
                    <div class="mb-3">
                        <label for="nim" class="form-label">NIM</label>
                        <input type="text" class="form-control" id="nim" name="nim" placeholder="NIM" maxlength="16" minlength="16" required>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Vote</button>
                </form>
            </div>
        </div>
    </div>
</div>
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


<?= $this->endSection() ?>