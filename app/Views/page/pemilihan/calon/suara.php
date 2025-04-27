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
                    <?= $title ?> Calon <?= $pemilihan['number'] ?> (<?= $pemilihan['anggota_1_name'] ?> - <?= $pemilihan['anggota_2_name'] ?>): <?= count($data) ?>
                </h5>
            </div>
            <div class="card-body">
                <?php include_once(APPPATH . 'Views/components/errors.php'); ?>
                <?php include_once(APPPATH . 'Views/components/flash.php'); ?>
                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>Kode Fakultas</th>
                            <th>Nim</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>IP</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $item) : ?>
                            <tr>
                                <td><?= $item['kode_fakultas'] ?></td>
                                <td><?= $item['nim'] ?></td>
                                <td><?= $item['name'] ?></td>
                                <td><?= $item['email'] ?></td>
                                <td><?= $item['ip_address'] ?></td>
                                <td>
                                    <?php if ($item['status'] == 1) : ?>
                                        <span class="badge bg-success">Verified</span>
                                    <?php else : ?>
                                        <span class="badge bg-danger">Not Verified</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($item['status'] == 1) : ?>
                                        <a href="<?= url_to('pemilihan.calon.suara.revoke', $pemilihan['id'], $item['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to revoke this vote?')">Revoke</a>
                                    <?php else : ?>
                                        <a href="<?= url_to('pemilihan.calon.suara.confirm', $pemilihan['id'], $item['id']) ?>" class="btn btn-primary btn-sm">Verify</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?>