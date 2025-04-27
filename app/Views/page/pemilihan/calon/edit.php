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
                        <li class="breadcrumb-item" aria-current="page"><a href="<?= url_to('pemilihan.calon.index') ?>">Pemilihan</a></li>
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
                <form action="<?= url_to('pemilihan.calon.update', $data['id']) ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="_method" value="PUT">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label for="name" class="form-label">Pemilihan</label>
                        <select name="pemilihan_id" id="pemilihan" class="form-select" required>
                            <option value="">--Pilih Pemilihan--</option>
                            <?php foreach ($pemilihans as $pemilihan) : ?>
                                <option value="<?= $pemilihan['id'] ?>"><?= $pemilihan['organisasi_name'] ?> - <?= $pemilihan['periode'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Calom 1</label>
                        <select name="anggota_id_1" id="anggota_id_1" class="form-select" required>
                            <option value="">--Pilih Anggota--</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Calom 2</label>
                        <select name="anggota_id_2" id="anggota_id_2" class="form-select" required>
                            <option value="">--Pilih Anggota--</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="logo" class="form-label">Gambar 1</label>
                        <input type="file" class="form-control" id="gambar_1" name="gambar_1">
                    </div>
                    <div class="mb-3">
                        <label for="logo" class="form-label">Gambar 2</label>
                        <input type="file" class="form-control" id="gambar_2" name="gambar_2">
                    </div>
                    <div class="mb-3">
                        <label for="periode" class="form-label">Number</label>
                        <input type="number" class="form-control" id="number" name="number" placeholder="Number" required value="<?= old('number', $data['number']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="jabatan" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="jabatan" name="description" placeholder="Deskripsi" required><?= old('description', $data['description']) ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="<?= url_to('pemilihan.calon.index') ?>" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const pemilihanSelect = document.getElementById('pemilihan');
        const anggota1Select = document.getElementById('anggota_id_1');
        const anggota2Select = document.getElementById('anggota_id_2');

        pemilihanSelect.addEventListener('change', function() {
            const pemilihanId = this.value;

            // Clear the anggota select
            anggota1Select.innerHTML = '<option value="">--Pilih Anggota--</option>';
            anggota2Select.innerHTML = '<option value="">--Pilih Anggota--</option>';

            if (pemilihanId) {
                fetch(`<?= base_url('pemilihan/calon/anggota') ?>/${pemilihanId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(anggota => {
                            const option = document.createElement('option');
                            option.value = anggota.id;
                            option.textContent = `${anggota.nim} - ${anggota.name}`;
                            anggota1Select.appendChild(option);
                            anggota2Select.appendChild(option.cloneNode(true));
                        });
                    })
                    .catch(error => console.error('Error fetching anggota:', error));
            }
        });

        setTimeout(() => {
            const selectedPemilihanId = '<?= $data['pemilihan_id'] ?>';
            pemilihanSelect.value = selectedPemilihanId;
            pemilihanSelect.dispatchEvent(new Event('change'));
            setTimeout(() => {
                const selectedAnggota1Id = '<?= $data['anggota_id_1'] ?>';
                const selectedAnggota2Id = '<?= $data['anggota_id_2'] ?>';
                anggota1Select.value = selectedAnggota1Id;
                anggota1Select.dispatchEvent(new Event('change'));
                anggota2Select.value = selectedAnggota2Id;
                anggota2Select.dispatchEvent(new Event('change'));
            }, 500);
        }, 1000);
    });
</script>
<?= $this->endSection() ?>