<?= $this->extend('components/layouts/app') ?>

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
                <h3>Pemilihan</h3>
                <p class="text-subtitle text-muted">
                    Pilih salah satu pasangan calon yang ada pada pemilihan ini.
                </p>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Calon</h4>
            </div>
            <div class="card-body">
                <?php include_once(APPPATH . 'Views/components/errors.php'); ?>
                <?php include_once(APPPATH . 'Views/components/flash.php'); ?>
                <div class="row">
                    <?php foreach ($calon as $key => $c) : ?>
                        <div class="col-md-12 mt-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3>Pasangan <?= $c['number']  ?>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-center align-items-center flex-column">
                                                <!-- <div class="avatar avatar-2xl">
                                                        <img src="./assets/compiled/jpg/2.jpg" alt="Avatar">
                                                    </div> -->

                                                <h3 class="mt-3"><?= $c['anggota_1_name'] ?></h3>
                                                <p class="text-small"><?= $c['anggota_1_nim'] ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-center align-items-center flex-column">
                                                <!-- <div class="avatar avatar-2xl">
                                                        <img src="./assets/compiled/jpg/2.jpg" alt="Avatar">
                                                    </div> -->

                                                <h3 class="mt-3"><?= $c['anggota_2_name'] ?></h3>
                                                <p class="text-small"><?= $c['anggota_2_nim'] ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <p class="">
                                        Keterangan : <br>
                                        <?= $c['description'] ?>
                                    </p>
                                    <!-- Bootstrap 5 open modal -->
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCalon" onclick="document.getElementById('pemilihan_calon_id').value = <?= $c['id'] ?>;">
                                        <i class="bi bi-check-circle"></i>
                                        Pilih
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal with form pemilihan_calon_id, nim. name. email -->
<div class="modal fade" id="modalCalon" tabindex="-1" role="dialog" aria-labelledby="modalCalonLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCalonLabel">Form Pemilihan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?= url_to('vote') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <input type="hidden" name="pemilihan_calon_id" id="pemilihan_calon_id">
                    <div class="mb-3">
                        <label for="nim" class="form-label">NIM</label>
                        <input type="text" class="form-control" id="nim" name="nim" placeholder="NIM" required>
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