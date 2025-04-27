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
                            <li class="breadcrumb-item" aria-current="page"><a href="<?= url_to('organisasi.kepengurusan.index') ?>">Organisasi</a></li>
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
                    <form action="<?= url_to('organisasi.kepengurusan.store') ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="name" class="form-label">Organisasi</label>
                            <select name="organisasi_id" id="organsisasi" class="form-select">
                                <option value="">--Pilih Organisasi--</option>
                                <?php foreach ($organisasis as $organisasi) : ?>
                                    <option value="<?= $organisasi['id'] ?>"><?= $organisasi['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Anggota</label>
                            <select name="anggota_id" id="anggota" class="form-select">
                                <option value="">--Pilih Anggota--</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="jabatan" class="form-label">Jabatan</label>
                            <input type="text" class="form-control" id="jabatan" name="jabatan" placeholder="Jabatan" required>
                        </div>
                        <div class="mb-3">
                            <label for="periode" class="form-label">Periode</label>
                            <input type="text" class="form-control" id="periode" name="periode" placeholder="Periode" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="<?= url_to('organisasi.kepengurusan.index') ?>" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>
        </section>
    </div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const organisasiSelect = document.getElementById('organsisasi');
            const anggotaSelect = document.getElementById('anggota');

            organisasiSelect.addEventListener('change', function() {
                const organisasiId = this.value;

                // Clear the anggota select
                anggotaSelect.innerHTML = '<option value="">--Pilih Anggota--</option>';

                if (organisasiId) {
                    fetch(`<?= base_url('organisasi/kepengurusan/anggota') ?>/${organisasiId}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(anggota => {
                                const option = document.createElement('option');
                                option.value = anggota.id;
                                option.textContent = `${anggota.nim} - ${anggota.name}`;
                                anggotaSelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error fetching anggota:', error));
                }
            });
        });
    </script>
<?= $this->endSection() ?>