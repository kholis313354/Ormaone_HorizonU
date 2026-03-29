<?= $this->extend('components/layouts/app') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
    /* Reuse styles from create.php */
    /* Improved styling consistent with structure create/edit */
    .struktur-item {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        background: #f8f9fa;
        position: relative;
    }
    
    .struktur-item h6 {
        color: #4e73df;
        font-weight: 700;
        margin-bottom: 20px;
        border-bottom: 2px solid #4e73df;
        padding-bottom: 10px;
        display: inline-block;
    }
    
    .preview-image {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 10px;
        border: 3px solid #dee2e6;
        display: block;
        margin-top: 10px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
    }

    /* Dark Mode Improvements */
    [data-bs-theme="dark"] .card {
        background: #1e1e2d;
        border-color: #4b4b5a;
        color: #e2e2e2;
    }
    
    [data-bs-theme="dark"] .card-header {
        border-bottom-color: #4b4b5a;
        background: transparent;
    }
    
    [data-bs-theme="dark"] .card-title {
        color: #e2e2e2;
    }
    
    [data-bs-theme="dark"] .struktur-item {
        background: #2b2b40;
        border-color: #4b4b5a;
    }
    
    [data-bs-theme="dark"] .struktur-item h6 {
        color: #6993ff;
        border-bottom-color: #6993ff;
    }
    
    [data-bs-theme="dark"] .preview-image {
        border-color: #4b4b5a;
    }
    
    [data-bs-theme="dark"] .text-muted {
        color: #a0a0a0 !important;
    }
    
    /* Form controls in dark mode */
    [data-bs-theme="dark"] .form-control,
    [data-bs-theme="dark"] .form-select {
        background-color: #151521;
        border-color: #4b4b5a;
        color: #e2e2e2;
    }
    
    [data-bs-theme="dark"] .form-control:focus,
    [data-bs-theme="dark"] .form-select:focus {
        background-color: #151521;
        border-color: #4e73df;
        color: #e2e2e2;
    }
    
    [data-bs-theme="dark"] .form-control[readonly] {
        background-color: #2b2b40 !important;
        color: #a0a0a0; 
    }
</style>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3><?= $title ?></h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= url_to('dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= url_to('struktur.index') ?>">Struktur</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Tambah Divisi (Maksimal 3 Divisi per Periode)</h5>
            </div>
            <div class="card-body">
                <?php include_once(APPPATH . 'Views/components/errors.php'); ?>
                <?php include_once(APPPATH . 'Views/components/flash.php'); ?>
                <form action="<?= url_to('struktur.divisi.store') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label for="organisasi_id" class="form-label">Organisasi</label>
                        <!-- Reuse organization selection logic -->
                        <?php 
                        $sessionOrgId = session()->get('organisasi_id');
                        $level = session()->get('level');
                        $organisasiName = '';
                        
                        if (isset($currentOrganisasi) && !empty($currentOrganisasi['name'])) {
                            $organisasiName = $currentOrganisasi['name'];
                            $finalOrgId = $currentOrganisasi['id'];
                        } elseif (isset($organisasis) && isset($organisasis[0])) {
                            $organisasiName = $organisasis[0]['name'];
                            $finalOrgId = $organisasis[0]['id'];
                        }
                        ?>
                        
                        <?php if ($level == 1 && isset($organisasis) && count($organisasis) > 1): ?>
                            <select name="organisasi_id" id="organisasi_id" class="form-select" required>
                                <option value="" disabled selected>Pilih Organisasi</option>
                                <?php foreach ($organisasis as $org): ?>
                                    <option value="<?= $org['id'] ?>"><?= esc($org['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php else: ?>
                            <input type="text" class="form-control" value="<?= esc($organisasiName) ?>" readonly style="background-color: #e9ecef; cursor: not-allowed;">
                            <input type="hidden" name="organisasi_id" value="<?= isset($finalOrgId) ? (int)$finalOrgId : '' ?>" required>
                        <?php endif; ?>
                    </div>
                    
                    <div class="row mb-3">
                         <div class="col-md-12 mb-3">
                            <label for="nama_divisi" class="form-label">Nama Divisi</label>
                            <input type="text" class="form-control" id="nama_divisi" name="nama_divisi" 
                                   placeholder="Contoh: Divisi Kominfo" 
                                   value="<?= old('nama_divisi') ?>" required maxlength="100">
                        </div>
                        <div class="col-md-6">
                            <label for="tahun" class="form-label">Tahun</label>
                            <input type="text" class="form-control" id="tahun" name="tahun" 
                                   placeholder="Contoh: 2024/2025" 
                                   value="<?= old('tahun') ?>" required maxlength="50">
                        </div>
                        <div class="col-md-6">
                            <label for="periode" class="form-label">Periode</label>
                            <input type="text" class="form-control" id="periode" name="periode" 
                                   placeholder="Contoh: HUSC 2024-2025" 
                                   value="<?= old('periode') ?>" maxlength="100">
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    <h5 class="mb-4">Ketua Divisi</h5>
                    
                    <div class="struktur-item">
                        <h6>Ketua Divisi</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="gambar_ketua" class="form-label">Gambar</label>
                                    <input type="file" class="form-control" id="gambar_ketua" name="gambar_ketua" 
                                           accept="image/jpeg,image/png,image/jpg" onchange="previewImage(this, 'ketua')">
                                    <div id="preview_ketua" class="mt-2"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="nama_ketua" class="form-label">Nama</label>
                                    <input type="text" class="form-control" id="nama_ketua" name="nama_ketua" 
                                           placeholder="Nama Ketua" value="<?= old('nama_ketua') ?>" maxlength="100">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="jabatan_ketua" class="form-label">Jabatan</label>
                                    <input type="text" class="form-control" id="jabatan_ketua" name="jabatan_ketua" 
                                           placeholder="Jabatan (e.g. Ketua Divisi)" value="<?= old('jabatan_ketua') ?>" maxlength="100">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="prodi_ketua" class="form-label">Prodi</label>
                                    <input type="text" class="form-control" id="prodi_ketua" name="prodi_ketua" 
                                           placeholder="Program Studi" value="<?= old('prodi_ketua') ?>" maxlength="100">
                                </div>
                            </div>
                        </div>
                    </div>

                    <h5 class="mb-4">Anggota Divisi (Maksimal 8)</h5>
                    
                    <?php for ($i = 1; $i <= 8; $i++): ?>
                    <div class="struktur-item">
                        <h6>Anggota <?= $i ?></h6>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="gambar_anggota_<?= $i ?>" class="form-label">Gambar</label>
                                    <input type="file" class="form-control" id="gambar_anggota_<?= $i ?>" name="gambar_anggota_<?= $i ?>" 
                                           accept="image/jpeg,image/png,image/jpg" onchange="previewImage(this, 'anggota_<?= $i ?>')">
                                    <div id="preview_anggota_<?= $i ?>" class="mt-2"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="nama_anggota_<?= $i ?>" class="form-label">Nama</label>
                                    <input type="text" class="form-control" id="nama_anggota_<?= $i ?>" name="nama_anggota_<?= $i ?>" 
                                           placeholder="Nama Anggota" value="<?= old('nama_anggota_' . $i) ?>" maxlength="100">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="jabatan_anggota_<?= $i ?>" class="form-label">Jabatan</label>
                                    <input type="text" class="form-control" id="jabatan_anggota_<?= $i ?>" name="jabatan_anggota_<?= $i ?>" 
                                           placeholder="Jabatan" value="<?= old('jabatan_anggota_' . $i) ?>" maxlength="100">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="prodi_anggota_<?= $i ?>" class="form-label">Prodi</label>
                                    <input type="text" class="form-control" id="prodi_anggota_<?= $i ?>" name="prodi_anggota_<?= $i ?>" 
                                           placeholder="Program Studi" value="<?= old('prodi_anggota_' . $i) ?>" maxlength="100">
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endfor; ?>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="<?= url_to('struktur.index') ?>" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<script>
function previewImage(input, idSuffix) {
    const preview = document.getElementById('preview_' + idSuffix);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = '<img src="' + e.target.result + '" class="preview-image" alt="Preview">';
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.innerHTML = '';
    }
}
</script>
<?= $this->endSection() ?>
