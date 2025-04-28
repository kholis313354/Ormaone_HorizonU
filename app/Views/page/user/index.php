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
                    <?= $title ?>
                </h5>
            </div>
            <div class="card-body">
                <?php include_once(APPPATH . 'Views/components/errors.php'); ?>
                <?php include_once(APPPATH . 'Views/components/flash.php'); ?>
                <a href="<?= url_to('user.create') ?>" class="btn btn-success mb-3">
                    <i class="bi bi-plus-circle"></i>
                    Create
                </a>
                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>Role</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        function level($item)
                        {
                            return match ($item) {
                                '0' => 'Organisasi - Fakultas',
                                '1' => 'CSDL',
                                '2' => 'BEM Universitas',
                                default => 'Unknown'
                            };
                        }
                        ?>
                        <?php foreach ($data as $item) : ?>
                            <tr>
                                <td><?= level($item['level']) ?></td>
                                <td><?= $item['name'] ?></td>
                                <td><?= $item['email'] ?></td>
                                <td><?= $item['created_at'] ?></td>
                                <td>
                                    <a href="<?= url_to('user.edit', $item['id']) ?>" class="btn btn-primary">
                                        <i class="bi bi-pencil-square"></i>
                                        Edit
                                    </a>
                                    <form action="<?= url_to('user.delete', $item['id']) ?>" method="POST" class="d-inline">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-danger">
                                            <i class="bi bi-trash"></i>
                                            Delete
                                        </button>
                                    </form>
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