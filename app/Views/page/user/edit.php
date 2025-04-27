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
                            <li class="breadcrumb-item" aria-current="page"><a href="<?= url_to('user.index') ?>">User</a></li>
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
                    <form action="<?= url_to('user.update', $data['id']) ?>" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="_method" value="PUT">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Nama" required value="<?= $data['name'] ?>">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Level</label>
                            <select name="level" id="level" class="form-select">
                                <option value="0" <?= $data['level'] == '0' ? 'selected' : '' ?>>Anggota</option>
                                <option value="1" <?= $data['level'] == '1' ? 'selected' : '' ?>>Admin</option>
                                <option value="2" <?= $data['level'] == '2' ? 'selected' : '' ?>>User</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Email</label>
                            <input type="text" class="form-control" id="email" name="email" placeholder="Email" required value="<?= $data['email'] ?>">
                        </div>
                        <div class="mb-3">
                            <label for="logo" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="*****">
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="<?= url_to('user.index') ?>" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>
        </section>
    </div>
<?= $this->endSection() ?>