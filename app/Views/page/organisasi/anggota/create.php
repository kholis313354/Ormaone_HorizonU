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
                <h3><?= $title ?></h3>
                <p class="text-subtitle text-muted"></p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= url_to('dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="<?= url_to('organisasi.anggota.index') ?>">Anggota</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <?= $title ?>
                </h5>
            </div>
            <div class="card-body">
                <?php include_once(APPPATH . 'Views/components/errors.php'); ?>
                <?php include_once(APPPATH . 'Views/components/flash.php'); ?>
                <form action="<?= url_to('organisasi.anggota.store') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label for="name" class="form-label">Organisasi</label>
                        <select name="organisasi_id" id="name" class="form-select">
                            <option value="">--Pilih Organisasi--</option>
                            <?php foreach ($organisasis as $organisasi) : ?>
                                <option value="<?= $organisasi['id'] ?>"><?= $organisasi['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- NIM, NAME, EMAIL, KELAS, JURUSAN, PHONE, NOTES, PASSWORD -->
                    <div class="mb-3">
                        <label for="nim" class="form-label">NIM</label>
                        <input type="text" class="form-control" id="nim" name="nim" placeholder="NIM" required>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Name" required>
                    </div>
                    <div class="mb-3">
                        <label for="kelas" class="form-label">Fakultas</label>
                        <input type="text" class="form-control" id="kelas" name="kelas" placeholder="Fakultas" required>
                    </div>
                    <div class="mb-3">
                        <label for="jurusan" class="form-label">Prodi</label>
                        <input type="text" class="form-control" id="jurusan" name="jurusan" placeholder="Prodi" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Nomor</label>
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Nomor" required>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="<?= url_to('organisasi.anggota.index') ?>" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?>